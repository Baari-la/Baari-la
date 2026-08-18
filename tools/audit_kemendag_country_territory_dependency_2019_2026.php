<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "========================================\n";
echo "DIGESTEX KEMENDAG TERRITORY / DEPENDENCY AUDIT\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  OPERATION   : READ ONLY\n\n";

/*
|--------------------------------------------------------------------------
| Territory / dependency candidates identified from previous audit
|--------------------------------------------------------------------------
*/

$territories = [

    'NETHERLANDS ANTILLES' => [
        'name_en' => 'Netherlands Antilles',
        'country_code' => null,
        'iso3' => null,
        'classification' => 'HISTORICAL_TERRITORY',
    ],

    'VIRGIN ISLANDS (BRITISH)' => [
        'name_en' => 'British Virgin Islands',
        'country_code' => 'VG',
        'iso3' => 'VGB',
        'classification' => 'TERRITORY',
    ],

    'U.S. VIRGIN ISLANDS' => [
        'name_en' => 'United States Virgin Islands',
        'country_code' => 'VI',
        'iso3' => 'VIR',
        'classification' => 'TERRITORY',
    ],

    'KEPULAUAN TURKS DAN CAICOS' => [
        'name_en' => 'Turks and Caicos Islands',
        'country_code' => 'TC',
        'iso3' => 'TCA',
        'classification' => 'TERRITORY',
    ],

    'KEPULAUAN CAYMAN' => [
        'name_en' => 'Cayman Islands',
        'country_code' => 'KY',
        'iso3' => 'CYM',
        'classification' => 'TERRITORY',
    ],

    'KEP. VALLIS DAN FUTUNA' => [
        'name_en' => 'Wallis and Futuna',
        'country_code' => 'WF',
        'iso3' => 'WLF',
        'classification' => 'TERRITORY',
    ],

    'ANGUILA' => [
        'name_en' => 'Anguilla',
        'country_code' => 'AI',
        'iso3' => 'AIA',
        'classification' => 'TERRITORY',
    ],

    'JERSEY' => [
        'name_en' => 'Jersey',
        'country_code' => 'JE',
        'iso3' => 'JEY',
        'classification' => 'DEPENDENCY',
    ],

    'MAYOTTE' => [
        'name_en' => 'Mayotte',
        'country_code' => 'YT',
        'iso3' => 'MYT',
        'classification' => 'TERRITORY',
    ],

    'ARUBA' => [
        'name_en' => 'Aruba',
        'country_code' => 'AW',
        'iso3' => 'ABW',
        'classification' => 'TERRITORY',
    ],

    'SAINT BARTHELEMY' => [
        'name_en' => 'Saint Barthelemy',
        'country_code' => 'BL',
        'iso3' => 'BLM',
        'classification' => 'TERRITORY',
    ],

    'SAMOA AMERIKA' => [
        'name_en' => 'American Samoa',
        'country_code' => 'AS',
        'iso3' => 'ASM',
        'classification' => 'TERRITORY',
    ],

    'GUIANA PERANCIS' => [
        'name_en' => 'French Guiana',
        'country_code' => 'GF',
        'iso3' => 'GUF',
        'classification' => 'TERRITORY',
    ],

    'TOKELAU' => [
        'name_en' => 'Tokelau',
        'country_code' => 'TK',
        'iso3' => 'TKL',
        'classification' => 'TERRITORY',
    ],

    'GIBRALTAR' => [
        'name_en' => 'Gibraltar',
        'country_code' => 'GI',
        'iso3' => 'GIB',
        'classification' => 'TERRITORY',
    ],

    'KEPULAUAN MARIANA UTARA' => [
        'name_en' => 'Northern Mariana Islands',
        'country_code' => 'MP',
        'iso3' => 'MNP',
        'classification' => 'TERRITORY',
    ],

    'SAINT MARTIN (FRENCH PART)' => [
        'name_en' => 'Saint Martin',
        'country_code' => 'MF',
        'iso3' => 'MAF',
        'classification' => 'TERRITORY',
    ],

    'KEPULAUAN CHRISTMAS' => [
        'name_en' => 'Christmas Island',
        'country_code' => 'CX',
        'iso3' => 'CXR',
        'classification' => 'TERRITORY',
    ],

    'U.S MINOR OUTLYING ISLAND' => [
        'name_en' => 'United States Minor Outlying Islands',
        'country_code' => 'UM',
        'iso3' => 'UMI',
        'classification' => 'TERRITORY_GROUP',
    ],

    'GUERNSEY' => [
        'name_en' => 'Guernsey',
        'country_code' => 'GG',
        'iso3' => 'GGY',
        'classification' => 'DEPENDENCY',
    ],

    'SAINT HELENA' => [
        'name_en' => 'Saint Helena',
        'country_code' => 'SH',
        'iso3' => 'SHN',
        'classification' => 'TERRITORY',
    ],

    'KEPULAUAN COCOS (KEELING)' => [
        'name_en' => 'Cocos (Keeling) Islands',
        'country_code' => 'CC',
        'iso3' => 'CCK',
        'classification' => 'TERRITORY',
    ],

    'KEPULAUAN FALKLAND (MALVINAS)' => [
        'name_en' => 'Falkland Islands',
        'country_code' => 'FK',
        'iso3' => 'FLK',
        'classification' => 'TERRITORY',
    ],

    'SINT MAARTEN (DUTCH PART)' => [
        'name_en' => 'Sint Maarten',
        'country_code' => 'SX',
        'iso3' => 'SXM',
        'classification' => 'TERRITORY',
    ],

    'KEPULAUAN NORFOLK' => [
        'name_en' => 'Norfolk Island',
        'country_code' => 'NF',
        'iso3' => 'NFK',
        'classification' => 'TERRITORY',
    ],

    'BRITISH INDIAN OCEAN TERRITORY' => [
        'name_en' => 'British Indian Ocean Territory',
        'country_code' => 'IO',
        'iso3' => 'IOT',
        'classification' => 'TERRITORY',
    ],

    'ANTARTICA' => [
        'name_en' => 'Antarctica',
        'country_code' => 'AQ',
        'iso3' => 'ATA',
        'classification' => 'SPECIAL_TERRITORY',
    ],

    'KEPULAUAN ALAND' => [
        'name_en' => 'Åland Islands',
        'country_code' => 'AX',
        'iso3' => 'ALA',
        'classification' => 'TERRITORY',
    ],

    'PULAU HEARD DAN KEPULAUAN MCDONALD' => [
        'name_en' => 'Heard Island and McDonald Islands',
        'country_code' => 'HM',
        'iso3' => 'HMD',
        'classification' => 'TERRITORY',
    ],

    'PITCAIRN' => [
        'name_en' => 'Pitcairn',
        'country_code' => 'PN',
        'iso3' => 'PCN',
        'classification' => 'TERRITORY',
    ],
];

/*
|--------------------------------------------------------------------------
| Query unresolved territory/dependency records
|--------------------------------------------------------------------------
*/

$rows =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('country_id')
        ->whereIn(
            'country_name',
            array_keys($territories)
        )
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
        ->get();

echo "========================================\n";
echo "TERRITORY / DEPENDENCY SUMMARY\n";
echo "========================================\n";

$totalRecords = 0;

foreach ($rows as $row) {

    $sourceName =
        trim((string) $row->country_name);

    $candidate =
        $territories[$sourceName] ?? null;

    $totalRecords +=
        (int) $row->records;

    if ($candidate === null) {

        echo sprintf(
            "  %-45s | %7d records | REVIEW\n",
            $sourceName,
            $row->records
        );

        continue;
    }

    echo sprintf(
        "  %-45s | %7d records | %-20s\n",
        $sourceName,
        $row->records,
        $candidate['classification']
    );

    echo "      TARGET : "
        . ($candidate['name_en'] ?? '-')
        . " | "
        . ($candidate['country_code'] ?? '-')
        . " | "
        . ($candidate['iso3'] ?? '-')
        . PHP_EOL;
}

echo PHP_EOL;

echo "TOTAL TERRITORY/DEPENDENCY RECORDS : "
    . $totalRecords
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Master country lookup
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "MASTER COUNTRY EXISTENCE CHECK\n";
echo "========================================\n";

$masterFound = 0;
$masterMissing = 0;
$masterNameOnly = 0;

foreach ($rows as $row) {

    $sourceName =
        trim((string) $row->country_name);

    $candidate =
        $territories[$sourceName]
        ?? null;

    if ($candidate === null) {
        continue;
    }

    $master = null;

    if ($candidate['iso3'] !== null) {
        $master =
            DB::table('mst_countries')
                ->where(
                    'iso3',
                    $candidate['iso3']
                )
                ->first();
    }

    if (
        $master === null
        &&
        $candidate['country_code'] !== null
    ) {
        $master =
            DB::table('mst_countries')
                ->where(
                    'country_code',
                    $candidate['country_code']
                )
                ->first();
    }

    if ($master !== null) {

        $masterFound++;

        echo sprintf(
            "  %-45s : MASTER FOUND -> ID=%d | %s/%s | %s\n",
            $sourceName,
            $master->id,
            $master->country_code,
            $master->iso3,
            $master->country_name_en
        );

        continue;
    }

    $canonicalNameExists =
        DB::table('mst_countries')
            ->whereRaw(
                'UPPER(country_name_en) = ?',
                [
                    mb_strtoupper(
                        $candidate['name_en']
                    ),
                ]
            )
            ->exists();

    if ($canonicalNameExists) {

        $masterNameOnly++;

        echo sprintf(
            "  %-45s : NAME EXISTS / CODE GAP\n",
            $sourceName
        );

        continue;
    }

    $masterMissing++;

    echo sprintf(
        "  %-45s : MASTER MISSING\n",
        $sourceName
    );
}

echo PHP_EOL;

echo "MASTER SUMMARY:\n";
echo "  MASTER FOUND        : {$masterFound}\n";
echo "  NAME ONLY / GAP     : {$masterNameOnly}\n";
echo "  MASTER MISSING      : {$masterMissing}\n";

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Alias existence check
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "KEMENDAG ALIAS CHECK\n";
echo "========================================\n";

$aliasRegistered = 0;
$aliasMissing = 0;

foreach ($rows as $row) {

    $sourceName =
        trim((string) $row->country_name);

    $alias =
        DB::table('trade_country_aliases')
            ->where('source_system', 'KEMENDAG')
            ->where(
                'normalized_name',
                $sourceName
            )
            ->first();

    if ($alias !== null) {

        $aliasRegistered++;

        echo sprintf(
            "  %-45s : REGISTERED -> country_id=%d\n",
            $sourceName,
            $alias->country_id
        );

    } else {

        $aliasMissing++;

        echo sprintf(
            "  %-45s : NOT REGISTERED\n",
            $sourceName
        );
    }
}

echo PHP_EOL;

echo "ALIAS SUMMARY:\n";
echo "  REGISTERED : {$aliasRegistered}\n";
echo "  MISSING    : {$aliasMissing}\n";

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Special historical territory
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "SPECIAL HISTORICAL TERRITORY\n";
echo "========================================\n";

$historical =
    $rows->firstWhere(
        'country_name',
        'NETHERLANDS ANTILLES'
    );

if ($historical !== null) {

    echo "  NETHERLANDS ANTILLES\n";
    echo "  RECORDS : {$historical->records}\n";
    echo "  POLICY  : REVIEW\n";
    echo "  REASON  : Historical nomenclature; should not automatically\n";
    echo "            collapse into a current sovereign country.\n";
} else {
    echo "  NETHERLANDS ANTILLES : NO RESIDUAL RECORDS\n";
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

echo "========================================\n";
echo "TERRITORY / DEPENDENCY AUDIT : COMPLETE\n";
echo "========================================\n";