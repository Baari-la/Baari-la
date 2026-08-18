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
echo "DIGESTEX KEMENDAG NEW MASTER COUNTRY BACKFILL\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  OPERATION   : TRANSACTIONAL BACKFILL\n\n";

$resolver = app(CountryResolverService::class);

$targetCountries = [
    'LUKSEMBURG' => 180,
    'SIPRUS' => 181,
    'REPUBLIK MACEDONIA' => 182,
    'ANDORRA' => 183,
    'REPUBLIK DEMOKRATIK KONGO' => 184,
    'ISLANDIA' => 185,
    'REP.AFRIKA TENGAH' => 186,
    'SAINT LUCIA' => 187,
    'GABON' => 188,
    'MAURITANIA' => 189,
    'ZAMBIA' => 190,
    'CHAD' => 191,
    'BOTSWANA' => 192,
    'MALAWI' => 193,
    'RWANDA' => 194,
    'NIGER' => 195,
    'DOMINIKA' => 196,
    'TAJIKISTAN' => 197,
    'TURKMENISTAN' => 198,
    'ERITREA' => 199,
    'SAN MARINO' => 200,
    'SAO TOME DAN PRINCIPE' => 201,
    'SUDAN SELATAN' => 202,
];

$beforeNull = DB::table('trade_statistics')
    ->whereBetween('year', [2019, 2026])
    ->whereIn('trade_flow', ['export', 'import'])
    ->whereNull('country_id')
    ->whereNotNull('country_name')
    ->where('country_name', '<>', '')
    ->count();

$beforeTarget = DB::table('trade_statistics')
    ->whereBetween('year', [2019, 2026])
    ->whereIn('trade_flow', ['export', 'import'])
    ->whereNull('country_id')
    ->whereIn('country_name', array_keys($targetCountries))
    ->count();

echo "BEFORE:\n";
echo "  NULL COUNTRY ID     : {$beforeNull}\n";
echo "  TARGET NULL RECORDS : {$beforeTarget}\n\n";

if ($beforeTarget !== 1767) {
    throw new RuntimeException(
        "Unexpected target count. Expected 1767, got {$beforeTarget}."
    );
}

$updated = 0;

DB::transaction(function () use (
    $resolver,
    $targetCountries,
    &$updated
): void {

    $rows = DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('country_id')
        ->whereIn('country_name', array_keys($targetCountries))
        ->select('id', 'country_name')
        ->orderBy('id')
        ->lockForUpdate()
        ->get();

    foreach ($rows as $row) {

        $expectedCountryId =
            $targetCountries[$row->country_name]
            ?? null;

        if ($expectedCountryId === null) {
            throw new RuntimeException(
                "Unexpected country name: {$row->country_name}"
            );
        }

        $country = $resolver->resolve($row->country_name);

        if (
            $country === null ||
            (int) $country->id !== $expectedCountryId
        ) {
            throw new RuntimeException(
                "Resolver mismatch for trade_statistics.id={$row->id}"
            );
        }

        $affected = DB::table('trade_statistics')
            ->where('id', $row->id)
            ->whereNull('country_id')
            ->update([
                'country_id' => (int) $country->id,
                'country_code' => $country->country_code,
                'updated_at' => now(),
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
| Verification
|--------------------------------------------------------------------------
*/

$afterNull = DB::table('trade_statistics')
    ->whereBetween('year', [2019, 2026])
    ->whereIn('trade_flow', ['export', 'import'])
    ->whereNull('country_id')
    ->whereNotNull('country_name')
    ->where('country_name', '<>', '')
    ->count();

$afterTarget = DB::table('trade_statistics')
    ->whereBetween('year', [2019, 2026])
    ->whereIn('trade_flow', ['export', 'import'])
    ->whereNull('country_id')
    ->whereIn('country_name', array_keys($targetCountries))
    ->count();

echo "AFTER:\n";
echo "  UPDATED RECORDS     : {$updated}\n";
echo "  NULL COUNTRY ID     : {$afterNull}\n";
echo "  TARGET STILL NULL   : {$afterTarget}\n\n";

echo "VERIFICATION:\n";
echo "  UPDATED COUNT       : "
    . ($updated === 1767 ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo "  NULL TARGET         : "
    . ($afterTarget === 0 ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo "  NULL RESIDUAL       : "
    . ($afterNull === 6252 ? 'PASS' : 'REVIEW')
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Core integrity
|--------------------------------------------------------------------------
*/

$integrity = DB::table('trade_statistics')
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

$success =
    $updated === 1767 &&
    $afterTarget === 0 &&
    $afterNull === 6252 &&
    (int) $integrity->total_records === 2266312 &&
    (int) $integrity->identities === 2266312 &&
    (int) $integrity->null_hs === 0 &&
    (int) $integrity->null_province === 0;

echo "========================================\n";

if ($success) {
    echo "NEW MASTER COUNTRY BACKFILL : PASS\n";
} else {
    echo "NEW MASTER COUNTRY BACKFILL : REVIEW\n";
    exit(1);
}

echo "========================================\n";