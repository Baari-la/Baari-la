<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use RuntimeException;


echo "========================================\n";
echo "DIGESTEX KEMENDAG CONDITIONAL TRADE POINT PREPARATION\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  SOURCES     : ATAMBUA + JAYAPURA\n";
echo "  OPERATION   : READ ONLY\n\n";

/*
|--------------------------------------------------------------------------
| Conditional approved candidates
|--------------------------------------------------------------------------
*/

$targets = [
    'ATAMBUA' => [
        'trade_point_id' => 7,
        'trade_point_name' => 'Atapupu Border Crossing',
        'province_id' => 23,
        'province_name' => 'NUSA TENGGARA TIMUR',
    ],

    'JAYAPURA' => [
        'trade_point_id' => 35,
        'trade_point_name' => 'Skouw Border Crossing',
        'province_id' => 24,
        'province_name' => 'PAPUA',
    ],
];

$expectedTotal = 3985;


/*
|--------------------------------------------------------------------------
| Global baseline
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

if ($currentNull !== 50761) {
    throw new RuntimeException(
        "Expected 50761 unresolved Trade Point records, got {$currentNull}."
    );
}

/*
|--------------------------------------------------------------------------
| Master validation
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "MASTER VALIDATION\n";
echo "========================================\n";

$masterValidationPass = true;

foreach ($targets as $sourceName => $target) {

    $master =
        DB::table('trade_points')
            ->where(
                'id',
                $target['trade_point_id']
            )
            ->first([
                'id',
                'name',
                'city',
                'province_id',
                'is_active',
            ]);

    $pass =
        $master !== null
        &&
        (int) $master->is_active === 1
        &&
        (string) $master->name ===
            $target['trade_point_name']
        &&
        (int) $master->province_id ===
            $target['province_id'];

    echo sprintf(
        "  %-10s : %s -> master=%s | %s | province=%d\n",
        $sourceName,
        $pass ? 'PASS' : 'FAIL',
        $master?->id ?? 'NULL',
        $target['trade_point_name'],
        $target['province_id']
    );

    if (!$pass) {
        $masterValidationPass = false;
    }
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Conditional validation
|--------------------------------------------------------------------------
*/

$validationPass = $masterValidationPass;

$totalEligible = 0;
$totalMismatch = 0;
$totalTarget = 0;

foreach ($targets as $sourceName => $target) {

    echo "========================================\n";
    echo "CONDITIONAL VALIDATION : {$sourceName}\n";
    echo "========================================\n";

    $base =
        DB::table('trade_statistics')
            ->whereBetween(
                'year',
                [2019, 2026]
            )
            ->whereIn(
                'trade_flow',
                ['export', 'import']
            )
            ->whereNull('trade_point_id')
            ->where(
                'port_name',
                $sourceName
            );

    $totalSource =
        (clone $base)->count();

    $provinceMatch =
        (clone $base)
            ->where(
                'province_id',
                $target['province_id']
            )
            ->count();

    $provinceMismatch =
        (clone $base)
            ->where(function ($query) use (
                $target
            ) {
                $query
                    ->whereNull('province_id')
                    ->orWhere(
                        'province_id',
                        '<>',
                        $target['province_id']
                    );
            })
            ->count();

    $eligible =
        (clone $base)
            ->where(
                'province_id',
                $target['province_id']
            )
            ->count();

   
       /*
    |--------------------------------------------------------------------------
    | Trade flow distribution
    |--------------------------------------------------------------------------
    */

    $flows =
        (clone $base)
            ->where(
                'province_id',
                $target['province_id']
            )
            ->select('trade_flow')
            ->selectRaw(
                'COUNT(*) AS records'
            )
            ->selectRaw(
                'SUM(trade_value) AS trade_value'
            )
            ->selectRaw(
                'SUM(trade_volume) AS trade_volume'
            )
            ->groupBy('trade_flow')
            ->orderBy('trade_flow')
            ->get();

    echo "  SOURCE RECORDS          : {$totalSource}\n";
    echo "  PROVINCE MATCH          : {$provinceMatch}\n";
    echo "  PROVINCE MISMATCH/NULL  : {$provinceMismatch}\n";
    echo "  ELIGIBLE FOR BACKFILL   : {$eligible}\n";
    echo "  REVIEW RECORDS          : {$provinceMismatch}\n";

    echo PHP_EOL;
    echo "  ELIGIBLE TRADE FLOW DISTRIBUTION:\n";

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
    | Eligible aggregate
    |--------------------------------------------------------------------------
    */

    $aggregate =
        (clone $base)
            ->where(
                'province_id',
                $target['province_id']
            )
            ->selectRaw(
                '
                SUM(trade_value) AS trade_value,
                SUM(trade_volume) AS trade_volume
                '
            )
            ->first();

    echo PHP_EOL;
    echo "  ELIGIBLE AGGREGATE:\n";
    echo "    TRADE VALUE  : "
        . ($aggregate->trade_value ?? 0)
        . PHP_EOL;
    echo "    TRADE VOLUME : "
        . ($aggregate->trade_volume ?? 0)
        . PHP_EOL;

    /*
    |--------------------------------------------------------------------------
    | Deterministic gate for source
    |--------------------------------------------------------------------------
    */

    $sourcePass =
    $totalSource > 0
    &&
    $provinceMatch === $eligible
    &&
    $provinceMismatch >= 0;

    echo PHP_EOL;
    echo "  CONDITIONAL RESULT      : "
        . ($sourcePass ? 'PASS' : 'REVIEW')
        . PHP_EOL;

    if (!$sourcePass) {
        $validationPass = false;
    }

    $totalEligible += $eligible;
    $totalMismatch += $provinceMismatch;
    $totalTarget += $totalSource;
}

/*
|--------------------------------------------------------------------------
| Aggregate gate
|--------------------------------------------------------------------------
*/

echo PHP_EOL;
echo "========================================\n";
echo "CONDITIONAL AGGREGATE VALIDATION\n";
echo "========================================\n";

echo "  TOTAL SOURCE RECORDS    : {$totalTarget}\n";
echo "  TOTAL ELIGIBLE RECORDS  : {$totalEligible}\n";
echo "  TOTAL REVIEW RECORDS    : {$totalMismatch}\n";
echo "  EXPECTED ELIGIBLE       : {$expectedTotal}\n";

$expectedSourceTotal = 4013;
$expectedReviewTotal = 28;

$aggregatePass =
    $totalEligible === $expectedTotal
    &&
    $totalTarget === $expectedSourceTotal
    &&
    $totalMismatch === $expectedReviewTotal;

echo "  AGGREGATE CHECK         : "
    . ($aggregatePass ? 'PASS' : 'FAIL')
    . PHP_EOL;

if (!$aggregatePass) {
    $validationPass = false;
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Safety
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
    $currentNull === 50761
    &&
    $masterValidationPass
    &&
    $aggregatePass
    &&
    $validationPass;

echo "========================================\n";
echo "CONDITIONAL TRADE POINT PREPARATION GATE\n";
echo "========================================\n";

echo "  MASTER VALIDATION : "
    . ($masterValidationPass ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo "  AGGREGATE CHECK    : "
    . ($aggregatePass ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo "  VALIDATION         : "
    . ($validationPass ? 'PASS' : 'REVIEW')
    . PHP_EOL;

echo PHP_EOL;

if ($success) {
    echo "CONDITIONAL TRADE POINT PREPARATION : PASS\n";
} else {
    echo "CONDITIONAL TRADE POINT PREPARATION : REVIEW\n";
    exit(1);
}

echo "========================================\n";