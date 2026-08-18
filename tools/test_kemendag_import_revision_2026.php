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
    'C:\\Users\\user\\Desktop\\DIGESTEX_DATA\\KEMENDAG\\IMPORT\\impor 2026.xlsx';

const TARGET_YEAR = 2026;
const TEST_PERIOD = '2026-03';
const TRADE_FLOW = 'import';

echo "========================================\n";
echo "DIGESTEX IMPORT REVISION TEST 2026\n";
echo "========================================\n\n";

echo "SOURCE:\n";
echo SOURCE_FILE . PHP_EOL;

echo "TRADE FLOW:\n";
echo TRADE_FLOW . PHP_EOL;

echo "TEST PERIOD:\n";
echo TEST_PERIOD . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Current production baseline
|--------------------------------------------------------------------------
*/

$currentImport =
    DB::table('trade_statistics')
        ->where('year', TARGET_YEAR)
        ->where('trade_flow', TRADE_FLOW)
        ->count();

$currentImportBatch =
    DB::table('trade_import_batches')
        ->where('year', TARGET_YEAR)
        ->where('trade_flow', TRADE_FLOW)
        ->orderByDesc('id')
        ->first();

$beforeTradeStats =
    DB::table('trade_statistics')
        ->count();

$beforeBatches =
    DB::table('trade_import_batches')
        ->count();

echo "DATABASE BASELINE:\n";
echo "  IMPORT 2026 RECORDS : "
    . $currentImport
    . PHP_EOL;

echo "  CURRENT IMPORT BATCH : "
    . (
        $currentImportBatch?->id
        ?? 'NONE'
    )
    . PHP_EOL;

echo "  TOTAL TRADE STATS    : "
    . $beforeTradeStats
    . PHP_EOL;

echo "  TOTAL BATCHES        : "
    . $beforeBatches
    . PHP_EOL;

echo PHP_EOL;

if (
    $currentImportBatch === null
    ||
    (int) $currentImportBatch->id !== 96
) {
    throw new RuntimeException(
        'Current IMPORT 2026 batch bukan Batch 96. '
        . 'Test dihentikan demi keamanan.'
    );
}

if ($currentImport !== 70716) {
    throw new RuntimeException(
        'Current IMPORT 2026 record count bukan 70,716. '
        . 'Test dihentikan demi keamanan.'
    );
}

/*
|--------------------------------------------------------------------------
| Run revision inside rollback transaction
|--------------------------------------------------------------------------
*/

$revisionPassed = false;

echo "TEST: REVISION IMPORT 2026-03\n";
echo "----------------------------------------\n";

try {

    DB::beginTransaction();

    $service =
        app(
            KemendagMonthlyTradeIngestionService::class
        );

    $batch =
        $service->ingest(
            SOURCE_FILE,
            TARGET_YEAR,
            'revision',
            [TEST_PERIOD]
        );

    echo "REVISION BATCH CREATED INSIDE TEST TRANSACTION:\n";
    echo "  batch_id : "
        . $batch->id
        . PHP_EOL;

    echo "  year     : "
        . $batch->year
        . PHP_EOL;

    echo "  flow     : "
        . $batch->trade_flow
        . PHP_EOL;

    echo "  status   : "
        . $batch->status
        . PHP_EOL;

    /*
     * The revision must be accepted.
     * We deliberately do NOT commit.
     */
    DB::rollBack();

    $revisionPassed = true;

    echo "RESULT: PASS\n";

} catch (\Throwable $e) {

    DB::rollBack();

    echo "RESULT: FAIL\n";
    echo "EXCEPTION : "
        . $e::class
        . PHP_EOL;
    echo "MESSAGE   : "
        . $e->getMessage()
        . PHP_EOL;
}

/*
|--------------------------------------------------------------------------
| Post-test verification
|--------------------------------------------------------------------------
*/

$afterTradeStats =
    DB::table('trade_statistics')
        ->count();

$afterBatches =
    DB::table('trade_import_batches')
        ->count();

$afterImport =
    DB::table('trade_statistics')
        ->where('year', TARGET_YEAR)
        ->where('trade_flow', TRADE_FLOW)
        ->count();

$afterImportBatch =
    DB::table('trade_import_batches')
        ->where('id', 96)
        ->first(
            [
                'id',
                'year',
                'trade_flow',
                'status',
                'total_rows',
                'inserted_rows',
                'updated_rows',
                'failed_rows',
            ]
        );

$databaseUnchanged =
    $beforeTradeStats === $afterTradeStats
    &&
    $beforeBatches === $afterBatches;

$currentImportPreserved =
    $afterImport === 70716;

$currentBatchPreserved =
    $afterImportBatch !== null
    &&
    (int) $afterImportBatch->id === 96
    &&
    $afterImportBatch->trade_flow === 'import'
    &&
    $afterImportBatch->status === 'completed';

echo PHP_EOL;
echo "DATABASE POST-TEST CHECK:\n";

echo "  TOTAL TRADE STATS    : "
    . $afterTradeStats
    . PHP_EOL;

echo "  TOTAL BATCHES        : "
    . $afterBatches
    . PHP_EOL;

echo "  IMPORT 2026 RECORDS  : "
    . $afterImport
    . PHP_EOL;

echo "  CURRENT BATCH 96     : "
    . (
        $afterImportBatch?->status
        ?? 'NOT FOUND'
    )
    . PHP_EOL;

echo PHP_EOL;
echo "========================================\n";

if (
    $revisionPassed
    &&
    $databaseUnchanged
    &&
    $currentImportPreserved
    &&
    $currentBatchPreserved
) {
    echo "IMPORT REVISION TEST : PASS\n";
} else {
    echo "IMPORT REVISION TEST : REVIEW\n";
}

echo "DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";