<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| DIGESTEX Trade Point Canonical Audit
|--------------------------------------------------------------------------
|
| Input:
|   Desktop\DIGESTEX_DATA\PROCESSED\trade_point_universe_2019_2026.csv
|
| Curated source:
|   config\trade_point_curated.php
|
| Output:
|   Desktop\DIGESTEX_DATA\PROCESSED\trade_point_canonical_review.csv
|
| Database:
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
    . 'trade_point_universe_2019_2026.csv';

$outputFile =
    $base
    . DIRECTORY_SEPARATOR
    . 'PROCESSED'
    . DIRECTORY_SEPARATOR
    . 'trade_point_canonical_review.csv';

$curatedFile =
    dirname(__DIR__)
    . DIRECTORY_SEPARATOR
    . 'config'
    . DIRECTORY_SEPARATOR
    . 'trade_point_curated.php';

/*
|--------------------------------------------------------------------------
| Validate files
|--------------------------------------------------------------------------
*/

if (!is_file($inputFile)) {
    throw new RuntimeException(
        "Trade point universe tidak ditemukan:\n{$inputFile}"
    );
}

if (!is_file($curatedFile)) {
    throw new RuntimeException(
        "Curated trade point config tidak ditemukan:\n{$curatedFile}"
    );
}

/*
|--------------------------------------------------------------------------
| Load curated registry
|--------------------------------------------------------------------------
*/

$curated = require $curatedFile;

if (!is_array($curated)) {
    throw new RuntimeException(
        'config/trade_point_curated.php harus mengembalikan array.'
    );
}

/*
|--------------------------------------------------------------------------
| Validate curated registry
|--------------------------------------------------------------------------
*/

$requiredFields = [
    'canonical_name',
    'canonical_name_en',
    'type_code',
    'physical_province_code',
    'city',
];

foreach ($curated as $sourceName => $mapping) {

    if (!is_array($mapping)) {
        throw new RuntimeException(
            "Mapping tidak valid untuk source trade point: {$sourceName}"
        );
    }

    foreach ($requiredFields as $field) {

        if (!array_key_exists($field, $mapping)) {
            throw new RuntimeException(
                "Field '{$field}' tidak ditemukan pada mapping: {$sourceName}"
            );
        }
    }
}

/*
|--------------------------------------------------------------------------
| Read source universe
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

$requiredSourceColumns = [
    'trade_point_source',
    'trade_point_normalized',
    'source_province_count',
    'source_provinces',
    'export_seen',
    'import_seen',
    'occurrence_count',
];

foreach ($requiredSourceColumns as $column) {

    if (!array_key_exists($column, $columns)) {

        fclose($handle);

        throw new RuntimeException(
            "Column source tidak ditemukan: {$column}"
        );
    }
}

/*
|--------------------------------------------------------------------------
| Build review rows
|--------------------------------------------------------------------------
*/

$rows = [];

while (($row = fgetcsv($handle)) !== false) {

    $sourceName = trim(
        (string) $row[
            $columns['trade_point_source']
        ]
    );

    $normalizedName = mb_strtoupper(
        trim(
            (string) $row[
                $columns['trade_point_normalized']
            ]
        )
    );

    if ($normalizedName === '') {
        continue;
    }

    $sourceProvinceCount = (int) (
        $row[
            $columns['source_province_count']
        ]
    );

    $sourceProvinces = trim(
        (string) $row[
            $columns['source_provinces']
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
    | Default = REVIEW
    |--------------------------------------------------------------------------
    */

    $canonicalName = null;
    $canonicalNameEn = null;
    $typeCode = null;
    $physicalProvinceCode = null;
    $city = null;

    $status = 'REVIEW';

    $reviewNote =
        'Canonical mapping not yet approved.';

    /*
    |--------------------------------------------------------------------------
    | Apply curated mapping
    |--------------------------------------------------------------------------
    */

    if (isset($curated[$normalizedName])) {

        $mapping = $curated[$normalizedName];

        $canonicalName =
            $mapping['canonical_name'];

        $canonicalNameEn =
            $mapping['canonical_name_en'];

        $typeCode =
            $mapping['type_code'];

        $physicalProvinceCode =
            $mapping['physical_province_code'];

        $city =
            $mapping['city'];

        $status = 'APPROVED';

        $reviewNote =
            'Matched curated DIGESTEX trade point registry.';
    }

    /*
    |--------------------------------------------------------------------------
    | Candidate canonical code
    |--------------------------------------------------------------------------
    */

    $candidateCode = null;

    if ($canonicalName !== null) {

        $slug = preg_replace(
            '/[^A-Z0-9]+/',
            '-',
            mb_strtoupper($canonicalName)
        ) ?? '';

        $slug = trim(
            $slug,
            '-'
        );

        if ($slug !== '') {
            $candidateCode = 'TP-' . $slug;
        }
    }

    $rows[] = [
        'trade_point_source' =>
            $sourceName,

        'trade_point_normalized' =>
            $normalizedName,

        'source_province_count' =>
            $sourceProvinceCount,

        'source_provinces' =>
            $sourceProvinces,

        'canonical_name' =>
            $canonicalName,

        'canonical_name_en' =>
            $canonicalNameEn,

        'candidate_code' =>
            $candidateCode,

        'trade_point_type_code' =>
            $typeCode,

        'physical_province_code' =>
            $physicalProvinceCode,

        'city' =>
            $city,

        'export_seen' =>
            $exportSeen,

        'import_seen' =>
            $importSeen,

        'occurrence_count' =>
            $occurrenceCount,

        'status' =>
            $status,

        'review_note' =>
            $reviewNote,
    ];
}

fclose($handle);

/*
|--------------------------------------------------------------------------
| Sort
|--------------------------------------------------------------------------
*/

usort(
    $rows,
    function (array $a, array $b): int {

        $statusRank = [
            'APPROVED' => 1,
            'REVIEW' => 2,
        ];

        $aRank =
            $statusRank[$a['status']] ?? 99;

        $bRank =
            $statusRank[$b['status']] ?? 99;

        if ($aRank !== $bRank) {
            return $aRank <=> $bRank;
        }

        return strcmp(
            $a['trade_point_normalized'],
            $b['trade_point_normalized']
        );
    }
);

/*
|--------------------------------------------------------------------------
| Write output
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
        'trade_point_source',
        'trade_point_normalized',
        'source_province_count',
        'source_provinces',
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
        'review_note',
    ]
);

foreach ($rows as $row) {

    fputcsv(
        $output,
        [
            $row['trade_point_source'],
            $row['trade_point_normalized'],
            $row['source_province_count'],
            $row['source_provinces'],
            $row['canonical_name'],
            $row['canonical_name_en'],
            $row['candidate_code'],
            $row['trade_point_type_code'],
            $row['physical_province_code'],
            $row['city'],
            $row['export_seen'],
            $row['import_seen'],
            $row['occurrence_count'],
            $row['status'],
            $row['review_note'],
        ]
    );
}

fclose($output);

/*
|--------------------------------------------------------------------------
| Summary
|--------------------------------------------------------------------------
*/

$total = count($rows);

$approved = 0;
$review = 0;

foreach ($rows as $row) {

    if ($row['status'] === 'APPROVED') {
        $approved++;
    }

    if ($row['status'] === 'REVIEW') {
        $review++;
    }
}

echo "========================================\n";
echo "DIGESTEX TRADE POINT CANONICAL REVIEW\n";
echo "========================================\n\n";

echo "TOTAL TRADE POINTS : {$total}\n";
echo "APPROVED            : {$approved}\n";
echo "REVIEW              : {$review}\n";

echo "\nCURATED MAPPINGS    : "
    . count($curated)
    . "\n";

echo "\nOUTPUT:\n";
echo $outputFile . "\n";

echo "\n========================================\n";
echo "DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";