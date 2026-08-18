<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use App\Services\Trade\TradePointResolverService;
use Illuminate\Support\Facades\DB;

echo "========================================\n";
echo "DIGESTEX KEMENDAG APPROVED TRADE POINT BACKFILL DRY-RUN\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  TARGET      : 2 APPROVED TRADE POINT ALIASES\n";
echo "  OPERATION   : READ ONLY\n\n";

/*
|--------------------------------------------------------------------------
| Approved mappings
|--------------------------------------------------------------------------
*/

$targets = [
    'SURAKARTA' => 3,
    'PONTIANAK' => 43,
];

/*
|--------------------------------------------------------------------------
| Expected deterministic record counts
|--------------------------------------------------------------------------
*/

$expectedCounts = [
    'SURAKARTA' => 497,
    'PONTIANAK' => 267,
];

$expectedTotal = 764;

if (count($targets) !== 2) {
    throw new RuntimeException(
        'Expected exactly 2 approved trade point targets.'
    );
}

/*
|--------------------------------------------------------------------------
| Resolver
|--------------------------------------------------------------------------
*/

$resolver = app(
    TradePointResolverService::class
);

/*
|--------------------------------------------------------------------------
| Current NULL baseline
|--------------------------------------------------------------------------
*/

$currentNull = DB::table('trade_statistics')
    ->whereBetween('year', [2019, 2026])
    ->whereIn('trade_flow', ['export', 'import'])
    ->whereNull('trade_point_id')
    ->count();

echo "CURRENT NULL TRADE POINT RECORDS : {$currentNull}\n\n";

if ($currentNull !== 51525) {
    throw new RuntimeException(
        "Expected 51525 unresolved trade point records, got {$currentNull}."
    );
}

/*
|--------------------------------------------------------------------------
| Load approved target rows
|--------------------------------------------------------------------------
*/

$rows = DB::table('trade_statistics')
    ->whereBetween('year', [2019, 2026])
    ->whereIn('trade_flow', ['export', 'import'])
    ->whereNull('trade_point_id')
    ->whereIn('port_name', array_keys($targets))
    ->select(
        'id',
        'year',
        'month',
        'trade_flow',
        'port_name',
        'province_name',
        'province_id',
        'country_name',
        'hs_code',
        'trade_value',
        'trade_volume',
        'import_batch_id'
    )
    ->orderBy('id')
    ->get();

$targetCount = $rows->count();

echo "TARGET RECORDS : {$targetCount}\n\n";

if ($targetCount !== $expectedTotal) {
    throw new RuntimeException(
        "Unexpected target count. Expected {$expectedTotal}, got {$targetCount}."
    );
}

/*
|--------------------------------------------------------------------------
| Inisialisasi Breakdown (Mencegah Undefined Index)
|--------------------------------------------------------------------------
*/

$breakdown = [];
foreach ($targets as $sourceName => $tradePointId) {
    $breakdown[$sourceName] = [
        'trade_point_id' => $tradePointId,
        'records'        => 0,
        'trade_value'    => 0.0,
        'trade_volume'   => 0.0,
    ];
}

/*
|--------------------------------------------------------------------------
| Resolve each row
|--------------------------------------------------------------------------
*/

$resolved = 0;
$failed = 0;

foreach ($rows as $row) {
    $sourceName = trim((string) $row->port_name);
    $expectedTradePointId = $targets[$sourceName] ?? null;

    if ($expectedTradePointId === null) {
        $failed++;
        continue;
    }

    $resolvedTradePoint = $resolver->resolve($sourceName);

if (
    $resolvedTradePoint === null
    || !is_array($resolvedTradePoint)
) {
    $failed++;
    continue;
}

$resolvedTradePointId =
    $resolvedTradePoint['id']
    ?? $resolvedTradePoint['trade_point_id']
    ?? null;

if (
    $resolvedTradePointId === null
    || (int) $resolvedTradePointId !== $expectedTradePointId
) {
    $failed++;
    continue;
}

    $resolved++;

    $breakdown[$sourceName]['records']++;
    $breakdown[$sourceName]['trade_value'] += (float) $row->trade_value;
    $breakdown[$sourceName]['trade_volume'] += (float) $row->trade_volume;
}

/*
|--------------------------------------------------------------------------
| Summary
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "BACKFILL SUMMARY\n";
echo "========================================\n";
echo "  NULL BEFORE         : {$currentNull}\n";
echo "  TARGET RECORDS      : {$targetCount}\n";
echo "  RESOLVED            : {$resolved}\n";
echo "  FAILED              : {$failed}\n";
echo "  WOULD UPDATE        : {$resolved}\n\n";

/*
|--------------------------------------------------------------------------
| Deterministic validation
|--------------------------------------------------------------------------
*/

$validationPass = true;

echo "========================================\n";
echo "DETERMINISTIC TARGET VALIDATION\n";
echo "========================================\n";

foreach ($expectedCounts as $sourceName => $expectedCount) {
    $actual = $breakdown[$sourceName]['records'] ?? 0;
    $expectedTradePointId = $targets[$sourceName];

    $pass = ($actual === $expectedCount);

    echo sprintf(
        "  %-15s : %s (%d/%d records, trade_point_id=%d)\n",
        $sourceName,
        $pass ? 'PASS' : 'FAIL',
        $actual,
        $expectedCount,
        $expectedTradePointId
    );

    if (!$pass) {
        $validationPass = false;
    }
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Master verification
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "MASTER / RESOLVER VERIFICATION\n";
echo "========================================\n";

foreach ($targets as $sourceName => $tradePointId) {
    $master = DB::table('trade_points')
        ->where('id', $tradePointId)
        ->first([
            'id',
            'name',
            'code',
            'city',
            'province_id',
            'is_active',
        ]);

    $resolvedTradePoint = $resolver->resolve($sourceName);

$resolvedTradePointId =
    is_array($resolvedTradePoint)
        ? (
            $resolvedTradePoint['id']
            ?? $resolvedTradePoint['trade_point_id']
            ?? null
        )
        : null;

$pass =
    $master !== null
    && (int) $master->is_active === 1
    && $resolvedTradePointId !== null
    && (int) $resolvedTradePointId === $tradePointId;

echo sprintf(
    "  %-15s : %s -> master=%s resolver=%s\n",
    $sourceName,
    $pass ? 'PASS' : 'FAIL',
    $master?->id ?? 'NULL',
    $resolvedTradePointId ?? 'NULL'
);

    if (!$pass) {
        $validationPass = false;
    }
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Aggregate target
|--------------------------------------------------------------------------
*/

$totalValue = 0.0;
$totalVolume = 0.0;

foreach ($breakdown as $data) {
    $totalValue += $data['trade_value'];
    $totalVolume += $data['trade_volume'];
}

echo "========================================\n";
echo "TARGET AGGREGATE\n";
echo "========================================\n";
echo "  TRADE VALUE  : " . number_format($totalValue, 3, '.', '') . PHP_EOL;
echo "  TRADE VOLUME : " . number_format($totalVolume, 3, '.', '') . PHP_EOL;
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
echo "  DATABASE WAS NOT MODIFIED.\n\n";

/*
|--------------------------------------------------------------------------
| Final gate
|--------------------------------------------------------------------------
*/

$success =
    $currentNull === 51525
    && $targetCount === 764
    && $resolved === 764
    && $failed === 0
    && $validationPass === true;

echo "========================================\n";
echo "FINAL APPROVED TRADE POINT DRY-RUN GATE\n";
echo "========================================\n";
echo "  CURRENT NULL        : {$currentNull}\n";
echo "  TARGET RECORDS      : {$targetCount}\n";
echo "  RESOLVED            : {$resolved}\n";
echo "  FAILED              : {$failed}\n";
echo "  VALIDATION          : " . ($validationPass ? 'PASS' : 'FAIL') . PHP_EOL;
echo PHP_EOL;

if ($success) {
    echo "APPROVED TRADE POINT BACKFILL DRY-RUN : PASS\n";
} else {
    echo "APPROVED TRADE POINT BACKFILL DRY-RUN : REVIEW\n";
    exit(1);
}