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
| DIGESTEX TRADE COUNTRY MASTER EXPANSION BUILDER
|--------------------------------------------------------------------------
|
| INPUT:
|   trade_country_master_gap_review_2019.csv
|
| OUTPUT:
|   trade_country_master_expansion_review_2019.csv
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
    . 'trade_country_master_gap_review_2019.csv';

$outputFile =
    $base
    . DIRECTORY_SEPARATOR
    . 'PROCESSED'
    . DIRECTORY_SEPARATOR
    . 'trade_country_master_expansion_review_2019.csv';

if (!is_file($inputFile)) {
    throw new RuntimeException(
        "Input file tidak ditemukan:\n{$inputFile}"
    );
}

/*
|--------------------------------------------------------------------------
| Suggested master metadata
|--------------------------------------------------------------------------
|
| Hanya untuk candidate generation.
| Tidak melakukan INSERT.
|--------------------------------------------------------------------------
*/

$knownCandidates = [

    'FEDERASI RUSIA' => [
        'iso2' => 'RU',
        'iso3' => 'RUS',
        'name_en' => 'Russia',
        'name_id' => 'Rusia',
    ],

    'SWEDIA' => [
        'iso2' => 'SE',
        'iso3' => 'SWE',
        'name_en' => 'Sweden',
        'name_id' => 'Swedia',
    ],

    'DENMARK' => [
        'iso2' => 'DK',
        'iso3' => 'DNK',
        'name_en' => 'Denmark',
        'name_id' => 'Denmark',
    ],

    'NORWEGIA' => [
        'iso2' => 'NO',
        'iso3' => 'NOR',
        'name_en' => 'Norway',
        'name_id' => 'Norwegia',
    ],

    'YUNANI' => [
        'iso2' => 'GR',
        'iso3' => 'GRC',
        'name_en' => 'Greece',
        'name_id' => 'Yunani',
    ],

    'KROASIA' => [
        'iso2' => 'HR',
        'iso3' => 'HRV',
        'name_en' => 'Croatia',
        'name_id' => 'Kroasia',
    ],

    'SERBIA' => [
        'iso2' => 'RS',
        'iso3' => 'SRB',
        'name_en' => 'Serbia',
        'name_id' => 'Serbia',
    ],

    'POLYNESIA PERANCIS' => [
        'iso2' => 'PF',
        'iso3' => 'PYF',
        'name_en' => 'French Polynesia',
        'name_id' => 'Polinesia Prancis',
    ],

    'REUNION' => [
        'iso2' => 'RE',
        'iso3' => 'REU',
        'name_en' => 'Réunion',
        'name_id' => 'Réunion',
    ],

    'HONGARIA' => [
        'iso2' => 'HU',
        'iso3' => 'HUN',
        'name_en' => 'Hungary',
        'name_id' => 'Hongaria',
    ],

    'UKRAINA' => [
        'iso2' => 'UA',
        'iso3' => 'UKR',
        'name_en' => 'Ukraine',
        'name_id' => 'Ukraina',
    ],

    'GUADELOUPE' => [
        'iso2' => 'GP',
        'iso3' => 'GLP',
        'name_en' => 'Guadeloupe',
        'name_id' => 'Guadeloupe',
    ],

    'SLOVENIA' => [
        'iso2' => 'SI',
        'iso3' => 'SVN',
        'name_en' => 'Slovenia',
        'name_id' => 'Slovenia',
    ],

    'IRLANDIA' => [
        'iso2' => 'IE',
        'iso3' => 'IRL',
        'name_en' => 'Ireland',
        'name_id' => 'Irlandia',
    ],

    'SOMALIA' => [
        'iso2' => 'SO',
        'iso3' => 'SOM',
        'name_en' => 'Somalia',
        'name_id' => 'Somalia',
    ],

    'FINLANDIA' => [
        'iso2' => 'FI',
        'iso3' => 'FIN',
        'name_en' => 'Finland',
        'name_id' => 'Finlandia',
    ],

    'NETHERLANDS ANTILLES' => [
        'iso2' => 'AN',
        'iso3' => '',
        'name_en' => 'Netherlands Antilles',
        'name_id' => 'Antillen Belanda',
    ],

    'PUERTO RICO' => [
        'iso2' => 'PR',
        'iso3' => 'PRI',
        'name_en' => 'Puerto Rico',
        'name_id' => 'Puerto Rico',
    ],

    'BULGARIA' => [
        'iso2' => 'BG',
        'iso3' => 'BGR',
        'name_en' => 'Bulgaria',
        'name_id' => 'Bulgaria',
    ],

    'SINEGAL' => [
        'iso2' => 'SN',
        'iso3' => 'SEN',
        'name_en' => 'Senegal',
        'name_id' => 'Senegal',
    ],

    'GUAM' => [
        'iso2' => 'GU',
        'iso3' => 'GUM',
        'name_en' => 'Guam',
        'name_id' => 'Guam',
    ],

    'SEYCHELLES' => [
        'iso2' => 'SC',
        'iso3' => 'SYC',
        'name_en' => 'Seychelles',
        'name_id' => 'Seychelles',
    ],

    'TOGO' => [
        'iso2' => 'TG',
        'iso3' => 'TGO',
        'name_en' => 'Togo',
        'name_id' => 'Togo',
    ],

    'SAINT KITTS DAN NEVIS' => [
        'iso2' => 'KN',
        'iso3' => 'KNA',
        'name_en' => 'Saint Kitts and Nevis',
        'name_id' => 'Saint Kitts dan Nevis',
    ],

    'KEPULAUAN COOK' => [
        'iso2' => 'CK',
        'iso3' => 'COK',
        'name_en' => 'Cook Islands',
        'name_id' => 'Kepulauan Cook',
    ],

    'MARTINIK' => [
        'iso2' => 'MQ',
        'iso3' => 'MTQ',
        'name_en' => 'Martinique',
        'name_id' => 'Martinique',
    ],

    'LATVIA' => [
        'iso2' => 'LV',
        'iso3' => 'LVA',
        'name_en' => 'Latvia',
        'name_id' => 'Latvia',
    ],

    'YAMAN' => [
        'iso2' => 'YE',
        'iso3' => 'YEM',
        'name_en' => 'Yemen',
        'name_id' => 'Yaman',
    ],

    'KAMERUN' => [
        'iso2' => 'CM',
        'iso3' => 'CMR',
        'name_en' => 'Cameroon',
        'name_id' => 'Kamerun',
    ],

    'SUDAN' => [
        'iso2' => 'SD',
        'iso3' => 'SDN',
        'name_en' => 'Sudan',
        'name_id' => 'Sudan',
    ],

    'ESTONIA' => [
        'iso2' => 'EE',
        'iso3' => 'EST',
        'name_en' => 'Estonia',
        'name_id' => 'Estonia',
    ],

    'MADAGASKAR' => [
        'iso2' => 'MG',
        'iso3' => 'MDG',
        'name_en' => 'Madagascar',
        'name_id' => 'Madagaskar',
    ],
];

/*
|--------------------------------------------------------------------------
| Load current master
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

    if ($code !== '') {
        $masterByCode[$code] = $country;
    }

    if ($iso3 !== '') {
        $masterByIso3[$iso3] = $country;
    }
}

/*
|--------------------------------------------------------------------------
| Read master gap review
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
        'Header master gap review tidak ditemukan.'
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
    'suggested_country_code',
    'suggested_country_name_en',
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

$results = [];

$totalRows = 0;
$candidates = 0;
$existing = 0;
$manualReview = 0;

while (($row = fgetcsv($handle)) !== false) {

    $totalRows++;

    $source = trim(
        (string) $row[
            $columns['country_source']
        ]
    );

    $occurrence = (int) (
        $row[
            $columns['occurrence_count']
        ]
    );

    $suggestedCode = strtoupper(
        trim(
            (string) $row[
                $columns[
                    'suggested_country_code'
                ]
            ]
        )
    );

    $suggestedNameEn = trim(
        (string) $row[
            $columns[
                'suggested_country_name_en'
            ]
        ]
    );

    $confidence = trim(
        (string) $row[
            $columns['candidate_confidence']
        ]
    );

    $masterStatus = trim(
        (string) $row[
            $columns['master_status']
        ]
    );

    $recommendation = trim(
        (string) $row[
            $columns['recommendation']
        ]
    );

    /*
    |--------------------------------------------------------------------------
    | Existing master
    |--------------------------------------------------------------------------
    */

    if ($masterStatus === 'EXISTING_MASTER') {

        $existing++;

        $matched = null;

        if (
            $suggestedCode !== ''
            &&
            isset(
                $masterByCode[
                    $suggestedCode
                ]
            )
        ) {
            $matched =
                $masterByCode[
                    $suggestedCode
                ];
        }

        $results[] = [
            'country_source' =>
                $source,

            'occurrence_count' =>
                $occurrence,

            'candidate_iso2' =>
                $matched?->country_code
                ?? $suggestedCode,

            'candidate_iso3' =>
                $matched?->iso3,

            'candidate_name_en' =>
                $matched?->country_name_en
                ?? $suggestedNameEn,

            'candidate_name_id' =>
                $matched?->country_name_id,

            'candidate_confidence' =>
                $confidence,

            'master_status' =>
                'EXISTING_MASTER',

            'recommendation' =>
                'ALIAS_ONLY',
        ];

        continue;
    }

    /*
    |--------------------------------------------------------------------------
    | Master expansion candidates
    |--------------------------------------------------------------------------
    */

    if (
        isset(
            $knownCandidates[
                $source
            ]
        )
    ) {

        $candidate =
            $knownCandidates[
                $source
            ];

        $iso2 =
            strtoupper(
                trim(
                    (string) $candidate['iso2']
                )
            );

        $iso3 =
            strtoupper(
                trim(
                    (string) $candidate['iso3']
                )
            );

        $candidateNameEn =
            $candidate['name_en'];

        $candidateNameId =
            $candidate['name_id'];

        /*
        |--------------------------------------------------------------------------
        | Protect against current master collision
        |--------------------------------------------------------------------------
        */

        if (
            $iso2 !== ''
            &&
            isset(
                $masterByCode[$iso2]
            )
        ) {
            $masterStatus =
                'CURRENT_MASTER_CODE_EXISTS';

            $recommendation =
                'REVIEW_EXISTING_CODE';

        } elseif (
            $iso3 !== ''
            &&
            isset(
                $masterByIso3[$iso3]
            )
        ) {
            $masterStatus =
                'CURRENT_MASTER_ISO3_EXISTS';

            $recommendation =
                'REVIEW_EXISTING_ISO3';

        } else {

            $masterStatus =
                'EXPANSION_CANDIDATE';

            $recommendation =
                'MASTER_ADDITION_REVIEW';

            $candidates++;
        }

        $results[] = [
            'country_source' =>
                $source,

            'occurrence_count' =>
                $occurrence,

            'candidate_iso2' =>
                $iso2,

            'candidate_iso3' =>
                $iso3,

            'candidate_name_en' =>
                $candidateNameEn,

            'candidate_name_id' =>
                $candidateNameId,

            'candidate_confidence' =>
                $confidence,

            'master_status' =>
                $masterStatus,

            'recommendation' =>
                $recommendation,
        ];

        continue;
    }

    /*
    |--------------------------------------------------------------------------
    | No curated candidate
    |--------------------------------------------------------------------------
    */

    $manualReview++;

    $results[] = [
        'country_source' =>
            $source,

        'occurrence_count' =>
            $occurrence,

        'candidate_iso2' =>
            $suggestedCode,

        'candidate_iso3' =>
            '',

        'candidate_name_en' =>
            $suggestedNameEn,

        'candidate_name_id' =>
            '',

        'candidate_confidence' =>
            $confidence,

        'master_status' =>
            'MANUAL_REVIEW',

        'recommendation' =>
            'MANUAL_REVIEW',
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
        'occurrence_count',
        'candidate_iso2',
        'candidate_iso3',
        'candidate_name_en',
        'candidate_name_id',
        'candidate_confidence',
        'master_status',
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
echo "DIGESTEX COUNTRY MASTER EXPANSION REVIEW\n";
echo "========================================\n\n";

echo "SOURCE COUNTRY NAMES     : "
    . $totalRows
    . PHP_EOL;

echo "EXISTING MASTER          : "
    . $existing
    . PHP_EOL;

echo "EXPANSION CANDIDATES     : "
    . $candidates
    . PHP_EOL;

echo "MANUAL REVIEW            : "
    . $manualReview
    . PHP_EOL;

echo "\nCURRENT MASTER COUNTRIES : "
    . $masterRows->count()
    . PHP_EOL;

echo "\nOUTPUT:\n";
echo $outputFile . PHP_EOL;

echo "\n========================================\n";
echo "DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";