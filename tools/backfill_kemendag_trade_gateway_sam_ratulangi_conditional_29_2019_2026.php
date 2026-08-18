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
echo "DIGESTEX KEMENDAG SAM RATULANGI CONDITIONAL TRADE GATEWAY BACKFILL\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  SOURCE      : SAM RATULANGI (U)\n";
echo "  CONDITION   : province_id=1 (BALI)\n";
echo "  CANDIDATE   : NGURAH RAI INTERNATIONAL AIRPORT\n";
echo "  TRADE POINT : ID=29\n";
echo "  OPERATION   : TRANSACTIONAL BACKFILL\n\n";

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
| Baseline before transaction
|--------------------------------------------------------------------------
*/

$currentNullBefore = DB::table('trade_statistics')
    ->whereBetween('year', [2019, 2026])
    ->whereIn('trade_flow', ['export', 'import'])
    ->whereNull('trade_point_id')
    ->count();

$sourceBase = DB::table('trade_statistics')
    ->whereBetween('year', [2019, 2026])
    ->whereIn('trade_flow', ['export', 'import'])
    ->whereNull('trade_point_id')
    ->where('port_name', $sourceName);

$totalSource = (clone $sourceBase)->count();

$eligibleSource = (clone $sourceBase)
    ->where('province_id', $sourceProvinceId)
    ->count();

$nonBaliReview = $totalSource - $eligibleSource;

echo "========================================\n";
echo "PRE-BACKFILL BASELINE\n";
echo "========================================\n";

echo "  NULL TRADE POINT ID : {$currentNullBefore}\n";
echo "  SOURCE TARGET       : {$totalSource}\n";
echo "  ELIGIBLE BALI       : {$eligibleSource}\n";
echo "  NON-BALI REVIEW     : {$nonBaliReview}\n";

if ($totalSource !== $expectedTotalSource) {
    throw new RuntimeException(
        "Expected {$expectedTotalSource} source records, got {$totalSource}."
    );
}

if ($eligibleSource !== $expectedEligibleSource) {
    throw new RuntimeException(
        "Expected {$expectedEligibleSource} eligible Bali records, got {$eligibleSource}."
    );
}

if ($nonBaliReview !== $expectedNonBaliReview) {
    throw new RuntimeException(
        "Expected {$expectedNonBaliReview} non-Bali review records, got {$nonBaliReview}."
    );
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Exact deterministic resolution
|--------------------------------------------------------------------------
|
| IMPORTANT:
| This repeats the same criteria as the successful dry-run.
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

$resolvedIds = [];

$matchedPatterns = 0;
$eligiblePatterns = 0;
$ambiguousPatterns = 0;
$unmatchedPatterns = 0;

$matchedRecords = 0;
$eligibleRecords = 0;
$ambiguousRecords = 0;
$unmatchedRecords = 0;

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

$resolvedIds = array_values(
    array_unique($resolvedIds)
);

$resolvedCount = count($resolvedIds);

$unmatchedEligibleRecords =
    $eligibleSource - $resolvedCount;

$totalReviewExcluded =
    $nonBaliReview
    +
    $unmatchedEligibleRecords;

$failedCount =
    max(
        0,
        $eligibleRecords - $resolvedCount
    );

/*
|--------------------------------------------------------------------------
| Exact deterministic gate
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "PRE-UPDATE DETERMINISTIC GATE\n";
echo "========================================\n";

echo "  TOTAL PATTERNS       : " . $patterns->count() . "\n";
echo "  MATCHED PATTERNS     : {$matchedPatterns}\n";
echo "  ELIGIBLE PATTERNS    : {$eligiblePatterns}\n";
echo "  AMBIGUOUS PATTERNS   : {$ambiguousPatterns}\n";
echo "  UNMATCHED PATTERNS   : {$unmatchedPatterns}\n\n";

echo "  MATCHED RECORDS      : {$matchedRecords}\n";
echo "  ELIGIBLE RECORDS     : {$eligibleRecords}\n";
echo "  RESOLVED IDS         : {$resolvedCount}\n";
echo "  AMBIGUOUS RECORDS    : {$ambiguousRecords}\n";
echo "  UNMATCHED BALI       : {$unmatchedEligibleRecords}\n";
echo "  REVIEW EXCLUDED      : {$totalReviewExcluded}\n";

if ($resolvedCount !== $expectedDeterministic) {
    throw new RuntimeException(
        "Deterministic target changed. Expected {$expectedDeterministic}, got {$resolvedCount}. ABORT."
    );
}

if ($failedCount !== 0) {
    throw new RuntimeException(
        "Deterministic failed count is {$failedCount}. ABORT."
    );
}

if ($unmatchedEligibleRecords !== $expectedUnmatchedEligible) {
    throw new RuntimeException(
        "Expected {$expectedUnmatchedEligible} unmatched Bali records, got {$unmatchedEligibleRecords}. ABORT."
    );
}

if ($totalReviewExcluded !== $expectedTotalReview) {
    throw new RuntimeException(
        "Expected {$expectedTotalReview} total review records, got {$totalReviewExcluded}. ABORT."
    );
}

echo "  PRE-UPDATE GATE      : PASS\n\n";

/*
|--------------------------------------------------------------------------
| Transaction
|--------------------------------------------------------------------------
*/

DB::beginTransaction();

try {

    /*
    |--------------------------------------------------------------------------
    | Re-check target IDs immediately before UPDATE
    |--------------------------------------------------------------------------
    */

    $stillNullTargetCount = DB::table('trade_statistics')
        ->whereIn('id', $resolvedIds)
        ->whereNull('trade_point_id')
        ->count();

    if ($stillNullTargetCount !== $expectedDeterministic) {

        throw new RuntimeException(
            "Safety re-check failed. Expected {$expectedDeterministic} NULL target rows, got {$stillNullTargetCount}."
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Ensure none of the target IDs belongs to another province/source
    |--------------------------------------------------------------------------
    */

    $targetEligibilityCheck = DB::table('trade_statistics')
        ->whereIn('id', $resolvedIds)
        ->whereNull('trade_point_id')
        ->where('port_name', $sourceName)
        ->where('province_id', $sourceProvinceId)
        ->count();

    if ($targetEligibilityCheck !== $expectedDeterministic) {

        throw new RuntimeException(
            "Target eligibility safety check failed. Expected {$expectedDeterministic}, got {$targetEligibilityCheck}."
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE ONLY VERIFIED IDS
    |--------------------------------------------------------------------------
    */

    $updated = DB::table('trade_statistics')
        ->whereIn('id', $resolvedIds)
        ->whereNull('trade_point_id')
        ->where('port_name', $sourceName)
        ->where('province_id', $sourceProvinceId)
        ->update([
            'trade_point_id' => $candidateId,
            'trade_point_type_id' => (int) $master->trade_point_type_id,
            'updated_at' => now(),
        ]);

    /*
    |--------------------------------------------------------------------------
    | Updated count gate
    |--------------------------------------------------------------------------
    */

    if ($updated !== $expectedDeterministic) {

        throw new RuntimeException(
            "Unexpected updated count. Expected {$expectedDeterministic}, got {$updated}."
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Target post-update verification
    |--------------------------------------------------------------------------
    */

    $targetResolvedCount = DB::table('trade_statistics')
        ->whereIn('id', $resolvedIds)
        ->where('trade_point_id', $candidateId)
        ->count();

    if ($targetResolvedCount !== $expectedDeterministic) {

        throw new RuntimeException(
            "Post-update target verification failed. Expected {$expectedDeterministic}, got {$targetResolvedCount}."
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Review records must remain NULL
    |--------------------------------------------------------------------------
    */

    $nonBaliStillNull = DB::table('trade_statistics')
        ->where('port_name', $sourceName)
        ->whereNull('trade_point_id')
        ->where('province_id', '<>', $sourceProvinceId)
        ->count();

    if ($nonBaliStillNull !== $expectedNonBaliReview) {

        throw new RuntimeException(
            "Non-Bali review verification failed. Expected {$expectedNonBaliReview}, got {$nonBaliStillNull}."
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Remaining Bali NULL = 6
    |--------------------------------------------------------------------------
    */

    $remainingBaliNull = DB::table('trade_statistics')
        ->where('port_name', $sourceName)
        ->whereNull('trade_point_id')
        ->where('province_id', $sourceProvinceId)
        ->count();

    if ($remainingBaliNull !== $expectedUnmatchedEligible) {

        throw new RuntimeException(
            "Remaining Bali review verification failed. Expected {$expectedUnmatchedEligible}, got {$remainingBaliNull}."
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Total source residual after update = 11
    |--------------------------------------------------------------------------
    */

    $sourceNullAfter = DB::table('trade_statistics')
        ->where('port_name', $sourceName)
        ->whereNull('trade_point_id')
        ->count();

    if ($sourceNullAfter !== $expectedTotalReview) {

        throw new RuntimeException(
            "Source residual verification failed. Expected {$expectedTotalReview}, got {$sourceNullAfter}."
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Global NULL after
    |--------------------------------------------------------------------------
    */

    $currentNullAfter = DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('trade_point_id')
        ->count();

    $expectedGlobalNullAfter =
        $currentNullBefore
        -
        $expectedDeterministic;

    if ($currentNullAfter !== $expectedGlobalNullAfter) {

        throw new RuntimeException(
            "Global NULL verification failed. Expected {$expectedGlobalNullAfter}, got {$currentNullAfter}."
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Integrity inside transaction
    |--------------------------------------------------------------------------
    */

    $integrityAfter = DB::table('trade_statistics')
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

    if (
        (int) $integrityAfter->records
            !==
        2266312
        ||
        (int) $integrityAfter->identities
            !==
        2266312
        ||
        (int) $integrityAfter->null_hs
            !==
        0
        ||
        (int) $integrityAfter->null_province
            !==
        0
    ) {

        throw new RuntimeException(
            'Core integrity verification failed. ROLLBACK.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | COMMIT
    |--------------------------------------------------------------------------
    */

    DB::commit();

    /*
    |--------------------------------------------------------------------------
    | Final output
    |--------------------------------------------------------------------------
    */

    echo "========================================\n";
    echo "AFTER\n";
    echo "========================================\n";

    echo "  UPDATED RECORDS      : {$updated}\n";
    echo "  SOURCE NULL REMAIN   : {$sourceNullAfter}\n";
    echo "  NON-BALI REVIEW      : {$nonBaliStillNull}\n";
    echo "  UNMATCHED BALI       : {$remainingBaliNull}\n";
    echo "  GLOBAL NULL AFTER    : {$currentNullAfter}\n";

    echo PHP_EOL;

    echo "========================================\n";
    echo "INTEGRITY CHECK\n";
    echo "========================================\n";

    echo "  RECORDS        : {$integrityAfter->records}\n";
    echo "  IDENTITIES     : {$integrityAfter->identities}\n";
    echo "  TRADE VALUE    : {$integrityAfter->trade_value}\n";
    echo "  TRADE VOLUME   : {$integrityAfter->trade_volume}\n";
    echo "  NULL HS        : {$integrityAfter->null_hs}\n";
    echo "  NULL PROVINCE  : {$integrityAfter->null_province}\n";

    echo PHP_EOL;

    echo "========================================\n";
    echo "FINAL SAM RATULANGI CONDITIONAL BACKFILL GATE\n";
    echo "========================================\n";

    echo "  UPDATED COUNT       : "
        . ($updated === $expectedDeterministic ? 'PASS' : 'FAIL')
        . PHP_EOL;

    echo "  SOURCE REVIEW       : "
        . ($sourceNullAfter === $expectedTotalReview ? 'PASS' : 'FAIL')
        . PHP_EOL;

    echo "  NON-BALI REVIEW     : "
        . ($nonBaliStillNull === $expectedNonBaliReview ? 'PASS' : 'FAIL')
        . PHP_EOL;

    echo "  UNMATCHED BALI      : "
        . ($remainingBaliNull === $expectedUnmatchedEligible ? 'PASS' : 'FAIL')
        . PHP_EOL;

    echo "  GLOBAL NULL         : "
        . ($currentNullAfter === $expectedGlobalNullAfter ? 'PASS' : 'FAIL')
        . PHP_EOL;

    echo "  CORE INTEGRITY      : PASS\n";

    echo PHP_EOL;

    echo "SAM RATULANGI CONDITIONAL TRADE GATEWAY BACKFILL : PASS\n";
    echo "========================================\n";

} catch (\Throwable $e) {

    DB::rollBack();

    echo PHP_EOL;
    echo "========================================\n";
    echo "BACKFILL FAILED - ROLLBACK\n";
    echo "========================================\n";
    echo "  ERROR : " . $e->getMessage() . PHP_EOL;
    echo "  DATABASE CHANGES : ROLLED BACK\n";
    echo "========================================\n";

    exit(1);
}