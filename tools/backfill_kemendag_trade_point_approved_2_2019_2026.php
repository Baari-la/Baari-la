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
echo "DIGESTEX KEMENDAG APPROVED TRADE POINT BACKFILL\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  TARGET      : SURAKARTA + PONTIANAK\n";
echo "  OPERATION   : TRANSACTIONAL BACKFILL\n\n";

/*
|--------------------------------------------------------------------------
| Approved mappings
|--------------------------------------------------------------------------
*/

$targets = [
    'SURAKARTA' => 3,
    'PONTIANAK' => 43,
];

$expectedCounts = [
    'SURAKARTA' => 497,
    'PONTIANAK' => 267,
];

$expectedTotal = 764;

/*
|--------------------------------------------------------------------------
| Resolver
|--------------------------------------------------------------------------
*/

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

$beforeTarget =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('trade_point_id')
        ->whereIn('port_name', array_keys($targets))
        ->count();

echo "BEFORE:\n";
echo "  NULL TRADE POINT ID : {$beforeNull}\n";
echo "  TARGET NULL RECORDS : {$beforeTarget}\n\n";

if ($beforeNull !== 51525) {
    throw new RuntimeException(
        "Unexpected NULL Trade Point baseline. Expected 51525, got {$beforeNull}."
    );
}

if ($beforeTarget !== $expectedTotal) {
    throw new RuntimeException(
        "Unexpected target count. Expected {$expectedTotal}, got {$beforeTarget}."
    );
}

/*
|--------------------------------------------------------------------------
| TRANSACTIONAL BACKFILL
|--------------------------------------------------------------------------
*/

$updated = 0;

DB::transaction(function () use (
    $targets,
    $resolver,
    &$updated
): void {

    $rows =
        DB::table('trade_statistics')
            ->whereBetween('year', [2019, 2026])
            ->whereIn('trade_flow', ['export', 'import'])
            ->whereNull('trade_point_id')
            ->whereIn('port_name', array_keys($targets))
            ->select(
                'id',
                'port_name'
            )
            ->orderBy('id')
            ->lockForUpdate()
            ->get();

    foreach ($rows as $row) {

        $sourceName =
            trim((string) $row->port_name);

        $expectedTradePointId =
            $targets[$sourceName] ?? null;

        if ($expectedTradePointId === null) {
            throw new RuntimeException(
                "Unexpected source name: {$sourceName}"
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Resolver verification
        |--------------------------------------------------------------------------
        */

        $resolvedTradePoint =
            $resolver->resolve($sourceName);

        if (
            !is_array($resolvedTradePoint)
            ||
            !isset($resolvedTradePoint['id'])
            ||
            (int) $resolvedTradePoint['id']
                !== $expectedTradePointId
        ) {
            throw new RuntimeException(
                "Resolver mismatch for trade_statistics.id={$row->id}, "
                . "source={$sourceName}."
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Target update
        |--------------------------------------------------------------------------
        */

        $affected =
            DB::table('trade_statistics')
                ->where('id', $row->id)
                ->whereNull('trade_point_id')
                ->update([
                    'trade_point_id' =>
                        $expectedTradePointId,

                    'updated_at' =>
                        now(),
                ]);

        if ($affected !== 1) {
            throw new RuntimeException(
                "Expected exactly 1 row updated for "
                . "trade_statistics.id={$row->id}, "
                . "affected={$affected}."
            );
        }

        $updated++;
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

$afterTarget =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('trade_point_id')
        ->whereIn('port_name', array_keys($targets))
        ->count();

$resolvedTarget =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereIn('port_name', array_keys($targets))
        ->whereIn('trade_point_id', array_values($targets))
        ->count();

echo "AFTER:\n";
echo "  UPDATED RECORDS     : {$updated}\n";
echo "  NULL TRADE POINT ID : {$afterNull}\n";
echo "  TARGET STILL NULL   : {$afterTarget}\n";
echo "  TARGET RESOLVED     : {$resolvedTarget}\n\n";

/*
|--------------------------------------------------------------------------
| Per-target verification
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "TARGET VERIFICATION\n";
echo "========================================\n";

$targetVerificationPass = true;

foreach ($expectedCounts as $sourceName => $expectedCount) {

    $tradePointId =
        $targets[$sourceName];

    $resolved =
        DB::table('trade_statistics')
            ->whereBetween('year', [2019, 2026])
            ->whereIn('trade_flow', ['export', 'import'])
            ->where('port_name', $sourceName)
            ->where('trade_point_id', $tradePointId)
            ->count();

    $stillNull =
        DB::table('trade_statistics')
            ->whereBetween('year', [2019, 2026])
            ->whereIn('trade_flow', ['export', 'import'])
            ->where('port_name', $sourceName)
            ->whereNull('trade_point_id')
            ->count();

    $pass =
        $resolved === $expectedCount
        &&
        $stillNull === 0;

    echo sprintf(
        "  %-15s | resolved=%4d | still_null=%d | %s\n",
        $sourceName,
        $resolved,
        $stillNull,
        $pass ? 'PASS' : 'FAIL'
    );

    if (!$pass) {
        $targetVerificationPass = false;
    }
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| INTEGRITY CHECK
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

$coreIntegrity =
    (int) $integrity->total_records === 2266312
    &&
    (int) $integrity->identities === 2266312
    &&
    (int) $integrity->null_hs === 0
    &&
    (int) $integrity->null_province === 0;

$success =
    $updated === $expectedTotal
    &&
    $afterTarget === 0
    &&
    $resolvedTarget === $expectedTotal
    &&
    $afterNull === 50761
    &&
    $targetVerificationPass
    &&
    $coreIntegrity;

echo "========================================\n";
echo "FINAL APPROVED TRADE POINT BACKFILL GATE\n";
echo "========================================\n";

echo "  UPDATED COUNT       : "
    . ($updated === $expectedTotal ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo "  NULL TARGET         : "
    . ($afterTarget === 0 ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo "  RESOLVED TARGET     : "
    . ($resolvedTarget === $expectedTotal ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo "  NULL RESIDUAL       : "
    . ($afterNull === 50761 ? 'PASS' : 'REVIEW')
    . PHP_EOL;

echo "  TARGET VERIFICATION : "
    . ($targetVerificationPass ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo "  CORE INTEGRITY      : "
    . ($coreIntegrity ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo PHP_EOL;

if ($success) {
    echo "APPROVED TRADE POINT BACKFILL : PASS\n";
} else {
    echo "APPROVED TRADE POINT BACKFILL : REVIEW\n";
    exit(1);
}

echo "========================================\n";