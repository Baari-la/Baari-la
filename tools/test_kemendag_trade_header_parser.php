<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use App\Services\Trade\KemendagTradeWorkbookHeaderParser;

$files = [
    'EXPORT 2025' =>
        'C:\Users\user\Desktop\DIGESTEX_DATA\KEMENDAG\EXPORT\ekspor 2025.xlsx',

    'EXPORT 2026' =>
        'C:\Users\user\Desktop\DIGESTEX_DATA\KEMENDAG\EXPORT\ekspor 2026.xlsx',

    'IMPORT 2025' =>
        'C:\Users\user\Desktop\DIGESTEX_DATA\KEMENDAG\IMPORT\impor 2025.xlsx',

    'IMPORT 2026' =>
        'C:\Users\user\Desktop\DIGESTEX_DATA\KEMENDAG\IMPORT\impor 2026.xlsx',
];

$parser =
    app(
        KemendagTradeWorkbookHeaderParser::class
    );

echo "========================================\n";
echo "DIGESTEX GENERIC TRADE HEADER PARSER TEST\n";
echo "========================================\n\n";

$allPassed = true;

foreach ($files as $label => $file) {

    echo "----------------------------------------\n";
    echo $label . PHP_EOL;
    echo "----------------------------------------\n";

    try {

        $mapping =
            $parser->parse($file);

        $periods =
            $mapping['periods'];

        $periodKeys =
            array_keys($periods);

        $firstPeriod =
            $periodKeys[0] ?? null;

        $lastPeriod =
            $periodKeys[count($periodKeys) - 1]
            ?? null;

        echo "  Trade Flow     : "
            . $mapping['trade_flow']
            . PHP_EOL;

        echo "  Value Prefix    : "
            . $mapping['value_prefix']
            . PHP_EOL;

        echo "  Period Count    : "
            . count($periods)
            . PHP_EOL;

        echo "  First Period    : "
            . ($firstPeriod ?? 'NONE')
            . PHP_EOL;

        echo "  Last Period     : "
            . ($lastPeriod ?? 'NONE')
            . PHP_EOL;

        /*
        |--------------------------------------------------------------------------
        | Basic expected schema validation
        |--------------------------------------------------------------------------
        */

        $expectedFlow =
            str_starts_with(
                strtoupper($label),
                'EXPORT'
            )
                ? 'export'
                : 'import';

        $expectedPrefix =
            $expectedFlow === 'export'
                ? 'fob_'
                : 'cif_';

        $expectedCount =
            str_contains(
                $label,
                '2026'
            )
                ? 6
                : 12;

        $testPassed =
            $mapping['trade_flow']
                === $expectedFlow
            &&
            $mapping['value_prefix']
                === $expectedPrefix
            &&
            count($periods)
                === $expectedCount;

        /*
        |--------------------------------------------------------------------------
        | Validate every period has value + volume
        |--------------------------------------------------------------------------
        */

        foreach (
            $periods as $period => $data
        ) {
            if (
                !isset(
                    $data['year'],
                    $data['month'],
                    $data['value_column'],
                    $data['volume_column']
                )
            ) {
                $testPassed = false;

                echo "  FAIL PERIOD     : "
                    . $period
                    . PHP_EOL;
            }
        }

        echo "  RESULT          : "
            . (
                $testPassed
                    ? 'PASS'
                    : 'FAIL'
            )
            . PHP_EOL;

        if (!$testPassed) {
            $allPassed = false;
        }

        echo PHP_EOL;

    } catch (\Throwable $e) {

        $allPassed = false;

        echo "  RESULT          : FAIL\n";
        echo "  EXCEPTION       : "
            . $e::class
            . PHP_EOL;
        echo "  MESSAGE         : "
            . $e->getMessage()
            . PHP_EOL;

        echo PHP_EOL;
    }
}

echo "========================================\n";

if ($allPassed) {
    echo "GENERIC HEADER PARSER TEST : PASS\n";
} else {
    echo "GENERIC HEADER PARSER TEST : REVIEW\n";
}

echo "DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";