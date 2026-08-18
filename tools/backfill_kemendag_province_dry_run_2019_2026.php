<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use App\Services\Trade\ProvinceResolverService;
use Illuminate\Support\Facades\DB;

echo "========================================\n";
echo "DIGESTEX KEMENDAG PROVINCE BACKFILL DRY-RUN\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  DATABASE    : READ ONLY\n\n";

$resolver =
    app(ProvinceResolverService::class);

$rows =
    DB::table('trade_statistics')
        ->whereBetween(
            'year',
            [2019, 2026]
        )
        ->whereNull('province_id')
        ->select(
            'id',
            'year',
            'month',
            'trade_flow',
            'province_name',
            'province_code',
            'country_name',
            'port_name',
            'hs_code',
            'trade_value',
            'trade_volume',
            'import_batch_id'
        )
        ->orderBy('year')
        ->orderBy('trade_flow')
        ->orderBy('month')
        ->orderBy('id')
        ->get();

echo "NULL PROVINCE RECORDS : "
    . $rows->count()
    . PHP_EOL;

echo PHP_EOL;

$resolved = 0;
$unresolved = 0;

$updates = [];

foreach ($rows as $row) {

    $province =
        $resolver->resolve(
            $row->province_name
        );

    echo "----------------------------------------\n";

    echo "  ID             : "
        . $row->id
        . PHP_EOL;

    echo "  YEAR           : "
        . $row->year
        . PHP_EOL;

    echo "  MONTH          : "
        . $row->month
        . PHP_EOL;

    echo "  FLOW           : "
        . strtoupper($row->trade_flow)
        . PHP_EOL;

    echo "  SOURCE PROVINCE: "
        . ($row->province_name ?? 'NULL')
        . PHP_EOL;

    echo "  SOURCE CODE    : "
        . ($row->province_code ?? 'NULL')
        . PHP_EOL;

    echo "  COUNTRY        : "
        . ($row->country_name ?? 'NULL')
        . PHP_EOL;

    echo "  PORT           : "
        . ($row->port_name ?? 'NULL')
        . PHP_EOL;

    if ($province !== null) {

        $resolved++;

        $updates[] = [
            'id' => (int) $row->id,
            'province_id' => (int) $province['id'],
            'province_code' => $province['code'],
            'province_name' => $province['name'],
        ];

        echo "  RESOLUTION     : PASS\n";

        echo "  TARGET ID      : "
            . $province['id']
            . PHP_EOL;

        echo "  TARGET CODE    : "
            . $province['code']
            . PHP_EOL;

        echo "  TARGET NAME    : "
            . $province['name']
            . PHP_EOL;

    } else {

        $unresolved++;

        echo "  RESOLUTION     : FAIL\n";
    }
}

echo PHP_EOL;

echo "========================================\n";
echo "BACKFILL SUMMARY\n";
echo "========================================\n";

echo "  NULL BEFORE        : "
    . $rows->count()
    . PHP_EOL;

echo "  RESOLVABLE         : "
    . $resolved
    . PHP_EOL;

echo "  UNRESOLVED         : "
    . $unresolved
    . PHP_EOL;

echo "  WOULD UPDATE       : "
    . count($updates)
    . PHP_EOL;

echo PHP_EOL;

echo "DATABASE SAFETY:\n";
echo "  INSERT             : NO\n";
echo "  UPDATE             : NO\n";
echo "  DELETE             : NO\n";
echo "  DATABASE MODIFIED  : NO\n";

echo PHP_EOL;

echo "========================================\n";

if (
    $rows->count() === $resolved
    &&
    $unresolved === 0
) {

    echo "PROVINCE BACKFILL DRY-RUN : PASS\n";

} else {

    echo "PROVINCE BACKFILL DRY-RUN : REVIEW\n";
}

echo "========================================\n";