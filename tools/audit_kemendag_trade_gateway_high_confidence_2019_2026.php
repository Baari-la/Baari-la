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
echo "DIGESTEX KEMENDAG HIGH-CONFIDENCE GATEWAY AUDIT\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  OPERATION   : READ ONLY\n";
echo "  METHOD      : PROVINCE + FLOW + COUNTRY + HS + MONTH\n\n";

$targets = [
    'LOBAM',
    'BUATAN',
    'JABABEKA',
    'JAKARTA / POS PASAR BARU (PTT)',
];

/*
|--------------------------------------------------------------------------
| Analyze one residual source
|--------------------------------------------------------------------------
*/

function analyzeSource(string $sourceName): void
{
    echo "========================================\n";
    echo "SOURCE : {$sourceName}\n";
    echo "========================================\n";

    /*
    |--------------------------------------------------------------------------
    | Residual baseline
    |--------------------------------------------------------------------------
    */

    $totalResidual =
        DB::table('trade_statistics')
            ->whereBetween('year', [2019, 2026])
            ->whereIn('trade_flow', ['export', 'import'])
            ->whereNull('trade_point_id')
            ->where('port_name', $sourceName)
            ->count();

    echo "  RESIDUAL RECORDS : {$totalResidual}\n";

    if ($totalResidual === 0) {
        echo "  STATUS           : NO RESIDUAL\n\n";
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Distinct residual patterns
    |--------------------------------------------------------------------------
    */

    $patterns =
        DB::table('trade_statistics')
            ->whereBetween('year', [2019, 2026])
            ->whereIn('trade_flow', ['export', 'import'])
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
            )
            ->get();

    $patternCount = $patterns->count();

    echo "  DISTINCT PATTERNS : {$patternCount}\n";

    /*
    |--------------------------------------------------------------------------
    | Candidate aggregation
    |--------------------------------------------------------------------------
    */

    $candidateStats = [];

    $matchedResidualRecords = 0;
    $matchedPatternCount = 0;

    foreach ($patterns as $pattern) {

        /*
        |--------------------------------------------------------------------------
        | Find resolved gateway distribution for exact pattern
        |--------------------------------------------------------------------------
        */

        $matches =
            DB::table('trade_statistics as ts')
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
                ->where(
                    'ts.province_id',
                    $pattern->province_id
                )
                ->where(
                    'ts.trade_flow',
                    $pattern->trade_flow
                )
                ->where(
                    'ts.country_id',
                    $pattern->country_id
                )
                ->where(
                    'ts.hs_id',
                    $pattern->hs_id
                )
                ->where(
                    'ts.month',
                    $pattern->month
                )
                ->where('tp.is_active', 1)
                ->select(
                    'tp.id',
                    'tp.name',
                    'tp.code',
                    'tp.trade_point_type_id',
                    'tp.city'
                )
                ->selectRaw(
                    'COUNT(*) AS matched_records'
                )
                ->groupBy(
                    'tp.id',
                    'tp.name',
                    'tp.code',
                    'tp.trade_point_type_id',
                    'tp.city'
                )
                ->orderByDesc('matched_records')
                ->get();

        if ($matches->isEmpty()) {
            continue;
        }

        $matchedTotal =
            $matches->sum(
                static function ($row) {
                    return (int) $row->matched_records;
                }
            );

        if ($matchedTotal <= 0) {
            continue;
        }

        /*
        |--------------------------------------------------------------------------
        | Pattern is considered matched.
        |--------------------------------------------------------------------------
        */

        $matchedPatternCount++;

        /*
        |--------------------------------------------------------------------------
        | Each residual record inherits the historical gateway
        | distribution of its exact pattern.
        |--------------------------------------------------------------------------
        */

        foreach ($matches as $match) {

            $gatewayId =
                (int) $match->id;

            $gatewayShare =
                (
                    (int) $match->matched_records
                    /
                    $matchedTotal
                );

            $weightedRecords =
                (int) round(
                    $pattern->residual_records
                    * $gatewayShare
                );

            if (!isset($candidateStats[$gatewayId])) {

                $candidateStats[$gatewayId] = [
                    'id' =>
                        $gatewayId,

                    'name' =>
                        (string) $match->name,

                    'code' =>
                        (string) $match->code,

                    'type' =>
                        (int) $match->trade_point_type_id,

                    'city' =>
                        $match->city !== null
                            ? (string) $match->city
                            : null,

                    'weighted_records' =>
                        0,

                    'pattern_hits' =>
                        0,

                    'dominant_patterns' =>
                        0,
                ];
            }

            $candidateStats[$gatewayId]['weighted_records']
                += $weightedRecords;

            $candidateStats[$gatewayId]['pattern_hits']++;

            $patternDominance =
                (
                    (int) $match->matched_records
                    /
                    $matchedTotal
                );

            if ($patternDominance >= 0.90) {
                $candidateStats[$gatewayId]['dominant_patterns']++;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Coverage accounting
        |--------------------------------------------------------------------------
        */

        $matchedResidualRecords +=
            (int) $pattern->residual_records;
    }

    /*
    |--------------------------------------------------------------------------
    | No matching historical pattern
    |--------------------------------------------------------------------------
    */

    if (
        $matchedResidualRecords === 0
        ||
        empty($candidateStats)
    ) {

        echo "  MATCHED RECORDS    : 0\n";
        echo "  COVERAGE           : 0.00%\n";
        echo "  STATUS             : REVIEW\n\n";

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Sort candidates
    |--------------------------------------------------------------------------
    */

    usort(
        $candidateStats,
        static function (array $a, array $b): int {

            return
                $b['weighted_records']
                <=>
                $a['weighted_records'];
        }
    );

    $coverage =
        (
            $matchedResidualRecords
            /
            $totalResidual
        )
        * 100;

    /*
    |--------------------------------------------------------------------------
    | Gateway dominance
    |--------------------------------------------------------------------------
    */

    $top =
        $candidateStats[0];

    $dominance =
        (
            $top['weighted_records']
            /
            max(
                1,
                $matchedResidualRecords
            )
        )
        * 100;

    $highDominancePatterns =
        $top['dominant_patterns'];

    $patternConsistency =
        $matchedPatternCount > 0
            ? (
                $highDominancePatterns
                /
                $matchedPatternCount
                * 100
            )
            : 0;

    /*
    |--------------------------------------------------------------------------
    | Classification
    |--------------------------------------------------------------------------
    |
    | Conservative thresholds:
    |
    | HIGH:
    |   dominance >= 90%
    |   coverage >= 80%
    |   matched residual >= 500
    |
    | MEDIUM:
    |   dominance >= 75%
    |   coverage >= 50%
    |
    | Otherwise REVIEW.
    |--------------------------------------------------------------------------
    */

    $classification = 'REVIEW';

    if (
        $dominance >= 90
        &&
        $coverage >= 80
        &&
        $matchedResidualRecords >= 500
    ) {

        $classification =
            'HIGH CONFIDENCE';

    } elseif (
        $dominance >= 75
        &&
        $coverage >= 50
    ) {

        $classification =
            'MEDIUM CONFIDENCE';
    }

    /*
    |--------------------------------------------------------------------------
    | Output
    |--------------------------------------------------------------------------
    */

    echo "  MATCHED RECORDS    : {$matchedResidualRecords}\n";

    echo "  MATCHED PATTERNS   : {$matchedPatternCount}\n";

    echo "  COVERAGE           : "
        . number_format($coverage, 2)
        . "%\n";

    echo PHP_EOL;
    echo "  TOP GATEWAY CANDIDATES:\n";

    foreach (
        array_slice($candidateStats, 0, 8)
        as $index => $candidate
    ) {

        $score =
            $matchedResidualRecords > 0
                ? (
                    $candidate['weighted_records']
                    /
                    $matchedResidualRecords
                    * 100
                )
                : 0;

        echo sprintf(
            "    %02d. ID=%-4d | %-35s | TYPE=%-3d | CITY=%-18s | WEIGHT=%7d | SCORE=%6.2f%% | PATTERNS=%4d\n",
            $index + 1,
            $candidate['id'],
            trim($candidate['name']),
            $candidate['type'],
            trim($candidate['city'] ?? '-'),
            $candidate['weighted_records'],
            $score,
            $candidate['pattern_hits']
        );
    }

    echo PHP_EOL;
    echo "  STRONGEST CANDIDATE\n";

    echo "    TRADE POINT ID : "
        . $top['id']
        . PHP_EOL;

    echo "    NAME           : "
        . $top['name']
        . PHP_EOL;

    echo "    DOMINANCE      : "
        . number_format($dominance, 2)
        . "%\n";

    echo "    COVERAGE       : "
        . number_format($coverage, 2)
        . "%\n";

    echo "    PATTERN CONSISTENCY : "
        . number_format($patternConsistency, 2)
        . "%\n";

    echo "    CLASSIFICATION : "
        . $classification
        . PHP_EOL;

    echo PHP_EOL;
}

/*
|--------------------------------------------------------------------------
| Run
|--------------------------------------------------------------------------
*/

foreach ($targets as $sourceName) {
    analyzeSource($sourceName);
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
echo "HIGH-CONFIDENCE GATEWAY AUDIT : COMPLETE\n";
echo "========================================\n";