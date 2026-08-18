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
echo "DIGESTEX KEMENDAG COUNTRY RESOLVABLE BACKFILL DRY-RUN\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  OPERATION   : READ ONLY\n\n";

$resolver = app(CountryResolverService::class);

/*
|--------------------------------------------------------------------------
| Find unresolved country records
|--------------------------------------------------------------------------
*/

$rows = DB::table('trade_statistics')
    ->whereBetween('year', [2019, 2026])
    ->whereIn('trade_flow', ['export', 'import'])
    ->whereNull('country_id')
    ->whereNotNull('country_name')
    ->where('country_name', '<>', '')
    ->select(
        'id',
        'year',
        'month',
        'trade_flow',
        'country_name',
        'country_code',
        'province_name',
        'port_name',
        'hs_code',
        'trade_value',
        'trade_volume',
        'import_batch_id'
    )
    ->orderBy('id')
    ->get();

echo "NULL COUNTRY RECORDS : "
    . $rows->count()
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Resolve only currently resolvable source names
|--------------------------------------------------------------------------
*/

$resolved = 0;
$unresolved = 0;

$resolvedByCountry = [];

$updates = [];

foreach ($rows as $row) {

    $country =
        $resolver->resolve(
            $row->country_name
        );

    if ($country === null) {

        $unresolved++;

        continue;
    }

    $resolved++;

    $canonicalName =
        $country->country_name_en
        ?? null;

    $countryId =
        (int) $country->id;

    $countryCode =
        $country->country_code
        ?? null;

    $iso3 =
        $country->iso3
        ?? null;

    $key =
        $countryId . '|'
        . ($countryCode ?? '');

    if (!isset($resolvedByCountry[$key])) {

        $resolvedByCountry[$key] = [
            'country_id' =>
                $countryId,

            'country_code' =>
                $countryCode,

            'iso3' =>
                $iso3,

            'canonical_name' =>
                $canonicalName,

            'records' =>
                0,

            'trade_value' =>
                0.0,

            'trade_volume' =>
                0.0,
        ];
    }

    $resolvedByCountry[$key]['records']++;

    $resolvedByCountry[$key]['trade_value'] +=
        (float) $row->trade_value;

    $resolvedByCountry[$key]['trade_volume'] +=
        (float) $row->trade_volume;

    $updates[] = [
        'id' =>
            (int) $row->id,

        'country_id' =>
            $countryId,

        'country_code' =>
            $countryCode,

        'canonical_name' =>
            $canonicalName,
    ];
}

ksort($resolvedByCountry);

/*
|--------------------------------------------------------------------------
| Summary
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "BACKFILL SUMMARY\n";
echo "========================================\n";

echo "  NULL BEFORE        : "
    . $rows->count()
    . PHP_EOL;

echo "  RESOLVABLE RECORDS : "
    . $resolved
    . PHP_EOL;

echo "  UNRESOLVED RECORDS : "
    . $unresolved
    . PHP_EOL;

echo "  WOULD UPDATE       : "
    . count($updates)
    . PHP_EOL;

echo "  RESOLVED COUNTRIES : "
    . count($resolvedByCountry)
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Country breakdown
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "RESOLVABLE COUNTRY BREAKDOWN\n";
echo "========================================\n";

foreach (
    $resolvedByCountry as $country
) {

    echo sprintf(
        "  %-30s | %-3s | %-3s | %7d records | VALUE=%15.3f | VOLUME=%15.3f\n",
        $country['canonical_name'] ?? '-',
        $country['country_code'] ?? '-',
        $country['iso3'] ?? '-',
        $country['records'],
        $country['trade_value'],
        $country['trade_volume']
    );
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Optional source-name proof
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "RESOLVABLE SOURCE NAME SAMPLE\n";
echo "========================================\n";

$sampleCount = 0;

foreach ($rows as $row) {

    if ($sampleCount >= 50) {
        break;
    }

    $country =
        $resolver->resolve(
            $row->country_name
        );

    if ($country === null) {
        continue;
    }

    echo sprintf(
        "  SOURCE %-35s -> ID=%d | %s [%s/%s]\n",
        $row->country_name,
        (int) $country->id,
        $country->country_name_en ?? '-',
        $country->country_code ?? '-',
        $country->iso3 ?? '-'
    );

    $sampleCount++;
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Database safety
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "DATABASE SAFETY\n";
echo "========================================\n";

echo "  INSERT : NO\n";
echo "  UPDATE : NO\n";
echo "  DELETE : NO\n";
echo "  DATABASE WAS NOT MODIFIED.\n";

echo PHP_EOL;

if (
    $resolved === 8146
    &&
    $unresolved === 31698
) {
    echo "COUNTRY RESOLVABLE BACKFILL DRY-RUN : PASS\n";
} else {
    echo "COUNTRY RESOLVABLE BACKFILL DRY-RUN : REVIEW\n";
}

echo "========================================\n";