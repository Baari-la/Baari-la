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
echo "DIGESTEX KEMENDAG NEW MASTER COUNTRY ALIAS INSERT\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  SOURCE SYSTEM : KEMENDAG\n";
echo "  ALIASES       : 23\n";
echo "  OPERATION     : TRANSACTIONAL INSERT\n\n";

$aliases = [
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

/*
|--------------------------------------------------------------------------
| 1. MASTER VALIDATION
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "MASTER VALIDATION\n";
echo "========================================\n";

foreach ($aliases as $sourceName => $countryId) {

    $master = DB::table('mst_countries')
        ->where('id', $countryId)
        ->first([
            'id',
            'country_code',
            'iso3',
            'country_name_en',
            'country_name_id',
            'is_active',
        ]);

    if ($master === null) {
        throw new RuntimeException(
            "Master country {$countryId} tidak ditemukan untuk {$sourceName}."
        );
    }

    if ((int) $master->is_active !== 1) {
        throw new RuntimeException(
            "Master country {$countryId} tidak aktif untuk {$sourceName}."
        );
    }

    echo "  {$sourceName} : PASS"
        . " -> ID={$master->id}"
        . " {$master->country_code}/{$master->iso3}"
        . " | {$master->country_name_en}"
        . PHP_EOL;
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| 2. EXISTING ALIAS SAFETY CHECK
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "EXISTING ALIAS SAFETY CHECK\n";
echo "========================================\n";

foreach ($aliases as $sourceName => $countryId) {

    $existing = DB::table('trade_country_aliases')
        ->where('source_system', 'KEMENDAG')
        ->where('normalized_name', $sourceName)
        ->first([
            'id',
            'country_id',
            'source_name',
            'normalized_name',
            'source_system',
            'is_active',
        ]);

    if ($existing === null) {

        echo "  {$sourceName} : NOT REGISTERED\n";
        continue;
    }

    if ((int) $existing->country_id !== $countryId) {
        throw new RuntimeException(
            "Alias conflict untuk {$sourceName}: "
            . "existing country_id={$existing->country_id}, "
            . "expected={$countryId}"
        );
    }

    echo "  {$sourceName} : ALREADY REGISTERED"
        . " -> alias_id={$existing->id}"
        . PHP_EOL;
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| 3. TRANSACTIONAL INSERT
|--------------------------------------------------------------------------
*/

$inserted = 0;
$alreadyRegistered = 0;

DB::transaction(function () use (
    $aliases,
    &$inserted,
    &$alreadyRegistered
): void {

    foreach ($aliases as $sourceName => $countryId) {

        $existing = DB::table('trade_country_aliases')
            ->where('source_system', 'KEMENDAG')
            ->where('normalized_name', $sourceName)
            ->lockForUpdate()
            ->first();

        if ($existing !== null) {

            if ((int) $existing->country_id !== $countryId) {
                throw new RuntimeException(
                    "Alias conflict saat transaction untuk {$sourceName}."
                );
            }

            $alreadyRegistered++;
            continue;
        }

        DB::table('trade_country_aliases')->insert([
            'country_id' => $countryId,
            'source_name' => $sourceName,
            'normalized_name' => $sourceName,
            'source_system' => 'KEMENDAG',
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $inserted++;
    }
});

echo "INSERT RESULT:\n";
echo "  INSERTED           : {$inserted}\n";
echo "  ALREADY REGISTERED : {$alreadyRegistered}\n\n";

/*
|--------------------------------------------------------------------------
| 4. ALIAS VERIFICATION
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "ALIAS VERIFICATION\n";
echo "========================================\n";

foreach ($aliases as $sourceName => $countryId) {

    $row = DB::table('trade_country_aliases')
        ->where('source_system', 'KEMENDAG')
        ->where('normalized_name', $sourceName)
        ->first([
            'id',
            'country_id',
            'source_name',
            'normalized_name',
            'source_system',
            'is_active',
        ]);

    if (
        $row === null
        ||
        (int) $row->country_id !== $countryId
        ||
        (int) $row->is_active !== 1
    ) {
        throw new RuntimeException(
            "Alias verification FAILED untuk {$sourceName}."
        );
    }

    echo "  {$sourceName} : PASS"
        . " -> alias_id={$row->id}"
        . " country_id={$row->country_id}"
        . PHP_EOL;
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| 5. RESOLVER VERIFICATION
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "RESOLVER VERIFICATION\n";
echo "========================================\n";

$resolver = app(CountryResolverService::class);

foreach ($aliases as $sourceName => $countryId) {

    $resolved =
        $resolver->resolve($sourceName);

    if (
        $resolved === null
        ||
        (int) $resolved->id !== $countryId
    ) {
        throw new RuntimeException(
            "Resolver verification FAILED untuk {$sourceName}."
        );
    }

    echo "  {$sourceName} : PASS"
        . " -> ID={$resolved->id}"
        . " {$resolved->country_code}/{$resolved->iso3}"
        . " | {$resolved->country_name_en}"
        . PHP_EOL;
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| 6. TRADE STATISTICS SAFETY CHECK
|--------------------------------------------------------------------------
*/

$targetNullRecords =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('country_id')
        ->whereIn(
            'country_name',
            array_keys($aliases)
        )
        ->count();

echo "========================================\n";
echo "TRADE STATISTICS SAFETY CHECK\n";
echo "========================================\n";

echo "  NULL TARGET RECORDS : {$targetNullRecords}\n";
echo "  TRADE STATISTICS    : UNMODIFIED\n\n";

if ($targetNullRecords !== 1767) {
    throw new RuntimeException(
        "Unexpected target record count. "
        . "Expected 1767, got {$targetNullRecords}."
    );
}

/*
|--------------------------------------------------------------------------
| Final gate
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "FINAL ALIAS INSERT GATE\n";
echo "========================================\n";

echo "  MASTER VALIDATION  : PASS\n";
echo "  ALIAS VERIFICATION : PASS\n";
echo "  RESOLVER TEST      : PASS\n";
echo "  TARGET RECORDS     : {$targetNullRecords}\n";
echo "  TRADE DATA UPDATED : NO\n\n";

if (
    ($inserted + $alreadyRegistered) === 23
    &&
    $targetNullRecords === 1767
) {
    echo "COUNTRY NEW MASTER ALIAS INSERT : PASS\n";
} else {
    echo "COUNTRY NEW MASTER ALIAS INSERT : REVIEW\n";
    exit(1);
}

echo "========================================\n";