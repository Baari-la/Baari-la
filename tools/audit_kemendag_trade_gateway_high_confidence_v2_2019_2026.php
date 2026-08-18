<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "========================================\n";
echo "DIGESTEX KEMENDAG HIGH-CONFIDENCE GATEWAY AUDIT V2\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  OPERATION   : READ ONLY\n";
echo "  METHOD      : SET-BASED PATTERN AGGREGATION\n\n";

$targets = [
    'LOBAM',
    'BUATAN',
    'JABABEKA',
    'JAKARTA / POS PASAR BARU (PTT)',
];

/*
|--------------------------------------------------------------------------
| Common base
|--------------------------------------------------------------------------
*/

$resolvedBase = DB::table('trade_statistics as ts')
    ->join(
        'trade_points as tp',
        'tp.id',
        '=',
        'ts.trade_point_id'
    )
    ->whereBetween(
        'ts.year',
        [2019, 2026]
    )
    ->whereIn(
        'ts.trade_flow',
        ['export', 'import']
    )
    ->whereNotNull('ts.trade_point_id')
    ->where('tp.is_active', 1);

/*
|--------------------------------------------------------------------------
| Analyze source
|--------------------------------------------------------------------------
*/

foreach ($targets as $sourceName) {

    echo "========================================\n";
    echo "SOURCE : {$sourceName}\n";
    echo "========================================\n";

    /*
    |--------------------------------------------------------------------------
    | Residual baseline
    |--------------------------------------------------------------------------
    */

    $residual = DB::table('trade_statistics')
        ->whereBetween(
            'year',
            [2019, 2026]
        )
        ->whereIn(
            'trade_flow',
            ['export', 'import']
        )
        ->whereNull('trade_point_id')
        ->where('port_name', $sourceName)
        ->first([
            DB::raw('COUNT(*) AS records'),
            DB::raw('COUNT(DISTINCT country_id) AS countries'),
            DB::raw('COUNT(DISTINCT hs_id) AS hs_count'),
            DB::raw('SUM(trade_value) AS trade_value'),
            DB::raw('SUM(trade_volume) AS trade_volume'),
        ]);

    $totalResidual = (int) $residual->records;

    echo "  RESIDUAL RECORDS : {$totalResidual}\n";
    echo "  DISTINCT COUNTRY : {$residual->countries}\n";
    echo "  DISTINCT HS      : {$residual->hs_count}\n";

    if ($totalResidual === 0) {
        echo "  STATUS           : NO RESIDUAL\n\n";
        continue;
    }

    /*
    |--------------------------------------------------------------------------
    | Residual exact pattern universe
    |--------------------------------------------------------------------------
    */

    $patterns = DB::table('trade_statistics')
        ->whereBetween(
            'year',
            [2019, 2026]
        )
        ->whereIn(
            'trade_flow',
            ['export', 'import']
        )
        ->whereNull('trade_point_id')
        ->where('port_name', $sourceName)
        ->whereNotNull('province_id')
        ->whereNotNull('country_id')
        ->whereNotNull('hs_id')
        ->select(
            'province_id',
            'trade_flow',
            'country_id',
            'hs_id',
            'month'
        )
        ->selectRaw('COUNT(*) AS residual_records')
        ->groupBy(
            'province_id',
            'trade_flow',
            'country_id',
            'hs_id',
            'month'
        );

    /*
    |--------------------------------------------------------------------------
    | Matched gateway-pattern aggregation
    |
    | Join resolved records to exact residual pattern universe.
    |--------------------------------------------------------------------------
    */

    $patternMatches = DB::query()
        ->fromSub(
            $patterns,
            'rp'
        )
        ->joinSub(
            $resolvedBase
                ->select(
                    'ts.province_id',
                    'ts.trade_flow',
                    'ts.country_id',
                    'ts.hs_id',
                    'ts.month',
                    'ts.trade_point_id'
                ),
            'rt',
            function ($join) {
                $join
                    ->on(
                        'rt.province_id',
                        '=',
                        'rp.province_id'
                    )
                    ->on(
                        'rt.trade_flow',
                        '=',
                        'rp.trade_flow'
                    )
                    ->on(
                        'rt.country_id',
                        '=',
                        'rp.country_id'
                    )
                    ->on(
                        'rt.hs_id',
                        '=',
                        'rp.hs_id'
                    )
                    ->on(
                        'rt.month',
                        '=',
                        'rp.month'
                    );
            }
        )
        ->join(
            'trade_points as tp',
            'tp.id',
            '=',
            'rt.trade_point_id'
        )
        ->where(
            'tp.is_active',
            1
        )
        ->select(
            'rp.province_id',
            'rp.trade_flow',
            'rp.country_id',
            'rp.hs_id',
            'rp.month',
            'rp.residual_records',
            'rt.trade_point_id'
        )
        ->selectRaw(
            'COUNT(*) AS historical_matches'
        )
        ->groupBy(
            'rp.province_id',
            'rp.trade_flow',
            'rp.country_id',
            'rp.hs_id',
            'rp.month',
            'rp.residual_records',
            'rt.trade_point_id'
        );

    /*
    |--------------------------------------------------------------------------
    | Gateway aggregate
    |--------------------------------------------------------------------------
    */

    /*
|--------------------------------------------------------------------------
| Gateway aggregate - no window function
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| First: total historical matches per exact pattern
|--------------------------------------------------------------------------
*/

$patternTotals = DB::query()
    ->fromSub(
        $patternMatches,
        'pm'
    )
    ->select(
        'pm.province_id',
        'pm.trade_flow',
        'pm.country_id',
        'pm.hs_id',
        'pm.month'
    )
    ->selectRaw(
        'SUM(pm.historical_matches) AS pattern_total_matches'
    )
    ->groupBy(
        'pm.province_id',
        'pm.trade_flow',
        'pm.country_id',
        'pm.hs_id',
        'pm.month'
    );

/*
|--------------------------------------------------------------------------
| Second: calculate gateway share for every pattern
|--------------------------------------------------------------------------
*/

$weightedMatches = DB::query()
    ->fromSub(
        $patternMatches,
        'pm'
    )
    ->joinSub(
        $patternTotals,
        'pt',
        function ($join) {

            $join
                ->on(
                    'pt.province_id',
                    '=',
                    'pm.province_id'
                )
                ->on(
                    'pt.trade_flow',
                    '=',
                    'pm.trade_flow'
                )
                ->on(
                    'pt.country_id',
                    '=',
                    'pm.country_id'
                )
                ->on(
                    'pt.hs_id',
                    '=',
                    'pm.hs_id'
                )
                ->on(
                    'pt.month',
                    '=',
                    'pm.month'
                );
        }
    )
    ->select(
        'pm.trade_point_id'
    )
    ->selectRaw(
        'SUM(
            pm.residual_records
            *
            (
                pm.historical_matches
                /
                NULLIF(
                    pt.pattern_total_matches,
                    0
                )
            )
        ) AS weighted_records'
    )
    ->selectRaw(
        'COUNT(DISTINCT CONCAT(
            pm.province_id,
            "-",
            pm.trade_flow,
            "-",
            pm.country_id,
            "-",
            pm.hs_id,
            "-",
            pm.month
        )) AS pattern_hits'
    )
    ->groupBy(
        'pm.trade_point_id'
    );

/*
|--------------------------------------------------------------------------
| Third: attach Trade Point master
|--------------------------------------------------------------------------
*/

$gatewayStats = DB::table('trade_points as tp')
    ->joinSub(
        $weightedMatches,
        'wm',
        function ($join) {

            $join->on(
                'wm.trade_point_id',
                '=',
                'tp.id'
            );
        }
    )
    ->where(
        'tp.is_active',
        1
    )
    ->select(
        'tp.id',
        'tp.code',
        'tp.name',
        'tp.name_en',
        'tp.trade_point_type_id',
        'tp.city'
    )
    ->addSelect(
        'wm.weighted_records',
        'wm.pattern_hits'
    )
    ->orderByDesc(
        'wm.weighted_records'
    )
    ->limit(10)
    ->get();

    /*
    |--------------------------------------------------------------------------
    | Coverage
    |--------------------------------------------------------------------------
    */

    $matchedResidual = DB::query()
        ->fromSub(
            $patterns,
            'rp'
        )
        ->joinSub(
            DB::query()
                ->fromSub(
                    $patternMatches,
                    'pm2'
                )
                ->select(
                    'pm2.province_id',
                    'pm2.trade_flow',
                    'pm2.country_id',
                    'pm2.hs_id',
                    'pm2.month'
                )
                ->selectRaw(
                    'SUM(pm2.historical_matches) AS matched'
                )
                ->groupBy(
                    'pm2.province_id',
                    'pm2.trade_flow',
                    'pm2.country_id',
                    'pm2.hs_id',
                    'pm2.month'
                ),
            'matched_patterns',
            function ($join) {
                $join
                    ->on(
                        'matched_patterns.province_id',
                        '=',
                        'rp.province_id'
                    )
                    ->on(
                        'matched_patterns.trade_flow',
                        '=',
                        'rp.trade_flow'
                    )
                    ->on(
                        'matched_patterns.country_id',
                        '=',
                        'rp.country_id'
                    )
                    ->on(
                        'matched_patterns.hs_id',
                        '=',
                        'rp.hs_id'
                    )
                    ->on(
                        'matched_patterns.month',
                        '=',
                        'rp.month'
                    );
            }
        )
        ->selectRaw(
            'SUM(rp.residual_records) AS total_residual'
        )
        ->selectRaw(
            'SUM(
                CASE
                    WHEN matched_patterns.matched IS NOT NULL
                    THEN rp.residual_records
                    ELSE 0
                END
            ) AS matched_residual'
        )
        ->first();

    $matchedResidualRecords =
        (int) ($matchedResidual->matched_residual ?? 0);

    $coverage =
        $totalResidual > 0
            ? (
                $matchedResidualRecords
                / $totalResidual
                * 100
            )
            : 0;

    echo "  MATCHED RECORDS : {$matchedResidualRecords}\n";
    echo "  COVERAGE        : "
        . number_format($coverage, 2)
        . "%\n";

    echo PHP_EOL;
    echo "  TOP GATEWAY CANDIDATES:\n";

    if ($gatewayStats->isEmpty()) {

        echo "    NONE\n\n";
        continue;
    }

    foreach ($gatewayStats as $index => $gateway) {

        $weight =
            (float) $gateway->weighted_records;

        $score =
            $matchedResidualRecords > 0
                ? (
                    $weight
                    / $matchedResidualRecords
                    * 100
                )
                : 0;

        echo sprintf(
            "    %02d. ID=%-4d | %-35s | TYPE=%-3d | CITY=%-18s | WEIGHT=%9.2f | SCORE=%6.2f%% | PATTERNS=%5d\n",
            $index + 1,
            $gateway->id,
            trim((string) $gateway->name),
            $gateway->trade_point_type_id,
            trim((string) ($gateway->city ?? '-')),
            $weight,
            $score,
            $gateway->pattern_hits
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Strongest candidate
    |--------------------------------------------------------------------------
    */

    $top = $gatewayStats->first();

    $topScore =
        $matchedResidualRecords > 0
            ? (
                (float) $top->weighted_records
                / $matchedResidualRecords
                * 100
            )
            : 0;

    $classification = 'REVIEW';

    if (
        $topScore >= 90
        &&
        $coverage >= 80
        &&
        $matchedResidualRecords >= 500
    ) {
        $classification = 'HIGH CONFIDENCE';
    } elseif (
        $topScore >= 75
        &&
        $coverage >= 50
    ) {
        $classification = 'MEDIUM CONFIDENCE';
    }

    echo PHP_EOL;
    echo "  STRONGEST CANDIDATE:\n";
    echo "    TRADE POINT ID : {$top->id}\n";
    echo "    NAME           : {$top->name}\n";
    echo "    DOMINANCE      : "
        . number_format($topScore, 2)
        . "%\n";
    echo "    COVERAGE       : "
        . number_format($coverage, 2)
        . "%\n";
    echo "    CLASSIFICATION : {$classification}\n";
    echo PHP_EOL;
}

/*
|--------------------------------------------------------------------------
| Safety
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "DATABASE SAFETY\n";
echo "========================================\n";

echo "  INSERT : NO\n";
echo "  UPDATE : NO\n";
echo "  DELETE : NO\n";
echo "  DATABASE WAS NOT MODIFIED.\n";

echo "========================================\n";
echo "HIGH-CONFIDENCE GATEWAY AUDIT V2 : COMPLETE\n";
echo "========================================\n";