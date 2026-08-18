<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

/*
|--------------------------------------------------------------------------
| DIGESTEX TRADE POINT RESIDUAL DECISION REGISTER
|--------------------------------------------------------------------------
|
| INPUT:
|   trade_point_final_residual_review_2019.csv
|
| OUTPUT:
|   trade_point_residual_decision_register_2019.csv
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
    . 'trade_point_final_residual_review_2019.csv';

$outputFile =
    $base
    . DIRECTORY_SEPARATOR
    . 'PROCESSED'
    . DIRECTORY_SEPARATOR
    . 'trade_point_residual_decision_register_2019.csv';

if (!is_file($inputFile)) {
    throw new RuntimeException(
        "Input file tidak ditemukan:\n{$inputFile}"
    );
}

/*
|--------------------------------------------------------------------------
| Final decision policy
|--------------------------------------------------------------------------
|
| Current policy:
|   All 9 residuals remain unresolved.
|
| Reason:
|   Source names do not provide sufficient evidence to identify
|   one exact physical canonical trade point.
|--------------------------------------------------------------------------
*/

$decisionPolicy = [

    'BUATAN' => [
        'decision' => 'KEEP_UNRESOLVED',
        'decision_class' => 'INLAND_AMBIGUOUS',
        'reason' =>
            'Source name identifies a geographic/logistics area but not one exact physical trade point.',
    ],

    'JAYAPURA' => [
        'decision' => 'KEEP_UNRESOLVED',
        'decision_class' => 'PORT_OR_BORDER_AMBIGUOUS',
        'reason' =>
            'Source name may refer to a sea port or border-related trade facility; exact physical point is not established.',
    ],

    'NUNUKAN' => [
        'decision' => 'KEEP_UNRESOLVED',
        'decision_class' => 'PORT_OR_BORDER_AMBIGUOUS',
        'reason' =>
            'Source name may refer to port or border trade facility; exact physical point is not established.',
    ],

    'NONGSA' => [
        'decision' => 'KEEP_UNRESOLVED',
        'decision_class' => 'SEA_PORT_AMBIGUOUS',
        'reason' =>
            'Source identifies a trade/logistics area but not a unique canonical physical facility.',
    ],

    'PONTIANAK' => [
        'decision' => 'KEEP_UNRESOLVED',
        'decision_class' => 'SEA_PORT_AMBIGUOUS',
        'reason' =>
            'City-level source name does not uniquely identify one physical trade point.',
    ],

    'LOBAM' => [
        'decision' => 'KEEP_UNRESOLVED',
        'decision_class' => 'SEA_PORT_AMBIGUOUS',
        'reason' =>
            'Source name refers to an area/facility location but exact canonical trade point is not established.',
    ],

    'BALIKPAPAN' => [
        'decision' => 'KEEP_UNRESOLVED',
        'decision_class' => 'PORT_OR_AIRPORT_AMBIGUOUS',
        'reason' =>
            'Source name can refer to more than one trade facility type.',
    ],

    'PEKAN BARU' => [
        'decision' => 'KEEP_UNRESOLVED',
        'decision_class' => 'INLAND_AMBIGUOUS',
        'reason' =>
            'City-level source name does not uniquely identify one physical trade point.',
    ],

    'SEMARANG (PTT)' => [
        'decision' => 'KEEP_UNRESOLVED',
        'decision_class' => 'TERMINAL_AMBIGUOUS',
        'reason' =>
            'PTT indicates a specific trade point/terminal, but the physical canonical facility is not established.',
    ],
];

/*
|--------------------------------------------------------------------------
| Read residual review
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
        'Header residual review tidak ditemukan.'
    );
}

$columns = [];

foreach ($header as $index => $name) {
    $columns[
        trim((string) $name)
    ] = $index;
}

foreach ([
    'trade_point_source',
    'monthly_occurrence_count',
    'source_provinces',
    'source_countries',
    'export_seen',
    'import_seen',
    'candidate_class',
    'confidence',
    'review_status',
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
| Build register
|--------------------------------------------------------------------------
*/

$results = [];

while (($row = fgetcsv($handle)) !== false) {

    $source = trim(
        (string) $row[
            $columns['trade_point_source']
        ]
    );

    if (!isset($decisionPolicy[$source])) {
        throw new RuntimeException(
            "Source residual tidak memiliki decision policy: "
            . $source
        );
    }

    $policy =
        $decisionPolicy[$source];

    $results[] = [
        'trade_point_source' =>
            $source,

        'monthly_occurrence_count' =>
            (int) (
                $row[
                    $columns[
                        'monthly_occurrence_count'
                    ]
                ]
            ),

        'source_provinces' =>
            $row[
                $columns['source_provinces']
            ],

        'source_countries' =>
            $row[
                $columns['source_countries']
            ],

        'export_seen' =>
            $row[
                $columns['export_seen']
            ],

        'import_seen' =>
            $row[
                $columns['import_seen']
            ],

        'candidate_class' =>
            $row[
                $columns['candidate_class']
            ],

        'candidate_confidence' =>
            $row[
                $columns['confidence']
            ],

        'previous_review_status' =>
            $row[
                $columns['review_status']
            ],

        'decision' =>
            $policy['decision'],

        'decision_class' =>
            $policy['decision_class'],

        'decision_reason' =>
            $policy['reason'],
    ];
}

fclose($handle);

/*
|--------------------------------------------------------------------------
| Sort by unresolved frequency
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
        'candidate_class',
        'candidate_confidence',
        'previous_review_status',
        'decision',
        'decision_class',
        'decision_reason',
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

$decisionCounts = [];

foreach ($results as $result) {

    $decision =
        $result['decision'];

    $decisionCounts[$decision] =
        ($decisionCounts[$decision] ?? 0)
        + 1;
}

echo "========================================\n";
echo "DIGESTEX TRADE POINT RESIDUAL DECISION REGISTER\n";
echo "========================================\n\n";

echo "RESIDUAL SOURCES        : "
    . count($results)
    . PHP_EOL;

foreach ($decisionCounts as $decision => $count) {
    echo sprintf(
        "%-24s : %d\n",
        $decision,
        $count
    );
}

echo "\nOUTPUT:\n";
echo $outputFile . PHP_EOL;

echo "\n========================================\n";
echo "DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";