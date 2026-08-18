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
echo "DIGESTEX KEMENDAG TERRITORY COUNTRY ALIAS INSERT\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  SOURCE SYSTEM : KEMENDAG\n";
echo "  ALIASES       : 28\n";
echo "  MASTER RANGE  : 203-230\n";
echo "  OPERATION     : TRANSACTIONAL INSERT\n\n";

$aliases = [
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

/*
|--------------------------------------------------------------------------
| Candidate validation
|--------------------------------------------------------------------------
*/

if (count($aliases) !== 28) {
    throw new RuntimeException(
        'Expected exactly 28 territory aliases.'
    );
}

echo "========================================\n";
echo "MASTER VALIDATION\n";
echo "========================================\n";

foreach ($aliases as $sourceName => $countryId) {

    $master =
        DB::table('mst_countries')
            ->where('id', $countryId)
            ->first([
                'id',
                'country_code',
                'iso3',
                'country_name_en',
                'country_name_id',
                'official_name',
                'is_active',
            ]);

    if ($master === null) {
        throw new RuntimeException(
            "Master country ID={$countryId} tidak ditemukan untuk {$sourceName}."
        );
    }

    if ((int) $master->is_active !== 1) {
        throw new RuntimeException(
            "Master country ID={$countryId} tidak aktif untuk {$sourceName}."
        );
    }

    echo sprintf(
        "  %-42s : PASS -> ID=%d %s/%s | %s\n",
        $sourceName,
        $master->id,
        $master->country_code,
        $master->iso3,
        $master->country_name_en
    );
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Existing alias safety check
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "EXISTING ALIAS SAFETY CHECK\n";
echo "========================================\n";

$existingConflicts = 0;
$alreadyRegistered = 0;

foreach ($aliases as $sourceName => $countryId) {

    $existing =
        DB::table('trade_country_aliases')
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
        $existingConflicts++;

        echo "  {$sourceName} : CONFLICT"
            . " existing_country_id={$existing->country_id}"
            . " expected={$countryId}"
            . PHP_EOL;

        continue;
    }

    $alreadyRegistered++;

    echo "  {$sourceName} : ALREADY REGISTERED"
        . " -> alias_id={$existing->id}"
        . PHP_EOL;
}

echo PHP_EOL;

if ($existingConflicts > 0) {
    throw new RuntimeException(
        "Existing alias conflicts detected: {$existingConflicts}"
    );
}

/*
|--------------------------------------------------------------------------
| Transactional insert
|--------------------------------------------------------------------------
*/

$inserted = 0;

DB::transaction(function () use (
    $aliases,
    &$inserted,
    &$alreadyRegistered
): void {

    foreach ($aliases as $sourceName => $countryId) {

        $existing =
            DB::table('trade_country_aliases')
                ->where('source_system', 'KEMENDAG')
                ->where('normalized_name', $sourceName)
                ->lockForUpdate()
                ->first();

        if ($existing !== null) {

            if ((int) $existing->country_id !== $countryId) {
                throw new RuntimeException(
                    "Alias conflict during transaction for {$sourceName}."
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
| Alias verification
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "ALIAS VERIFICATION\n";
echo "========================================\n";

$aliasVerificationPass = true;

foreach ($aliases as $sourceName => $countryId) {

    $alias =
        DB::table('trade_country_aliases')
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

    $pass =
        $alias !== null
        &&
        (int) $alias->country_id === $countryId
        &&
        (int) $alias->is_active === 1;

    echo sprintf(
        "  %-42s : %s",
        $sourceName,
        $pass ? 'PASS' : 'FAIL'
    );

    if ($alias !== null) {
        echo " -> alias_id={$alias->id}"
            . " country_id={$alias->country_id}";
    }

    echo PHP_EOL;

    if (!$pass) {
        $aliasVerificationPass = false;
    }
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Resolver verification
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "RESOLVER VERIFICATION\n";
echo "========================================\n";

$resolver =
    app(CountryResolverService::class);

$resolverVerificationPass = true;

foreach ($aliases as $sourceName => $countryId) {

    $resolvedCountry =
        $resolver->resolve(
            $sourceName
        );

    $pass =
        $resolvedCountry !== null
        &&
        (int) $resolvedCountry->id === $countryId;

    echo sprintf(
        "  %-42s : %s",
        $sourceName,
        $pass ? 'PASS' : 'FAIL'
    );

    if ($resolvedCountry !== null) {
        echo " -> ID={$resolvedCountry->id}"
            . " {$resolvedCountry->country_code}/{$resolvedCountry->iso3}"
            . " | {$resolvedCountry->country_name_en}";
    }

    echo PHP_EOL;

    if (!$pass) {
        $resolverVerificationPass = false;
    }
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Trade statistics safety check
|--------------------------------------------------------------------------
*/

$tradeStatsCount =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->count();

$tradeStatsIdentities =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->distinct('trade_identity')
        ->count('trade_identity');

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

echo "  TARGET NULL RECORDS : {$targetNullRecords}\n";
echo "  TRADE RECORDS       : {$tradeStatsCount}\n";
echo "  DISTINCT IDENTITY   : {$tradeStatsIdentities}\n";
echo "  TRADE STATISTICS    : NOT MODIFIED\n\n";

/*
|--------------------------------------------------------------------------
| Final gate
|--------------------------------------------------------------------------
*/

$success =
    ($inserted + $alreadyRegistered) === 28
    &&
    $existingConflicts === 0
    &&
    $aliasVerificationPass
    &&
    $resolverVerificationPass
    &&
    $targetNullRecords === 1964
    &&
    $tradeStatsCount === 2266312
    &&
    $tradeStatsIdentities === 2266312;

echo "========================================\n";
echo "FINAL TERRITORY ALIAS INSERT GATE\n";
echo "========================================\n";

echo "  MASTER VALIDATION   : PASS\n";
echo "  ALIAS VERIFICATION  : "
    . ($aliasVerificationPass ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo "  RESOLVER TEST       : "
    . ($resolverVerificationPass ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo "  TARGET NULL RECORDS : {$targetNullRecords}\n";
echo "  TRADE DATA UPDATED  : NO\n\n";

if ($success) {
    echo "TERRITORY COUNTRY ALIAS INSERT : PASS\n";
} else {
    echo "TERRITORY COUNTRY ALIAS INSERT : REVIEW\n";
    exit(1);
}

echo "========================================\n";