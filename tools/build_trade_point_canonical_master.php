<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| DIGESTEX Canonical Trade Point Master Builder V1
|--------------------------------------------------------------------------
|
| INPUT:
|   trade_point_canonical_review.csv
|
| OUTPUT:
|   trade_point_canonical_master_v1.csv
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
    . 'trade_point_canonical_review.csv';

$outputFile =
    $base
    . DIRECTORY_SEPARATOR
    . 'PROCESSED'
    . DIRECTORY_SEPARATOR
    . 'trade_point_canonical_master_v1.csv';

if (!is_file($inputFile)) {
    throw new RuntimeException(
        "Input file tidak ditemukan:\n{$inputFile}"
    );
}

/*
|--------------------------------------------------------------------------
| Read approved aliases
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

$required = [
    'trade_point_source',
    'trade_point_normalized',
    'canonical_name',
    'canonical_name_en',
    'candidate_code',
    'trade_point_type_code',
    'physical_province_code',
    'city',
    'export_seen',
    'import_seen',
    'occurrence_count',
    'status',
];

foreach ($required as $column) {
    if (!array_key_exists($column, $columns)) {
        fclose($handle);

        throw new RuntimeException(
            "Column tidak ditemukan: {$column}"
        );
    }
}

/*
|--------------------------------------------------------------------------
| Group by canonical physical trade point
|--------------------------------------------------------------------------
*/

$master = [];

$totalApprovedAliases = 0;

while (($row = fgetcsv($handle)) !== false) {

    $status = trim(
        (string) $row[
            $columns['status']
        ]
    );

    if ($status !== 'APPROVED') {
        continue;
    }

    $canonicalName = trim(
        (string) $row[
            $columns['canonical_name']
        ]
    );

    if ($canonicalName === '') {
        continue;
    }

    $canonicalNameEn = trim(
        (string) $row[
            $columns['canonical_name_en']
        ]
    );

    $candidateCode = trim(
        (string) $row[
            $columns['candidate_code']
        ]
    );

    $typeCode = trim(
        (string) $row[
            $columns['trade_point_type_code']
        ]
    );

    $provinceCode = trim(
        (string) $row[
            $columns['physical_province_code']
        ]
    );

    $city = trim(
        (string) $row[
            $columns['city']
        ]
    );

    $sourceName = trim(
        (string) $row[
            $columns['trade_point_source']
        ]
    );

    $sourceNormalized = trim(
        (string) $row[
            $columns['trade_point_normalized']
        ]
    );

    $exportSeen = (int) (
        $row[
            $columns['export_seen']
        ]
    );

    $importSeen = (int) (
        $row[
            $columns['import_seen']
        ]
    );

    $occurrenceCount = (int) (
        $row[
            $columns['occurrence_count']
        ]
    );

    /*
    |--------------------------------------------------------------------------
    | First canonical record
    |--------------------------------------------------------------------------
    */

    if (!isset($master[$canonicalName])) {

        $master[$canonicalName] = [
            'code' =>
                $candidateCode,

            'name' =>
                $canonicalName,

            'name_en' =>
                $canonicalNameEn,

            'trade_point_type_code' =>
                $typeCode,

            'physical_province_code' =>
                $provinceCode,

            'city' =>
                $city,

            'alias_count' =>
                0,

            'source_aliases' =>
                [],

            'source_aliases_normalized' =>
                [],

            'export_seen' =>
                0,

            'import_seen' =>
                0,

            'occurrence_count' =>
                0,

            'status' =>
                'APPROVED',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Consistency validation
    |--------------------------------------------------------------------------
    */

    if (
        $master[$canonicalName]['code']
        !== $candidateCode
    ) {
        throw new RuntimeException(
            "Candidate code tidak konsisten untuk canonical name: "
            . $canonicalName
        );
    }

    if (
        $master[$canonicalName]['trade_point_type_code']
        !== $typeCode
    ) {
        throw new RuntimeException(
            "Trade point type tidak konsisten untuk canonical name: "
            . $canonicalName
        );
    }

    if (
        $master[$canonicalName]['physical_province_code']
        !== $provinceCode
    ) {
        throw new RuntimeException(
            "Physical province tidak konsisten untuk canonical name: "
            . $canonicalName
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Add source alias
    |--------------------------------------------------------------------------
    */

    $master[$canonicalName]['alias_count']++;

    $master[$canonicalName][
        'source_aliases'
    ][] = $sourceName;

    $master[$canonicalName][
        'source_aliases_normalized'
    ][] = $sourceNormalized;

    /*
    |--------------------------------------------------------------------------
    | Aggregate visibility
    |--------------------------------------------------------------------------
    */

    if ($exportSeen === 1) {
        $master[$canonicalName]['export_seen'] = 1;
    }

    if ($importSeen === 1) {
        $master[$canonicalName]['import_seen'] = 1;
    }

    $master[$canonicalName]['occurrence_count'] +=
        $occurrenceCount;

    $totalApprovedAliases++;
}

fclose($handle);

/*
|--------------------------------------------------------------------------
| Sort canonical master
|--------------------------------------------------------------------------
*/

uasort(
    $master,
    function (array $a, array $b): int {
        return strcmp(
            $a['name'],
            $b['name']
        );
    }
);

/*
|--------------------------------------------------------------------------
| Validate unique canonical code
|--------------------------------------------------------------------------
*/

$codes = [];

foreach ($master as $item) {

    $code = $item['code'];

    if ($code === '') {
        throw new RuntimeException(
            "Canonical trade point tidak memiliki code: "
            . $item['name']
        );
    }

    if (isset($codes[$code])) {
        throw new RuntimeException(
            "Duplicate canonical code ditemukan: {$code}"
        );
    }

    $codes[$code] = true;
}

/*
|--------------------------------------------------------------------------
| Sort aliases inside each canonical point
|--------------------------------------------------------------------------
*/

foreach ($master as &$item) {

    array_multisort(
        $item['source_aliases'],
        SORT_ASC,
        SORT_STRING,
        $item['source_aliases_normalized'],
        SORT_ASC,
        SORT_STRING
    );
}

unset($item);

/*
|--------------------------------------------------------------------------
| Write master CSV
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
        'code',
        'name',
        'name_en',
        'trade_point_type_code',
        'physical_province_code',
        'city',
        'alias_count',
        'source_aliases',
        'source_aliases_normalized',
        'export_seen',
        'import_seen',
        'occurrence_count',
        'status',
    ]
);

foreach ($master as $item) {

    fputcsv(
        $output,
        [
            $item['code'],
            $item['name'],
            $item['name_en'],
            $item['trade_point_type_code'],
            $item['physical_province_code'],
            $item['city'],
            $item['alias_count'],

            implode(
                ' | ',
                $item['source_aliases']
            ),

            implode(
                ' | ',
                $item['source_aliases_normalized']
            ),

            $item['export_seen'],
            $item['import_seen'],
            $item['occurrence_count'],
            $item['status'],
        ]
    );
}

fclose($output);

/*
|--------------------------------------------------------------------------
| Summary
|--------------------------------------------------------------------------
*/

$canonicalCount = count($master);

$aliasGroups = 0;

foreach ($master as $item) {

    if ($item['alias_count'] > 1) {
        $aliasGroups++;
    }
}

echo "========================================\n";
echo "DIGESTEX CANONICAL TRADE POINT MASTER V1\n";
echo "========================================\n\n";

echo "APPROVED SOURCE ALIASES : "
    . $totalApprovedAliases
    . PHP_EOL;

echo "CANONICAL TRADE POINTS  : "
    . $canonicalCount
    . PHP_EOL;

echo "ALIAS GROUPS             : "
    . $aliasGroups
    . PHP_EOL;

echo "UNIQUE CANONICAL CODES  : "
    . count($codes)
    . PHP_EOL;

echo "\nOUTPUT:\n";
echo $outputFile . PHP_EOL;

echo "\n========================================\n";
echo "DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";