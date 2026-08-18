<?php

declare(strict_types=1);

namespace App\Services\Trade;

use App\Services\Trade\TradeIntelligenceSnapshotService;
use App\Services\Support\CacheService;
use Illuminate\Support\Facades\DB;

class LaunchTradeIntelligenceService
{
    protected const CACHE_KEY = 'digestex.trade.launch-intelligence';

    protected int $ttl = 1800; // 30 minutes

    protected int $currentYear = 2026;

    protected int $baseYear = 2025;

    protected array $h1Months = [1, 2, 3, 4, 5, 6];

    protected int $topLimit = 10;

    /**
     * --------------------------------------------------------------------------
     * Public read method
     * --------------------------------------------------------------------------
     *
     * IMPORTANT:
     * User request never builds the snapshot.
     */
public function __construct(
    protected TradeIntelligenceSnapshotService $snapshotService
) {
}
    
    public function getData(): array
{
    $snapshot = $this->snapshotService->get(
        self::CACHE_KEY
    );

    if (is_array($snapshot)) {
        return $snapshot;
    }

    return $this->emptySnapshot();
}

    /**
     * --------------------------------------------------------------------------
     * Build snapshot
     * --------------------------------------------------------------------------
     *
     * Intended for CLI / scheduler.
     */
    public function build(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Launch Period
            |--------------------------------------------------------------------------
            */

            'period' => [
                'base_year' => $this->baseYear,
                'current_year' => $this->currentYear,
                'months' => $this->h1Months,
                'label' =>
                    'H1 '
                    . $this->currentYear
                    . ' vs H1 '
                    . $this->baseYear,
            ],

            /*
            |--------------------------------------------------------------------------
            | Executive Summary
            |--------------------------------------------------------------------------
            */

            'summary' => $this->buildSummary(),

            /*
            |--------------------------------------------------------------------------
            | Major Export Destinations
            |--------------------------------------------------------------------------
            */

            'topExportDestinations' =>
                $this->topCountries('export'),

            /*
            |--------------------------------------------------------------------------
            | Major Import Origins
            |--------------------------------------------------------------------------
            */

            'topImportOrigins' =>
                $this->topCountries('import'),

            /*
            |--------------------------------------------------------------------------
            | Major Export Products
            |--------------------------------------------------------------------------
            */

            'topExportProducts' =>
                $this->topProducts('export'),

            /*
            |--------------------------------------------------------------------------
            | Major Import Products
            |--------------------------------------------------------------------------
            */

            'topImportProducts' =>
                $this->topProducts('import'),

            /*
            |--------------------------------------------------------------------------
            | Annual Trend
            |--------------------------------------------------------------------------
            */

            'yearlyTrends' =>
                $this->yearlyTrends(),

            /*
            |--------------------------------------------------------------------------
            | Trade Categories
            |--------------------------------------------------------------------------
            */

            'topTrade' =>
                $this->tradeCategories(),

            /*
            |--------------------------------------------------------------------------
            | Generated
            |--------------------------------------------------------------------------
            */

            'generated_at' => now()->toDateTimeString(),
        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Save snapshot to cache
     * --------------------------------------------------------------------------
     */
    public function refresh(): array
{
    $snapshot = $this->build();

    $this->snapshotService->save(
        self::CACHE_KEY,
        $snapshot,
        [
            'snapshot_type' => 'trade_launch',
            'sector' => 'all',
            'period_key' => 'H1-2026-vs-H1-2025',
        ]
    );

    return $snapshot;
}

    /**
     * --------------------------------------------------------------------------
     * Remove snapshot
     * --------------------------------------------------------------------------
     */
    public function forget(): void
    {
        CacheService::forget(self::CACHE_KEY);
    }

    /**
     * --------------------------------------------------------------------------
     * Cache status
     * --------------------------------------------------------------------------
     */
    public function isCached(): bool
    {
        return CacheService::has(self::CACHE_KEY);
    }

    /**
     * --------------------------------------------------------------------------
     * Executive Summary
     * --------------------------------------------------------------------------
     */
    protected function buildSummary(): array
    {
        $rows = DB::table('trade_statistics')
            ->whereIn('trade_flow', ['export', 'import'])
            ->whereIn('year', [
                $this->baseYear,
                $this->currentYear,
            ])
            ->whereIn('month', $this->h1Months)
            ->select('year', 'trade_flow')
            ->selectRaw(
                'SUM(trade_value) AS trade_value'
            )
            ->selectRaw(
                'SUM(trade_volume) AS trade_volume'
            )
            ->groupBy('year', 'trade_flow')
            ->get();

        $summary = [
            'export' => [
                'value_2025' => 0,
                'value_2026' => 0,
                'volume_2025' => 0,
                'volume_2026' => 0,
                'growth' => null,
                'volume_growth' => null,
            ],

            'import' => [
                'value_2025' => 0,
                'value_2026' => 0,
                'volume_2025' => 0,
                'volume_2026' => 0,
                'growth' => null,
                'volume_growth' => null,
            ],
        ];

        foreach ($rows as $row) {

            $flow = $row->trade_flow;
            $year = (int) $row->year;

            if (!isset($summary[$flow])) {
                continue;
            }

            if ($year === $this->baseYear) {

                $summary[$flow]['value_2025'] =
                    (float) $row->trade_value;

                $summary[$flow]['volume_2025'] =
                    (float) $row->trade_volume;

            } elseif ($year === $this->currentYear) {

                $summary[$flow]['value_2026'] =
                    (float) $row->trade_value;

                $summary[$flow]['volume_2026'] =
                    (float) $row->trade_volume;
            }
        }

        foreach (['export', 'import'] as $flow) {

            $summary[$flow]['growth'] =
                $this->growth(
                    $summary[$flow]['value_2025'],
                    $summary[$flow]['value_2026']
                );

            $summary[$flow]['volume_growth'] =
                $this->growth(
                    $summary[$flow]['volume_2025'],
                    $summary[$flow]['volume_2026']
                );
        }

        return $summary;
    }

    /**
     * --------------------------------------------------------------------------
     * Top Countries
     * --------------------------------------------------------------------------
     *
     * Export:
     *   Major Export Destinations
     *
     * Import:
     *   Major Import Origins
     *
     * Only resolved country_id records are included.
     */
    protected function topCountries(string $tradeFlow): array
    {
        $current = DB::table('trade_statistics as ts')
            ->leftJoin(
                'mst_countries as mc',
                'mc.id',
                '=',
                'ts.country_id'
            )
            ->where(
                'ts.trade_flow',
                $tradeFlow
            )
            ->where(
                'ts.year',
                $this->currentYear
            )
            ->whereIn(
                'ts.month',
                $this->h1Months
            )
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
                'SUM(ts.trade_value) AS current_value'
            )
            ->selectRaw(
                'SUM(ts.trade_volume) AS current_volume'
            )
            ->groupBy(
                'ts.country_id',
                'mc.country_code',
                'mc.iso3',
                'mc.country_name_en',
                'mc.country_name_id'
            )
            ->orderByDesc('current_value')
            ->limit($this->topLimit)
            ->get();

        $totalCurrent = DB::table('trade_statistics')
            ->where(
                'trade_flow',
                $tradeFlow
            )
            ->where(
                'year',
                $this->currentYear
            )
            ->whereIn(
                'month',
                $this->h1Months
            )
            ->sum('trade_value');

        return $current
            ->values()
            ->map(
                function ($row, int $index) use ($totalCurrent): array {

                    return [
                        'rank' => $index + 1,

                        'country_id' =>
                            (int) $row->country_id,

                        'country_code' =>
                            $row->country_code,

                        'iso3' =>
                            $row->iso3,

                        'country_name_en' =>
                            $row->country_name_en,

                        'country_name_id' =>
                            $row->country_name_id,

                        'trade_value' =>
                            (float) $row->current_value,

                        'trade_volume' =>
                            (float) $row->current_volume,

                        'share' =>
                            $totalCurrent > 0
                                ? round(
                                    (
                                        $row->current_value
                                        / $totalCurrent
                                    ) * 100,
                                    2
                                )
                                : 0,
                    ];
                }
            )
            ->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Top Products
     * --------------------------------------------------------------------------
     */
    protected function topProducts(string $tradeFlow): array
    {
        $totalCurrent = DB::table('trade_statistics')
            ->where(
                'trade_flow',
                $tradeFlow
            )
            ->where(
                'year',
                $this->currentYear
            )
            ->whereIn(
                'month',
                $this->h1Months
            )
            ->sum('trade_value');

        return DB::table('trade_statistics')
            ->where(
                'trade_flow',
                $tradeFlow
            )
            ->where(
                'year',
                $this->currentYear
            )
            ->whereIn(
                'month',
                $this->h1Months
            )
            ->whereNotNull('hs_code')
            ->select(
                'hs_code',
                'hs_description'
            )
            ->selectRaw(
                'SUM(trade_value) AS trade_value'
            )
            ->selectRaw(
                'SUM(trade_volume) AS trade_volume'
            )
            ->groupBy(
                'hs_code',
                'hs_description'
            )
            ->orderByDesc('trade_value')
            ->limit($this->topLimit)
            ->get()
            ->values()
            ->map(
                function ($row, int $index) use ($totalCurrent): array {

                    return [
                        'rank' => $index + 1,

                        'hs_code' =>
                            $row->hs_code,

                        'product' =>
                            $row->hs_description,

                        'trade_value' =>
                            (float) $row->trade_value,

                        'trade_volume' =>
                            (float) $row->trade_volume,

                        'share' =>
                            $totalCurrent > 0
                                ? round(
                                    (
                                        $row->trade_value
                                        / $totalCurrent
                                    ) * 100,
                                    2
                                )
                                : 0,
                    ];
                }
            )
            ->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Annual Trend
     * --------------------------------------------------------------------------
     *
     * Canonical historical data from trade_statistics.
     */
    protected function yearlyTrends(): array
    {
        return DB::table('trade_statistics')
            ->whereIn(
                'year',
                [2021, 2022, 2023, 2024, 2025]
            )
            ->whereIn(
                'trade_flow',
                ['export', 'import']
            )
            ->select(
                'year',
                'trade_flow'
            )
            ->selectRaw(
                'SUM(trade_value) AS trade_value'
            )
            ->selectRaw(
                'SUM(trade_volume) AS trade_volume'
            )
            ->groupBy(
                'year',
                'trade_flow'
            )
            ->orderBy('year')
            ->get()
            ->map(
                fn ($row): array => [
                    'year' =>
                        (int) $row->year,

                    'trade_flow' =>
                        $row->trade_flow,

                    'trade_value' =>
                        (float) $row->trade_value,

                    'trade_volume' =>
                        (float) $row->trade_volume,
                ]
            )
            ->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Trade Categories
     * --------------------------------------------------------------------------
     *
     * Uses HS chapter mapping directly from canonical records.
     */
    protected function tradeCategories(): array
    {
        return DB::table('trade_statistics')
            ->where(
                'year',
                $this->currentYear
            )
            ->whereIn(
                'month',
                $this->h1Months
            )
            ->whereIn(
                'trade_flow',
                ['export', 'import']
            )
            ->selectRaw("
                trade_flow,
                CASE
                    WHEN hs_code LIKE '50%' THEN 'Fiber / Silk'
                    WHEN hs_code LIKE '51%' THEN 'Wool / Fine Animal Hair'
                    WHEN hs_code LIKE '52%' THEN 'Cotton'
                    WHEN hs_code LIKE '54%' THEN 'Man-Made Filament'
                    WHEN hs_code LIKE '55%' THEN 'Synthetic Staple Fiber'
                    WHEN hs_code LIKE '56%' THEN 'Wadding / Nonwovens'
                    WHEN hs_code LIKE '57%' THEN 'Carpets'
                    WHEN hs_code LIKE '58%' THEN 'Special Fabrics'
                    WHEN hs_code LIKE '60%' THEN 'Knitted Fabrics'
                    WHEN hs_code LIKE '61%' THEN 'Knitted Apparel'
                    WHEN hs_code LIKE '62%' THEN 'Woven Apparel'
                    WHEN hs_code LIKE '63%' THEN 'Other Textile Articles'
                    ELSE 'Other Textile Products'
                END AS name
            ")
            ->selectRaw(
                'SUM(trade_value) AS value'
            )
            ->groupBy(
                'trade_flow',
                'name'
            )
            ->orderByDesc('value')
            ->get()
            ->map(
                fn ($row): array => [
                    'trade_flow' =>
                        $row->trade_flow,

                    'name' =>
                        $row->name,

                    'value' =>
                        (float) $row->value,

                    'unit' =>
                        in_array(
                            $row->name,
                            [
                                'Knitted Apparel',
                                'Woven Apparel',
                            ],
                            true
                        )
                            ? 'Pcs'
                            : 'Kg',
                ]
            )
            ->all();
    }

    /**
     * --------------------------------------------------------------------------
     * Empty Snapshot
     * --------------------------------------------------------------------------
     */
    protected function emptySnapshot(): array
    {
        return [

            'period' => [
                'base_year' => $this->baseYear,
                'current_year' => $this->currentYear,
                'months' => $this->h1Months,
                'label' =>
                    'H1 '
                    . $this->currentYear
                    . ' vs H1 '
                    . $this->baseYear,
            ],

            'summary' => [
                'export' => [
                    'value_2025' => 0,
                    'value_2026' => 0,
                    'volume_2025' => 0,
                    'volume_2026' => 0,
                    'growth' => null,
                    'volume_growth' => null,
                ],

                'import' => [
                    'value_2025' => 0,
                    'value_2026' => 0,
                    'volume_2025' => 0,
                    'volume_2026' => 0,
                    'growth' => null,
                    'volume_growth' => null,
                ],
            ],

            'topExportDestinations' => [],
            'topImportOrigins' => [],
            'topExportProducts' => [],
            'topImportProducts' => [],
            'yearlyTrends' => [],
            'topTrade' => [],
            'generated_at' => null,
        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Growth Helper
     * --------------------------------------------------------------------------
     */
    protected function growth(
        float $base,
        float $current
    ): ?float {

        if ($base <= 0) {
            return null;
        }

        return round(
            (
                ($current - $base)
                / $base
            ) * 100,
            2
        );
    }
}