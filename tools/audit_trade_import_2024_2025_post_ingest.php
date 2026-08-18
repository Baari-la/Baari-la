<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "========================================\n";
echo "DIGESTEX TRADE IMPORT POST-INGEST AUDIT\n";
echo "2024 + 2025\n";
echo "========================================\n\n";

$audits = [
    [
        'year' => 2024,
        'batch_id' => 104,
        'expected_records' => 152352,
        'expected_trade_value' => 9038230735.000000,
        'expected_trade_volume' => 2202711827.000000,
        'expected_province_null' => 0,
    ],
    [
        'year' => 2025,
        'batch_id' => 105,
        'expected_records' => 144078,
        'expected_trade_value' => 9202576242.722800,
        'expected_trade_volume' => 2358242952.737200,
        'expected_province_null' => 0,
    ],
];

$overallPass = true;

foreach ($audits as $cfg) {

    $year = (int) $cfg['year'];
    $batchId = (int) $cfg['batch_id'];
    $expectedRecords = (int) $cfg['expected_records'];
    $expectedTradeValue = (float) $cfg['expected_trade_value'];
    $expectedTradeVolume = (float) $cfg['expected_trade_volume'];
    $expectedProvinceNull = (int) $cfg['expected_province_null'];

    echo "========================================\n";
    echo "POST-INGEST AUDIT : {$year} IMPORT\n";
    echo "========================================\n\n";

    /*
    |--------------------------------------------------------------------------
    | Independent database queries
    |--------------------------------------------------------------------------
    */

    $total = DB::table('trade_statistics')
        ->where('year', $year)
        ->where('trade_flow', 'import')
        ->count();

    $batchTotal = DB::table('trade_statistics')
        ->where('year', $year)
        ->where('trade_flow', 'import')
        ->where('import_batch_id', $batchId)
        ->count();

    $distinctIdentities = DB::table('trade_statistics')
        ->where('year', $year)
        ->where('trade_flow', 'import')
        ->distinct('trade_identity')
        ->count('trade_identity');

    $nullIdentity = DB::table('trade_statistics')
        ->where('year', $year)
        ->where('trade_flow', 'import')
        ->whereNull('trade_identity')
        ->count();

    $nullHs = DB::table('trade_statistics')
        ->where('year', $year)
        ->where('trade_flow', 'import')
        ->whereNull('hs_id')
        ->count();

    $nullCountry = DB::table('trade_statistics')
        ->where('year', $year)
        ->where('trade_flow', 'import')
        ->whereNull('country_id')
        ->count();

    $nullProvince = DB::table('trade_statistics')
        ->where('year', $year)
        ->where('trade_flow', 'import')
        ->whereNull('province_id')
        ->count();

    $nullTradePoint = DB::table('trade_statistics')
        ->where('year', $year)
        ->where('trade_flow', 'import')
        ->whereNull('trade_point_id')
        ->count();

    /*
    |--------------------------------------------------------------------------
    | Duplicate identity groups
    |--------------------------------------------------------------------------
    */

    $duplicateIdentityGroups = DB::table('trade_statistics')
        ->where('year', $year)
        ->where('trade_flow', 'import')
        ->select(
            'trade_identity',
            'year',
            'month'
        )
        ->groupBy(
            'trade_identity',
            'year',
            'month'
        )
        ->havingRaw('COUNT(*) > 1')
        ->count();

    /*
    |--------------------------------------------------------------------------
    | Aggregate values
    |--------------------------------------------------------------------------
    */

    $aggregates = DB::table('trade_statistics')
        ->where('year', $year)
        ->where('trade_flow', 'import')
        ->selectRaw(
            'SUM(trade_value) AS trade_value,
             SUM(trade_volume) AS trade_volume'
        )
        ->first();

    $actualTradeValue =
        (float) ($aggregates->trade_value ?? 0);

    $actualTradeVolume =
        (float) ($aggregates->trade_volume ?? 0);

    /*
    |--------------------------------------------------------------------------
    | Batch metadata
    |--------------------------------------------------------------------------
    */

    $batch = DB::table('trade_import_batches')
        ->where('id', $batchId)
        ->first();

    /*
    |--------------------------------------------------------------------------
    | Quality checks
    |--------------------------------------------------------------------------
    */

    $checks = [

        'TOTAL_RECORDS' =>
            $total === $expectedRecords,

        'BATCH_RECORDS' =>
            $batchTotal === $expectedRecords,

        'DISTINCT_IDENTITIES' =>
            $distinctIdentities === $expectedRecords,

        'IDENTITY_NULL' =>
            $nullIdentity === 0,

        'HS_NULL' =>
            $nullHs === 0,

        'COUNTRY_NULL' =>
            $nullCountry === 0,

        'PROVINCE_NULL' =>
            $nullProvince === $expectedProvinceNull,

        'DUPLICATE_IDENTITIES' =>
            $duplicateIdentityGroups === 0,

        'TRADE_VALUE' =>
            abs(
                $actualTradeValue
                -
                $expectedTradeValue
            ) < 0.000001,

        'TRADE_VOLUME' =>
            abs(
                $actualTradeVolume
                -
                $expectedTradeVolume
            ) < 0.000001,

        'BATCH_COMPLETED' =>
            $batch !== null
            &&
            $batch->status === 'completed'
            &&
            (int) $batch->year === $year
            &&
            strtoupper((string) ($batch->trade_flow ?? 'IMPORT'))
                === 'IMPORT',

        'BATCH_TOTAL_ROWS' =>
            $batch !== null
            &&
            (int) $batch->total_rows === $expectedRecords,

        'BATCH_INSERTED_ROWS' =>
            $batch !== null
            &&
            (int) $batch->inserted_rows === $expectedRecords,

        'BATCH_FAILED_ROWS' =>
            $batch !== null
            &&
            (int) $batch->failed_rows === 0,
    ];

    $failedChecks = array_keys(
        array_filter(
            $checks,
            static fn (bool $value): bool => !$value
        )
    );

    /*
    |--------------------------------------------------------------------------
    | Output
    |--------------------------------------------------------------------------
    */

    echo "DATABASE:\n";

    echo "  {$year} IMPORT RECORDS : "
        . $total
        . PHP_EOL;

    echo "  BATCH {$batchId} RECORDS      : "
        . $batchTotal
        . PHP_EOL;

    echo "  DISTINCT IDENTITIES      : "
        . $distinctIdentities
        . PHP_EOL;

    echo "  NULL IDENTITY            : "
        . $nullIdentity
        . PHP_EOL;

    echo "  NULL HS ID               : "
        . $nullHs
        . PHP_EOL;

    echo "  NULL COUNTRY ID          : "
        . $nullCountry
        . PHP_EOL;

    echo "  NULL PROVINCE ID         : "
        . $nullProvince
        . PHP_EOL;

    echo "  NULL TRADE POINT ID      : "
        . $nullTradePoint
        . PHP_EOL;

    echo "  DUPLICATE ID GROUPS      : "
        . $duplicateIdentityGroups
        . PHP_EOL;

    echo PHP_EOL;

    echo "AGGREGATES:\n";

    echo "  TRADE VALUE              : "
        . number_format(
            $actualTradeValue,
            6,
            '.',
            ''
        )
        . PHP_EOL;

    echo "  TRADE VOLUME             : "
        . number_format(
            $actualTradeVolume,
            6,
            '.',
            ''
        )
        . PHP_EOL;

    echo PHP_EOL;

    echo "EXPECTED:\n";

    echo "  EXPECTED RECORDS         : "
        . $expectedRecords
        . PHP_EOL;

    echo "  EXPECTED TRADE VALUE     : "
        . number_format(
            $expectedTradeValue,
            6,
            '.',
            ''
        )
        . PHP_EOL;

    echo "  EXPECTED TRADE VOLUME    : "
        . number_format(
            $expectedTradeVolume,
            6,
            '.',
            ''
        )
        . PHP_EOL;

    echo PHP_EOL;

    echo "BATCH:\n";

    if ($batch !== null) {

        echo "  ID                       : "
            . $batch->id
            . PHP_EOL;

        echo "  YEAR                     : "
            . $batch->year
            . PHP_EOL;

        echo "  FLOW                     : "
            . ($batch->trade_flow ?? 'IMPORT')
            . PHP_EOL;

        echo "  STATUS                   : "
            . $batch->status
            . PHP_EOL;

        echo "  TOTAL ROWS               : "
            . $batch->total_rows
            . PHP_EOL;

        echo "  INSERTED ROWS            : "
            . $batch->inserted_rows
            . PHP_EOL;

        echo "  FAILED ROWS              : "
            . $batch->failed_rows
            . PHP_EOL;

    } else {

        echo "  BATCH {$batchId} NOT FOUND\n";
    }

    echo PHP_EOL;

    if (empty($failedChecks)) {

        echo "FINAL POST-INGEST GATE : PASS\n";

    } else {

        $overallPass = false;

        echo "FINAL POST-INGEST GATE : REVIEW\n";

        echo "\nFAILED CHECKS:\n";

        foreach ($failedChecks as $check) {
            echo "  - {$check}\n";
        }
    }

    echo PHP_EOL;
}

/*
|--------------------------------------------------------------------------
| Overall gate
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "FINAL 2024 + 2025 IMPORT RECONCILIATION\n";
echo "========================================\n";

if ($overallPass) {

    echo "  2024 IMPORT : PASS\n";
    echo "  2025 IMPORT : PASS\n";
    echo "  OVERALL     : PASS\n";

} else {

    echo "  OVERALL     : REVIEW\n";
}

echo "  DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";

if (!$overallPass) {
    exit(1);
}