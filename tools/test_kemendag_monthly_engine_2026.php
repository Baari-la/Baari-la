<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use App\Services\Trade\KemendagWorkbookHeaderParser;

$sourceFile =
    'C:\\Users\\user\\Desktop\\DIGESTEX_DATA\\KEMENDAG\\EXPORT\\ekspor 2026.xlsx';

echo "========================================\n";
echo "DIGESTEX KEMENDAG MONTHLY ENGINE TEST\n";
echo "========================================\n\n";

echo "SOURCE:\n";
echo $sourceFile . PHP_EOL;
echo PHP_EOL;

$parser =
    app(
        KemendagWorkbookHeaderParser::class
    );

$mapping =
    $parser->parse($sourceFile);

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
        "  %s -> FOB column %d | VOLUME column %d\n",
        $period,
        $data['fob_column'],
        $data['volume_column']
    );
}

$periods =
    array_keys(
        $mapping['periods']
    );

echo PHP_EOL;
echo "VALIDATION:\n";

$expected = [
    '2026-01' => [6, 12],
    '2026-02' => [7, 13],
    '2026-03' => [8, 14],
    '2026-04' => [9, 15],
    '2026-05' => [10, 16],
    '2026-06' => [11, 17],
];

$passed = true;

foreach ($expected as $period => $columns) {

    if (
        !isset(
            $mapping['periods'][$period]
        )
    ) {
        echo "  FAIL: {$period} tidak ditemukan.\n";
        $passed = false;
        continue;
    }

    $actual =
        $mapping['periods'][$period];

    $ok =
        (int) $actual['fob_column']
            === $columns[0]
        &&
        (int) $actual['volume_column']
            === $columns[1];

    echo sprintf(
        "  %s : %s\n",
        $period,
        $ok ? 'PASS' : 'FAIL'
    );

    if (!$ok) {
        $passed = false;
    }
}

/*
|--------------------------------------------------------------------------
| Ensure no periods outside Jan-Jun are being accepted
|--------------------------------------------------------------------------
*/

$unexpected =
    array_diff(
        $periods,
        array_keys($expected)
    );

if (!empty($unexpected)) {

    echo "\n  FAIL: Unexpected periods:\n";

    foreach ($unexpected as $period) {
        echo "    - {$period}\n";
    }

    $passed = false;
}

/*
|--------------------------------------------------------------------------
| Database protection check
|--------------------------------------------------------------------------
*/

$dbCount =
    DB::table('trade_statistics')
        ->where('year', 2026)
        ->count();

echo PHP_EOL;
echo "DATABASE CHECK:\n";
echo "  Existing 2026 rows : "
    . $dbCount
    . PHP_EOL;

/*
|--------------------------------------------------------------------------
| Final result
|--------------------------------------------------------------------------
*/

echo PHP_EOL;
echo "========================================\n";

if ($passed) {
    echo "MONTHLY ENGINE HEADER TEST : PASS\n";
} else {
    echo "MONTHLY ENGINE HEADER TEST : FAIL\n";
}

echo "DATABASE WAS NOT MODIFIED.\n";

echo "========================================\n";