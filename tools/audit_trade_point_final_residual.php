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
| DIGESTEX TRADE POINT FINAL RESIDUAL AUDIT
|--------------------------------------------------------------------------
|
| INPUT:
|   dry_run_export_2019.csv
|
| TARGET RESIDUALS:
|   BUATAN
|   JAYAPURA
|   NUNUKAN
|   NONGSA
|   PONTIANAK
|   LOBAM
|   BALIKPAPAN
|   PEKAN BARU
|   SEMARANG (PTT)
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
    . 'trade_point_final_residual_review_2019.csv';

if (!is_file($inputFile)) {
    throw new RuntimeException(
        "Input file tidak ditemukan:\n{$inputFile}"
    );
}

/*
|--------------------------------------------------------------------------
| Target names
|--------------------------------------------------------------------------
*/

$targets = [
    'BUATAN',
    'JAYAPURA',
    'NUNUKAN',
    'NONGSA',
    'PONTIANAK',
    'LOBAM',
    'BALIKPAPAN',
    'PEKAN BARU',
    'SEMARANG (PTT)',
];

/*
|--------------------------------------------------------------------------
| Normalize
|--------------------------------------------------------------------------
*/

function normalizeTradePointResidual(
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

$targetLookup = [];

foreach ($targets as $target) {
    $targetLookup[
        normalizeTradePointResidual($target)
    ] = $target;
}

/*
|--------------------------------------------------------------------------
| Load canonical master
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

$typeLookup = DB::table('trade_point_types')
    ->where('is_active', true)
    ->pluck('code', 'id')
    ->toArray();

$provinceLookup = DB::table('provinces')
    ->where('is_active', true)
    ->get([
        'id',
        'code',
        'name',
        'name_en',
    ]);

/*
|--------------------------------------------------------------------------
| Current alias lookup
|--------------------------------------------------------------------------
*/

$aliases = DB::table('trade_point_aliases')
    ->where('source_system', 'KEMENDAG')
    ->where('is_active', true)
    ->get([
        'normalized_name',
        'trade_point_id',
    ]);

$aliasLookup = [];

foreach ($aliases as $alias) {

    $normalized =
        normalizeTradePointResidual(
            (string) $alias->normalized_name
        );

    if ($normalized === '') {
        continue;
    }

    $aliasLookup[$normalized] =
        (int) $alias->trade_point_id;
}

/*
|--------------------------------------------------------------------------
| Read dry-run output
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
    'COUNTRY_STATUS',
    'PROVINCE_SOURCE',
    'PROVINCE_STATUS',
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
| Aggregate residual context
|--------------------------------------------------------------------------
*/

$stats = [];

while (($row = fgetcsv($handle)) !== false) {

    $tradePointStatus =
        trim(
            (string) $row[
                $columns['TRADE_POINT_STATUS']
            ]
        );

    if ($tradePointStatus !== 'UNRESOLVED') {
        continue;
    }

    $source =
        trim(
            (string) $row[
                $columns['TRADE_POINT_SOURCE']
            ]
        );

    $normalized =
        normalizeTradePointResidual(
            $source
        );

    if (!isset($targetLookup[$normalized])) {
        continue;
    }

    if (!isset($stats[$normalized])) {

        $stats[$normalized] = [
            'source' =>
                $targetLookup[$normalized],

            'monthly_occurrence_count' =>
                0,

            'countries' =>
                [],

            'provinces' =>
                [],

            'export_seen' =>
                false,

            'import_seen' =>
                false,

            'country_resolved_seen' =>
                false,

            'province_resolved_seen' =>
                false,
        ];
    }

    $stats[$normalized][
        'monthly_occurrence_count'
    ]++;

    $country =
        trim(
            (string) $row[
                $columns['COUNTRY_SOURCE']
            ]
        );

    if ($country !== '') {

        $countryKey =
            normalizeTradePointResidual(
                $country
            );

        $stats[$normalized][
            'countries'
        ][$countryKey] =
            $country;
    }

    $province =
        trim(
            (string) $row[
                $columns['PROVINCE_SOURCE']
            ]
        );

    if ($province !== '') {

        $provinceKey =
            normalizeTradePointResidual(
                $province
            );

        $stats[$normalized][
            'provinces'
        ][$provinceKey] =
            $province;
    }

    $countryStatus =
        trim(
            (string) $row[
                $columns['COUNTRY_STATUS']
            ]
        );

    if ($countryStatus === 'RESOLVED') {
        $stats[$normalized][
            'country_resolved_seen'
        ] = true;
    }

    $provinceStatus =
        trim(
            (string) $row[
                $columns['PROVINCE_STATUS']
            ]
        );

    if ($provinceStatus === 'RESOLVED') {
        $stats[$normalized][
            'province_resolved_seen'
        ] = true;
    }

    $flow =
        strtoupper(
            trim(
                (string) $row[
                    $columns['TRADE_FLOW']
                ]
            )
        );

    if ($flow === 'EXPORT') {
        $stats[$normalized]['export_seen'] = true;
    }

    if ($flow === 'IMPORT') {
        $stats[$normalized]['import_seen'] = true;
    }
}

fclose($handle);

/*
|--------------------------------------------------------------------------
| Build master lookup by ID
|--------------------------------------------------------------------------
*/

$tradePointById =
    $tradePoints->keyBy('id');

/*
|--------------------------------------------------------------------------
| Analyze each residual
|--------------------------------------------------------------------------
*/

$results = [];

foreach ($stats as $normalized => $stat) {

    $aliasTargetId =
        $aliasLookup[$normalized]
        ?? null;

    $aliasTarget =
        $aliasTargetId !== null
            ? ($tradePointById[$aliasTargetId] ?? null)
            : null;

    /*
    |--------------------------------------------------------------------------
    | Candidate canonical suggestions
    |--------------------------------------------------------------------------
    */

    $candidateClass = 'UNKNOWN';
    $candidateType = null;
    $candidateCanonical = null;
    $candidateCode = null;
    $candidateReason = '';

    switch ($normalized) {

        case 'BUATAN':

            $candidateClass =
                'POTENTIAL_INLAND_TRADE_POINT';

            $candidateReason =
                'Source name may refer to Buatan/Perawang area logistics or port facility; exact physical trade point not established.';

            break;

        case 'JAYAPURA':

            $candidateClass =
                'POTENTIAL_SEA_PORT_OR_BORDER';

            $candidateReason =
                'Jayapura has multiple potential international trade facilities; source name alone is insufficient.';

            break;

        case 'NUNUKAN':

            $candidateClass =
                'POTENTIAL_BORDER_OR_SEA_PORT';

            $candidateReason =
                'Nunukan is a border-trade geography and may also refer to a port facility; physical point must be distinguished.';

            break;

        case 'NONGSA':

            $candidateClass =
                'POTENTIAL_SEA_PORT';

            $candidateReason =
                'Nongsa is a trade/logistics area in Batam; source name alone does not identify the exact physical point.';

            break;

        case 'PONTIANAK':

            $candidateClass =
                'POTENTIAL_SEA_PORT';

            $candidateReason =
                'Pontianak is likely associated with a port facility, but canonical facility must be confirmed.';

            break;

        case 'LOBAM':

            $candidateClass =
                'POTENTIAL_SEA_PORT';

            $candidateReason =
                'Lobam is a logistics/industrial area; exact physical trade point requires confirmation.';

            break;

        case 'BALIKPAPAN':

            $candidateClass =
                'POTENTIAL_SEA_PORT_OR_AIRPORT';

            $candidateReason =
                'Balikpapan can refer to multiple trade facilities; source name alone is ambiguous.';

            break;

        case 'PEKAN BARU':

            $candidateClass =
                'POTENTIAL_INLAND_TRADE_POINT';

            $candidateReason =
                'Pekan Baru is a city-level source name and does not identify a physical trade point.';

            break;

        case 'SEMARANG (PTT)':

            $candidateClass =
                'POTENTIAL_DRY_PORT_OR_TERMINAL';

            $candidateReason =
                'PTT indicates a specific trade point/terminal, but canonical facility is not yet established.';

            break;
    }

    /*
    |--------------------------------------------------------------------------
    | Existing alias should remain NONE for this residual set.
    |--------------------------------------------------------------------------
    */

    $reviewStatus =
        $aliasTarget !== null
            ? 'ALIAS_ALREADY_EXISTS'
            : 'FINAL_MANUAL_REVIEW';

    /*
    |--------------------------------------------------------------------------
    | Confidence
    |--------------------------------------------------------------------------
    */

    $confidence = 'LOW';

    if (
        in_array(
            $normalized,
            [
                'NONGSA',
                'PONTIANAK',
                'LOBAM',
            ],
            true
        )
    ) {
        $confidence = 'MEDIUM';
    }

    $results[] = [
        'trade_point_source' =>
            $stat['source'],

        'monthly_occurrence_count' =>
            $stat[
                'monthly_occurrence_count'
            ],

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

        'country_resolved_seen' =>
            $stat['country_resolved_seen']
                ? 'YES'
                : 'NO',

        'province_resolved_seen' =>
            $stat['province_resolved_seen']
                ? 'YES'
                : 'NO',

        'existing_alias_target' =>
            $aliasTarget?->name,

        'existing_alias_code' =>
            $aliasTarget?->code,

        'candidate_class' =>
            $candidateClass,

        'candidate_canonical_name' =>
            $candidateCanonical,

        'candidate_code' =>
            $candidateCode,

        'candidate_type_code' =>
            $candidateType,

        'confidence' =>
            $confidence,

        'review_status' =>
            $reviewStatus,

        'reason' =>
            $candidateReason,
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
            $b['monthly_occurrence_count']
            <=>
            $a['monthly_occurrence_count'];
    }
);

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
        'trade_point_source',
        'monthly_occurrence_count',
        'source_provinces',
        'source_countries',
        'export_seen',
        'import_seen',
        'country_resolved_seen',
        'province_resolved_seen',
        'existing_alias_target',
        'existing_alias_code',
        'candidate_class',
        'candidate_canonical_name',
        'candidate_code',
        'candidate_type_code',
        'confidence',
        'review_status',
        'reason',
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
echo "DIGESTEX TRADE POINT FINAL RESIDUAL AUDIT\n";
echo "========================================\n\n";

echo "RESIDUAL SOURCE NAMES   : "
    . count($results)
    . PHP_EOL;

echo "CANONICAL MASTER        : "
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