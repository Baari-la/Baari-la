<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use App\Services\Trade\TradePointResolverService;
use Illuminate\Support\Facades\DB;


echo "========================================\n";
echo "DIGESTEX KEMENDAG CONDITIONAL TRADE POINT BACKFILL DRY-RUN\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  SOURCES     : ATAMBUA + JAYAPURA\n";
echo "  OPERATION   : READ ONLY\n\n";

/*
|--------------------------------------------------------------------------
| Conditional targets
|--------------------------------------------------------------------------
*/

$targets = [
    'ATAMBUA' => [
        'trade_point_id' => 7,
        'province_id' => 23,
        'expected_records' => 3811,
    ],

    'JAYAPURA' => [
        'trade_point_id' => 35,
        'province_id' => 24,
        'expected_records' => 174,
    ],
];

$expectedSourceTotal = 4013;
$expectedEligibleTotal = 3985;
$expectedReviewTotal = 28;

/*
|--------------------------------------------------------------------------
| Resolver
|--------------------------------------------------------------------------
*/

$resolver =
    app(TradePointResolverService::class);

/*
|--------------------------------------------------------------------------
| Current baseline
|--------------------------------------------------------------------------
*/

$currentNull =
    DB::table('trade_statistics')
        ->whereBetween(
            'year',
            [2019, 2026]
        )
        ->whereIn(
            'trade_flow',
            ['export', 'import']
        )
        ->whereNull('trade_point_id')
        ->count();

echo "CURRENT NULL TRADE POINT RECORDS : {$currentNull}\n\n";

if ($currentNull !== 50761) {
    throw new RuntimeException(
        "Expected 50761 unresolved Trade Point records, got {$currentNull}."
    );
}

/*
|--------------------------------------------------------------------------
| Load ONLY eligible candidate rows
|--------------------------------------------------------------------------
|
| We deliberately filter by the approved province condition here.
| The 28 mismatch records never enter the dry-run target set.
|--------------------------------------------------------------------------
*/

$rows =
    DB::table('trade_statistics')
        ->whereBetween(
            'year',
            [2019, 2026]
        )
        ->whereIn(
            'trade_flow',
            ['export', 'import']
        )
        ->whereNull('trade_point_id')
        ->where(function ($query) use ($targets) {

            foreach ($targets as $sourceName => $target) {

                $query->orWhere(function ($subQuery) use (
                    $sourceName,
                    $target
                ) {
                    $subQuery
                        ->where(
                            'port_name',
                            $sourceName
                        )
                        ->where(
                            'province_id',
                            $target['province_id']
                        );
                });
            }
        })
        ->select(
            'id',
            'year',
            'month',
            'trade_flow',
            'port_name',
            'province_id',
            'province_name',
            'country_name',
            'hs_code',
            'trade_value',
            'trade_volume',
            'import_batch_id'
        )
        ->orderBy('id')
        ->get();

$targetCount =
    $rows->count();

echo "TARGET ELIGIBLE RECORDS : {$targetCount}\n\n";

if ($targetCount !== $expectedEligibleTotal) {
    throw new RuntimeException(
        "Unexpected eligible target count. "
        . "Expected {$expectedEligibleTotal}, got {$targetCount}."
    );
}

/*
|--------------------------------------------------------------------------
| Resolve every eligible row using province context
|--------------------------------------------------------------------------
*/

$resolvedCount = 0;
$failed = 0;

$breakdown = [
    'ATAMBUA' => [
        'trade_point_id' => 7,
        'records' => 0,
        'trade_value' => 0.0,
        'trade_volume' => 0.0,
    ],

    'JAYAPURA' => [
        'trade_point_id' => 35,
        'records' => 0,
        'trade_value' => 0.0,
        'trade_volume' => 0.0,
    ],
];

foreach ($rows as $row) {

    $sourceName =
        trim((string) $row->port_name);

    $provinceId =
        $row->province_id !== null
            ? (int) $row->province_id
            : null;

    $target =
        $targets[$sourceName]
        ?? null;

    if ($target === null) {
        $failed++;
        continue;
    }

    if (
        $provinceId === null
        ||
        $provinceId !== $target['province_id']
    ) {
        $failed++;
        continue;
    }

    /*
    |--------------------------------------------------------------------------
    | Province-aware resolver
    |--------------------------------------------------------------------------
    */

    $resolvedTradePoint =
        $resolver->resolve(
            $sourceName,
            'KEMENDAG',
            $provinceId
        );

    if (
        !is_array($resolvedTradePoint)
        ||
        !isset($resolvedTradePoint['id'])
        ||
        (int) $resolvedTradePoint['id']
            !== $target['trade_point_id']
    ) {
        $failed++;
        continue;
    }

    $resolvedCount++;

    $breakdown[$sourceName]['records']++;

    $breakdown[$sourceName]['trade_value'] +=
        (float) $row->trade_value;

    $breakdown[$sourceName]['trade_volume'] +=
        (float) $row->trade_volume;
}

/*
|--------------------------------------------------------------------------
| Summary
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "BACKFILL SUMMARY\n";
echo "========================================\n";

echo "  NULL BEFORE         : {$currentNull}\n";
echo "  SOURCE TARGET       : {$expectedSourceTotal}\n";
echo "  ELIGIBLE TARGET     : {$targetCount}\n";
echo "  RESOLVED            : {$resolvedCount}\n";
echo "  FAILED              : {$failed}\n";
echo "  WOULD UPDATE        : {$resolvedCount}\n";

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Target validation
|--------------------------------------------------------------------------
*/

$validationPass = true;

echo "========================================\n";
echo "CONDITIONAL TARGET VALIDATION\n";
echo "========================================\n";

foreach ($targets as $sourceName => $target) {

    $resolvedTradePoint =
        $resolver->resolve(
            $sourceName,
            'KEMENDAG',
            $target['province_id']
        );

    $resolvedId =
        is_array($resolvedTradePoint)
            ? (
                $resolvedTradePoint['id']
                ?? $resolvedTradePoint['trade_point_id']
                ?? null
            )
            : null;

    $pass =
        $resolvedId !== null
        &&
        (int) $resolvedId ===
            $target['trade_point_id'];

    echo sprintf(
        "  %-10s : %s -> resolver=%s | province=%d\n",
        $sourceName,
        $pass ? 'PASS' : 'FAIL',
        $resolvedId ?? 'NULL',
        $target['province_id']
    );

    if (!$pass) {
        $validationPass = false;
    }
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Resolver verification
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "PROVINCE-AWARE RESOLVER VERIFICATION\n";
echo "========================================\n";

foreach ($targets as $sourceName => $target) {

   $resolvedTradePoint =
    $resolver->resolve(
        $sourceName,
        'KEMENDAG',
        $target['province_id']
    );

$resolvedId =
    is_array($resolvedTradePoint)
        ? (
            $resolvedTradePoint['id']
            ?? $resolvedTradePoint['trade_point_id']
            ?? null
        )
        : null;

    $pass =
        $resolvedId !== null
        &&
        (int) $resolvedId ===
            $target['trade_point_id'];

    echo sprintf(
        "  %-10s : %s -> resolver=%s | province=%d\n",
        $sourceName,
        $pass ? 'PASS' : 'FAIL',
        $resolvedId ?? 'NULL',
        $target['province_id']
    );

    if (!$pass) {
        $validationPass = false;
    }
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Confirm mismatch records remain outside target
|--------------------------------------------------------------------------
*/

$atambuaReview =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn(
            'trade_flow',
            ['export', 'import']
        )
        ->whereNull('trade_point_id')
        ->where('port_name', 'ATAMBUA')
        ->where(function ($query) {
            $query
                ->whereNull('province_id')
                ->orWhere(
                    'province_id',
                    '<>',
                    23
                );
        })
        ->count();

$jayapuraReview =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn(
            'trade_flow',
            ['export', 'import']
        )
        ->whereNull('trade_point_id')
        ->where('port_name', 'JAYAPURA')
        ->where(function ($query) {
            $query
                ->whereNull('province_id')
                ->orWhere(
                    'province_id',
                    '<>',
                    24
                );
        })
        ->count();

$reviewTotal =
    $atambuaReview
    + $jayapuraReview;

echo "========================================\n";
echo "REVIEW EXCLUSION CHECK\n";
echo "========================================\n";

echo "  ATAMBUA REVIEW RECORDS  : {$atambuaReview}\n";
echo "  JAYAPURA REVIEW RECORDS : {$jayapuraReview}\n";
echo "  TOTAL REVIEW RECORDS    : {$reviewTotal}\n";
echo "  EXPECTED REVIEW         : {$expectedReviewTotal}\n";

$reviewPass =
    $reviewTotal === $expectedReviewTotal
    &&
    $atambuaReview === 24
    &&
    $jayapuraReview === 4;

echo "  REVIEW EXCLUSION        : "
    . ($reviewPass ? 'PASS' : 'FAIL')
    . PHP_EOL;

if (!$reviewPass) {
    $validationPass = false;
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Aggregate
|--------------------------------------------------------------------------
*/

$totalValue = 0.0;
$totalVolume = 0.0;

foreach ($breakdown as $data) {
    $totalValue +=
        $data['trade_value'];

    $totalVolume +=
        $data['trade_volume'];
}

echo "========================================\n";
echo "ELIGIBLE TARGET AGGREGATE\n";
echo "========================================\n";

echo "  TRADE VALUE  : "
    . number_format(
        $totalValue,
        3,
        '.',
        ''
    )
    . PHP_EOL;

echo "  TRADE VOLUME : "
    . number_format(
        $totalVolume,
        3,
        '.',
        ''
    )
    . PHP_EOL;

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
echo "  DATABASE WAS NOT MODIFIED.\n";

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Final gate
|--------------------------------------------------------------------------
*/

$success =
    $currentNull === 50761
    &&
    $targetCount === $expectedEligibleTotal
    &&
    $resolvedCount === $expectedEligibleTotal
    &&
    $failed === 0
    &&
    $validationPass
    &&
    $reviewPass;

echo "========================================\n";
echo "FINAL CONDITIONAL TRADE POINT DRY-RUN GATE\n";
echo "========================================\n";

echo "  CURRENT NULL        : {$currentNull}\n";
echo "  SOURCE TARGET       : {$expectedSourceTotal}\n";
echo "  ELIGIBLE TARGET     : {$targetCount}\n";
echo "  RESOLVED            : {$resolvedCount}\n";
echo "  FAILED              : {$failed}\n";
echo "  REVIEW EXCLUDED     : {$reviewTotal}\n";
echo "  VALIDATION          : "
    . ($validationPass ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo PHP_EOL;

if ($success) {
    echo "CONDITIONAL TRADE POINT BACKFILL DRY-RUN : PASS\n";
} else {
    echo "CONDITIONAL TRADE POINT BACKFILL DRY-RUN : REVIEW\n";
    exit(1);
}

echo "========================================\n";