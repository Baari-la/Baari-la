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
echo "DIGESTEX KEMENDAG TERRITORY BACKFILL\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  TARGET      : 28 TERRITORY / DEPENDENCY\n";
echo "  OPERATION   : TRANSACTIONAL BACKFILL\n\n";

$resolver = app(
    CountryResolverService::class
);

/*
|--------------------------------------------------------------------------
| Source name -> approved master country ID
|--------------------------------------------------------------------------
*/

$targets = [
    'VIRGIN ISLANDS (BRITISH)' => 203,
    'U.S. VIRGIN ISLANDS' => 204,
    'KEPULAUAN TURKS DAN CAICOS' => 205,
    'KEPULAUAN CAYMAN' => 206,
    'KEP. VALLIS DAN FUTUNA' => 207,
    'ANGUILA' => 208,
    'JERSEY' => 209,
    'MAYOTTE' => 210,
    'ARUBA' => 211,
    'SAINT BARTHELEMY' => 212,
    'SAMOA AMERIKA' => 213,
    'TOKELAU' => 214,
    'GIBRALTAR' => 215,
    'KEPULAUAN MARIANA UTARA' => 216,
    'SAINT MARTIN (FRENCH PART)' => 217,
    'KEPULAUAN CHRISTMAS' => 218,
    'U.S MINOR OUTLYING ISLAND' => 219,
    'GUERNSEY' => 220,
    'SAINT HELENA' => 221,
    'KEPULAUAN FALKLAND (MALVINAS)' => 222,
    'KEPULAUAN COCOS (KEELING)' => 223,
    'SINT MAARTEN (DUTCH PART)' => 224,
    'KEPULAUAN NORFOLK' => 225,
    'BRITISH INDIAN OCEAN TERRITORY' => 226,
    'ANTARTICA' => 227,
    'PITCAIRN' => 228,
    'PULAU HEARD DAN KEPULAUAN MCDONALD' => 229,
    'KEPULAUAN ALAND' => 230,
];

if (count($targets) !== 28) {
    throw new RuntimeException(
        'Expected exactly 28 territory targets.'
    );
}

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
        ->whereIn('country_name', array_keys($targets))
        ->count();

echo "BEFORE:\n";
echo "  NULL COUNTRY ID     : {$beforeNull}\n";
echo "  TARGET NULL RECORDS : {$beforeTarget}\n\n";

if ($beforeTarget !== 1964) {
    throw new RuntimeException(
        "Unexpected target count. Expected 1964, got {$beforeTarget}."
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
    $targets,
    &$updated
): void {

    $rows =
        DB::table('trade_statistics')
            ->whereBetween('year', [2019, 2026])
            ->whereIn('trade_flow', ['export', 'import'])
            ->whereNull('country_id')
            ->whereIn('country_name', array_keys($targets))
            ->select(
                'id',
                'country_name'
            )
            ->orderBy('id')
            ->lockForUpdate()
            ->get();

    foreach ($rows as $row) {

        $sourceName = trim(
            (string) $row->country_name
        );

        $expectedCountryId =
            $targets[$sourceName]
            ?? null;

        if ($expectedCountryId === null) {
            throw new RuntimeException(
                "Unexpected territory source name: {$sourceName}"
            );
        }

        $country =
            $resolver->resolve($sourceName);

        if (
            $country === null
            ||
            (int) $country->id !== $expectedCountryId
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
                        (int) $country->id,

                    'country_code' =>
                        $country->country_code,

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
        ->whereIn('country_name', array_keys($targets))
        ->count();

$resolvedTarget =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereIn('country_name', array_keys($targets))
        ->whereIn('country_id', array_values($targets))
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
echo "FINAL TERRITORY BACKFILL GATE\n";
echo "========================================\n";

echo "  UPDATED COUNT       : "
    . ($updated === 1964 ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo "  NULL TARGET         : "
    . ($afterTarget === 0 ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo "  RESOLVED TARGET     : "
    . ($resolvedTarget === 1964 ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo "  NULL RESIDUAL       : "
    . ($afterNull === 4254 ? 'PASS' : 'REVIEW')
    . PHP_EOL;

$coreIntegrity =
    (int) $integrity->total_records === 2266312
    &&
    (int) $integrity->identities === 2266312
    &&
    (int) $integrity->null_hs === 0
    &&
    (int) $integrity->null_province === 0;

echo "  CORE INTEGRITY      : "
    . ($coreIntegrity ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo PHP_EOL;

$success =
    $updated === 1964
    &&
    $afterTarget === 0
    &&
    $resolvedTarget === 1964
    &&
    $afterNull === 4254
    &&
    $coreIntegrity;

if ($success) {
    echo "TERRITORY BACKFILL : PASS\n";
} else {
    echo "TERRITORY BACKFILL : REVIEW\n";
    exit(1);
}

echo "========================================\n";