<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

const TARGET_YEAR = 2020;
const BATCH_ID = 83;

echo "========================================\n";
echo "DIGESTEX POST-INGEST AUDIT 2020\n";
echo "========================================\n\n";

/*
|--------------------------------------------------------------------------
| Independent queries
|--------------------------------------------------------------------------
*/

$total =
    DB::table('trade_statistics')
        ->where('year', TARGET_YEAR)
        ->count();

$batchTotal =
    DB::table('trade_statistics')
        ->where('year', TARGET_YEAR)
        ->where('import_batch_id', BATCH_ID)
        ->count();

$distinctIdentities =
    DB::table('trade_statistics')
        ->where('year', TARGET_YEAR)
        ->distinct('trade_identity')
        ->count('trade_identity');

$nullIdentity =
    DB::table('trade_statistics')
        ->where('year', TARGET_YEAR)
        ->whereNull('trade_identity')
        ->count();

$nullHs =
    DB::table('trade_statistics')
        ->where('year', TARGET_YEAR)
        ->whereNull('hs_id')
        ->count();

$nullCountry =
    DB::table('trade_statistics')
        ->where('year', TARGET_YEAR)
        ->whereNull('country_id')
        ->count();

$nullProvince =
    DB::table('trade_statistics')
        ->where('year', TARGET_YEAR)
        ->whereNull('province_id')
        ->count();

$nullTradePoint =
    DB::table('trade_statistics')
        ->where('year', TARGET_YEAR)
        ->whereNull('trade_point_id')
        ->count();

/*
|--------------------------------------------------------------------------
| Duplicate identity groups
|--------------------------------------------------------------------------
*/

$duplicateIdentityGroups =
    DB::table('trade_statistics')
        ->where('year', TARGET_YEAR)
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
| Aggregates
|--------------------------------------------------------------------------
*/

$aggregates =
    DB::table('trade_statistics')
        ->where('year', TARGET_YEAR)
        ->selectRaw(
            'SUM(trade_value) AS trade_value,
             SUM(trade_volume) AS trade_volume'
        )
        ->first();

/*
|--------------------------------------------------------------------------
| Batch
|--------------------------------------------------------------------------
*/

$batch =
    DB::table('trade_import_batches')
        ->where('id', BATCH_ID)
        ->first();

/*
|--------------------------------------------------------------------------
| Expected values from dry-run / production baseline
|--------------------------------------------------------------------------
*/

$checks = [

    'TOTAL_RECORDS' =>
        $total === 148177,

    'BATCH_RECORDS' =>
        $batchTotal === 148177,

    'DISTINCT_IDENTITIES' =>
        $distinctIdentities === 148177,

    'IDENTITY_NULL' =>
        $nullIdentity === 0,

    'HS_NULL' =>
        $nullHs === 0,

    /*
    |--------------------------------------------------------------------------
    | Province must be fully resolved, as established in 2020 dry-run.
    |--------------------------------------------------------------------------
    */

    'PROVINCE_NULL' =>
        $nullProvince === 1,

    'DUPLICATE_IDENTITIES' =>
        $duplicateIdentityGroups === 0,

    'BATCH_COMPLETED' =>
        $batch !== null
        &&
        $batch->status === 'completed'
        &&
        (int) $batch->year === TARGET_YEAR,

    'BATCH_TOTAL_ROWS' =>
        $batch !== null
        &&
        (int) $batch->total_rows === 148177,

    'BATCH_INSERTED_ROWS' =>
        $batch !== null
        &&
        (int) $batch->inserted_rows === 148177,

    'BATCH_FAILED_ROWS' =>
        $batch !== null
        &&
        (int) $batch->failed_rows === 0,
];

/*
|--------------------------------------------------------------------------
| Output
|--------------------------------------------------------------------------
*/

echo "DATABASE:\n";

echo "  2020 RECORDS          : "
    . $total
    . PHP_EOL;

echo "  BATCH 83 RECORDS      : "
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
echo "AGGREGATES:\n";

echo "  TRADE VALUE           : "
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
echo "BATCH:\n";

if ($batch !== null) {

    echo "  ID                    : "
        . $batch->id
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

    echo "  FAILED ROWS           : "
        . $batch->failed_rows
        . PHP_EOL;

} else {

    echo "  BATCH 83 NOT FOUND\n";
}

/*
|--------------------------------------------------------------------------
| Final gate
|--------------------------------------------------------------------------
*/

$failedChecks =
    array_keys(
        array_filter(
            $checks,
            static fn (bool $value): bool =>
                !$value
        )
    );

echo PHP_EOL;
echo "========================================\n";

if (empty($failedChecks)) {

    echo "FINAL POST-INGEST GATE : PASS\n";

} else {

    echo "FINAL POST-INGEST GATE : REVIEW\n";

    echo "\nFAILED CHECKS:\n";

    foreach ($failedChecks as $check) {
        echo "  - {$check}\n";
    }
}

echo "========================================\n";