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
echo "DIGESTEX KEMENDAG NEW MASTER COUNTRY BACKFILL DRY-RUN\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  OPERATION   : READ ONLY\n\n";

$resolver = app(CountryResolverService::class);

/*
|--------------------------------------------------------------------------
| Approved source name -> country ID mapping
|--------------------------------------------------------------------------
*/

$targetCountries = [
    'LUKSEMBURG' => 180,
    'SIPRUS' => 181,
    'REPUBLIK MACEDONIA' => 182,
    'ANDORRA' => 183,
    'REPUBLIK DEMOKRATIK KONGO' => 184,
    'ISLANDIA' => 185,
    'REP.AFRIKA TENGAH' => 186,
    'SAINT LUCIA' => 187,
    'GABON' => 188,
    'MAURITANIA' => 189,
    'ZAMBIA' => 190,
    'CHAD' => 191,
    'BOTSWANA' => 192,
    'MALAWI' => 193,
    'RWANDA' => 194,
    'NIGER' => 195,
    'DOMINIKA' => 196,
    'TAJIKISTAN' => 197,
    'TURKMENISTAN' => 198,
    'ERITREA' => 199,
    'SAN MARINO' => 200,
    'SAO TOME DAN PRINCIPE' => 201,
    'SUDAN SELATAN' => 202,
];

/*
|--------------------------------------------------------------------------
| Current residual country baseline
|--------------------------------------------------------------------------
*/

$currentNull =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('country_id')
        ->whereNotNull('country_name')
        ->where('country_name', '<>', '')
        ->count();

echo "CURRENT NULL COUNTRY RECORDS : {$currentNull}\n\n";

/*
|--------------------------------------------------------------------------
| Target records
|--------------------------------------------------------------------------
*/

$rows =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('country_id')
        ->whereIn(
            'country_name',
            array_keys($targetCountries)
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

echo "TARGET RECORDS : {$rows->count()}\n\n";

$resolved = 0;
$failed = 0;

$breakdown = [];

/*
|--------------------------------------------------------------------------
| Resolve every target row
|--------------------------------------------------------------------------
*/

$resolved = 0;
$failed = 0;

foreach ($rows as $row) {

    $expectedCountryId =
        $targetCountries[$row->country_name]
        ?? null;

    if ($expectedCountryId === null) {
        $failed++;
        continue;
    }

    $country =
        $resolver->resolve(
            $row->country_name
        );

    if (
        $country === null
        ||
        (int) $country->id !== $expectedCountryId
    ) {
        $failed++;
        continue;
    }

    $resolved++;

    if (!isset($breakdown[$row->country_name])) {
        $breakdown[$row->country_name] = [
            'country_id' =>
                $expectedCountryId,

            'records' =>
                0,

            'trade_value' =>
                0.0,

            'trade_volume' =>
                0.0,
        ];
    }

    $breakdown[$row->country_name]['records']++;

    $breakdown[$row->country_name]['trade_value'] +=
        (float) $row->trade_value;

    $breakdown[$row->country_name]['trade_volume'] +=
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
echo "  TARGET RECORDS      : {$rows->count()}\n";
echo "  RESOLVED            : {$resolved}\n";
echo "  FAILED              : {$failed}\n";
echo "  WOULD UPDATE        : {$resolved}\n";
echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Per-country breakdown
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "23 COUNTRY TARGET BREAKDOWN\n";
echo "========================================\n";

foreach ($breakdown as $sourceName => $data) {

    echo sprintf(
        "  %-35s | ID=%3d | %5d records | VALUE=%15.3f | VOLUME=%15.3f\n",
        $sourceName,
        $data['country_id'],
        $data['records'],
        $data['trade_value'],
        $data['trade_volume']
    );
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Expected record counts
|--------------------------------------------------------------------------
*/

$expectedCounts = [
    'LUKSEMBURG' => 474,
    'SIPRUS' => 373,
    'REPUBLIK MACEDONIA' => 256,
    'ANDORRA' => 131,
    'REPUBLIK DEMOKRATIK KONGO' => 89,
    'ISLANDIA' => 89,
    'REP.AFRIKA TENGAH' => 52,
    'SAINT LUCIA' => 51,
    'GABON' => 45,
    'MAURITANIA' => 33,
    'ZAMBIA' => 32,
    'CHAD' => 29,
    'BOTSWANA' => 26,
    'MALAWI' => 19,
    'RWANDA' => 15,
    'NIGER' => 15,
    'DOMINIKA' => 14,
    'TAJIKISTAN' => 7,
    'TURKMENISTAN' => 5,
    'ERITREA' => 4,
    'SAN MARINO' => 4,
    'SAO TOME DAN PRINCIPE' => 3,
    'SUDAN SELATAN' => 1,
];

$validationPass = true;



echo "========================================\n";
echo "DETERMINISTIC TARGET VALIDATION\n";
echo "========================================\n";

foreach ($expectedCounts as $sourceName => $expectedCount) {

    $actual =
        $breakdown[$sourceName]['records']
        ?? 0;

    $expectedCountryId =
        $targetCountries[$sourceName];

    $pass =
        $actual === $expectedCount;

    echo sprintf(
        "  %-35s : %s (%d/%d records, country_id=%d)\n",
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
| Master verification
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "MASTER / RESOLVER VERIFICATION\n";
echo "========================================\n";

foreach ($targetCountries as $sourceName => $countryId) {

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
        $resolver->resolve(
            $sourceName
        );

    $pass =
        $master !== null
        &&
        (int) $master->is_active === 1
        &&
        $resolvedCountry !== null
        &&
        (int) $resolvedCountry->id === $countryId;

    echo sprintf(
        "  %-35s : %s -> master=%s resolver=%s\n",
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

echo "DEBUG FINAL GATE:\n";

echo "  currentNull    = ";
var_dump($currentNull);

echo "  targetCount    = ";
var_dump($rows->count());

echo "  resolved       = ";
var_dump($resolved);

echo "  failed         = ";
var_dump($failed);

echo "  validationPass = ";
var_dump($validationPass);

echo PHP_EOL;

$success =
    $currentNull === 8019
    &&
    $rows->count() === 1767
    &&
    $resolved === 1767
    &&
    $failed === 0
    &&
    $validationPass === true;

echo "DEBUG SUCCESS = ";
var_dump($success);

echo PHP_EOL;

echo "========================================\n";

if ($success) {
    echo "NEW MASTER COUNTRY BACKFILL DRY-RUN : PASS\n";
} else {
    echo "NEW MASTER COUNTRY BACKFILL DRY-RUN : REVIEW\n";
}

echo "========================================\n";