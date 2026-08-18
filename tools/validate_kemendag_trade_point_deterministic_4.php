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
echo "DIGESTEX KEMENDAG TRADE POINT DETERMINISTIC VALIDATION\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  CANDIDATES  : 4\n";
echo "  OPERATION   : READ ONLY\n\n";

/*
|--------------------------------------------------------------------------
| Approved candidate set for validation only
|--------------------------------------------------------------------------
*/

$candidates = [
    'ATAMBUA' => [
        'trade_point_id' => 7,
        'trade_point_name' => 'Atapupu Border Crossing',
        'city' => 'Atambua',
        'province_id' => 23,
    ],

    'SURAKARTA' => [
        'trade_point_id' => 3,
        'trade_point_name' => 'Adi Soemarmo Airport',
        'city' => 'Surakarta',
        'province_id' => 10,
    ],

    'PONTIANAK' => [
        'trade_point_id' => 43,
        'trade_point_name' => 'Supadio International Airport',
        'city' => 'Pontianak',
        'province_id' => 12,
    ],

    'JAYAPURA' => [
        'trade_point_id' => 35,
        'trade_point_name' => 'Skouw Border Crossing',
        'city' => 'Jayapura',
        'province_id' => 24,
    ],
];

/*
|--------------------------------------------------------------------------
| Expected residual record counts
|--------------------------------------------------------------------------
*/

$expectedCounts = [
    'ATAMBUA' => 3835,
    'SURAKARTA' => 497,
    'PONTIANAK' => 267,
    'JAYAPURA' => 178,
];

$totalExpected = array_sum($expectedCounts);

echo "EXPECTED TARGET RECORDS : {$totalExpected}\n\n";

if (count($candidates) !== 4) {
    throw new RuntimeException(
        'Expected exactly 4 candidates.'
    );
}

/*
|--------------------------------------------------------------------------
| Baseline
|--------------------------------------------------------------------------
*/

$currentNull =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn(
            'trade_flow',
            ['export', 'import']
        )
        ->whereNull('trade_point_id')
        ->count();

echo "CURRENT NULL TRADE POINT RECORDS : {$currentNull}\n\n";

if ($currentNull !== 51525) {
    throw new RuntimeException(
        "Expected 51525 unresolved Trade Point records, got {$currentNull}."
    );
}

/*
|--------------------------------------------------------------------------
| Validate master candidates
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "MASTER VALIDATION\n";
echo "========================================\n";

$masterValidationPass = true;

foreach ($candidates as $sourceName => $candidate) {

    $master =
        DB::table('trade_points')
            ->where(
                'id',
                $candidate['trade_point_id']
            )
            ->first();

    $pass =
        $master !== null
        &&
        (int) ($master->is_active ?? 1) === 1
        &&
        (string) $master->name
            ===
            $candidate['trade_point_name']
        &&
        (int) $master->province_id
            ===
            $candidate['province_id'];

    echo sprintf(
        "  %-12s : %s -> ID=%d | %s | CITY=%s | PROVINCE=%d\n",
        $sourceName,
        $pass ? 'PASS' : 'FAIL',
        $candidate['trade_point_id'],
        $candidate['trade_point_name'],
        $candidate['city'],
        $candidate['province_id']
    );

    if (!$pass) {
        $masterValidationPass = false;
    }
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Candidate-specific source statistics
|--------------------------------------------------------------------------
*/

$validationPass = $masterValidationPass;

foreach ($candidates as $sourceName => $candidate) {

    $expectedCount =
        $expectedCounts[$sourceName];

    echo "========================================\n";
    echo "VALIDATION : {$sourceName}\n";
    echo "========================================\n";

    /*
    |--------------------------------------------------------------------------
    | Source baseline
    |--------------------------------------------------------------------------
    */

    $sourceQuery =
        DB::table('trade_statistics')
            ->whereBetween(
                'year',
                [2019, 2026]
            )
            ->whereIn(
                'trade_flow',
                ['export', 'import']
            )
            ->whereNull(
                'trade_point_id'
            )
            ->where(
                'port_name',
                $sourceName
            );

    $recordCount =
        (clone $sourceQuery)->count();

    $provinceMismatch =
        (clone $sourceQuery)
            ->where(
                'province_id',
                '<>',
                $candidate['province_id']
            )
            ->count();

    $provinceNull =
        (clone $sourceQuery)
            ->whereNull('province_id')
            ->count();

    echo "  SOURCE RECORDS          : {$recordCount}\n";
    echo "  EXPECTED RECORDS        : {$expectedCount}\n";
    echo "  PROVINCE MISMATCH       : {$provinceMismatch}\n";
    echo "  PROVINCE NULL           : {$provinceNull}\n";

    /*
    |--------------------------------------------------------------------------
    | Trade flow distribution
    |--------------------------------------------------------------------------
    */

    $flows =
        (clone $sourceQuery)
            ->select(
                'trade_flow'
            )
            ->selectRaw(
                'COUNT(*) AS records'
            )
            ->selectRaw(
                'SUM(trade_value) AS trade_value'
            )
            ->selectRaw(
                'SUM(trade_volume) AS trade_volume'
            )
            ->groupBy(
                'trade_flow'
            )
            ->orderBy(
                'trade_flow'
            )
            ->get();

    echo PHP_EOL;
    echo "  TRADE FLOW DISTRIBUTION:\n";

    foreach ($flows as $flow) {

        echo sprintf(
            "    %-7s | %6d records | VALUE=%15.3f | VOLUME=%15.3f\n",
            strtoupper(
                (string) $flow->trade_flow
            ),
            $flow->records,
            $flow->trade_value,
            $flow->trade_volume
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Country distribution
    |--------------------------------------------------------------------------
    */

    $countries =
        (clone $sourceQuery)
            ->select(
                'country_name'
            )
            ->selectRaw(
                'COUNT(*) AS records'
            )
            ->groupBy(
                'country_name'
            )
            ->orderByDesc(
                'records'
            )
            ->limit(10)
            ->get();

    echo PHP_EOL;
    echo "  TOP COUNTRY CONTEXT:\n";

    foreach ($countries as $country) {

        echo sprintf(
            "    %-35s | %6d records\n",
            trim(
                (string) $country->country_name
            ),
            $country->records
        );
    }

    /*
    |--------------------------------------------------------------------------
    | HS distribution
    |--------------------------------------------------------------------------
    */

    $hsCount =
        (clone $sourceQuery)
            ->whereNotNull(
                'hs_code'
            )
            ->distinct(
                'hs_code'
            )
            ->count(
                'hs_code'
            );

    echo PHP_EOL;
    echo "  DISTINCT HS CODES        : {$hsCount}\n";

    /*
    |--------------------------------------------------------------------------
    | Source-specific trade point candidate
    |--------------------------------------------------------------------------
    */

    $existingSameName =
        DB::table('trade_points')
            ->whereRaw(
                'UPPER(name) = ?',
                [
                    mb_strtoupper(
                        $sourceName
                    ),
                ]
            )
            ->count();

    echo "  EXACT MASTER NAME MATCH  : {$existingSameName}\n";

    /*
    |--------------------------------------------------------------------------
    | City semantic check
    |--------------------------------------------------------------------------
    */

    $cityMatch =
        strtoupper(
            trim($sourceName)
        )
        ===
        strtoupper(
            trim($candidate['city'])
        );

    echo "  SOURCE = CITY            : "
        . ($cityMatch ? 'YES' : 'NO')
        . PHP_EOL;

    /*
    |--------------------------------------------------------------------------
    | Deterministic criteria
    |--------------------------------------------------------------------------
    */

    $pass =
        $recordCount === $expectedCount
        &&
        $provinceMismatch === 0
        &&
        $provinceNull === 0
        &&
        $existingSameName === 0
        &&
        $cityMatch;

    echo PHP_EOL;

    echo "  DETERMINISTIC RESULT     : "
        . ($pass ? 'PASS' : 'REVIEW')
        . PHP_EOL;

    if (!$pass) {
        $validationPass = false;
    }

    echo PHP_EOL;
}

/*
|--------------------------------------------------------------------------
| Cross-candidate collision check
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "CANDIDATE COLLISION CHECK\n";
echo "========================================\n";

$ids = array_column(
    $candidates,
    'trade_point_id'
);

$uniqueIds =
    array_unique($ids);

$collisionPass =
    count($ids)
    ===
    count($uniqueIds);

echo "  CANDIDATES            : "
    . count($ids)
    . PHP_EOL;

echo "  UNIQUE TRADE POINT ID : "
    . count($uniqueIds)
    . PHP_EOL;

echo "  COLLISION CHECK       : "
    . ($collisionPass ? 'PASS' : 'FAIL')
    . PHP_EOL;

if (!$collisionPass) {
    $validationPass = false;
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Aggregate validation
|--------------------------------------------------------------------------
*/

$totalTarget =
    0;

foreach ($expectedCounts as $count) {
    $totalTarget += $count;
}

echo "========================================\n";
echo "AGGREGATE VALIDATION\n";
echo "========================================\n";

echo "  EXPECTED TARGET RECORDS : {$totalExpected}\n";
echo "  AGGREGATE TARGET        : {$totalTarget}\n";

$aggregatePass =
    $totalTarget === 4777;

echo "  AGGREGATE CHECK         : "
    . ($aggregatePass ? 'PASS' : 'FAIL')
    . PHP_EOL;

if (!$aggregatePass) {
    $validationPass = false;
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

/*
|--------------------------------------------------------------------------
| Final gate
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "DETERMINISTIC VALIDATION GATE\n";
echo "========================================\n";

echo "  MASTER VALIDATION : "
    . ($masterValidationPass ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo "  COLLISION CHECK    : "
    . ($collisionPass ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo "  AGGREGATE CHECK    : "
    . ($aggregatePass ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo "  VALIDATION         : "
    . ($validationPass ? 'PASS' : 'REVIEW')
    . PHP_EOL;

echo PHP_EOL;

if ($validationPass) {
    echo "TRADE POINT DETERMINISTIC VALIDATION : PASS\n";
} else {
    echo "TRADE POINT DETERMINISTIC VALIDATION : REVIEW\n";
    exit(1);
}

echo "========================================\n";