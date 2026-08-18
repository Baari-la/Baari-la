<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| DIGESTEX Trade Point Canonical Master Validator
|--------------------------------------------------------------------------
|
| INPUT:
|   Desktop\DIGESTEX_DATA\PROCESSED\trade_point_canonical_master_v1.csv
|
| DATABASE:
|   READ ONLY
|
|--------------------------------------------------------------------------
*/

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

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
    . 'trade_point_canonical_master_v1.csv';

if (!is_file($inputFile)) {
    throw new RuntimeException(
        "Canonical master file tidak ditemukan:\n{$inputFile}"
    );
}

/*
|--------------------------------------------------------------------------
| Expected trade point types
|--------------------------------------------------------------------------
*/

$validTypes = DB::table('trade_point_types')
    ->where('is_active', true)
    ->pluck('id', 'code')
    ->toArray();

if (empty($validTypes)) {
    throw new RuntimeException(
        'Tidak ada active trade point types di database.'
    );
}

/*
|--------------------------------------------------------------------------
| Valid province codes
|--------------------------------------------------------------------------
*/

$validProvinceCodes = DB::table('provinces')
    ->where('is_active', true)
    ->pluck('id', 'code')
    ->toArray();

if (empty($validProvinceCodes)) {
    throw new RuntimeException(
        'Tidak ada active province codes di database.'
    );
}

/*
|--------------------------------------------------------------------------
| Read CSV
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

$requiredColumns = [
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
];

foreach ($requiredColumns as $column) {
    if (!array_key_exists($column, $columns)) {
        fclose($handle);

        throw new RuntimeException(
            "Column tidak ditemukan: {$column}"
        );
    }
}

/*
|--------------------------------------------------------------------------
| Counters
|--------------------------------------------------------------------------
*/

$rows = [];

$duplicateCodes = [];
$duplicateNames = [];

$errors = [];

$typeSummary = [];

$totalAliases = 0;
$totalOccurrences = 0;

/*
|--------------------------------------------------------------------------
| Process records
|--------------------------------------------------------------------------
*/

while (($row = fgetcsv($handle)) !== false) {

    $code = trim(
        (string) $row[$columns['code']]
    );

    $name = trim(
        (string) $row[$columns['name']]
    );

    $nameEn = trim(
        (string) $row[$columns['name_en']]
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
        (string) $row[$columns['city']]
    );

    $aliasCount = (int) $row[
        $columns['alias_count']
    ];

    $sourceAliases = trim(
        (string) $row[
            $columns['source_aliases']
        ]
    );

    $sourceAliasesNormalized = trim(
        (string) $row[
            $columns['source_aliases_normalized']
        ]
    );

    $exportSeen = (int) $row[
        $columns['export_seen']
    ];

    $importSeen = (int) $row[
        $columns['import_seen']
    ];

    $occurrenceCount = (int) $row[
        $columns['occurrence_count']
    ];

    $status = trim(
        (string) $row[$columns['status']]
    );

    $records = [
        'code' => $code,
        'name' => $name,
        'name_en' => $nameEn,
        'trade_point_type_code' => $typeCode,
        'physical_province_code' => $provinceCode,
        'city' => $city,
        'alias_count' => $aliasCount,
        'source_aliases' => $sourceAliases,
        'source_aliases_normalized' =>
            $sourceAliasesNormalized,
        'export_seen' => $exportSeen,
        'import_seen' => $importSeen,
        'occurrence_count' => $occurrenceCount,
        'status' => $status,
    ];

    $rows[] = $records;

    /*
    |--------------------------------------------------------------------------
    | Required fields
    |--------------------------------------------------------------------------
    */

    $requiredValues = [
        'code' => $code,
        'name' => $name,
        'name_en' => $nameEn,
        'trade_point_type_code' => $typeCode,
        'physical_province_code' => $provinceCode,
        'city' => $city,
        'status' => $status,
    ];

    foreach ($requiredValues as $field => $value) {
        if ($value === '') {
            $errors[] =
                "{$code}: {$field} is empty";
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */

    if ($status !== 'APPROVED') {
        $errors[] =
            "{$code}: status is not APPROVED";
    }

    /*
    |--------------------------------------------------------------------------
    | Type validation
    |--------------------------------------------------------------------------
    */

    if ($typeCode !== '' && !isset($validTypes[$typeCode])) {
        $errors[] =
            "{$code}: invalid trade point type {$typeCode}";
    }

    /*
    |--------------------------------------------------------------------------
    | Province validation
    |--------------------------------------------------------------------------
    */

    if (
        $provinceCode !== ''
        &&
        !isset($validProvinceCodes[$provinceCode])
    ) {
        $errors[] =
            "{$code}: invalid province code {$provinceCode}";
    }

    /*
    |--------------------------------------------------------------------------
    | Alias validation
    |--------------------------------------------------------------------------
    */

    if ($aliasCount < 1) {
        $errors[] =
            "{$code}: alias_count must be >= 1";
    }

    $aliasArray = $sourceAliases === ''
        ? []
        : array_filter(
            array_map(
                'trim',
                explode('|', $sourceAliases)
            ),
            fn ($value) => $value !== ''
        );

    $aliasNormalizedArray =
        $sourceAliasesNormalized === ''
            ? []
            : array_filter(
                array_map(
                    'trim',
                    explode('|', $sourceAliasesNormalized)
                ),
                fn ($value) => $value !== ''
            );

    if (count($aliasArray) !== $aliasCount) {
        $errors[] =
            "{$code}: alias_count mismatch. "
            . "Expected {$aliasCount}, "
            . "found " . count($aliasArray);
    }

    if (
        count($aliasNormalizedArray)
        !== $aliasCount
    ) {
        $errors[] =
            "{$code}: normalized alias count mismatch.";
    }

    /*
    |--------------------------------------------------------------------------
    | Export / Import flag validation
    |--------------------------------------------------------------------------
    */

    if (!in_array($exportSeen, [0, 1], true)) {
        $errors[] =
            "{$code}: export_seen must be 0 or 1";
    }

    if (!in_array($importSeen, [0, 1], true)) {
        $errors[] =
            "{$code}: import_seen must be 0 or 1";
    }

    if ($occurrenceCount < 1) {
        $errors[] =
            "{$code}: occurrence_count must be >= 1";
    }

    /*
    |--------------------------------------------------------------------------
    | Duplicate candidates
    |--------------------------------------------------------------------------
    */

    if ($code !== '') {
        if (isset($duplicateCodes[$code])) {
            $duplicateCodes[$code]++;
        } else {
            $duplicateCodes[$code] = 1;
        }
    }

    if ($name !== '') {
        $normalizedName = mb_strtoupper(
            preg_replace(
                '/\s+/',
                ' ',
                $name
            ) ?? ''
        );

        if (isset($duplicateNames[$normalizedName])) {
            $duplicateNames[$normalizedName]++;
        } else {
            $duplicateNames[$normalizedName] = 1;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Type summary
    |--------------------------------------------------------------------------
    */

    if ($typeCode !== '') {
        if (!isset($typeSummary[$typeCode])) {
            $typeSummary[$typeCode] = 0;
        }

        $typeSummary[$typeCode]++;
    }

    $totalAliases += $aliasCount;
    $totalOccurrences += $occurrenceCount;
}

fclose($handle);

/*
|--------------------------------------------------------------------------
| Find duplicate codes/names
|--------------------------------------------------------------------------
*/

$duplicateCodeList = array_filter(
    $duplicateCodes,
    fn (int $count): bool => $count > 1
);

$duplicateNameList = array_filter(
    $duplicateNames,
    fn (int $count): bool => $count > 1
);

/*
|--------------------------------------------------------------------------
| Structural checks
|--------------------------------------------------------------------------
*/

$totalRecords = count($rows);

if ($totalRecords === 0) {
    $errors[] = 'Canonical master contains zero records.';
}

if ($totalRecords !== 49) {
    $errors[] =
        "Expected 49 canonical trade points, "
        . "found {$totalRecords}.";
}

if (!empty($duplicateCodeList)) {
    foreach ($duplicateCodeList as $code => $count) {
        $errors[] =
            "Duplicate canonical code {$code} "
            . "appears {$count} times.";
    }
}

if (!empty($duplicateNameList)) {
    foreach ($duplicateNameList as $name => $count) {
        $errors[] =
            "Duplicate canonical name {$name} "
            . "appears {$count} times.";
    }
}

/*
|--------------------------------------------------------------------------
| Print report
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "DIGESTEX TRADE POINT CANONICAL MASTER AUDIT\n";
echo "========================================\n\n";

echo "TOTAL RECORDS          : "
    . $totalRecords
    . PHP_EOL;

echo "TOTAL SOURCE ALIASES   : "
    . $totalAliases
    . PHP_EOL;

echo "TOTAL OCCURRENCES      : "
    . $totalOccurrences
    . PHP_EOL;

echo "UNIQUE CANONICAL CODES : "
    . count($duplicateCodes)
    . PHP_EOL;

echo "DUPLICATE CODES        : "
    . count($duplicateCodeList)
    . PHP_EOL;

echo "DUPLICATE NAMES        : "
    . count($duplicateNameList)
    . PHP_EOL;

echo "\nTYPE SUMMARY:\n";

ksort($typeSummary);

foreach ($typeSummary as $type => $count) {
    echo sprintf(
        "  %-20s : %d\n",
        $type,
        $count
    );
}

echo "\nVALID ACTIVE TRADE TYPES : "
    . count($validTypes)
    . PHP_EOL;

echo "VALID ACTIVE PROVINCES   : "
    . count($validProvinceCodes)
    . PHP_EOL;

echo "\n========================================\n";

if (empty($errors)) {

    echo "VALIDATION RESULT : PASS\n";
    echo "========================================\n";
    echo "Canonical master is ready for seeding.\n";

} else {

    echo "VALIDATION RESULT : FAIL\n";
    echo "========================================\n";

    echo "\nERRORS (" . count($errors) . "):\n";

    foreach ($errors as $error) {
        echo " - {$error}\n";
    }
}

echo "\nDATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";