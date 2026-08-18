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
echo "DIGESTEX KEMENDAG COUNTRY MASTER INSERT\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  SOURCE SYSTEM : KEMENDAG\n";
echo "  CANDIDATES    : 23\n";
echo "  OPERATION     : TRANSACTIONAL INSERT\n\n";

/*
|--------------------------------------------------------------------------
| Approved master candidates
|--------------------------------------------------------------------------
*/

$candidates = [
    'LUKSEMBURG' => [
        'country_code' => 'LU',
        'iso3' => 'LUX',
        'country_name_en' => 'Luxembourg',
        'country_name_id' => 'Luksemburg',
    ],

    'SIPRUS' => [
        'country_code' => 'CY',
        'iso3' => 'CYP',
        'country_name_en' => 'Cyprus',
        'country_name_id' => 'Siprus',
    ],

    'REPUBLIK MACEDONIA' => [
        'country_code' => 'MK',
        'iso3' => 'MKD',
        'country_name_en' => 'North Macedonia',
        'country_name_id' => 'Makedonia Utara',
    ],

    'ANDORRA' => [
        'country_code' => 'AD',
        'iso3' => 'AND',
        'country_name_en' => 'Andorra',
        'country_name_id' => 'Andorra',
    ],

    'REPUBLIK DEMOKRATIK KONGO' => [
        'country_code' => 'CD',
        'iso3' => 'COD',
        'country_name_en' => 'Democratic Republic of the Congo',
        'country_name_id' => 'Republik Demokratik Kongo',
    ],

    'ISLANDIA' => [
        'country_code' => 'IS',
        'iso3' => 'ISL',
        'country_name_en' => 'Iceland',
        'country_name_id' => 'Islandia',
    ],

    'REP.AFRIKA TENGAH' => [
        'country_code' => 'CF',
        'iso3' => 'CAF',
        'country_name_en' => 'Central African Republic',
        'country_name_id' => 'Republik Afrika Tengah',
    ],

    'SAINT LUCIA' => [
        'country_code' => 'LC',
        'iso3' => 'LCA',
        'country_name_en' => 'Saint Lucia',
        'country_name_id' => 'Saint Lucia',
    ],

    'GABON' => [
        'country_code' => 'GA',
        'iso3' => 'GAB',
        'country_name_en' => 'Gabon',
        'country_name_id' => 'Gabon',
    ],

    'MAURITANIA' => [
        'country_code' => 'MR',
        'iso3' => 'MRT',
        'country_name_en' => 'Mauritania',
        'country_name_id' => 'Mauritania',
    ],

    'ZAMBIA' => [
        'country_code' => 'ZM',
        'iso3' => 'ZMB',
        'country_name_en' => 'Zambia',
        'country_name_id' => 'Zambia',
    ],

    'CHAD' => [
        'country_code' => 'TD',
        'iso3' => 'TCD',
        'country_name_en' => 'Chad',
        'country_name_id' => 'Chad',
    ],

    'BOTSWANA' => [
        'country_code' => 'BW',
        'iso3' => 'BWA',
        'country_name_en' => 'Botswana',
        'country_name_id' => 'Botswana',
    ],

    'MALAWI' => [
        'country_code' => 'MW',
        'iso3' => 'MWI',
        'country_name_en' => 'Malawi',
        'country_name_id' => 'Malawi',
    ],

    'RWANDA' => [
        'country_code' => 'RW',
        'iso3' => 'RWA',
        'country_name_en' => 'Rwanda',
        'country_name_id' => 'Rwanda',
    ],

    'NIGER' => [
        'country_code' => 'NE',
        'iso3' => 'NER',
        'country_name_en' => 'Niger',
        'country_name_id' => 'Niger',
    ],

    'DOMINIKA' => [
        'country_code' => 'DM',
        'iso3' => 'DMA',
        'country_name_en' => 'Dominica',
        'country_name_id' => 'Dominika',
    ],

    'TAJIKISTAN' => [
        'country_code' => 'TJ',
        'iso3' => 'TJK',
        'country_name_en' => 'Tajikistan',
        'country_name_id' => 'Tajikistan',
    ],

    'TURKMENISTAN' => [
        'country_code' => 'TM',
        'iso3' => 'TKM',
        'country_name_en' => 'Turkmenistan',
        'country_name_id' => 'Turkmenistan',
    ],

    'ERITREA' => [
        'country_code' => 'ER',
        'iso3' => 'ERI',
        'country_name_en' => 'Eritrea',
        'country_name_id' => 'Eritrea',
    ],

    'SAN MARINO' => [
        'country_code' => 'SM',
        'iso3' => 'SMR',
        'country_name_en' => 'San Marino',
        'country_name_id' => 'San Marino',
    ],

    'SAO TOME DAN PRINCIPE' => [
        'country_code' => 'ST',
        'iso3' => 'STP',
        'country_name_en' => 'Sao Tome and Principe',
        'country_name_id' => 'Sao Tome dan Principe',
    ],

    'SUDAN SELATAN' => [
        'country_code' => 'SS',
        'iso3' => 'SSD',
        'country_name_en' => 'South Sudan',
        'country_name_id' => 'Sudan Selatan',
    ],
];

/*
|--------------------------------------------------------------------------
| Master pre-check
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "MASTER PRE-CHECK\n";
echo "========================================\n";

foreach ($candidates as $sourceName => $candidate) {

    $byCode =
        DB::table('mst_countries')
            ->where(
                'country_code',
                $candidate['country_code']
            )
            ->first();

    if ($byCode !== null) {
        throw new RuntimeException(
            "Country code conflict for {$sourceName}: "
            . $candidate['country_code']
        );
    }

    $byIso3 =
        DB::table('mst_countries')
            ->where(
                'iso3',
                $candidate['iso3']
            )
            ->first();

    if ($byIso3 !== null) {
        throw new RuntimeException(
            "ISO3 conflict for {$sourceName}: "
            . $candidate['iso3']
        );
    }

    $byName =
        DB::table('mst_countries')
            ->whereRaw(
                'UPPER(country_name_en) = ?',
                [
                    mb_strtoupper(
                        $candidate['country_name_en']
                    ),
                ]
            )
            ->first();

    if ($byName !== null) {
        throw new RuntimeException(
            "Canonical country name already exists for {$sourceName}: "
            . $candidate['country_name_en']
        );
    }

    echo "  {$sourceName} : PRE-CHECK PASS\n";
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Transactional INSERT
|--------------------------------------------------------------------------
*/

$inserted = 0;
$alreadyExisting = 0;

DB::transaction(function () use (
    $candidates,
    &$inserted,
    &$alreadyExisting
): void {

    foreach ($candidates as $sourceName => $candidate) {

        /*
        |--------------------------------------------------------------------------
        | Re-check under transaction / lock
        |--------------------------------------------------------------------------
        */

        $byCode =
            DB::table('mst_countries')
                ->where(
                    'country_code',
                    $candidate['country_code']
                )
                ->lockForUpdate()
                ->first();

        if ($byCode !== null) {

            /*
             * Idempotent safety:
             * accept only if the existing record exactly matches.
             */
            if (
                strtoupper((string) $byCode->iso3)
                    !==
                    strtoupper($candidate['iso3'])
                ||
                (string) $byCode->country_name_en
                    !==
                    $candidate['country_name_en']
            ) {
                throw new RuntimeException(
                    "Country code collision during transaction: "
                    . $sourceName
                );
            }

            $alreadyExisting++;
            continue;
        }

        $byIso3 =
            DB::table('mst_countries')
                ->where(
                    'iso3',
                    $candidate['iso3']
                )
                ->lockForUpdate()
                ->first();

        if ($byIso3 !== null) {

            if (
                strtoupper((string) $byIso3->country_code)
                    !==
                    strtoupper($candidate['country_code'])
            ) {
                throw new RuntimeException(
                    "ISO3 collision during transaction: "
                    . $sourceName
                );
            }

            $alreadyExisting++;
            continue;
        }

        DB::table('mst_countries')->insert([
            'country_code' => $candidate['country_code'],
            'iso3' => $candidate['iso3'],
            'country_name_en' => $candidate['country_name_en'],
            'country_name_id' => $candidate['country_name_id'],
            'official_name' => null,
            'region_code' => null,
            'region_en' => null,
            'region_id' => null,
            'sub_region_en' => null,
            'sub_region_id' => null,
            'flag_emoji' => null,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $inserted++;
    }
});

echo "INSERT RESULT:\n";
echo "  INSERTED         : {$inserted}\n";
echo "  ALREADY EXISTING : {$alreadyExisting}\n\n";

/*
|--------------------------------------------------------------------------
| Verification
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "MASTER VERIFICATION\n";
echo "========================================\n";

$verificationPassed = true;

foreach ($candidates as $sourceName => $candidate) {

    $master =
        DB::table('mst_countries')
            ->where(
                'country_code',
                $candidate['country_code']
            )
            ->first([
                'id',
                'country_code',
                'iso3',
                'country_name_en',
                'country_name_id',
                'is_active',
            ]);

    if (
        $master === null
        ||
        strtoupper((string) $master->country_code)
            !==
            strtoupper($candidate['country_code'])
        ||
        strtoupper((string) ($master->iso3 ?? ''))
            !==
            strtoupper($candidate['iso3'])
        ||
        (string) $master->country_name_en
            !==
            $candidate['country_name_en']
        ||
        (int) $master->is_active !== 1
    ) {

        $verificationPassed = false;

        echo "  {$sourceName} : FAIL\n";
        continue;
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
| Count active master
|--------------------------------------------------------------------------
*/

$insertedIds =
    DB::table('mst_countries')
        ->whereIn(
            'iso3',
            array_column(
                $candidates,
                'iso3'
            )
        )
        ->where('is_active', 1)
        ->count();

echo "MASTER COUNT CHECK:\n";
echo "  EXPECTED ACTIVE CANDIDATES : "
    . count($candidates)
    . PHP_EOL;

echo "  FOUND ACTIVE CANDIDATES    : "
    . $insertedIds
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Trade data safety
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

echo "TRADE DATA SAFETY:\n";
echo "  TRADE RECORDS     : {$tradeStatsCount}\n";
echo "  DISTINCT IDENTITY : {$tradeStatsIdentities}\n";
echo "  TRADE STATISTICS  : NOT MODIFIED\n";

echo PHP_EOL;

$success =
    ($inserted + $alreadyExisting) === 23
    &&
    $verificationPassed
    &&
    $insertedIds === 23
    &&
    $tradeStatsCount === 2266312
    &&
    $tradeStatsIdentities === 2266312;

echo "========================================\n";

if ($success) {
    echo "COUNTRY MASTER INSERT : PASS\n";
} else {
    echo "COUNTRY MASTER INSERT : REVIEW\n";
    exit(1);
}

echo "========================================\n";