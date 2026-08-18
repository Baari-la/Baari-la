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
echo "DIGESTEX KEMENDAG FRENCH GUIANA BACKFILL DRY-RUN\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  SOURCE NAME : GUIANA PERANCIS\n";
echo "  TARGET ID   : 71\n";
echo "  OPERATION   : READ ONLY\n\n";

$resolver = app(
    CountryResolverService::class
);

/*
|--------------------------------------------------------------------------
| Baseline
|--------------------------------------------------------------------------
*/

$currentNull =
    DB::table('trade_statistics')
        ->whereBetween(
            'year',
            [2019, 2026]
        )
        ->whereIn(
            'trade_flow',
            ['export', 'import']
        )
        ->whereNull('country_id')
        ->count();

$targetRows =
    DB::table('trade_statistics')
        ->whereBetween(
            'year',
            [2019, 2026]
        )
        ->whereIn(
            'trade_flow',
            ['export', 'import']
        )
        ->whereNull('country_id')
        ->where(
            'country_name',
            'GUIANA PERANCIS'
        )
        ->select(
            'id',
            'year',
            'month',
            'trade_flow',
            'country_name',
            'country_code',
            'province_name',
            'port_name',
            'hs_code',
            'trade_value',
            'trade_volume',
            'import_batch_id'
        )
        ->orderBy('id')
        ->get();

echo "CURRENT NULL COUNTRY RECORDS : {$currentNull}\n";
echo "TARGET RECORDS               : {$targetRows->count()}\n\n";

/*
|--------------------------------------------------------------------------
| Resolve
|--------------------------------------------------------------------------
*/

$resolvedCount = 0;
$failedCount = 0;

$totalValue = 0.0;
$totalVolume = 0.0;

foreach ($targetRows as $row) {

    $resolvedCountry =
        $resolver->resolve(
            'GUIANA PERANCIS'
        );

    if (
        $resolvedCountry === null
        ||
        (int) $resolvedCountry->id !== 71
    ) {
        $failedCount++;
        continue;
    }

    $resolvedCount++;

    $totalValue +=
        (float) $row->trade_value;

    $totalVolume +=
        (float) $row->trade_volume;
}

/*
|--------------------------------------------------------------------------
| Summary
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "BACKFILL SUMMARY\n";
echo "========================================\n";

echo "  NULL BEFORE         : {$currentNull}\n";
echo "  TARGET RECORDS      : {$targetRows->count()}\n";
echo "  RESOLVED            : {$resolvedCount}\n";
echo "  FAILED              : {$failedCount}\n";
echo "  WOULD UPDATE        : {$resolvedCount}\n";
echo PHP_EOL;

echo "TARGET COUNTRY:\n";
echo "  COUNTRY ID          : 71\n";
echo "  COUNTRY CODE        : GF\n";
echo "  ISO3                : GUF\n";
echo "  COUNTRY NAME        : French Guiana\n";
echo PHP_EOL;

echo "TARGET AGGREGATE:\n";
echo "  TRADE VALUE         : "
    . number_format(
        $totalValue,
        3,
        '.',
        ''
    )
    . PHP_EOL;

echo "  TRADE VOLUME        : "
    . number_format(
        $totalVolume,
        3,
        '.',
        ''
    )
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Deterministic validation
|--------------------------------------------------------------------------
*/

$targetPass =
    $targetRows->count() === 34
    &&
    $resolvedCount === 34
    &&
    $failedCount === 0;

echo "========================================\n";
echo "DETERMINISTIC TARGET VALIDATION\n";
echo "========================================\n";

echo "  GUIANA PERANCIS : "
    . (
        $targetPass
            ? 'PASS'
            : 'FAIL'
    )
    . " ("
    . $resolvedCount
    . "/34 records, country_id=71)"
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Master / resolver verification
|--------------------------------------------------------------------------
*/

$master =
    DB::table('mst_countries')
        ->where('id', 71)
        ->first([
            'id',
            'country_code',
            'iso3',
            'country_name_en',
            'is_active',
        ]);

$resolvedCountry =
    $resolver->resolve(
        'GUIANA PERANCIS'
    );

$masterResolverPass =
    $master !== null
    &&
    (int) $master->is_active === 1
    &&
    strtoupper(
        (string) $master->country_code
    ) === 'GF'
    &&
    strtoupper(
        (string) $master->iso3
    ) === 'GUF'
    &&
    $resolvedCountry !== null
    &&
    (int) $resolvedCountry->id === 71;

echo "========================================\n";
echo "MASTER / RESOLVER VERIFICATION\n";
echo "========================================\n";

echo "  MASTER  : "
    . ($master !== null ? 'FOUND' : 'MISSING')
    . " -> ID="
    . ($master?->id ?? 'NULL')
    . PHP_EOL;

echo "  RESOLVER: "
    . (
        $resolvedCountry !== null
            ? 'FOUND'
            : 'NULL'
    )
    . " -> ID="
    . ($resolvedCountry?->id ?? 'NULL')
    . PHP_EOL;

echo "  RESULT  : "
    . (
        $masterResolverPass
            ? 'PASS'
            : 'FAIL'
    )
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Database safety
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "DATABASE SAFETY\n";
echo "========================================\n";

echo "  INSERT : NO\n";
echo "  UPDATE : NO\n";
echo "  DELETE : NO\n";
echo "  DATABASE WAS NOT MODIFIED.\n";

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Final gate
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "FINAL DRY-RUN GATE\n";
echo "========================================\n";

$success =
    $targetRows->count() === 34
    &&
    $resolvedCount === 34
    &&
    $failedCount === 0
    &&
    $masterResolverPass;

if ($success) {
    echo "FRENCH GUIANA BACKFILL DRY-RUN : PASS\n";
} else {
    echo "FRENCH GUIANA BACKFILL DRY-RUN : REVIEW\n";
}

echo "========================================\n";