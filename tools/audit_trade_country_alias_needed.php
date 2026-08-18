<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| INPUT / OUTPUT
|--------------------------------------------------------------------------
*/

$base =
    getenv('USERPROFILE')
    . DIRECTORY_SEPARATOR
    . 'Desktop'
    . DIRECTORY_SEPARATOR
    . 'DIGESTEX_DATA';

$inputFile =
    $base
    . DIRECTORY_SEPARATOR
    . 'PROCESSED'
    . DIRECTORY_SEPARATOR
    . 'dry_run_export_2019.csv';

$outputFile =
    $base
    . DIRECTORY_SEPARATOR
    . 'PROCESSED'
    . DIRECTORY_SEPARATOR
    . 'trade_country_alias_review_2019.csv';

if (!is_file($inputFile)) {
    throw new RuntimeException(
        "Dry-run file tidak ditemukan:\n{$inputFile}"
    );
}

/*
|--------------------------------------------------------------------------
| Normalize
|--------------------------------------------------------------------------
*/

function normalizeCountry(string $value): string
{
    $value = trim($value);

    if ($value === '') {
        return '';
    }

    $value = preg_replace(
        '/\s+/',
        ' ',
        $value
    ) ?? '';

    return mb_strtoupper($value);
}

/*
|--------------------------------------------------------------------------
| Load master countries
|--------------------------------------------------------------------------
*/

$countryRows = DB::table('mst_countries')
    ->get([
        'id',
        'country_code',
        'iso3',
        'country_name_en',
        'country_name_id',
    ]);

if ($countryRows->isEmpty()) {
    throw new RuntimeException(
        'mst_countries kosong.'
    );
}

/*
|--------------------------------------------------------------------------
| Build master lookup
|--------------------------------------------------------------------------
*/

$countryLookup = [];

foreach ($countryRows as $country) {

    foreach ([
        $country->country_name_en,
        $country->country_name_id,
        $country->country_code,
        $country->iso3,
    ] as $value) {

        $normalized =
            normalizeCountry(
                (string) $value
            );

        if ($normalized !== '') {
            $countryLookup[$normalized] =
                $country;
        }
    }
}

/*
|--------------------------------------------------------------------------
| Known Kemendag country aliases
|--------------------------------------------------------------------------
|
| These are conservative explicit aliases.
| We do NOT use fuzzy matching.
|--------------------------------------------------------------------------
*/

$knownAliases = [

    'REP.RAKYAT TIONGKOK' => 'CN',
    'TIONGKOK' => 'CN',
    'CINA' => 'CN',

    'HONGKONG' => 'HK',
    'HONG KONG' => 'HK',

    'INGGRIS' => 'GB',

    'PERANCIS' => 'FR',

    'PILIPINA' => 'PH',
    'FILIPINA' => 'PH',

    'REP.AFRIKA SELATAN' => 'ZA',

    'FEDERASI RUSIA' => 'RU',
    'RUSIA' => 'RU',

    'SWEDIA' => 'SE',

    'CHILI' => 'CL',

    'BRASILIA' => 'BR',
    'BRASIL' => 'BR',

    'DENMARK' => 'DK',
    'DANMARK' => 'DK',

    'KOLUMBIA' => 'CO',
    'KOLOMBIA' => 'CO',

    'TIMOR TIMUR' => 'TL',

    'NORWEGIA' => 'NO',

    'SRI LANKA' => 'LK',
    'SRI LANKA' => 'LK',

    'YUNANI' => 'GR',

    'KROASIA' => 'HR',

    'SERBIA' => 'RS',

    'BURMA' => 'MM',
    'MYANMAR' => 'MM',

    'REPUBLIK MALADEWA' => 'MV',
    'MALADEWA' => 'MV',
    'MALDIVES' => 'MV',

    'POLYNESIA PERANCIS' => 'PF',

    'BRUNAI DARUSSALAM' => 'BN',
    'BRUNEI DARUSSALAM' => 'BN',

    'REUNION' => 'RE',

    'HONGARIA' => 'HU',

    'LIBANON' => 'LB',

    'UKRAINA' => 'UA',
    'UKRAINE' => 'UA',

    'REPUBLIK CZECH' => 'CZ',
    'CZECH REPUBLIC' => 'CZ',
    'REPUBLIK CEKO' => 'CZ',

    'GUADELOUPE' => 'GP',

    'SLOVENIA' => 'SI',

    'IRLANDIA' => 'IE',
    'IRELAND' => 'IE',

    'SOMALIA' => 'SO',

    'FINLANDIA' => 'FI',

    'NETHERLANDS ANTILLES' => 'AN',

    'PUERTO RICO' => 'PR',

    'BULGARIA' => 'BG',

    'SINEGAL' => 'SN',
    'SENEGAL' => 'SN',

    'GUAM' => 'GU',

    'SEYCHELLES' => 'SC',

    'TOGO' => 'TG',

    'SAINT KITTS DAN NEVIS' => 'KN',

    'KEPULAUAN COOK' => 'CK',

    'MARTINIK' => 'MQ',

    'LATVIA' => 'LV',

    'YAMAN' => 'YE',
    'YEMEN' => 'YE',

    'KAMERUN' => 'CM',

    'SUDAN' => 'SD',

    'ESTONIA' => 'EE',

    'MACAU' => 'MO',
    'MAKAU' => 'MO',

    'MADAGASKAR' => 'MG',
];

/*
|--------------------------------------------------------------------------
| Country code lookup
|--------------------------------------------------------------------------
*/

$countryByCode = [];

foreach ($countryRows as $country) {
    $countryByCode[
        strtoupper(
            trim(
                (string) $country->country_code
            )
        )
    ] = $country;
}

/*
|--------------------------------------------------------------------------
| Read dry-run CSV
|--------------------------------------------------------------------------
*/

$handle = fopen(
    $inputFile,
    'rb'
);

if ($handle === false) {
    throw new RuntimeException(
        "Tidak dapat membuka:\n{$inputFile}"
    );
}

$header = fgetcsv($handle);

if ($header === false) {
    fclose($handle);

    throw new RuntimeException(
        'Header CSV tidak ditemukan.'
    );
}

$columns = [];

foreach ($header as $index => $name) {
    $columns[
        trim((string) $name)
    ] = $index;
}

foreach ([
    'COUNTRY_SOURCE',
    'COUNTRY_STATUS',
] as $required) {

    if (!isset($columns[$required])) {
        fclose($handle);

        throw new RuntimeException(
            "Column {$required} tidak ditemukan."
        );
    }
}

/*
|--------------------------------------------------------------------------
| Aggregate unresolved country names
|--------------------------------------------------------------------------
*/

$countryStats = [];

while (($row = fgetcsv($handle)) !== false) {

    $status = trim(
        (string) $row[
            $columns['COUNTRY_STATUS']
        ]
    );

    if ($status !== 'UNRESOLVED') {
        continue;
    }

    $sourceName = trim(
        (string) $row[
            $columns['COUNTRY_SOURCE']
        ]
    );

    $normalized =
        normalizeCountry($sourceName);

    if ($normalized === '') {
        continue;
    }

    if (!isset($countryStats[$normalized])) {

        $countryStats[$normalized] = [
            'country_source' =>
                $sourceName,

            'country_normalized' =>
                $normalized,

            'monthly_occurrence_count' =>
                0,
        ];
    }

    $countryStats[$normalized][
        'monthly_occurrence_count'
    ]++;
}

fclose($handle);

/*
|--------------------------------------------------------------------------
| Build review rows
|--------------------------------------------------------------------------
*/

$reviewRows = [];

foreach ($countryStats as $normalized => $stats) {

    $suggestedCode = null;
    $suggestedCountry = null;
    $suggestedCountryEn = null;
    $suggestionMethod = null;
    $confidence = 'LOW';

    /*
    |--------------------------------------------------------------------------
    | 1. Explicit alias
    |--------------------------------------------------------------------------
    */

    if (isset($knownAliases[$normalized])) {

        $suggestedCode =
            $knownAliases[$normalized];

        $suggestedCountry =
            $countryByCode[$suggestedCode]
                ?? null;

        if ($suggestedCountry !== null) {

            $suggestedCountryEn =
                $suggestedCountry->country_name_en;

            $suggestionMethod =
                'EXPLICIT_ALIAS';

            $confidence =
                'HIGH';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | 2. Exact master match
    |--------------------------------------------------------------------------
    */

    if (
        $suggestedCode === null
        &&
        isset($countryLookup[$normalized])
    ) {

        $matched =
            $countryLookup[$normalized];

        $suggestedCode =
            $matched->country_code;

        $suggestedCountry =
            $matched;

        $suggestedCountryEn =
            $matched->country_name_en;

        $suggestionMethod =
            'EXACT_MASTER_MATCH';

        $confidence =
            'HIGH';
    }

    /*
    |--------------------------------------------------------------------------
    | 3. No automatic fuzzy matching
    |--------------------------------------------------------------------------
    */

    if ($suggestedCode === null) {

        $suggestionMethod =
            'MANUAL_REVIEW';

        $confidence =
            'REVIEW';
    }

    $reviewRows[] = [
        'country_source' =>
            $stats['country_source'],

        'country_normalized' =>
            $normalized,

        'monthly_occurrence_count' =>
            $stats['monthly_occurrence_count'],

        'suggested_country_code' =>
            $suggestedCode,

        'suggested_country_name_id' =>
            $suggestedCountry?->country_name_id,

        'suggested_country_name_en' =>
            $suggestedCountryEn,

        'suggestion_method' =>
            $suggestionMethod,

        'confidence' =>
            $confidence,

        'status' =>
            $suggestedCode !== null
                ? 'SUGGESTED'
                : 'REVIEW',
    ];
}

/*
|--------------------------------------------------------------------------
| Sort by occurrence
|--------------------------------------------------------------------------
*/

usort(
    $reviewRows,
    function (array $a, array $b): int {
        return
            $b['monthly_occurrence_count']
            <=>
            $a['monthly_occurrence_count'];
    }
);

/*
|--------------------------------------------------------------------------
| Write review CSV
|--------------------------------------------------------------------------
*/

$output = fopen(
    $outputFile,
    'wb'
);

if ($output === false) {
    throw new RuntimeException(
        "Tidak dapat membuat:\n{$outputFile}"
    );
}

fputcsv(
    $output,
    [
        'country_source',
        'country_normalized',
        'monthly_occurrence_count',
        'suggested_country_code',
        'suggested_country_name_id',
        'suggested_country_name_en',
        'suggestion_method',
        'confidence',
        'status',
    ]
);

foreach ($reviewRows as $row) {

    fputcsv(
        $output,
        [
            $row['country_source'],
            $row['country_normalized'],
            $row['monthly_occurrence_count'],
            $row['suggested_country_code'],
            $row['suggested_country_name_id'],
            $row['suggested_country_name_en'],
            $row['suggestion_method'],
            $row['confidence'],
            $row['status'],
        ]
    );
}

fclose($output);

/*
|--------------------------------------------------------------------------
| Summary
|--------------------------------------------------------------------------
*/

$totalReviewNames =
    count($reviewRows);

$suggestedCount = count(
    array_filter(
        $reviewRows,
        fn ($row) =>
            $row['status'] === 'SUGGESTED'
    )
);

$manualReviewCount =
    $totalReviewNames - $suggestedCount;

echo "========================================\n";
echo "DIGESTEX TRADE COUNTRY ALIAS AUDIT\n";
echo "========================================\n\n";

echo "UNRESOLVED COUNTRY NAMES : "
    . $totalReviewNames
    . PHP_EOL;

echo "SUGGESTED                : "
    . $suggestedCount
    . PHP_EOL;

echo "MANUAL REVIEW            : "
    . $manualReviewCount
    . PHP_EOL;

echo "\nMASTER COUNTRIES         : "
    . $countryRows->count()
    . PHP_EOL;

echo "\nOUTPUT:\n";
echo $outputFile . PHP_EOL;

echo "\n========================================\n";
echo "DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";