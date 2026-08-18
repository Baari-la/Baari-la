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
| DIGESTEX TRADE COUNTRY MASTER EXPANSION VALIDATOR
|--------------------------------------------------------------------------
|
| INPUT:
|   trade_country_master_expansion_review_v2_2019.csv
|
| VALIDATES:
|   - ISO2 uniqueness
|   - ISO3 uniqueness
|   - no collision with current mst_countries
|   - duplicate candidate names
|   - entity classification
|
| DATABASE:
|   READ ONLY
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
    . 'trade_country_master_expansion_review_v2_2019.csv';

$outputFile =
    $base
    . DIRECTORY_SEPARATOR
    . 'PROCESSED'
    . DIRECTORY_SEPARATOR
    . 'trade_country_master_expansion_validation_2019.csv';

if (!is_file($inputFile)) {
    throw new RuntimeException(
        "Input file tidak ditemukan:\n{$inputFile}"
    );
}

/*
|--------------------------------------------------------------------------
| Load existing master
|--------------------------------------------------------------------------
*/

$masterRows = DB::table('mst_countries')
    ->get([
        'id',
        'country_code',
        'iso3',
        'country_name_en',
        'country_name_id',
    ]);

$masterByCode = [];
$masterByIso3 = [];
$masterByNameEn = [];
$masterByNameId = [];

foreach ($masterRows as $country) {

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

    $nameEn = strtoupper(
        trim(
            (string) $country->country_name_en
        )
    );

    $nameId = strtoupper(
        trim(
            (string) $country->country_name_id
        )
    );

    if ($code !== '') {
        $masterByCode[$code] = $country;
    }

    if ($iso3 !== '') {
        $masterByIso3[$iso3] = $country;
    }

    if ($nameEn !== '') {
        $masterByNameEn[$nameEn] = $country;
    }

    if ($nameId !== '') {
        $masterByNameId[$nameId] = $country;
    }
}

/*
|--------------------------------------------------------------------------
| Read review V2
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

$required = [
    'country_source',
    'candidate_iso2',
    'candidate_iso3',
    'candidate_name_en',
    'candidate_name_id',
    'entity_class',
    'review_status',
];

foreach ($required as $column) {

    if (!isset($columns[$column])) {
        fclose($handle);

        throw new RuntimeException(
            "Column tidak ditemukan: {$column}"
        );
    }
}

/*
|--------------------------------------------------------------------------
| Tracking
|--------------------------------------------------------------------------
*/

$rows = [];

$candidateIso2 = [];
$candidateIso3 = [];
$candidateNames = [];

$approvedCountry = 0;
$approvedTerritory = 0;
$historicalHold = 0;
$reviewRows = 0;

$iso2Collision = 0;
$iso3Collision = 0;
$nameCollision = 0;

$duplicateCandidateIso2 = 0;
$duplicateCandidateIso3 = 0;
$duplicateCandidateName = 0;

$totalExpansionCandidates = 0;

/*
|--------------------------------------------------------------------------
| Validate rows
|--------------------------------------------------------------------------
*/

while (($row = fgetcsv($handle)) !== false) {

    $source = trim(
        (string) $row[
            $columns['country_source']
        ]
    );

    $iso2 = strtoupper(
        trim(
            (string) $row[
                $columns['candidate_iso2']
            ]
        )
    );

    $iso3 = strtoupper(
        trim(
            (string) $row[
                $columns['candidate_iso3']
            ]
        )
    );

    $nameEn = trim(
        (string) $row[
            $columns['candidate_name_en']
        ]
    );

    $nameId = trim(
        (string) $row[
            $columns['candidate_name_id']
        ]
    );

    $entityClass = strtoupper(
        trim(
            (string) $row[
                $columns['entity_class']
            ]
        )
    );

    $reviewStatus = strtoupper(
        trim(
            (string) $row[
                $columns['review_status']
            ]
        )
    );

    /*
    |--------------------------------------------------------------------------
    | Only classify expansion candidates and historical hold
    |--------------------------------------------------------------------------
    */

    if ($entityClass === 'COUNTRY') {
        $approvedCountry++;
    } elseif ($entityClass === 'TERRITORY') {
        $approvedTerritory++;
    } elseif ($entityClass === 'HISTORICAL') {
        $historicalHold++;
    } else {
        $reviewRows++;
    }

    if (
        !in_array(
            $entityClass,
            ['COUNTRY', 'TERRITORY'],
            true
        )
    ) {
        $rows[] = [
            'country_source' => $source,
            'candidate_iso2' => $iso2,
            'candidate_iso3' => $iso3,
            'candidate_name_en' => $nameEn,
            'candidate_name_id' => $nameId,
            'entity_class' => $entityClass,
            'review_status' => $reviewStatus,
            'iso2_status' => 'NOT_VALIDATED',
            'iso3_status' => 'NOT_VALIDATED',
            'name_status' => 'NOT_VALIDATED',
            'overall_status' => $entityClass === 'HISTORICAL'
                ? 'HOLD'
                : 'REVIEW',
            'remarks' => $entityClass === 'HISTORICAL'
                ? 'Historical geography held out of master expansion.'
                : 'Manual review.',
        ];

        continue;
    }

    $totalExpansionCandidates++;

    /*
    |--------------------------------------------------------------------------
    | ISO2 validation
    |--------------------------------------------------------------------------
    */

    $iso2Status = 'PASS';

    if ($iso2 === '') {

        $iso2Status = 'MISSING_ISO2';

    } elseif (isset($masterByCode[$iso2])) {

        $iso2Status = 'COLLISION_EXISTING_MASTER';
        $iso2Collision++;

    } elseif (isset($candidateIso2[$iso2])) {

        $iso2Status = 'DUPLICATE_CANDIDATE';
        $duplicateCandidateIso2++;

    } else {

        $candidateIso2[$iso2] = $source;
    }

    /*
    |--------------------------------------------------------------------------
    | ISO3 validation
    |--------------------------------------------------------------------------
    */

    $iso3Status = 'PASS';

    if ($iso3 === '') {

        $iso3Status = 'MISSING_ISO3';

    } elseif (isset($masterByIso3[$iso3])) {

        $iso3Status = 'COLLISION_EXISTING_MASTER';
        $iso3Collision++;

    } elseif (isset($candidateIso3[$iso3])) {

        $iso3Status = 'DUPLICATE_CANDIDATE';
        $duplicateCandidateIso3++;

    } else {

        $candidateIso3[$iso3] = $source;
    }

    /*
    |--------------------------------------------------------------------------
    | Name validation
    |--------------------------------------------------------------------------
    */

    $nameStatus = 'PASS';

    $nameEnKey =
        strtoupper($nameEn);

    $nameIdKey =
        strtoupper($nameId);

    if (
        $nameEnKey !== ''
        &&
        isset(
            $masterByNameEn[
                $nameEnKey
            ]
        )
    ) {

        $nameStatus =
            'NAME_COLLISION_EXISTING_MASTER';

        $nameCollision++;

    } elseif (
        $nameIdKey !== ''
        &&
        isset(
            $masterByNameId[
                $nameIdKey
            ]
        )
    ) {

        $nameStatus =
            'NAME_COLLISION_EXISTING_MASTER';

        $nameCollision++;

    } elseif (
        $nameEnKey !== ''
        &&
        isset(
            $candidateNames[
                $nameEnKey
            ]
        )
    ) {

        $nameStatus =
            'DUPLICATE_CANDIDATE_NAME';

        $duplicateCandidateName++;

    } elseif ($nameEnKey !== '') {

        $candidateNames[
            $nameEnKey
        ] = $source;
    }

    /*
    |--------------------------------------------------------------------------
    | Overall
    |--------------------------------------------------------------------------
    */

    $overallStatus = 'PASS';
    $remarks = [];

    if (
        $iso2Status !== 'PASS'
    ) {
        $overallStatus = 'REVIEW';
        $remarks[] = $iso2Status;
    }

    if (
        $iso3Status !== 'PASS'
        &&
        $iso3Status !== 'MISSING_ISO3'
    ) {
        $overallStatus = 'REVIEW';
        $remarks[] = $iso3Status;
    }

    if (
        $entityClass === 'COUNTRY'
        &&
        $iso3Status === 'MISSING_ISO3'
    ) {
        $overallStatus = 'REVIEW';
        $remarks[] = 'COUNTRY_REQUIRES_ISO3';
    }

    if (
        $nameStatus !== 'PASS'
    ) {
        $overallStatus = 'REVIEW';
        $remarks[] = $nameStatus;
    }

    $rows[] = [
        'country_source' =>
            $source,

        'candidate_iso2' =>
            $iso2,

        'candidate_iso3' =>
            $iso3,

        'candidate_name_en' =>
            $nameEn,

        'candidate_name_id' =>
            $nameId,

        'entity_class' =>
            $entityClass,

        'review_status' =>
            $reviewStatus,

        'iso2_status' =>
            $iso2Status,

        'iso3_status' =>
            $iso3Status,

        'name_status' =>
            $nameStatus,

        'overall_status' =>
            $overallStatus,

        'remarks' =>
            implode(
                '|',
                $remarks
            ),
    ];
}

fclose($handle);

/*
|--------------------------------------------------------------------------
| Write validation CSV
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
        'candidate_iso2',
        'candidate_iso3',
        'candidate_name_en',
        'candidate_name_id',
        'entity_class',
        'review_status',
        'iso2_status',
        'iso3_status',
        'name_status',
        'overall_status',
        'remarks',
    ]
);

foreach ($rows as $result) {

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
echo "DIGESTEX COUNTRY MASTER EXPANSION VALIDATOR\n";
echo "========================================\n\n";

echo "COUNTRY CANDIDATES      : "
    . $approvedCountry
    . PHP_EOL;

echo "TERRITORY CANDIDATES    : "
    . $approvedTerritory
    . PHP_EOL;

echo "HISTORICAL HOLD         : "
    . $historicalHold
    . PHP_EOL;

echo "MANUAL REVIEW ROWS      : "
    . $reviewRows
    . PHP_EOL;

echo "\nVALIDATION:\n";

echo "  ISO2 EXISTING COLLISION    : "
    . $iso2Collision
    . PHP_EOL;

echo "  ISO3 EXISTING COLLISION    : "
    . $iso3Collision
    . PHP_EOL;

echo "  NAME EXISTING COLLISION    : "
    . $nameCollision
    . PHP_EOL;

echo "  DUPLICATE CANDIDATE ISO2   : "
    . $duplicateCandidateIso2
    . PHP_EOL;

echo "  DUPLICATE CANDIDATE ISO3   : "
    . $duplicateCandidateIso3
    . PHP_EOL;

echo "  DUPLICATE CANDIDATE NAME   : "
    . $duplicateCandidateName
    . PHP_EOL;

$passCount = count(
    array_filter(
        $rows,
        fn (array $row): bool =>
            $row['overall_status'] === 'PASS'
    )
);

$reviewCount = count(
    $rows
) - $passCount;

echo "\nRESULT:\n";

echo "  PASS                     : "
    . $passCount
    . PHP_EOL;

echo "  REVIEW                   : "
    . $reviewCount
    . PHP_EOL;

echo "\nCURRENT MASTER COUNTRIES  : "
    . $masterRows->count()
    . PHP_EOL;

echo "PROJECTED MASTER V2       : "
    . (
        $masterRows->count()
        +
        $passCount
    )
    . PHP_EOL;

echo "\nOUTPUT:\n";
echo $outputFile . PHP_EOL;

echo "\n========================================\n";
echo "DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";