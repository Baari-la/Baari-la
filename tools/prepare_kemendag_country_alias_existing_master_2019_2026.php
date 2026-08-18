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
echo "DIGESTEX KEMENDAG EXISTING MASTER COUNTRY ALIAS PREPARATION\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  OPERATION   : READ ONLY\n\n";

/*
|--------------------------------------------------------------------------
| Approved deterministic aliases
|--------------------------------------------------------------------------
*/

$aliases = [
    'SRI LANGKA' => [
        'country_id' => 23,
        'country_code' => 'LK',
        'iso3' => 'LKA',
        'canonical_name' => 'Sri Lanka',
    ],

    'REPUBLIK CZECH' => [
        'country_id' => 51,
        'country_code' => 'CZ',
        'iso3' => 'CZE',
        'canonical_name' => 'Czech Republic',
    ],

    'LIBIA' => [
        'country_id' => 90,
        'country_code' => 'LY',
        'iso3' => 'LBY',
        'canonical_name' => 'Libya',
    ],
];

$resolver =
    app(CountryResolverService::class);

/*
|--------------------------------------------------------------------------
| Validate master records
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "MASTER VALIDATION\n";
echo "========================================\n";

$masterConflicts = 0;

foreach ($aliases as $sourceName => $candidate) {

    $master =
        DB::table('mst_countries')
            ->where('id', $candidate['country_id'])
            ->first();

    if ($master === null) {

        echo "  {$sourceName} : MASTER MISSING\n";

        $masterConflicts++;
        continue;
    }

    $valid =
        strtoupper((string) $master->country_code)
            ===
            $candidate['country_code']
        &&
        strtoupper((string) $master->iso3)
            ===
            $candidate['iso3'];

    echo "  {$sourceName} : "
        . ($valid ? 'MASTER PASS' : 'MASTER CONFLICT')
        . PHP_EOL;

    echo "      ID      : {$master->id}\n";
    echo "      CODE    : {$master->country_code}\n";
    echo "      ISO3    : {$master->iso3}\n";
    echo "      NAME    : {$master->country_name_en}\n";

    if (!$valid) {
        $masterConflicts++;
    }
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Check current aliases
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "CURRENT ALIAS STATUS\n";
echo "========================================\n";

$aliasConflicts = 0;
$totalAliasRecords = 0;

foreach ($aliases as $sourceName => $candidate) {

    $existing =
        DB::table('trade_country_aliases')
            ->where(
                'source_system',
                'KEMENDAG'
            )
            ->where(
                'normalized_name',
                $sourceName
            )
            ->first();

    if ($existing === null) {

        echo "  {$sourceName} : NOT REGISTERED\n";

        continue;
    }

    if (
        (int) $existing->country_id
        !==
        $candidate['country_id']
    ) {

        echo "  {$sourceName} : CONFLICT\n";

        echo "      EXISTING COUNTRY ID : "
            . $existing->country_id
            . PHP_EOL;

        echo "      EXPECTED COUNTRY ID : "
            . $candidate['country_id']
            . PHP_EOL;

        $aliasConflicts++;

    } else {

        echo "  {$sourceName} : ALREADY REGISTERED\n";
    }
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Verify resolver target
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "RESOLVER VALIDATION\n";
echo "========================================\n";

$resolverConflicts = 0;

foreach ($aliases as $sourceName => $candidate) {

    $resolved =
        $resolver->resolve(
            $sourceName
        );

    if ($resolved === null) {

        echo "  {$sourceName} : RESOLVER CURRENTLY NULL\n";
        continue;
    }

    $valid =
        (int) $resolved->id
        ===
        $candidate['country_id'];

    echo "  {$sourceName} : "
        . ($valid ? 'RESOLVER PASS' : 'RESOLVER CONFLICT')
        . PHP_EOL;

    echo "      ID   : {$resolved->id}\n";
    echo "      CODE : {$resolved->country_code}\n";
    echo "      ISO3 : {$resolved->iso3}\n";
    echo "      NAME : {$resolved->country_name_en}\n";

    if (!$valid) {
        $resolverConflicts++;
    }
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Count unresolved records for the three source names
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "TRADE STATISTICS IMPACT\n";
echo "========================================\n";

$totalTargetRecords = 0;

foreach ($aliases as $sourceName => $candidate) {

    $count =
        DB::table('trade_statistics')
            ->whereBetween(
                'year',
                [2019, 2026]
            )
            ->whereIn(
                'trade_flow',
                [
                    'export',
                    'import',
                ]
            )
            ->whereNull('country_id')
            ->where(
                'country_name',
                $sourceName
            )
            ->count();

    $totalTargetRecords += $count;

    echo sprintf(
        "  %-30s : %7d records\n",
        $sourceName,
        $count
    );
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Expected total
|--------------------------------------------------------------------------
*/

$expectedTotal = 23679;

echo "TOTAL TARGET RECORDS:\n";
echo "  CURRENT NULL TARGET : "
    . $totalTargetRecords
    . PHP_EOL;

echo "  EXPECTED            : "
    . $expectedTotal
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Deterministic outcome
|--------------------------------------------------------------------------
*/

$allAliasesUnregistered = true;

foreach ($aliases as $sourceName => $candidate) {

    $existing =
        DB::table('trade_country_aliases')
            ->where(
                'source_system',
                'KEMENDAG'
            )
            ->where(
                'normalized_name',
                $sourceName
            )
            ->exists();

    if ($existing) {
        $allAliasesUnregistered = false;
    }
}

echo "========================================\n";
echo "PREPARE GATE\n";
echo "========================================\n";

echo "  MASTER CONFLICTS : "
    . $masterConflicts
    . PHP_EOL;

echo "  ALIAS CONFLICTS  : "
    . $aliasConflicts
    . PHP_EOL;

echo "  RESOLVER CONFLICTS : "
    . $resolverConflicts
    . PHP_EOL;

echo "  TARGET RECORDS   : "
    . $totalTargetRecords
    . PHP_EOL;

echo "  ALIAS COUNT      : "
    . count($aliases)
    . PHP_EOL;

echo PHP_EOL;

if (
    $masterConflicts === 0
    &&
    $aliasConflicts === 0
    &&
    $totalTargetRecords === $expectedTotal
) {
    echo "ALIAS PREPARATION GATE : PASS\n";
} else {
    echo "ALIAS PREPARATION GATE : REVIEW\n";
}

echo PHP_EOL;

echo "DATABASE SAFETY:\n";
echo "  INSERT : NO\n";
echo "  UPDATE : NO\n";
echo "  DELETE : NO\n";
echo "  DATABASE WAS NOT MODIFIED.\n";

echo "========================================\n";
echo "EXISTING MASTER ALIAS PREPARATION : COMPLETE\n";
echo "========================================\n";