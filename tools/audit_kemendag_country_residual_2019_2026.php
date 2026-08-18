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
echo "DIGESTEX KEMENDAG COUNTRY RESIDUAL AUDIT\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  OPERATION   : READ ONLY\n\n";

/*
|--------------------------------------------------------------------------
| Expected residual classification
|--------------------------------------------------------------------------
*/

$classification = [

    'NETHERLANDS ANTILLES' => [
        'class' => 'HISTORICAL',
        'expected_records' => 635,
        'policy' => 'REVIEW / NO AUTO MASTER',
    ],

    'INDONESIA (BATAM)' => [
        'class' => 'SPECIAL',
        'expected_records' => 3152,
        'policy' => 'REVIEW',
    ],

    'FD STS MICRONESIA' => [
        'class' => 'SPECIAL',
        'expected_records' => 96,
        'policy' => 'REVIEW',
    ],

    'PALESTINA' => [
        'class' => 'SPECIAL',
        'expected_records' => 90,
        'policy' => 'REVIEW',
    ],

    'KEPULAUAN SALOMON' => [
        'class' => 'REVIEW',
        'expected_records' => 86,
        'policy' => 'REVIEW',
    ],

    'KURASAO' => [
        'class' => 'REVIEW',
        'expected_records' => 48,
        'policy' => 'REVIEW',
    ],

    'CAPE VERDE' => [
        'class' => 'REVIEW',
        'expected_records' => 41,
        'policy' => 'REVIEW',
    ],

    'LIECHTENSTEIN' => [
        'class' => 'REVIEW',
        'expected_records' => 36,
        'policy' => 'REVIEW',
    ],

    'SAINT VINCENT DAN THE GRENADINES' => [
        'class' => 'REVIEW',
        'expected_records' => 34,
        'policy' => 'REVIEW',
    ],

    'REPUBLIK KOMORO' => [
        'class' => 'REVIEW',
        'expected_records' => 15,
        'policy' => 'REVIEW',
    ],

    'KOSOVO' => [
        'class' => 'REVIEW',
        'expected_records' => 7,
        'policy' => 'REVIEW',
    ],

    'GUINEA (EQUATORIAL)' => [
        'class' => 'REVIEW',
        'expected_records' => 7,
        'policy' => 'REVIEW',
    ],

    'VATIKAN CITY STATE' => [
        'class' => 'REVIEW',
        'expected_records' => 7,
        'policy' => 'REVIEW',
    ],
];

/*
|--------------------------------------------------------------------------
| Current residual baseline
|--------------------------------------------------------------------------
*/

$currentNull =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn(
            'trade_flow',
            ['export', 'import']
        )
        ->whereNull('country_id')
        ->count();

echo "CURRENT NULL COUNTRY RECORDS : {$currentNull}\n\n";

if ($currentNull !== 4254) {
    throw new RuntimeException(
        "Unexpected residual count. Expected 4254, got {$currentNull}."
    );
}

/*
|--------------------------------------------------------------------------
| Read grouped residuals
|--------------------------------------------------------------------------
*/

$residuals =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn(
            'trade_flow',
            ['export', 'import']
        )
        ->whereNull('country_id')
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

    $name = trim(
        (string) $row->country_name
    );

    $residualMap[$name] = [
        'records' =>
            (int) $row->records,

        'trade_value' =>
            (float) $row->trade_value,

        'trade_volume' =>
            (float) $row->trade_volume,
    ];
}

/*
|--------------------------------------------------------------------------
| Print current residual source names
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "CURRENT RESIDUAL COUNTRY NAMES\n";
echo "========================================\n";

foreach ($residualMap as $name => $data) {

    $known =
        $classification[$name]
        ?? null;

    echo sprintf(
        "  %-42s | %6d records | %s\n",
        $name,
        $data['records'],
        $known['class'] ?? 'UNCLASSIFIED'
    );
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Deterministic validation
|--------------------------------------------------------------------------
*/

$validationPass = true;
$unclassified = 0;
$recordCountFailures = 0;

echo "========================================\n";
echo "DETERMINISTIC RESIDUAL VALIDATION\n";
echo "========================================\n";

foreach ($classification as $name => $expected) {

    $actual =
        $residualMap[$name]['records']
        ?? 0;

    $pass =
        $actual === $expected['expected_records'];

    echo sprintf(
        "  %-42s : %s (%d/%d records) | %s\n",
        $name,
        $pass ? 'PASS' : 'FAIL',
        $actual,
        $expected['expected_records'],
        $expected['class']
    );

    if (!$pass) {
        $validationPass = false;
        $recordCountFailures++;
    }
}

/*
|--------------------------------------------------------------------------
| Detect unexpected residual names
|--------------------------------------------------------------------------
*/

foreach ($residualMap as $name => $data) {

    if (!isset($classification[$name])) {

        $unclassified++;

        echo "  UNCLASSIFIED -> "
            . $name
            . " : "
            . $data['records']
            . " records\n";
    }
}

if ($unclassified > 0) {
    $validationPass = false;
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Class totals
|--------------------------------------------------------------------------
*/

$historicalTotal = 0;
$specialTotal = 0;
$reviewTotal = 0;

foreach ($classification as $name => $expected) {

    $actual =
        $residualMap[$name]['records']
        ?? 0;

    switch ($expected['class']) {

        case 'HISTORICAL':
            $historicalTotal += $actual;
            break;

        case 'SPECIAL':
            $specialTotal += $actual;
            break;

        case 'REVIEW':
            $reviewTotal += $actual;
            break;
    }
}

$classifiedTotal =
    $historicalTotal
    + $specialTotal
    + $reviewTotal;

echo "========================================\n";
echo "CLASSIFICATION SUMMARY\n";
echo "========================================\n";

echo "  HISTORICAL : {$historicalTotal}\n";
echo "  SPECIAL    : {$specialTotal}\n";
echo "  REVIEW     : {$reviewTotal}\n";
echo "  TOTAL      : {$classifiedTotal}\n";

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Aggregate trade values
|--------------------------------------------------------------------------
*/

$historicalValue = 0.0;
$specialValue = 0.0;
$reviewValue = 0.0;

$historicalVolume = 0.0;
$specialVolume = 0.0;
$reviewVolume = 0.0;

foreach ($classification as $name => $expected) {

    if (!isset($residualMap[$name])) {
        continue;
    }

    $data = $residualMap[$name];

    switch ($expected['class']) {

        case 'HISTORICAL':
            $historicalValue += $data['trade_value'];
            $historicalVolume += $data['trade_volume'];
            break;

        case 'SPECIAL':
            $specialValue += $data['trade_value'];
            $specialVolume += $data['trade_volume'];
            break;

        case 'REVIEW':
            $reviewValue += $data['trade_value'];
            $reviewVolume += $data['trade_volume'];
            break;
    }
}

echo "========================================\n";
echo "CLASSIFICATION TRADE AGGREGATES\n";
echo "========================================\n";

echo "  HISTORICAL\n";
echo "    VALUE  : "
    . number_format($historicalValue, 3, '.', '')
    . PHP_EOL;
echo "    VOLUME : "
    . number_format($historicalVolume, 3, '.', '')
    . PHP_EOL;

echo "  SPECIAL\n";
echo "    VALUE  : "
    . number_format($specialValue, 3, '.', '')
    . PHP_EOL;
echo "    VOLUME : "
    . number_format($specialVolume, 3, '.', '')
    . PHP_EOL;

echo "  REVIEW\n";
echo "    VALUE  : "
    . number_format($reviewValue, 3, '.', '')
    . PHP_EOL;
echo "    VOLUME : "
    . number_format($reviewVolume, 3, '.', '')
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Policy output
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "POLICY STATUS\n";
echo "========================================\n";

echo "  NETHERLANDS ANTILLES\n";
echo "    ACTION      : REVIEW\n";
echo "    AUTO MASTER : NO\n";
echo "    AUTO BACKFILL: NO\n";

echo PHP_EOL;

echo "  SPECIAL NOMENCLATURE\n";
echo "    ACTION      : REVIEW\n";
echo "    AUTO MASTER : NO\n";
echo "    AUTO BACKFILL: NO\n";

echo PHP_EOL;

echo "  REVIEW / UNKNOWN\n";
echo "    ACTION      : REVIEW\n";
echo "    AUTO MASTER : NO\n";
echo "    AUTO BACKFILL: NO\n";

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

/*
|--------------------------------------------------------------------------
| Final gate
|--------------------------------------------------------------------------
*/

$success =
    $currentNull === 4254
    &&
    $classifiedTotal === 4254
    &&
    $historicalTotal === 635
    &&
    $specialTotal === 3338
    &&
    $reviewTotal === 281
    &&
    $unclassified === 0
    &&
    $recordCountFailures === 0
    &&
    $validationPass;

echo "========================================\n";
echo "COUNTRY RESIDUAL AUDIT GATE\n";
echo "========================================\n";

echo "  CURRENT NULL        : {$currentNull}\n";
echo "  CLASSIFIED TOTAL    : {$classifiedTotal}\n";
echo "  HISTORICAL          : {$historicalTotal}\n";
echo "  SPECIAL             : {$specialTotal}\n";
echo "  REVIEW              : {$reviewTotal}\n";
echo "  UNCLASSIFIED        : {$unclassified}\n";
echo "  RECORD FAILURES     : {$recordCountFailures}\n";
echo "  VALIDATION          : "
    . ($validationPass ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo PHP_EOL;

if ($success) {
    echo "COUNTRY RESIDUAL AUDIT : PASS\n";
} else {
    echo "COUNTRY RESIDUAL AUDIT : REVIEW\n";
    exit(1);
}

echo "========================================\n";