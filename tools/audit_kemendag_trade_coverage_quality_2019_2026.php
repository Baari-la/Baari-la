<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

const FIRST_YEAR = 2019;
const LAST_YEAR = 2026;

echo "========================================\n";
echo "DIGESTEX KEMENDAG TRADE COVERAGE & QUALITY AUDIT\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : " . FIRST_YEAR . "-" . LAST_YEAR . PHP_EOL;
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  DATABASE    : READ ONLY\n\n";

/*
|--------------------------------------------------------------------------
| Aggregate by year + trade flow
|--------------------------------------------------------------------------
*/

$rows =
    DB::table('trade_statistics')
        ->selectRaw(
            "
            year,
            trade_flow,

            COUNT(*) AS total_records,

            COUNT(DISTINCT trade_identity)
                AS distinct_identities,

            COUNT(DISTINCT month)
                AS months,

            MIN(month)
                AS min_month,

            MAX(month)
                AS max_month,

            SUM(trade_value)
                AS trade_value,

            SUM(trade_volume)
                AS trade_volume,

            SUM(
                CASE
                    WHEN country_id IS NULL THEN 1
                    ELSE 0
                END
            ) AS null_country,

            SUM(
                CASE
                    WHEN trade_point_id IS NULL THEN 1
                    ELSE 0
                END
            ) AS null_trade_point,

            SUM(
                CASE
                    WHEN province_id IS NULL THEN 1
                    ELSE 0
                END
            ) AS null_province,

            SUM(
                CASE
                    WHEN hs_id IS NULL THEN 1
                    ELSE 0
                END
            ) AS null_hs,

            SUM(
                CASE
                    WHEN trade_identity IS NULL THEN 1
                    ELSE 0
                END
            ) AS null_identity
            "
        )
        ->whereBetween(
            'year',
            [FIRST_YEAR, LAST_YEAR]
        )
        ->whereIn(
            'trade_flow',
            [
                'export',
                'import',
            ]
        )
        ->groupBy(
            'year',
            'trade_flow'
        )
        ->orderBy('year')
        ->orderBy('trade_flow')
        ->get();

/*
|--------------------------------------------------------------------------
| Index rows
|--------------------------------------------------------------------------
*/

$indexed = [];

foreach ($rows as $row) {

    $key =
        $row->year
        . '|'
        . $row->trade_flow;

    $indexed[$key] = $row;
}

/*
|--------------------------------------------------------------------------
| Header
|--------------------------------------------------------------------------
*/

echo "YEAR | FLOW   | RECORDS | MONTHS | VALUE | VOLUME | COUNTRY NULL | TRADE POINT NULL | PROVINCE NULL | HS NULL | IDENTITY NULL\n";
echo str_repeat('-', 132) . PHP_EOL;

/*
|--------------------------------------------------------------------------
| Coverage + quality report
|--------------------------------------------------------------------------
*/

for (
    $year = FIRST_YEAR;
    $year <= LAST_YEAR;
    $year++
) {

    foreach (
        ['export', 'import']
        as $flow
    ) {

        $key =
            $year
            . '|'
            . $flow;

        $row =
            $indexed[$key]
            ?? null;

        if ($row === null) {

            echo sprintf(
                "%4d | %-6s | MISSING\n",
                $year,
                strtoupper($flow)
            );

            continue;
        }

        $records =
            (int) $row->total_records;

        $months =
            (int) $row->months;

        $expectedMonths =
            $year === 2026
                ? 6
                : 12;

        $coveragePass =
            $months === $expectedMonths
            &&
            (int) $row->min_month === 1
            &&
            (int) $row->max_month === $expectedMonths;

        $countryNull =
            (int) $row->null_country;

        $tradePointNull =
            (int) $row->null_trade_point;

        $provinceNull =
            (int) $row->null_province;

        $hsNull =
            (int) $row->null_hs;

        $identityNull =
            (int) $row->null_identity;

        $countryRate =
            $records > 0
                ? (
                    ($records - $countryNull)
                    / $records
                ) * 100
                : 0.0;

        $tradePointRate =
            $records > 0
                ? (
                    ($records - $tradePointNull)
                    / $records
                ) * 100
                : 0.0;

        $provinceRate =
            $records > 0
                ? (
                    ($records - $provinceNull)
                    / $records
                ) * 100
                : 0.0;

        $hsRate =
            $records > 0
                ? (
                    ($records - $hsNull)
                    / $records
                ) * 100
                : 0.0;

        $identityRate =
            $records > 0
                ? (
                    ($records - $identityNull)
                    / $records
                ) * 100
                : 0.0;

        echo sprintf(
            "%4d | %-6s | %7d | %6d | %14.3f | %14.3f | %11.2f%% | %15.2f%% | %13.2f%% | %7.2f%% | %10.2f%%\n",
            $year,
            strtoupper($flow),
            $records,
            $months,
            (float) $row->trade_value,
            (float) $row->trade_volume,
            $countryRate,
            $tradePointRate,
            $provinceRate,
            $hsRate,
            $identityRate
        );

        /*
         * Store calculated metrics for summary.
         */
        $indexed[$key]->country_rate =
            $countryRate;

        $indexed[$key]->trade_point_rate =
            $tradePointRate;

        $indexed[$key]->province_rate =
            $provinceRate;

        $indexed[$key]->hs_rate =
            $hsRate;

        $indexed[$key]->identity_rate =
            $identityRate;

        $indexed[$key]->coverage_pass =
            $coveragePass;
    }
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Coverage status
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "MONTHLY COVERAGE STATUS\n";
echo "========================================\n";

$coverageFailures = 0;

for (
    $year = FIRST_YEAR;
    $year <= LAST_YEAR;
    $year++
) {

    foreach (
        ['export', 'import']
        as $flow
    ) {

        $key =
            $year
            . '|'
            . $flow;

        $row =
            $indexed[$key]
            ?? null;

        if ($row === null) {

            echo sprintf(
                "  %4d %-6s : MISSING\n",
                $year,
                strtoupper($flow)
            );

            $coverageFailures++;

            continue;
        }

        $status =
            $row->coverage_pass
                ? 'PASS'
                : 'FAIL';

        echo sprintf(
            "  %4d %-6s : %s (%d months, %d-%d)\n",
            $year,
            strtoupper($flow),
            $status,
            (int) $row->months,
            (int) $row->min_month,
            (int) $row->max_month
        );

        if (
            !$row->coverage_pass
        ) {
            $coverageFailures++;
        }
    }
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Global totals
|--------------------------------------------------------------------------
*/

$global =
    DB::table('trade_statistics')
        ->selectRaw(
            "
            COUNT(*) AS total_records,
            COUNT(DISTINCT trade_identity)
                AS distinct_identities,
            SUM(trade_value)
                AS trade_value,
            SUM(trade_volume)
                AS trade_volume,
            SUM(
                CASE
                    WHEN country_id IS NULL THEN 1
                    ELSE 0
                END
            ) AS null_country,
            SUM(
                CASE
                    WHEN trade_point_id IS NULL THEN 1
                    ELSE 0
                END
            ) AS null_trade_point,
            SUM(
                CASE
                    WHEN province_id IS NULL THEN 1
                    ELSE 0
                END
            ) AS null_province,
            SUM(
                CASE
                    WHEN hs_id IS NULL THEN 1
                    ELSE 0
                END
            ) AS null_hs,
            SUM(
                CASE
                    WHEN trade_identity IS NULL THEN 1
                    ELSE 0
                END
            ) AS null_identity
            "
        )
        ->whereBetween(
            'year',
            [FIRST_YEAR, LAST_YEAR]
        )
        ->whereIn(
            'trade_flow',
            [
                'export',
                'import',
            ]
        )
        ->first();

echo "========================================\n";
echo "GLOBAL 2019-2026 TRADE DATA QUALITY\n";
echo "========================================\n";

$totalRecords =
    (int) $global->total_records;

$globalCountryRate =
    $totalRecords > 0
        ? (
            ($totalRecords
                - (int) $global->null_country)
            / $totalRecords
        ) * 100
        : 0.0;

$globalTradePointRate =
    $totalRecords > 0
        ? (
            ($totalRecords
                - (int) $global->null_trade_point)
            / $totalRecords
        ) * 100
        : 0.0;

$globalProvinceRate =
    $totalRecords > 0
        ? (
            ($totalRecords
                - (int) $global->null_province)
            / $totalRecords
        ) * 100
        : 0.0;

$globalHsRate =
    $totalRecords > 0
        ? (
            ($totalRecords
                - (int) $global->null_hs)
            / $totalRecords
        ) * 100
        : 0.0;

$globalIdentityRate =
    $totalRecords > 0
        ? (
            ($totalRecords
                - (int) $global->null_identity)
            / $totalRecords
        ) * 100
        : 0.0;

echo "  RECORDS             : "
    . $totalRecords
    . PHP_EOL;

echo "  DISTINCT IDENTITIES : "
    . (int) $global->distinct_identities
    . PHP_EOL;

echo "  TRADE VALUE         : "
    . number_format(
        (float) $global->trade_value,
        3,
        '.',
        ''
    )
    . PHP_EOL;

echo "  TRADE VOLUME        : "
    . number_format(
        (float) $global->trade_volume,
        3,
        '.',
        ''
    )
    . PHP_EOL;

echo PHP_EOL;

echo "RESOLUTION RATE:\n";

echo "  COUNTRY             : "
    . number_format(
        $globalCountryRate,
        4
    )
    . "%\n";

echo "  TRADE POINT         : "
    . number_format(
        $globalTradePointRate,
        4
    )
    . "%\n";

echo "  PROVINCE            : "
    . number_format(
        $globalProvinceRate,
        4
    )
    . "%\n";

echo "  HS                  : "
    . number_format(
        $globalHsRate,
        4
    )
    . "%\n";

echo "  TRADE IDENTITY      : "
    . number_format(
        $globalIdentityRate,
        4
    )
    . "%\n";

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Residual counts
|--------------------------------------------------------------------------
*/

echo "RESIDUAL NULL COUNTS:\n";

echo "  COUNTRY             : "
    . (int) $global->null_country
    . PHP_EOL;

echo "  TRADE POINT         : "
    . (int) $global->null_trade_point
    . PHP_EOL;

echo "  PROVINCE            : "
    . (int) $global->null_province
    . PHP_EOL;

echo "  HS                  : "
    . (int) $global->null_hs
    . PHP_EOL;

echo "  TRADE IDENTITY      : "
    . (int) $global->null_identity
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Batch inventory
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "INGESTION BATCH INVENTORY\n";
echo "========================================\n";

$batches =
    DB::table('trade_import_batches')
        ->select(
            'id',
            'year',
            'trade_flow',
            'status',
            'total_rows',
            'inserted_rows',
            'updated_rows',
            'failed_rows'
        )
        ->whereBetween(
            'year',
            [FIRST_YEAR, LAST_YEAR]
        )
        ->whereIn(
            'trade_flow',
            [
                'export',
                'import',
            ]
        )
        ->orderBy('year')
        ->orderBy('trade_flow')
        ->orderBy('id')
        ->get();

foreach ($batches as $batch) {

    echo sprintf(
        "  Batch %-4d | %4d | %-6s | %-10s | total=%7d | inserted=%7d | updated=%5d | failed=%d\n",
        $batch->id,
        $batch->year,
        strtoupper($batch->trade_flow),
        $batch->status,
        $batch->total_rows,
        $batch->inserted_rows,
        $batch->updated_rows,
        $batch->failed_rows
    );
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Final gate
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "FINAL QUALITY GATE\n";
echo "========================================\n";

echo "  COVERAGE FAILURES   : "
    . $coverageFailures
    . PHP_EOL;

echo "  NULL IDENTITY       : "
    . (int) $global->null_identity
    . PHP_EOL;

echo "  NULL HS             : "
    . (int) $global->null_hs
    . PHP_EOL;

echo "  NULL PROVINCE       : "
    . (int) $global->null_province
    . PHP_EOL;

echo PHP_EOL;

if (
    $coverageFailures === 0
    &&
    (int) $global->null_identity === 0
    &&
    (int) $global->null_hs === 0
    &&
    (int) $global->null_province === 0
) {

    echo "FINAL QUALITY GATE : PASS\n";

} else {

    echo "FINAL QUALITY GATE : REVIEW\n";
}

echo "DATABASE WAS NOT MODIFIED.\n";
echo "========================================\n";
echo "COVERAGE & QUALITY AUDIT : COMPLETE\n";
echo "========================================\n";