<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use App\Services\Trade\CountryResolverService;
use Illuminate\Support\Facades\DB;


echo "========================================\n";
echo "DIGESTEX KEMENDAG COUNTRY ALIAS INSERT\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  SOURCE SYSTEM : KEMENDAG\n";
echo "  ALIASES       : 3\n";
echo "  OPERATION     : TRANSACTIONAL INSERT\n\n";

$aliases = [
    [
        'source_name'     => 'SRI LANGKA',
        'normalized_name' => 'SRI LANGKA',
        'country_id'      => 23,
        'expected_code'   => 'LK',
        'expected_iso3'   => 'LKA',
    ],
    [
        'source_name'     => 'REPUBLIK CZECH',
        'normalized_name' => 'REPUBLIK CZECH',
        'country_id'      => 51,
        'expected_code'   => 'CZ',
        'expected_iso3'   => 'CZE',
    ],
    [
        'source_name'     => 'LIBIA',
        'normalized_name' => 'LIBIA',
        'country_id'      => 90,
        'expected_code'   => 'LY',
        'expected_iso3'   => 'LBY',
    ],
];

/*
|--------------------------------------------------------------------------
| 1. MASTER VALIDATION
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "MASTER VALIDATION\n";
echo "========================================\n";

foreach ($aliases as $alias) {

    $master = DB::table('mst_countries')
        ->where('id', $alias['country_id'])
        ->first([
            'id',
            'country_code',
            'iso3',
            'country_name_en',
        ]);

    if ($master === null) {
        throw new RuntimeException(
            "Master country tidak ditemukan: "
            . $alias['country_id']
        );
    }

    if (
        strtoupper((string) $master->country_code)
        !== $alias['expected_code']
        ||
        strtoupper((string) ($master->iso3 ?? ''))
        !== $alias['expected_iso3']
    ) {
        throw new RuntimeException(
            "Master conflict untuk "
            . $alias['source_name']
        );
    }

    echo "  {$alias['source_name']} : PASS"
        . " -> ID={$master->id}"
        . " {$master->country_code}/{$master->iso3}"
        . " | {$master->country_name_en}"
        . PHP_EOL;
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| 2. EXISTING ALIAS SAFETY CHECK
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "EXISTING ALIAS SAFETY CHECK\n";
echo "========================================\n";

foreach ($aliases as $alias) {

    $existing = DB::table('trade_country_aliases')
        ->where('source_system', 'KEMENDAG')
        ->where('normalized_name', $alias['normalized_name'])
        ->first([
            'id',
            'country_id',
            'source_name',
            'normalized_name',
            'source_system',
            'is_active',
        ]);

    if ($existing === null) {

        echo "  {$alias['source_name']} : NOT REGISTERED\n";

        continue;
    }

    if (
        (int) $existing->country_id
        !== $alias['country_id']
    ) {
        throw new RuntimeException(
            "ALIAS CONFLICT untuk "
            . $alias['source_name']
            . " existing country_id="
            . $existing->country_id
        );
    }

    echo "  {$alias['source_name']} : ALREADY REGISTERED"
        . " -> alias_id={$existing->id}"
        . PHP_EOL;
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| 3. TRANSACTIONAL INSERT
|--------------------------------------------------------------------------
*/

$inserted = 0;
$alreadyRegistered = 0;

DB::transaction(function () use (
    $aliases,
    &$inserted,
    &$alreadyRegistered
): void {

    foreach ($aliases as $alias) {

        $existing = DB::table('trade_country_aliases')
            ->where('source_system', 'KEMENDAG')
            ->where('normalized_name', $alias['normalized_name'])
            ->lockForUpdate()
            ->first();

        if ($existing !== null) {

            if (
                (int) $existing->country_id
                !== $alias['country_id']
            ) {
                throw new RuntimeException(
                    "Alias conflict saat transaction: "
                    . $alias['source_name']
                );
            }

            $alreadyRegistered++;

            continue;
        }

        DB::table('trade_country_aliases')->insert([
            'country_id'      => $alias['country_id'],
            'source_name'     => $alias['source_name'],
            'normalized_name' => $alias['normalized_name'],
            'source_system'   => 'KEMENDAG',
            'is_active'       => 1,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        $inserted++;
    }
});

/*
|--------------------------------------------------------------------------
| 4. ALIAS VERIFICATION
|--------------------------------------------------------------------------
*/

echo "INSERT RESULT:\n";
echo "  INSERTED           : {$inserted}\n";
echo "  ALREADY REGISTERED : {$alreadyRegistered}\n\n";

echo "========================================\n";
echo "ALIAS VERIFICATION\n";
echo "========================================\n";

foreach ($aliases as $alias) {

    $row = DB::table('trade_country_aliases')
        ->where('source_system', 'KEMENDAG')
        ->where('normalized_name', $alias['normalized_name'])
        ->first([
            'id',
            'country_id',
            'source_name',
            'normalized_name',
            'source_system',
            'is_active',
        ]);

    if (
        $row === null
        ||
        (int) $row->country_id !== $alias['country_id']
        ||
        (int) $row->is_active !== 1
    ) {
        throw new RuntimeException(
            "Alias verification FAILED: "
            . $alias['source_name']
        );
    }

    echo "  {$alias['source_name']} : PASS"
        . " -> alias_id={$row->id}"
        . " country_id={$row->country_id}"
        . PHP_EOL;
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| 5. RESOLVER VERIFICATION
|--------------------------------------------------------------------------
|
| Important:
| CountryResolverService may cache lookup only inside the
| current service instance. Create a fresh instance after insert.
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "RESOLVER VERIFICATION\n";
echo "========================================\n";

$resolver =
    app(
        CountryResolverService::class
    );

foreach ($aliases as $alias) {

    $resolved =
        $resolver->resolve(
            $alias['source_name']
        );

    if (
        $resolved === null
        ||
        (int) $resolved->id !== $alias['country_id']
        ||
        strtoupper((string) $resolved->country_code)
            !== $alias['expected_code']
        ||
        strtoupper((string) ($resolved->iso3 ?? ''))
            !== $alias['expected_iso3']
    ) {
        throw new RuntimeException(
            "Resolver verification FAILED: "
            . $alias['source_name']
        );
    }

    echo "  {$alias['source_name']} : PASS"
        . " -> ID={$resolved->id}"
        . " {$resolved->country_code}/{$resolved->iso3}"
        . " | {$resolved->country_name_en}"
        . PHP_EOL;
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| 6. TRADE STATISTICS SAFETY CHECK
|--------------------------------------------------------------------------
|
| Alias registration must NOT alter trade_statistics.
|--------------------------------------------------------------------------
*/

$targetNullRecords =
    DB::table('trade_statistics')
        ->whereBetween(
            'year',
            [2019, 2026]
        )
        ->whereIn(
            'trade_flow',
            [
                'export',
                'import',
            ]
        )
        ->whereNull('country_id')
        ->whereIn(
            'country_name',
            [
                'SRI LANGKA',
                'REPUBLIK CZECH',
                'LIBIA',
            ]
        )
        ->count();

echo "========================================\n";
echo "TRADE STATISTICS SAFETY CHECK\n";
echo "========================================\n";

echo "  NULL TARGET RECORDS : "
    . $targetNullRecords
    . PHP_EOL;

if ($targetNullRecords !== 23679) {
    throw new RuntimeException(
        "Unexpected NULL target count. "
        . "Expected 23679, got "
        . $targetNullRecords
    );
}

echo "  TRADE STATISTICS    : UNMODIFIED\n";
echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| 7. FINAL GATE
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "FINAL ALIAS INSERT GATE\n";
echo "========================================\n";

echo "  MASTER VALIDATION   : PASS\n";
echo "  ALIAS VERIFICATION  : PASS\n";
echo "  RESOLVER TEST       : PASS\n";
echo "  TARGET RECORDS      : {$targetNullRecords}\n";
echo "  TRADE DATA UPDATED  : NO\n";
echo PHP_EOL;

if (
    ($inserted + $alreadyRegistered) === 3
    &&
    $targetNullRecords === 23679
) {
    echo "COUNTRY ALIAS INSERT : PASS\n";
} else {
    echo "COUNTRY ALIAS INSERT : REVIEW\n";
    exit(1);
}

echo "========================================\n";