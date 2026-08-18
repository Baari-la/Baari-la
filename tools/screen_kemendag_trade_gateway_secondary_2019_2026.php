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
echo "DIGESTEX KEMENDAG SECONDARY GATEWAY SCREENING\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  OPERATION   : READ ONLY\n";
echo "  PURPOSE     : FIND STRONG GATEWAY CANDIDATES\n\n";

/*
|--------------------------------------------------------------------------
| Already deeply reviewed
|--------------------------------------------------------------------------
*/

$reviewedSources = [
    'JABABEKA',
    'JAKARTA / POS PASAR BARU (PTT)',
    'LOBAM',
    'BUATAN',
    'OBI ISLAND',
    'WEDA',
    'BENETE',
    'PERAWANG, SUMATRA',
    'SURABAYA (PTT)',
];

/*
|--------------------------------------------------------------------------
| Residual source universe
|--------------------------------------------------------------------------
*/

$sources = DB::table('trade_statistics')
    ->whereBetween('year', [2019, 2026])
    ->whereIn('trade_flow', ['export', 'import'])
    ->whereNull('trade_point_id')
    ->whereNotNull('port_name')
    ->where('port_name', '<>', '')
    ->whereNotIn('port_name', $reviewedSources)
    ->select('port_name')
    ->selectRaw('COUNT(*) AS records')
    ->selectRaw('SUM(trade_value) AS trade_value')
    ->selectRaw('COUNT(DISTINCT province_id) AS province_count')
    ->selectRaw('COUNT(DISTINCT country_id) AS country_count')
    ->selectRaw('COUNT(DISTINCT hs_id) AS hs_count')
    ->groupBy('port_name')
    ->orderByDesc('records')
    ->get();

echo "UNSCREENED SOURCES : {$sources->count()}\n\n";

$results = [];

foreach ($sources as $source) {

    $sourceName = trim((string) $source->port_name);

    /*
    |--------------------------------------------------------------------------
    | Dominant province
    |--------------------------------------------------------------------------
    */

    $province = DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('trade_point_id')
        ->where('port_name', $sourceName)
        ->whereNotNull('province_id')
        ->select(
            'province_id',
            'province_name'
        )
        ->selectRaw('COUNT(*) AS records')
        ->groupBy(
            'province_id',
            'province_name'
        )
        ->orderByDesc('records')
        ->first();

    if ($province === null) {
        continue;
    }

    $provinceTotal = (int) $source->records;

    $provinceConsistency =
        $provinceTotal > 0
            ? (
                (int) $province->records
                /
                $provinceTotal
                * 100
            )
            : 0;

    /*
    |--------------------------------------------------------------------------
    | Dominant flow
    |--------------------------------------------------------------------------
    */

    $flow = DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('trade_point_id')
        ->where('port_name', $sourceName)
        ->select('trade_flow')
        ->selectRaw('COUNT(*) AS records')
        ->groupBy('trade_flow')
        ->orderByDesc('records')
        ->first();

    if ($flow === null) {
        continue;
    }

    $flowConsistency =
        $provinceTotal > 0
            ? (
                (int) $flow->records
                /
                $provinceTotal
                * 100
            )
            : 0;

    /*
    |--------------------------------------------------------------------------
    | Active gateways in dominant province
    |--------------------------------------------------------------------------
    */

    $gatewayIds = DB::table('trade_points')
        ->where('is_active', 1)
        ->where('province_id', (int) $province->province_id)
        ->pluck('id')
        ->all();

    $gatewayCount = count($gatewayIds);

    /*
    |--------------------------------------------------------------------------
    | Historical exact-pattern screening
    |--------------------------------------------------------------------------
    |
    | To keep this screening fast, only inspect the most common
    | residual patterns representing up to 80% of records.
    |--------------------------------------------------------------------------
    */

    $patterns = DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('trade_point_id')
        ->where('port_name', $sourceName)
        ->where('province_id', (int) $province->province_id)
        ->whereNotNull('country_id')
        ->whereNotNull('hs_id')
        ->whereNotNull('month')
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
        ->orderByDesc('residual_records')
        ->limit(250)
        ->get();

    $sampledRecords = 0;

    $matchedRecords = 0;

    $dominantCandidateRecords = [];

    foreach ($gatewayIds as $gatewayId) {
        $dominantCandidateRecords[$gatewayId] = 0;
    }

    foreach ($patterns as $pattern) {

        $patternRecords =
            (int) $pattern->residual_records;

        $sampledRecords += $patternRecords;

        /*
        |--------------------------------------------------------------------------
        | Historical gateway distribution for exact pattern
        |--------------------------------------------------------------------------
        */

        $matches = DB::table('trade_statistics')
            ->whereBetween(
                'year',
                [2019, 2026]
            )
            ->whereIn(
                'trade_flow',
                ['export', 'import']
            )
            ->whereNotNull(
                'trade_point_id'
            )
            ->where(
                'province_id',
                (int) $province->province_id
            )
            ->where(
                'trade_flow',
                $pattern->trade_flow
            )
            ->where(
                'country_id',
                $pattern->country_id
            )
            ->where(
                'hs_id',
                $pattern->hs_id
            )
            ->where(
                'month',
                $pattern->month
            )
            ->whereIn(
                'trade_point_id',
                $gatewayIds
            )
            ->select(
                'trade_point_id'
            )
            ->selectRaw(
                'COUNT(*) AS records'
            )
            ->groupBy(
                'trade_point_id'
            )
            ->orderByDesc(
                'records'
            )
            ->get();

        if ($matches->isEmpty()) {
            continue;
        }

        $historicalTotal =
            $matches->sum(
                static function ($row) {
                    return (int) $row->records;
                }
            );

        if ($historicalTotal <= 0) {
            continue;
        }

        $matchedRecords += $patternRecords;

        $top = $matches->first();

        $topShare =
            (
                (int) $top->records
                /
                $historicalTotal
                * 100
            );

        if ($topShare >= 90) {

            $gatewayId =
                (int) $top->trade_point_id;

            $dominantCandidateRecords[$gatewayId]
                += $patternRecords;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Screening metrics
    |--------------------------------------------------------------------------
    */

    $sampleCoverage =
        $provinceTotal > 0
            ? (
                min(
                    $sampledRecords,
                    $provinceTotal
                )
                /
                $provinceTotal
                * 100
            )
            : 0;

    $matchedCoverage =
        $provinceTotal > 0
            ? (
                $matchedRecords
                /
                $provinceTotal
                * 100
            )
            : 0;

    $topGatewayId = null;
    $topGatewayRecords = 0;

    foreach ($dominantCandidateRecords as $gatewayId => $records) {

        if ($records > $topGatewayRecords) {

            $topGatewayId = $gatewayId;
            $topGatewayRecords = $records;
        }
    }

    $dominance =
        $matchedRecords > 0
            ? (
                $topGatewayRecords
                /
                $matchedRecords
                * 100
            )
            : 0;

    /*
    |--------------------------------------------------------------------------
    | Strong candidate condition
    |--------------------------------------------------------------------------
    */

    $strongCandidate =
        $matchedCoverage >= 70
        &&
        $dominance >= 85
        &&
        $topGatewayId !== null;

    if (!$strongCandidate) {
        continue;
    }

    $gateway = DB::table('trade_points')
        ->where('id', $topGatewayId)
        ->first([
            'id',
            'name',
            'code',
            'trade_point_type_id',
            'province_id',
            'city',
        ]);

    $results[] = [
        'source' => $sourceName,
        'records' => $provinceTotal,
        'province_id' => (int) $province->province_id,
        'province_name' => $province->province_name,
        'flow' => (string) $flow->trade_flow,
        'province_consistency' => $provinceConsistency,
        'flow_consistency' => $flowConsistency,
        'gateway_count' => $gatewayCount,
        'sample_coverage' => $sampleCoverage,
        'matched_coverage' => $matchedCoverage,
        'dominance' => $dominance,
        'gateway_id' => $gateway?->id,
        'gateway_name' => $gateway?->name,
        'gateway_type' => $gateway?->trade_point_type_id,
        'gateway_city' => $gateway?->city,
    ];
}

/*
|--------------------------------------------------------------------------
| Ranking
|--------------------------------------------------------------------------
*/

usort(
    $results,
    static function (
        array $a,
        array $b
    ): int {

        if (
            $a['dominance']
            !==
            $b['dominance']
        ) {
            return
                $b['dominance']
                <=>
                $a['dominance'];
        }

        return
            $b['records']
            <=>
            $a['records'];
    }
);

/*
|--------------------------------------------------------------------------
| Output
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "STRONG GATEWAY CANDIDATES\n";
echo "========================================\n";

if (empty($results)) {

    echo "  NONE\n";

} else {

    foreach (
        array_slice($results, 0, 20)
        as $index => $row
    ) {

        echo sprintf(
            "%02d. %-35s | %6d records | PROV=%6.2f%% | FLOW=%6.2f%% | MATCH=%6.2f%% | DOM=%6.2f%% | GW=%d | ID=%d | %s\n",
            $index + 1,
            $row['source'],
            $row['records'],
            $row['province_consistency'],
            $row['flow_consistency'],
            $row['matched_coverage'],
            $row['dominance'],
            $row['gateway_count'],
            $row['gateway_id'],
            $row['gateway_name']
        );
    }
}

echo PHP_EOL;

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
echo "SECONDARY GATEWAY SCREENING : COMPLETE\n";
echo "========================================\n";