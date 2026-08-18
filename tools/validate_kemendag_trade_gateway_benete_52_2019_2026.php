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
echo "DIGESTEX KEMENDAG BENETE GATEWAY VALIDATION\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  SOURCE      : BENETE\n";
echo "  SOURCE PROV : 22 - NUSA TENGGARA BARAT\n";
echo "  CANDIDATE   : LOMBOK INTERNATIONAL AIRPORT\n";
echo "  TRADE POINT : ID=52\n";
echo "  OPERATION   : READ ONLY\n\n";

$sourceName = 'BENETE';
$sourceProvinceId = 22;
$candidateId = 52;
$expectedRecords = 196;

/*
|--------------------------------------------------------------------------
| Candidate master validation
|--------------------------------------------------------------------------
*/

$master = DB::table('trade_points')
    ->where('id', $candidateId)
    ->where('is_active', 1)
    ->first([
        'id',
        'code',
        'name',
        'name_en',
        'trade_point_type_id',
        'province_id',
        'city',
        'is_active',
    ]);

$masterPass =
    $master !== null
    &&
    (int) $master->id === $candidateId
    &&
    (string) $master->name === 'Lombok International Airport'
    &&
    (int) $master->trade_point_type_id === 2
    &&
    (int) $master->province_id === $sourceProvinceId
    &&
    (int) $master->is_active === 1;

echo "========================================\n";
echo "MASTER VALIDATION\n";
echo "========================================\n";

echo "  CANDIDATE : "
    . ($masterPass ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo "  ID        : "
    . ($master?->id ?? 'NULL')
    . PHP_EOL;

echo "  NAME      : "
    . ($master?->name ?? 'NULL')
    . PHP_EOL;

echo "  TYPE      : "
    . ($master?->trade_point_type_id ?? 'NULL')
    . PHP_EOL;

echo "  PROVINCE  : "
    . ($master?->province_id ?? 'NULL')
    . PHP_EOL;

echo "  CITY      : "
    . ($master?->city ?? 'NULL')
    . PHP_EOL;

if (!$masterPass) {
    throw new \RuntimeException(
        'BENETE candidate master validation failed.'
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

if ($totalResidual !== $expectedRecords) {
    throw new \RuntimeException(
        "Expected {$expectedRecords} BENETE residual records, got {$totalResidual}."
    );
}

$provinceMatch = (clone $sourceBase)
    ->where('province_id', $sourceProvinceId)
    ->count();

$provinceMismatch = (clone $sourceBase)
    ->where(function ($q) use ($sourceProvinceId) {
        $q->whereNull('province_id')
          ->orWhere('province_id', '<>', $sourceProvinceId);
    })
    ->count();

echo "  PROVINCE MATCH    : {$provinceMatch}\n";
echo "  PROVINCE MISMATCH : {$provinceMismatch}\n";

$provincePass =
    $provinceMatch === $totalResidual
    &&
    $provinceMismatch === 0;

echo "  PROVINCE RESULT   : "
    . ($provincePass ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Trade flow distribution
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

/*
|--------------------------------------------------------------------------
| Pattern validation
|--------------------------------------------------------------------------
*/

foreach ($patterns as $pattern) {

    $historicalMatches = DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNotNull('trade_point_id')
        ->where('province_id', $sourceProvinceId)
        ->where('trade_flow', $pattern->trade_flow)
        ->where('country_id', $pattern->country_id)
        ->where('hs_id', $pattern->hs_id)
        ->where('month', $pattern->month)
        ->where('trade_point_id', $candidateId)
        ->count();

    $otherGatewayMatches = DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNotNull('trade_point_id')
        ->where('province_id', $sourceProvinceId)
        ->where('trade_flow', $pattern->trade_flow)
        ->where('country_id', $pattern->country_id)
        ->where('hs_id', $pattern->hs_id)
        ->where('month', $pattern->month)
        ->where('trade_point_id', '<>', $candidateId)
        ->whereIn(
            'trade_point_id',
            DB::table('trade_points')
                ->where('is_active', 1)
                ->where('province_id', $sourceProvinceId)
                ->pluck('id')
                ->all()
        )
        ->count();

    $historicalTotal =
        $historicalMatches
        + $otherGatewayMatches;

    $patternRecords =
        (int) $pattern->residual_records;

    if ($historicalTotal === 0) {
        $unmatchedPatterns++;
        $unmatchedRecords += $patternRecords;
        continue;
    }

    $matchedPatterns++;
    $matchedRecords += $patternRecords;

    $candidateShare =
        $historicalMatches / $historicalTotal;

    if ($candidateShare >= 0.90) {
        $eligiblePatterns++;
        $eligibleRecords += $patternRecords;
    } else {
        $ambiguousPatterns++;
        $ambiguousRecords += $patternRecords;
    }
}

/*
|--------------------------------------------------------------------------
| Coverage calculations
|--------------------------------------------------------------------------
*/

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

/*
|--------------------------------------------------------------------------
| Pattern summary
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
echo "  UNMATCHED RECORDS    : {$unmatchedRecords}\n\n";

echo "  MATCHED COVERAGE     : "
    . number_format(
        $matchedCoverage,
        2
    )
    . "%\n";

echo "  ELIGIBLE COVERAGE    : "
    . number_format(
        $eligibleCoverage,
        2
    )
    . "%\n";

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Direct candidate check
|--------------------------------------------------------------------------
*/

$directCandidateResolved = (clone $sourceBase)
    ->where('trade_point_id', $candidateId)
    ->count();

echo "========================================\n";
echo "DIRECT CANDIDATE CHECK\n";
echo "========================================\n";

echo "  SOURCE + CANDIDATE ID=52 : {$directCandidateResolved}\n";
echo "  EXPECTED                 : 0\n\n";

/*
|--------------------------------------------------------------------------
| Final classification
|--------------------------------------------------------------------------
*/

$classification = 'REVIEW';

if (
    $provincePass
    &&
    $eligibleCoverage >= 80
    &&
    $ambiguousRecords === 0
    &&
    $unmatchedRecords === 0
) {
    $classification = 'HIGH CONFIDENCE';

} elseif (
    $provincePass
    &&
    $eligibleCoverage >= 50
) {
    $classification = 'MEDIUM CONFIDENCE';
}

echo "========================================\n";
echo "FINAL BENETE GATE\n";
echo "========================================\n";

echo "  MASTER VALIDATION    : "
    . ($masterPass ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo "  PROVINCE VALIDATION  : "
    . ($provincePass ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo "  MATCHED COVERAGE     : "
    . number_format(
        $matchedCoverage,
        2
    )
    . "%\n";

echo "  ELIGIBLE COVERAGE    : "
    . number_format(
        $eligibleCoverage,
        2
    )
    . "%\n";

echo "  AMBIGUOUS RECORDS    : {$ambiguousRecords}\n";
echo "  UNMATCHED RECORDS    : {$unmatchedRecords}\n";

echo "  CLASSIFICATION       : "
    . $classification
    . PHP_EOL;

echo PHP_EOL;

echo "DATABASE SAFETY:\n";
echo "  INSERT : NO\n";
echo "  UPDATE : NO\n";
echo "  DELETE : NO\n";
echo "  DATABASE WAS NOT MODIFIED.\n";

echo "========================================\n";
echo "BENETE TRADE GATEWAY VALIDATION : COMPLETE\n";
echo "========================================\n";