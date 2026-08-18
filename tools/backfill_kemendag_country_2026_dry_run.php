<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use App\Services\Trade\CountryResolverService;
use Illuminate\Support\Facades\DB;

const TARGET_YEAR = 2026;

echo "========================================\n";
echo "DIGESTEX KEMENDAG COUNTRY BACKFILL DRY-RUN 2026\n";
echo "========================================\n\n";

$resolver = app(CountryResolverService::class);

$beforeTotal =
    DB::table('trade_statistics')
        ->where('year', TARGET_YEAR)
        ->count();

$beforeNullCountry =
    DB::table('trade_statistics')
        ->where('year', TARGET_YEAR)
        ->whereNull('country_id')
        ->count();

echo "DATABASE BASELINE:\n";
echo "  2026 RECORDS          : {$beforeTotal}\n";
echo "  NULL COUNTRY ID       : {$beforeNullCountry}\n\n";

/*
|--------------------------------------------------------------------------
| Aggregate NULL country names by trade flow + source name
|--------------------------------------------------------------------------
*/

$rows =
    DB::table('trade_statistics')
        ->where('year', TARGET_YEAR)
        ->whereNull('country_id')
        ->whereNotNull('country_name')
        ->where('country_name', '<>', '')
        ->selectRaw(
            'trade_flow, country_name, COUNT(*) AS records'
        )
        ->groupBy(
            'trade_flow',
            'country_name'
        )
        ->orderBy('trade_flow')
        ->orderByDesc('records')
        ->get();

$resolvedRows = [];
$unresolvedRows = [];
$specialRows = [];

$totalResolvable = 0;
$totalUnresolvable = 0;
$totalSpecial = 0;

/*
|--------------------------------------------------------------------------
| Special nomenclature
|--------------------------------------------------------------------------
*/

$specialNames = [
    'INDONESIA (BATAM)',
    'FD STS MICRONESIA',
];

/*
|--------------------------------------------------------------------------
| Resolve
|--------------------------------------------------------------------------
*/

foreach ($rows as $row) {

    $tradeFlow =
        (string) $row->trade_flow;

    $sourceName =
        trim((string) $row->country_name);

    $records =
        (int) $row->records;

    /*
     * Special source nomenclature is deliberately excluded
     * from automatic country backfill.
     */
    if (
        in_array(
            mb_strtoupper($sourceName),
            $specialNames,
            true
        )
    ) {

        $specialRows[] = [
            'trade_flow' => $tradeFlow,
            'source_name' => $sourceName,
            'records' => $records,
        ];

        $totalSpecial += $records;

        continue;
    }

    $country =
        $resolver->resolve(
            $sourceName,
            'KEMENDAG'
        );

    if ($country !== null) {

        $resolvedRows[] = [
            'trade_flow' => $tradeFlow,
            'source_name' => $sourceName,
            'country_id' => (int) $country->id,
            'country_code' => $country->country_code,
            'iso3' => $country->iso3,
            'canonical_name' => $country->country_name_en,
            'records' => $records,
        ];

        $totalResolvable += $records;

    } else {

        $unresolvedRows[] = [
            'trade_flow' => $tradeFlow,
            'source_name' => $sourceName,
            'records' => $records,
        ];

        $totalUnresolvable += $records;
    }
}

/*
|--------------------------------------------------------------------------
| Sort
|--------------------------------------------------------------------------
*/

usort(
    $resolvedRows,
    static fn(array $a, array $b): int =>
        $b['records'] <=> $a['records']
);

usort(
    $unresolvedRows,
    static fn(array $a, array $b): int =>
        $b['records'] <=> $a['records']
);

usort(
    $specialRows,
    static fn(array $a, array $b): int =>
        $b['records'] <=> $a['records']
);

/*
|--------------------------------------------------------------------------
| Output
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "BACKFILL SUMMARY\n";
echo "========================================\n";

echo "  NULL COUNTRY RECORDS   : {$beforeNullCountry}\n";
echo "  RESOLVABLE RECORDS     : {$totalResolvable}\n";
echo "  UNRESOLVED RECORDS     : {$totalUnresolvable}\n";
echo "  SPECIAL NOMENCLATURE   : {$totalSpecial}\n";

echo PHP_EOL;

echo "CHECK:\n";

$classifiedTotal =
    $totalResolvable
    + $totalUnresolvable
    + $totalSpecial;

echo "  CLASSIFIED RECORDS     : {$classifiedTotal}\n";
echo "  CLASSIFICATION BALANCE : "
    . (
        $classifiedTotal === $beforeNullCountry
            ? 'PASS'
            : 'FAIL'
    )
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Resolvable
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "RESOLVABLE COUNTRY RECORDS\n";
echo "========================================\n";

if (empty($resolvedRows)) {

    echo "  NONE\n";

} else {

    foreach ($resolvedRows as $item) {

        echo sprintf(
            "  %-7s | %-35s | ID=%d | %-3s | %-3s | %-30s | %d records\n",
            strtoupper($item['trade_flow']),
            $item['source_name'],
            $item['country_id'],
            $item['country_code'] ?? '-',
            $item['iso3'] ?? '-',
            $item['canonical_name'],
            $item['records']
        );
    }
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Special
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "SPECIAL SOURCE NOMENCLATURE\n";
echo "========================================\n";

if (empty($specialRows)) {

    echo "  NONE\n";

} else {

    foreach ($specialRows as $item) {

        echo sprintf(
            "  %-7s | %-35s | %d records\n",
            strtoupper($item['trade_flow']),
            $item['source_name'],
            $item['records']
        );
    }
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Unresolved
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "UNRESOLVED COUNTRY RECORDS\n";
echo "========================================\n";

if (empty($unresolvedRows)) {

    echo "  NONE\n";

} else {

    foreach ($unresolvedRows as $item) {

        echo sprintf(
            "  %-7s | %-45s | %d records\n",
            strtoupper($item['trade_flow']),
            $item['source_name'],
            $item['records']
        );
    }
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Production protection
|--------------------------------------------------------------------------
*/

$exportCount =
    DB::table('trade_statistics')
        ->where('year', TARGET_YEAR)
        ->where('trade_flow', 'export')
        ->count();

$importCount =
    DB::table('trade_statistics')
        ->where('year', TARGET_YEAR)
        ->where('trade_flow', 'import')
        ->count();

echo "========================================\n";
echo "PRODUCTION PROTECTION CHECK\n";
echo "========================================\n";

echo "  EXPORT 2026 RECORDS    : {$exportCount}\n";
echo "  IMPORT 2026 RECORDS    : {$importCount}\n";

echo PHP_EOL;

echo "========================================\n";
echo "DRY-RUN RESULT\n";
echo "========================================\n";

echo "  UPDATE WOULD BE APPLIED: "
    . $totalResolvable
    . " records\n";

echo "  DATABASE WAS NOT MODIFIED.\n";

echo "========================================\n";
echo "COUNTRY BACKFILL DRY-RUN : COMPLETE\n";
echo "========================================\n";