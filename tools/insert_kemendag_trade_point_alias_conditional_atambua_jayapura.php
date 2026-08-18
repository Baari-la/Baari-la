<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use RuntimeException;

echo "========================================\n";
echo "DIGESTEX KEMENDAG CONDITIONAL TRADE POINT ALIAS INSERT\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  SOURCE SYSTEM : KEMENDAG\n";
echo "  ALIASES       : 2\n";
echo "  CONDITIONAL   : PROVINCE-AWARE BACKFILL\n";
echo "  OPERATION     : TRANSACTIONAL INSERT\n\n";

$aliases = [
    'ATAMBUA' => 7,
    'JAYAPURA' => 35,
];

/*
|--------------------------------------------------------------------------
| Master validation
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "MASTER VALIDATION\n";
echo "========================================\n";

$masterValidationPass = true;

foreach ($aliases as $sourceName => $tradePointId) {

    $master =
        DB::table('trade_points')
            ->where('id', $tradePointId)
            ->where('is_active', 1)
            ->first([
                'id',
                'name',
                'city',
                'province_id',
            ]);

    $expected = [
        'ATAMBUA' => [
            'name' => 'Atapupu Border Crossing',
            'province_id' => 23,
        ],
        'JAYAPURA' => [
            'name' => 'Skouw Border Crossing',
            'province_id' => 24,
        ],
    ][$sourceName];

    $pass =
        $master !== null
        &&
        (string) $master->name === $expected['name']
        &&
        (int) $master->province_id === $expected['province_id'];

    echo sprintf(
        "  %-10s : %s -> ID=%s | %s | province=%s\n",
        $sourceName,
        $pass ? 'PASS' : 'FAIL',
        $master?->id ?? 'NULL',
        $master?->name ?? 'NULL',
        $master?->province_id ?? 'NULL'
    );

    if (!$pass) {
        $masterValidationPass = false;
    }
}

if (!$masterValidationPass) {
    throw new RuntimeException(
        'Master validation failed.'
    );
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Alias safety
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "EXISTING ALIAS SAFETY CHECK\n";
echo "========================================\n";

$inserted = 0;
$alreadyRegistered = 0;

foreach ($aliases as $sourceName => $tradePointId) {

    $existing =
        DB::table('trade_point_aliases')
            ->where('source_system', 'KEMENDAG')
            ->where('normalized_name', $sourceName)
            ->first();

    if ($existing === null) {

        echo "  {$sourceName} : NOT REGISTERED\n";
        continue;
    }

    if (
        (int) $existing->trade_point_id
        !== $tradePointId
    ) {
        throw new RuntimeException(
            "Alias conflict for {$sourceName}: "
            . "existing trade_point_id="
            . $existing->trade_point_id
            . ", expected="
            . $tradePointId
        );
    }

    echo "  {$sourceName} : ALREADY REGISTERED"
        . " -> alias_id={$existing->id}"
        . PHP_EOL;

    $alreadyRegistered++;
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Transactional insert
|--------------------------------------------------------------------------
*/

DB::transaction(function () use (
    $aliases,
    &$inserted,
    &$alreadyRegistered
): void {

    foreach ($aliases as $sourceName => $tradePointId) {

        $existing =
            DB::table('trade_point_aliases')
                ->where('source_system', 'KEMENDAG')
                ->where('normalized_name', $sourceName)
                ->lockForUpdate()
                ->first();

        if ($existing !== null) {

            if (
                (int) $existing->trade_point_id
                !== $tradePointId
            ) {
                throw new RuntimeException(
                    "Alias conflict during transaction: {$sourceName}"
                );
            }

            $alreadyRegistered++;
            continue;
        }

        DB::table('trade_point_aliases')->insert([
            'trade_point_id' => $tradePointId,
            'source_name' => $sourceName,
            'normalized_name' => $sourceName,
            'source_system' => 'KEMENDAG',
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $inserted++;
    }
});

echo "INSERT RESULT:\n";
echo "  INSERTED           : {$inserted}\n";
echo "  ALREADY REGISTERED : {$alreadyRegistered}\n\n";

/*
|--------------------------------------------------------------------------
| Alias verification
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "ALIAS VERIFICATION\n";
echo "========================================\n";

$aliasVerificationPass = true;

foreach ($aliases as $sourceName => $tradePointId) {

    $alias =
        DB::table('trade_point_aliases')
            ->where('source_system', 'KEMENDAG')
            ->where('normalized_name', $sourceName)
            ->first();

    $pass =
        $alias !== null
        &&
        (int) $alias->trade_point_id === $tradePointId
        &&
        (int) $alias->is_active === 1;

    echo sprintf(
        "  %-10s : %s -> alias_id=%s trade_point_id=%s\n",
        $sourceName,
        $pass ? 'PASS' : 'FAIL',
        $alias?->id ?? 'NULL',
        $alias?->trade_point_id ?? 'NULL'
    );

    if (!$pass) {
        $aliasVerificationPass = false;
    }
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Trade statistics safety
|--------------------------------------------------------------------------
*/

$atambuaTarget =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('trade_point_id')
        ->where('port_name', 'ATAMBUA')
        ->count();

$jayapuraTarget =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('trade_point_id')
        ->where('port_name', 'JAYAPURA')
        ->count();

$totalTradeRecords =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->count();

$totalIdentities =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->distinct('trade_identity')
        ->count('trade_identity');

echo "========================================\n";
echo "TRADE STATISTICS SAFETY CHECK\n";
echo "========================================\n";

echo "  ATAMBUA NULL TARGET  : {$atambuaTarget}\n";
echo "  JAYAPURA NULL TARGET : {$jayapuraTarget}\n";
echo "  TRADE RECORDS        : {$totalTradeRecords}\n";
echo "  DISTINCT IDENTITY    : {$totalIdentities}\n";
echo "  TRADE STATISTICS     : UNMODIFIED\n\n";

/*
|--------------------------------------------------------------------------
| Final gate
|--------------------------------------------------------------------------
*/

$success =
    ($inserted + $alreadyRegistered) === 2
    &&
    $masterValidationPass
    &&
    $aliasVerificationPass
    &&
    $atambuaTarget === 3835
    &&
    $jayapuraTarget === 178
    &&
    $totalTradeRecords === 2266312
    &&
    $totalIdentities === 2266312;

echo "========================================\n";
echo "FINAL CONDITIONAL ALIAS INSERT GATE\n";
echo "========================================\n";

echo "  MASTER VALIDATION  : "
    . ($masterValidationPass ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo "  ALIAS VERIFICATION : "
    . ($aliasVerificationPass ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo "  ATAMBUA TARGET      : {$atambuaTarget}\n";
echo "  JAYAPURA TARGET     : {$jayapuraTarget}\n";
echo "  TRADE DATA UPDATED  : NO\n\n";

if ($success) {
    echo "CONDITIONAL TRADE POINT ALIAS INSERT : PASS\n";
} else {
    echo "CONDITIONAL TRADE POINT ALIAS INSERT : REVIEW\n";
    exit(1);
}

echo "========================================\n";