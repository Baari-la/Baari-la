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
echo "DIGESTEX KEMENDAG FRENCH GUIANA ALIAS INSERT\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  SOURCE SYSTEM : KEMENDAG\n";
echo "  SOURCE NAME   : GUIANA PERANCIS\n";
echo "  TARGET ID     : 71\n";
echo "  OPERATION     : TRANSACTIONAL INSERT\n\n";

/*
|--------------------------------------------------------------------------
| Master validation
|--------------------------------------------------------------------------
*/

$master = DB::table('mst_countries')
    ->where('id', 71)
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
        'mst_countries.id=71 tidak ditemukan.'
    );
}

if (
    strtoupper((string) $master->country_code) !== 'GF'
    ||
    strtoupper((string) $master->iso3) !== 'GUF'
    ||
    (string) $master->country_name_en !== 'French Guiana'
    ||
    (int) $master->is_active !== 1
) {
    throw new RuntimeException(
        'Master country ID 71 tidak sesuai dengan French Guiana.'
    );
}

echo "MASTER VALIDATION:\n";
echo "  PASS -> ID={$master->id}"
    . " {$master->country_code}/{$master->iso3}"
    . " | {$master->country_name_en}"
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Existing alias check
|--------------------------------------------------------------------------
*/

$existing = DB::table('trade_country_aliases')
    ->where('source_system', 'KEMENDAG')
    ->where('normalized_name', 'GUIANA PERANCIS')
    ->first([
        'id',
        'country_id',
        'source_name',
        'normalized_name',
        'source_system',
        'is_active',
    ]);

if ($existing !== null) {

    if ((int) $existing->country_id !== 71) {
        throw new RuntimeException(
            'Alias GUIANA PERANCIS sudah terdaftar tetapi menunjuk ke country_id='
            . $existing->country_id
        );
    }

    echo "ALIAS STATUS:\n";
    echo "  ALREADY REGISTERED"
        . " -> alias_id={$existing->id}"
        . " country_id={$existing->country_id}"
        . PHP_EOL;

} else {

    echo "ALIAS STATUS:\n";
    echo "  NOT REGISTERED\n";
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Transactional insert
|--------------------------------------------------------------------------
*/

$inserted = 0;
$alreadyRegistered = 0;

DB::transaction(function () use (
    &$inserted,
    &$alreadyRegistered
): void {

    $existing = DB::table('trade_country_aliases')
        ->where('source_system', 'KEMENDAG')
        ->where('normalized_name', 'GUIANA PERANCIS')
        ->lockForUpdate()
        ->first();

    if ($existing !== null) {

        if ((int) $existing->country_id !== 71) {
            throw new RuntimeException(
                'Alias conflict saat transaction.'
            );
        }

        $alreadyRegistered++;
        return;
    }

    DB::table('trade_country_aliases')->insert([
        'country_id' => 71,
        'source_name' => 'GUIANA PERANCIS',
        'normalized_name' => 'GUIANA PERANCIS',
        'source_system' => 'KEMENDAG',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $inserted++;
});

echo "INSERT RESULT:\n";
echo "  INSERTED           : {$inserted}\n";
echo "  ALREADY REGISTERED : {$alreadyRegistered}\n\n";

/*
|--------------------------------------------------------------------------
| Alias verification
|--------------------------------------------------------------------------
*/

$alias = DB::table('trade_country_aliases')
    ->where('source_system', 'KEMENDAG')
    ->where('normalized_name', 'GUIANA PERANCIS')
    ->first([
        'id',
        'country_id',
        'source_name',
        'normalized_name',
        'source_system',
        'is_active',
    ]);

if (
    $alias === null
    ||
    (int) $alias->country_id !== 71
    ||
    (int) $alias->is_active !== 1
) {
    throw new RuntimeException(
        'Alias verification gagal.'
    );
}

echo "ALIAS VERIFICATION:\n";
echo "  PASS -> alias_id={$alias->id}"
    . " country_id={$alias->country_id}"
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Resolver verification
|--------------------------------------------------------------------------
*/

$resolver = app(CountryResolverService::class);

$resolvedCountry =
    $resolver->resolve('GUIANA PERANCIS');

if (
    $resolvedCountry === null
    ||
    (int) $resolvedCountry->id !== 71
) {
    throw new RuntimeException(
        'CountryResolverService gagal resolve GUIANA PERANCIS.'
    );
}

echo "RESOLVER VERIFICATION:\n";
echo "  PASS -> ID={$resolvedCountry->id}"
    . " {$resolvedCountry->country_code}/{$resolvedCountry->iso3}"
    . " | {$resolvedCountry->country_name_en}"
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Trade statistics safety check
|--------------------------------------------------------------------------
*/

$targetNullRecords =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('country_id')
        ->where('country_name', 'GUIANA PERANCIS')
        ->count();

echo "TRADE STATISTICS SAFETY CHECK:\n";
echo "  NULL TARGET RECORDS : {$targetNullRecords}\n";
echo "  TRADE STATISTICS    : UNMODIFIED\n";

if ($targetNullRecords !== 34) {
    throw new RuntimeException(
        "Unexpected target count. Expected 34, got {$targetNullRecords}."
    );
}

echo PHP_EOL;

echo "========================================\n";
echo "FRENCH GUIANA ALIAS INSERT : PASS\n";
echo "========================================\n";