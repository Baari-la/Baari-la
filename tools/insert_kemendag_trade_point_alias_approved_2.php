<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use Illuminate\Support\Facades\DB;


echo "========================================\n";
echo "DIGESTEX KEMENDAG APPROVED TRADE POINT ALIAS INSERT\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  SOURCE SYSTEM : KEMENDAG\n";
echo "  ALIASES       : 2\n";
echo "  OPERATION     : TRANSACTIONAL INSERT\n\n";

$aliases = [
    'SURAKARTA' => 3,
    'PONTIANAK' => 43,
];

/*
|--------------------------------------------------------------------------
| Master validation
|--------------------------------------------------------------------------
*/

foreach ($aliases as $sourceName => $tradePointId) {

    $master =
        DB::table('trade_points')
            ->where('id', $tradePointId)
            ->where('is_active', 1)
            ->first();

    if ($master === null) {
        throw new RuntimeException(
            "Trade point master {$tradePointId} not found for {$sourceName}."
        );
    }

    echo "  {$sourceName} : PASS -> "
        . "ID={$master->id} | "
        . "{$master->name}"
        . PHP_EOL;
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Existing alias safety
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
            . "existing={$existing->trade_point_id}, "
            . "expected={$tradePointId}"
        );
    }

    $alreadyRegistered++;

    echo "  {$sourceName} : ALREADY REGISTERED"
        . " -> alias_id={$existing->id}"
        . PHP_EOL;
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
| Verification
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "ALIAS VERIFICATION\n";
echo "========================================\n";

$verificationPass = true;

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

    echo "  {$sourceName} : "
        . ($pass ? 'PASS' : 'FAIL');

    if ($alias !== null) {
        echo " -> alias_id={$alias->id}"
            . " trade_point_id={$alias->trade_point_id}";
    }

    echo PHP_EOL;

    if (!$pass) {
        $verificationPass = false;
    }
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Trade statistics safety
|--------------------------------------------------------------------------
*/

$targetNull =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('trade_point_id')
        ->whereIn('port_name', [
            'SURAKARTA',
            'PONTIANAK',
        ])
        ->count();

$totalRecords =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->count();

$identities =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->distinct('trade_identity')
        ->count('trade_identity');

echo "========================================\n";
echo "TRADE STATISTICS SAFETY CHECK\n";
echo "========================================\n";

echo "  TARGET NULL RECORDS : {$targetNull}\n";
echo "  TRADE RECORDS       : {$totalRecords}\n";
echo "  DISTINCT IDENTITY   : {$identities}\n";
echo "  TRADE STATISTICS    : UNMODIFIED\n\n";

/*
|--------------------------------------------------------------------------
| Final gate
|--------------------------------------------------------------------------
*/

$success =
    ($inserted + $alreadyRegistered) === 2
    &&
    $verificationPass
    &&
    $targetNull === 764
    &&
    $totalRecords === 2266312
    &&
    $identities === 2266312;

echo "========================================\n";
echo "FINAL APPROVED ALIAS INSERT GATE\n";
echo "========================================\n";

echo "  MASTER VALIDATION  : PASS\n";
echo "  ALIAS VERIFICATION : "
    . ($verificationPass ? 'PASS' : 'FAIL')
    . PHP_EOL;
echo "  TARGET NULL        : {$targetNull}\n";
echo "  TRADE DATA UPDATED : NO\n\n";

if ($success) {
    echo "APPROVED TRADE POINT ALIAS INSERT : PASS\n";
} else {
    echo "APPROVED TRADE POINT ALIAS INSERT : REVIEW\n";
    exit(1);
}

echo "========================================\n";