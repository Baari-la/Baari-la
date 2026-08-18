<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use App\Services\Trade\KemendagTradeWorkbookHeaderParser;


const IMPORT_BASE_PATH =
    'C:\\Users\\user\\Desktop\\DIGESTEX_DATA\\KEMENDAG\\IMPORT';

$years = [
    2019,
    2020,
    2021,
    2022,
    2023,
    2024,
    2025,
];

echo "========================================\n";
echo "DIGESTEX KEMENDAG HISTORICAL IMPORT HEADER TEST\n";
echo "========================================\n\n";

$parser =
    app(
        KemendagTradeWorkbookHeaderParser::class
    );

$allPassed = true;

foreach ($years as $year) {

    echo "----------------------------------------\n";
    echo "IMPORT {$year}\n";
    echo "----------------------------------------\n";

    $file =
        IMPORT_BASE_PATH
        . DIRECTORY_SEPARATOR
        . "impor {$year}.xlsx";

    echo "  FILE           : {$file}\n";

    /*
    |--------------------------------------------------------------------------
    | File check
    |--------------------------------------------------------------------------
    */

    if (!is_file($file)) {

        echo "  FILE EXISTS    : FAIL\n";
        echo "  RESULT         : FAIL\n\n";

        $allPassed = false;

        continue;
    }

    echo "  FILE EXISTS    : PASS\n";

    try {

        /*
         |--------------------------------------------------------------------------
         | Generic parser
         |--------------------------------------------------------------------------
         */

        $mapping =
            $parser->parse($file);

        /*
         |--------------------------------------------------------------------------
         | Trade flow
         |--------------------------------------------------------------------------
         */

        $tradeFlow =
            $mapping['trade_flow']
            ?? null;

        $valuePrefix =
            $mapping['value_prefix']
            ?? null;

        $periods =
            $mapping['periods']
            ?? [];

        /*
         |--------------------------------------------------------------------------
         | Basic validation
         |--------------------------------------------------------------------------
         */

        $flowPass =
            $tradeFlow === 'import';

        $prefixPass =
            $valuePrefix === 'cif_';

        $periodCount =
            count($periods);

        $periodKeys =
            array_keys($periods);

        $firstPeriod =
            $periodKeys[0]
            ?? null;

        $lastPeriod =
            $periodKeys[
                count($periodKeys) - 1
            ]
            ?? null;

        $periodCountPass =
            $periodCount === 12;

        /*
         |--------------------------------------------------------------------------
         | Expected periods
         |--------------------------------------------------------------------------
         */

        $expectedFirst =
            sprintf(
                '%d-01',
                $year
            );

        $expectedLast =
            sprintf(
                '%d-12',
                $year
            );

        $periodRangePass =
            $firstPeriod === $expectedFirst
            &&
            $lastPeriod === $expectedLast;

        /*
         |--------------------------------------------------------------------------
         | Period structure validation
         |--------------------------------------------------------------------------
         */

        $periodStructurePass = true;

        foreach ($periods as $period => $meta) {

            if (
                !isset($meta['year'])
                ||
                !isset($meta['month'])
                ||
                !isset($meta['value_column'])
                ||
                !isset($meta['volume_column'])
            ) {
                $periodStructurePass = false;
                break;
            }

            if (
                (int) $meta['year'] !== $year
            ) {
                $periodStructurePass = false;
                break;
            }

            $expectedMonth =
                (int) substr(
                    $period,
                    5,
                    2
                );

            if (
                (int) $meta['month']
                !==
                $expectedMonth
            ) {
                $periodStructurePass = false;
                break;
            }
        }

        /*
         |--------------------------------------------------------------------------
         | Output
         |--------------------------------------------------------------------------
         */

        echo "  Trade Flow     : "
            . (
                $tradeFlow
                ?? 'NULL'
            )
            . PHP_EOL;

        echo "  Value Prefix    : "
            . (
                $valuePrefix
                ?? 'NULL'
            )
            . PHP_EOL;

        echo "  Period Count    : "
            . $periodCount
            . PHP_EOL;

        echo "  First Period    : "
            . (
                $firstPeriod
                ?? 'NULL'
            )
            . PHP_EOL;

        echo "  Last Period     : "
            . (
                $lastPeriod
                ?? 'NULL'
            )
            . PHP_EOL;

        echo "  Flow Validation : "
            . (
                $flowPass
                    ? 'PASS'
                    : 'FAIL'
            )
            . PHP_EOL;

        echo "  Prefix Validation : "
            . (
                $prefixPass
                    ? 'PASS'
                    : 'FAIL'
            )
            . PHP_EOL;

        echo "  Period Validation : "
            . (
                $periodCountPass
                    ? 'PASS'
                    : 'FAIL'
            )
            . PHP_EOL;

        echo "  Range Validation : "
            . (
                $periodRangePass
                    ? 'PASS'
                    : 'FAIL'
            )
            . PHP_EOL;

        echo "  Structure Validation : "
            . (
                $periodStructurePass
                    ? 'PASS'
                    : 'FAIL'
            )
            . PHP_EOL;

        /*
         |--------------------------------------------------------------------------
         | Final year result
         |--------------------------------------------------------------------------
         */

        $yearPassed =
            $flowPass
            &&
            $prefixPass
            &&
            $periodCountPass
            &&
            $periodRangePass
            &&
            $periodStructurePass;

        echo "  RESULT          : "
            . (
                $yearPassed
                    ? 'PASS'
                    : 'FAIL'
            )
            . PHP_EOL;

        if (!$yearPassed) {
            $allPassed = false;
        }

    } catch (\Throwable $e) {

        echo "  PARSER ERROR    : "
            . $e::class
            . PHP_EOL;

        echo "  MESSAGE         : "
            . $e->getMessage()
            . PHP_EOL;

        echo "  RESULT          : FAIL\n";

        $allPassed = false;
    }

    echo PHP_EOL;
}

/*
|--------------------------------------------------------------------------
| Final result
|--------------------------------------------------------------------------
*/

echo "========================================\n";

if ($allPassed) {

    echo "HISTORICAL IMPORT HEADER TEST : PASS\n";

} else {

    echo "HISTORICAL IMPORT HEADER TEST : REVIEW\n";
}

echo "DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";