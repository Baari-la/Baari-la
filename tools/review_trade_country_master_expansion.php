<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

/*
|--------------------------------------------------------------------------
| DIGESTEX COUNTRY MASTER EXPANSION REVIEW V2
|--------------------------------------------------------------------------
|
| READ ONLY
|
| Input:
|   trade_country_master_expansion_review_2019.csv
|
| Output:
|   trade_country_master_expansion_review_v2_2019.csv
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
    . 'trade_country_master_expansion_review_2019.csv';

$outputFile =
    $base
    . DIRECTORY_SEPARATOR
    . 'PROCESSED'
    . DIRECTORY_SEPARATOR
    . 'trade_country_master_expansion_review_v2_2019.csv';

if (!is_file($inputFile)) {
    throw new RuntimeException(
        "Input file tidak ditemukan:\n{$inputFile}"
    );
}

/*
|--------------------------------------------------------------------------
| Entity classification
|--------------------------------------------------------------------------
|
| Important:
| Classification is conservative.
| No database modification.
|--------------------------------------------------------------------------
*/

$entityClassification = [

    /*
    |--------------------------------------------------------------------------
    | Historical geography
    |--------------------------------------------------------------------------
    */

    'NETHERLANDS ANTILLES' => [
        'entity_class' => 'HISTORICAL',
        'review_status' => 'HOLD',
        'review_reason' =>
            'Historical geography; do not add as a current country record without dedicated historical-country policy.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Territories / special trade geographies
    |--------------------------------------------------------------------------
    */

    'POLYNESIA PERANCIS' => [
        'entity_class' => 'TERRITORY',
        'review_status' => 'REVIEW',
        'review_reason' =>
            'Non-sovereign trade geography; review whether Digestex should maintain as separate trade geography.',
    ],

    'REUNION' => [
        'entity_class' => 'TERRITORY',
        'review_status' => 'REVIEW',
        'review_reason' =>
            'Overseas French trade geography; review master classification.',
    ],

    'GUADELOUPE' => [
        'entity_class' => 'TERRITORY',
        'review_status' => 'REVIEW',
        'review_reason' =>
            'Overseas French trade geography; review master classification.',
    ],

    'PUERTO RICO' => [
        'entity_class' => 'TERRITORY',
        'review_status' => 'REVIEW',
        'review_reason' =>
            'US territory; review separate trade geography treatment.',
    ],

    'GUAM' => [
        'entity_class' => 'TERRITORY',
        'review_status' => 'REVIEW',
        'review_reason' =>
            'US territory; review separate trade geography treatment.',
    ],

    'KEPULAUAN COOK' => [
        'entity_class' => 'TERRITORY',
        'review_status' => 'REVIEW',
        'review_reason' =>
            'Special self-governing geography; review master treatment.',
    ],

    'MARTINIK' => [
        'entity_class' => 'TERRITORY',
        'review_status' => 'REVIEW',
        'review_reason' =>
            'Overseas French trade geography; review master classification.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Current countries
    |--------------------------------------------------------------------------
    */

    'FEDERASI RUSIA' => [
        'entity_class' => 'COUNTRY',
        'review_status' => 'REVIEW',
        'review_reason' =>
            'Current country candidate; approve master expansion.',
    ],

    'SWEDIA' => [
        'entity_class' => 'COUNTRY',
        'review_status' => 'REVIEW',
        'review_reason' =>
            'Current country candidate; approve master expansion.',
    ],

    'DENMARK' => [
        'entity_class' => 'COUNTRY',
        'review_status' => 'REVIEW',
        'review_reason' =>
            'Current country candidate; approve master expansion.',
    ],

    'NORWEGIA' => [
        'entity_class' => 'COUNTRY',
        'review_status' => 'REVIEW',
        'review_reason' =>
            'Current country candidate; approve master expansion.',
    ],

    'YUNANI' => [
        'entity_class' => 'COUNTRY',
        'review_status' => 'REVIEW',
        'review_reason' =>
            'Current country candidate; approve master expansion.',
    ],

    'KROASIA' => [
        'entity_class' => 'COUNTRY',
        'review_status' => 'REVIEW',
        'review_reason' =>
            'Current country candidate; approve master expansion.',
    ],

    'SERBIA' => [
        'entity_class' => 'COUNTRY',
        'review_status' => 'REVIEW',
        'review_reason' =>
            'Current country candidate; approve master expansion.',
    ],

    'HONGARIA' => [
        'entity_class' => 'COUNTRY',
        'review_status' => 'REVIEW',
        'review_reason' =>
            'Current country candidate; approve master expansion.',
    ],

    'UKRAINA' => [
        'entity_class' => 'COUNTRY',
        'review_status' => 'REVIEW',
        'review_reason' =>
            'Current country candidate; approve master expansion.',
    ],

    'SLOVENIA' => [
        'entity_class' => 'COUNTRY',
        'review_status' => 'REVIEW',
        'review_reason' =>
            'Current country candidate; approve master expansion.',
    ],

    'IRLANDIA' => [
        'entity_class' => 'COUNTRY',
        'review_status' => 'REVIEW',
        'review_reason' =>
            'Current country candidate; approve master expansion.',
    ],

    'SOMALIA' => [
        'entity_class' => 'COUNTRY',
        'review_status' => 'REVIEW',
        'review_reason' =>
            'Current country candidate; approve master expansion.',
    ],

    'FINLANDIA' => [
        'entity_class' => 'COUNTRY',
        'review_status' => 'REVIEW',
        'review_reason' =>
            'Current country candidate; approve master expansion.',
    ],

    'BULGARIA' => [
        'entity_class' => 'COUNTRY',
        'review_status' => 'REVIEW',
        'review_reason' =>
            'Current country candidate; approve master expansion.',
    ],

    'SINEGAL' => [
        'entity_class' => 'COUNTRY',
        'review_status' => 'REVIEW',
        'review_reason' =>
            'Current country candidate; approve master expansion.',
    ],

    'SEYCHELLES' => [
        'entity_class' => 'COUNTRY',
        'review_status' => 'REVIEW',
        'review_reason' =>
            'Current country candidate; approve master expansion.',
    ],

    'TOGO' => [
        'entity_class' => 'COUNTRY',
        'review_status' => 'REVIEW',
        'review_reason' =>
            'Current country candidate; approve master expansion.',
    ],

    'SAINT KITTS DAN NEVIS' => [
        'entity_class' => 'COUNTRY',
        'review_status' => 'REVIEW',
        'review_reason' =>
            'Current country candidate; approve master expansion.',
    ],

    'LATVIA' => [
        'entity_class' => 'COUNTRY',
        'review_status' => 'REVIEW',
        'review_reason' =>
            'Current country candidate; approve master expansion.',
    ],

    'YAMAN' => [
        'entity_class' => 'COUNTRY',
        'review_status' => 'REVIEW',
        'review_reason' =>
            'Current country candidate; approve master expansion.',
    ],

    'KAMERUN' => [
        'entity_class' => 'COUNTRY',
        'review_status' => 'REVIEW',
        'review_reason' =>
            'Current country candidate; approve master expansion.',
    ],

    'SUDAN' => [
        'entity_class' => 'COUNTRY',
        'review_status' => 'REVIEW',
        'review_reason' =>
            'Current country candidate; approve master expansion.',
    ],

    'ESTONIA' => [
        'entity_class' => 'COUNTRY',
        'review_status' => 'REVIEW',
        'review_reason' =>
            'Current country candidate; approve master expansion.',
    ],

    'MADAGASKAR' => [
        'entity_class' => 'COUNTRY',
        'review_status' => 'REVIEW',
        'review_reason' =>
            'Current country candidate; approve master expansion.',
    ],
];

/*
|--------------------------------------------------------------------------
| Read source CSV
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

$requiredColumns = [
    'country_source',
    'occurrence_count',
    'candidate_iso2',
    'candidate_iso3',
    'candidate_name_en',
    'candidate_name_id',
    'candidate_confidence',
    'master_status',
    'recommendation',
];

foreach ($requiredColumns as $column) {

    if (!isset($columns[$column])) {
        fclose($handle);

        throw new RuntimeException(
            "Column tidak ditemukan: {$column}"
        );
    }
}

/*
|--------------------------------------------------------------------------
| Build output rows
|--------------------------------------------------------------------------
*/

$results = [];

$total = 0;
$classifiedCountry = 0;
$classifiedTerritory = 0;
$classifiedHistorical = 0;
$unclassified = 0;

while (($row = fgetcsv($handle)) !== false) {

    $total++;

    $source = trim(
        (string) $row[
            $columns['country_source']
        ]
    );

    $classification =
        $entityClassification[
            $source
        ] ?? null;

    if ($classification === null) {

        $entityClass =
            'REVIEW';

        $reviewStatus =
            'MANUAL_REVIEW';

        $reviewReason =
            'No curated entity classification yet.';

        $unclassified++;

    } else {

        $entityClass =
            $classification['entity_class'];

        $reviewStatus =
            $classification['review_status'];

        $reviewReason =
            $classification['review_reason'];

        if ($entityClass === 'COUNTRY') {
            $classifiedCountry++;
        } elseif ($entityClass === 'TERRITORY') {
            $classifiedTerritory++;
        } elseif ($entityClass === 'HISTORICAL') {
            $classifiedHistorical++;
        }
    }

    $results[] = [
        'country_source' =>
            $source,

        'occurrence_count' =>
            (int) (
                $row[
                    $columns['occurrence_count']
                ]
            ),

        'candidate_iso2' =>
            trim(
                (string) (
                    $row[
                        $columns['candidate_iso2']
                    ]
                )
            ),

        'candidate_iso3' =>
            trim(
                (string) (
                    $row[
                        $columns['candidate_iso3']
                    ]
                )
            ),

        'candidate_name_en' =>
            trim(
                (string) (
                    $row[
                        $columns['candidate_name_en']
                    ]
                )
            ),

        'candidate_name_id' =>
            trim(
                (string) (
                    $row[
                        $columns['candidate_name_id']
                    ]
                )
            ),

        'candidate_confidence' =>
            trim(
                (string) (
                    $row[
                        $columns['candidate_confidence']
                    ]
                )
            ),

        'master_status' =>
            trim(
                (string) (
                    $row[
                        $columns['master_status']
                    ]
                )
            ),

        'entity_class' =>
            $entityClass,

        'review_status' =>
            $reviewStatus,

        'review_reason' =>
            $reviewReason,

        'recommendation' =>
            $classification === null
                ? 'MANUAL_REVIEW'
                : (
                    $entityClass === 'HISTORICAL'
                        ? 'HOLD_HISTORICAL'
                        : (
                            $entityClass === 'TERRITORY'
                                ? 'REVIEW_TERRITORY'
                                : 'REVIEW_COUNTRY'
                        )
                ),
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

        if (
            $a['occurrence_count']
            ===
            $b['occurrence_count']
        ) {
            return strcmp(
                $a['country_source'],
                $b['country_source']
            );
        }

        return
            $b['occurrence_count']
            <=>
            $a['occurrence_count'];
    }
);

/*
|--------------------------------------------------------------------------
| Write CSV
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
        'occurrence_count',
        'candidate_iso2',
        'candidate_iso3',
        'candidate_name_en',
        'candidate_name_id',
        'candidate_confidence',
        'master_status',
        'entity_class',
        'review_status',
        'review_reason',
        'recommendation',
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
echo "DIGESTEX COUNTRY MASTER EXPANSION REVIEW V2\n";
echo "========================================\n\n";

echo "TOTAL SOURCE NAMES       : "
    . $total
    . PHP_EOL;

echo "COUNTRY CLASSIFIED       : "
    . $classifiedCountry
    . PHP_EOL;

echo "TERRITORY CLASSIFIED     : "
    . $classifiedTerritory
    . PHP_EOL;

echo "HISTORICAL CLASSIFIED    : "
    . $classifiedHistorical
    . PHP_EOL;

echo "UNCLASSIFIED / REVIEW    : "
    . $unclassified
    . PHP_EOL;

echo "\nOUTPUT:\n";
echo $outputFile . PHP_EOL;

echo "\n========================================\n";
echo "DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";