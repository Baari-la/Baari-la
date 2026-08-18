<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use App\Services\Trade\KemendagTradeWorkbookHeaderParser;
use Illuminate\Support\Facades\DB;

const SOURCE_FILE =
    'C:\\Users\\user\\Desktop\\DIGESTEX_DATA\\KEMENDAG\\IMPORT\\impor 2026.xlsx';

echo "========================================\n";
echo "DIGESTEX IMPORT MONTHLY ENGINE TEST 2026\n";
echo "========================================\n\n";

echo "SOURCE:\n";
echo SOURCE_FILE . PHP_EOL;
echo PHP_EOL;

$parser =
    app(
        KemendagTradeWorkbookHeaderParser::class
    );

$mapping =
    $parser->parse(SOURCE_FILE);

echo "TRADE FLOW:\n";
echo "  "
    . $mapping['trade_flow']
    . PHP_EOL;

echo "VALUE PREFIX:\n";
echo "  "
    . $mapping['value_prefix']
    . PHP_EOL;

echo PHP_EOL;

echo "STATIC COLUMNS:\n";

foreach (
    $mapping['static'] as $name => $column
) {
    echo sprintf(
        "  %-12s : %d\n",
        $name,
        $column
    );
}

echo PHP_EOL;

echo "DETECTED PERIODS:\n";

foreach (
    $mapping['periods'] as $period => $data
) {
    echo sprintf(
        "  %s -> VALUE column %d | VOLUME column %d\n",
        $period,
        $data['value_column'],
        $data['volume_column']
    );
}

echo PHP_EOL;

$passed = true;

if (
    $mapping['trade_flow'] !== 'import'
) {
    echo "FAIL: trade_flow bukan import.\n";
    $passed = false;
}

if (
    $mapping['value_prefix'] !== 'cif_'
) {
    echo "FAIL: value_prefix bukan cif_.\n";
    $passed = false;
}

$expectedPeriods = [
    '2026-01' => [6, 12],
    '2026-02' => [7, 13],
    '2026-03' => [8, 14],
    '2026-04' => [9, 15],
    '2026-05' => [10, 16],
    '2026-06' => [11, 17],
];

foreach (
    $expectedPeriods as $period => [$valueColumn, $volumeColumn]
) {
    if (
        !isset(
            $mapping['periods'][$period]
        )
    ) {
        echo "FAIL: period {$period} tidak ditemukan.\n";
        $passed = false;
        continue;
    }

    $actual =
        $mapping['periods'][$period];

    $ok =
        (int) $actual['value_column']
            === $valueColumn
        &&
        (int) $actual['volume_column']
            === $volumeColumn;

    echo sprintf(
        "  VALIDATE %-7s : %s\n",
        $period,
        $ok ? 'PASS' : 'FAIL'
    );

    if (!$ok) {
        $passed = false;
    }
}

$beforeTradeStats =
    DB::table('trade_statistics')
        ->count();

$beforeBatches =
    DB::table('trade_import_batches')
        ->count();

echo PHP_EOL;
echo "DATABASE BASELINE:\n";
echo "  trade_statistics     : "
    . $beforeTradeStats
    . PHP_EOL;

echo "  trade_import_batches : "
    . $beforeBatches
    . PHP_EOL;

echo PHP_EOL;
echo "========================================\n";

if ($passed) {
    echo "IMPORT ENGINE HEADER TEST : PASS\n";
} else {
    echo "IMPORT ENGINE HEADER TEST : REVIEW\n";
}

echo "DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";