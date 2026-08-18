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
| DIGESTEX TRADE POINT RESIDUAL AUDIT
|--------------------------------------------------------------------------
|
| INPUT:
|   dry_run_export_2019.csv
|
| MASTER:
|   trade_points
|   trade_point_aliases
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
    . 'dry_run_export_2019.csv';

$outputFile =
    $base
    . DIRECTORY_SEPARATOR
    . 'PROCESSED'
    . DIRECTORY_SEPARATOR
    . 'trade_point_residual_review_2019.csv';

if (!is_file($inputFile)) {
    throw new RuntimeException(
        "Input file tidak ditemukan:\n{$inputFile}"
    );
}

/*
|--------------------------------------------------------------------------
| Normalizer
|--------------------------------------------------------------------------
*/

function normalizeTradePoint(
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
| Load trade point master
|--------------------------------------------------------------------------
*/

$tradePoints = DB::table('trade_points')
    ->where('is_active', true)
    ->get([
        'id',
        'code',
        'name',
        'name_en',
        'trade_point_type_id',
        'province_id',
        'city',
    ]);

if ($tradePoints->isEmpty()) {
    throw new RuntimeException(
        'trade_points kosong.'
    );
}

/*
|--------------------------------------------------------------------------
| Load trade point types
|--------------------------------------------------------------------------
*/

$tradePointTypes = DB::table('trade_point_types')
    ->get([
        'id',
        'code',
        'name',
        'name_en',
    ])
    ->keyBy('id');

/*
|--------------------------------------------------------------------------
| Build master name lookup
|--------------------------------------------------------------------------
*/

$masterByName = [];

foreach ($tradePoints as $tp) {

    foreach ([
        $tp->name,
        $tp->name_en,
    ] as $name) {

        $normalized =
            normalizeTradePoint(
                (string) $name
            );

        if ($normalized !== '') {
            $masterByName[
                $normalized
            ] = $tp;
        }
    }
}

/*
|--------------------------------------------------------------------------
| Existing aliases
|--------------------------------------------------------------------------
*/

$aliases = DB::table(
    'trade_point_aliases'
)
    ->where(
        'source_system',
        'KEMENDAG'
    )
    ->where(
        'is_active',
        true
    )
    ->get([
        'normalized_name',
        'trade_point_id',
    ]);

$aliasLookup = [];

foreach ($aliases as $alias) {

    $normalized =
        normalizeTradePoint(
            (string) $alias->normalized_name
        );

    if ($normalized !== '') {

        $aliasLookup[
            $normalized
        ] = (int) $alias->trade_point_id;
    }
}

/*
|--------------------------------------------------------------------------
| Read dry-run CSV
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

foreach ([
    'TRADE_POINT_SOURCE',
    'TRADE_POINT_STATUS',
    'COUNTRY_SOURCE',
    'PROVINCE_SOURCE',
    'TRADE_FLOW',
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
| Aggregate unresolved trade points
|--------------------------------------------------------------------------
*/

$stats = [];

while (($row = fgetcsv($handle)) !== false) {

    $status = trim(
        (string) $row[
            $columns[
                'TRADE_POINT_STATUS'
            ]
        ]
    );

    if ($status !== 'UNRESOLVED') {
        continue;
    }

    $source = trim(
        (string) $row[
            $columns[
                'TRADE_POINT_SOURCE'
            ]
        ]
    );

    $normalized =
        normalizeTradePoint(
            $source
        );

    if ($normalized === '') {
        continue;
    }

    if (!isset($stats[$normalized])) {

        $stats[$normalized] = [
            'source' =>
                $source,

            'occurrence_count' =>
                0,

            'provinces' =>
                [],

            'countries' =>
                [],

            'export_seen' =>
                false,

            'import_seen' =>
                false,
        ];
    }

    $stats[$normalized][
        'occurrence_count'
    ]++;

    $province =
        trim(
            (string) $row[
                $columns[
                    'PROVINCE_SOURCE'
                ]
            ]
        );

    if ($province !== '') {

        $provinceKey =
            normalizeTradePoint(
                $province
            );

        $stats[$normalized][
            'provinces'
        ][$provinceKey] =
            $province;
    }

    $country =
        trim(
            (string) $row[
                $columns[
                    'COUNTRY_SOURCE'
                ]
            ]
        );

    if ($country !== '') {

        $countryKey =
            normalizeTradePoint(
                $country
            );

        $stats[$normalized][
            'countries'
        ][$countryKey] =
            $country;
    }

    $flow =
        strtoupper(
            trim(
                (string) $row[
                    $columns[
                        'TRADE_FLOW'
                    ]
                ]
            )
        );

    if ($flow === 'EXPORT') {
        $stats[$normalized][
            'export_seen'
        ] = true;
    }

    if ($flow === 'IMPORT') {
        $stats[$normalized][
            'import_seen'
        ] = true;
    }
}

fclose($handle);

/*
|--------------------------------------------------------------------------
| Build review rows
|--------------------------------------------------------------------------
*/

$results = [];

foreach ($stats as $normalized => $stat) {

    /*
    |--------------------------------------------------------------------------
    | Exact existing alias?
    |--------------------------------------------------------------------------
    */

    $aliasTradePointId =
        $aliasLookup[
            $normalized
        ] ?? null;

    $aliasMatch = null;

    if ($aliasTradePointId !== null) {

        $aliasMatch =
            $tradePoints->first(
                fn ($tp): bool =>
                    (int) $tp->id
                    ===
                    $aliasTradePointId
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Exact canonical name?
    |--------------------------------------------------------------------------
    */

    $masterMatch =
        $masterByName[
            $normalized
        ] ?? null;

    /*
    |--------------------------------------------------------------------------
    | Candidate information
    |--------------------------------------------------------------------------
    */

    $candidateName = null;
    $candidateCode = null;
    $candidateType = null;

    if ($aliasMatch !== null) {

        $candidateName =
            $aliasMatch->name;

        $candidateCode =
            $aliasMatch->code;

        $candidateType =
            $tradePointTypes[
                $aliasMatch->trade_point_type_id
            ]->code
            ?? null;

        $reviewStatus =
            'ALIAS_ALREADY_MAPPED';

    } elseif ($masterMatch !== null) {

        $candidateName =
            $masterMatch->name;

        $candidateCode =
            $masterMatch->code;

        $candidateType =
            $tradePointTypes[
                $masterMatch->trade_point_type_id
            ]->code
            ?? null;

        $reviewStatus =
            'CANONICAL_NAME_MATCH';

    } else {

        $reviewStatus =
            'MANUAL_REVIEW';
    }

    /*
    |--------------------------------------------------------------------------
    | Candidate classification
    |--------------------------------------------------------------------------
    */

    $candidateClass =
        'UNKNOWN';

    $sourceUpper =
        $normalized;

    if (
        str_contains(
            $sourceUpper,
            'BORDER'
        )
        ||
        in_array(
            $sourceUpper,
            [
                'NUNUKAN',
                'JAYAPURA',
            ],
            true
        )
    ) {
        $candidateClass =
            'POTENTIAL_LAND_BORDER';
    }

    if (
        str_contains(
            $sourceUpper,
            'PORT'
        )
        ||
        in_array(
            $sourceUpper,
            [
                'PANJANG',
                'BUATAN',
                'TANJUNG BALAI ASAHAN',
                'TANJUNG PINANG',
                'NONGSA',
                'LOBAM',
                'TEMBILAHAN',
                'BALIKPAPAN',
                'AMAMAPARE',
                'PEKAN BARU',
                'TENAU',
                'KUALA ENOK',
            ],
            true
        )
    ) {
        $candidateClass =
            'POTENTIAL_SEA_PORT';
    }

    if (
        str_contains(
            $sourceUpper,
            'AIRPORT'
        )
        ||
        str_contains(
            $sourceUpper,
            'U)'
        )
        ||
        str_contains(
            $sourceUpper,
            'BANDARA'
        )
    ) {
        $candidateClass =
            'POTENTIAL_AIRPORT';
    }

    if (
        str_contains(
            $sourceUpper,
            'PTT'
        )
    ) {
        $candidateClass =
            'POTENTIAL_DRY_PORT';
    }

    /*
    |--------------------------------------------------------------------------
    | Output
    |--------------------------------------------------------------------------
    */

    $results[] = [
        'trade_point_source' =>
            $stat['source'],

        'normalized_name' =>
            $normalized,

        'occurrence_count' =>
            $stat['occurrence_count'],

        'source_provinces' =>
            implode(
                ' | ',
                array_values(
                    $stat['provinces']
                )
            ),

        'source_countries' =>
            implode(
                ' | ',
                array_values(
                    $stat['countries']
                )
            ),

        'export_seen' =>
            $stat['export_seen']
                ? 'YES'
                : 'NO',

        'import_seen' =>
            $stat['import_seen']
                ? 'YES'
                : 'NO',

        'existing_alias' =>
            $aliasMatch !== null
                ? 'YES'
                : 'NO',

        'candidate_canonical_name' =>
            $candidateName,

        'candidate_code' =>
            $candidateCode,

        'candidate_type_code' =>
            $candidateType,

        'candidate_class' =>
            $candidateClass,

        'review_status' =>
            $reviewStatus,
    ];
}

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
        'trade_point_source',
        'normalized_name',
        'occurrence_count',
        'source_provinces',
        'source_countries',
        'export_seen',
        'import_seen',
        'existing_alias',
        'candidate_canonical_name',
        'candidate_code',
        'candidate_type_code',
        'candidate_class',
        'review_status',
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
echo "DIGESTEX TRADE POINT RESIDUAL AUDIT\n";
echo "========================================\n\n";

echo "UNRESOLVED SOURCE NAMES : "
    . count($results)
    . PHP_EOL;

echo "TRADE POINT MASTER      : "
    . $tradePoints->count()
    . PHP_EOL;

echo "ACTIVE ALIASES          : "
    . $aliases->count()
    . PHP_EOL;

echo "\nOUTPUT:\n";
echo $outputFile . PHP_EOL;

echo "\n========================================\n";
echo "DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";