<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use RuntimeException;

echo "========================================\n";
echo "DIGESTEX KEMENDAG TRADE POINT RESIDUAL PRIORITY AUDIT\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  OPERATION   : READ ONLY\n\n";

/*
|--------------------------------------------------------------------------
| Baseline
|--------------------------------------------------------------------------
*/

$currentNull =
    DB::table('trade_statistics')
        ->whereBetween(
            'year',
            [2019, 2026]
        )
        ->whereIn(
            'trade_flow',
            ['export', 'import']
        )
        ->whereNull('trade_point_id')
        ->count();

echo "CURRENT NULL TRADE POINT RECORDS : {$currentNull}\n\n";

if ($currentNull !== 46776) {
    throw new RuntimeException(
        "Expected 46776 unresolved Trade Point records, got {$currentNull}."
    );
}

/*
|--------------------------------------------------------------------------
| Total distinct residual source names
|--------------------------------------------------------------------------
*/

$distinctSourceNames =
    DB::table('trade_statistics')
        ->whereBetween(
            'year',
            [2019, 2026]
        )
        ->whereIn(
            'trade_flow',
            ['export', 'import']
        )
        ->whereNull('trade_point_id')
        ->whereNotNull('port_name')
        ->where('port_name', '<>', '')
        ->distinct('port_name')
        ->count('port_name');

echo "DISTINCT RESIDUAL SOURCE NAMES : {$distinctSourceNames}\n\n";

/*
|--------------------------------------------------------------------------
| Top 30 residual source names
|--------------------------------------------------------------------------
*/

$topSources =
    DB::table('trade_statistics')
        ->whereBetween(
            'year',
            [2019, 2026]
        )
        ->whereIn(
            'trade_flow',
            ['export', 'import']
        )
        ->whereNull('trade_point_id')
        ->whereNotNull('port_name')
        ->where('port_name', '<>', '')
        ->select('port_name')
        ->selectRaw(
            'COUNT(*) AS records'
        )
        ->selectRaw(
            'SUM(trade_value) AS trade_value'
        )
        ->selectRaw(
            'SUM(trade_volume) AS trade_volume'
        )
        ->groupBy('port_name')
        ->orderByDesc('records')
        ->limit(30)
        ->get();

echo "========================================\n";
echo "TOP 30 RESIDUAL TRADE POINT SOURCES\n";
echo "========================================\n";

foreach ($topSources as $index => $source) {

    echo sprintf(
        "%02d. %-40s | %7d records | VALUE=%15.3f | VOLUME=%15.3f\n",
        $index + 1,
        trim((string) $source->port_name),
        $source->records,
        $source->trade_value,
        $source->trade_volume
    );
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Detailed context for TOP 30
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "TOP 30 CONTEXT\n";
echo "========================================\n";

foreach ($topSources as $index => $source) {

    $sourceName =
        trim((string) $source->port_name);

    $base =
        DB::table('trade_statistics')
            ->whereBetween(
                'year',
                [2019, 2026]
            )
            ->whereIn(
                'trade_flow',
                ['export', 'import']
            )
            ->whereNull('trade_point_id')
            ->where(
                'port_name',
                $sourceName
            );

    $provinceRows =
        (clone $base)
            ->select(
                'province_id',
                'province_name'
            )
            ->selectRaw(
                'COUNT(*) AS records'
            )
            ->groupBy(
                'province_id',
                'province_name'
            )
            ->orderByDesc('records')
            ->limit(5)
            ->get();

    $flows =
        (clone $base)
            ->select('trade_flow')
            ->selectRaw(
                'COUNT(*) AS records'
            )
            ->selectRaw(
                'SUM(trade_value) AS trade_value'
            )
            ->selectRaw(
                'SUM(trade_volume) AS trade_volume'
            )
            ->groupBy('trade_flow')
            ->orderBy('trade_flow')
            ->get();

    $countries =
        (clone $base)
            ->whereNotNull('country_name')
            ->where(
                'country_name',
                '<>',
                ''
            )
            ->select('country_name')
            ->selectRaw(
                'COUNT(*) AS records'
            )
            ->groupBy('country_name')
            ->orderByDesc('records')
            ->limit(5)
            ->get();

    echo PHP_EOL;

    echo sprintf(
        "%02d. %s\n",
        $index + 1,
        $sourceName
    );

    echo "    RECORDS : "
        . $source->records
        . PHP_EOL;

    echo "    VALUE   : "
        . $source->trade_value
        . PHP_EOL;

    echo "    VOLUME  : "
        . $source->trade_volume
        . PHP_EOL;

    echo "    PROVINCES:\n";

    foreach ($provinceRows as $province) {

        echo sprintf(
            "      %-30s | province_id=%-4s | %6d records\n",
            trim(
                (string) (
                    $province->province_name
                    ?? '-'
                )
            ),
            $province->province_id ?? 'NULL',
            $province->records
        );
    }

    echo "    FLOWS:\n";

    foreach ($flows as $flow) {

        echo sprintf(
            "      %-7s | %6d records | VALUE=%15.3f | VOLUME=%15.3f\n",
            strtoupper(
                (string) $flow->trade_flow
            ),
            $flow->records,
            $flow->trade_value,
            $flow->trade_volume
        );
    }

    echo "    TOP COUNTRIES:\n";

    foreach ($countries as $country) {

        echo sprintf(
            "      %-30s | %6d records\n",
            trim(
                (string) $country->country_name
            ),
            $country->records
        );
    }
}

/*
|--------------------------------------------------------------------------
| Residual category summary
|--------------------------------------------------------------------------
|
| We distinguish:
|   - very large source groups
|   - medium groups
|   - small groups
|
| This is only prioritization, not mapping.
|--------------------------------------------------------------------------
*/

$bucketLarge =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn(
            'trade_flow',
            ['export', 'import']
        )
        ->whereNull('trade_point_id')
        ->whereNotNull('port_name')
        ->where('port_name', '<>', '')
        ->select('port_name')
        ->selectRaw('COUNT(*) AS records')
        ->groupBy('port_name')
        ->having('records', '>=', 1000)
        ->get();

$bucketMedium =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn(
            'trade_flow',
            ['export', 'import']
        )
        ->whereNull('trade_point_id')
        ->whereNotNull('port_name')
        ->where('port_name', '<>', '')
        ->select('port_name')
        ->selectRaw('COUNT(*) AS records')
        ->groupBy('port_name')
        ->having('records', '>=', 100)
        ->having('records', '<', 1000)
        ->get();

$bucketSmall =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn(
            'trade_flow',
            ['export', 'import']
        )
        ->whereNull('trade_point_id')
        ->whereNotNull('port_name')
        ->where('port_name', '<>', '')
        ->select('port_name')
        ->selectRaw('COUNT(*) AS records')
        ->groupBy('port_name')
        ->having('records', '<', 100)
        ->get();

echo PHP_EOL;
echo "========================================\n";
echo "RESIDUAL PRIORITY BUCKETS\n";
echo "========================================\n";

echo "  LARGE SOURCE GROUPS  : "
    . $bucketLarge->count()
    . PHP_EOL;

echo "  MEDIUM SOURCE GROUPS : "
    . $bucketMedium->count()
    . PHP_EOL;

echo "  SMALL SOURCE GROUPS  : "
    . $bucketSmall->count()
    . PHP_EOL;

/*
|--------------------------------------------------------------------------
| Coverage represented by top 30
|--------------------------------------------------------------------------
*/

$top30Records =
    $topSources->sum(
        static function ($row) {
            return (int) $row->records;
        }
    );

$top30Share =
    $currentNull > 0
        ? (
            $top30Records
            / $currentNull
            * 100
        )
        : 0;

echo PHP_EOL;
echo "TOP 30 RESIDUAL COVERAGE:\n";

echo "  RECORDS : "
    . $top30Records
    . PHP_EOL;

echo "  SHARE   : "
    . number_format(
        $top30Share,
        2
    )
    . "%\n";

/*
|--------------------------------------------------------------------------
| Safety
|--------------------------------------------------------------------------
*/

echo PHP_EOL;
echo "========================================\n";
echo "DATABASE SAFETY\n";
echo "========================================\n";

echo "  INSERT : NO\n";
echo "  UPDATE : NO\n";
echo "  DELETE : NO\n";
echo "  DATABASE WAS NOT MODIFIED.\n";

echo "========================================\n";
echo "TRADE POINT RESIDUAL PRIORITY AUDIT : COMPLETE\n";
echo "========================================\n";