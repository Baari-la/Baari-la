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
| DIGESTEX TRADE POINT MASTER EXPANSION REVIEW
|--------------------------------------------------------------------------
|
| INPUT:
|   trade_point_residual_curated_2019.csv
|
| OUTPUT:
|   trade_point_master_expansion_review_2019.csv
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
    . 'trade_point_residual_curated_2019.csv';

$outputFile =
    $base
    . DIRECTORY_SEPARATOR
    . 'PROCESSED'
    . DIRECTORY_SEPARATOR
    . 'trade_point_master_expansion_review_2019.csv';

if (!is_file($inputFile)) {
    throw new RuntimeException(
        "Input file tidak ditemukan:\n{$inputFile}"
    );
}

/*
|--------------------------------------------------------------------------
| Candidate metadata
|--------------------------------------------------------------------------
|
| IMPORTANT:
| Only high-confidence structural naming is prepared here.
| Physical province/city are NOT guessed.
|--------------------------------------------------------------------------
*/

$candidates = [

    'TANJUNG BALAI ASAHAN' => [
        'candidate_code' =>
            'TP-TANJUNG-BALAI-ASAHAN-PORT',

        'name' =>
            'Tanjung Balai Asahan Port',

        'name_en' =>
            'Tanjung Balai Asahan Port',

        'trade_point_type_code' =>
            'SEA_PORT',

        'physical_province_code' =>
            'ID-SU',

        'city' =>
            'Tanjung Balai',

        'confidence' =>
            'HIGH',

        'status' =>
            'REVIEW',

        'reason' =>
            'Strong sea-port candidate from residual audit; physical master record should be reviewed before insertion.',
    ],

    'TANJUNG PINANG' => [
        'candidate_code' =>
            'TP-TANJUNG-PINANG-PORT',

        'name' =>
            'Tanjung Pinang Port',

        'name_en' =>
            'Tanjung Pinang Port',

        'trade_point_type_code' =>
            'SEA_PORT',

        'physical_province_code' =>
            'ID-KR',

        'city' =>
            'Tanjung Pinang',

        'confidence' =>
            'HIGH',

        'status' =>
            'REVIEW',

        'reason' =>
            'Strong sea-port candidate from residual audit; physical master record should be reviewed before insertion.',
    ],

    'TEMBILAHAN' => [
        'candidate_code' =>
            'TP-TEMBILAHAN-PORT',

        'name' =>
            'Tembilahan Port',

        'name_en' =>
            'Tembilahan Port',

        'trade_point_type_code' =>
            'SEA_PORT',

        'physical_province_code' =>
            'ID-RI',

        'city' =>
            'Tembilahan',

        'confidence' =>
            'MEDIUM',

        'status' =>
            'REVIEW',

        'reason' =>
            'Likely sea-port candidate from residual audit; requires physical master confirmation.',
    ],

    'TENAU' => [
        'candidate_code' =>
            'TP-TENAU-PORT',

        'name' =>
            'Tenau Port',

        'name_en' =>
            'Tenau Port',

        'trade_point_type_code' =>
            'SEA_PORT',

        'physical_province_code' =>
            'ID-NT',

        'city' =>
            'Kupang',

        'confidence' =>
            'MEDIUM',

        'status' =>
            'REVIEW',

        'reason' =>
            'Likely Tenau Port in Kupang; physical master confirmation required.',
    ],

    'KUALA ENOK' => [
        'candidate_code' =>
            'TP-KUALA-ENOK-PORT',

        'name' =>
            'Kuala Enok Port',

        'name_en' =>
            'Kuala Enok Port',

        'trade_point_type_code' =>
            'SEA_PORT',

        'physical_province_code' =>
            'ID-RI',

        'city' =>
            'Indragiri Hilir',

        'confidence' =>
            'MEDIUM',

        'status' =>
            'REVIEW',

        'reason' =>
            'Likely Kuala Enok Port; physical master confirmation required.',
    ],

    'BANDARA INTERNASIONAL LOMBOK' => [
        'candidate_code' =>
            'TP-BANDARA-INTERNASIONAL-LOMBOK',

        'name' =>
            'Lombok International Airport',

        'name_en' =>
            'Lombok International Airport',

        'trade_point_type_code' =>
            'AIRPORT',

        'physical_province_code' =>
            'ID-NB',

        'city' =>
            'Praya',

        'confidence' =>
            'HIGH',

        'status' =>
            'REVIEW',

        'reason' =>
            'Airport identity is clear; verify canonical city/province fields before insertion.',
    ],
];

/*
|--------------------------------------------------------------------------
| Load current master
|--------------------------------------------------------------------------
*/

$master = DB::table('trade_points')
    ->where('is_active', true)
    ->get([
        'id',
        'code',
        'name',
        'name_en',
        'trade_point_type_id',
        'province_id',
        'city',
    ]);

$masterByCode = [];

$masterByName = [];

foreach ($master as $tp) {

    $masterByCode[
        strtoupper(
            trim(
                (string) $tp->code
            )
        )
    ] = $tp;

    foreach ([
        $tp->name,
        $tp->name_en,
    ] as $name) {

        $normalized =
            mb_strtoupper(
                trim(
                    (string) $name
                )
            );

        if ($normalized !== '') {
            $masterByName[
                $normalized
            ] = $tp;
        }
    }
}

/*
|--------------------------------------------------------------------------
| Load trade point types
|--------------------------------------------------------------------------
*/

$typeRows = DB::table('trade_point_types')
    ->where('is_active', true)
    ->get([
        'id',
        'code',
    ]);

$typeIds = [];

foreach ($typeRows as $type) {
    $typeIds[
        strtoupper(
            trim(
                (string) $type->code
            )
        )
    ] = (int) $type->id;
}

/*
|--------------------------------------------------------------------------
| Read residual curation
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
        'Header residual curation tidak ditemukan.'
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
| Build output
|--------------------------------------------------------------------------
*/

$results = [];

$candidateCount = 0;
$existingCollision = 0;
$nameCollision = 0;

while (($row = fgetcsv($handle)) !== false) {

    $source = trim(
        (string) $row[
            $columns[
                'trade_point_source'
            ]
        ]
    );

    if (!isset($candidates[$source])) {
        continue;
    }

    $candidate = $candidates[$source];

    $candidateCount++;

    $code =
        strtoupper(
            trim(
                (string) $candidate['candidate_code']
            )
        );

    $nameNormalized =
        mb_strtoupper(
            trim(
                (string) $candidate['name']
            )
        );

    /*
    |--------------------------------------------------------------------------
    | Code collision
    |--------------------------------------------------------------------------
    */

    $codeStatus = 'PASS';

    if (isset($masterByCode[$code])) {

        $codeStatus =
            'COLLISION_EXISTING_MASTER';

        $existingCollision++;
    }

    /*
    |--------------------------------------------------------------------------
    | Name collision
    |--------------------------------------------------------------------------
    */

    $nameStatus = 'PASS';

    if (isset($masterByName[$nameNormalized])) {

        $nameStatus =
            'COLLISION_EXISTING_MASTER';

        $nameCollision++;
    }

    /*
    |--------------------------------------------------------------------------
    | Type validation
    |--------------------------------------------------------------------------
    */

    $typeCode =
        strtoupper(
            trim(
                (string) $candidate[
                    'trade_point_type_code'
                ]
            )
        );

    $typeStatus =
        isset(
            $typeIds[$typeCode]
        )
            ? 'PASS'
            : 'INVALID_TYPE';

    /*
    |--------------------------------------------------------------------------
    | Overall
    |--------------------------------------------------------------------------
    */

    $overallStatus = 'PASS';
    $remarks = [];

    if ($codeStatus !== 'PASS') {
        $overallStatus = 'REVIEW';
        $remarks[] = $codeStatus;
    }

    if ($nameStatus !== 'PASS') {
        $overallStatus = 'REVIEW';
        $remarks[] = $nameStatus;
    }

    if ($typeStatus !== 'PASS') {
        $overallStatus = 'REVIEW';
        $remarks[] = $typeStatus;
    }

    /*
    |--------------------------------------------------------------------------
    | Output
    |--------------------------------------------------------------------------
    */

    $results[] = [
        'trade_point_source' =>
            $source,

        'candidate_code' =>
            $code,

        'candidate_name' =>
            $candidate['name'],

        'candidate_name_en' =>
            $candidate['name_en'],

        'trade_point_type_code' =>
            $typeCode,

        'trade_point_type_id' =>
            $typeIds[$typeCode] ?? null,

        'physical_province_code' =>
            $candidate[
                'physical_province_code'
            ],

        'city' =>
            $candidate['city'],

        'confidence' =>
            $candidate['confidence'],

        'code_status' =>
            $codeStatus,

        'name_status' =>
            $nameStatus,

        'type_status' =>
            $typeStatus,

        'overall_status' =>
            $overallStatus,

        'remarks' =>
            implode(
                '|',
                $remarks
            ),

        'reason' =>
            $candidate['reason'],
    ];
}

fclose($handle);

/*
|--------------------------------------------------------------------------
| Sort
|--------------------------------------------------------------------------
*/

usort(
    $results,
    function (
        array $a,
        array $b
    ): int {
        return strcmp(
            $a['trade_point_source'],
            $b['trade_point_source']
        );
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
        'trade_point_source',
        'candidate_code',
        'candidate_name',
        'candidate_name_en',
        'trade_point_type_code',
        'trade_point_type_id',
        'physical_province_code',
        'city',
        'confidence',
        'code_status',
        'name_status',
        'type_status',
        'overall_status',
        'remarks',
        'reason',
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

$passCount = count(
    array_filter(
        $results,
        fn (array $row): bool =>
            $row['overall_status'] === 'PASS'
    )
);

$reviewCount =
    count($results) - $passCount;

echo "========================================\n";
echo "DIGESTEX TRADE POINT MASTER EXPANSION\n";
echo "========================================\n\n";

echo "NEW CANDIDATES          : "
    . $candidateCount
    . PHP_EOL;

echo "CODE COLLISIONS         : "
    . $existingCollision
    . PHP_EOL;

echo "NAME COLLISIONS         : "
    . $nameCollision
    . PHP_EOL;

echo "PASS                    : "
    . $passCount
    . PHP_EOL;

echo "REVIEW                  : "
    . $reviewCount
    . PHP_EOL;

echo "\nCURRENT CANONICAL MASTER: "
    . $master->count()
    . PHP_EOL;

echo "PROJECTED MASTER        : "
    . (
        $master->count()
        +
        $passCount
    )
    . PHP_EOL;

echo "\nOUTPUT:\n";
echo $outputFile . PHP_EOL;

echo "\n========================================\n";
echo "DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";