<?php

declare(strict_types=1);

namespace App\Services\Trade;

use App\Services\Support\CacheService;
use Illuminate\Support\Facades\DB;
use App\Services\Trade\TradeIntelligenceSnapshotService;

class SectorTradeIntelligenceService
{
    protected const GARMENT_CACHE_KEY =
        'digestex.trade.sector.garment';

    protected int $ttl = 1800;

    protected int $baseYear = 2025;

    protected int $currentYear = 2026;

    protected array $months = [
        1,
        2,
        3,
        4,
        5,
        6,
    ];

    protected int $topLimit = 10;

    public function __construct(
        protected TradeIntelligenceSnapshotService $snapshotService,
    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * PUBLIC READ
     * --------------------------------------------------------------------------
     *
     * IMPORTANT:
     * Never build the snapshot during a browser request.
     */
    public function getGarmentData(): array
{
    $snapshot = $this->snapshotService->get(
        self::GARMENT_CACHE_KEY
    );

    if (is_array($snapshot)) {
        return $snapshot;
    }

    return $this->emptyGarmentSnapshot();
}

    /**
     * --------------------------------------------------------------------------
     * BUILD
     * --------------------------------------------------------------------------
     *
     * Intended for Artisan CLI / scheduler.
     *
     * Garment classification:
     *   HS 61 = Knitted apparel
     *   HS 62 = Woven apparel
     */
    public function buildGarmentData(): array
    {
        $summary = $this->buildSummary();

        return [
            'sector' => [
                'key' => 'garment',
                'name' => 'Garment',
                'name_id' => 'Garmen',
                'classification' => [
                    'hs_chapters' => [
                        '61',
                        '62',
                    ],
                    'description' =>
                        'Apparel products classified under HS Chapters 61 and 62.',
                ],
            ],

            'period' => [
                'base_year' => $this->baseYear,
                'current_year' => $this->currentYear,
                'months' => $this->months,
                'label' =>
                    'H1 '
                    . $this->currentYear
                    . ' vs H1 '
                    . $this->baseYear,
            ],

            'summary' => $summary,

            'export_destinations' =>
                $this->topCountries('export'),

            'import_origins' =>
                $this->topCountries('import'),

            'export_products' =>
                $this->topProducts('export'),

            'import_products' =>
                $this->topProducts('import'),

            'monthly_trend' =>
                $this->monthlyTrend(),

            'generated_at' =>
                now()->toDateTimeString(),
        ];
    }

    /**
     * --------------------------------------------------------------------------
     * REFRESH CACHE
     * --------------------------------------------------------------------------
     */
    public function refreshGarmentData(): array
        {
            $snapshot = $this->buildGarmentData();

            $this->snapshotService->save(
                self::GARMENT_CACHE_KEY,
                $snapshot,
                [
                    'snapshot_type' => 'sector',
                    'sector' => 'garment',
                    'period_key' => 'H1-2026-vs-H1-2025',
                ]
            );

            return $snapshot;
        }

    /**
     * --------------------------------------------------------------------------
     * CACHE CONTROL
     * --------------------------------------------------------------------------
     */
    public function forgetGarmentData(): void
    {
        CacheService::forget(
            self::GARMENT_CACHE_KEY
        );
    }

    public function isGarmentCached(): bool
    {
        return CacheService::has(
            self::GARMENT_CACHE_KEY
        );
    }

    /**
     * --------------------------------------------------------------------------
     * SUMMARY
     * --------------------------------------------------------------------------
     */
    protected function buildSummary(): array
    {
        $rows = DB::table('trade_statistics')
            ->whereIn(
                'trade_flow',
                [
                    'export',
                    'import',
                ]
            )
            ->whereIn(
                'year',
                [
                    $this->baseYear,
                    $this->currentYear,
                ]
            )
            ->whereIn(
                'month',
                $this->months
            )
            ->where(function ($query) {
                $query
                    ->where(
                        'hs_code',
                        'like',
                        '61%'
                    )
                    ->orWhere(
                        'hs_code',
                        'like',
                        '62%'
                    );
            })
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
            ->get();

        $summary = [
            'export' => [
                'value_2025' => 0.0,
                'value_2026' => 0.0,
                'volume_2025' => 0.0,
                'volume_2026' => 0.0,
                'growth' => null,
                'volume_growth' => null,
            ],

            'import' => [
                'value_2025' => 0.0,
                'value_2026' => 0.0,
                'volume_2025' => 0.0,
                'volume_2026' => 0.0,
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
            }

            if ($year === $this->currentYear) {
                $summary[$flow]['value_2026'] =
                    (float) $row->trade_value;

                $summary[$flow]['volume_2026'] =
                    (float) $row->trade_volume;
            }
        }

        foreach (
            [
                'export',
                'import',
            ] as $flow
        ) {
            $summary[$flow]['growth'] =
                $this->calculateGrowth(
                    $summary[$flow]['value_2025'],
                    $summary[$flow]['value_2026']
                );

            $summary[$flow]['volume_growth'] =
                $this->calculateGrowth(
                    $summary[$flow]['volume_2025'],
                    $summary[$flow]['volume_2026']
                );
        }

        return $summary;
    }

    /**
     * --------------------------------------------------------------------------
     * TOP COUNTRIES
     * --------------------------------------------------------------------------
     *
     * Export:
     *   major garment export destinations
     *
     * Import:
     *   major garment import origins
     */
    protected function topCountries(
        string $tradeFlow
    ): array {
        $totalValue = DB::table('trade_statistics')
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
                $this->months
            )
            ->whereNotNull(
                'country_id'
            )
            ->where(function ($query) {
                $query
                    ->where(
                        'hs_code',
                        'like',
                        '61%'
                    )
                    ->orWhere(
                        'hs_code',
                        'like',
                        '62%'
                    );
            })
            ->sum('trade_value');

        $rows = DB::table(
            'trade_statistics as ts'
        )
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
                $this->months
            )
            ->whereNotNull(
                'ts.country_id'
            )
            ->where(
                'mc.is_active',
                1
            )
            ->where(function ($query) {
                $query
                    ->where(
                        'ts.hs_code',
                        'like',
                        '61%'
                    )
                    ->orWhere(
                        'ts.hs_code',
                        'like',
                        '62%'
                    );
            })
            ->select(
                'ts.country_id',
                'mc.country_code',
                'mc.iso3',
                'mc.country_name_en',
                'mc.country_name_id'
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
            ->orderByDesc(
                'trade_value'
            )
            ->limit(
                $this->topLimit
            )
            ->get();

        return $rows
            ->values()
            ->map(
                function ($row, int $index) use (
                    $totalValue
                ): array {
                    return [
                        'rank' =>
                            $index + 1,

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
                            (float) $row->trade_value,

                        'trade_volume' =>
                            (float) $row->trade_volume,

                        'share' =>
                            $totalValue > 0
                                ? round(
                                    (
                                        (float) $row->trade_value
                                        / $totalValue
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
     * TOP PRODUCTS
     * --------------------------------------------------------------------------
     */
    protected function topProducts(
        string $tradeFlow
    ): array {
        $totalValue = DB::table(
            'trade_statistics'
        )
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
                $this->months
            )
            ->where(function ($query) {
                $query
                    ->where(
                        'hs_code',
                        'like',
                        '61%'
                    )
                    ->orWhere(
                        'hs_code',
                        'like',
                        '62%'
                    );
            })
            ->sum('trade_value');

        return DB::table(
            'trade_statistics'
        )
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
                $this->months
            )
            ->whereNotNull(
                'hs_code'
            )
            ->where(function ($query) {
                $query
                    ->where(
                        'hs_code',
                        'like',
                        '61%'
                    )
                    ->orWhere(
                        'hs_code',
                        'like',
                        '62%'
                    );
            })
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
            ->orderByDesc(
                'trade_value'
            )
            ->limit(
                $this->topLimit
            )
            ->get()
            ->values()
            ->map(
                function ($row, int $index) use (
                    $totalValue
                ): array {
                    return [
                        'rank' =>
                            $index + 1,

                        'hs_code' =>
                            $row->hs_code,

                        'product' =>
                            $row->hs_description,

                        'trade_value' =>
                            (float) $row->trade_value,

                        'trade_volume' =>
                            (float) $row->trade_volume,

                        'share' =>
                            $totalValue > 0
                                ? round(
                                    (
                                        (float) $row->trade_value
                                        / $totalValue
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
     * MONTHLY TREND
     * --------------------------------------------------------------------------
     */
    protected function monthlyTrend(): array
    {
        return DB::table(
            'trade_statistics'
        )
            ->whereIn(
                'trade_flow',
                [
                    'export',
                    'import',
                ]
            )
            ->whereIn(
                'year',
                [
                    $this->baseYear,
                    $this->currentYear,
                ]
            )
            ->whereIn(
                'month',
                $this->months
            )
            ->where(function ($query) {
                $query
                    ->where(
                        'hs_code',
                        'like',
                        '61%'
                    )
                    ->orWhere(
                        'hs_code',
                        'like',
                        '62%'
                    );
            })
            ->select(
                'year',
                'month',
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
                'month',
                'trade_flow'
            )
            ->orderBy(
                'year'
            )
            ->orderBy(
                'month'
            )
            ->get()
            ->map(
                fn ($row): array => [
                    'year' =>
                        (int) $row->year,

                    'month' =>
                        (int) $row->month,

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
     * EMPTY FALLBACK
     * --------------------------------------------------------------------------
     */
    protected function emptyGarmentSnapshot(): array
    {
        return [
            'sector' => [
                'key' => 'garment',
                'name' => 'Garment',
                'name_id' => 'Garmen',
                'classification' => [
                    'hs_chapters' => [
                        '61',
                        '62',
                    ],
                    'description' =>
                        'Apparel products classified under HS Chapters 61 and 62.',
                ],
            ],

            'period' => [
                'base_year' => $this->baseYear,
                'current_year' => $this->currentYear,
                'months' => $this->months,
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

            'export_destinations' => [],
            'import_origins' => [],
            'export_products' => [],
            'import_products' => [],
            'monthly_trend' => [],
            'generated_at' => null,
        ];
    }

    /**
     * --------------------------------------------------------------------------
     * GROWTH
     * --------------------------------------------------------------------------
     */
    protected function calculateGrowth(
        float $base,
        float $current
    ): ?float {
        if ($base <= 0) {
            return null;
        }

        return round(
            (
                (
                    $current - $base
                )
                / $base
            ) * 100,
            2
        );
    }
}