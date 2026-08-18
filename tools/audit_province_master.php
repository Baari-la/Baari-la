<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| DIGESTEX Province Master Audit
|--------------------------------------------------------------------------
|
| Input:
|   Desktop\DIGESTEX_DATA\PROCESSED\province_universe_2019_2026.csv
|
| Output:
|   Desktop\DIGESTEX_DATA\PROCESSED\province_master_review.csv
|
| Database:
|   READ ONLY - no database modification
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
    . 'province_universe_2019_2026.csv';

$outputFile =
    $base
    . DIRECTORY_SEPARATOR
    . 'PROCESSED'
    . DIRECTORY_SEPARATOR
    . 'province_master_review.csv';

if (!is_file($inputFile)) {
    throw new RuntimeException(
        "Input file tidak ditemukan:\n{$inputFile}"
    );
}

/*
|--------------------------------------------------------------------------
| Canonical DIGESTEX Province Mapping
|--------------------------------------------------------------------------
|
| province_code di sini adalah stable DIGESTEX code.
| Kita sengaja menggunakan format ID-XX agar tidak bergantung
| pada urutan row file Kemendag.
|
*/

$mapping = [

    'BALI' => [
        'province_code' => 'ID-BA',
        'name' => 'Bali',
        'name_en' => 'Bali',
        'source_alias' => null,
        'island_group' => 'Bali & Nusa Tenggara',
        'region_group' => 'Central Indonesia',
        'sort_order' => 1,
    ],

    'BANGKA BELITUNG' => [
        'province_code' => 'ID-BB',
        'name' => 'Kepulauan Bangka Belitung',
        'name_en' => 'Bangka Belitung Islands',
        'source_alias' => null,
        'island_group' => 'Sumatra',
        'region_group' => 'Western Indonesia',
        'sort_order' => 2,
    ],

    'BANTEN' => [
        'province_code' => 'ID-BT',
        'name' => 'Banten',
        'name_en' => 'Banten',
        'source_alias' => null,
        'island_group' => 'Java',
        'region_group' => 'Western Indonesia',
        'sort_order' => 3,
    ],

    'BENGKULU' => [
        'province_code' => 'ID-BE',
        'name' => 'Bengkulu',
        'name_en' => 'Bengkulu',
        'source_alias' => null,
        'island_group' => 'Sumatra',
        'region_group' => 'Western Indonesia',
        'sort_order' => 4,
    ],

    'D.I. YOGYAKARTA' => [
        'province_code' => 'ID-YO',
        'name' => 'Daerah Istimewa Yogyakarta',
        'name_en' => 'Special Region of Yogyakarta',
        'source_alias' => 'D.I. Yogyakarta',
        'island_group' => 'Java',
        'region_group' => 'Central Indonesia',
        'sort_order' => 5,
    ],

    'DKI JAKARTA' => [
        'province_code' => 'ID-JK',
        'name' => 'DKI Jakarta',
        'name_en' => 'Jakarta',
        'source_alias' => null,
        'island_group' => 'Java',
        'region_group' => 'Western Indonesia',
        'sort_order' => 6,
    ],

    'GORONTALO' => [
        'province_code' => 'ID-GO',
        'name' => 'Gorontalo',
        'name_en' => 'Gorontalo',
        'source_alias' => null,
        'island_group' => 'Sulawesi',
        'region_group' => 'Central Indonesia',
        'sort_order' => 7,
    ],

    'JAMBI' => [
        'province_code' => 'ID-JA',
        'name' => 'Jambi',
        'name_en' => 'Jambi',
        'source_alias' => null,
        'island_group' => 'Sumatra',
        'region_group' => 'Western Indonesia',
        'sort_order' => 8,
    ],

    'JAWA BARAT' => [
        'province_code' => 'ID-JB',
        'name' => 'Jawa Barat',
        'name_en' => 'West Java',
        'source_alias' => null,
        'island_group' => 'Java',
        'region_group' => 'Western Indonesia',
        'sort_order' => 9,
    ],

    'JAWA TENGAH' => [
        'province_code' => 'ID-JT',
        'name' => 'Jawa Tengah',
        'name_en' => 'Central Java',
        'source_alias' => null,
        'island_group' => 'Java',
        'region_group' => 'Central Indonesia',
        'sort_order' => 10,
    ],

    'JAWA TIMUR' => [
        'province_code' => 'ID-JI',
        'name' => 'Jawa Timur',
        'name_en' => 'East Java',
        'source_alias' => null,
        'island_group' => 'Java',
        'region_group' => 'Central Indonesia',
        'sort_order' => 11,
    ],

    'KALIMANTAN BARAT' => [
        'province_code' => 'ID-KB',
        'name' => 'Kalimantan Barat',
        'name_en' => 'West Kalimantan',
        'source_alias' => null,
        'island_group' => 'Kalimantan',
        'region_group' => 'Central Indonesia',
        'sort_order' => 12,
    ],

    'KALIMANTAN SELATAN' => [
        'province_code' => 'ID-KS',
        'name' => 'Kalimantan Selatan',
        'name_en' => 'South Kalimantan',
        'source_alias' => null,
        'island_group' => 'Kalimantan',
        'region_group' => 'Central Indonesia',
        'sort_order' => 13,
    ],

    'KALIMANTAN TENGAH' => [
        'province_code' => 'ID-KT',
        'name' => 'Kalimantan Tengah',
        'name_en' => 'Central Kalimantan',
        'source_alias' => null,
        'island_group' => 'Kalimantan',
        'region_group' => 'Central Indonesia',
        'sort_order' => 14,
    ],

    'KALIMANTAN TIMUR' => [
        'province_code' => 'ID-KI',
        'name' => 'Kalimantan Timur',
        'name_en' => 'East Kalimantan',
        'source_alias' => null,
        'island_group' => 'Kalimantan',
        'region_group' => 'Central Indonesia',
        'sort_order' => 15,
    ],

    'KALIMANTAN UTARA' => [
        'province_code' => 'ID-KU',
        'name' => 'Kalimantan Utara',
        'name_en' => 'North Kalimantan',
        'source_alias' => null,
        'island_group' => 'Kalimantan',
        'region_group' => 'Central Indonesia',
        'sort_order' => 16,
    ],

    'KEPULAUAN RIAU' => [
        'province_code' => 'ID-KR',
        'name' => 'Kepulauan Riau',
        'name_en' => 'Riau Islands',
        'source_alias' => null,
        'island_group' => 'Sumatra',
        'region_group' => 'Western Indonesia',
        'sort_order' => 17,
    ],

    'LAMPUNG' => [
        'province_code' => 'ID-LA',
        'name' => 'Lampung',
        'name_en' => 'Lampung',
        'source_alias' => null,
        'island_group' => 'Sumatra',
        'region_group' => 'Western Indonesia',
        'sort_order' => 18,
    ],

    'MALUKU' => [
        'province_code' => 'ID-MA',
        'name' => 'Maluku',
        'name_en' => 'Maluku',
        'source_alias' => null,
        'island_group' => 'Maluku',
        'region_group' => 'Eastern Indonesia',
        'sort_order' => 19,
    ],

    'MALUKU UTARA' => [
        'province_code' => 'ID-MU',
        'name' => 'Maluku Utara',
        'name_en' => 'North Maluku',
        'source_alias' => null,
        'island_group' => 'Maluku',
        'region_group' => 'Eastern Indonesia',
        'sort_order' => 20,
    ],

    'NANGROE ACEH DARUSALAM' => [
        'province_code' => 'ID-AC',
        'name' => 'Aceh',
        'name_en' => 'Aceh',
        'source_alias' => 'Nangroe Aceh Darusalam',
        'island_group' => 'Sumatra',
        'region_group' => 'Western Indonesia',
        'sort_order' => 21,
    ],

    'NUSA TENGGARA BARAT' => [
        'province_code' => 'ID-NB',
        'name' => 'Nusa Tenggara Barat',
        'name_en' => 'West Nusa Tenggara',
        'source_alias' => null,
        'island_group' => 'Bali & Nusa Tenggara',
        'region_group' => 'Central Indonesia',
        'sort_order' => 22,
    ],

    'NUSA TENGGARA TIMUR' => [
        'province_code' => 'ID-NT',
        'name' => 'Nusa Tenggara Timur',
        'name_en' => 'East Nusa Tenggara',
        'source_alias' => null,
        'island_group' => 'Bali & Nusa Tenggara',
        'region_group' => 'Eastern Indonesia',
        'sort_order' => 23,
    ],

    'PAPUA' => [
        'province_code' => 'ID-PA',
        'name' => 'Papua',
        'name_en' => 'Papua',
        'source_alias' => null,
        'island_group' => 'Papua',
        'region_group' => 'Eastern Indonesia',
        'sort_order' => 24,
    ],

    'PAPUA BARAT' => [
        'province_code' => 'ID-PB',
        'name' => 'Papua Barat',
        'name_en' => 'West Papua',
        'source_alias' => null,
        'island_group' => 'Papua',
        'region_group' => 'Eastern Indonesia',
        'sort_order' => 25,
    ],

    'PAPUA BARAT DAYA' => [
        'province_code' => 'ID-PBD',
        'name' => 'Papua Barat Daya',
        'name_en' => 'Southwest Papua',
        'source_alias' => null,
        'island_group' => 'Papua',
        'region_group' => 'Eastern Indonesia',
        'sort_order' => 26,
    ],

    'PAPUA SELATAN' => [
        'province_code' => 'ID-PS',
        'name' => 'Papua Selatan',
        'name_en' => 'South Papua',
        'source_alias' => null,
        'island_group' => 'Papua',
        'region_group' => 'Eastern Indonesia',
        'sort_order' => 27,
    ],

    'PAPUA TENGAH' => [
        'province_code' => 'ID-PT',
        'name' => 'Papua Tengah',
        'name_en' => 'Central Papua',
        'source_alias' => null,
        'island_group' => 'Papua',
        'region_group' => 'Eastern Indonesia',
        'sort_order' => 28,
    ],

    'RIAU' => [
        'province_code' => 'ID-RI',
        'name' => 'Riau',
        'name_en' => 'Riau',
        'source_alias' => null,
        'island_group' => 'Sumatra',
        'region_group' => 'Western Indonesia',
        'sort_order' => 29,
    ],

    'SULAWESI BARAT' => [
        'province_code' => 'ID-SR',
        'name' => 'Sulawesi Barat',
        'name_en' => 'West Sulawesi',
        'source_alias' => null,
        'island_group' => 'Sulawesi',
        'region_group' => 'Central Indonesia',
        'sort_order' => 30,
    ],

    'SULAWESI SELATAN' => [
        'province_code' => 'ID-SN',
        'name' => 'Sulawesi Selatan',
        'name_en' => 'South Sulawesi',
        'source_alias' => null,
        'island_group' => 'Sulawesi',
        'region_group' => 'Central Indonesia',
        'sort_order' => 31,
    ],

    'SULAWESI TENGAH' => [
        'province_code' => 'ID-ST',
        'name' => 'Sulawesi Tengah',
        'name_en' => 'Central Sulawesi',
        'source_alias' => null,
        'island_group' => 'Sulawesi',
        'region_group' => 'Central Indonesia',
        'sort_order' => 32,
    ],

    'SULAWESI TENGGARA' => [
        'province_code' => 'ID-SG',
        'name' => 'Sulawesi Tenggara',
        'name_en' => 'Southeast Sulawesi',
        'source_alias' => null,
        'island_group' => 'Sulawesi',
        'region_group' => 'Central Indonesia',
        'sort_order' => 33,
    ],

    'SULAWESI UTARA' => [
        'province_code' => 'ID-SA',
        'name' => 'Sulawesi Utara',
        'name_en' => 'North Sulawesi',
        'source_alias' => null,
        'island_group' => 'Sulawesi',
        'region_group' => 'Central Indonesia',
        'sort_order' => 34,
    ],

    'SUMATERA BARAT' => [
        'province_code' => 'ID-SB',
        'name' => 'Sumatera Barat',
        'name_en' => 'West Sumatra',
        'source_alias' => null,
        'island_group' => 'Sumatra',
        'region_group' => 'Western Indonesia',
        'sort_order' => 35,
    ],

    'SUMATERA SELATAN' => [
        'province_code' => 'ID-SS',
        'name' => 'Sumatera Selatan',
        'name_en' => 'South Sumatra',
        'source_alias' => null,
        'island_group' => 'Sumatra',
        'region_group' => 'Western Indonesia',
        'sort_order' => 36,
    ],

    'SUMATERA UTARA' => [
        'province_code' => 'ID-SU',
        'name' => 'Sumatera Utara',
        'name_en' => 'North Sumatra',
        'source_alias' => null,
        'island_group' => 'Sumatra',
        'region_group' => 'Western Indonesia',
        'sort_order' => 37,
    ],
];

/*
|--------------------------------------------------------------------------
| Read Province Universe
|--------------------------------------------------------------------------
*/

$handle = fopen($inputFile, 'rb');

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
    $columns[trim((string) $name)] = $index;
}

if (!isset($columns['name_normalized'])) {
    fclose($handle);

    throw new RuntimeException(
        'Column name_normalized tidak ditemukan.'
    );
}

$reviewRows = [];
$seen = [];

while (($row = fgetcsv($handle)) !== false) {

    $sourceName = trim(
        (string) ($row[$columns['name_source']] ?? '')
    );

    $normalizedName = strtoupper(
        trim(
            (string) (
                $row[$columns['name_normalized']]
                ?? ''
            )
        )
    );

    if ($normalizedName === '') {
        continue;
    }

    $seen[$normalizedName] = true;

    if (!isset($mapping[$normalizedName])) {
        $reviewRows[] = [
            'name_source' => $sourceName,
            'name_normalized' => $normalizedName,
            'province_code' => null,
            'name' => null,
            'name_en' => null,
            'source_alias' => null,
            'island_group' => null,
            'region_group' => null,
            'sort_order' => null,
            'status' => 'UNMAPPED',
        ];

        continue;
    }

    $item = $mapping[$normalizedName];

    $reviewRows[] = [
        'name_source' => $sourceName,
        'name_normalized' => $normalizedName,
        'province_code' => $item['province_code'],
        'name' => $item['name'],
        'name_en' => $item['name_en'],
        'source_alias' => $item['source_alias'],
        'island_group' => $item['island_group'],
        'region_group' => $item['region_group'],
        'sort_order' => $item['sort_order'],
        'status' => 'MAPPED',
    ];
}

fclose($handle);

/*
|--------------------------------------------------------------------------
| Detect mapping entries not present in source
|--------------------------------------------------------------------------
*/

foreach ($mapping as $sourceName => $item) {

    if (isset($seen[$sourceName])) {
        continue;
    }

    $reviewRows[] = [
        'name_source' => $sourceName,
        'name_normalized' => $sourceName,
        'province_code' => $item['province_code'],
        'name' => $item['name'],
        'name_en' => $item['name_en'],
        'source_alias' => $item['source_alias'],
        'island_group' => $item['island_group'],
        'region_group' => $item['region_group'],
        'sort_order' => $item['sort_order'],
        'status' => 'NOT_IN_SOURCE',
    ];
}

/*
|--------------------------------------------------------------------------
| Sort
|--------------------------------------------------------------------------
*/

usort(
    $reviewRows,
    function (array $a, array $b): int {
        return ($a['sort_order'] ?? 999)
            <=> ($b['sort_order'] ?? 999);
    }
);

/*
|--------------------------------------------------------------------------
| Write review file
|--------------------------------------------------------------------------
*/

$output = fopen($outputFile, 'wb');

if ($output === false) {
    throw new RuntimeException(
        "Tidak dapat membuat:\n{$outputFile}"
    );
}

fputcsv(
    $output,
    [
        'name_source',
        'name_normalized',
        'province_code',
        'name',
        'name_en',
        'source_alias',
        'island_group',
        'region_group',
        'sort_order',
        'status',
    ]
);

foreach ($reviewRows as $row) {
    fputcsv(
        $output,
        [
            $row['name_source'],
            $row['name_normalized'],
            $row['province_code'],
            $row['name'],
            $row['name_en'],
            $row['source_alias'],
            $row['island_group'],
            $row['region_group'],
            $row['sort_order'],
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

$mapped = 0;
$unmapped = 0;
$notInSource = 0;

foreach ($reviewRows as $row) {
    if ($row['status'] === 'MAPPED') {
        $mapped++;
    } elseif ($row['status'] === 'UNMAPPED') {
        $unmapped++;
    } elseif ($row['status'] === 'NOT_IN_SOURCE') {
        $notInSource++;
    }
}

echo "========================================\n";
echo "DIGESTEX PROVINCE MASTER AUDIT\n";
echo "========================================\n\n";

echo "SOURCE PROVINCE        : "
    . count($seen)
    . PHP_EOL;

echo "MAPPED                 : "
    . $mapped
    . PHP_EOL;

echo "UNMAPPED               : "
    . $unmapped
    . PHP_EOL;

echo "MAPPING NOT IN SOURCE  : "
    . $notInSource
    . PHP_EOL;

echo "\nOUTPUT:\n";
echo $outputFile . PHP_EOL;

echo "\n========================================\n";

if ($unmapped > 0) {
    echo "WARNING: ADA PROVINCE YANG BELUM TERMAPPING.\n";
} else {
    echo "ALL SOURCE PROVINCES MAPPED.\n";
}

echo "DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";