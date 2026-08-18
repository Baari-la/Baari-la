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
echo "DIGESTEX KEMENDAG COUNTRY ALIAS BACKFILL\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  OPERATION   : TRANSACTIONAL BACKFILL\n\n";

$resolver = app(CountryResolverService::class);

/*
|--------------------------------------------------------------------------
| Approved aliases only
|--------------------------------------------------------------------------
*/

$targetNames = [
    'SRI LANGKA' => 23,
    'REPUBLIK CZECH' => 51,
    'LIBIA' => 90,
];

/*
|--------------------------------------------------------------------------
| Before
|--------------------------------------------------------------------------
*/

$beforeNull =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('country_id')
        ->whereNotNull('country_name')
        ->where('country_name', '<>', '')
        ->count();

$beforeTarget =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('country_id')
        ->whereIn('country_name', array_keys($targetNames))
        ->count();

echo "BEFORE:\n";
echo "  NULL COUNTRY ID      : {$beforeNull}\n";
echo "  TARGET NULL RECORDS  : {$beforeTarget}\n\n";

if ($beforeTarget !== 23679) {
    throw new RuntimeException(
        "Unexpected target count. Expected 23679, got {$beforeTarget}."
    );
}

$updatedIds = [];

DB::transaction(function () use (
    $resolver,
    $targetNames,
    &$updatedIds
): void {

    $rows =
        DB::table('trade_statistics')
            ->whereBetween('year', [2019, 2026])
            ->whereIn('trade_flow', ['export', 'import'])
            ->whereNull('country_id')
            ->whereIn('country_name', array_keys($targetNames))
            ->select(
                'id',
                'country_name'
            )
            ->orderBy('id')
            ->lockForUpdate()
            ->get();

    foreach ($rows as $row) {

        $expectedCountryId =
            $targetNames[$row->country_name]
            ?? null;

        if ($expectedCountryId === null) {
            throw new RuntimeException(
                "Unexpected country target: {$row->country_name}"
            );
        }

        $country =
            $resolver->resolve(
                $row->country_name
            );

        if ($country === null) {
            throw new RuntimeException(
                "Resolver failed for trade_statistics.id={$row->id}"
                . " source={$row->country_name}"
            );
        }

        if (
            (int) $country->id
            !==
            $expectedCountryId
        ) {
            throw new RuntimeException(
                "Resolver target mismatch for trade_statistics.id={$row->id}"
                . " expected={$expectedCountryId}"
                . " resolved={$country->id}"
            );
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
                "Expected 1 updated row for trade_statistics.id={$row->id}, "
                . "affected={$affected}"
            );
        }

        $updatedIds[] =
            (int) $row->id;
    }
});

/*
|--------------------------------------------------------------------------
| After verification
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

$afterTarget =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('country_id')
        ->whereIn('country_name', array_keys($targetNames))
        ->count();

$updatedCount =
    count($updatedIds);

echo "AFTER:\n";
echo "  UPDATED RECORDS     : {$updatedCount}\n";
echo "  NULL COUNTRY ID     : {$afterNull}\n";
echo "  TARGET STILL NULL   : {$afterTarget}\n\n";

echo "VERIFICATION:\n";

echo "  UPDATED COUNT       : "
    . (
        $updatedCount === 23679
            ? 'PASS'
            : 'FAIL'
    )
    . PHP_EOL;

echo "  NULL TARGET         : "
    . (
        $afterTarget === 0
            ? 'PASS'
            : 'FAIL'
    )
    . PHP_EOL;

echo "  NULL RESIDUAL       : "
    . (
        $afterNull === 8019
            ? 'PASS'
            : 'REVIEW'
    )
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Alias-specific verification
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "ALIAS-SPECIFIC VERIFICATION\n";
echo "========================================\n";

$expectedByAlias = [
    'SRI LANGKA' => [
        'country_id' => 23,
        'records_before' => 20127,
    ],

    'REPUBLIK CZECH' => [
        'country_id' => 51,
        'records_before' => 3349,
    ],

    'LIBIA' => [
        'country_id' => 90,
        'records_before' => 203,
    ],
];

foreach ($expectedByAlias as $sourceName => $expected) {

    $resolvedCount =
        DB::table('trade_statistics')
            ->whereBetween('year', [2019, 2026])
            ->whereIn('trade_flow', ['export', 'import'])
            ->where('country_name', $sourceName)
            ->where('country_id', $expected['country_id'])
            ->count();

    $stillNull =
        DB::table('trade_statistics')
            ->whereBetween('year', [2019, 2026])
            ->whereIn('trade_flow', ['export', 'import'])
            ->where('country_name', $sourceName)
            ->whereNull('country_id')
            ->count();

    echo sprintf(
        "  %-25s | resolved=%6d | still_null=%d | %s\n",
        $sourceName,
        $resolvedCount,
        $stillNull,
        (
            $resolvedCount === $expected['records_before']
            && $stillNull === 0
        )
            ? 'PASS'
            : 'FAIL'
    );
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Integrity check
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

echo "  RECORDS        : "
    . $integrity->total_records
    . PHP_EOL;

echo "  IDENTITIES     : "
    . $integrity->identities
    . PHP_EOL;

echo "  TRADE VALUE    : "
    . $integrity->trade_value
    . PHP_EOL;

echo "  TRADE VOLUME   : "
    . $integrity->trade_volume
    . PHP_EOL;

echo "  NULL HS        : "
    . $integrity->null_hs
    . PHP_EOL;

echo "  NULL PROVINCE  : "
    . $integrity->null_province
    . PHP_EOL;

echo PHP_EOL;

$success =
    $updatedCount === 23679
    &&
    $afterTarget === 0
    &&
    $afterNull === 8019
    &&
    (int) $integrity->total_records === 2266312
    &&
    (int) $integrity->identities === 2266312
    &&
    (int) $integrity->null_hs === 0
    &&
    (int) $integrity->null_province === 0;

echo "========================================\n";

if ($success) {
    echo "COUNTRY ALIAS BACKFILL : PASS\n";
} else {
    echo "COUNTRY ALIAS BACKFILL : REVIEW\n";
    exit(1);
}

echo "========================================\n";