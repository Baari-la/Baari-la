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
echo "DIGESTEX KEMENDAG COUNTRY RESOLVABLE BACKFILL\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  OPERATION   : TRANSACTIONAL BACKFILL\n\n";

$resolver =
    app(CountryResolverService::class);

$beforeNull =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('country_id')
        ->whereNotNull('country_name')
        ->where('country_name', '<>', '')
        ->count();

echo "BEFORE:\n";
echo "  NULL COUNTRY ID : {$beforeNull}\n\n";

if ($beforeNull === 0) {
    echo "Nothing to backfill.\n";
    echo "========================================\n";
    echo "COUNTRY BACKFILL : PASS\n";
    echo "========================================\n";
    exit(0);
}

$updatedIds = [];

DB::transaction(function () use (
    $resolver,
    &$updatedIds
): void {

    $rows =
        DB::table('trade_statistics')
            ->whereBetween('year', [2019, 2026])
            ->whereIn('trade_flow', ['export', 'import'])
            ->whereNull('country_id')
            ->whereNotNull('country_name')
            ->where('country_name', '<>', '')
            ->select(
                'id',
                'country_name'
            )
            ->orderBy('id')
            ->lockForUpdate()
            ->get();

    foreach ($rows as $row) {

        $country =
            $resolver->resolve(
                $row->country_name
            );

        /*
        |--------------------------------------------------------------------------
        | Important:
        | Only currently-resolvable countries may be updated.
        |--------------------------------------------------------------------------
        */

        if ($country === null) {
            continue;
        }

        $affected =
            DB::table('trade_statistics')
                ->where('id', $row->id)
                ->whereNull('country_id')
                ->update([
                    'country_id' =>
                        (int) $country->id,

                    'country_code' =>
                        $country->country_code,

                    'updated_at' =>
                        now(),
                ]);

        if ($affected !== 1) {
            throw new RuntimeException(
                'Expected exactly 1 row updated for trade_statistics.id='
                . $row->id
                . ', affected='
                . $affected
            );
        }

        $updatedIds[] =
            (int) $row->id;
    }
});

/*
|--------------------------------------------------------------------------
| Post-transaction verification
|--------------------------------------------------------------------------
*/

$afterNull =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('country_id')
        ->whereNotNull('country_name')
        ->where('country_name', '<>', '')
        ->count();

$updatedCount =
    count($updatedIds);

echo "AFTER:\n";
echo "  UPDATED RECORDS : {$updatedCount}\n";
echo "  NULL COUNTRY ID : {$afterNull}\n\n";

echo "VERIFICATION:\n";

if (
    $updatedCount === 8146
) {
    echo "  UPDATED COUNT   : PASS\n";
} else {
    echo "  UPDATED COUNT   : REVIEW\n";
}

if (
    $afterNull === 31698
) {
    echo "  NULL RESIDUAL   : PASS\n";
} else {
    echo "  NULL RESIDUAL   : REVIEW\n";
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Integrity verification
|--------------------------------------------------------------------------
|
| Ensure core trade facts were not changed.
|--------------------------------------------------------------------------
*/

$integrity =
    DB::table('trade_statistics')
        ->selectRaw(
            '
            COUNT(*) AS total_records,
            COUNT(DISTINCT trade_identity) AS identities,
            SUM(trade_value) AS trade_value,
            SUM(trade_volume) AS trade_volume,
            SUM(
                CASE
                    WHEN hs_id IS NULL THEN 1
                    ELSE 0
                END
            ) AS null_hs,
            SUM(
                CASE
                    WHEN province_id IS NULL THEN 1
                    ELSE 0
                END
            ) AS null_province
            '
        )
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->first();

echo "INTEGRITY CHECK:\n";

echo "  RECORDS        : "
    . $integrity->total_records
    . PHP_EOL;

echo "  IDENTITIES     : "
    . $integrity->identities
    . PHP_EOL;

echo "  NULL HS        : "
    . $integrity->null_hs
    . PHP_EOL;

echo "  NULL PROVINCE  : "
    . $integrity->null_province
    . PHP_EOL;

echo PHP_EOL;

$success =
    $updatedCount === 8146
    &&
    $afterNull === 31698
    &&
    (int) $integrity->identities ===
        (int) $integrity->total_records
    &&
    (int) $integrity->null_hs === 0
    &&
    (int) $integrity->null_province === 0;

echo "========================================\n";

if ($success) {
    echo "COUNTRY BACKFILL : PASS\n";
} else {
    echo "COUNTRY BACKFILL : REVIEW\n";
}

echo "========================================\n";