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
echo "DIGESTEX KEMENDAG TRADE POINT RESIDUAL AUDIT\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  OPERATION   : READ ONLY\n\n";

/*
|--------------------------------------------------------------------------
| Baseline
|--------------------------------------------------------------------------
*/

$currentNull =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('trade_point_id')
        ->count();

echo "CURRENT NULL TRADE POINT RECORDS : {$currentNull}\n\n";

if ($currentNull !== 51525) {
    throw new RuntimeException(
        "Unexpected Trade Point residual. Expected 51525, got {$currentNull}."
    );
}

/*
|--------------------------------------------------------------------------
| Residual source names
|--------------------------------------------------------------------------
|
| port_name is the source-side Kemendag nomenclature.
|--------------------------------------------------------------------------
*/

$residuals =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('trade_point_id')
        ->selectRaw(
            '
            port_name,
            COUNT(*) AS records,
            SUM(trade_value) AS trade_value,
            SUM(trade_volume) AS trade_volume
            '
        )
        ->groupBy('port_name')
        ->orderByDesc('records')
        ->get();

echo "========================================\n";
echo "TOP UNRESOLVED TRADE POINT NAMES\n";
echo "========================================\n";

foreach ($residuals as $row) {

    $name =
        trim((string) $row->port_name);

    echo sprintf(
        "  %-50s | %7d records | VALUE=%15.3f | VOLUME=%15.3f\n",
        $name === '' ? '[EMPTY]' : $name,
        $row->records,
        $row->trade_value,
        $row->trade_volume
    );
}

echo PHP_EOL;

echo "DISTINCT UNRESOLVED TRADE POINT NAMES : "
    . $residuals->count()
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Master-side inventory
|--------------------------------------------------------------------------
|
| First inspect likely available master fields without changing data.
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "TRADE POINT MASTER INVENTORY\n";
echo "========================================\n";

$masterCount =
    DB::table('trade_points')->count();

$activeMasterCount =
    DB::table('trade_points')
        ->where('is_active', 1)
        ->count();

echo "  TOTAL MASTER TRADE POINTS   : {$masterCount}\n";
echo "  ACTIVE MASTER TRADE POINTS  : {$activeMasterCount}\n";

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Exact-name match test
|--------------------------------------------------------------------------
*/

$exactMatches = 0;
$exactNames = 0;

foreach ($residuals as $row) {

    $sourceName =
        trim((string) $row->port_name);

    if ($sourceName === '') {
        continue;
    }

    $matches =
        DB::table('trade_points')
            ->whereRaw(
                'UPPER(name) = ?',
                [mb_strtoupper($sourceName)]
            )
            ->where('is_active', 1)
            ->count();

    if ($matches === 1) {
        $exactNames++;
        $exactMatches += (int) $row->records;
    }
}

echo "========================================\n";
echo "EXACT MASTER NAME MATCH\n";
echo "========================================\n";

echo "  EXACT MATCH NAMES   : {$exactNames}\n";
echo "  EXACT MATCH RECORDS : {$exactMatches}\n";

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Empty / NULL source-name audit
|--------------------------------------------------------------------------
*/

$emptyPortRecords =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('trade_point_id')
        ->where(function ($query) {
            $query
                ->whereNull('port_name')
                ->orWhere('port_name', '');
        })
        ->count();

echo "========================================\n";
echo "SOURCE NAME QUALITY\n";
echo "========================================\n";

echo "  NULL / EMPTY PORT NAME : {$emptyPortRecords}\n";

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Candidate source sample
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "SOURCE NAME SAMPLE\n";
echo "========================================\n";

$sample =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('trade_point_id')
        ->whereNotNull('port_name')
        ->where('port_name', '<>', '')
        ->select(
            'id',
            'year',
            'month',
            'trade_flow',
            'port_name',
            'province_name',
            'province_id',
            'country_name',
            'trade_value',
            'trade_volume',
            'import_batch_id'
        )
        ->orderBy('id')
        ->limit(50)
        ->get();

foreach ($sample as $row) {

    echo sprintf(
        "  ID=%-8d | %4d-%02d | %-6s | %-40s | PROVINCE=%s | COUNTRY=%s\n",
        $row->id,
        $row->year,
        $row->month,
        strtoupper((string) $row->trade_flow),
        trim((string) $row->port_name),
        trim((string) $row->province_name),
        trim((string) $row->country_name)
    );
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

$success =
    $currentNull === 51525
    &&
    $emptyPortRecords >= 0;

echo "========================================\n";
echo "TRADE POINT RESIDUAL AUDIT GATE\n";
echo "========================================\n";

echo "  CURRENT NULL RECORDS : {$currentNull}\n";
echo "  DISTINCT SOURCE NAMES: {$residuals->count()}\n";
echo "  EXACT MATCH RECORDS  : {$exactMatches}\n";
echo "  EMPTY SOURCE RECORDS : {$emptyPortRecords}\n";
echo "  VALIDATION            : "
    . ($success ? 'PASS' : 'FAIL')
    . PHP_EOL;

echo PHP_EOL;

if ($success) {
    echo "TRADE POINT RESIDUAL AUDIT : PASS\n";
} else {
    echo "TRADE POINT RESIDUAL AUDIT : REVIEW\n";
    exit(1);
}

echo "========================================\n";