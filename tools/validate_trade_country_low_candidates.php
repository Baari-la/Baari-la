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
| DIGESTEX LOW COUNTRY CANDIDATE VALIDATOR
|--------------------------------------------------------------------------
|
| INPUT:
|   trade_country_alias_review_2019.csv
|
| SOURCE OF TRUTH:
|   mst_countries
|
| DATABASE:
|   READ ONLY
|
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
    . 'trade_country_alias_review_2019.csv';

$outputFile =
    $base
    . DIRECTORY_SEPARATOR
    . 'PROCESSED'
    . DIRECTORY_SEPARATOR
    . 'trade_country_low_candidates_validation.csv';

if (!is_file($inputFile)) {
    throw new RuntimeException(
        "Input file tidak ditemukan:\n{$inputFile}"
    );
}

/*
|--------------------------------------------------------------------------
| Read master country
|--------------------------------------------------------------------------
*/

$countryRows = DB::table('mst_countries')
    ->where('is_active', true)
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
| Build lookup
|--------------------------------------------------------------------------
*/

$countryByCode = [];

$countryByIso3 = [];

foreach ($countryRows as $country) {

    $code = strtoupper(
        trim(
            (string) $country->country_code
        )
    );

    $iso3 = strtoupper(
        trim(
            (string) $country->iso3
        )
    );

    if ($code !== '') {
        $countryByCode[$code] = $country;
    }

    if ($iso3 !== '') {
        $countryByIso3[$iso3] = $country;
    }
}

/*
|--------------------------------------------------------------------------
| Read review CSV
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

/*
|--------------------------------------------------------------------------
| Required columns
|--------------------------------------------------------------------------
*/

$required = [
    'country_source',
    'country_normalized',
    'monthly_occurrence_count',
    'suggested_country_code',
    'suggested_country_name_en',
    'confidence',
    'status',
];

foreach ($required as $column) {

    if (!isset($columns[$column])) {
        fclose($handle);

        throw new RuntimeException(
            "Column {$column} tidak ditemukan."
        );
    }
}

/*
|--------------------------------------------------------------------------
| Process LOW candidates
|--------------------------------------------------------------------------
*/

$results = [];

$totalLow = 0;
$masterFound = 0;
$masterMissing = 0;
$iso3Found = 0;
$codeConflict = 0;

while (($row = fgetcsv($handle)) !== false) {

    $status = trim(
        (string) $row[
            $columns['status']
        ]
    );

    $confidence = trim(
        (string) $row[
            $columns['confidence']
        ]
    );

    if (
        $status !== 'SUGGESTED'
        ||
        strtoupper($confidence) !== 'LOW'
    ) {
        continue;
    }

    $totalLow++;

    $countrySource = trim(
        (string) $row[
            $columns['country_source']
        ]
    );

    $countryNormalized = trim(
        (string) $row[
            $columns['country_normalized']
        ]
    );

    $occurrenceCount = (int) (
        $row[
            $columns['monthly_occurrence_count']
        ]
    );

    $suggestedCode = strtoupper(
        trim(
            (string) $row[
                $columns['suggested_country_code']
            ]
        )
    );

    $suggestedNameEn = trim(
        (string) $row[
            $columns['suggested_country_name_en']
        ]
    );

    $matchedCountry =
        $countryByCode[$suggestedCode]
        ?? null;

    $masterExists =
        $matchedCountry !== null;

    $masterId =
        $matchedCountry?->id;

    $masterNameEn =
        $matchedCountry?->country_name_en;

    $masterNameId =
        $matchedCountry?->country_name_id;

    $masterIso3 =
        $matchedCountry?->iso3;

    /*
    |--------------------------------------------------------------------------
    | Validate suggested code
    |--------------------------------------------------------------------------
    */

    if ($masterExists) {

        $masterFound++;

        $validationStatus =
            'CONFIRMED_CODE';

        /*
        |--------------------------------------------------------------------------
        | Check whether suggested display name agrees
        |--------------------------------------------------------------------------
        */

        if (
            $suggestedNameEn !== ''
            &&
            $masterNameEn !== null
            &&
            strcasecmp(
                $suggestedNameEn,
                $masterNameEn
            ) !== 0
        ) {
            $codeConflict++;
            $validationStatus =
                'CODE_EXISTS_NAME_DIFFERS';
        }

    } else {

        $masterMissing++;

        /*
        |--------------------------------------------------------------------------
        | Suggested country code is not present.
        | Try to determine whether it is actually an ISO3.
        |--------------------------------------------------------------------------
        */

        $iso3Match =
            $countryByIso3[$suggestedCode]
            ?? null;

        if ($iso3Match !== null) {

            $iso3Found++;

            $validationStatus =
                'CODE_IS_ISO3_NOT_ISO2';

            $masterId =
                $iso3Match->id;

            $masterNameEn =
                $iso3Match->country_name_en;

            $masterNameId =
                $iso3Match->country_name_id;

            $masterIso3 =
                $iso3Match->iso3;

        } else {

            $validationStatus =
                'NOT_IN_MASTER';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Final recommendation
    |--------------------------------------------------------------------------
    */

    $recommendedStatus =
        match ($validationStatus) {
            'CONFIRMED_CODE' =>
                'APPROVE',

            'CODE_IS_ISO3_NOT_ISO2' =>
                'CORRECT_CODE',

            'CODE_EXISTS_NAME_DIFFERS' =>
                'REVIEW_NAME',

            default =>
                'MANUAL_REVIEW',
        };

    $results[] = [
        'country_source' =>
            $countrySource,

        'country_normalized' =>
            $countryNormalized,

        'monthly_occurrence_count' =>
            $occurrenceCount,

        'suggested_country_code' =>
            $suggestedCode,

        'suggested_country_name_en' =>
            $suggestedNameEn,

        'master_exists' =>
            $masterExists
                ? 'YES'
                : 'NO',

        'master_id' =>
            $masterId,

        'master_country_code' =>
            $matchedCountry?->country_code
                ?? $iso3Match?->country_code,

        'master_iso3' =>
            $masterIso3,

        'master_country_name_en' =>
            $masterNameEn,

        'master_country_name_id' =>
            $masterNameId,

        'validation_status' =>
            $validationStatus,

        'recommended_status' =>
            $recommendedStatus,
    ];
}

fclose($handle);

/*
|--------------------------------------------------------------------------
| Sort by occurrence
|--------------------------------------------------------------------------
*/

usort(
    $results,
    function (array $a, array $b): int {
        return
            $b['monthly_occurrence_count']
            <=>
            $a['monthly_occurrence_count'];
    }
);

/*
|--------------------------------------------------------------------------
| Write output
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
        'suggested_country_name_en',
        'master_exists',
        'master_id',
        'master_country_code',
        'master_iso3',
        'master_country_name_en',
        'master_country_name_id',
        'validation_status',
        'recommended_status',
    ]
);

foreach ($results as $result) {

    fputcsv(
        $output,
        $result
    );
}

fclose($output);

/*
|--------------------------------------------------------------------------
| Summary
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "DIGESTEX LOW COUNTRY CANDIDATE VALIDATION\n";
echo "========================================\n\n";

echo "LOW CANDIDATES          : "
    . $totalLow
    . PHP_EOL;

echo "MASTER CODE FOUND       : "
    . $masterFound
    . PHP_EOL;

echo "MASTER CODE NOT FOUND   : "
    . $masterMissing
    . PHP_EOL;

echo "ISO3 MATCH FOUND        : "
    . $iso3Found
    . PHP_EOL;

echo "CODE / NAME CONFLICT    : "
    . $codeConflict
    . PHP_EOL;

echo "\nVALIDATION BREAKDOWN:\n";

$breakdown = [];

foreach ($results as $result) {

    $status =
        $result['recommended_status'];

    $breakdown[$status] =
        ($breakdown[$status] ?? 0)
        + 1;
}

ksort($breakdown);

foreach ($breakdown as $status => $count) {

    echo sprintf(
        "  %-22s : %d\n",
        $status,
        $count
    );
}

echo "\nOUTPUT:\n";
echo $outputFile . PHP_EOL;

echo "\n========================================\n";
echo "DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";