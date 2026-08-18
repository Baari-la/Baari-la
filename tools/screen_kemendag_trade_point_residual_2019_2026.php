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
echo "DIGESTEX KEMENDAG RESIDUAL TRADE POINT SCREENING\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  OPERATION   : READ ONLY\n";
echo "  METHOD      : AUTOMATED PRIORITY SCREENING\n\n";

$excludedSources = [
    'JABABEKA',
    'JAKARTA / POS PASAR BARU (PTT)',
    'LOBAM',
    'BUATAN',
];

/*
|--------------------------------------------------------------------------
| Global residual baseline
|--------------------------------------------------------------------------
*/

$totalResidual = DB::table('trade_statistics')
    ->whereBetween('year', [2019, 2026])
    ->whereIn('trade_flow', ['export', 'import'])
    ->whereNull('trade_point_id')
    ->count();

echo "CURRENT NULL TRADE POINT RECORDS : {$totalResidual}\n\n";

/*
|--------------------------------------------------------------------------
| Residual source groups
|--------------------------------------------------------------------------
*/

$sources = DB::table('trade_statistics')
    ->whereBetween('year', [2019, 2026])
    ->whereIn('trade_flow', ['export', 'import'])
    ->whereNull('trade_point_id')
    ->whereNotNull('port_name')
    ->where('port_name', '<>', '')
    ->select('port_name')
    ->selectRaw('COUNT(*) AS records')
    ->selectRaw('COUNT(DISTINCT province_id) AS province_count')
    ->selectRaw('COUNT(DISTINCT country_id) AS country_count')
    ->selectRaw('COUNT(DISTINCT hs_id) AS hs_count')
    ->selectRaw('COUNT(DISTINCT trade_flow) AS flow_count')
    ->selectRaw('SUM(trade_value) AS trade_value')
    ->selectRaw('SUM(trade_volume) AS trade_volume')
    ->groupBy('port_name')
    ->orderByDesc('records')
    ->get();

echo "DISTINCT RESIDUAL SOURCES : " . $sources->count() . "\n\n";

/*
|--------------------------------------------------------------------------
| Screening
|--------------------------------------------------------------------------
*/

$results = [];

foreach ($sources as $source) {

    $name = trim((string) $source->port_name);

    /*
    |--------------------------------------------------------------------------
    | Already deeply reviewed
    |--------------------------------------------------------------------------
    */

    if (in_array($name, $excludedSources, true)) {
        continue;
    }

    /*
    |--------------------------------------------------------------------------
    | Dominant province
    |--------------------------------------------------------------------------
    */

    $province = DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('trade_point_id')
        ->where('port_name', $name)
        ->whereNotNull('province_id')
        ->select('province_id', 'province_name')
        ->selectRaw('COUNT(*) AS records')
        ->groupBy('province_id', 'province_name')
        ->orderByDesc('records')
        ->first();

    $dominantProvinceRecords =
        $province?->records
            ? (int) $province->records
            : 0;

    $provinceConsistency =
        $source->records > 0
            ? (
                $dominantProvinceRecords
                / $source->records
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
        ->where('port_name', $name)
        ->select('trade_flow')
        ->selectRaw('COUNT(*) AS records')
        ->groupBy('trade_flow')
        ->orderByDesc('records')
        ->first();

    $dominantFlowRecords =
        $flow?->records
            ? (int) $flow->records
            : 0;

    $flowConsistency =
        $source->records > 0
            ? (
                $dominantFlowRecords
                / $source->records
                * 100
            )
            : 0;

    /*
    |--------------------------------------------------------------------------
    | Candidate gateways in dominant province
    |--------------------------------------------------------------------------
    */

    $gatewayCount = 0;

    if ($province !== null) {

        $gatewayCount = DB::table('trade_points')
            ->where('is_active', 1)
            ->where('province_id', (int) $province->province_id)
            ->count();
    }

    /*
    |--------------------------------------------------------------------------
    | Simple screening score
    |--------------------------------------------------------------------------
    |
    | Weight:
    |   40% source volume
    |   25% province consistency
    |   20% flow consistency
    |   15% gateway availability
    |--------------------------------------------------------------------------
    */

    $volumeScore = min(
        100,
        log10(max(1, (int) $source->records)) / 5 * 100
    );

    $gatewayScore =
        $gatewayCount > 0
            ? 100
            : 0;

    $score =
        (
            $volumeScore * 0.40
        )
        +
        (
            $provinceConsistency * 0.25
        )
        +
        (
            $flowConsistency * 0.20
        )
        +
        (
            $gatewayScore * 0.15
        );

    /*
    |--------------------------------------------------------------------------
    | Priority classification
    |--------------------------------------------------------------------------
    */

    $priority = 'LOW / REVIEW';

    if (
        $score >= 75
        &&
        $provinceConsistency >= 90
        &&
        $flowConsistency >= 90
        &&
        $gatewayCount > 0
    ) {
        $priority = 'HIGH PRIORITY';

    } elseif (
        $score >= 55
        &&
        $provinceConsistency >= 75
        &&
        $gatewayCount > 0
    ) {
        $priority = 'MEDIUM PRIORITY';
    }

    $results[] = [
        'source' => $name,
        'records' => (int) $source->records,
        'trade_value' => (float) $source->trade_value,
        'province' => $province?->province_name,
        'province_id' => $province?->province_id,
        'province_consistency' => $provinceConsistency,
        'flow' => $flow?->trade_flow,
        'flow_consistency' => $flowConsistency,
        'country_count' => (int) $source->country_count,
        'hs_count' => (int) $source->hs_count,
        'gateway_count' => $gatewayCount,
        'score' => $score,
        'priority' => $priority,
    ];
}

/*
|--------------------------------------------------------------------------
| Ranking
|--------------------------------------------------------------------------
*/

usort(
    $results,
    static function (array $a, array $b): int {

        if ($a['priority'] !== $b['priority']) {

            $rank = [
                'HIGH PRIORITY' => 1,
                'MEDIUM PRIORITY' => 2,
                'LOW / REVIEW' => 3,
            ];

            return
                $rank[$a['priority']]
                <=>
                $rank[$b['priority']];
        }

        if ($a['records'] !== $b['records']) {
            return
                $b['records']
                <=>
                $a['records'];
        }

        return
            $b['score']
            <=>
            $a['score'];
    }
);

/*
|--------------------------------------------------------------------------
| Summary counts
|--------------------------------------------------------------------------
*/

$high = 0;
$medium = 0;
$low = 0;

foreach ($results as $row) {

    if ($row['priority'] === 'HIGH PRIORITY') {
        $high++;
    } elseif ($row['priority'] === 'MEDIUM PRIORITY') {
        $medium++;
    } else {
        $low++;
    }
}

echo "========================================\n";
echo "SCREENING SUMMARY\n";
echo "========================================\n";

echo "  EXCLUDED / ALREADY REVIEWED : "
    . count($excludedSources)
    . PHP_EOL;

echo "  HIGH PRIORITY               : {$high}\n";
echo "  MEDIUM PRIORITY             : {$medium}\n";
echo "  LOW / REVIEW                : {$low}\n";

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Top 30
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "TOP RESIDUAL SCREENING RESULTS\n";
echo "========================================\n";

foreach (
    array_slice($results, 0, 30)
    as $index => $row
) {

    echo sprintf(
        "%02d. %-35s | %6d | PROV=%6.2f%% | FLOW=%6.2f%% | GW=%2d | SCORE=%6.2f | %s\n",
        $index + 1,
        $row['source'],
        $row['records'],
        $row['province_consistency'],
        $row['flow_consistency'],
        $row['gateway_count'],
        $row['score'],
        $row['priority']
    );
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| High priority only
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "HIGH PRIORITY CANDIDATES\n";
echo "========================================\n";

$highCandidates = array_values(
    array_filter(
        $results,
        static function (array $row): bool {
            return $row['priority'] === 'HIGH PRIORITY';
        }
    )
);

if (empty($highCandidates)) {

    echo "  NONE\n";

} else {

    foreach ($highCandidates as $index => $row) {

        echo sprintf(
            "%02d. %s | %d records | province=%s (%d) | flow=%s | score=%.2f\n",
            $index + 1,
            $row['source'],
            $row['records'],
            $row['province'],
            $row['province_id'],
            strtoupper((string) $row['flow']),
            $row['score']
        );
    }
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Medium priority
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "MEDIUM PRIORITY CANDIDATES\n";
echo "========================================\n";

$mediumCandidates = array_values(
    array_filter(
        $results,
        static function (array $row): bool {
            return $row['priority'] === 'MEDIUM PRIORITY';
        }
    )
);

if (empty($mediumCandidates)) {

    echo "  NONE\n";

} else {

    foreach (
        array_slice($mediumCandidates, 0, 20)
        as $index => $row
    ) {

        echo sprintf(
            "%02d. %s | %d records | province=%s | flow=%s | score=%.2f\n",
            $index + 1,
            $row['source'],
            $row['records'],
            $row['province'],
            strtoupper((string) $row['flow']),
            $row['score']
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
echo "RESIDUAL TRADE POINT SCREENING : COMPLETE\n";
echo "========================================\n";