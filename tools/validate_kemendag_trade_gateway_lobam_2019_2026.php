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
echo "DIGESTEX KEMENDAG LOBAM TRADE GATEWAY VALIDATION\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  SOURCE      : LOBAM\n";
echo "  SOURCE PROV : 17 - KEPULAUAN RIAU\n";
echo "  OPERATION   : READ ONLY\n\n";

$sourceName = 'LOBAM';
$sourceProvinceId = 17;

$candidateIds = [
    8,   // Batu Ampar
    34,  // Sekupang
    45,  // Tanjung Balai Karimun
    23,  // Kijang
    49,  // Tanjung Uban
    55,  // Tanjung Pinang
];

/*
|--------------------------------------------------------------------------
| Master validation
|--------------------------------------------------------------------------
*/

$masters = DB::table('trade_points')
    ->whereIn('id', $candidateIds)
    ->where('is_active', 1)
    ->select(
        'id',
        'name',
        'code',
        'trade_point_type_id',
        'province_id',
        'city',
        'is_active'
    )
    ->orderBy('id')
    ->get();

echo "========================================\n";
echo "MASTER VALIDATION\n";
echo "========================================\n";

$masterPass = true;

foreach ($candidateIds as $id) {

    $master = $masters->first(
        static fn ($row) =>
            (int) $row->id === $id
    );

    $pass =
        $master !== null
        &&
        (int) $master->province_id === $sourceProvinceId
        &&
        (int) $master->trade_point_type_id === 1
        &&
        (int) $master->is_active === 1;

    echo sprintf(
        "  ID=%-3d | %-35s | province=%-3s | city=%-18s | %s\n",
        $id,
        $master?->name ?? 'NULL',
        $master?->province_id ?? 'NULL',
        $master?->city ?? 'NULL',
        $pass ? 'PASS' : 'FAIL'
    );

    if (!$pass) {
        $masterPass = false;
    }
}

if (!$masterPass) {
    throw new \RuntimeException(
        'One or more LOBAM candidate masters failed validation.'
    );
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Source baseline
|--------------------------------------------------------------------------
*/

$sourceBase = DB::table('trade_statistics')
    ->whereBetween('year', [2019, 2026])
    ->whereIn('trade_flow', ['export', 'import'])
    ->whereNull('trade_point_id')
    ->where('port_name', $sourceName);

$totalResidual = (clone $sourceBase)->count();

echo "========================================\n";
echo "SOURCE BASELINE\n";
echo "========================================\n";

echo "  RESIDUAL RECORDS : {$totalResidual}\n";

if ($totalResidual !== 2651) {
    throw new \RuntimeException(
        "Expected 2651 LOBAM residual records, got {$totalResidual}."
    );
}

$provinceMismatch = (clone $sourceBase)
    ->where(function ($q) use ($sourceProvinceId) {
        $q->whereNull('province_id')
          ->orWhere('province_id', '<>', $sourceProvinceId);
    })
    ->count();

$provinceMatch = (clone $sourceBase)
    ->where('province_id', $sourceProvinceId)
    ->count();

echo "  PROVINCE MATCH    : {$provinceMatch}\n";
echo "  PROVINCE MISMATCH : {$provinceMismatch}\n";

$provincePass =
    $provinceMatch === $totalResidual
    &&
    $provinceMismatch === 0;

echo "  PROVINCE RESULT    : "
    . ($provincePass ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Flow distribution
|--------------------------------------------------------------------------
*/

$flows = (clone $sourceBase)
    ->select('trade_flow')
    ->selectRaw('COUNT(*) AS records')
    ->selectRaw('SUM(trade_value) AS trade_value')
    ->selectRaw('SUM(trade_volume) AS trade_volume')
    ->groupBy('trade_flow')
    ->orderBy('trade_flow')
    ->get();

echo "========================================\n";
echo "TRADE FLOW DISTRIBUTION\n";
echo "========================================\n";

foreach ($flows as $flow) {

    echo sprintf(
        "  %-7s | %6d records | VALUE=%15.3f | VOLUME=%15.3f\n",
        strtoupper((string) $flow->trade_flow),
        $flow->records,
        $flow->trade_value,
        $flow->trade_volume
    );
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Exact residual pattern universe
|--------------------------------------------------------------------------
*/

$patterns = (clone $sourceBase)
    ->whereNotNull('province_id')
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
    ->get();

$totalPatterns = $patterns->count();

$matchedPatterns = 0;
$eligiblePatterns = 0;
$ambiguousPatterns = 0;
$unmatchedPatterns = 0;

$matchedRecords = 0;
$eligibleRecords = 0;
$ambiguousRecords = 0;
$unmatchedRecords = 0;

$candidateWeighted = [];

foreach ($candidateIds as $id) {
    $candidateWeighted[$id] = [
        'records' => 0.0,
        'patterns' => 0,
        'dominant_patterns' => 0,
    ];
}

/*
|--------------------------------------------------------------------------
| Pattern validation
|--------------------------------------------------------------------------
*/

foreach ($patterns as $pattern) {

    $matches = DB::table('trade_statistics as ts')
        ->whereBetween('ts.year', [2019, 2026])
        ->whereIn('ts.trade_flow', ['export', 'import'])
        ->whereNotNull('ts.trade_point_id')
        ->where('ts.province_id', $sourceProvinceId)
        ->where('ts.trade_flow', $pattern->trade_flow)
        ->where('ts.country_id', $pattern->country_id)
        ->where('ts.hs_id', $pattern->hs_id)
        ->where('ts.month', $pattern->month)
        ->whereIn('ts.trade_point_id', $candidateIds)
        ->select(
            'ts.trade_point_id'
        )
        ->selectRaw('COUNT(*) AS records')
        ->groupBy('ts.trade_point_id')
        ->orderByDesc('records')
        ->get();

    $patternResidual =
        (int) $pattern->residual_records;

    if ($matches->isEmpty()) {

        $unmatchedPatterns++;
        $unmatchedRecords += $patternResidual;

        continue;
    }

    $matchedPatterns++;

    $historicalTotal =
        $matches->sum(
            static fn ($row) =>
                (int) $row->records
        );

    if ($historicalTotal <= 0) {

        $unmatchedPatterns++;
        $unmatchedRecords += $patternResidual;

        continue;
    }

    $candidateShares = [];

    foreach ($matches as $match) {

        $gatewayId = (int) $match->trade_point_id;

        $share =
            (
                (int) $match->records
                /
                $historicalTotal
            );

        $candidateShares[$gatewayId] = $share;

        if (!isset($candidateWeighted[$gatewayId])) {
            continue;
        }

        $candidateWeighted[$gatewayId]['records'] +=
            $patternResidual * $share;

        $candidateWeighted[$gatewayId]['patterns']++;
    }

    arsort($candidateShares);

    $topGatewayId =
        array_key_first($candidateShares);

    $topShare =
        $candidateShares[$topGatewayId] ?? 0;

    if ($topShare >= 0.90) {

        $eligiblePatterns++;
        $eligibleRecords += $patternResidual;

        if (isset($candidateWeighted[$topGatewayId])) {
            $candidateWeighted[$topGatewayId]['dominant_patterns']++;
        }

    } else {

        $ambiguousPatterns++;
        $ambiguousRecords += $patternResidual;
    }

    $matchedRecords += $patternResidual;
}

/*
|--------------------------------------------------------------------------
| Summary
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "DETERMINISTIC PATTERN VALIDATION\n";
echo "========================================\n";

echo "  TOTAL PATTERNS       : {$totalPatterns}\n";
echo "  MATCHED PATTERNS     : {$matchedPatterns}\n";
echo "  ELIGIBLE PATTERNS    : {$eligiblePatterns}\n";
echo "  AMBIGUOUS PATTERNS   : {$ambiguousPatterns}\n";
echo "  UNMATCHED PATTERNS   : {$unmatchedPatterns}\n\n";

echo "  TOTAL RECORDS        : {$totalResidual}\n";
echo "  MATCHED RECORDS      : {$matchedRecords}\n";
echo "  ELIGIBLE RECORDS     : {$eligibleRecords}\n";
echo "  AMBIGUOUS RECORDS    : {$ambiguousRecords}\n";
echo "  UNMATCHED RECORDS    : {$unmatchedRecords}\n";

$matchedCoverage =
    $totalResidual > 0
        ? (
            $matchedRecords
            / $totalResidual
            * 100
        )
        : 0;

$eligibleCoverage =
    $totalResidual > 0
        ? (
            $eligibleRecords
            / $totalResidual
            * 100
        )
        : 0;

echo PHP_EOL;

echo "  MATCHED COVERAGE     : "
    . number_format($matchedCoverage, 2)
    . "%\n";

echo "  ELIGIBLE COVERAGE    : "
    . number_format($eligibleCoverage, 2)
    . "%\n";

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Candidate ranking
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "CANDIDATE GATEWAY RANKING\n";
echo "========================================\n";

$ranked = [];

foreach ($candidateWeighted as $id => $stats) {

    $score =
        $matchedRecords > 0
            ? (
                $stats['records']
                / $matchedRecords
                * 100
            )
            : 0;

    $ranked[$id] = [
        'id' => $id,
        'records' => $stats['records'],
        'patterns' => $stats['patterns'],
        'dominant_patterns' =>
            $stats['dominant_patterns'],
        'score' => $score,
    ];
}

usort(
    $ranked,
    static function (array $a, array $b): int {
        return
            $b['records']
            <=>
            $a['records'];
    }
);

foreach ($ranked as $index => $candidate) {

    $master =
        $masters->first(
            static fn ($row) =>
                (int) $row->id ===
                $candidate['id']
        );

    echo sprintf(
        "  %02d. ID=%-3d | %-35s | WEIGHT=%9.2f | SCORE=%6.2f%% | PATTERNS=%4d | DOMINANT=%4d\n",
        $index + 1,
        $candidate['id'],
        $master?->name ?? 'NULL',
        $candidate['records'],
        $candidate['score'],
        $candidate['patterns'],
        $candidate['dominant_patterns']
    );
}

/*
|--------------------------------------------------------------------------
| Candidate classification
|--------------------------------------------------------------------------
*/

$top =
    $ranked[0] ?? null;

$topClassification = 'REVIEW';

if ($top !== null) {

    $topScore =
        (float) $top['score'];

    /*
    | Conservative rule:
    | - province must be perfect
    | - matched coverage >= 80%
    | - top gateway dominance >= 90%
    | - eligible coverage >= 80%
    */

    if (
        $provincePass
        &&
        $matchedCoverage >= 80
        &&
        $topScore >= 90
        &&
        $eligibleCoverage >= 80
    ) {
        $topClassification =
            'HIGH CONFIDENCE';
    } elseif (
        $provincePass
        &&
        $matchedCoverage >= 50
        &&
        $topScore >= 75
        &&
        $eligibleCoverage >= 50
    ) {
        $topClassification =
            'MEDIUM CONFIDENCE';
    }
}

echo PHP_EOL;
echo "========================================\n";
echo "FINAL LOBAM GATE\n";
echo "========================================\n";

echo "  MASTER VALIDATION    : "
    . ($masterPass ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo "  PROVINCE VALIDATION  : "
    . ($provincePass ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo "  MATCHED COVERAGE     : "
    . number_format($matchedCoverage, 2)
    . "%\n";

echo "  ELIGIBLE COVERAGE    : "
    . number_format($eligibleCoverage, 2)
    . "%\n";

echo "  CLASSIFICATION       : "
    . $topClassification
    . PHP_EOL;

echo PHP_EOL;
echo "DATABASE SAFETY:\n";
echo "  INSERT : NO\n";
echo "  UPDATE : NO\n";
echo "  DELETE : NO\n";
echo "  DATABASE WAS NOT MODIFIED.\n";

echo "========================================\n";
echo "LOBAM TRADE GATEWAY VALIDATION : COMPLETE\n";
echo "========================================\n";