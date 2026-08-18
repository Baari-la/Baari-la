<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| DIGESTEX Trade Point Resolver Audit
|--------------------------------------------------------------------------
|
| INPUT:
|   Desktop\DIGESTEX_DATA\PROCESSED\trade_point_universe_2019_2026.csv
|
| SERVICE:
|   App\Services\Trade\TradePointResolverService
|
| OUTPUT:
|   Desktop\DIGESTEX_DATA\PROCESSED\trade_point_resolver_audit.csv
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

use App\Services\Trade\TradePointResolverService;

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
    . 'trade_point_resolver_audit.csv';

if (!is_file($inputFile)) {
    throw new RuntimeException(
        "Trade point universe tidak ditemukan:\n{$inputFile}"
    );
}

/*
|--------------------------------------------------------------------------
| Resolver
|--------------------------------------------------------------------------
*/

$resolver = app(
    TradePointResolverService::class
);

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

$required = [
    'trade_point_source',
    'trade_point_normalized',
    'source_province_count',
    'source_provinces',
    'first_flow',
    'export_seen',
    'import_seen',
    'occurrence_count',
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
| Process
|--------------------------------------------------------------------------
*/

$rows = [];

$total = 0;
$resolved = 0;
$unresolved = 0;

$resolvedByCode = [];

while (($row = fgetcsv($handle)) !== false) {

    $sourceName = trim(
        (string) $row[
            $columns['trade_point_source']
        ]
    );

    $normalizedName = trim(
        (string) $row[
            $columns['trade_point_normalized']
        ]
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

    $firstFlow = trim(
        (string) $row[
            $columns['first_flow']
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

    $total++;

    /*
    |--------------------------------------------------------------------------
    | Resolve exact normalized alias
    |--------------------------------------------------------------------------
    */

    $tradePoint = $resolver->resolve(
        $sourceName,
        'KEMENDAG'
    );

    if ($tradePoint !== null) {

        $status = 'RESOLVED';

        $tradePointId = $tradePoint->id;

        $tradePointCode = $tradePoint->code;

        $canonicalName = $tradePoint->name;

        $resolved++;

        if (!isset($resolvedByCode[$tradePointCode])) {
            $resolvedByCode[$tradePointCode] = 0;
        }

        $resolvedByCode[$tradePointCode]++;

    } else {

        $status = 'UNRESOLVED';

        $tradePointId = null;

        $tradePointCode = null;

        $canonicalName = null;

        $unresolved++;
    }

    $rows[] = [
        'trade_point_source' =>
            $sourceName,

        'normalized_name' =>
            $normalizedName,

        'source_province_count' =>
            $sourceProvinceCount,

        'source_provinces' =>
            $sourceProvinces,

        'first_flow' =>
            $firstFlow,

        'export_seen' =>
            $exportSeen,

        'import_seen' =>
            $importSeen,

        'occurrence_count' =>
            $occurrenceCount,

        'status' =>
            $status,

        'trade_point_id' =>
            $tradePointId,

        'trade_point_code' =>
            $tradePointCode,

        'canonical_name' =>
            $canonicalName,
    ];
}

fclose($handle);

/*
|--------------------------------------------------------------------------
| Write audit CSV
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
        'normalized_name',
        'source_province_count',
        'source_provinces',
        'first_flow',
        'export_seen',
        'import_seen',
        'occurrence_count',
        'status',
        'trade_point_id',
        'trade_point_code',
        'canonical_name',
    ]
);

foreach ($rows as $row) {

    fputcsv(
        $output,
        [
            $row['trade_point_source'],
            $row['normalized_name'],
            $row['source_province_count'],
            $row['source_provinces'],
            $row['first_flow'],
            $row['export_seen'],
            $row['import_seen'],
            $row['occurrence_count'],
            $row['status'],
            $row['trade_point_id'],
            $row['trade_point_code'],
            $row['canonical_name'],
        ]
    );
}

fclose($output);

/*
|--------------------------------------------------------------------------
| Validation
|--------------------------------------------------------------------------
*/

$expectedTotal = 192;
$expectedResolved = 59;
$expectedUnresolved = 133;

$validationErrors = [];

if ($total !== $expectedTotal) {
    $validationErrors[] =
        "Expected {$expectedTotal} source trade points, found {$total}.";
}

if ($resolved !== $expectedResolved) {
    $validationErrors[] =
        "Expected {$expectedResolved} resolved aliases, found {$resolved}.";
}

if ($unresolved !== $expectedUnresolved) {
    $validationErrors[] =
        "Expected {$expectedUnresolved} unresolved source names, found {$unresolved}.";
}

if (
    ($resolved + $unresolved)
    !== $total
) {
    $validationErrors[] =
        'Resolved + unresolved tidak sama dengan total source trade points.';
}

/*
|--------------------------------------------------------------------------
| Summary
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "DIGESTEX TRADE POINT RESOLVER AUDIT\n";
echo "========================================\n\n";

echo "TOTAL SOURCE TRADE POINTS : "
    . $total
    . PHP_EOL;

echo "RESOLVED                  : "
    . $resolved
    . PHP_EOL;

echo "UNRESOLVED                : "
    . $unresolved
    . PHP_EOL;

echo "CANONICAL POINTS REACHED  : "
    . count($resolvedByCode)
    . PHP_EOL;

echo "\nOUTPUT:\n";
echo $outputFile . PHP_EOL;

echo "\n========================================\n";

if (empty($validationErrors)) {

    echo "VALIDATION RESULT : PASS\n";
    echo "========================================\n";
    echo "Resolver behaves as expected.\n";

} else {

    echo "VALIDATION RESULT : FAIL\n";
    echo "========================================\n";

    foreach ($validationErrors as $error) {
        echo " - {$error}\n";
    }
}

echo "\nDATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";