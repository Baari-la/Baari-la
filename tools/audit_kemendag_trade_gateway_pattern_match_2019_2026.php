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
echo "DIGESTEX KEMENDAG TRADE GATEWAY PATTERN MATCH\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  OPERATION   : READ ONLY\n";
echo "  METHOD      : PROVINCE + FLOW + COUNTRY + HS\n\n";

$targets = [
    'LOBAM',
    'BUATAN',
    'JABABEKA',
    'JAKARTA / POS PASAR BARU (PTT)',
];

/*
|--------------------------------------------------------------------------
| Residual source baseline
|--------------------------------------------------------------------------
*/

foreach ($targets as $sourceName) {

    $baseline =
        DB::table('trade_statistics')
            ->whereBetween('year', [2019, 2026])
            ->whereIn('trade_flow', ['export', 'import'])
            ->whereNull('trade_point_id')
            ->where('port_name', $sourceName)
            ->selectRaw('COUNT(*) AS records')
            ->selectRaw('COUNT(DISTINCT country_id) AS countries')
            ->selectRaw('COUNT(DISTINCT hs_id) AS hs_count')
            ->selectRaw('SUM(trade_value) AS trade_value')
            ->selectRaw('SUM(trade_volume) AS trade_volume')
            ->first();

    echo sprintf(
        "SOURCE %-35s | %6d records | COUNTRY=%4d | HS=%5d | VALUE=%15.3f\n",
        $sourceName,
        $baseline->records,
        $baseline->countries,
        $baseline->hs_count,
        $baseline->trade_value
    );
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Pattern analysis function
|--------------------------------------------------------------------------
*/

function analyzeSource(string $sourceName): void
{
    echo "========================================\n";
    echo "PATTERN MATCH : {$sourceName}\n";
    echo "========================================\n";

    /*
    |--------------------------------------------------------------------------
    | Residual patterns
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
                'hs_id'
            )
            ->selectRaw('COUNT(*) AS residual_records')
            ->groupBy(
                'province_id',
                'trade_flow',
                'country_id',
                'hs_id'
            )
            ->orderByDesc('residual_records')
            ->limit(100)
            ->get();

    echo "RESIDUAL PATTERNS ANALYZED : "
        . $patterns->count()
        . PHP_EOL;

    $candidateStats = [];

    /*
    |--------------------------------------------------------------------------
    | Compare each pattern with resolved historical records
    |--------------------------------------------------------------------------
    */

    foreach ($patterns as $pattern) {

        $resolved =
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
                ->where('ts.province_id', $pattern->province_id)
                ->where('ts.trade_flow', $pattern->trade_flow)
                ->where('ts.country_id', $pattern->country_id)
                ->where('ts.hs_id', $pattern->hs_id)
                ->where('tp.is_active', 1)
                ->select(
                    'tp.id',
                    'tp.name',
                    'tp.code',
                    'tp.trade_point_type_id',
                    'tp.city'
                )
                ->selectRaw('COUNT(*) AS matched_records')
                ->groupBy(
                    'tp.id',
                    'tp.name',
                    'tp.code',
                    'tp.trade_point_type_id',
                    'tp.city'
                )
                ->orderByDesc('matched_records')
                ->get();

        $patternTotal =
            $resolved->sum(
                static function ($row) {
                    return (int) $row->matched_records;
                }
            );

        if ($patternTotal === 0) {
            continue;
        }

        foreach ($resolved as $candidate) {

            $key =
                (int) $candidate->id;

            if (!isset($candidateStats[$key])) {
                $candidateStats[$key] = [
                    'id' => $key,
                    'name' => $candidate->name,
                    'code' => $candidate->code,
                    'type' => $candidate->trade_point_type_id,
                    'city' => $candidate->city,
                    'matched_records' => 0,
                    'pattern_hits' => 0,
                ];
            }

            $candidateStats[$key]['matched_records']
                += (int) $candidate->matched_records;

            $candidateStats[$key]['pattern_hits']++;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Ranking
    |--------------------------------------------------------------------------
    */

    usort(
        $candidateStats,
        static function (array $a, array $b): int {
            return
                $b['matched_records']
                <=>
                $a['matched_records'];
        }
    );

    if (count($candidateStats) === 0) {

        echo PHP_EOL;
        echo "NO RESOLVED PATTERN MATCH FOUND.\n";
        echo PHP_EOL;

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Total matched patterns
    |--------------------------------------------------------------------------
    */

    $totalMatched =
        array_sum(
            array_column(
                $candidateStats,
                'matched_records'
            )
        );

    echo PHP_EOL;
    echo "TOP PATTERN-MATCH GATEWAYS:\n";

    foreach (
        array_slice($candidateStats, 0, 10)
        as $index => $candidate
    ) {

        $score =
            $totalMatched > 0
                ? (
                    $candidate['matched_records']
                    / $totalMatched
                    * 100
                )
                : 0;

        $classification =
            $score >= 90
                ? 'HIGH'
                : (
                    $score >= 70
                        ? 'MEDIUM'
                        : 'REVIEW'
                );

        echo sprintf(
            "  %02d. ID=%-4d | %-35s | TYPE=%-3d | CITY=%-18s | MATCH=%7d | SCORE=%6.2f%% | %s\n",
            $index + 1,
            $candidate['id'],
            trim((string) $candidate['name']),
            $candidate['type'],
            trim((string) ($candidate['city'] ?? '-')),
            $candidate['matched_records'],
            $score,
            $classification
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Strongest candidate
    |--------------------------------------------------------------------------
    */

    $top =
        $candidateStats[0];

    $topScore =
        $totalMatched > 0
            ? (
                $top['matched_records']
                / $totalMatched
                * 100
            )
            : 0;

    echo PHP_EOL;
    echo "STRONGEST CANDIDATE:\n";

    echo "  TRADE POINT ID : "
        . $top['id']
        . PHP_EOL;

    echo "  NAME           : "
        . $top['name']
        . PHP_EOL;

    echo "  SCORE          : "
        . number_format($topScore, 2)
        . "%\n";

    echo "  PATTERN HITS   : "
        . $top['pattern_hits']
        . PHP_EOL;

    echo "  MATCH RECORDS  : "
        . $top['matched_records']
        . PHP_EOL;

    echo "  CLASSIFICATION : "
        . (
            $topScore >= 90
                ? 'HIGH CONFIDENCE'
                : (
                    $topScore >= 70
                        ? 'MEDIUM CONFIDENCE'
                        : 'REVIEW'
                )
        )
        . PHP_EOL;

    echo PHP_EOL;
}

/*
|--------------------------------------------------------------------------
| Execute
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
echo "TRADE GATEWAY PATTERN MATCH : COMPLETE\n";
echo "========================================\n";