<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(
    \Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Configuration
|--------------------------------------------------------------------------
*/

const BASE_YEAR = 2025;
const CURRENT_YEAR = 2026;

const START_MONTH = 1;
const END_MONTH = 6;

const TOP_N = 10;

const TRADE_FLOWS = [
    'export',
    'import',
];

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function fmtNumber(float|int|string|null $value, int $decimals = 3): string
{
    return number_format(
        (float) ($value ?? 0),
        $decimals,
        '.',
        ''
    );
}

function fmtPercent(float|int|null $value, int $decimals = 2): string
{
    return number_format(
        (float) ($value ?? 0),
        $decimals,
        '.',
        ''
    ) . '%';
}

function yoyGrowth(
    float $base,
    float $current
): ?float {

    if (abs($base) < 0.000000001) {
        return null;
    }

    return (($current - $base) / $base) * 100;
}

function growthLabel(
    ?float $growth
): string {

    if ($growth === null) {
        return 'N/A';
    }

    if ($growth > 0) {
        return '+' . fmtPercent($growth);
    }

    if ($growth < 0) {
        return fmtPercent($growth);
    }

    return '0.00%';
}

/*
|--------------------------------------------------------------------------
| Header
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "DIGESTEX KEMENDAG LAUNCH INTELLIGENCE YOY\n";
echo "2025 H1 vs 2026 H1\n";
echo "========================================\n\n";

echo "SCOPE:\n";
echo "  BASE YEAR     : 2025\n";
echo "  CURRENT YEAR  : 2026\n";
echo "  PERIOD        : JANUARY-JUNE\n";
echo "  TRADE FLOWS   : EXPORT + IMPORT\n";
echo "  OPERATION     : READ ONLY\n";
echo "  PURPOSE       : COATS LAUNCH INTELLIGENCE BASELINE\n\n";

/*
|--------------------------------------------------------------------------
| Global baseline
|--------------------------------------------------------------------------
*/

$baseline = [];

foreach (TRADE_FLOWS as $flow) {

    $base = DB::table('trade_statistics')
        ->where('year', BASE_YEAR)
        ->where('month', '>=', START_MONTH)
        ->where('month', '<=', END_MONTH)
        ->where('trade_flow', $flow)
        ->selectRaw('COUNT(*) AS records')
        ->selectRaw('COUNT(DISTINCT trade_identity) AS identities')
        ->selectRaw('SUM(trade_value) AS trade_value')
        ->selectRaw('SUM(trade_volume) AS trade_volume')
        ->first();

    $current = DB::table('trade_statistics')
        ->where('year', CURRENT_YEAR)
        ->where('month', '>=', START_MONTH)
        ->where('month', '<=', END_MONTH)
        ->where('trade_flow', $flow)
        ->selectRaw('COUNT(*) AS records')
        ->selectRaw('COUNT(DISTINCT trade_identity) AS identities')
        ->selectRaw('SUM(trade_value) AS trade_value')
        ->selectRaw('SUM(trade_volume) AS trade_volume')
        ->first();

    $baseline[$flow] = [
        'base' => $base,
        'current' => $current,
    ];
}

echo "========================================\n";
echo "H1 TRADE BASELINE\n";
echo "========================================\n";

foreach (TRADE_FLOWS as $flow) {

    $base = $baseline[$flow]['base'];
    $current = $baseline[$flow]['current'];

    $valueGrowth = yoyGrowth(
        (float) $base->trade_value,
        (float) $current->trade_value
    );

    $volumeGrowth = yoyGrowth(
        (float) $base->trade_volume,
        (float) $current->trade_volume
    );

    $recordGrowth = yoyGrowth(
        (float) $base->records,
        (float) $current->records
    );

    echo "\n";
    echo strtoupper($flow) . "\n";
    echo "  ----------------------------------------\n";

    echo "  2025 H1 RECORDS     : "
        . $base->records
        . PHP_EOL;

    echo "  2026 H1 RECORDS     : "
        . $current->records
        . PHP_EOL;

    echo "  RECORD YOY          : "
        . growthLabel($recordGrowth)
        . PHP_EOL;

    echo "  2025 H1 VALUE       : "
        . fmtNumber($base->trade_value, 6)
        . PHP_EOL;

    echo "  2026 H1 VALUE       : "
        . fmtNumber($current->trade_value, 6)
        . PHP_EOL;

    echo "  VALUE YOY           : "
        . growthLabel($valueGrowth)
        . PHP_EOL;

    echo "  2025 H1 VOLUME      : "
        . fmtNumber($base->trade_volume, 6)
        . PHP_EOL;

    echo "  2026 H1 VOLUME      : "
        . fmtNumber($current->trade_volume, 6)
        . PHP_EOL;

    echo "  VOLUME YOY          : "
        . growthLabel($volumeGrowth)
        . PHP_EOL;

    echo "  2025 IDENTITIES     : "
        . $base->identities
        . PHP_EOL;

    echo "  2026 IDENTITIES     : "
        . $current->identities
        . PHP_EOL;
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Monthly coverage
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "MONTHLY COVERAGE\n";
echo "========================================\n";

foreach (TRADE_FLOWS as $flow) {

    foreach ([BASE_YEAR, CURRENT_YEAR] as $year) {

        $months = DB::table('trade_statistics')
            ->where('year', $year)
            ->where('month', '>=', START_MONTH)
            ->where('month', '<=', END_MONTH)
            ->where('trade_flow', $flow)
            ->distinct()
            ->orderBy('month')
            ->pluck('month')
            ->map(fn ($month) => (int) $month)
            ->values();

        $expectedMonths = range(
            START_MONTH,
            END_MONTH
        );

        $pass =
            $months->all()
            ===
            $expectedMonths;

        echo sprintf(
            "  %d %-6s : %s (%d months)\n",
            $year,
            strtoupper($flow),
            $pass ? 'PASS' : 'REVIEW',
            $months->count()
        );
    }
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Country ranking
|--------------------------------------------------------------------------
|
| IMPORTANT:
| Only rows with country_id are used as foreign-country rankings.
| Special source labels with country_id NULL are kept separate.
|--------------------------------------------------------------------------
*/

function countryRanking(
    string $flow,
    int $year
) {

    return DB::table('trade_statistics as ts')
        ->leftJoin(
            'mst_countries as mc',
            'mc.id',
            '=',
            'ts.country_id'
        )
        ->where('ts.year', $year)
        ->where('ts.month', '>=', START_MONTH)
        ->where('ts.month', '<=', END_MONTH)
        ->where('ts.trade_flow', $flow)
        ->whereNotNull('ts.country_id')
        ->where('mc.is_active', 1)
        ->select(
            'ts.country_id',
            'mc.country_code',
            'mc.iso3',
            'mc.country_name_en',
            'mc.country_name_id'
        )
        ->selectRaw(
            'COUNT(*) AS records'
        )
        ->selectRaw(
            'SUM(ts.trade_value) AS trade_value'
        )
        ->selectRaw(
            'SUM(ts.trade_volume) AS trade_volume'
        )
        ->groupBy(
            'ts.country_id',
            'mc.country_code',
            'mc.iso3',
            'mc.country_name_en',
            'mc.country_name_id'
        )
        ->orderByDesc('trade_value')
        ->limit(TOP_N)
        ->get();
}

/*
|--------------------------------------------------------------------------
| Country comparison
|--------------------------------------------------------------------------
*/

function compareCountries(
    string $flow
): array {

    $baseRows = countryRanking(
        $flow,
        BASE_YEAR
    );

    $currentRows = countryRanking(
        $flow,
        CURRENT_YEAR
    );

    $baseMap = [];

    foreach ($baseRows as $row) {

        $baseMap[(int) $row->country_id] = $row;
    }

    $currentMap = [];

    foreach ($currentRows as $row) {

        $currentMap[(int) $row->country_id] = $row;
    }

    $allIds = array_unique(
        array_merge(
            array_keys($baseMap),
            array_keys($currentMap)
        )
    );

    $items = [];

    foreach ($allIds as $countryId) {

        $base = $baseMap[$countryId] ?? null;
        $current = $currentMap[$countryId] ?? null;

        $name =
            $current?->country_name_en
            ??
            $base?->country_name_en
            ??
            'Unknown';

        $value2025 =
            (float) (
                $base?->trade_value
                ?? 0
            );

        $value2026 =
            (float) (
                $current?->trade_value
                ?? 0
            );

        $volume2025 =
            (float) (
                $base?->trade_volume
                ?? 0
            );

        $volume2026 =
            (float) (
                $current?->trade_volume
                ?? 0
            );

        $items[] = [
            'country_id' => (int) $countryId,
            'country_name' => $name,
            'country_code' =>
                $current?->country_code
                ??
                $base?->country_code,
            'iso3' =>
                $current?->iso3
                ??
                $base?->iso3,
            'records_2025' =>
                (int) (
                    $base?->records
                    ?? 0
                ),
            'records_2026' =>
                (int) (
                    $current?->records
                    ?? 0
                ),
            'value_2025' => $value2025,
            'value_2026' => $value2026,
            'volume_2025' => $volume2025,
            'volume_2026' => $volume2026,
            'value_growth' =>
                yoyGrowth(
                    $value2025,
                    $value2026
                ),
            'volume_growth' =>
                yoyGrowth(
                    $volume2025,
                    $volume2026
                ),
        ];
    }

    usort(
        $items,
        static function (
            array $a,
            array $b
        ): int {

            return
                $b['value_2026']
                <=>
                $a['value_2026'];
        }
    );

    return array_slice(
        $items,
        0,
        TOP_N
    );
}

/*
|--------------------------------------------------------------------------
| Country destination/origin sections
|--------------------------------------------------------------------------
*/

foreach ([
    'export' => 'MAJOR EXPORT DESTINATIONS',
    'import' => 'MAJOR IMPORT ORIGINS',
] as $flow => $title) {

    echo "========================================\n";
    echo $title . "\n";
    echo "2025 H1 vs 2026 H1\n";
    echo "========================================\n";

    $items = compareCountries($flow);

    if (empty($items)) {

        echo "  NO RESOLVED COUNTRY DATA\n\n";
        continue;
    }

    echo sprintf(
        "  %-2s %-28s %16s %16s %12s %12s\n",
        '#',
        'Country',
        '2025 VALUE',
        '2026 VALUE',
        'YOY',
        '2026 SHARE'
    );

    $totalCurrentValue =
        (float) (
            $baseline[$flow]['current']->trade_value
            ?? 0
        );

    foreach ($items as $index => $item) {

        $share =
            $totalCurrentValue > 0
                ? (
                    $item['value_2026']
                    /
                    $totalCurrentValue
                    * 100
                )
                : null;

        echo sprintf(
            "  %-2d %-28s %16s %16s %12s %12s\n",
            $index + 1,
            mb_strimwidth(
                $item['country_name'],
                0,
                28,
                ''
            ),
            fmtNumber(
                $item['value_2025'],
                3
            ),
            fmtNumber(
                $item['value_2026'],
                3
            ),
            growthLabel(
                $item['value_growth']
            ),
            $share === null
                ? 'N/A'
                : fmtPercent($share)
        );
    }

    echo PHP_EOL;
}

/*
|--------------------------------------------------------------------------
| Special country source labels
|--------------------------------------------------------------------------
|
| These are NOT included in country ranking.
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "SPECIAL / UNRESOLVED COUNTRY LABELS\n";
echo "========================================\n";

$specialLabels = DB::table('trade_statistics')
    ->whereIn('year', [BASE_YEAR, CURRENT_YEAR])
    ->where('month', '>=', START_MONTH)
    ->where('month', '<=', END_MONTH)
    ->whereIn('trade_flow', TRADE_FLOWS)
    ->whereNull('country_id')
    ->whereNotNull('country_name')
    ->select(
        'trade_flow',
        'country_name'
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
        'trade_flow',
        'country_name'
    )
    ->orderByDesc('trade_value')
    ->limit(20)
    ->get();

foreach ($specialLabels as $row) {

    echo sprintf(
        "  %-7s | %-30s | %6d records | VALUE=%15s | VOLUME=%15s\n",
        strtoupper($row->trade_flow),
        mb_strimwidth(
            $row->country_name,
            0,
            30,
            ''
        ),
        $row->records,
        fmtNumber(
            $row->trade_value,
            3
        ),
        fmtNumber(
            $row->trade_volume,
            3
        )
    );
}

echo PHP_EOL;

/*
|--------------------------------------------------------------------------
| Product ranking
|--------------------------------------------------------------------------
|
| Product intelligence uses HS data from mst_hscode.
|--------------------------------------------------------------------------
*/

function productRanking(
    string $flow,
    int $year
) {

    return DB::table('trade_statistics as ts')
        ->leftJoin(
            'mst_hscode as hs',
            'hs.id_hs',
            '=',
            'ts.hs_id'
        )
        ->where('ts.year', $year)
        ->where('ts.month', '>=', START_MONTH)
        ->where('ts.month', '<=', END_MONTH)
        ->where('ts.trade_flow', $flow)
        ->whereNotNull('ts.hs_id')
        ->select(
            'ts.hs_id',
            'ts.hs_code'
        )
        ->selectRaw(
            'COALESCE(
                hs.uraian_hs_en,
                hs.uraian_hs_id,
                ts.hs_code
            ) AS product_name'
        )
        ->selectRaw(
            'COUNT(*) AS records'
        )
        ->selectRaw(
            'SUM(ts.trade_value) AS trade_value'
        )
        ->selectRaw(
            'SUM(ts.trade_volume) AS trade_volume'
        )
        ->groupBy(
            'ts.hs_id',
            'ts.hs_code',
            'hs.uraian_hs_en',
            'hs.uraian_hs_id'
        )
        ->orderByDesc('trade_value')
        ->limit(TOP_N)
        ->get();
}

/*
|--------------------------------------------------------------------------
| Product comparison
|--------------------------------------------------------------------------
*/

function compareProducts(
    string $flow
): array {

    $baseRows = productRanking(
        $flow,
        BASE_YEAR
    );

    $currentRows = productRanking(
        $flow,
        CURRENT_YEAR
    );

    $baseMap = [];

    foreach ($baseRows as $row) {

        $baseMap[(int) $row->hs_id] = $row;
    }

    $currentMap = [];

    foreach ($currentRows as $row) {

        $currentMap[(int) $row->hs_id] = $row;
    }

    $allIds = array_unique(
        array_merge(
            array_keys($baseMap),
            array_keys($currentMap)
        )
    );

    $items = [];

    foreach ($allIds as $hsId) {

        $base = $baseMap[$hsId] ?? null;
        $current = $currentMap[$hsId] ?? null;

        $name =
            $current?->product_name
            ??
            $base?->product_name
            ??
            'Unknown Product';

        $hsCode =
            $current?->hs_code
            ??
            $base?->hs_code
            ??
            null;

        $value2025 =
            (float) (
                $base?->trade_value
                ?? 0
            );

        $value2026 =
            (float) (
                $current?->trade_value
                ?? 0
            );

        $volume2025 =
            (float) (
                $base?->trade_volume
                ?? 0
            );

        $volume2026 =
            (float) (
                $current?->trade_volume
                ?? 0
            );

        $items[] = [
            'hs_id' => (int) $hsId,
            'hs_code' => $hsCode,
            'product_name' => $name,
            'records_2025' =>
                (int) (
                    $base?->records
                    ?? 0
                ),
            'records_2026' =>
                (int) (
                    $current?->records
                    ?? 0
                ),
            'value_2025' => $value2025,
            'value_2026' => $value2026,
            'volume_2025' => $volume2025,
            'volume_2026' => $volume2026,
            'value_growth' =>
                yoyGrowth(
                    $value2025,
                    $value2026
                ),
            'volume_growth' =>
                yoyGrowth(
                    $volume2025,
                    $volume2026
                ),
        ];
    }

    usort(
        $items,
        static function (
            array $a,
            array $b
        ): int {

            return
                $b['value_2026']
                <=>
                $a['value_2026'];
        }
    );

    return array_slice(
        $items,
        0,
        TOP_N
    );
}

/*
|--------------------------------------------------------------------------
| Product sections
|--------------------------------------------------------------------------
*/

foreach ([
    'export' => 'MAJOR EXPORT PRODUCTS',
    'import' => 'MAJOR IMPORT PRODUCTS',
] as $flow => $title) {

    echo "========================================\n";
    echo $title . "\n";
    echo "2025 H1 vs 2026 H1\n";
    echo "========================================\n";

    $items = compareProducts($flow);

    echo sprintf(
        "  %-2s %-10s %-32s %16s %16s %12s %12s\n",
        '#',
        'HS',
        'Product',
        '2025 VALUE',
        '2026 VALUE',
        'YOY',
        '2026 SHARE'
    );

    $totalCurrentValue =
        (float) (
            $baseline[$flow]['current']->trade_value
            ?? 0
        );

    foreach ($items as $index => $item) {

        $share =
            $totalCurrentValue > 0
                ? (
                    $item['value_2026']
                    /
                    $totalCurrentValue
                    * 100
                )
                : null;

        echo sprintf(
            "  %-2d %-10s %-32s %16s %16s %12s %12s\n",
            $index + 1,
            mb_strimwidth(
                (string) $item['hs_code'],
                0,
                10,
                ''
            ),
            mb_strimwidth(
                $item['product_name'],
                0,
                32,
                ''
            ),
            fmtNumber(
                $item['value_2025'],
                3
            ),
            fmtNumber(
                $item['value_2026'],
                3
            ),
            growthLabel(
                $item['value_growth']
            ),
            $share === null
                ? 'N/A'
                : fmtPercent($share)
        );
    }

    echo PHP_EOL;
}

/*
|--------------------------------------------------------------------------
| Top movers
|--------------------------------------------------------------------------
|
| This section is intended for "What changed in 2026?"
|--------------------------------------------------------------------------
*/

function topMovers(
    string $flow,
    string $dimension
): array {

    if ($dimension === 'country') {

        $base = countryRanking(
            $flow,
            BASE_YEAR
        );

        $current = countryRanking(
            $flow,
            CURRENT_YEAR
        );

        $baseMap = [];

        foreach ($base as $row) {
            $baseMap[(int) $row->country_id] = $row;
        }

        $currentMap = [];

        foreach ($current as $row) {
            $currentMap[(int) $row->country_id] = $row;
        }

        $ids = array_unique(
            array_merge(
                array_keys($baseMap),
                array_keys($currentMap)
            )
        );

        $items = [];

        foreach ($ids as $id) {

            $b = $baseMap[$id] ?? null;
            $c = $currentMap[$id] ?? null;

            $v2025 =
                (float) (
                    $b?->trade_value
                    ?? 0
                );

            $v2026 =
                (float) (
                    $c?->trade_value
                    ?? 0
                );

            $growth =
                yoyGrowth(
                    $v2025,
                    $v2026
                );

            if ($growth === null) {
                continue;
            }

            $items[] = [
                'label' =>
                    $c?->country_name_en
                    ??
                    $b?->country_name_en
                    ??
                    'Unknown',
                'growth' => $growth,
                'value_2025' => $v2025,
                'value_2026' => $v2026,
            ];
        }

    } else {

        $base = productRanking(
            $flow,
            BASE_YEAR
        );

        $current = productRanking(
            $flow,
            CURRENT_YEAR
        );

        $baseMap = [];

        foreach ($base as $row) {
            $baseMap[(int) $row->hs_id] = $row;
        }

        $currentMap = [];

        foreach ($current as $row) {
            $currentMap[(int) $row->hs_id] = $row;
        }

        $ids = array_unique(
            array_merge(
                array_keys($baseMap),
                array_keys($currentMap)
            )
        );

        $items = [];

        foreach ($ids as $id) {

            $b = $baseMap[$id] ?? null;
            $c = $currentMap[$id] ?? null;

            $v2025 =
                (float) (
                    $b?->trade_value
                    ?? 0
                );

            $v2026 =
                (float) (
                    $c?->trade_value
                    ?? 0
                );

            $growth =
                yoyGrowth(
                    $v2025,
                    $v2026
                );

            if ($growth === null) {
                continue;
            }

            $items[] = [
                'label' =>
                    $c?->product_name
                    ??
                    $b?->product_name
                    ??
                    'Unknown',
                'growth' => $growth,
                'value_2025' => $v2025,
                'value_2026' => $v2026,
            ];
        }
    }

    usort(
        $items,
        static function (
            array $a,
            array $b
        ): int {

            return
                $b['growth']
                <=>
                $a['growth'];
        }
    );

    return array_slice(
        $items,
        0,
        5
    );
}

/*
|--------------------------------------------------------------------------
| 2026 H1 highlights
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "2026 H1 HIGHLIGHTS\n";
echo "========================================\n";

foreach (TRADE_FLOWS as $flow) {

    echo strtoupper($flow) . "\n";
    echo "  ------------------------------\n";

    $topCountryMovers =
        topMovers(
            $flow,
            'country'
        );

    $topProductMovers =
        topMovers(
            $flow,
            'product'
        );

    echo "  TOP DESTINATION / ORIGIN GROWTH:\n";

    foreach (
        array_slice(
            $topCountryMovers,
            0,
            5
        ) as $index => $item
    ) {

        echo sprintf(
            "    %d. %-28s %s\n",
            $index + 1,
            mb_strimwidth(
                $item['label'],
                0,
                28,
                ''
            ),
            growthLabel(
                $item['growth']
            )
        );
    }

    echo "  TOP PRODUCT GROWTH:\n";

    foreach (
        array_slice(
            $topProductMovers,
            0,
            5
        ) as $index => $item
    ) {

        echo sprintf(
            "    %d. %-35s %s\n",
            $index + 1,
            mb_strimwidth(
                $item['label'],
                0,
                35,
                ''
            ),
            growthLabel(
                $item['growth']
            )
        );
    }

    echo PHP_EOL;
}

/*
|--------------------------------------------------------------------------
| Data quality checks for launch
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "LAUNCH DATA QUALITY CHECK\n";
echo "========================================\n";

$qualityPass = true;

foreach (TRADE_FLOWS as $flow) {

    foreach ([BASE_YEAR, CURRENT_YEAR] as $year) {

        $check = DB::table('trade_statistics')
            ->where('year', $year)
            ->where('month', '>=', START_MONTH)
            ->where('month', '<=', END_MONTH)
            ->where('trade_flow', $flow)
            ->selectRaw('COUNT(*) AS records')
            ->selectRaw(
                'COUNT(DISTINCT trade_identity) AS identities'
            )
            ->selectRaw(
                'SUM(CASE WHEN hs_code IS NULL THEN 1 ELSE 0 END) AS null_hs'
            )
            ->selectRaw(
                'SUM(CASE WHEN province_id IS NULL THEN 1 ELSE 0 END) AS null_province'
            )
            ->first();

        $pass =
            (int) $check->records
            ===
            (int) $check->identities
            &&
            (int) $check->null_hs === 0
            &&
            (int) $check->null_province === 0;

        if (!$pass) {
            $qualityPass = false;
        }

        echo sprintf(
            "  %d %-6s : %s | records=%d | identities=%d | null_hs=%d | null_province=%d\n",
            $year,
            strtoupper($flow),
            $pass ? 'PASS' : 'REVIEW',
            $check->records,
            $check->identities,
            $check->null_hs,
            $check->null_province
        );
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
echo "  DATABASE WAS NOT MODIFIED.\n\n";

/*
|--------------------------------------------------------------------------
| Final launch gate
|--------------------------------------------------------------------------
*/

echo "========================================\n";
echo "FINAL LAUNCH INTELLIGENCE GATE\n";
echo "========================================\n";

echo "  H1 PERIOD          : 2025 Jan-Jun vs 2026 Jan-Jun\n";
echo "  QUALITY CHECK      : "
    . ($qualityPass ? 'PASS' : 'REVIEW')
    . PHP_EOL;

echo "  DATA MODE          : READ ONLY\n";

if ($qualityPass) {

    echo "  LAUNCH BASELINE    : PASS\n";

} else {

    echo "  LAUNCH BASELINE    : REVIEW\n";
}

echo "========================================\n";
echo "LAUNCH INTELLIGENCE YOY AUDIT : COMPLETE\n";
echo "========================================\n";

if (!$qualityPass) {
    exit(1);
}