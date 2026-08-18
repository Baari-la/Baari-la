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
echo "DIGESTEX KEMENDAG TERRITORY MASTER PREPARATION\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  OPERATION   : READ ONLY\n\n";

/*
|--------------------------------------------------------------------------
| Approved remaining territory candidates
|--------------------------------------------------------------------------
|
| French Guiana is intentionally excluded because:
| GUIANA PERANCIS -> mst_countries.id = 71
| and its 34 records have already been backfilled.
|
| Netherlands Antilles is also intentionally excluded from automatic
| master creation because it is a historical nomenclature requiring
| separate policy review.
|--------------------------------------------------------------------------
*/

$candidates = [

    'VIRGIN ISLANDS (BRITISH)' => [
        'country_code' => 'VG',
        'iso3' => 'VGB',
        'country_name_en' => 'British Virgin Islands',
        'country_name_id' => 'Kepulauan Virgin Britania',
        'classification' => 'TERRITORY',
        'expected_records' => 502,
    ],

    'U.S. VIRGIN ISLANDS' => [
        'country_code' => 'VI',
        'iso3' => 'VIR',
        'country_name_en' => 'United States Virgin Islands',
        'country_name_id' => 'Kepulauan Virgin Amerika Serikat',
        'classification' => 'TERRITORY',
        'expected_records' => 254,
    ],

    'KEPULAUAN TURKS DAN CAICOS' => [
        'country_code' => 'TC',
        'iso3' => 'TCA',
        'country_name_en' => 'Turks and Caicos Islands',
        'country_name_id' => 'Kepulauan Turks dan Caicos',
        'classification' => 'TERRITORY',
        'expected_records' => 240,
    ],

    'KEPULAUAN CAYMAN' => [
        'country_code' => 'KY',
        'iso3' => 'CYM',
        'country_name_en' => 'Cayman Islands',
        'country_name_id' => 'Kepulauan Cayman',
        'classification' => 'TERRITORY',
        'expected_records' => 196,
    ],

    'KEP. VALLIS DAN FUTUNA' => [
        'country_code' => 'WF',
        'iso3' => 'WLF',
        'country_name_en' => 'Wallis and Futuna',
        'country_name_id' => 'Wallis dan Futuna',
        'classification' => 'TERRITORY',
        'expected_records' => 192,
    ],

    'ANGUILA' => [
        'country_code' => 'AI',
        'iso3' => 'AIA',
        'country_name_en' => 'Anguilla',
        'country_name_id' => 'Anguilla',
        'classification' => 'TERRITORY',
        'expected_records' => 84,
    ],

    'JERSEY' => [
        'country_code' => 'JE',
        'iso3' => 'JEY',
        'country_name_en' => 'Jersey',
        'country_name_id' => 'Jersey',
        'classification' => 'DEPENDENCY',
        'expected_records' => 82,
    ],

    'MAYOTTE' => [
        'country_code' => 'YT',
        'iso3' => 'MYT',
        'country_name_en' => 'Mayotte',
        'country_name_id' => 'Mayotte',
        'classification' => 'TERRITORY',
        'expected_records' => 78,
    ],

    'ARUBA' => [
        'country_code' => 'AW',
        'iso3' => 'ABW',
        'country_name_en' => 'Aruba',
        'country_name_id' => 'Aruba',
        'classification' => 'TERRITORY',
        'expected_records' => 68,
    ],

    'SAINT BARTHELEMY' => [
        'country_code' => 'BL',
        'iso3' => 'BLM',
        'country_name_en' => 'Saint Barthelemy',
        'country_name_id' => 'Saint Barthelemy',
        'classification' => 'TERRITORY',
        'expected_records' => 44,
    ],

    'SAMOA AMERIKA' => [
        'country_code' => 'AS',
        'iso3' => 'ASM',
        'country_name_en' => 'American Samoa',
        'country_name_id' => 'Samoa Amerika',
        'classification' => 'TERRITORY',
        'expected_records' => 41,
    ],

    'TOKELAU' => [
        'country_code' => 'TK',
        'iso3' => 'TKL',
        'country_name_en' => 'Tokelau',
        'country_name_id' => 'Tokelau',
        'classification' => 'TERRITORY',
        'expected_records' => 29,
    ],

    'GIBRALTAR' => [
        'country_code' => 'GI',
        'iso3' => 'GIB',
        'country_name_en' => 'Gibraltar',
        'country_name_id' => 'Gibraltar',
        'classification' => 'TERRITORY',
        'expected_records' => 26,
    ],

    'KEPULAUAN MARIANA UTARA' => [
        'country_code' => 'MP',
        'iso3' => 'MNP',
        'country_name_en' => 'Northern Mariana Islands',
        'country_name_id' => 'Kepulauan Mariana Utara',
        'classification' => 'TERRITORY',
        'expected_records' => 23,
    ],

    'SAINT MARTIN (FRENCH PART)' => [
        'country_code' => 'MF',
        'iso3' => 'MAF',
        'country_name_en' => 'Saint Martin',
        'country_name_id' => 'Saint Martin',
        'classification' => 'TERRITORY',
        'expected_records' => 22,
    ],

    'KEPULAUAN CHRISTMAS' => [
        'country_code' => 'CX',
        'iso3' => 'CXR',
        'country_name_en' => 'Christmas Island',
        'country_name_id' => 'Pulau Christmas',
        'classification' => 'TERRITORY',
        'expected_records' => 16,
    ],

    'U.S MINOR OUTLYING ISLAND' => [
        'country_code' => 'UM',
        'iso3' => 'UMI',
        'country_name_en' => 'United States Minor Outlying Islands',
        'country_name_id' => 'Kepulauan Terluar Kecil Amerika Serikat',
        'classification' => 'TERRITORY_GROUP',
        'expected_records' => 14,
    ],

    'GUERNSEY' => [
        'country_code' => 'GG',
        'iso3' => 'GGY',
        'country_name_en' => 'Guernsey',
        'country_name_id' => 'Guernsey',
        'classification' => 'DEPENDENCY',
        'expected_records' => 9,
    ],

    'SAINT HELENA' => [
        'country_code' => 'SH',
        'iso3' => 'SHN',
        'country_name_en' => 'Saint Helena',
        'country_name_id' => 'Saint Helena',
        'classification' => 'TERRITORY',
        'expected_records' => 9,
    ],

    'KEPULAUAN FALKLAND (MALVINAS)' => [
        'country_code' => 'FK',
        'iso3' => 'FLK',
        'country_name_en' => 'Falkland Islands',
        'country_name_id' => 'Kepulauan Falkland',
        'classification' => 'TERRITORY',
        'expected_records' => 8,
    ],

    'KEPULAUAN COCOS (KEELING)' => [
        'country_code' => 'CC',
        'iso3' => 'CCK',
        'country_name_en' => 'Cocos (Keeling) Islands',
        'country_name_id' => 'Kepulauan Cocos',
        'classification' => 'TERRITORY',
        'expected_records' => 8,
    ],

    'SINT MAARTEN (DUTCH PART)' => [
        'country_code' => 'SX',
        'iso3' => 'SXM',
        'country_name_en' => 'Sint Maarten',
        'country_name_id' => 'Sint Maarten',
        'classification' => 'TERRITORY',
        'expected_records' => 7,
    ],

    'KEPULAUAN NORFOLK' => [
        'country_code' => 'NF',
        'iso3' => 'NFK',
        'country_name_en' => 'Norfolk Island',
        'country_name_id' => 'Pulau Norfolk',
        'classification' => 'TERRITORY',
        'expected_records' => 4,
    ],

    'BRITISH INDIAN OCEAN TERRITORY' => [
        'country_code' => 'IO',
        'iso3' => 'IOT',
        'country_name_en' => 'British Indian Ocean Territory',
        'country_name_id' => 'Wilayah Samudra Hindia Britania',
        'classification' => 'TERRITORY',
        'expected_records' => 3,
    ],

    'ANTARTICA' => [
        'country_code' => 'AQ',
        'iso3' => 'ATA',
        'country_name_en' => 'Antarctica',
        'country_name_id' => 'Antarktika',
        'classification' => 'SPECIAL_TERRITORY',
        'expected_records' => 2,
    ],

    'PITCAIRN' => [
        'country_code' => 'PN',
        'iso3' => 'PCN',
        'country_name_en' => 'Pitcairn',
        'country_name_id' => 'Pitcairn',
        'classification' => 'TERRITORY',
        'expected_records' => 1,
    ],

    'PULAU HEARD DAN KEPULAUAN MCDONALD' => [
        'country_code' => 'HM',
        'iso3' => 'HMD',
        'country_name_en' => 'Heard Island and McDonald Islands',
        'country_name_id' => 'Pulau Heard dan Kepulauan McDonald',
        'classification' => 'TERRITORY',
        'expected_records' => 1,
    ],

    'KEPULAUAN ALAND' => [
        'country_code' => 'AX',
        'iso3' => 'ALA',
        'country_name_en' => 'Åland Islands',
        'country_name_id' => 'Kepulauan Åland',
        'classification' => 'TERRITORY',
        'expected_records' => 1,
    ],
];

echo "========================================\n";
echo "CANDIDATE VALIDATION\n";
echo "========================================\n";

$expectedCandidateCount = 28;

if (count($candidates) !== $expectedCandidateCount) {
    throw new RuntimeException(
        "Expected {$expectedCandidateCount} candidates, got "
        . count($candidates)
    );
}

echo "  CANDIDATE COUNTRIES : "
    . count($candidates)
    . PHP_EOL;

/*
|--------------------------------------------------------------------------
| Current residual query
|--------------------------------------------------------------------------
*/

$residuals =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('country_id')
        ->whereIn('country_name', array_keys($candidates))
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

$residualMap = [];

foreach ($residuals as $row) {
    $residualMap[
        trim((string) $row->country_name)
    ] = [
        'records' => (int) $row->records,
        'trade_value' => (float) $row->trade_value,
        'trade_volume' => (float) $row->trade_volume,
    ];
}

$totalRecords = 0;
$recordCountFailures = 0;

/*
|--------------------------------------------------------------------------
| Deterministic residual validation
|--------------------------------------------------------------------------
*/

echo PHP_EOL;
echo "========================================\n";
echo "CURRENT RESIDUAL TERRITORY TARGETS\n";
echo "========================================\n";

foreach ($candidates as $sourceName => $candidate) {

    $actual =
        $residualMap[$sourceName]['records']
        ?? 0;

    $expected =
        $candidate['expected_records'];

    $pass =
        $actual === $expected;

    if (!$pass) {
        $recordCountFailures++;
    }

    $totalRecords += $actual;

    echo sprintf(
        "  %-42s | %5d/%-5d records | %s\n",
        $sourceName,
        $actual,
        $expected,
        $pass ? 'PASS' : 'FAIL'
    );

    if ($actual > 0) {
        echo "      TARGET : "
            . $candidate['country_name_en']
            . " | "
            . $candidate['country_code']
            . " | "
            . $candidate['iso3']
            . PHP_EOL;
    } else {
        echo "      TARGET : NO CURRENT RESIDUAL\n";
    }
}

echo PHP_EOL;

echo "TOTAL TARGET RECORDS : {$totalRecords}\n";
echo "EXPECTED TOTAL       : 1964\n";
echo PHP_EOL;

if ($totalRecords !== 1964) {
    $recordCountFailures++;
}

/*
|--------------------------------------------------------------------------
| Historical Netherlands Antilles
|--------------------------------------------------------------------------
*/

$netherlandsAntilles =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('country_id')
        ->where(
            'country_name',
            'NETHERLANDS ANTILLES'
        )
        ->count();

echo "========================================\n";
echo "HISTORICAL POLICY\n";
echo "========================================\n";

echo "  NETHERLANDS ANTILLES : {$netherlandsAntilles} records\n";
echo "  ACTION                : REVIEW\n";
echo "  AUTO MASTER           : NO\n";

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Candidate uniqueness checks
|--------------------------------------------------------------------------
*/

$codeMap = [];
$iso3Map = [];
$nameMap = [];

$candidateDuplicates = 0;

foreach ($candidates as $sourceName => $candidate) {

    $code = strtoupper(
        trim($candidate['country_code'])
    );

    $iso3 = strtoupper(
        trim($candidate['iso3'])
    );

    $name = mb_strtoupper(
        trim($candidate['country_name_en'])
    );

    if (isset($codeMap[$code])) {
        $candidateDuplicates++;
    }

    if (isset($iso3Map[$iso3])) {
        $candidateDuplicates++;
    }

    if (isset($nameMap[$name])) {
        $candidateDuplicates++;
    }

    $codeMap[$code] = $sourceName;
    $iso3Map[$iso3] = $sourceName;
    $nameMap[$name] = $sourceName;
}

/*
|--------------------------------------------------------------------------
| Master conflict checks
|--------------------------------------------------------------------------
*/

$codeConflicts = 0;
$iso3Conflicts = 0;
$nameConflicts = 0;

echo "========================================\n";
echo "MASTER CONFLICT CHECK\n";
echo "========================================\n";

foreach ($candidates as $sourceName => $candidate) {

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
            ->get();

    if ($byCode->isNotEmpty()) {
        $codeConflicts++;
    }

    if ($byIso3->isNotEmpty()) {
        $iso3Conflicts++;
    }

    if ($byName->isNotEmpty()) {
        $nameConflicts++;
    }

    echo sprintf(
        "  %-42s | CODE=%s | ISO3=%s | NAME=%s\n",
        $sourceName,
        $byCode->isEmpty() ? 'FREE' : 'USED',
        $byIso3->isEmpty() ? 'FREE' : 'USED',
        $byName->isEmpty() ? 'FREE' : 'USED'
    );
}

echo PHP_EOL;

echo "CONFLICT SUMMARY:\n";
echo "  COUNTRY CODE CONFLICTS : {$codeConflicts}\n";
echo "  ISO3 CONFLICTS         : {$iso3Conflicts}\n";
echo "  NAME CONFLICTS         : {$nameConflicts}\n";
echo "  CANDIDATE DUPLICATES   : {$candidateDuplicates}\n";
echo "  RECORD COUNT FAILURES  : {$recordCountFailures}\n";

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Existing alias check
|--------------------------------------------------------------------------
*/

$aliasRegistered = 0;
$aliasConflicts = 0;

echo "========================================\n";
echo "KEMENDAG ALIAS STATUS\n";
echo "========================================\n";

foreach ($candidates as $sourceName => $candidate) {

    $alias =
        DB::table('trade_country_aliases')
            ->where('source_system', 'KEMENDAG')
            ->where(
                'normalized_name',
                $sourceName
            )
            ->first();

    if ($alias === null) {

        echo "  {$sourceName} : NOT REGISTERED\n";
        continue;
    }

    $aliasRegistered++;

    $expectedCountryId =
        DB::table('mst_countries')
            ->where(
                'iso3',
                $candidate['iso3']
            )
            ->value('id');

    $conflict =
        $expectedCountryId !== null
        &&
        (int) $alias->country_id !== (int) $expectedCountryId;

    if ($conflict) {
        $aliasConflicts++;
    }

    echo "  {$sourceName} : REGISTERED"
        . " -> country_id={$alias->country_id}"
        . ($conflict ? ' : CONFLICT' : '')
        . PHP_EOL;
}

echo PHP_EOL;

echo "ALIAS SUMMARY:\n";
echo "  REGISTERED      : {$aliasRegistered}\n";
echo "  ALIAS CONFLICTS : {$aliasConflicts}\n";

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Final preparation gate
|--------------------------------------------------------------------------
*/

$success =
    count($candidates) === 28
    &&
    $totalRecords === 1964
    &&
    $recordCountFailures === 0
    &&
    $codeConflicts === 0
    &&
    $iso3Conflicts === 0
    &&
    $nameConflicts === 0
    &&
    $candidateDuplicates === 0
    &&
    $aliasConflicts === 0;

echo "========================================\n";
echo "TERRITORY MASTER PREPARATION GATE\n";
echo "========================================\n";

echo "  CANDIDATES             : "
    . count($candidates)
    . PHP_EOL;

echo "  TARGET RECORDS         : "
    . $totalRecords
    . PHP_EOL;

echo "  EXPECTED RECORDS       : 1964\n";

echo "  CODE CONFLICTS         : "
    . $codeConflicts
    . PHP_EOL;

echo "  ISO3 CONFLICTS         : "
    . $iso3Conflicts
    . PHP_EOL;

echo "  NAME CONFLICTS         : "
    . $nameConflicts
    . PHP_EOL;

echo "  CANDIDATE DUPLICATES   : "
    . $candidateDuplicates
    . PHP_EOL;

echo "  RECORD COUNT FAILURES  : "
    . $recordCountFailures
    . PHP_EOL;

echo "  ALIAS CONFLICTS        : "
    . $aliasConflicts
    . PHP_EOL;

echo PHP_EOL;

if ($success) {
    echo "TERRITORY MASTER PREPARATION : PASS\n";
} else {
    echo "TERRITORY MASTER PREPARATION : REVIEW\n";
}

echo PHP_EOL;

echo "DATABASE SAFETY:\n";
echo "  INSERT : NO\n";
echo "  UPDATE : NO\n";
echo "  DELETE : NO\n";
echo "  DATABASE WAS NOT MODIFIED.\n";

echo "========================================\n";
echo "TERRITORY MASTER PREPARATION : COMPLETE\n";
echo "========================================\n";