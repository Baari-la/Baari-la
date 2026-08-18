<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use App\Services\Trade\CountryResolverService;
use Illuminate\Support\Facades\DB;


echo "========================================\n";
echo "DIGESTEX KEMENDAG FRENCH GUIANA BACKFILL\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  SOURCE NAME : GUIANA PERANCIS\n";
echo "  TARGET ID   : 71\n";
echo "  OPERATION   : TRANSACTIONAL BACKFILL\n\n";

$resolver = app(
    CountryResolverService::class
);

/*
|--------------------------------------------------------------------------
| BEFORE
|--------------------------------------------------------------------------
*/

$beforeNull =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('country_id')
        ->count();

$beforeTarget =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('country_id')
        ->where('country_name', 'GUIANA PERANCIS')
        ->count();

echo "BEFORE:\n";
echo "  NULL COUNTRY ID     : {$beforeNull}\n";
echo "  TARGET NULL RECORDS : {$beforeTarget}\n\n";

if ($beforeTarget !== 34) {
    throw new RuntimeException(
        "Unexpected target count. Expected 34, got {$beforeTarget}."
    );
}

/*
|--------------------------------------------------------------------------
| TRANSACTIONAL BACKFILL
|--------------------------------------------------------------------------
*/

$updated = 0;

DB::transaction(function () use (
    $resolver,
    &$updated
): void {

    $rows =
        DB::table('trade_statistics')
            ->whereBetween('year', [2019, 2026])
            ->whereIn('trade_flow', ['export', 'import'])
            ->whereNull('country_id')
            ->where('country_name', 'GUIANA PERANCIS')
            ->select('id', 'country_name')
            ->orderBy('id')
            ->lockForUpdate()
            ->get();

    foreach ($rows as $row) {

        $country =
            $resolver->resolve(
                'GUIANA PERANCIS'
            );

        if (
            $country === null
            ||
            (int) $country->id !== 71
        ) {
            throw new RuntimeException(
                "Resolver mismatch for trade_statistics.id={$row->id}"
            );
        }

        $affected =
            DB::table('trade_statistics')
                ->where('id', $row->id)
                ->whereNull('country_id')
                ->update([
                    'country_id' =>
                        71,

                    'country_code' =>
                        'GF',

                    'updated_at' =>
                        now(),
                ]);

        if ($affected !== 1) {
            throw new RuntimeException(
                "Expected 1 updated row for id={$row->id}, affected={$affected}"
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
        ->whereNull('country_id')
        ->count();

$afterTarget =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('country_id')
        ->where('country_name', 'GUIANA PERANCIS')
        ->count();

$resolvedTarget =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->where('country_name', 'GUIANA PERANCIS')
        ->where('country_id', 71)
        ->count();

echo "AFTER:\n";
echo "  UPDATED RECORDS     : {$updated}\n";
echo "  NULL COUNTRY ID     : {$afterNull}\n";
echo "  TARGET STILL NULL   : {$afterTarget}\n";
echo "  TARGET RESOLVED     : {$resolvedTarget}\n\n";

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
| FINAL GATE
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "FINAL BACKFILL GATE\n";
echo "========================================\n";

echo "  UPDATED COUNT       : "
    . ($updated === 34 ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo "  NULL TARGET         : "
    . ($afterTarget === 0 ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo "  RESOLVED TARGET     : "
    . ($resolvedTarget === 34 ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo "  NULL RESIDUAL       : "
    . ($afterNull === 6218 ? 'PASS' : 'REVIEW')
    . PHP_EOL;

echo "  CORE INTEGRITY      : "
    . (
        (int) $integrity->total_records === 2266312
        &&
        (int) $integrity->identities === 2266312
        &&
        (int) $integrity->null_hs === 0
        &&
        (int) $integrity->null_province === 0
            ? 'PASS'
            : 'FAIL'
    )
    . PHP_EOL;

echo PHP_EOL;

$success =
    $updated === 34
    &&
    $afterTarget === 0
    &&
    $resolvedTarget === 34
    &&
    $afterNull === 6218
    &&
    (int) $integrity->total_records === 2266312
    &&
    (int) $integrity->identities === 2266312
    &&
    (int) $integrity->null_hs === 0
    &&
    (int) $integrity->null_province === 0;

if ($success) {
    echo "FRENCH GUIANA BACKFILL : PASS\n";
} else {
    echo "FRENCH GUIANA BACKFILL : REVIEW\n";
    exit(1);
}

echo "========================================\n";