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
echo "DIGESTEX KEMENDAG PROVINCE BACKFILL 2019-2026\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  OPERATION   : TRANSACTIONAL BACKFILL\n\n";

$resolver = app(ProvinceResolverService::class);

$beforeNull =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereNull('province_id')
        ->count();

echo "BEFORE:\n";
echo "  NULL PROVINCE ID : {$beforeNull}\n\n";

if ($beforeNull === 0) {
    echo "Nothing to backfill.\n";
    echo "========================================\n";
    echo "PROVINCE BACKFILL : PASS\n";
    echo "========================================\n";
    exit(0);
}

$updatedIds = [];

DB::transaction(function () use (
    $resolver,
    &$updatedIds
): void {

    $rows =
        DB::table('trade_statistics')
            ->whereBetween('year', [2019, 2026])
            ->whereNull('province_id')
            ->select(
                'id',
                'province_name'
            )
            ->orderBy('id')
            ->lockForUpdate()
            ->get();

    foreach ($rows as $row) {

        $province =
            $resolver->resolve(
                $row->province_name
            );

        if ($province === null) {
            throw new RuntimeException(
                'Province tidak dapat di-resolve untuk trade_statistics.id='
                . $row->id
                . ' | source='
                . ($row->province_name ?? 'NULL')
            );
        }

        $affected =
            DB::table('trade_statistics')
                ->where('id', $row->id)
                ->whereNull('province_id')
                ->update([
                    'province_id' =>
                        (int) $province['id'],

                    'province_code' =>
                        $province['code'],

                    'province_name' =>
                        $province['name'],

                    'updated_at' =>
                        now(),
                ]);

        if ($affected !== 1) {
            throw new RuntimeException(
                'Expected exactly 1 row updated for trade_statistics.id='
                . $row->id
                . ', affected='
                . $affected
            );
        }

        $updatedIds[] =
            (int) $row->id;
    }
});

/*
|--------------------------------------------------------------------------
| Post-transaction verification
|--------------------------------------------------------------------------
*/

$afterNull =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereNull('province_id')
        ->count();

$updatedCount =
    count($updatedIds);

echo "AFTER:\n";
echo "  UPDATED RECORDS : {$updatedCount}\n";
echo "  NULL PROVINCE ID: {$afterNull}\n\n";

echo "VERIFICATION:\n";

if ($updatedCount !== $beforeNull) {
    echo "  UPDATED COUNT   : FAIL\n";
} else {
    echo "  UPDATED COUNT   : PASS\n";
}

if ($afterNull !== 0) {
    echo "  NULL RESIDUAL   : FAIL\n";
} else {
    echo "  NULL RESIDUAL   : PASS\n";
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Check the actual 10 records
|--------------------------------------------------------------------------
*/

$remaining =
    DB::table('trade_statistics')
        ->whereIn('id', $updatedIds)
        ->whereNull('province_id')
        ->count();

echo "TARGET RECORD CHECK:\n";
echo "  TARGET IDS      : {$updatedCount}\n";
echo "  STILL NULL      : {$remaining}\n";

echo PHP_EOL;

if (
    $updatedCount === $beforeNull
    &&
    $afterNull === 0
    &&
    $remaining === 0
) {
    echo "========================================\n";
    echo "PROVINCE BACKFILL : PASS\n";
    echo "========================================\n";
} else {
    echo "========================================\n";
    echo "PROVINCE BACKFILL : REVIEW\n";
    echo "========================================\n";

    exit(1);
}