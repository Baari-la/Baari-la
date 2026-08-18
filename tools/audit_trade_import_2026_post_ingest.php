<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

const TARGET_YEAR = 2026;
const TRADE_FLOW = 'import';
const BATCH_ID = 96;

const EXPECTED_RECORDS = 70716;
const EXPECTED_MIN_MONTH = 1;
const EXPECTED_MAX_MONTH = 6;
const EXPECTED_MONTH_COUNT = 6;

$expectedTradeValue = 4751101967.567000;
$expectedTradeVolume = 1243216236.984100;

echo "========================================\n";
echo "DIGESTEX POST-INGEST AUDIT IMPORT 2026\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  TRADE FLOW            : IMPORT\n";
echo "  CURRENT MODEL         : MONTHLY ENGINE\n";
echo "  PERIOD                : JANUARY-JUNE 2026\n";
echo "  BATCH ID              : " . BATCH_ID . "\n\n";

/*
|--------------------------------------------------------------------------
| Database facts
|--------------------------------------------------------------------------
*/

$total =
    DB::table('trade_statistics')
        ->where('year', TARGET_YEAR)
        ->where('trade_flow', TRADE_FLOW)
        ->count();

$batchTotal =
    DB::table('trade_statistics')
        ->where('year', TARGET_YEAR)
        ->where('trade_flow', TRADE_FLOW)
        ->where('import_batch_id', BATCH_ID)
        ->count();

$distinctIdentities =
    DB::table('trade_statistics')
        ->where('year', TARGET_YEAR)
        ->where('trade_flow', TRADE_FLOW)
        ->distinct('trade_identity')
        ->count('trade_identity');

$nullIdentity =
    DB::table('trade_statistics')
        ->where('year', TARGET_YEAR)
        ->where('trade_flow', TRADE_FLOW)
        ->whereNull('trade_identity')
        ->count();

$nullHs =
    DB::table('trade_statistics')
        ->where('year', TARGET_YEAR)
        ->where('trade_flow', TRADE_FLOW)
        ->whereNull('hs_id')
        ->count();

$nullCountry =
    DB::table('trade_statistics')
        ->where('year', TARGET_YEAR)
        ->where('trade_flow', TRADE_FLOW)
        ->whereNull('country_id')
        ->count();

$nullProvince =
    DB::table('trade_statistics')
        ->where('year', TARGET_YEAR)
        ->where('trade_flow', TRADE_FLOW)
        ->whereNull('province_id')
        ->count();

$nullTradePoint =
    DB::table('trade_statistics')
        ->where('year', TARGET_YEAR)
        ->where('trade_flow', TRADE_FLOW)
        ->whereNull('trade_point_id')
        ->count();

/*
|--------------------------------------------------------------------------
| Period validation
|--------------------------------------------------------------------------
*/

$periods =
    DB::table('trade_statistics')
        ->where('year', TARGET_YEAR)
        ->where('trade_flow', TRADE_FLOW)
        ->selectRaw(
            'MIN(month) AS min_month,
             MAX(month) AS max_month,
             COUNT(DISTINCT month) AS month_count'
        )
        ->first();

$minMonth =
    (int) (
        $periods->min_month ?? 0
    );

$maxMonth =
    (int) (
        $periods->max_month ?? 0
    );

$monthCount =
    (int) (
        $periods->month_count ?? 0
    );

$monthsOutsideRange =
    DB::table('trade_statistics')
        ->where('year', TARGET_YEAR)
        ->where('trade_flow', TRADE_FLOW)
        ->where(
            function ($query) {
                $query
                    ->where(
                        'month',
                        '<',
                        EXPECTED_MIN_MONTH
                    )
                    ->orWhere(
                        'month',
                        '>',
                        EXPECTED_MAX_MONTH
                    );
            }
        )
        ->count();

/*
|--------------------------------------------------------------------------
| Duplicate identity groups
|--------------------------------------------------------------------------
*/

$duplicateIdentityGroups =
    DB::table('trade_statistics')
        ->where('year', TARGET_YEAR)
        ->where('trade_flow', TRADE_FLOW)
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
        ->havingRaw(
            'COUNT(*) > 1'
        )
        ->count();

/*
|--------------------------------------------------------------------------
| Aggregates
|--------------------------------------------------------------------------
*/

$aggregates =
    DB::table('trade_statistics')
        ->where('year', TARGET_YEAR)
        ->where('trade_flow', TRADE_FLOW)
        ->selectRaw(
            'SUM(trade_value) AS trade_value,
             SUM(trade_volume) AS trade_volume'
        )
        ->first();

/*
|--------------------------------------------------------------------------
| Production batch
|--------------------------------------------------------------------------
*/

$batch =
    DB::table('trade_import_batches')
        ->where('id', BATCH_ID)
        ->first();

/*
|--------------------------------------------------------------------------
| Export protection check
|--------------------------------------------------------------------------
|
| We explicitly verify that Export 2026 remains present.
|
*/

$exportCount =
    DB::table('trade_statistics')
        ->where('year', TARGET_YEAR)
        ->where('trade_flow', 'export')
        ->count();

/*
|--------------------------------------------------------------------------
| Quality checks
|--------------------------------------------------------------------------
*/

$checks = [

    'TRADE_FLOW_IMPORT' =>
        $total > 0,

    'TOTAL_RECORDS' =>
        $total === EXPECTED_RECORDS,

    'BATCH_RECORDS' =>
        $batchTotal === EXPECTED_RECORDS,

    'DISTINCT_IDENTITIES' =>
        $distinctIdentities === EXPECTED_RECORDS,

    'IDENTITY_NULL' =>
        $nullIdentity === 0,

    'HS_NULL' =>
        $nullHs === 0,

    'MIN_MONTH' =>
        $minMonth === EXPECTED_MIN_MONTH,

    'MAX_MONTH' =>
        $maxMonth === EXPECTED_MAX_MONTH,

    'MONTH_COUNT' =>
        $monthCount === EXPECTED_MONTH_COUNT,

    'NO_MONTH_OUTSIDE_RANGE' =>
        $monthsOutsideRange === 0,

    'DUPLICATE_IDENTITIES' =>
        $duplicateIdentityGroups === 0,

    'TRADE_VALUE' =>
        abs(
            (float) (
                $aggregates->trade_value ?? 0
            )
            -
            $expectedTradeValue
        ) < 0.000001,

    'TRADE_VOLUME' =>
        abs(
            (float) (
                $aggregates->trade_volume ?? 0
            )
            -
            $expectedTradeVolume
        ) < 0.000001,

    'BATCH_COMPLETED' =>
        $batch !== null
        &&
        $batch->status === 'completed'
        &&
        (int) $batch->year === TARGET_YEAR
        &&
        $batch->trade_flow === TRADE_FLOW,

    'BATCH_TOTAL_ROWS' =>
        $batch !== null
        &&
        (int) $batch->total_rows === EXPECTED_RECORDS,

    'BATCH_INSERTED_ROWS' =>
        $batch !== null
        &&
        (int) $batch->inserted_rows === EXPECTED_RECORDS,

    'BATCH_FAILED_ROWS' =>
        $batch !== null
        &&
        (int) $batch->failed_rows === 0,

    'EXPORT_DATA_PRESERVED' =>
        $exportCount === 82183,
];

/*
|--------------------------------------------------------------------------
| Output
|--------------------------------------------------------------------------
*/

echo "DATABASE:\n";

echo "  IMPORT 2026 RECORDS   : "
    . $total
    . PHP_EOL;

echo "  BATCH 96 RECORDS      : "
    . $batchTotal
    . PHP_EOL;

echo "  DISTINCT IDENTITIES   : "
    . $distinctIdentities
    . PHP_EOL;

echo "  NULL IDENTITY         : "
    . $nullIdentity
    . PHP_EOL;

echo "  NULL HS ID            : "
    . $nullHs
    . PHP_EOL;

echo "  NULL COUNTRY ID       : "
    . $nullCountry
    . PHP_EOL;

echo "  NULL PROVINCE ID      : "
    . $nullProvince
    . PHP_EOL;

echo "  NULL TRADE POINT ID   : "
    . $nullTradePoint
    . PHP_EOL;

echo "  DUPLICATE ID GROUPS   : "
    . $duplicateIdentityGroups
    . PHP_EOL;

echo PHP_EOL;
echo "PERIOD:\n";

echo "  MIN MONTH             : "
    . $minMonth
    . PHP_EOL;

echo "  MAX MONTH             : "
    . $maxMonth
    . PHP_EOL;

echo "  DISTINCT MONTHS       : "
    . $monthCount
    . PHP_EOL;

echo "  MONTHS OUTSIDE 1-6    : "
    . $monthsOutsideRange
    . PHP_EOL;

echo PHP_EOL;
echo "AGGREGATES:\n";

echo "  CIF / TRADE VALUE     : "
    . number_format(
        (float) (
            $aggregates->trade_value ?? 0
        ),
        6,
        '.',
        ''
    )
    . PHP_EOL;

echo "  TRADE VOLUME          : "
    . number_format(
        (float) (
            $aggregates->trade_volume ?? 0
        ),
        6,
        '.',
        ''
    )
    . PHP_EOL;

echo PHP_EOL;
echo "BATCH 96:\n";

if ($batch !== null) {

    echo "  ID                    : "
        . $batch->id
        . PHP_EOL;

    echo "  TRADE FLOW            : "
        . $batch->trade_flow
        . PHP_EOL;

    echo "  STATUS                : "
        . $batch->status
        . PHP_EOL;

    echo "  TOTAL ROWS            : "
        . $batch->total_rows
        . PHP_EOL;

    echo "  INSERTED ROWS         : "
        . $batch->inserted_rows
        . PHP_EOL;

    echo "  UPDATED ROWS          : "
        . $batch->updated_rows
        . PHP_EOL;

    echo "  FAILED ROWS           : "
        . $batch->failed_rows
        . PHP_EOL;

} else {

    echo "  BATCH 96 NOT FOUND\n";
}

echo PHP_EOL;
echo "EXPORT PROTECTION:\n";

echo "  EXPORT 2026 RECORDS   : "
    . $exportCount
    . PHP_EOL;

echo PHP_EOL;
echo "========================================\n";

$failedChecks =
    array_keys(
        array_filter(
            $checks,
            static fn (bool $value): bool =>
                !$value
        )
    );

if (empty($failedChecks)) {

    echo "FINAL IMPORT POST-INGEST GATE : PASS\n";

} else {

    echo "FINAL IMPORT POST-INGEST GATE : REVIEW\n";

    echo PHP_EOL;
    echo "FAILED CHECKS:\n";

    foreach (
        $failedChecks as $check
    ) {
        echo "  - {$check}\n";
    }
}

echo "========================================\n";