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
echo "DIGESTEX KEMENDAG TRADE GATEWAY DETERMINISTIC VALIDATION\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  SOURCE      : JAKARTA / POS PASAR BARU (PTT)\n";
echo "  CANDIDATE   : SOEKARNO-HATTA INTERNATIONAL AIRPORT\n";
echo "  TRADE POINT : ID=36\n";
echo "  OPERATION   : READ ONLY\n\n";

$sourceName = 'JAKARTA / POS PASAR BARU (PTT)';
$candidateId = 36;
$expectedProvinceId = 6;

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
    (int) $master->id === 36
    &&
    (string) $master->name === 'Soekarno-Hatta International Airport'
    &&
    (int) $master->trade_point_type_id === 2
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

if (!$masterPass) {
    throw new RuntimeException(
        'Candidate Trade Point master validation failed.'
    );
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Residual source baseline
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

if ($totalResidual !== 18992) {
    throw new RuntimeException(
        "Expected 18992 residual records, got {$totalResidual}."
    );
}

$sourceProvince = (clone $sourceBase)
    ->select('province_id', 'province_name')
    ->selectRaw('COUNT(*) AS records')
    ->groupBy('province_id', 'province_name')
    ->orderByDesc('records')
    ->get();

echo "  PROVINCE DISTRIBUTION:\n";

foreach ($sourceProvince as $row) {
    echo sprintf(
        "    province_id=%-4s | %-25s | %6d records\n",
        $row->province_id ?? 'NULL',
        $row->province_name ?? 'NULL',
        $row->records
    );
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Exact deterministic pattern universe
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

$eligiblePatterns = 0;
$unmatchedPatterns = 0;
$ambiguousPatterns = 0;

$eligibleRecords = 0;
$unmatchedRecords = 0;
$ambiguousRecords = 0;

$yearStats = [];
$countryStats = [];
$hsStats = [];

/*
|--------------------------------------------------------------------------
| Pattern validation
|--------------------------------------------------------------------------
*/

foreach ($patterns as $pattern) {

    /*
    |--------------------------------------------------------------------------
    | Historical resolved distribution for exact pattern
    |--------------------------------------------------------------------------
    */

    $matches = DB::table('trade_statistics as ts')
        ->join(
            'trade_points as tp',
            'tp.id',
            '=',
            'ts.trade_point_id'
        )
        ->whereBetween('ts.year', [2019, 2026])
        ->whereIn('ts.trade_flow', ['export', 'import'])
        ->whereNotNull('ts.trade_point_id')
        ->where('ts.province_id', $pattern->province_id)
        ->where('ts.trade_flow', $pattern->trade_flow)
        ->where('ts.country_id', $pattern->country_id)
        ->where('ts.hs_id', $pattern->hs_id)
        ->where('ts.month', $pattern->month)
        ->where('tp.is_active', 1)
        ->select(
            'tp.id',
            'tp.name',
            'tp.trade_point_type_id'
        )
        ->selectRaw('COUNT(*) AS records')
        ->groupBy(
            'tp.id',
            'tp.name',
            'tp.trade_point_type_id'
        )
        ->orderByDesc('records')
        ->get();

    $patternRecords = (int) $pattern->residual_records;

    if ($matches->isEmpty()) {
        $unmatchedPatterns++;
        $unmatchedRecords += $patternRecords;
        continue;
    }

    /*
    |--------------------------------------------------------------------------
    | Determine whether candidate ID=36 is the unique dominant gateway
    |--------------------------------------------------------------------------
    */

    $historicalTotal = $matches->sum(
        static function ($row) {
            return (int) $row->records;
        }
    );

    $candidateHistorical = 0;

    foreach ($matches as $match) {
        if ((int) $match->id === $candidateId) {
            $candidateHistorical = (int) $match->records;
        }
    }

    if ($candidateHistorical === 0 || $historicalTotal === 0) {
        $unmatchedPatterns++;
        $unmatchedRecords += $patternRecords;
        continue;
    }

    $candidateShare =
        (
            $candidateHistorical
            /
            $historicalTotal
            *
            100
        );

    /*
    |--------------------------------------------------------------------------
    | Deterministic rule:
    | candidate must account for >=90% of historical pattern
    |--------------------------------------------------------------------------
    */

    if ($candidateShare >= 90.0) {

        $eligiblePatterns++;
        $eligibleRecords += $patternRecords;

        $yearStats[$pattern->trade_flow]['eligible_records'] =
            ($yearStats[$pattern->trade_flow]['eligible_records'] ?? 0)
            + $patternRecords;

    } else {

        $ambiguousPatterns++;
        $ambiguousRecords += $patternRecords;
    }

    /*
    |--------------------------------------------------------------------------
    | Country / HS tracking
    |--------------------------------------------------------------------------
    */

    $countryKey = (string) $pattern->country_id;
    $hsKey = (string) $pattern->hs_id;

    $countryStats[$countryKey] =
        ($countryStats[$countryKey] ?? 0)
        + $patternRecords;

    $hsStats[$hsKey] =
        ($hsStats[$hsKey] ?? 0)
        + $patternRecords;
}

echo "========================================\n";
echo "PATTERN VALIDATION SUMMARY\n";
echo "========================================\n";

echo "  TOTAL PATTERNS       : {$totalPatterns}\n";
echo "  ELIGIBLE PATTERNS    : {$eligiblePatterns}\n";
echo "  AMBIGUOUS PATTERNS   : {$ambiguousPatterns}\n";
echo "  UNMATCHED PATTERNS   : {$unmatchedPatterns}\n\n";

echo "  TOTAL SOURCE RECORDS : {$totalResidual}\n";
echo "  ELIGIBLE RECORDS     : {$eligibleRecords}\n";
echo "  AMBIGUOUS RECORDS    : {$ambiguousRecords}\n";
echo "  UNMATCHED RECORDS    : {$unmatchedRecords}\n";

$patternCoverage =
    $totalPatterns > 0
        ? (
            $eligiblePatterns
            /
            $totalPatterns
            * 100
        )
        : 0;

$recordCoverage =
    $totalResidual > 0
        ? (
            $eligibleRecords
            /
            $totalResidual
            * 100
        )
        : 0;

echo PHP_EOL;
echo "  ELIGIBLE PATTERN COVERAGE : "
    . number_format($patternCoverage, 2)
    . "%\n";

echo "  ELIGIBLE RECORD COVERAGE  : "
    . number_format($recordCoverage, 2)
    . "%\n";

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Province consistency
|--------------------------------------------------------------------------
*/

$provinceMismatch =
    (clone $sourceBase)
        ->where(function ($q) use ($expectedProvinceId) {
            $q->whereNull('province_id')
              ->orWhere('province_id', '<>', $expectedProvinceId);
        })
        ->count();

$provinceMatch =
    (clone $sourceBase)
        ->where('province_id', $expectedProvinceId)
        ->count();

echo "========================================\n";
echo "PROVINCE CONSISTENCY\n";
echo "========================================\n";

echo "  PROVINCE MATCH    : {$provinceMatch}\n";
echo "  PROVINCE MISMATCH : {$provinceMismatch}\n";

$provincePass =
    $provinceMatch === 18992
    &&
    $provinceMismatch === 0;

echo "  RESULT            : "
    . ($provincePass ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Trade flow consistency
|--------------------------------------------------------------------------
*/

$flowStats = (clone $sourceBase)
    ->select('trade_flow')
    ->selectRaw('COUNT(*) AS records')
    ->groupBy('trade_flow')
    ->get();

echo "========================================\n";
echo "TRADE FLOW CONSISTENCY\n";
echo "========================================\n";

foreach ($flowStats as $flow) {
    echo sprintf(
        "  %-7s : %6d records\n",
        strtoupper((string) $flow->trade_flow),
        $flow->records
    );
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Candidate gateway direct match
|--------------------------------------------------------------------------
*/

$directCandidateResolved =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->where('port_name', $sourceName)
        ->where('trade_point_id', $candidateId)
        ->count();

echo "========================================\n";
echo "DIRECT CANDIDATE CHECK\n";
echo "========================================\n";

echo "  SOURCE + CANDIDATE ID=36 : {$directCandidateResolved}\n";
echo "  EXPECTED                 : 0\n\n";

/*
|--------------------------------------------------------------------------
| Final classification
|--------------------------------------------------------------------------
*/

$highConfidence =
    $provincePass
    &&
    $eligibleRecords >= 0.80 * $totalResidual
    &&
    $eligiblePatterns >= 0.80 * $totalPatterns
    &&
    $ambiguousRecords === 0
    &&
    $unmatchedRecords === 0;

$mediumConfidence =
    !$highConfidence
    &&
    $provincePass
    &&
    $eligibleRecords >= 0.50 * $totalResidual;

$classification =
    $highConfidence
        ? 'HIGH CONFIDENCE'
        : (
            $mediumConfidence
                ? 'MEDIUM CONFIDENCE'
                : 'REVIEW'
        );

echo "========================================\n";
echo "DETERMINISTIC VALIDATION GATE\n";
echo "========================================\n";

echo "  MASTER VALIDATION     : PASS\n";
echo "  PROVINCE CONSISTENCY  : "
    . ($provincePass ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo "  ELIGIBLE RECORDS      : {$eligibleRecords}\n";
echo "  AMBIGUOUS RECORDS     : {$ambiguousRecords}\n";
echo "  UNMATCHED RECORDS     : {$unmatchedRecords}\n";

echo "  RECORD COVERAGE       : "
    . number_format($recordCoverage, 2)
    . "%\n";

echo "  CLASSIFICATION        : {$classification}\n";

echo PHP_EOL;

echo "  DATABASE SAFETY:\n";
echo "    INSERT : NO\n";
echo "    UPDATE : NO\n";
echo "    DELETE : NO\n";
echo "    DATABASE WAS NOT MODIFIED.\n";

echo "========================================\n";
echo "POS PASAR BARU GATEWAY VALIDATION : COMPLETE\n";
echo "========================================\n";