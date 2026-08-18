<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "========================================\n";
echo "DIGESTEX KEMENDAG COUNTRY ALIAS / MASTER GAP AUDIT\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  STATUS      : READ ONLY\n\n";

/*
|--------------------------------------------------------------------------
| Load all unresolved source names
|--------------------------------------------------------------------------
*/

$rows =
    DB::table('trade_statistics')
        ->whereBetween(
            'year',
            [2019, 2026]
        )
        ->whereIn(
            'trade_flow',
            [
                'export',
                'import',
            ]
        )
        ->whereNull('country_id')
        ->whereNotNull('country_name')
        ->where(
            'country_name',
            '<>',
            ''
        )
        ->selectRaw(
            '
            country_name,
            COUNT(*) AS records,
            SUM(trade_value) AS trade_value,
            SUM(trade_volume) AS trade_volume
            '
        )
        ->groupBy('country_name')
        ->orderByDesc('records')
        ->get();

/*
|--------------------------------------------------------------------------
| Explicit special / territory names
|--------------------------------------------------------------------------
|
| These are not treated as ordinary country aliases.
|--------------------------------------------------------------------------
*/

$specialNames = [
    'INDONESIA (BATAM)',
    'PALESTINA',
    'FD STS MICRONESIA',
];

$territoryNames = [
    'NETHERLANDS ANTILLES',
    'VIRGIN ISLANDS (BRITISH)',
    'U.S. VIRGIN ISLANDS',
    'KEPULAUAN TURKS DAN CAICOS',
    'KEPULAUAN CAYMAN',
    'KEP. VALLIS DAN FUTUNA',
    'ANGUILA',
    'JERSEY',
    'MAYOTTE',
    'ARUBA',
    'SAINT BARTHELEMY',
    'SAMOA AMERIKA',
    'GUIANA PERANCIS',
    'TOKELAU',
    'GIBRALTAR',
    'KEPULAUAN MARIANA UTARA',
    'SAINT MARTIN (FRENCH PART)',
    'KEPULAUAN CHRISTMAS',
    'U.S MINOR OUTLYING ISLAND',
    'GUERNSEY',
    'SAINT HELENA',
    'KEPULAUAN COCOS (KEELING)',
    'KEPULAUAN FALKLAND (MALVINAS)',
    'SINT MAARTEN (DUTCH PART)',
    'KEPULAUAN NORFOLK',
    'BRITISH INDIAN OCEAN TERRITORY',
    'ANTARTICA',
    'PULAU HEARD DAN KEPULAUAN MCDONALD',
    'PITCAIRN',
    'KEPULAUAN ALAND',
];

/*
|--------------------------------------------------------------------------
| Candidate ISO dictionary
|--------------------------------------------------------------------------
|
| Only deterministic country mappings are included.
| This is an audit dictionary, not a migration.
|--------------------------------------------------------------------------
*/

$isoCandidates = [

    'SRI LANGKA' => [
        'name_en' => 'Sri Lanka',
        'name_id' => 'Sri Lanka',
        'country_code' => 'LK',
        'iso3' => 'LKA',
        'confidence' => 'HIGH',
    ],

    'REPUBLIK CZECH' => [
        'name_en' => 'Czech Republic',
        'name_id' => 'Republik Ceko',
        'country_code' => 'CZ',
        'iso3' => 'CZE',
        'confidence' => 'HIGH',
    ],

    'LUKSEMBURG' => [
        'name_en' => 'Luxembourg',
        'name_id' => 'Luksemburg',
        'country_code' => 'LU',
        'iso3' => 'LUX',
        'confidence' => 'HIGH',
    ],

    'SIPRUS' => [
        'name_en' => 'Cyprus',
        'name_id' => 'Siprus',
        'country_code' => 'CY',
        'iso3' => 'CYP',
        'confidence' => 'HIGH',
    ],

    'LIBIA' => [
        'name_en' => 'Libya',
        'name_id' => 'Libya',
        'country_code' => 'LY',
        'iso3' => 'LBY',
        'confidence' => 'HIGH',
    ],

    'REPUBLIK MACEDONIA' => [
        'name_en' => 'North Macedonia',
        'name_id' => 'Makedonia Utara',
        'country_code' => 'MK',
        'iso3' => 'MKD',
        'confidence' => 'HIGH',
    ],

    'REPUBLIK DEMOKRATIK KONGO' => [
        'name_en' => 'Democratic Republic of the Congo',
        'name_id' => 'Republik Demokratik Kongo',
        'country_code' => 'CD',
        'iso3' => 'COD',
        'confidence' => 'HIGH',
    ],

    'ISLANDIA' => [
        'name_en' => 'Iceland',
        'name_id' => 'Islandia',
        'country_code' => 'IS',
        'iso3' => 'ISL',
        'confidence' => 'HIGH',
    ],

    'REP.AFRIKA TENGAH' => [
        'name_en' => 'Central African Republic',
        'name_id' => 'Republik Afrika Tengah',
        'country_code' => 'CF',
        'iso3' => 'CAF',
        'confidence' => 'HIGH',
    ],

    'ANDORRA' => [
        'name_en' => 'Andorra',
        'name_id' => 'Andorra',
        'country_code' => 'AD',
        'iso3' => 'AND',
        'confidence' => 'HIGH',
    ],

    'GABON' => [
        'name_en' => 'Gabon',
        'name_id' => 'Gabon',
        'country_code' => 'GA',
        'iso3' => 'GAB',
        'confidence' => 'HIGH',
    ],

    'SAINT LUCIA' => [
        'name_en' => 'Saint Lucia',
        'name_id' => 'Saint Lucia',
        'country_code' => 'LC',
        'iso3' => 'LCA',
        'confidence' => 'HIGH',
    ],

    'MAURITANIA' => [
        'name_en' => 'Mauritania',
        'name_id' => 'Mauritania',
        'country_code' => 'MR',
        'iso3' => 'MRT',
        'confidence' => 'HIGH',
    ],

    'ZAMBIA' => [
        'name_en' => 'Zambia',
        'name_id' => 'Zambia',
        'country_code' => 'ZM',
        'iso3' => 'ZMB',
        'confidence' => 'HIGH',
    ],

    'CHAD' => [
        'name_en' => 'Chad',
        'name_id' => 'Chad',
        'country_code' => 'TD',
        'iso3' => 'TCD',
        'confidence' => 'HIGH',
    ],

    'BOTSWANA' => [
        'name_en' => 'Botswana',
        'name_id' => 'Botswana',
        'country_code' => 'BW',
        'iso3' => 'BWA',
        'confidence' => 'HIGH',
    ],

    'MALAWI' => [
        'name_en' => 'Malawi',
        'name_id' => 'Malawi',
        'country_code' => 'MW',
        'iso3' => 'MWI',
        'confidence' => 'HIGH',
    ],

    'RWANDA' => [
        'name_en' => 'Rwanda',
        'name_id' => 'Rwanda',
        'country_code' => 'RW',
        'iso3' => 'RWA',
        'confidence' => 'HIGH',
    ],

    'NIGER' => [
        'name_en' => 'Niger',
        'name_id' => 'Niger',
        'country_code' => 'NE',
        'iso3' => 'NER',
        'confidence' => 'HIGH',
    ],

    'DOMINIKA' => [
        'name_en' => 'Dominica',
        'name_id' => 'Dominika',
        'country_code' => 'DM',
        'iso3' => 'DMA',
        'confidence' => 'HIGH',
    ],

    'TAJIKISTAN' => [
        'name_en' => 'Tajikistan',
        'name_id' => 'Tajikistan',
        'country_code' => 'TJ',
        'iso3' => 'TJK',
        'confidence' => 'HIGH',
    ],

    'TURKMENISTAN' => [
        'name_en' => 'Turkmenistan',
        'name_id' => 'Turkmenistan',
        'country_code' => 'TM',
        'iso3' => 'TKM',
        'confidence' => 'HIGH',
    ],

    'ERITREA' => [
        'name_en' => 'Eritrea',
        'name_id' => 'Eritrea',
        'country_code' => 'ER',
        'iso3' => 'ERI',
        'confidence' => 'HIGH',
    ],

    'SAN MARINO' => [
        'name_en' => 'San Marino',
        'name_id' => 'San Marino',
        'country_code' => 'SM',
        'iso3' => 'SMR',
        'confidence' => 'HIGH',
    ],

    'SAO TOME DAN PRINCIPE' => [
        'name_en' => 'Sao Tome and Principe',
        'name_id' => 'Sao Tome and Principe',
        'country_code' => 'ST',
        'iso3' => 'STP',
        'confidence' => 'HIGH',
    ],

    'SUDAN SELATAN' => [
        'name_en' => 'South Sudan',
        'name_id' => 'Sudan Selatan',
        'country_code' => 'SS',
        'iso3' => 'SSD',
        'confidence' => 'HIGH',
    ],
];

/*
|--------------------------------------------------------------------------
| Master lookup helpers
|--------------------------------------------------------------------------
*/

function findMasterByIso(
    ?string $iso2,
    ?string $iso3
): ?object {

    if ($iso3 !== null) {

        $master =
            DB::table('mst_countries')
                ->where(
                    'iso3',
                    $iso3
                )
                ->first();

        if ($master !== null) {
            return $master;
        }
    }

    if ($iso2 !== null) {

        $master =
            DB::table('mst_countries')
                ->where(
                    'country_code',
                    $iso2
                )
                ->first();

        if ($master !== null) {
            return $master;
        }
    }

    return null;
}

function masterExistsByName(
    string $sourceName
): ?object {

    $normalized =
        preg_replace(
            '/\s+/',
            ' ',
            mb_strtoupper(
                trim($sourceName)
            )
        );

    if ($normalized === '') {
        return null;
    }

    return DB::table('mst_countries')
        ->where(function ($q) use ($normalized) {

            $q->whereRaw(
                'UPPER(country_name_en) = ?',
                [$normalized]
            );

            $q->orWhereRaw(
                'UPPER(country_name_id) = ?',
                [$normalized]
            );

        })
        ->first();
}

/*
|--------------------------------------------------------------------------
| Classification counters
|--------------------------------------------------------------------------
*/

$summary = [
    'MASTER_EXISTS_ALIAS_ONLY' => [
        'records' => 0,
        'names' => 0,
    ],

    'MASTER_MISSING_NEW_MASTER' => [
        'records' => 0,
        'names' => 0,
    ],

    'TERRITORY_DEPENDENCY' => [
        'records' => 0,
        'names' => 0,
    ],

    'SPECIAL' => [
        'records' => 0,
        'names' => 0,
    ],

    'REVIEW' => [
        'records' => 0,
        'names' => 0,
    ],
];

$results = [];

/*
|--------------------------------------------------------------------------
| Evaluate every unresolved source name
|--------------------------------------------------------------------------
*/

foreach ($rows as $row) {

    $sourceName =
        trim(
            (string) $row->country_name
        );

    $records =
        (int) $row->records;

    $candidate =
        $isoCandidates[$sourceName]
        ?? null;

    /*
     * Special nomenclature
     */
    if (
        in_array(
            $sourceName,
            $specialNames,
            true
        )
    ) {

        $classification =
            'SPECIAL';

        $master =
            masterExistsByName(
                $sourceName
            );

        $results[] = [
            'source_name' =>
                $sourceName,

            'records' =>
                $records,

            'classification' =>
                $classification,

            'master_status' =>
                $master !== null
                    ? 'FOUND'
                    : 'MISSING',

            'master_id' =>
                $master?->id,

            'proposed_name' =>
                $candidate['name_en']
                ?? null,

            'country_code' =>
                $candidate['country_code']
                ?? null,

            'iso3' =>
                $candidate['iso3']
                ?? null,

            'confidence' =>
                'REVIEW',
        ];

        $summary[$classification]['records'] +=
            $records;

        $summary[$classification]['names']++;

        continue;
    }

    /*
     * Territory / dependency
     */
    if (
        in_array(
            $sourceName,
            $territoryNames,
            true
        )
    ) {

        $classification =
            'TERRITORY_DEPENDENCY';

        $results[] = [
            'source_name' =>
                $sourceName,

            'records' =>
                $records,

            'classification' =>
                $classification,

            'master_status' =>
                'POLICY REVIEW',

            'master_id' =>
                null,

            'proposed_name' =>
                null,

            'country_code' =>
                null,

            'iso3' =>
                null,

            'confidence' =>
                'REVIEW',
        ];

        $summary[$classification]['records'] +=
            $records;

        $summary[$classification]['names']++;

        continue;
    }

    /*
     * Deterministic alias/new-master candidate
     */
    if ($candidate !== null) {

        $master =
            findMasterByIso(
                $candidate['country_code'],
                $candidate['iso3']
            );

        if ($master !== null) {

            $classification =
                'MASTER_EXISTS_ALIAS_ONLY';

            $masterStatus =
                'FOUND';

        } else {

            $classification =
                'MASTER_MISSING_NEW_MASTER';

            $masterStatus =
                'MISSING';
        }

        $results[] = [
            'source_name' =>
                $sourceName,

            'records' =>
                $records,

            'classification' =>
                $classification,

            'master_status' =>
                $masterStatus,

            'master_id' =>
                $master?->id,

            'proposed_name' =>
                $candidate['name_en'],

            'country_code' =>
                $candidate['country_code'],

            'iso3' =>
                $candidate['iso3'],

            'confidence' =>
                $candidate['confidence'],
        ];

        $summary[$classification]['records'] +=
            $records;

        $summary[$classification]['names']++;

        continue;
    }

    /*
     * Unknown source name
     */
    $results[] = [
        'source_name' =>
            $sourceName,

        'records' =>
            $records,

        'classification' =>
            'REVIEW',

        'master_status' =>
            'UNKNOWN',

        'master_id' =>
            null,

        'proposed_name' =>
            null,

        'country_code' =>
            null,

        'iso3' =>
            null,

        'confidence' =>
            'REVIEW',
    ];

    $summary['REVIEW']['records'] +=
        $records;

    $summary['REVIEW']['names']++;
}

/*
|--------------------------------------------------------------------------
| Summary
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "CLASSIFICATION SUMMARY\n";
echo "========================================\n";

foreach (
    $summary as $classification => $data
) {

    echo sprintf(
        "  %-28s | %3d names | %7d records\n",
        $classification,
        $data['names'],
        $data['records']
    );
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Master exists / alias only
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "MASTER EXISTS / ALIAS ONLY\n";
echo "========================================\n";

foreach ($results as $item) {

    if (
        $item['classification']
        !==
        'MASTER_EXISTS_ALIAS_ONLY'
    ) {
        continue;
    }

    echo sprintf(
        "  %-38s | %7d records | ID=%d | %s | %s | %s\n",
        $item['source_name'],
        $item['records'],
        $item['master_id'],
        $item['proposed_name'] ?? '-',
        $item['country_code'] ?? '-',
        $item['iso3'] ?? '-'
    );
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Master missing / new master
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "MASTER MISSING / NEW MASTER\n";
echo "========================================\n";

foreach ($results as $item) {

    if (
        $item['classification']
        !==
        'MASTER_MISSING_NEW_MASTER'
    ) {
        continue;
    }

    echo sprintf(
        "  %-38s | %7d records | %s | %s | %s\n",
        $item['source_name'],
        $item['records'],
        $item['proposed_name'] ?? '-',
        $item['country_code'] ?? '-',
        $item['iso3'] ?? '-'
    );
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Territory / Dependency
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "TERRITORY / DEPENDENCY\n";
echo "========================================\n";

foreach ($results as $item) {

    if (
        $item['classification']
        !==
        'TERRITORY_DEPENDENCY'
    ) {
        continue;
    }

    echo sprintf(
        "  %-45s | %7d records | POLICY REVIEW\n",
        $item['source_name'],
        $item['records']
    );
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Special
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "SPECIAL NOMENCLATURE\n";
echo "========================================\n";

foreach ($results as $item) {

    if (
        $item['classification']
        !==
        'SPECIAL'
    ) {
        continue;
    }

    echo sprintf(
        "  %-45s | %7d records | REVIEW\n",
        $item['source_name'],
        $item['records']
    );
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Review
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "REVIEW / UNKNOWN\n";
echo "========================================\n";

foreach ($results as $item) {

    if (
        $item['classification']
        !==
        'REVIEW'
    ) {
        continue;
    }

    echo sprintf(
        "  %-45s | %7d records\n",
        $item['source_name'],
        $item['records']
    );
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

echo "========================================\n";
echo "COUNTRY ALIAS / MASTER GAP AUDIT : COMPLETE\n";
echo "========================================\n";