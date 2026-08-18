<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use App\Services\Trade\KemendagMonthlyTradeIngestionService;


/*
|--------------------------------------------------------------------------
| DIGESTEX KEMENDAG MONTHLY TRADE INGESTION
|--------------------------------------------------------------------------
|
| Examples:
|
| Regular new period:
|
| php tools\ingest_kemendag_monthly.php \
|   --file="C:\...\ekspor 2026.xlsx" \
|   --year=2026 \
|   --type=regular \
|   --period=2026-07
|
| Multiple new periods:
|
| php tools\ingest_kemendag_monthly.php \
|   --file="C:\...\ekspor 2026.xlsx" \
|   --year=2026 \
|   --type=regular \
|   --period=2026-07,2026-08
|
| Revision:
|
| php tools\ingest_kemendag_monthly.php \
|   --file="C:\...\ekspor 2026.xlsx" \
|   --year=2026 \
|   --type=revision \
|   --period=2026-03
|
|--------------------------------------------------------------------------
*/

$options = getopt(
    '',
    [
        'file:',
        'year:',
        'type:',
        'period:',
    ]
);

$file =
    isset($options['file'])
        ? trim((string) $options['file'])
        : null;

$year =
    isset($options['year'])
        ? (int) $options['year']
        : null;

$type =
    isset($options['type'])
        ? strtolower(
            trim(
                (string) $options['type']
            )
        )
        : 'regular';

$periodArgument =
    isset($options['period'])
        ? trim(
            (string) $options['period']
        )
        : null;

/*
|--------------------------------------------------------------------------
| Validation
|--------------------------------------------------------------------------
*/

if ($file === null || $file === '') {
    throw new RuntimeException(
        'Parameter --file wajib diisi.'
    );
}

if (!is_file($file)) {
    throw new RuntimeException(
        "File tidak ditemukan:\n{$file}"
    );
}

if ($year === null || $year < 2000) {
    throw new RuntimeException(
        'Parameter --year wajib diisi dengan tahun yang valid.'
    );
}

if (
    !in_array(
        $type,
        ['regular', 'revision'],
        true
    )
) {
    throw new RuntimeException(
        "Parameter --type harus 'regular' atau 'revision'."
    );
}

/*
|--------------------------------------------------------------------------
| Parse periods
|--------------------------------------------------------------------------
*/

$periodFilter = null;

if (
    $periodArgument !== null
    &&
    $periodArgument !== ''
) {
    $periodFilter =
        array_values(
            array_filter(
                array_map(
                    static function (
                        string $period
                    ): string {
                        return trim($period);
                    },
                    explode(
                        ',',
                        $periodArgument
                    )
                ),
                static function (
                    string $period
                ): bool {
                    return $period !== '';
                }
            )
        );

    foreach (
        $periodFilter as $period
    ) {
        if (
            !preg_match(
                '/^\d{4}-\d{2}$/',
                $period
            )
        ) {
            throw new RuntimeException(
                "Format period tidak valid: {$period}. "
                . 'Gunakan YYYY-MM.'
            );
        }

        $periodYear =
            (int) substr(
                $period,
                0,
                4
            );

        $periodMonth =
            (int) substr(
                $period,
                5,
                2
            );

        if ($periodYear !== $year) {
            throw new RuntimeException(
                "Period {$period} tidak sesuai dengan year={$year}."
            );
        }

        if (
            $periodMonth < 1
            ||
            $periodMonth > 12
        ) {
            throw new RuntimeException(
                "Bulan period tidak valid: {$period}."
            );
        }
    }
}

/*
|--------------------------------------------------------------------------
| Header
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "DIGESTEX KEMENDAG MONTHLY TRADE INGESTION\n";
echo "========================================\n\n";

echo "SOURCE:\n";
echo $file . PHP_EOL;

echo "YEAR:\n";
echo $year . PHP_EOL;

echo "TYPE:\n";
echo $type . PHP_EOL;

echo "PERIOD:\n";

if ($periodFilter === null) {
    echo "ALL AVAILABLE PERIODS\n";
} else {
    echo implode(
        ', ',
        $periodFilter
    ) . PHP_EOL;
}

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
| Execute
|--------------------------------------------------------------------------
*/

try {

    $batch =
        $service->ingest(
            $file,
            $year,
            $type,
            $periodFilter
        );

    echo "========================================\n";
    echo "MONTHLY INGESTION COMPLETE\n";
    echo "========================================\n\n";

    echo "BATCH ID       : "
        . $batch->id
        . PHP_EOL;

    echo "YEAR           : "
        . $batch->year
        . PHP_EOL;

    echo "TYPE           : "
        . $type
        . PHP_EOL;

    echo "STATUS         : "
        . $batch->status
        . PHP_EOL;

    echo "TOTAL ROWS     : "
        . $batch->total_rows
        . PHP_EOL;

    echo "INSERTED ROWS  : "
        . $batch->inserted_rows
        . PHP_EOL;

    echo "UPDATED ROWS   : "
        . $batch->updated_rows
        . PHP_EOL;

    echo "FAILED ROWS    : "
        . $batch->failed_rows
        . PHP_EOL;

    echo PHP_EOL;
    echo "REMARKS:\n";
    echo $batch->remarks . PHP_EOL;

    echo PHP_EOL;
    echo "========================================\n";

} catch (\Throwable $e) {

    fwrite(
        STDERR,
        PHP_EOL .
        "========================================" . PHP_EOL .
        "MONTHLY INGESTION FAILED" . PHP_EOL .
        "========================================" . PHP_EOL .
        "Exception : "
        . $e::class
        . PHP_EOL
        . "Code      : "
        . $e->getCode()
        . PHP_EOL
        . "Message   : "
        . $e->getMessage()
        . PHP_EOL
    );

    if (
        $e instanceof
        \Illuminate\Database\QueryException
    ) {
        fwrite(
            STDERR,
            "SQLSTATE   : "
            . ($e->errorInfo[0] ?? '')
            . PHP_EOL
            . "DriverCode : "
            . ($e->errorInfo[1] ?? '')
            . PHP_EOL
            . "DriverMsg  : "
            . ($e->errorInfo[2] ?? '')
            . PHP_EOL
        );
    }

    exit(1);
}