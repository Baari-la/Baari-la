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
echo "DIGESTEX KEMENDAG SAM RATULANGI CONDITIONAL TRADE GATEWAY BACKFILL DRY-RUN\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  SOURCE      : SAM RATULANGI (U)\n";
echo "  CONDITION   : province_id=1 (BALI)\n";
echo "  CANDIDATE   : NGURAH RAI INTERNATIONAL AIRPORT\n";
echo "  TRADE POINT : ID=29\n";
echo "  OPERATION   : READ ONLY\n\n";

$sourceName = 'SAM RATULANGI (U)';
$sourceProvinceId = 1;
$candidateId = 29;

$expectedTotalSource = 114;
$expectedEligibleSource = 109;
$expectedDeterministic = 103;

$expectedNonBaliReview = 5;
$expectedUnmatchedEligible = 6;
$expectedTotalReview = 11;

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
    (string) $master->name === 'Ngurah Rai International Airport'
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
    throw new RuntimeException(
        'SAM RATULANGI candidate master validation failed.'
    );
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Global NULL baseline
|--------------------------------------------------------------------------
*/

$currentNull = DB::table('trade_statistics')
    ->whereBetween('year', [2019, 2026])
    ->whereIn('trade_flow', ['export', 'import'])
    ->whereNull('trade_point_id')
    ->count();

echo "========================================\n";
echo "GLOBAL NULL BASELINE\n";
echo "========================================\n";

echo "  CURRENT NULL TRADE POINT RECORDS : {$currentNull}\n\n";

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

$totalSource = (clone $sourceBase)->count();

echo "========================================\n";
echo "SOURCE BASELINE\n";
echo "========================================\n";

echo "  TOTAL SOURCE RECORDS : {$totalSource}\n";

if ($totalSource !== $expectedTotalSource) {
    throw new RuntimeException(
        "Expected {$expectedTotalSource} source records, got {$totalSource}."
    );
}

/*
|--------------------------------------------------------------------------
| Province split
|--------------------------------------------------------------------------
*/

$eligibleSource = (clone $sourceBase)
    ->where('province_id', $sourceProvinceId)
    ->count();

$nonBaliReview = $totalSource - $eligibleSource;

echo "  ELIGIBLE BALI        : {$eligibleSource}\n";
echo "  NON-BALI REVIEW      : {$nonBaliReview}\n";

if (
    $eligibleSource !== $expectedEligibleSource
    ||
    $nonBaliReview !== $expectedNonBaliReview
) {
    throw new RuntimeException(
        "Unexpected conditional scope. Expected eligible={$expectedEligibleSource}, non_bali_review={$expectedNonBaliReview}; got eligible={$eligibleSource}, non_bali_review={$nonBaliReview}."
    );
}

echo "  CONDITIONAL SCOPE    : PASS\n";
echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Exact eligible pattern universe
|--------------------------------------------------------------------------
*/

$patterns = (clone $sourceBase)
    ->where(
        'province_id',
        $sourceProvinceId
    )
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
    ->selectRaw(
        'COUNT(*) AS residual_records'
    )
    ->groupBy(
        'province_id',
        'trade_flow',
        'country_id',
        'hs_id',
        'month'
    )
    ->get();

$totalPatterns = $patterns->count();

/*
|--------------------------------------------------------------------------
| Deterministic resolution counters
|--------------------------------------------------------------------------
*/

$resolvedIds = [];

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
| Candidate validation per exact pattern
|--------------------------------------------------------------------------
*/

foreach ($patterns as $pattern) {

    $historicalMatches = DB::table('trade_statistics')
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
            $sourceProvinceId
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
        ->where(
            'trade_point_id',
            $candidateId
        )
        ->count();

    $otherGatewayIds = DB::table('trade_points')
        ->where('is_active', 1)
        ->where('province_id', $sourceProvinceId)
        ->where('id', '<>', $candidateId)
        ->pluck('id')
        ->all();

    $otherGatewayMatches = 0;

    if (!empty($otherGatewayIds)) {

        $otherGatewayMatches = DB::table('trade_statistics')
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
                $sourceProvinceId
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
                $otherGatewayIds
            )
            ->count();
    }

    $historicalTotal =
        $historicalMatches
        +
        $otherGatewayMatches;

    $patternRecords =
        (int) $pattern->residual_records;

    /*
    |--------------------------------------------------------------------------
    | No historical evidence
    |--------------------------------------------------------------------------
    */

    if ($historicalTotal === 0) {

        $unmatchedPatterns++;
        $unmatchedRecords += $patternRecords;

        continue;
    }

    $matchedPatterns++;
    $matchedRecords += $patternRecords;

    $candidateShare =
        $historicalMatches
        /
        $historicalTotal;

    /*
    |--------------------------------------------------------------------------
    | Deterministic threshold >= 90%
    |--------------------------------------------------------------------------
    */

    if ($candidateShare >= 0.90) {

        $eligiblePatterns++;
        $eligibleRecords += $patternRecords;

        $ids = (clone $sourceBase)
            ->where(
                'province_id',
                $sourceProvinceId
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
            ->pluck('id')
            ->all();

        foreach ($ids as $id) {
            $resolvedIds[] = (int) $id;
        }

    } else {

        $ambiguousPatterns++;
        $ambiguousRecords += $patternRecords;
    }
}

/*
|--------------------------------------------------------------------------
| Normalize deterministic IDs
|--------------------------------------------------------------------------
*/

$resolvedIds = array_values(
    array_unique($resolvedIds)
);

$resolvedCount = count($resolvedIds);

/*
|--------------------------------------------------------------------------
| Unmatched eligible = Bali eligible but not deterministic
|--------------------------------------------------------------------------
*/

$unmatchedEligibleRecords =
    $eligibleSource - $resolvedCount;

$failedCount =
    max(
        0,
        $eligibleRecords - $resolvedCount
    );

/*
|--------------------------------------------------------------------------
| Review total
|--------------------------------------------------------------------------
*/

$totalReviewExcluded =
    $nonBaliReview
    +
    $unmatchedEligibleRecords;

/*
|--------------------------------------------------------------------------
| Coverage
|--------------------------------------------------------------------------
*/

$matchedCoverage =
    $eligibleSource > 0
        ? (
            $matchedRecords
            /
            $eligibleSource
            * 100
        )
        : 0;

$deterministicCoverage =
    $eligibleSource > 0
        ? (
            $resolvedCount
            /
            $eligibleSource
            * 100
        )
        : 0;

/*
|--------------------------------------------------------------------------
| Expected deterministic count
|--------------------------------------------------------------------------
*/

if ($resolvedCount !== $expectedDeterministic) {
    throw new RuntimeException(
        "Unexpected deterministic resolved count. Expected {$expectedDeterministic}, got {$resolvedCount}."
    );
}

if ($unmatchedEligibleRecords !== $expectedUnmatchedEligible) {
    throw new RuntimeException(
        "Unexpected unmatched Bali count. Expected {$expectedUnmatchedEligible}, got {$unmatchedEligibleRecords}."
    );
}

if ($totalReviewExcluded !== $expectedTotalReview) {
    throw new RuntimeException(
        "Unexpected total review count. Expected {$expectedTotalReview}, got {$totalReviewExcluded}."
    );
}

/*
|--------------------------------------------------------------------------
| Backfill summary
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "BACKFILL SUMMARY\n";
echo "========================================\n";

echo "  NULL BEFORE         : {$currentNull}\n";
echo "  SOURCE TARGET       : {$totalSource}\n";
echo "  ELIGIBLE BALI       : {$eligibleSource}\n";
echo "  DETERMINISTIC       : {$resolvedCount}\n";
echo "  FAILED              : {$failedCount}\n";
echo "  WOULD UPDATE        : {$resolvedCount}\n";
echo "  NON-BALI REVIEW     : {$nonBaliReview}\n";
echo "  UNMATCHED BALI      : {$unmatchedEligibleRecords}\n";
echo "  REVIEW EXCLUDED     : {$totalReviewExcluded}\n";

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Pattern validation summary
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "PATTERN VALIDATION SUMMARY\n";
echo "========================================\n";

echo "  TOTAL PATTERNS       : {$totalPatterns}\n";
echo "  MATCHED PATTERNS     : {$matchedPatterns}\n";
echo "  ELIGIBLE PATTERNS    : {$eligiblePatterns}\n";
echo "  AMBIGUOUS PATTERNS   : {$ambiguousPatterns}\n";
echo "  UNMATCHED PATTERNS   : {$unmatchedPatterns}\n\n";

echo "  MATCHED RECORDS      : {$matchedRecords}\n";
echo "  ELIGIBLE RECORDS     : {$eligibleRecords}\n";
echo "  AMBIGUOUS RECORDS    : {$ambiguousRecords}\n";
echo "  UNMATCHED RECORDS    : {$unmatchedRecords}\n\n";

echo "  HISTORICAL MATCHED COVERAGE : "
    . number_format(
        $matchedCoverage,
        2
    )
    . "%\n";

echo "  DETERMINISTIC COVERAGE      : "
    . number_format(
        $deterministicCoverage,
        2
    )
    . "%\n";

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Deterministic target validation
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "DETERMINISTIC TARGET VALIDATION\n";
echo "========================================\n";

$targetSourceRows = DB::table('trade_statistics')
    ->whereBetween(
        'year',
        [2019, 2026]
    )
    ->whereIn(
        'trade_flow',
        ['export', 'import']
    )
    ->whereNull(
        'trade_point_id'
    )
    ->where(
        'port_name',
        $sourceName
    )
    ->where(
        'province_id',
        $sourceProvinceId
    )
    ->whereIn(
        'id',
        $resolvedIds
    )
    ->count();

$targetValidationPass =
    $targetSourceRows === $resolvedCount
    &&
    $resolvedCount === $expectedDeterministic;

echo "  EXPECTED DETERMINISTIC : {$expectedDeterministic}\n";
echo "  RESOLVED IDS           : {$resolvedCount}\n";
echo "  TARGET ROW CHECK       : {$targetSourceRows}\n";

echo "  TARGET VALIDATION      : "
    . ($targetValidationPass ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Review exclusion check
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "REVIEW EXCLUSION CHECK\n";
echo "========================================\n";

echo "  NON-BALI REVIEW        : {$nonBaliReview}\n";
echo "  UNMATCHED BALI REVIEW  : {$unmatchedEligibleRecords}\n";
echo "  TOTAL REVIEW EXCLUDED  : {$totalReviewExcluded}\n";
echo "  EXPECTED TOTAL REVIEW  : {$expectedTotalReview}\n";

$reviewExclusionPass =
    $nonBaliReview === $expectedNonBaliReview
    &&
    $unmatchedEligibleRecords === $expectedUnmatchedEligible
    &&
    $totalReviewExcluded === $expectedTotalReview;

echo "  REVIEW EXCLUSION       : "
    . ($reviewExclusionPass ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Integrity baseline
|--------------------------------------------------------------------------
*/

$integrityBefore = DB::table('trade_statistics')
    ->whereBetween(
        'year',
        [2019, 2026]
    )
    ->whereIn(
        'trade_flow',
        ['export', 'import']
    )
    ->selectRaw(
        'COUNT(*) AS records'
    )
    ->selectRaw(
        'COUNT(DISTINCT trade_identity) AS identities'
    )
    ->selectRaw(
        'SUM(trade_value) AS trade_value'
    )
    ->selectRaw(
        'SUM(trade_volume) AS trade_volume'
    )
    ->selectRaw(
        'SUM(CASE WHEN hs_code IS NULL THEN 1 ELSE 0 END) AS null_hs'
    )
    ->selectRaw(
        'SUM(CASE WHEN province_id IS NULL THEN 1 ELSE 0 END) AS null_province'
    )
    ->first();

echo "========================================\n";
echo "INTEGRITY BASELINE\n";
echo "========================================\n";

echo "  RECORDS        : {$integrityBefore->records}\n";
echo "  IDENTITIES     : {$integrityBefore->identities}\n";
echo "  TRADE VALUE    : {$integrityBefore->trade_value}\n";
echo "  TRADE VOLUME   : {$integrityBefore->trade_volume}\n";
echo "  NULL HS        : {$integrityBefore->null_hs}\n";
echo "  NULL PROVINCE  : {$integrityBefore->null_province}\n";

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Database safety
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "DATABASE SAFETY\n";
echo "========================================\n";

echo "  INSERT : NO\n";
echo "  UPDATE : NO\n";
echo "  DELETE : NO\n";
echo "  DATABASE WAS NOT MODIFIED.\n\n";

/*
|--------------------------------------------------------------------------
| Final dry-run gate
|--------------------------------------------------------------------------
*/

$validationPass =
    $masterPass
    &&
    $totalSource === $expectedTotalSource
    &&
    $eligibleSource === $expectedEligibleSource
    &&
    $nonBaliReview === $expectedNonBaliReview
    &&
    $resolvedCount === $expectedDeterministic
    &&
    $failedCount === 0
    &&
    $unmatchedEligibleRecords === $expectedUnmatchedEligible
    &&
    $totalReviewExcluded === $expectedTotalReview
    &&
    $targetValidationPass
    &&
    $reviewExclusionPass;

echo "========================================\n";
echo "FINAL SAM RATULANGI CONDITIONAL DRY-RUN GATE\n";
echo "========================================\n";

echo "  CURRENT NULL        : {$currentNull}\n";
echo "  SOURCE TARGET       : {$totalSource}\n";
echo "  ELIGIBLE BALI       : {$eligibleSource}\n";
echo "  DETERMINISTIC       : {$resolvedCount}\n";
echo "  FAILED              : {$failedCount}\n";
echo "  NON-BALI REVIEW     : {$nonBaliReview}\n";
echo "  UNMATCHED BALI      : {$unmatchedEligibleRecords}\n";
echo "  REVIEW EXCLUDED     : {$totalReviewExcluded}\n";
echo "  WOULD UPDATE        : {$resolvedCount}\n";

echo "  VALIDATION          : "
    . ($validationPass ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo PHP_EOL;

if ($validationPass) {

    echo "SAM RATULANGI CONDITIONAL BACKFILL DRY-RUN : PASS\n";

} else {

    echo "SAM RATULANGI CONDITIONAL BACKFILL DRY-RUN : REVIEW\n";
    exit(1);
}

echo "========================================\n";