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
| DIGESTEX TRADE POINT RESIDUAL CURATION
|--------------------------------------------------------------------------
|
| INPUT:
|   trade_point_residual_review_2019.csv
|
| PURPOSE:
|   Curate strong residual mappings without modifying DB.
|
| OUTPUT:
|   trade_point_residual_curated_2019.csv
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
    . 'trade_point_residual_review_2019.csv';

$outputFile =
    $base
    . DIRECTORY_SEPARATOR
    . 'PROCESSED'
    . DIRECTORY_SEPARATOR
    . 'trade_point_residual_curated_2019.csv';

if (!is_file($inputFile)) {
    throw new RuntimeException(
        "Input file tidak ditemukan:\n{$inputFile}"
    );
}

/*
|--------------------------------------------------------------------------
| Strong curated mappings
|--------------------------------------------------------------------------
|
| canonical_code must already exist in trade_points.
|--------------------------------------------------------------------------
*/

$curated = [

    'PANJANG' => [
        'canonical_code' =>
            'TP-PANJANG-PORT',
        'review_status' =>
            'APPROVED',
        'confidence' =>
            'HIGH',
        'reason' =>
            'Source trade point matches existing Panjang Port canonical.',
    ],

    'AMAMAPARE' => [
        'canonical_code' =>
            'TP-AMAMAPARE-PORT',
        'review_status' =>
            'APPROVED',
        'confidence' =>
            'HIGH',
        'reason' =>
            'Source trade point matches existing Amamapare Port canonical.',
    ],

    'SEPINGGAN (U)' => [
        'canonical_code' =>
            'TP-SULTAN-AJI-MUHAMMAD-SULAIMAN-SEPINGGAN-INTERNATIONAL-AIRPORT',
        'review_status' =>
            'APPROVED',
        'confidence' =>
            'HIGH',
        'reason' =>
            'U suffix indicates airport source alias; matches Sepinggan airport canonical.',
    ],

    'SOLO/JEBRES/ADI SUMARMO (U)' => [
        'canonical_code' =>
            'TP-ADI-SOEMARMO-AIRPORT',
        'review_status' =>
            'APPROVED',
        'confidence' =>
            'HIGH',
        'reason' =>
            'Source explicitly names Adi Soemarmo airport.',
    ],

    'TENAU' => [
        'canonical_code' =>
            null,
        'review_status' =>
            'REVIEW',
        'confidence' =>
            'MEDIUM',
        'reason' =>
            'Likely Tenau Port, but canonical trade point does not yet exist in master.',
    ],

    'KUALA ENOK' => [
        'canonical_code' =>
            null,
        'review_status' =>
            'REVIEW',
        'confidence' =>
            'MEDIUM',
        'reason' =>
            'Likely Kuala Enok Port, but canonical trade point does not yet exist in master.',
    ],

    'TANJUNG BALAI ASAHAN' => [
        'canonical_code' =>
            null,
        'review_status' =>
            'REVIEW',
        'confidence' =>
            'HIGH',
        'reason' =>
            'Strong sea-port candidate, but new canonical trade point is required.',
    ],

    'TANJUNG PINANG' => [
        'canonical_code' =>
            null,
        'review_status' =>
            'REVIEW',
        'confidence' =>
            'HIGH',
        'reason' =>
            'Strong sea-port candidate, but new canonical trade point is required.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Deliberately unresolved
    |--------------------------------------------------------------------------
    */

    'BUATAN' => [
        'canonical_code' =>
            null,
        'review_status' =>
            'REVIEW',
        'confidence' =>
            'LOW',
        'reason' =>
            'Location name alone is insufficient to determine physical trade point.',
    ],

    'JAYAPURA' => [
        'canonical_code' =>
            null,
        'review_status' =>
            'REVIEW',
        'confidence' =>
            'LOW',
        'reason' =>
            'Could represent more than one trade facility.',
    ],

    'NUNUKAN' => [
        'canonical_code' =>
            null,
        'review_status' =>
            'REVIEW',
        'confidence' =>
            'LOW',
        'reason' =>
            'Could represent port or border trade point; requires physical classification.',
    ],

    'NONGSA' => [
        'canonical_code' =>
            null,
        'review_status' =>
            'REVIEW',
        'confidence' =>
            'LOW',
        'reason' =>
            'Location is insufficient to distinguish trade facility.',
    ],

    'PONTIANAK' => [
        'canonical_code' =>
            null,
        'review_status' =>
            'REVIEW',
        'confidence' =>
            'LOW',
        'reason' =>
            'City name alone is insufficient to select canonical facility.',
    ],

    'TEMBILAHAN' => [
        'canonical_code' =>
            null,
        'review_status' =>
            'REVIEW',
        'confidence' =>
            'MEDIUM',
        'reason' =>
            'Likely Tembilahan Port, but canonical trade point is not yet in master.',
    ],

    'LOBAM' => [
        'canonical_code' =>
            null,
        'review_status' =>
            'REVIEW',
        'confidence' =>
            'LOW',
        'reason' =>
            'Location name alone is insufficient to select canonical facility.',
    ],

    'BALIKPAPAN' => [
        'canonical_code' =>
            null,
        'review_status' =>
            'REVIEW',
        'confidence' =>
            'LOW',
        'reason' =>
            'Could refer to airport or seaport; source name is ambiguous.',
    ],

    'PEKAN BARU' => [
        'canonical_code' =>
            null,
        'review_status' =>
            'REVIEW',
        'confidence' =>
            'LOW',
        'reason' =>
            'City name alone is insufficient to select canonical facility.',
    ],

    'SEMARANG (PTT)' => [
        'canonical_code' =>
            null,
        'review_status' =>
            'REVIEW',
        'confidence' =>
            'MEDIUM',
        'reason' =>
            'PTT indicates a trade point/terminal, but canonical facility is not established.',
    ],

    'BANDARA INTERNASIONAL LOMBOK' => [
        'canonical_code' =>
            null,
        'review_status' =>
            'REVIEW',
        'confidence' =>
            'HIGH',
        'reason' =>
            'Airport identity is clear, but canonical airport record is not yet in master.',
    ],
];

/*
|--------------------------------------------------------------------------
| Load canonical master
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
    ])
    ->keyBy('code');

if ($master->isEmpty()) {
    throw new RuntimeException(
        'trade_points kosong.'
    );
}

/*
|--------------------------------------------------------------------------
| Read residual audit
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
        'Header residual review tidak ditemukan.'
    );
}

$columns = [];

foreach ($header as $index => $name) {
    $columns[
        trim((string) $name)
    ] = $index;
}

foreach ([
    'trade_point_source',
    'normalized_name',
    'occurrence_count',
    'source_provinces',
    'source_countries',
    'export_seen',
    'import_seen',
] as $required) {

    if (!isset($columns[$required])) {
        fclose($handle);

        throw new RuntimeException(
            "Column tidak ditemukan: {$required}"
        );
    }
}

/*
|--------------------------------------------------------------------------
| Build curated output
|--------------------------------------------------------------------------
*/

$results = [];

$approvedCount = 0;
$reviewCount = 0;
$newCanonicalCandidates = 0;

while (($row = fgetcsv($handle)) !== false) {

    $source = trim(
        (string) $row[
            $columns['trade_point_source']
        ]
    );

    $normalized = trim(
        (string) $row[
            $columns['normalized_name']
        ]
    );

    $mapping =
        $curated[$source]
        ?? [
            'canonical_code' => null,
            'review_status' => 'REVIEW',
            'confidence' => 'LOW',
            'reason' =>
                'No curated mapping defined.',
        ];

    $canonicalCode =
        $mapping['canonical_code'];

    $canonical = null;

    if ($canonicalCode !== null) {

        $canonical =
            $master[$canonicalCode]
            ?? null;

        if ($canonical === null) {

            throw new RuntimeException(
                "Canonical code tidak ditemukan di master: "
                . $canonicalCode
                . " untuk "
                . $source
            );
        }

        $approvedCount++;

    } else {

        $reviewCount++;

        if (
            in_array(
                $source,
                [
                    'TENAU',
                    'KUALA ENOK',
                    'TANJUNG BALAI ASAHAN',
                    'TANJUNG PINANG',
                    'TEMBILAHAN',
                    'BANDARA INTERNASIONAL LOMBOK',
                ],
                true
            )
        ) {
            $newCanonicalCandidates++;
        }
    }

    $results[] = [
        'trade_point_source' =>
            $source,

        'normalized_name' =>
            $normalized,

        'occurrence_count' =>
            (int) (
                $row[
                    $columns['occurrence_count']
                ]
            ),

        'source_provinces' =>
            $row[
                $columns['source_provinces']
            ],

        'source_countries' =>
            $row[
                $columns['source_countries']
            ],

        'export_seen' =>
            $row[
                $columns['export_seen']
            ],

        'import_seen' =>
            $row[
                $columns['import_seen']
            ],

        'canonical_code' =>
            $canonical?->code,

        'canonical_name' =>
            $canonical?->name,

        'canonical_type_code' =>
            $canonical !== null
                ? DB::table('trade_point_types')
                    ->where(
                        'id',
                        $canonical->trade_point_type_id
                    )
                    ->value('code')
                : null,

        'canonical_province_id' =>
            $canonical?->province_id,

        'canonical_city' =>
            $canonical?->city,

        'confidence' =>
            $mapping['confidence'],

        'review_status' =>
            $mapping['review_status'],

        'reason' =>
            $mapping['reason'],
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
        return
            $b['occurrence_count']
            <=>
            $a['occurrence_count'];
    }
);

/*
|--------------------------------------------------------------------------
| Output
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
        'normalized_name',
        'occurrence_count',
        'source_provinces',
        'source_countries',
        'export_seen',
        'import_seen',
        'canonical_code',
        'canonical_name',
        'canonical_type_code',
        'canonical_province_id',
        'canonical_city',
        'confidence',
        'review_status',
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

echo "========================================\n";
echo "DIGESTEX TRADE POINT RESIDUAL CURATION\n";
echo "========================================\n\n";

echo "SOURCE NAMES             : "
    . count($results)
    . PHP_EOL;

echo "APPROVED EXISTING        : "
    . $approvedCount
    . PHP_EOL;

echo "REVIEW                   : "
    . $reviewCount
    . PHP_EOL;

echo "NEW CANONICAL CANDIDATES : "
    . $newCanonicalCandidates
    . PHP_EOL;

echo "\nOUTPUT:\n";
echo $outputFile . PHP_EOL;

echo "\n========================================\n";
echo "DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";