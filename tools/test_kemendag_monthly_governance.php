<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use App\Services\Trade\KemendagMonthlyTradeIngestionService;
use Illuminate\Support\Facades\DB;


const SOURCE_FILE =
    'C:\\Users\\user\\Desktop\\DIGESTEX_DATA\\KEMENDAG\\EXPORT\\ekspor 2026.xlsx';

const TEST_PERIOD = '2026-03';

echo "========================================\n";
echo "DIGESTEX MONTHLY GOVERNANCE TEST\n";
echo "========================================\n\n";

echo "SOURCE:\n";
echo SOURCE_FILE . PHP_EOL;

echo "TEST PERIOD:\n";
echo TEST_PERIOD . PHP_EOL;
echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Find one existing production identity
|--------------------------------------------------------------------------
|
| We use an existing 2026 identity only as a test target.
| Nothing will be committed.
|
*/

$existing =
    DB::table('trade_statistics')
        ->where('year', 2026)
        ->where('month', 3)
        ->whereNotNull('trade_identity')
        ->select([
            'trade_identity',
            'year',
            'month',
            'hs_code',
            'country_name',
            'province_name',
            'port_name',
        ])
        ->first();

if ($existing === null) {
    throw new RuntimeException(
        'Tidak ditemukan existing 2026-03 trade identity untuk governance test.'
    );
}

echo "EXISTING IDENTITY:\n";
echo "  trade_identity : "
    . $existing->trade_identity
    . PHP_EOL;

echo "  year           : "
    . $existing->year
    . PHP_EOL;

echo "  month          : "
    . $existing->month
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Service
|--------------------------------------------------------------------------
*/

$service =
    app(
        KemendagMonthlyTradeIngestionService::class
    );

/*
|--------------------------------------------------------------------------
| Baseline database counts
|--------------------------------------------------------------------------
*/

$beforeTradeStats =
    DB::table('trade_statistics')
        ->count();

$beforeBatches =
    DB::table('trade_import_batches')
        ->count();

echo "DATABASE BASELINE:\n";
echo "  trade_statistics : "
    . $beforeTradeStats
    . PHP_EOL;

echo "  trade_import_batches : "
    . $beforeBatches
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| TEST 1
|--------------------------------------------------------------------------
| REGULAR RELEASE must reject existing identity.
|--------------------------------------------------------------------------
*/

echo "TEST 1: REGULAR RELEASE + EXISTING PERIOD\n";
echo "----------------------------------------\n";

$regularPassed = false;

try {

    DB::beginTransaction();

    /*
     * We intentionally use only 2026-03.
     *
     * Because this identity already exists,
     * the Regular Release guard must abort.
     */
    $service->ingest(
        SOURCE_FILE,
        2026,
        'regular',
        [TEST_PERIOD]
    );

    DB::rollBack();

    echo "FAIL: Regular Release tidak melakukan ABORT.\n";

} catch (\Throwable $e) {

    DB::rollBack();

    $message =
        $e->getMessage();

    echo "EXPECTED ABORT:\n";
    echo $message . PHP_EOL;

    if (
        str_contains(
            $message,
            'REGULAR RELEASE ABORT'
        )
    ) {
        $regularPassed = true;
        echo "RESULT: PASS\n";
    } else {
        echo "RESULT: REVIEW\n";
    }
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| TEST 2
|--------------------------------------------------------------------------
| REVISION must be allowed for existing period.
|--------------------------------------------------------------------------
|
| IMPORTANT:
|   The transaction is always rolled back.
|   Therefore no data or batch survives this test.
|--------------------------------------------------------------------------
*/

echo "TEST 2: REVISION + EXISTING PERIOD\n";
echo "----------------------------------------\n";

$revisionPassed = false;

try {

    DB::beginTransaction();

    $batch =
        $service->ingest(
            SOURCE_FILE,
            2026,
            'revision',
            [TEST_PERIOD]
        );

    echo "Revision batch created inside test transaction:\n";
    echo "  batch_id : "
        . $batch->id
        . PHP_EOL;

    echo "  status   : "
        . $batch->status
        . PHP_EOL;

    /*
     * Do NOT commit.
     */
    DB::rollBack();

    $revisionPassed = true;

    echo "RESULT: PASS\n";

} catch (\Throwable $e) {

    DB::rollBack();

    echo "FAIL:\n";
    echo $e::class . PHP_EOL;
    echo $e->getMessage() . PHP_EOL;
}

/*
|--------------------------------------------------------------------------
| Post-test database verification
|--------------------------------------------------------------------------
*/

$afterTradeStats =
    DB::table('trade_statistics')
        ->count();

$afterBatches =
    DB::table('trade_import_batches')
        ->count();

echo PHP_EOL;
echo "DATABASE POST-TEST CHECK:\n";

echo "  trade_statistics : "
    . $afterTradeStats
    . PHP_EOL;

echo "  trade_import_batches : "
    . $afterBatches
    . PHP_EOL;

echo PHP_EOL;

$dbUnchanged =
    $beforeTradeStats === $afterTradeStats
    &&
    $beforeBatches === $afterBatches;

echo "========================================\n";

if (
    $regularPassed
    &&
    $revisionPassed
    &&
    $dbUnchanged
) {
    echo "MONTHLY GOVERNANCE TEST : PASS\n";
} else {
    echo "MONTHLY GOVERNANCE TEST : REVIEW\n";
}

echo "DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";