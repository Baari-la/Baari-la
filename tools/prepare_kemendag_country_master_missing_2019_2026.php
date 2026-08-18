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
echo "DIGESTEX KEMENDAG COUNTRY MASTER MISSING PREPARATION\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  OPERATION   : READ ONLY\n\n";

/*
|--------------------------------------------------------------------------
| Candidate country masters
|--------------------------------------------------------------------------
|
| Derived from the previously approved unresolved-country audit.
| No database mutation is performed by this script.
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
        'country_name_id' => 'Dominica',
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
| Expected unresolved record counts
|--------------------------------------------------------------------------
*/

$expectedRecords = [
    'LUKSEMBURG' => 474,
    'SIPRUS' => 373,
    'REPUBLIK MACEDONIA' => 256,
    'ANDORRA' => 131,
    'REPUBLIK DEMOKRATIK KONGO' => 89,
    'ISLANDIA' => 89,
    'REP.AFRIKA TENGAH' => 52,
    'SAINT LUCIA' => 51,
    'GABON' => 45,
    'MAURITANIA' => 33,
    'ZAMBIA' => 32,
    'CHAD' => 29,
    'BOTSWANA' => 26,
    'MALAWI' => 19,
    'RWANDA' => 15,
    'NIGER' => 15,
    'DOMINIKA' => 14,
    'TAJIKISTAN' => 7,
    'TURKMENISTAN' => 5,
    'ERITREA' => 4,
    'SAN MARINO' => 4,
    'SAO TOME DAN PRINCIPE' => 3,
    'SUDAN SELATAN' => 1,
];

/*
|--------------------------------------------------------------------------
| Summary
|--------------------------------------------------------------------------
*/

$totalTargetRecords = 0;
$masterNameConflicts = 0;
$countryCodeConflicts = 0;
$iso3Conflicts = 0;
$duplicateCandidateConflicts = 0;
$recordCountFailures = 0;

echo "========================================\n";
echo "CANDIDATE SUMMARY\n";
echo "========================================\n";

echo "  CANDIDATE COUNTRIES : "
    . count($candidates)
    . PHP_EOL;

/*
|--------------------------------------------------------------------------
| Candidate validation
|--------------------------------------------------------------------------
*/

foreach ($candidates as $sourceName => $candidate) {

    $targetRecords =
        DB::table('trade_statistics')
            ->whereBetween(
                'year',
                [2019, 2026]
            )
            ->whereIn(
                'trade_flow',
                ['export', 'import']
            )
            ->whereNull('country_id')
            ->where(
                'country_name',
                $sourceName
            )
            ->count();

    $totalTargetRecords +=
        $targetRecords;

    $expected =
        $expectedRecords[$sourceName]
        ?? null;

    if (
        $expected === null
        ||
        $targetRecords !== $expected
    ) {
        $recordCountFailures++;
    }

    /*
    |--------------------------------------------------------------------------
    | Existing master checks
    |--------------------------------------------------------------------------
    */

    $byCode =
        DB::table('mst_countries')
            ->where(
                'country_code',
                $candidate['country_code']
            )
            ->get();

    $byIso3 =
        DB::table('mst_countries')
            ->where(
                'iso3',
                $candidate['iso3']
            )
            ->get();

    $byEnglishName =
        DB::table('mst_countries')
            ->whereRaw(
                'UPPER(country_name_en) = ?',
                [
                    mb_strtoupper(
                        $candidate['country_name_en']
                    ),
                ]
            )
            ->get();

    if ($byCode->isNotEmpty()) {

        $countryConflict =
            $byCode->contains(
                function ($row) use ($candidate) {
                    return
                        strtoupper(
                            (string) $row->country_code
                        )
                        ===
                        strtoupper(
                            $candidate['country_code']
                        )
                        &&
                        strtoupper(
                            (string) ($row->iso3 ?? '')
                        )
                        !==
                        strtoupper(
                            $candidate['iso3']
                        );
                }
            );

        if ($countryConflict) {
            $countryCodeConflicts++;
        }
    }

    if ($byIso3->isNotEmpty()) {

        $isoConflict =
            $byIso3->contains(
                function ($row) use ($candidate) {
                    return
                        strtoupper(
                            (string) $row->country_code
                        )
                        !==
                        strtoupper(
                            $candidate['country_code']
                        );
                }
            );

        if ($isoConflict) {
            $iso3Conflicts++;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Detect an already existing canonical name.
    |--------------------------------------------------------------------------
    */

    $masterNameExists =
        $byEnglishName->isNotEmpty();

    /*
    |--------------------------------------------------------------------------
    | Output
    |--------------------------------------------------------------------------
    */

    echo sprintf(
        "  %-38s | %7d records | %s/%s | %s",
        $sourceName,
        $targetRecords,
        $candidate['country_code'],
        $candidate['iso3'],
        $candidate['country_name_en']
    );

    echo PHP_EOL;

    echo "      SOURCE → "
        . $sourceName
        . PHP_EOL;

    echo "      TARGET → "
        . $candidate['country_name_en']
        . " ["
        . $candidate['country_code']
        . "/"
        . $candidate['iso3']
        . "]"
        . PHP_EOL;

    echo "      MASTER CODE USED : "
        . (
            $byCode->isNotEmpty()
                ? 'ALREADY USED'
                : 'AVAILABLE'
        )
        . PHP_EOL;

    echo "      MASTER ISO3 USED : "
        . (
            $byIso3->isNotEmpty()
                ? 'ALREADY USED'
                : 'AVAILABLE'
        )
        . PHP_EOL;

    echo "      CANONICAL NAME    : "
        . (
            $masterNameExists
                ? 'ALREADY EXISTS'
                : 'AVAILABLE'
        )
        . PHP_EOL;

    echo "      RECORD COUNT      : "
        . (
            $expected !== null
            && $targetRecords === $expected
                ? 'PASS'
                : 'REVIEW'
        )
        . PHP_EOL;

    echo PHP_EOL;
}

/*
|--------------------------------------------------------------------------
| Global collision detection
|--------------------------------------------------------------------------
*/

$candidateCodes = [];
$candidateIso3 = [];
$candidateNames = [];

foreach ($candidates as $sourceName => $candidate) {

    $code =
        strtoupper(
            $candidate['country_code']
        );

    $iso3 =
        strtoupper(
            $candidate['iso3']
        );

    $name =
        mb_strtoupper(
            $candidate['country_name_en']
        );

    if (isset($candidateCodes[$code])) {
        $duplicateCandidateConflicts++;
    }

    if (isset($candidateIso3[$iso3])) {
        $duplicateCandidateConflicts++;
    }

    if (isset($candidateNames[$name])) {
        $duplicateCandidateConflicts++;
    }

    $candidateCodes[$code] = $sourceName;
    $candidateIso3[$iso3] = $sourceName;
    $candidateNames[$name] = $sourceName;
}

/*
|--------------------------------------------------------------------------
| Expected totals
|--------------------------------------------------------------------------
*/

$expectedTotalRecords =
    array_sum(
        $expectedRecords
    );

echo "========================================\n";
echo "TOTAL IMPACT\n";
echo "========================================\n";

echo "  TARGET RECORDS : "
    . $totalTargetRecords
    . PHP_EOL;

echo "  EXPECTED       : "
    . $expectedTotalRecords
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Collision summary
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "CONFLICT CHECK\n";
echo "========================================\n";

echo "  COUNTRY CODE CONFLICTS : "
    . $countryCodeConflicts
    . PHP_EOL;

echo "  ISO3 CONFLICTS         : "
    . $iso3Conflicts
    . PHP_EOL;

echo "  CANDIDATE DUPLICATES   : "
    . $duplicateCandidateConflicts
    . PHP_EOL;

echo "  RECORD COUNT FAILURES  : "
    . $recordCountFailures
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Master existence summary
|--------------------------------------------------------------------------
*/

$masterExistingCount = 0;
$masterMissingCount = 0;

foreach ($candidates as $sourceName => $candidate) {

    $exists =
        DB::table('mst_countries')
            ->where(
                'country_code',
                $candidate['country_code']
            )
            ->exists();

    if ($exists) {
        $masterExistingCount++;
    } else {
        $masterMissingCount++;
    }
}

echo "========================================\n";
echo "MASTER STATUS\n";
echo "========================================\n";

echo "  MASTER ALREADY EXISTS : "
    . $masterExistingCount
    . PHP_EOL;

echo "  MASTER MISSING        : "
    . $masterMissingCount
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Final preparation gate
|--------------------------------------------------------------------------
*/

$preparationPass =
    count($candidates) === 23
    &&
    $totalTargetRecords === 1767
    &&
    $countryCodeConflicts === 0
    &&
    $iso3Conflicts === 0
    &&
    $duplicateCandidateConflicts === 0
    &&
    $recordCountFailures === 0;

echo "========================================\n";
echo "MASTER PREPARATION GATE\n";
echo "========================================\n";

if ($preparationPass) {
    echo "COUNTRY MASTER PREPARATION : PASS\n";
} else {
    echo "COUNTRY MASTER PREPARATION : REVIEW\n";
}

echo PHP_EOL;

echo "DATABASE SAFETY:\n";
echo "  INSERT : NO\n";
echo "  UPDATE : NO\n";
echo "  DELETE : NO\n";
echo "  DATABASE WAS NOT MODIFIED.\n";

echo "========================================\n";
echo "COUNTRY MASTER MISSING PREPARATION : COMPLETE\n";
echo "========================================\n";