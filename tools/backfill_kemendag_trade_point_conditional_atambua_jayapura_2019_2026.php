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
echo "DIGESTEX KEMENDAG CONDITIONAL TRADE POINT BACKFILL\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  SOURCES     : ATAMBUA + JAYAPURA\n";
echo "  OPERATION   : TRANSACTIONAL BACKFILL\n\n";

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

$resolver =
    app(TradePointResolverService::class);

/*
|--------------------------------------------------------------------------
| BEFORE
|--------------------------------------------------------------------------
*/

$beforeNull =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('trade_point_id')
        ->count();

$beforeSourceTarget =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('trade_point_id')
        ->whereIn('port_name', array_keys($targets))
        ->count();

echo "BEFORE:\n";
echo "  NULL TRADE POINT ID : {$beforeNull}\n";
echo "  SOURCE TARGET       : {$beforeSourceTarget}\n\n";

if ($beforeNull !== 50761) {
    throw new \RuntimeException(
        "Unexpected NULL Trade Point baseline. "
        . "Expected 50761, got {$beforeNull}."
    );
}

if ($beforeSourceTarget !== $expectedSourceTotal) {
    throw new \RuntimeException(
        "Unexpected source target count. "
        . "Expected {$expectedSourceTotal}, got {$beforeSourceTarget}."
    );
}

/*
|--------------------------------------------------------------------------
| Transactional backfill
|--------------------------------------------------------------------------
*/

$updatedCount = 0;

DB::transaction(function () use (
    $targets,
    $resolver,
    &$updatedCount
): void {

    $rows =
        DB::table('trade_statistics')
            ->whereBetween('year', [2019, 2026])
            ->whereIn('trade_flow', ['export', 'import'])
            ->whereNull('trade_point_id')
            ->where(function ($query) use ($targets) {

                foreach ($targets as $sourceName => $target) {

                    $query->orWhere(function ($subQuery) use (
                        $sourceName,
                        $target
                    ) {
                        $subQuery
                            ->where('port_name', $sourceName)
                            ->where('province_id', $target['province_id']);
                    });
                }

            })
            ->select([
                'id',
                'port_name',
                'province_id',
            ])
            ->orderBy('id')
            ->lockForUpdate()
            ->get();

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
            throw new \RuntimeException(
                "Unexpected source name: {$sourceName}"
            );
        }

        if (
            $provinceId === null
            ||
            $provinceId !== $target['province_id']
        ) {
            throw new \RuntimeException(
                "Province mismatch for trade_statistics.id={$row->id}."
            );
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
            throw new \RuntimeException(
                "Resolver mismatch for "
                . "trade_statistics.id={$row->id}, "
                . "source={$sourceName}, "
                . "province={$provinceId}."
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Update only NULL target
        |--------------------------------------------------------------------------
        */

        $affected =
            DB::table('trade_statistics')
                ->where('id', $row->id)
                ->whereNull('trade_point_id')
                ->update([
                    'trade_point_id' =>
                        $target['trade_point_id'],
                    'updated_at' =>
                        now(),
                ]);

        if ($affected !== 1) {
            throw new \RuntimeException(
                "Expected exactly one update for "
                . "trade_statistics.id={$row->id}, "
                . "affected={$affected}."
            );
        }

        $updatedCount++;
    }

    /*
    |--------------------------------------------------------------------------
    | Transaction-level safety
    |--------------------------------------------------------------------------
    */

    if ($updatedCount !== 3985) {
        throw new \RuntimeException(
            "Transactional update count mismatch. "
            . "Expected 3985, got {$updatedCount}."
        );
    }
});

/*
|--------------------------------------------------------------------------
| AFTER
|--------------------------------------------------------------------------
*/

$afterNull =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('trade_point_id')
        ->count();

$afterSourceTarget =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('trade_point_id')
        ->whereIn('port_name', array_keys($targets))
        ->count();

$resolvedAtambua =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->where('port_name', 'ATAMBUA')
        ->where('province_id', 23)
        ->where('trade_point_id', 7)
        ->count();

$resolvedJayapura =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->where('port_name', 'JAYAPURA')
        ->where('province_id', 24)
        ->where('trade_point_id', 35)
        ->count();

$atambuaReview =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('trade_point_id')
        ->where('port_name', 'ATAMBUA')
        ->where(function ($query) {
            $query
                ->whereNull('province_id')
                ->orWhere('province_id', '<>', 23);
        })
        ->count();

$jayapuraReview =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('trade_point_id')
        ->where('port_name', 'JAYAPURA')
        ->where(function ($query) {
            $query
                ->whereNull('province_id')
                ->orWhere('province_id', '<>', 24);
        })
        ->count();

$reviewTotal =
    $atambuaReview
    + $jayapuraReview;

echo "AFTER:\n";
echo "  UPDATED RECORDS      : {$updatedCount}\n";
echo "  NULL TRADE POINT ID : {$afterNull}\n";
echo "  SOURCE TARGET NULL   : {$afterSourceTarget}\n";
echo "  ATAMBUA RESOLVED     : {$resolvedAtambua}\n";
echo "  JAYAPURA RESOLVED    : {$resolvedJayapura}\n";
echo "  REVIEW RECORDS       : {$reviewTotal}\n\n";

/*
|--------------------------------------------------------------------------
| Integrity
|--------------------------------------------------------------------------
*/

$integrity =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->selectRaw(
            '
            COUNT(*) AS total_records,
            COUNT(DISTINCT trade_identity) AS identities,
            SUM(trade_value) AS trade_value,
            SUM(trade_volume) AS trade_volume,
            SUM(CASE WHEN hs_id IS NULL THEN 1 ELSE 0 END) AS null_hs,
            SUM(CASE WHEN province_id IS NULL THEN 1 ELSE 0 END) AS null_province
            '
        )
        ->first();

echo "========================================\n";
echo "INTEGRITY CHECK\n";
echo "========================================\n";

echo "  RECORDS        : {$integrity->total_records}\n";
echo "  IDENTITIES     : {$integrity->identities}\n";
echo "  TRADE VALUE    : {$integrity->trade_value}\n";
echo "  TRADE VOLUME   : {$integrity->trade_volume}\n";
echo "  NULL HS        : {$integrity->null_hs}\n";
echo "  NULL PROVINCE  : {$integrity->null_province}\n\n";

/*
|--------------------------------------------------------------------------
| Final gate
|--------------------------------------------------------------------------
*/

$resolvedTotal =
    $resolvedAtambua
    + $resolvedJayapura;

$coreIntegrity =
    (int) $integrity->total_records === 2266312
    &&
    (int) $integrity->identities === 2266312
    &&
    (int) $integrity->null_hs === 0
    &&
    (int) $integrity->null_province === 0;

$success =
    $updatedCount === $expectedEligibleTotal
    &&
    $afterSourceTarget === 28
    &&
    $resolvedTotal === $expectedEligibleTotal
    &&
    $atambuaReview === 24
    &&
    $jayapuraReview === 4
    &&
    $reviewTotal === $expectedReviewTotal
    &&
    $afterNull === 46776
    &&
    $coreIntegrity;

echo "========================================\n";
echo "FINAL CONDITIONAL TRADE POINT BACKFILL GATE\n";
echo "========================================\n";

echo "  UPDATED COUNT       : "
    . ($updatedCount === $expectedEligibleTotal ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo "  SOURCE TARGET NULL  : "
    . ($afterSourceTarget === 28 ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo "  RESOLVED TARGET     : "
    . ($resolvedTotal === $expectedEligibleTotal ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo "  REVIEW EXCLUDED     : "
    . (
        $reviewTotal === $expectedReviewTotal
            ? 'PASS'
            : 'FAIL'
    )
    . PHP_EOL;

echo "  NULL RESIDUAL       : "
    . ($afterNull === 46776 ? 'PASS' : 'REVIEW')
    . PHP_EOL;

echo "  CORE INTEGRITY      : "
    . ($coreIntegrity ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo PHP_EOL;

if ($success) {
    echo "CONDITIONAL TRADE POINT BACKFILL : PASS\n";
} else {
    echo "CONDITIONAL TRADE POINT BACKFILL : REVIEW\n";
    exit(1);
}

echo "========================================\n";