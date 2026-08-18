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
echo "DIGESTEX KEMENDAG TERRITORY BACKFILL DRY-RUN\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  TARGET      : 28 TERRITORY / DEPENDENCY\n";
echo "  OPERATION   : READ ONLY\n\n";

$resolver = app(
    CountryResolverService::class
);

/*
|--------------------------------------------------------------------------
| Source name -> approved master country ID
|--------------------------------------------------------------------------
*/

$targets = [
    'VIRGIN ISLANDS (BRITISH)' => 203,
    'U.S. VIRGIN ISLANDS' => 204,
    'KEPULAUAN TURKS DAN CAICOS' => 205,
    'KEPULAUAN CAYMAN' => 206,
    'KEP. VALLIS DAN FUTUNA' => 207,
    'ANGUILA' => 208,
    'JERSEY' => 209,
    'MAYOTTE' => 210,
    'ARUBA' => 211,
    'SAINT BARTHELEMY' => 212,
    'SAMOA AMERIKA' => 213,
    'TOKELAU' => 214,
    'GIBRALTAR' => 215,
    'KEPULAUAN MARIANA UTARA' => 216,
    'SAINT MARTIN (FRENCH PART)' => 217,
    'KEPULAUAN CHRISTMAS' => 218,
    'U.S MINOR OUTLYING ISLAND' => 219,
    'GUERNSEY' => 220,
    'SAINT HELENA' => 221,
    'KEPULAUAN FALKLAND (MALVINAS)' => 222,
    'KEPULAUAN COCOS (KEELING)' => 223,
    'SINT MAARTEN (DUTCH PART)' => 224,
    'KEPULAUAN NORFOLK' => 225,
    'BRITISH INDIAN OCEAN TERRITORY' => 226,
    'ANTARTICA' => 227,
    'PITCAIRN' => 228,
    'PULAU HEARD DAN KEPULAUAN MCDONALD' => 229,
    'KEPULAUAN ALAND' => 230,
];

/*
|--------------------------------------------------------------------------
| Expected deterministic record counts
|--------------------------------------------------------------------------
*/

$expectedCounts = [
    'VIRGIN ISLANDS (BRITISH)' => 502,
    'U.S. VIRGIN ISLANDS' => 254,
    'KEPULAUAN TURKS DAN CAICOS' => 240,
    'KEPULAUAN CAYMAN' => 196,
    'KEP. VALLIS DAN FUTUNA' => 192,
    'ANGUILA' => 84,
    'JERSEY' => 82,
    'MAYOTTE' => 78,
    'ARUBA' => 68,
    'SAINT BARTHELEMY' => 44,
    'SAMOA AMERIKA' => 41,
    'TOKELAU' => 29,
    'GIBRALTAR' => 26,
    'KEPULAUAN MARIANA UTARA' => 23,
    'SAINT MARTIN (FRENCH PART)' => 22,
    'KEPULAUAN CHRISTMAS' => 16,
    'U.S MINOR OUTLYING ISLAND' => 14,
    'GUERNSEY' => 9,
    'SAINT HELENA' => 9,
    'KEPULAUAN FALKLAND (MALVINAS)' => 8,
    'KEPULAUAN COCOS (KEELING)' => 8,
    'SINT MAARTEN (DUTCH PART)' => 7,
    'KEPULAUAN NORFOLK' => 4,
    'BRITISH INDIAN OCEAN TERRITORY' => 3,
    'ANTARTICA' => 2,
    'PITCAIRN' => 1,
    'PULAU HEARD DAN KEPULAUAN MCDONALD' => 1,
    'KEPULAUAN ALAND' => 1,
];

if (count($targets) !== 28) {
    throw new RuntimeException(
        'Expected 28 territory targets.'
    );
}

if (count($expectedCounts) !== 28) {
    throw new RuntimeException(
        'Expected 28 territory expected-count entries.'
    );
}

/*
|--------------------------------------------------------------------------
| Baseline
|--------------------------------------------------------------------------
*/

$currentNull =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('country_id')
        ->count();

echo "CURRENT NULL COUNTRY RECORDS : {$currentNull}\n\n";

/*
|--------------------------------------------------------------------------
| Load target rows
|--------------------------------------------------------------------------
*/

$rows =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('country_id')
        ->whereIn('country_name', array_keys($targets))
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

$targetCount = $rows->count();

echo "TARGET RECORDS : {$targetCount}\n\n";

if ($targetCount !== 1964) {
    throw new RuntimeException(
        "Unexpected target count. Expected 1964, got {$targetCount}."
    );
}

/*
|--------------------------------------------------------------------------
| Resolve every record
|--------------------------------------------------------------------------
*/

$resolvedCount = 0;
$failedCount = 0;

$breakdown = [];

foreach ($rows as $row) {

    $sourceName = trim(
        (string) $row->country_name
    );

    $expectedCountryId =
        $targets[$sourceName]
        ?? null;

    if ($expectedCountryId === null) {
        $failedCount++;
        continue;
    }

    $resolvedCountry =
        $resolver->resolve($sourceName);

    if (
        $resolvedCountry === null
        ||
        (int) $resolvedCountry->id !== $expectedCountryId
    ) {
        $failedCount++;
        continue;
    }

    $resolvedCount++;

    if (!isset($breakdown[$sourceName])) {
        $breakdown[$sourceName] = [
            'expected' => $expectedCounts[$sourceName] ?? 0,
            'records' => 0,
            'trade_value' => 0.0,
            'trade_volume' => 0.0,
            'country_id' => $expectedCountryId,
        ];
    }

    $breakdown[$sourceName]['records']++;

    $breakdown[$sourceName]['trade_value'] +=
        (float) $row->trade_value;

    $breakdown[$sourceName]['trade_volume'] +=
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
echo "  TARGET RECORDS      : {$targetCount}\n";
echo "  RESOLVED            : {$resolvedCount}\n";
echo "  FAILED              : {$failedCount}\n";
echo "  WOULD UPDATE        : {$resolvedCount}\n";
echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Deterministic per-country validation
|--------------------------------------------------------------------------
*/

$validationPass = true;

echo "========================================\n";
echo "DETERMINISTIC TARGET VALIDATION\n";
echo "========================================\n";

foreach ($expectedCounts as $sourceName => $expectedCount) {

    $actual =
        $breakdown[$sourceName]['records']
        ?? 0;

    $expectedCountryId =
        $targets[$sourceName];

    $pass =
        $actual === $expectedCount;

    echo sprintf(
        "  %-42s : %s (%d/%d records, country_id=%d)\n",
        $sourceName,
        $pass ? 'PASS' : 'FAIL',
        $actual,
        $expectedCount,
        $expectedCountryId
    );

    if (!$pass) {
        $validationPass = false;
    }
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Master + resolver verification
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "MASTER / RESOLVER VERIFICATION\n";
echo "========================================\n";

foreach ($targets as $sourceName => $countryId) {

    $master =
        DB::table('mst_countries')
            ->where('id', $countryId)
            ->first([
                'id',
                'country_code',
                'iso3',
                'country_name_en',
                'is_active',
            ]);

    $resolvedCountry =
        $resolver->resolve($sourceName);

    $pass =
        $master !== null
        &&
        (int) $master->is_active === 1
        &&
        $resolvedCountry !== null
        &&
        (int) $resolvedCountry->id === $countryId;

    echo sprintf(
        "  %-42s : %s -> master=%s resolver=%s\n",
        $sourceName,
        $pass ? 'PASS' : 'FAIL',
        $master?->id ?? 'NULL',
        $resolvedCountry?->id ?? 'NULL'
    );

    if (!$pass) {
        $validationPass = false;
    }
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Aggregate totals
|--------------------------------------------------------------------------
*/

$totalValue = 0.0;
$totalVolume = 0.0;

foreach ($breakdown as $data) {
    $totalValue += $data['trade_value'];
    $totalVolume += $data['trade_volume'];
}

echo "========================================\n";
echo "TARGET AGGREGATE\n";
echo "========================================\n";

echo "  TRADE VALUE  : "
    . number_format(
        $totalValue,
        3,
        '.',
        ''
    )
    . PHP_EOL;

echo "  TRADE VOLUME : "
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

$success =
    $currentNull === 6218
    &&
    $targetCount === 1964
    &&
    $resolvedCount === 1964
    &&
    $failedCount === 0
    &&
    $validationPass === true;

echo "========================================\n";
echo "FINAL TERRITORY DRY-RUN GATE\n";
echo "========================================\n";

echo "  CURRENT NULL COUNTRY : {$currentNull}\n";
echo "  TARGET RECORDS       : {$targetCount}\n";
echo "  RESOLVED             : {$resolvedCount}\n";
echo "  FAILED               : {$failedCount}\n";
echo "  VALIDATION           : "
    . ($validationPass ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo PHP_EOL;

if ($success) {
    echo "TERRITORY BACKFILL DRY-RUN : PASS\n";
} else {
    echo "TERRITORY BACKFILL DRY-RUN : REVIEW\n";
    exit(1);
}

echo "========================================\n";