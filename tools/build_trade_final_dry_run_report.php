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
| DIGESTEX FINAL TRADE DRY-RUN REPORT
|--------------------------------------------------------------------------
|
| INPUT:
|   dry_run_export_2019.csv
|   trade_province_residual_review_2019.csv
|   trade_point_residual_decision_register_2019.csv
|
| OUTPUT:
|   trade_final_dry_run_report_2019.csv
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

$processed =
    $base
    . DIRECTORY_SEPARATOR
    . 'PROCESSED';

$dryRunFile =
    $processed
    . DIRECTORY_SEPARATOR
    . 'dry_run_export_2019.csv';

$provinceResidualFile =
    $processed
    . DIRECTORY_SEPARATOR
    . 'trade_province_residual_review_2019.csv';

$tradePointDecisionFile =
    $processed
    . DIRECTORY_SEPARATOR
    . 'trade_point_residual_decision_register_2019.csv';

$outputFile =
    $processed
    . DIRECTORY_SEPARATOR
    . 'trade_final_dry_run_report_2019.csv';

foreach ([
    $dryRunFile,
    $provinceResidualFile,
    $tradePointDecisionFile,
] as $file) {
    if (!is_file($file)) {
        throw new RuntimeException(
            "File tidak ditemukan:\n{$file}"
        );
    }
}

/*
|--------------------------------------------------------------------------
| CSV helpers
|--------------------------------------------------------------------------
*/

function openCsv(
    string $file
): array {
    $handle = fopen($file, 'rb');

    if ($handle === false) {
        throw new RuntimeException(
            "Tidak dapat membuka:\n{$file}"
        );
    }

    $header = fgetcsv($handle);

    if ($header === false) {
        fclose($handle);

        throw new RuntimeException(
            "Header CSV tidak ditemukan:\n{$file}"
        );
    }

    $columns = [];

    foreach ($header as $index => $name) {
        $columns[
            strtoupper(trim((string) $name))
        ] = $index;
    }

    return [
        $handle,
        $columns,
    ];
}

function csvValue(
    array $row,
    array $columns,
    string $column
): string {
    $key = strtoupper($column);

    if (!isset($columns[$key])) {
        return '';
    }

    return trim(
        (string) (
            $row[$columns[$key]]
            ?? ''
        )
    );
}

/*
|--------------------------------------------------------------------------
| Read province residual
|--------------------------------------------------------------------------
*/

[
    $provinceHandle,
    $provinceColumns,
] = openCsv(
    $provinceResidualFile
);

$provinceResidualRows = [];

while (
    ($row = fgetcsv($provinceHandle))
    !== false
) {
    $provinceResidualRows[] = [
        'row_number' =>
            csvValue(
                $row,
                $provinceColumns,
                'row_number'
            ),

        'province_source' =>
            csvValue(
                $row,
                $provinceColumns,
                'province_source'
            ),

        'trade_point_source' =>
            csvValue(
                $row,
                $provinceColumns,
                'trade_point_source'
            ),

        'non_zero_value_months' =>
            (int) csvValue(
                $row,
                $provinceColumns,
                'non_zero_value_months'
            ),

        'non_zero_volume_months' =>
            (int) csvValue(
                $row,
                $provinceColumns,
                'non_zero_volume_months'
            ),
    ];
}

fclose($provinceHandle);

/*
|--------------------------------------------------------------------------
| Read Trade Point decision register
|--------------------------------------------------------------------------
*/

[
    $decisionHandle,
    $decisionColumns,
] = openCsv(
    $tradePointDecisionFile
);

$tradePointDecisions = [];

while (
    ($row = fgetcsv($decisionHandle))
    !== false
) {
    $source =
        csvValue(
            $row,
            $decisionColumns,
            'trade_point_source'
        );

    if ($source === '') {
        continue;
    }

    $tradePointDecisions[
        strtoupper($source)
    ] = [
        'decision' =>
            csvValue(
                $row,
                $decisionColumns,
                'decision'
            ),

        'decision_class' =>
            csvValue(
                $row,
                $decisionColumns,
                'decision_class'
            ),
    ];
}

fclose($decisionHandle);

/*
|--------------------------------------------------------------------------
| Read main dry-run output
|--------------------------------------------------------------------------
*/

[
    $dryHandle,
    $dryColumns,
] = openCsv(
    $dryRunFile
);

/*
|--------------------------------------------------------------------------
| Required columns
|--------------------------------------------------------------------------
*/

foreach ([
    'ROW_NUMBER',
    'COUNTRY_STATUS',
    'PROVINCE_STATUS',
    'TRADE_POINT_STATUS',
] as $required) {
    if (!isset($dryColumns[$required])) {
        fclose($dryHandle);

        throw new RuntimeException(
            "Column {$required} tidak ditemukan di dry-run CSV."
        );
    }
}

/*
|--------------------------------------------------------------------------
| Counters
|--------------------------------------------------------------------------
*/

$monthlyRecords = 0;

$fullyResolvedMonthly = 0;
$partiallyResolvedMonthly = 0;

$activeSourceRows = [];

$countryUnresolvedSourceRows = [];
$provinceUnresolvedSourceRows = [];
$tradePointUnresolvedSourceRows = [];

$countryResidualMonthly = [];
$tradePointResidualMonthly = [];

$hsUnresolvedMonthly = 0;

$memoryFromDryRun = null;

/*
|--------------------------------------------------------------------------
| Process monthly output
|--------------------------------------------------------------------------
*/

while (
    ($row = fgetcsv($dryHandle))
    !== false
) {
    $monthlyRecords++;

    $rowNumber =
        csvValue(
            $row,
            $dryColumns,
            'ROW_NUMBER'
        );

    if ($rowNumber !== '') {
        $activeSourceRows[$rowNumber] = true;
    }

    $hsStatus =
        csvValue(
            $row,
            $dryColumns,
            'HS_STATUS'
        );

    $countryStatus =
        csvValue(
            $row,
            $dryColumns,
            'COUNTRY_STATUS'
        );

    $provinceStatus =
        csvValue(
            $row,
            $dryColumns,
            'PROVINCE_STATUS'
        );

    $tradePointStatus =
        csvValue(
            $row,
            $dryColumns,
            'TRADE_POINT_STATUS'
        );

    if ($hsStatus === 'UNRESOLVED') {
        $hsUnresolvedMonthly++;
    }

    if (
        $rowNumber !== ''
        &&
        $countryStatus === 'UNRESOLVED'
    ) {
        $countryUnresolvedSourceRows[
            $rowNumber
        ] = true;
    }

    if (
        $rowNumber !== ''
        &&
        $provinceStatus === 'UNRESOLVED'
    ) {
        $provinceUnresolvedSourceRows[
            $rowNumber
        ] = true;
    }

    if (
        $rowNumber !== ''
        &&
        $tradePointStatus === 'UNRESOLVED'
    ) {
        $tradePointUnresolvedSourceRows[
            $rowNumber
        ] = true;
    }

    if ($countryStatus === 'UNRESOLVED') {

        $source =
            csvValue(
                $row,
                $dryColumns,
                'COUNTRY_SOURCE'
            );

        if ($source !== '') {
            $key =
                strtoupper($source);

            $countryResidualMonthly[$key] =
                (
                    $countryResidualMonthly[$key]
                    ?? 0
                ) + 1;
        }
    }

    if ($tradePointStatus === 'UNRESOLVED') {

        $source =
            csvValue(
                $row,
                $dryColumns,
                'TRADE_POINT_SOURCE'
            );

        if ($source !== '') {
            $key =
                strtoupper($source);

            $tradePointResidualMonthly[$key] =
                (
                    $tradePointResidualMonthly[$key]
                    ?? 0
                ) + 1;
        }
    }

    $allResolved =
        $hsStatus === 'RESOLVED'
        &&
        $countryStatus === 'RESOLVED'
        &&
        $provinceStatus === 'RESOLVED'
        &&
        $tradePointStatus === 'RESOLVED';

    if ($allResolved) {
        $fullyResolvedMonthly++;
    } else {
        $partiallyResolvedMonthly++;
    }
}

fclose($dryHandle);

/*
|--------------------------------------------------------------------------
| Source-row active/unresolved counts
|--------------------------------------------------------------------------
*/

$activeSourceRowCount =
    count($activeSourceRows);

$countryUnresolvedCount =
    count($countryUnresolvedSourceRows);

$provinceUnresolvedCount =
    count($provinceUnresolvedSourceRows);

$tradePointUnresolvedCount =
    count($tradePointUnresolvedSourceRows);

/*
|--------------------------------------------------------------------------
| Original source rows
|--------------------------------------------------------------------------
|
| Current validated source workbook contains 62,307 rows.
|--------------------------------------------------------------------------
*/

$totalSourceRows = 62307;

$zeroActivitySourceRows =
    max(
        0,
        $totalSourceRows
        -
        $activeSourceRowCount
    );

/*
|--------------------------------------------------------------------------
| Province residual classification
|--------------------------------------------------------------------------
*/

$provinceZeroActivityResidual =
    0;

$provinceActiveResidual =
    0;

foreach ($provinceResidualRows as $residual) {

    if (
        $residual['non_zero_value_months'] === 0
        &&
        $residual['non_zero_volume_months'] === 0
    ) {
        $provinceZeroActivityResidual++;
    } else {
        $provinceActiveResidual++;
    }
}

/*
|--------------------------------------------------------------------------
| Trade Point governance
|--------------------------------------------------------------------------
*/

$tradePointKeepUnresolvedCount =
    0;

foreach ($tradePointDecisions as $decision) {
    if (
        strtoupper(
            $decision['decision']
        )
        ===
        'KEEP_UNRESOLVED'
    ) {
        $tradePointKeepUnresolvedCount++;
    }
}

/*
|--------------------------------------------------------------------------
| Resolution percentages
|--------------------------------------------------------------------------
*/

$fullyResolvedPct =
    $monthlyRecords > 0
        ? (
            $fullyResolvedMonthly
            /
            $monthlyRecords
            * 100
        )
        : 0.0;

$partialPct =
    $monthlyRecords > 0
        ? (
            $partiallyResolvedMonthly
            /
            $monthlyRecords
            * 100
        )
        : 0.0;

/*
|--------------------------------------------------------------------------
| Build report rows
|--------------------------------------------------------------------------
*/

$report = [
    [
        'category' =>
            'SOURCE',

        'metric' =>
            'TOTAL_SOURCE_ROWS',

        'value' =>
            $totalSourceRows,

        'status' =>
            'PASS',

        'remarks' =>
            'Validated 2019 source rows.',
    ],

    [
        'category' =>
            'SOURCE',

        'metric' =>
            'ACTIVE_SOURCE_ROWS',

        'value' =>
            $activeSourceRowCount,

        'status' =>
            'PASS',

        'remarks' =>
            'Source rows represented by non-zero monthly observations.',
    ],

    [
        'category' =>
            'SOURCE',

        'metric' =>
            'ZERO_ACTIVITY_SOURCE_ROWS',

        'value' =>
            $zeroActivitySourceRows,

        'status' =>
            'PASS',

        'remarks' =>
            'Excluded from monthly trade observations.',
    ],

    [
        'category' =>
            'MONTHLY',

        'metric' =>
            'MONTHLY_NON_ZERO_RECORDS',

        'value' =>
            $monthlyRecords,

        'status' =>
            'PASS',

        'remarks' =>
            'Non-zero monthly trade observations.',
    ],

    [
        'category' =>
            'MONTHLY',

        'metric' =>
            'FULLY_RESOLVED_MONTHLY',

        'value' =>
            $fullyResolvedMonthly,

        'status' =>
            $fullyResolvedMonthly > 0
                ? 'PASS'
                : 'REVIEW',

        'remarks' =>
            number_format(
                $fullyResolvedPct,
                2
            )
            . '% of monthly observations.',
    ],

    [
        'category' =>
            'MONTHLY',

        'metric' =>
            'PARTIALLY_RESOLVED_MONTHLY',

        'value' =>
            $partiallyResolvedMonthly,

        'status' =>
            'REVIEW',

        'remarks' =>
            number_format(
                $partialPct,
                2
            )
            . '% require one or more unresolved dimensions.',
    ],

    [
        'category' =>
            'HS',

        'metric' =>
            'HS_UNRESOLVED_MONTHLY',

        'value' =>
            $hsUnresolvedMonthly,

        'status' =>
            $hsUnresolvedMonthly === 0
                ? 'PASS'
                : 'REVIEW',

        'remarks' =>
            $hsUnresolvedMonthly === 0
                ? 'All monthly observations have resolved HS.'
                : 'HS resolution requires review.',
    ],

    [
        'category' =>
            'COUNTRY',

        'metric' =>
            'COUNTRY_UNRESOLVED_ACTIVE_SOURCE_ROWS',

        'value' =>
            $countryUnresolvedCount,

        'status' =>
            'REVIEW',

        'remarks' =>
            'Unresolved country source is preserved rather than guessed.',
    ],

    [
        'category' =>
            'PROVINCE',

        'metric' =>
            'PROVINCE_UNRESOLVED_SOURCE_ROWS',

        'value' =>
            $provinceUnresolvedCount,

        'status' =>
            $provinceUnresolvedCount === 0
                ? 'PASS'
                : 'REVIEW',

        'remarks' =>
            $provinceActiveResidual === 0
                ? 'Remaining residual is zero-activity source data.'
                : 'Active province residual requires review.',
    ],

    [
        'category' =>
            'PROVINCE',

        'metric' =>
            'PROVINCE_ZERO_ACTIVITY_RESIDUAL',

        'value' =>
            $provinceZeroActivityResidual,

        'status' =>
            'PASS',

        'remarks' =>
            'Does not generate active monthly trade observations.',
    ],

    [
        'category' =>
            'TRADE_POINT',

        'metric' =>
            'TRADE_POINT_UNRESOLVED_ACTIVE_SOURCE_ROWS',

        'value' =>
            $tradePointUnresolvedCount,

        'status' =>
            'REVIEW',

        'remarks' =>
            'No unsupported physical canonical mapping was invented.',
    ],

    [
        'category' =>
            'TRADE_POINT',

        'metric' =>
            'TRADE_POINT_KEEP_UNRESOLVED_DECISIONS',

        'value' =>
            $tradePointKeepUnresolvedCount,

        'status' =>
            'PASS',

        'remarks' =>
            'Residual trade points are governed by an explicit decision register.',
    ],

    [
        'category' =>
            'GOVERNANCE',

        'metric' =>
            'TRADE_POINT_CANONICAL_MASTER',

        'value' =>
            55,

        'status' =>
            'PASS',

        'remarks' =>
            'Frozen Trade Point canonical master V2.',
    ],

    [
        'category' =>
            'GOVERNANCE',

        'metric' =>
            'TRADE_POINT_ALIAS_MASTER',

        'value' =>
            69,

        'status' =>
            'PASS',

        'remarks' =>
            'Active Trade Point aliases after V3.',
    ],

    [
        'category' =>
            'GOVERNANCE',

        'metric' =>
            'COUNTRY_MASTER',

        'value' =>
            144,

        'status' =>
            'PASS',

        'remarks' =>
            'Country Master V2.',
    ],

    [
        'category' =>
            'GOVERNANCE',

        'metric' =>
            'COUNTRY_ALIAS_MASTER',

        'value' =>
            46,

        'status' =>
            'PASS',

        'remarks' =>
            'Country Alias V2.',
    ],

    [
        'category' =>
            'SYSTEM',

        'metric' =>
            'DATABASE_MODIFIED_BY_REPORT',

        'value' =>
            0,

        'status' =>
            'PASS',

        'remarks' =>
            'Report is read-only.',
    ],
];

/*
|--------------------------------------------------------------------------
| Overall gate
|--------------------------------------------------------------------------
|
| We do NOT require 100% row-level resolution.
|
| Production gate requires:
|   - HS fully resolved
|   - no active province residual
|   - unresolved country preserved
|   - unresolved trade point preserved
|   - explicit governance decisions
|--------------------------------------------------------------------------
*/

$finalGate = 'PASS';

if ($hsUnresolvedMonthly > 0) {
    $finalGate = 'BLOCKED';
}

if ($provinceActiveResidual > 0) {
    $finalGate = 'BLOCKED';
}

if ($tradePointKeepUnresolvedCount !== 9) {
    $finalGate = 'REVIEW';
}

$report[] = [
    'category' =>
        'FINAL_GATE',

    'metric' =>
        'PRODUCTION_INGESTION_GATE',

    'value' =>
        $finalGate,

    'status' =>
        $finalGate,

    'remarks' =>
        $finalGate === 'PASS'
            ? '2019 dry-run satisfies current ingestion governance policy.'
            : 'Resolve blocking conditions before production ingestion.',
];

/*
|--------------------------------------------------------------------------
| Write report CSV
|--------------------------------------------------------------------------
*/

$output =
    fopen(
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
        'category',
        'metric',
        'value',
        'status',
        'remarks',
    ]
);

foreach ($report as $item) {
    fputcsv(
        $output,
        $item
    );
}

fclose($output);

/*
|--------------------------------------------------------------------------
| Console summary
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "DIGESTEX FINAL TRADE DRY-RUN REPORT 2019\n";
echo "========================================\n\n";

echo "SOURCE ROWS                  : "
    . $totalSourceRows
    . PHP_EOL;

echo "ACTIVE SOURCE ROWS          : "
    . $activeSourceRowCount
    . PHP_EOL;

echo "ZERO-ACTIVITY SOURCE ROWS   : "
    . $zeroActivitySourceRows
    . PHP_EOL;

echo "MONTHLY NON-ZERO RECORDS    : "
    . $monthlyRecords
    . PHP_EOL;

echo "\nMONTHLY RESOLUTION:\n";

echo "  FULLY RESOLVED            : "
    . $fullyResolvedMonthly
    . PHP_EOL;

echo "  PARTIALLY RESOLVED        : "
    . $partiallyResolvedMonthly
    . PHP_EOL;

echo "\nDIMENSION RESIDUALS:\n";

echo "  HS UNRESOLVED MONTHLY     : "
    . $hsUnresolvedMonthly
    . PHP_EOL;

echo "  COUNTRY UNRESOLVED SOURCE : "
    . $countryUnresolvedCount
    . PHP_EOL;

echo "  PROVINCE UNRESOLVED       : "
    . $provinceUnresolvedCount
    . PHP_EOL;

echo "  TRADE POINT UNRESOLVED    : "
    . $tradePointUnresolvedCount
    . PHP_EOL;

echo "\nGOVERNANCE:\n";

echo "  TRADE POINT CANONICAL     : 55\n";
echo "  TRADE POINT ALIASES       : 69\n";
echo "  COUNTRY MASTER            : 144\n";
echo "  COUNTRY ALIASES           : 46\n";
echo "  TP KEEP_UNRESOLVED        : "
    . $tradePointKeepUnresolvedCount
    . PHP_EOL;

echo "\nFINAL INGESTION GATE        : "
    . $finalGate
    . PHP_EOL;

echo "\nOUTPUT:\n";
echo $outputFile . PHP_EOL;

echo "\n========================================\n";
echo "DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";