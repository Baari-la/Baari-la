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

echo "========================================\n";
echo "DIGESTEX KEMENDAG COUNTRY ALIAS BACKFILL DRY-RUN\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  OPERATION   : READ ONLY\n\n";

$resolver =
    app(CountryResolverService::class);

/*
|--------------------------------------------------------------------------
| Approved aliases
|--------------------------------------------------------------------------
*/

$targetNames = [
    'SRI LANGKA' => 23,
    'REPUBLIK CZECH' => 51,
    'LIBIA' => 90,
];

/*
|--------------------------------------------------------------------------
| Current residual baseline
|--------------------------------------------------------------------------
*/

$totalNullBefore =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('country_id')
        ->whereNotNull('country_name')
        ->where('country_name', '<>', '')
        ->count();

echo "CURRENT NULL COUNTRY RECORDS : "
    . $totalNullBefore
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Candidate records
|--------------------------------------------------------------------------
*/

$rows =
    DB::table('trade_statistics')
        ->whereBetween('year', [2019, 2026])
        ->whereIn('trade_flow', ['export', 'import'])
        ->whereNull('country_id')
        ->whereIn('country_name', array_keys($targetNames))
        ->select(
            'id',
            'year',
            'month',
            'trade_flow',
            'country_name',
            'country_code',
            'province_name',
            'port_name',
            'hs_code',
            'trade_value',
            'trade_volume',
            'import_batch_id'
        )
        ->orderBy('id')
        ->get();

echo "TARGET RECORDS : "
    . $rows->count()
    . PHP_EOL;

echo PHP_EOL;

$resolved = 0;
$failed = 0;
$byAlias = [];

/*
|--------------------------------------------------------------------------
| Resolve every target record
|--------------------------------------------------------------------------
*/

foreach ($rows as $row) {

    $expectedCountryId =
        $targetNames[
            $row->country_name
        ] ?? null;

    if ($expectedCountryId === null) {
        $failed++;
        continue;
    }

    $resolvedCountry =
        $resolver->resolve(
            $row->country_name
        );

    if (
        $resolvedCountry === null
        ||
        (int) $resolvedCountry->id
            !==
            $expectedCountryId
    ) {

        $failed++;
        continue;
    }

    $resolved++;

    $key =
        $row->country_name;

    if (!isset($byAlias[$key])) {
        $byAlias[$key] = [
            'expected_country_id' =>
                $expectedCountryId,

            'records' =>
                0,

            'trade_value' =>
                0.0,

            'trade_volume' =>
                0.0,
        ];
    }

    $byAlias[$key]['records']++;

    $byAlias[$key]['trade_value'] +=
        (float) $row->trade_value;

    $byAlias[$key]['trade_volume'] +=
        (float) $row->trade_volume;
}

/*
|--------------------------------------------------------------------------
| Summary
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "BACKFILL SUMMARY\n";
echo "========================================\n";

echo "  NULL BEFORE         : "
    . $totalNullBefore
    . PHP_EOL;

echo "  TARGET RECORDS      : "
    . $rows->count()
    . PHP_EOL;

echo "  RESOLVED            : "
    . $resolved
    . PHP_EOL;

echo "  UNRESOLVED / FAIL   : "
    . $failed
    . PHP_EOL;

echo "  WOULD UPDATE        : "
    . $resolved
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Alias breakdown
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "ALIAS BREAKDOWN\n";
echo "========================================\n";

foreach ($byAlias as $sourceName => $data) {

    echo sprintf(
        "  %-30s | country_id=%3d | %7d records | VALUE=%15.3f | VOLUME=%15.3f\n",
        $sourceName,
        $data['expected_country_id'],
        $data['records'],
        $data['trade_value'],
        $data['trade_volume']
    );
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Expected target checks
|--------------------------------------------------------------------------
*/

$expectedByAlias = [
    'SRI LANGKA' => [
        'records' => 20127,
        'country_id' => 23,
    ],

    'REPUBLIK CZECH' => [
        'records' => 3349,
        'country_id' => 51,
    ],

    'LIBIA' => [
        'records' => 203,
        'country_id' => 90,
    ],
];

echo "========================================\n";
echo "DETERMINISTIC TARGET VALIDATION\n";
echo "========================================\n";

$targetValidationPass = true;

foreach ($expectedByAlias as $sourceName => $expected) {

    $actual =
        $byAlias[$sourceName]
        ?? [
            'records' => 0,
            'expected_country_id' => null,
        ];

    $pass =
        $actual['records'] === $expected['records']
        &&
        (int) $actual['expected_country_id']
            ===
            $expected['country_id'];

    echo sprintf(
        "  %-30s : %s (%d/%d records, country_id=%d)\n",
        $sourceName,
        $pass ? 'PASS' : 'FAIL',
        $actual['records'],
        $expected['records'],
        (int) (
            $actual['expected_country_id']
            ?? 0
        )
    );

    if (!$pass) {
        $targetValidationPass = false;
    }
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
    $totalNullBefore === 31698
    &&
    $rows->count() === 23679
    &&
    $resolved === 23679
    &&
    $failed === 0
    &&
    $targetValidationPass;

echo "========================================\n";

if ($success) {
    echo "COUNTRY ALIAS BACKFILL DRY-RUN : PASS\n";
} else {
    echo "COUNTRY ALIAS BACKFILL DRY-RUN : REVIEW\n";
}

echo "========================================\n";