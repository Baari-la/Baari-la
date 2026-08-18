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
| DIGESTEX TRADE COUNTRY ALIAS V2 BUILDER
|--------------------------------------------------------------------------
|
| PURPOSE:
|   Build a validated country alias registry from:
|
|   1. Existing curated aliases V1
|   2. Approved master expansion PASS records
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

$validationFile =
    $base
    . DIRECTORY_SEPARATOR
    . 'PROCESSED'
    . DIRECTORY_SEPARATOR
    . 'trade_country_master_expansion_validation_2019.csv';

$outputFile =
    $base
    . DIRECTORY_SEPARATOR
    . 'PROCESSED'
    . DIRECTORY_SEPARATOR
    . 'trade_country_alias_curated_v2.csv';

$curatedFile =
    base_path(
        'config/trade_country_curated.php'
    );

if (!is_file($validationFile)) {
    throw new RuntimeException(
        "Validation file tidak ditemukan:\n{$validationFile}"
    );
}

if (!is_file($curatedFile)) {
    throw new RuntimeException(
        "Curated V1 file tidak ditemukan:\n{$curatedFile}"
    );
}

/*
|--------------------------------------------------------------------------
| Normalize
|--------------------------------------------------------------------------
*/

function normalizeCountryAlias(
    string $value
): string {
    $value = trim($value);

    if ($value === '') {
        return '';
    }

    $value = preg_replace(
        '/\s+/',
        ' ',
        $value
    ) ?? '';

    return mb_strtoupper($value);
}

/*
|--------------------------------------------------------------------------
| Load current 144-country master
|--------------------------------------------------------------------------
*/

$countryRows = DB::table('mst_countries')
    ->where('is_active', true)
    ->get([
        'id',
        'country_code',
        'iso3',
        'country_name_en',
        'country_name_id',
    ]);

$countryByCode = [];

foreach ($countryRows as $country) {

    $code = strtoupper(
        trim(
            (string) $country->country_code
        )
    );

    if ($code !== '') {
        $countryByCode[$code] =
            $country;
    }
}

/*
|--------------------------------------------------------------------------
| Load curated V1
|--------------------------------------------------------------------------
*/

$curatedV1 =
    require $curatedFile;

if (!is_array($curatedV1)) {
    throw new RuntimeException(
        'trade_country_curated.php harus mengembalikan array.'
    );
}

$aliases = [];

/*
|--------------------------------------------------------------------------
| Add V1 aliases
|--------------------------------------------------------------------------
*/

foreach ($curatedV1 as $sourceName => $mapping) {

    $normalized =
        normalizeCountryAlias(
            (string) $sourceName
        );

    $countryCode =
        strtoupper(
            trim(
                (string) (
                    $mapping['country_code']
                    ?? ''
                )
            )
        );

    if (
        $normalized === ''
        ||
        $countryCode === ''
    ) {
        continue;
    }

    if (!isset($countryByCode[$countryCode])) {
        throw new RuntimeException(
            "Alias V1 mengarah ke country_code yang tidak ada "
            . "di mst_countries: {$countryCode}"
            . " ({$sourceName})"
        );
    }

    $aliases[$normalized] = [
        'source_name' =>
            $sourceName,

        'normalized_name' =>
            $normalized,

        'country_code' =>
            $countryCode,

        'country_name_en' =>
            $countryByCode[
                $countryCode
            ]->country_name_en,

        'country_name_id' =>
            $countryByCode[
                $countryCode
            ]->country_name_id,

        'entity_class' =>
            'EXISTING',
    ];
}

/*
|--------------------------------------------------------------------------
| Read expansion validation
|--------------------------------------------------------------------------
*/

$handle = fopen(
    $validationFile,
    'rb'
);

if ($handle === false) {
    throw new RuntimeException(
        "Tidak dapat membuka:\n{$validationFile}"
    );
}

$header = fgetcsv($handle);

if ($header === false) {
    fclose($handle);

    throw new RuntimeException(
        'Header expansion validation tidak ditemukan.'
    );
}

$columns = [];

foreach ($header as $index => $name) {
    $columns[
        trim((string) $name)
    ] = $index;
}

foreach ([
    'country_source',
    'candidate_iso2',
    'candidate_name_en',
    'candidate_name_id',
    'entity_class',
    'overall_status',
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
| Add only PASS master expansion records
|--------------------------------------------------------------------------
*/

$expansionAdded = 0;
$expansionSkipped = 0;

while (($row = fgetcsv($handle)) !== false) {

    $overallStatus = strtoupper(
        trim(
            (string) $row[
                $columns['overall_status']
            ]
        )
    );

    if ($overallStatus !== 'PASS') {
        $expansionSkipped++;
        continue;
    }

    $sourceName = trim(
        (string) $row[
            $columns['country_source']
        ]
    );

    $normalized =
        normalizeCountryAlias(
            $sourceName
        );

    $countryCode = strtoupper(
        trim(
            (string) $row[
                $columns['candidate_iso2']
            ]
        )
    );

    $entityClass = strtoupper(
        trim(
            (string) $row[
                $columns['entity_class']
            ]
        )
    );

    if (
        $normalized === ''
        ||
        $countryCode === ''
    ) {
        $expansionSkipped++;
        continue;
    }

    /*
    |--------------------------------------------------------------------------
    | Final master check
    |--------------------------------------------------------------------------
    */

    if (!isset($countryByCode[$countryCode])) {
        throw new RuntimeException(
            "PASS candidate tidak ditemukan di mst_countries: "
            . "{$countryCode} / {$sourceName}"
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Protect duplicate aliases
    |--------------------------------------------------------------------------
    */

    if (isset($aliases[$normalized])) {

        /*
        |--------------------------------------------------------------------------
        | Same source alias + same country is harmless.
        |--------------------------------------------------------------------------
        */

        if (
            $aliases[$normalized]['country_code']
            ===
            $countryCode
        ) {
            $expansionSkipped++;
            continue;
        }

        throw new RuntimeException(
            "Duplicate normalized alias dengan target berbeda: "
            . $normalized
        );
    }

    $aliases[$normalized] = [
        'source_name' =>
            $sourceName,

        'normalized_name' =>
            $normalized,

        'country_code' =>
            $countryCode,

        'country_name_en' =>
            $countryByCode[
                $countryCode
            ]->country_name_en,

        'country_name_id' =>
            $countryByCode[
                $countryCode
            ]->country_name_id,

        'entity_class' =>
            $entityClass,
    ];

    $expansionAdded++;
}

fclose($handle);

/*
|--------------------------------------------------------------------------
| Sort by normalized source name
|--------------------------------------------------------------------------
*/

ksort($aliases);

/*
|--------------------------------------------------------------------------
| Final validation
|--------------------------------------------------------------------------
*/

$duplicateNormalized = [];

foreach ($aliases as $normalized => $alias) {

    if (
        isset(
            $duplicateNormalized[
                $normalized
            ]
        )
    ) {
        $duplicateNormalized[
            $normalized
        ]++;
    } else {
        $duplicateNormalized[
            $normalized
        ] = 1;
    }
}

$duplicates = array_filter(
    $duplicateNormalized,
    fn (int $count): bool =>
        $count > 1
);

if (!empty($duplicates)) {
    throw new RuntimeException(
        'Duplicate normalized aliases ditemukan.'
    );
}

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
        'source_name',
        'normalized_name',
        'country_code',
        'country_name_en',
        'country_name_id',
        'entity_class',
    ]
);

foreach ($aliases as $alias) {

    fputcsv(
        $output,
        [
            $alias['source_name'],
            $alias['normalized_name'],
            $alias['country_code'],
            $alias['country_name_en'],
            $alias['country_name_id'],
            $alias['entity_class'],
        ]
    );
}

fclose($output);

/*
|--------------------------------------------------------------------------
| Summary
|--------------------------------------------------------------------------
*/

$existingCount = count(
    $curatedV1
);

$totalAliases =
    count($aliases);

echo "========================================\n";
echo "DIGESTEX TRADE COUNTRY ALIAS V2 BUILDER\n";
echo "========================================\n\n";

echo "CURRENT MASTER COUNTRIES : "
    . $countryRows->count()
    . PHP_EOL;

echo "V1 CURATED ALIASES       : "
    . $existingCount
    . PHP_EOL;

echo "EXPANSION ALIASES ADDED  : "
    . $expansionAdded
    . PHP_EOL;

echo "EXPANSION ROWS SKIPPED   : "
    . $expansionSkipped
    . PHP_EOL;

echo "TOTAL ALIASES V2         : "
    . $totalAliases
    . PHP_EOL;

echo "\nOUTPUT:\n";
echo $outputFile . PHP_EOL;

echo "\n========================================\n";
echo "DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";