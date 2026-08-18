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
echo "DIGESTEX KEMENDAG TERRITORY MASTER INSERT\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  SOURCE SYSTEM : KEMENDAG\n";
echo "  CANDIDATES    : 28\n";
echo "  OPERATION     : TRANSACTIONAL INSERT\n\n";

/*
|--------------------------------------------------------------------------
| Approved territory master candidates
|--------------------------------------------------------------------------
*/

$candidates = [
    'VIRGIN ISLANDS (BRITISH)' => [
        'country_code' => 'VG',
        'iso3' => 'VGB',
        'country_name_en' => 'British Virgin Islands',
        'country_name_id' => 'Kepulauan Virgin Britania',
    ],

    'U.S. VIRGIN ISLANDS' => [
        'country_code' => 'VI',
        'iso3' => 'VIR',
        'country_name_en' => 'United States Virgin Islands',
        'country_name_id' => 'Kepulauan Virgin Amerika Serikat',
    ],

    'KEPULAUAN TURKS DAN CAICOS' => [
        'country_code' => 'TC',
        'iso3' => 'TCA',
        'country_name_en' => 'Turks and Caicos Islands',
        'country_name_id' => 'Kepulauan Turks dan Caicos',
    ],

    'KEPULAUAN CAYMAN' => [
        'country_code' => 'KY',
        'iso3' => 'CYM',
        'country_name_en' => 'Cayman Islands',
        'country_name_id' => 'Kepulauan Cayman',
    ],

    'KEP. VALLIS DAN FUTUNA' => [
        'country_code' => 'WF',
        'iso3' => 'WLF',
        'country_name_en' => 'Wallis and Futuna',
        'country_name_id' => 'Wallis dan Futuna',
    ],

    'ANGUILA' => [
        'country_code' => 'AI',
        'iso3' => 'AIA',
        'country_name_en' => 'Anguilla',
        'country_name_id' => 'Anguilla',
    ],

    'JERSEY' => [
        'country_code' => 'JE',
        'iso3' => 'JEY',
        'country_name_en' => 'Jersey',
        'country_name_id' => 'Jersey',
    ],

    'MAYOTTE' => [
        'country_code' => 'YT',
        'iso3' => 'MYT',
        'country_name_en' => 'Mayotte',
        'country_name_id' => 'Mayotte',
    ],

    'ARUBA' => [
        'country_code' => 'AW',
        'iso3' => 'ABW',
        'country_name_en' => 'Aruba',
        'country_name_id' => 'Aruba',
    ],

    'SAINT BARTHELEMY' => [
        'country_code' => 'BL',
        'iso3' => 'BLM',
        'country_name_en' => 'Saint Barthelemy',
        'country_name_id' => 'Saint Barthelemy',
    ],

    'SAMOA AMERIKA' => [
        'country_code' => 'AS',
        'iso3' => 'ASM',
        'country_name_en' => 'American Samoa',
        'country_name_id' => 'Samoa Amerika',
    ],

    'TOKELAU' => [
        'country_code' => 'TK',
        'iso3' => 'TKL',
        'country_name_en' => 'Tokelau',
        'country_name_id' => 'Tokelau',
    ],

    'GIBRALTAR' => [
        'country_code' => 'GI',
        'iso3' => 'GIB',
        'country_name_en' => 'Gibraltar',
        'country_name_id' => 'Gibraltar',
    ],

    'KEPULAUAN MARIANA UTARA' => [
        'country_code' => 'MP',
        'iso3' => 'MNP',
        'country_name_en' => 'Northern Mariana Islands',
        'country_name_id' => 'Kepulauan Mariana Utara',
    ],

    'SAINT MARTIN (FRENCH PART)' => [
        'country_code' => 'MF',
        'iso3' => 'MAF',
        'country_name_en' => 'Saint Martin',
        'country_name_id' => 'Saint Martin',
    ],

    'KEPULAUAN CHRISTMAS' => [
        'country_code' => 'CX',
        'iso3' => 'CXR',
        'country_name_en' => 'Christmas Island',
        'country_name_id' => 'Pulau Christmas',
    ],

    'U.S MINOR OUTLYING ISLAND' => [
        'country_code' => 'UM',
        'iso3' => 'UMI',
        'country_name_en' => 'United States Minor Outlying Islands',
        'country_name_id' => 'Kepulauan Terluar Kecil Amerika Serikat',
    ],

    'GUERNSEY' => [
        'country_code' => 'GG',
        'iso3' => 'GGY',
        'country_name_en' => 'Guernsey',
        'country_name_id' => 'Guernsey',
    ],

    'SAINT HELENA' => [
        'country_code' => 'SH',
        'iso3' => 'SHN',
        'country_name_en' => 'Saint Helena',
        'country_name_id' => 'Saint Helena',
    ],

    'KEPULAUAN FALKLAND (MALVINAS)' => [
        'country_code' => 'FK',
        'iso3' => 'FLK',
        'country_name_en' => 'Falkland Islands',
        'country_name_id' => 'Kepulauan Falkland',
    ],

    'KEPULAUAN COCOS (KEELING)' => [
        'country_code' => 'CC',
        'iso3' => 'CCK',
        'country_name_en' => 'Cocos (Keeling) Islands',
        'country_name_id' => 'Kepulauan Cocos',
    ],

    'SINT MAARTEN (DUTCH PART)' => [
        'country_code' => 'SX',
        'iso3' => 'SXM',
        'country_name_en' => 'Sint Maarten',
        'country_name_id' => 'Sint Maarten',
    ],

    'KEPULAUAN NORFOLK' => [
        'country_code' => 'NF',
        'iso3' => 'NFK',
        'country_name_en' => 'Norfolk Island',
        'country_name_id' => 'Pulau Norfolk',
    ],

    'BRITISH INDIAN OCEAN TERRITORY' => [
        'country_code' => 'IO',
        'iso3' => 'IOT',
        'country_name_en' => 'British Indian Ocean Territory',
        'country_name_id' => 'Wilayah Samudra Hindia Britania',
    ],

    'ANTARTICA' => [
        'country_code' => 'AQ',
        'iso3' => 'ATA',
        'country_name_en' => 'Antarctica',
        'country_name_id' => 'Antarktika',
    ],

    'PITCAIRN' => [
        'country_code' => 'PN',
        'iso3' => 'PCN',
        'country_name_en' => 'Pitcairn',
        'country_name_id' => 'Pitcairn',
    ],

    'PULAU HEARD DAN KEPULAUAN MCDONALD' => [
        'country_code' => 'HM',
        'iso3' => 'HMD',
        'country_name_en' => 'Heard Island and McDonald Islands',
        'country_name_id' => 'Pulau Heard dan Kepulauan McDonald',
    ],

    'KEPULAUAN ALAND' => [
        'country_code' => 'AX',
        'iso3' => 'ALA',
        'country_name_en' => 'Åland Islands',
        'country_name_id' => 'Kepulauan Åland',
    ],
];

/*
|--------------------------------------------------------------------------
| Candidate validation
|--------------------------------------------------------------------------
*/

if (count($candidates) !== 28) {
    throw new RuntimeException(
        'Expected exactly 28 territory candidates.'
    );
}

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
            "Canonical name conflict for {$sourceName}: "
            . $candidate['country_name_en']
        );
    }

    echo "  {$sourceName} : PRE-CHECK PASS\n";
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Transactional insert
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

        $byCode =
            DB::table('mst_countries')
                ->where(
                    'country_code',
                    $candidate['country_code']
                )
                ->lockForUpdate()
                ->first();

        if ($byCode !== null) {

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
            'official_name' => $candidate['country_name_en'],
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
                'official_name',
                'is_active',
            ]);

    $pass =
        $master !== null
        &&
        (int) $master->is_active === 1
        &&
        strtoupper((string) $master->country_code)
            ===
            strtoupper($candidate['country_code'])
        &&
        strtoupper((string) $master->iso3)
            ===
            strtoupper($candidate['iso3'])
        &&
        (string) $master->country_name_en
            ===
            $candidate['country_name_en'];

    echo "  {$sourceName} : "
        . ($pass ? 'PASS' : 'FAIL');

    if ($master !== null) {
        echo " -> ID={$master->id}"
            . " {$master->country_code}/{$master->iso3}"
            . " | {$master->country_name_en}";
    }

    echo PHP_EOL;

    if (!$pass) {
        $verificationPassed = false;
    }
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Master count verification
|--------------------------------------------------------------------------
*/

$activeCount =
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
echo "  EXPECTED ACTIVE CANDIDATES : 28\n";
echo "  FOUND ACTIVE CANDIDATES    : {$activeCount}\n";

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

/*
|--------------------------------------------------------------------------
| Final gate
|--------------------------------------------------------------------------
*/

$success =
    ($inserted + $alreadyExisting) === 28
    &&
    $activeCount === 28
    &&
    $verificationPassed
    &&
    $tradeStatsCount === 2266312
    &&
    $tradeStatsIdentities === 2266312;

echo "========================================\n";

if ($success) {
    echo "TERRITORY MASTER INSERT : PASS\n";
} else {
    echo "TERRITORY MASTER INSERT : REVIEW\n";
    exit(1);
}

echo "========================================\n";