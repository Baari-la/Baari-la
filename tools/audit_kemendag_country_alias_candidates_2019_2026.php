<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

const TOP_N = 20;

echo "========================================\n";
echo "DIGESTEX KEMENDAG COUNTRY ALIAS CANDIDATE AUDIT\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  TOP N       : " . TOP_N . "\n";
echo "  DATABASE    : READ ONLY\n\n";

/*
|--------------------------------------------------------------------------
| Current unresolved country names
|--------------------------------------------------------------------------
*/

$rows =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('country_id')
        ->whereNotNull('country_name')
        ->where('country_name', '<>', '')
        ->selectRaw(
            '
            country_name,
            COUNT(*) AS records,
            SUM(trade_value) AS trade_value,
            SUM(trade_volume) AS trade_volume
            '
        )
        ->groupBy('country_name')
        ->orderByDesc('records')
        ->limit(TOP_N)
        ->get();

/*
|--------------------------------------------------------------------------
| Candidate dictionary
|--------------------------------------------------------------------------
|
| Explicit deterministic mappings for source nomenclature
| that are clearly country aliases.
|
| Special nomenclature is intentionally excluded.
|--------------------------------------------------------------------------
*/

$candidates = [

    'SRI LANGKA' => [
        'classification' => 'ALIAS',
        'country_name_en' => 'Sri Lanka',
        'country_name_id' => 'Sri Lanka',
        'country_code' => 'LK',
        'iso3' => 'LKA',
        'confidence' => 'HIGH',
        'reason' => 'Direct historical Indonesian/Kemendag nomenclature for Sri Lanka.',
    ],

    'REPUBLIK CZECH' => [
        'classification' => 'ALIAS',
        'country_name_en' => 'Czech Republic',
        'country_name_id' => 'Republik Ceko',
        'country_code' => 'CZ',
        'iso3' => 'CZE',
        'confidence' => 'HIGH',
        'reason' => 'Direct source nomenclature for Czech Republic.',
    ],

    'LUKSEMBURG' => [
        'classification' => 'ALIAS',
        'country_name_en' => 'Luxembourg',
        'country_name_id' => 'Luksemburg',
        'country_code' => 'LU',
        'iso3' => 'LUX',
        'confidence' => 'HIGH',
        'reason' => 'Indonesian source spelling of Luxembourg.',
    ],

    'SIPRUS' => [
        'classification' => 'ALIAS',
        'country_name_en' => 'Cyprus',
        'country_name_id' => 'Siprus',
        'country_code' => 'CY',
        'iso3' => 'CYP',
        'confidence' => 'HIGH',
        'reason' => 'Indonesian source spelling of Cyprus.',
    ],

    'LIBIA' => [
        'classification' => 'ALIAS',
        'country_name_en' => 'Libya',
        'country_name_id' => 'Libya',
        'country_code' => 'LY',
        'iso3' => 'LBY',
        'confidence' => 'HIGH',
        'reason' => 'Indonesian source spelling of Libya.',
    ],

    'REPUBLIK MACEDONIA' => [
        'classification' => 'ALIAS',
        'country_name_en' => 'North Macedonia',
        'country_name_id' => 'Makedonia Utara',
        'country_code' => 'MK',
        'iso3' => 'MKD',
        'confidence' => 'HIGH',
        'reason' => 'Historical source nomenclature for North Macedonia.',
    ],

    'REPUBLIK DEMOKRATIK KONGO' => [
        'classification' => 'ALIAS',
        'country_name_en' => 'Democratic Republic of the Congo',
        'country_name_id' => 'Republik Demokratik Kongo',
        'country_code' => 'CD',
        'iso3' => 'COD',
        'confidence' => 'HIGH',
        'reason' => 'Explicit source nomenclature identifying Democratic Republic of the Congo.',
    ],

    'ISLANDIA' => [
        'classification' => 'ALIAS',
        'country_name_en' => 'Iceland',
        'country_name_id' => 'Islandia',
        'country_code' => 'IS',
        'iso3' => 'ISL',
        'confidence' => 'HIGH',
        'reason' => 'Indonesian source spelling of Iceland.',
    ],

    'REP.AFRIKA TENGAH' => [
        'classification' => 'ALIAS',
        'country_name_en' => 'Central African Republic',
        'country_name_id' => 'Republik Afrika Tengah',
        'country_code' => 'CF',
        'iso3' => 'CAF',
        'confidence' => 'HIGH',
        'reason' => 'Abbreviated Indonesian source nomenclature.',
    ],

    'PALESTINA' => [
        'classification' => 'SPECIAL',
        'country_name_en' => 'Palestine',
        'country_name_id' => 'Palestina',
        'country_code' => 'PS',
        'iso3' => 'PSE',
        'confidence' => 'REVIEW',
        'reason' => 'Use special/review classification rather than automatic country backfill.',
    ],

    'INDONESIA (BATAM)' => [
        'classification' => 'SPECIAL',
        'country_name_en' => 'Indonesia',
        'country_name_id' => 'Indonesia',
        'country_code' => 'ID',
        'iso3' => 'IDN',
        'confidence' => 'REVIEW',
        'reason' => 'Source explicitly identifies BATAM; do not collapse automatically to ordinary Indonesia country mapping.',
    ],

    'NETHERLANDS ANTILLES' => [
        'classification' => 'TERRITORY_DEPENDENCY',
        'country_name_en' => null,
        'country_name_id' => null,
        'country_code' => null,
        'iso3' => null,
        'confidence' => 'REVIEW',
        'reason' => 'Historical territory/dependency nomenclature.',
    ],

    'VIRGIN ISLANDS (BRITISH)' => [
        'classification' => 'TERRITORY_DEPENDENCY',
        'country_name_en' => 'British Virgin Islands',
        'country_name_id' => 'Kepulauan Virgin Inggris',
        'country_code' => 'VG',
        'iso3' => 'VGB',
        'confidence' => 'HIGH',
        'reason' => 'Recognizable territory/dependency; should be represented according to master-data policy.',
    ],

    'U.S. VIRGIN ISLANDS' => [
        'classification' => 'TERRITORY_DEPENDENCY',
        'country_name_en' => 'United States Virgin Islands',
        'country_name_id' => 'Kepulauan Virgin Amerika Serikat',
        'country_code' => 'VI',
        'iso3' => 'VIR',
        'confidence' => 'HIGH',
        'reason' => 'Recognizable territory/dependency.',
    ],

    'LIECHTENSTEIN' => [
        'classification' => 'ALIAS',
        'country_name_en' => 'Liechtenstein',
        'country_name_id' => 'Liechtenstein',
        'country_code' => 'LI',
        'iso3' => 'LIE',
        'confidence' => 'HIGH',
        'reason' => 'Direct country name.',
    ],

    'MAURITANIA' => [
        'classification' => 'ALIAS',
        'country_name_en' => 'Mauritania',
        'country_name_id' => 'Mauritania',
        'country_code' => 'MR',
        'iso3' => 'MRT',
        'confidence' => 'HIGH',
        'reason' => 'Direct country name.',
    ],

    'ZAMBIA' => [
        'classification' => 'ALIAS',
        'country_name_en' => 'Zambia',
        'country_name_id' => 'Zambia',
        'country_code' => 'ZM',
        'iso3' => 'ZMB',
        'confidence' => 'HIGH',
        'reason' => 'Direct country name.',
    ],

    'BOTSWANA' => [
        'classification' => 'ALIAS',
        'country_name_en' => 'Botswana',
        'country_name_id' => 'Botswana',
        'country_code' => 'BW',
        'iso3' => 'BWA',
        'confidence' => 'HIGH',
        'reason' => 'Direct country name.',
    ],
];

/*
|--------------------------------------------------------------------------
| Master lookup
|--------------------------------------------------------------------------
*/

function findCountryMaster(array $candidate): ?object
{
    if (
        $candidate['iso3'] !== null
    ) {
        $country =
            DB::table('mst_countries')
                ->where('iso3', $candidate['iso3'])
                ->first();

        if ($country !== null) {
            return $country;
        }
    }

    if (
        $candidate['country_code'] !== null
    ) {
        $country =
            DB::table('mst_countries')
                ->where(
                    'country_code',
                    $candidate['country_code']
                )
                ->first();

        if ($country !== null) {
            return $country;
        }
    }

    return null;
}

/*
|--------------------------------------------------------------------------
| Report
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "TOP UNRESOLVED COUNTRY NAMES\n";
echo "========================================\n";

foreach ($rows as $row) {

    $source =
        trim((string) $row->country_name);

    $candidate =
        $candidates[$source]
        ?? null;

    if ($candidate === null) {

        echo sprintf(
            "  %-38s | %7d records | CLASSIFICATION=REVIEW\n",
            $source,
            $row->records
        );

        continue;
    }

    $master =
        findCountryMaster($candidate);

    echo sprintf(
        "  %-38s | %7d records | %-20s | CONF=%-6s | MASTER=%s\n",
        $source,
        $row->records,
        $candidate['classification'],
        $candidate['confidence'],
        $master !== null
            ? 'FOUND'
            : 'MISSING'
    );

    echo "      PROPOSED : "
        . (
            $candidate['country_name_en']
            ?? '-'
        )
        . " | "
        . (
            $candidate['country_code']
            ?? '-'
        )
        . " | "
        . (
            $candidate['iso3']
            ?? '-'
        )
        . PHP_EOL;

    echo "      REASON   : "
        . $candidate['reason']
        . PHP_EOL;
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Summary by classification
|--------------------------------------------------------------------------
*/

$classificationTotals = [];

foreach ($rows as $row) {

    $source =
        trim((string) $row->country_name);

    $classification =
        $candidates[$source]['classification']
        ?? 'REVIEW';

    if (!isset(
        $classificationTotals[$classification]
    )) {
        $classificationTotals[$classification] = 0;
    }

    $classificationTotals[$classification] +=
        (int) $row->records;
}

echo "========================================\n";
echo "CLASSIFICATION SUMMARY\n";
echo "========================================\n";

foreach (
    $classificationTotals as $classification => $records
) {
    echo sprintf(
        "  %-24s : %d records\n",
        $classification,
        $records
    );
}

echo PHP_EOL;

echo "========================================\n";
echo "DATABASE SAFETY\n";
echo "========================================\n";

echo "  INSERT : NO\n";
echo "  UPDATE : NO\n";
echo "  DELETE : NO\n";
echo "  DATABASE WAS NOT MODIFIED.\n";

echo "========================================\n";
echo "COUNTRY ALIAS CANDIDATE AUDIT : COMPLETE\n";
echo "========================================\n";