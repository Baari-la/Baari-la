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
echo "DIGESTEX KEMENDAG COUNTRY RESOLUTION AUDIT\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  YEARS       : 2019-2026\n";
echo "  TRADE FLOWS : EXPORT + IMPORT\n";
echo "  STATUS      : READ ONLY\n\n";

$resolver =
    app(
        CountryResolverService::class
    );

/*
|--------------------------------------------------------------------------
| Unresolved country source names
|--------------------------------------------------------------------------
*/

$rows =
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
        ->whereNotNull('country_name')
        ->where(
            'country_name',
            '<>',
            ''
        )
        ->selectRaw(
            'country_name,
             trade_flow,
             COUNT(*) AS records,
             SUM(trade_value) AS trade_value,
             SUM(trade_volume) AS trade_volume'
        )
        ->groupBy(
            'country_name',
            'trade_flow'
        )
        ->orderByDesc('records')
        ->get();

/*
|--------------------------------------------------------------------------
| Aggregate same source names across flow
|--------------------------------------------------------------------------
*/

$sourceGroups = [];

foreach ($rows as $row) {

    $sourceName =
        trim(
            (string) $row->country_name
        );

    $key =
        mb_strtoupper(
            $sourceName
        );

    if (!isset($sourceGroups[$key])) {

        $sourceGroups[$key] = [
            'source_name' =>
                $sourceName,

            'records' =>
                0,

            'trade_value' =>
                0.0,

            'trade_volume' =>
                0.0,

            'flows' =>
                [],
        ];
    }

    $sourceGroups[$key]['records'] +=
        (int) $row->records;

    $sourceGroups[$key]['trade_value'] +=
        (float) $row->trade_value;

    $sourceGroups[$key]['trade_volume'] +=
        (float) $row->trade_volume;

    $sourceGroups[$key]['flows'][
        $row->trade_flow
    ] =
        (int) $row->records;
}

/*
|--------------------------------------------------------------------------
| Sort by records
|--------------------------------------------------------------------------
*/

$sourceGroups =
    array_values(
        $sourceGroups
    );

usort(
    $sourceGroups,
    static function (
        array $a,
        array $b
    ): int {
        return
            $b['records']
            <=>
            $a['records'];
    }
);

/*
|--------------------------------------------------------------------------
| Classification counters
|--------------------------------------------------------------------------
*/

$totalRecords = 0;
$totalSourceNames = count(
    $sourceGroups
);

$resolvableRecords = 0;
$unresolvableRecords = 0;

$resolvableNames = 0;
$unresolvableNames = 0;

/*
|--------------------------------------------------------------------------
| Resolve each source name
|--------------------------------------------------------------------------
*/

$results = [];

foreach ($sourceGroups as $group) {

    $country =
        $resolver->resolve(
            $group['source_name']
        );

    $isResolvable =
        $country !== null;

    if ($isResolvable) {

        $resolvableRecords +=
            $group['records'];

        $resolvableNames++;

    } else {

        $unresolvableRecords +=
            $group['records'];

        $unresolvableNames++;
    }

    $totalRecords +=
        $group['records'];

    $results[] = [
        'source_name' =>
            $group['source_name'],

        'records' =>
            $group['records'],

        'trade_value' =>
            $group['trade_value'],

        'trade_volume' =>
            $group['trade_volume'],

        'flows' =>
            $group['flows'],

        'resolved' =>
            $isResolvable,

        'country_id' =>
            $country?->id,

        'country_code' =>
            $country?->country_code,

        'iso3' =>
            $country?->iso3,

        'canonical_name' =>
            $country?->country_name_en,
    ];
}

/*
|--------------------------------------------------------------------------
| Summary
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "SUMMARY\n";
echo "========================================\n";

echo "  NULL COUNTRY RECORDS  : "
    . $totalRecords
    . PHP_EOL;

echo "  UNIQUE SOURCE NAMES   : "
    . $totalSourceNames
    . PHP_EOL;

echo "  RESOLVABLE RECORDS    : "
    . $resolvableRecords
    . PHP_EOL;

echo "  UNRESOLVABLE RECORDS  : "
    . $unresolvableRecords
    . PHP_EOL;

echo "  RESOLVABLE NAMES      : "
    . $resolvableNames
    . PHP_EOL;

echo "  UNRESOLVABLE NAMES    : "
    . $unresolvableNames
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Resolution rate
|--------------------------------------------------------------------------
*/

$resolutionRate =
    $totalRecords > 0
        ? (
            $resolvableRecords
            /
            $totalRecords
        ) * 100
        : 0.0;

echo "CURRENT NULL-COUNTRY RESOLUTION OPPORTUNITY:\n";

echo "  WOULD RESOLVE        : "
    . number_format(
        $resolutionRate,
        4
    )
    . "% of NULL records"
    . PHP_EOL;

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| RESOLVABLE SOURCE NAMES
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "ALREADY RESOLVABLE SOURCE NAMES\n";
echo "========================================\n";

foreach ($results as $item) {

    if (!$item['resolved']) {
        continue;
    }

    $flowText =
        implode(
            '+',
            array_map(
                static fn($flow) =>
                    strtoupper(
                        substr(
                            $flow,
                            0,
                            1
                        )
                    ),
                array_keys(
                    $item['flows']
                )
            )
        );

    echo sprintf(
        "  %-35s | %-3s | %7d records | ID=%d | %-3s | %-3s | %s\n",
        $item['source_name'],
        $flowText,
        $item['records'],
        $item['country_id'],
        $item['country_code'] ?? '-',
        $item['iso3'] ?? '-',
        $item['canonical_name'] ?? '-'
    );
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| UNRESOLVED SOURCE NAMES
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "UNRESOLVED SOURCE NAMES\n";
echo "========================================\n";

foreach ($results as $item) {

    if ($item['resolved']) {
        continue;
    }

    $flowText =
        implode(
            '+',
            array_map(
                static fn($flow) =>
                    strtoupper(
                        substr(
                            $flow,
                            0,
                            1
                        )
                    ),
                array_keys(
                    $item['flows']
                )
            )
        );

    echo sprintf(
        "  %-40s | %-3s | %7d records | VALUE=%14.3f | VOLUME=%14.3f\n",
        $item['source_name'],
        $flowText,
        $item['records'],
        $item['trade_value'],
        $item['trade_volume']
    );
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Country master status for unresolved names
|--------------------------------------------------------------------------
|
| The purpose is to distinguish:
| A. Alias gap
| B. Master gap
| C. Possible territory/dependency/special nomenclature
|
*/

echo "========================================\n";
echo "UNRESOLVED SOURCE NAME CLASSIFICATION\n";
echo "========================================\n";

$masterMatchCount = 0;

foreach ($results as $item) {

    if ($item['resolved']) {
        continue;
    }

    $source =
        $item['source_name'];

    $normalized =
        preg_replace(
            '/\s+/',
            ' ',
            mb_strtoupper(
                trim($source)
            )
        );

    $masterMatches =
        DB::table('mst_countries')
            ->select(
                'id',
                'country_code',
                'iso3',
                'country_name_en',
                'country_name_id'
            )
            ->where(function ($q) use (
                $normalized
            ) {

                $q->whereRaw(
                    'UPPER(country_name_en) = ?',
                    [$normalized]
                )
                ->orWhereRaw(
                    'UPPER(country_name_id) = ?',
                    [$normalized]
                );
            })
            ->get();

    $classification =
        $masterMatches->isNotEmpty()
            ? 'MASTER EXISTS / ALIAS GAP'
            : 'MASTER GAP OR SPECIAL';

    if (
        $masterMatches->isNotEmpty()
    ) {
        $masterMatchCount++;
    }

    $masterText =
        $masterMatches->isNotEmpty()
            ? $masterMatches
                ->map(
                    static function ($match) {
                        return
                            $match->country_name_en
                            . ' ['
                            . $match->country_code
                            . '/'
                            . ($match->iso3 ?? '-')
                            . ']';
                    }
                )
                ->implode('; ')
            : '-';

    echo sprintf(
        "  %-40s | %-24s | %7d records | %s\n",
        $source,
        $classification,
        $item['records'],
        $masterText
    );
}

echo PHP_EOL;

echo "========================================\n";
echo "DATABASE SAFETY\n";
echo "========================================\n";

echo "  INSERT : NO\n";
echo "  UPDATE : NO\n";
echo "  DELETE : NO\n";
echo "  DATABASE WAS NOT MODIFIED.\n";

echo "========================================\n";
echo "COUNTRY RESOLUTION AUDIT : COMPLETE\n";
echo "========================================\n";