<?php

declare(strict_types=1);

namespace App\Services\Trade;

use App\Services\Trade\Taxonomy\TextileTaxonomyService;
use App\Services\Trade\TradeIntelligenceSnapshotService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ThreadTradeIntelligenceService
{
    /*
    |--------------------------------------------------------------------------
    | Snapshot / Cache
    |--------------------------------------------------------------------------
    */

    public const CACHE_KEY =
        'digestex.trade.sector.thread';

    protected const SNAPSHOT_TYPE =
        'sector';

    protected const SECTOR =
        'thread';

    protected const CACHE_TTL =
        1800;


    /*
    |--------------------------------------------------------------------------
    | Confirmed Thread HS4
    |--------------------------------------------------------------------------
    |
    | Digestex Textile Taxonomy v1.2
    |
    | 5204 = Cotton Sewing Thread
    | 5401 = Filament Sewing Thread
    | 5508 = Synthetic Staple Sewing Thread
    |
    */

    protected const THREAD_HS4 = [
        '5204',
        '5401',
        '5508',
    ];


    /*
    |--------------------------------------------------------------------------
    | Services
    |--------------------------------------------------------------------------
    */

    public function __construct(
        protected TextileTaxonomyService $taxonomy,
        protected TradeIntelligenceSnapshotService $snapshotService,
        protected TradeReportingPeriodProvider $periodProvider,
        protected CountryResolverService $countryResolver,
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | PUBLIC: Get Current Thread Intelligence
    |--------------------------------------------------------------------------
    |
    | User request flow:
    |
    | Admin Reporting Period
    |        ↓
    | TradeReportingPeriod
    |        ↓
    | Period-aware runtime cache
    |        ↓
    | Persistent validated snapshot
    |        ↓
    | Empty fallback
    |
    | Heavy aggregation is NOT performed here.
    |--------------------------------------------------------------------------
    */

    public function get(): array
    {
        $period =
            $this->periodProvider->current();

        $runtimeCacheKey =
            $this->runtimeCacheKey($period);

        /*
        |--------------------------------------------------------------------------
        | Runtime Cache
        |--------------------------------------------------------------------------
        */

        $cached =
            Cache::get($runtimeCacheKey);

        if (is_array($cached)) {
            return $cached;
        }


        /*
        |--------------------------------------------------------------------------
        | Persistent Last Known Good Snapshot
        |--------------------------------------------------------------------------
        */

        $snapshot =
            $this->snapshotService->get(
                self::CACHE_KEY
            );

        /*
        |--------------------------------------------------------------------------
        | Validate Snapshot Period
        |--------------------------------------------------------------------------
        |
        | Do not return an older public period after Admin has moved
        | the reporting period forward.
        |--------------------------------------------------------------------------
        */

        if (
            is_array($snapshot)
            && $this->snapshotMatchesPeriod(
                $snapshot,
                $period
            )
        ) {
            Cache::put(
                $runtimeCacheKey,
                $snapshot,
                now()->addSeconds(
                    self::CACHE_TTL
                )
            );

            return $snapshot;
        }


        /*
        |--------------------------------------------------------------------------
        | Safe Fallback
        |--------------------------------------------------------------------------
        |
        | Important:
        | We do not rebuild trade data during the user request.
        | Admin must warm the new snapshot after changing the
        | reporting period.
        |--------------------------------------------------------------------------
        */

        return $this->emptySnapshot(
            $period
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PUBLIC: Refresh Snapshot
    |--------------------------------------------------------------------------
    */

    public function refresh(): array
    {
        $period =
            $this->periodProvider->current();

        $snapshot =
            $this->buildSnapshot(
                $period
            );


        /*
        |--------------------------------------------------------------------------
        | Persistent Validated Snapshot
        |--------------------------------------------------------------------------
        */

        $this->snapshotService->save(
            self::CACHE_KEY,
            $snapshot,
            [
                'snapshot_type' =>
                    self::SNAPSHOT_TYPE,

                'sector' =>
                    self::SECTOR,

                'period_key' =>
                    $period->snapshotKey(),
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Runtime Cache
        |--------------------------------------------------------------------------
        */

        $runtimeCacheKey =
            $this->runtimeCacheKey(
                $period
            );

        Cache::put(
            $runtimeCacheKey,
            $snapshot,
            now()->addSeconds(
                self::CACHE_TTL
            )
        );

        return $snapshot;
    }


    /*
    |--------------------------------------------------------------------------
    | PUBLIC: Build Snapshot
    |--------------------------------------------------------------------------
    */

    // public function buildSnapshot(): array
    // {
    //     return $this->buildSnapshot(
    //         $this->periodProvider->current()
    //     );
    // }


    /*
    |--------------------------------------------------------------------------
    | CORE SNAPSHOT BUILDER
    |--------------------------------------------------------------------------
    */

    protected function buildSnapshot(
        TradeReportingPeriod $period
    ): array {
        $columns =
            $this->resolveColumns();

        $query =
            DB::table('trade_statistics');


        /*
        |--------------------------------------------------------------------------
        | Select Thread HS4
        |--------------------------------------------------------------------------
        */

        $query->where(
            function ($q) use (
                $columns
            ) {
                foreach (
                    self::THREAD_HS4
                    as $hs4
                ) {
                    $q->orWhere(
                        $columns['hs_code'],
                        'like',
                        $hs4 . '%'
                    );
                }
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Public Reporting Period
        |--------------------------------------------------------------------------
        |
        | Current:
        | Jan → public_through_month of current year
        |
        | Comparison:
        | Jan → comparison_through_month of previous year
        |
        */

        $query->where(
            function ($q) use (
                $columns,
                $period
            ) {
                $q->where(
                    function ($currentQuery)
                        use (
                            $columns,
                            $period
                        ) {
                            $currentQuery
                                ->where(
                                    $columns['year'],
                                    $period
                                        ->publicThroughYear
                                )
                                ->whereBetween(
                                    $columns['month'],
                                    [
                                        1,
                                        $period
                                            ->publicThroughMonth,
                                    ]
                                );
                        }
                );

                $q->orWhere(
                    function ($comparisonQuery)
                        use (
                            $columns,
                            $period
                        ) {
                            $comparisonQuery
                                ->where(
                                    $columns['year'],
                                    $period
                                        ->comparisonYear
                                )
                                ->whereBetween(
                                    $columns['month'],
                                    [
                                        1,
                                        $period
                                            ->comparisonThroughMonth,
                                    ]
                                );
                        }
                );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Select Data
        |--------------------------------------------------------------------------
        */

        $rows =
            $query
                ->select([
                    $columns['year']
                        . ' as trade_year',

                    $columns['month']
                        . ' as trade_month',

                    $columns['hs_code']
                        . ' as hs_code',

                    $columns['trade_value']
                        . ' as trade_value',

                    $columns['trade_volume']
                        . ' as trade_volume',

                    $columns['hs_description']
                        . ' as hs_description',

                    $columns['flow']
                        . ' as trade_flow',

                    $columns['country']
                        . ' as trade_country',
                ])
                ->get();


        /*
        |--------------------------------------------------------------------------
        | Transform / Taxonomy Classification
        |--------------------------------------------------------------------------
        */

        $classified =
            $rows
                ->map(
                    function ($row) {
                        $classification =
                            $this->taxonomy->classify(
                                $row->hs_code
                            );

                        if (
                    $classification === null
                    || $classification['sector']
                        !== self::SECTOR
                ) {
                    return null;
                }

                /*
                |--------------------------------------------------------------------------
                | Canonical Country Identity
                |--------------------------------------------------------------------------
                */

                $country =
                    $this->countryResolver->resolve(
                        $row->trade_country
                    );

                return [    
                            'year' =>
                                (int) $row->trade_year,

                            'month' =>
                                (int) $row->trade_month,

                            'hs_code' =>
                                (string) $row->hs_code,

                            'hs4' =>
                                substr(
                                    (string) $row->hs_code,
                                    0,
                                    4
                                ),

                            'description' =>
                                $row->hs_description,

                            'subsector' =>
                                $classification['subsector'],

                            'label_en' =>
                                $classification['label_en'],

                            'label_id' =>
                                $classification['label_id'],

                            'flow' =>
                            $this->normalizeFlow(
                                $row->trade_flow
                            ),

                        'country' =>
                            $row->trade_country,

                        'country_id' =>
                            $country?->id,

                        'country_code' =>
                            $country?->country_code,

                        'iso3' =>
                            $country?->iso3,

                        'country_name_en' =>
                            $country?->country_name_en,

                        'country_name_id' =>
                            $country?->country_name_id,

                        'flag_emoji' =>
                            $country?->flag_emoji,

                        'value' =>
                            $this->toFloat(
                                $row->trade_value
                            ),

                        'volume' =>
                            $this->toFloat(
                                $row->trade_volume
                            ),
                        ];
                    }
                )
                ->filter()
                ->values();


        /*
        |--------------------------------------------------------------------------
        | Current / Comparison Collections
        |--------------------------------------------------------------------------
        */

        $current =
            $classified->where(
                'year',
                $period->publicThroughYear
            );

        $previous =
            $classified->where(
                'year',
                $period->comparisonYear
            );


        /*
        |--------------------------------------------------------------------------
        | Snapshot Payload
        |--------------------------------------------------------------------------
        */

        return [

            /*
            |--------------------------------------------------------------------------
            | Metadata
            |--------------------------------------------------------------------------
            */

            'meta' => [

                'sector' =>
                    self::SECTOR,

                'snapshot_key' =>
                    self::CACHE_KEY,

                'snapshot_type' =>
                    self::SNAPSHOT_TYPE,

                'period' =>
                    $period->periodLabel(),

                'period_label_en' =>
                    $period->periodLabel(),

                'period_label_id' =>
                    $period->periodLabel(),

                'display_period_label_en' =>
                    $period->displayPeriodLabelEn(),

                'display_period_label_id' =>
                    $period->displayPeriodLabelId(),

                'comparison_period_label_en' =>
                    $period
                        ->comparisonPeriodLabelEn(),

                'comparison_period_label_id' =>
                    $period
                        ->comparisonPeriodLabelId(),

                'current_period' =>
                    $period->currentPeriod(),

                'comparison_period' =>
                    $period->comparisonPeriod(),

                'current_year' =>
                    $period->publicThroughYear,

                'comparison_year' =>
                    $period->comparisonYear,

                'through_month' =>
                    $period->publicThroughMonth,

                'comparison_through_month' =>
                    $period->comparisonThroughMonth,

                'latest_available_period' =>
                    null,

                'buffer_period' =>
                    $period->bufferPeriod(),

                'buffer_status' =>
                    $period->status,

                'data_status' =>
                    $this->dataStatus(
                        $period
                    ),

                'generated_at' =>
                    now()->toIso8601String(),

                'record_count' =>
                    $classified->count(),

                'snapshot_period_key' =>
                    $period->snapshotKey(),

                'hs4' =>
                    self::THREAD_HS4,
            ],


            /*
            |--------------------------------------------------------------------------
            | Executive Overview
            |--------------------------------------------------------------------------
            */

            'overview' =>
                $this->buildOverview(
                    $current,
                    $previous
                ),


            /*
            |--------------------------------------------------------------------------
            | Subsector Intelligence
            |--------------------------------------------------------------------------
            */

            'by_subsector' =>
                $this->buildSubsectorPerformance(
                    $classified
                ),


            /*
            |--------------------------------------------------------------------------
            | Trade Flow
            |--------------------------------------------------------------------------
            */

            'by_flow' =>
                $this->buildFlowPerformance(
                    $classified
                ),


            /*
            |--------------------------------------------------------------------------
            | Product Intelligence
            |--------------------------------------------------------------------------
            */

            'top_import_products' =>
                $this->buildTopProducts(
                    $current,
                    'import'
                ),

            'top_export_products' =>
                $this->buildTopProducts(
                    $current,
                    'export'
                ),


            /*
            |--------------------------------------------------------------------------
            | Country Intelligence
            |--------------------------------------------------------------------------
            */

            'top_import_origins' =>
                $this->buildTopCountries(
                    $current,
                    'import'
                ),

            'top_export_destinations' =>
                $this->buildTopCountries(
                    $current,
                    'export'
                ),

            'import_market_share' =>
                $this->buildCountryMarketShare(
                    $current,
                    'import'
                ),

            'export_market_share' =>
                $this->buildCountryMarketShare(
                    $current,
                    'export'
                ),


            /*
            |--------------------------------------------------------------------------
            | Time Intelligence
            |--------------------------------------------------------------------------
            */

            'monthly_trend' =>
                $this->buildMonthlyTrend(
                    $classified
                ),

            'yearly_trend' =>
                $this->buildYearlyTrend(
                    $classified
                ),


            /*
            |--------------------------------------------------------------------------
            | HS-8 Intelligence
            |--------------------------------------------------------------------------
            */

            'hs8_products' =>
                $this->buildHs8Products(
                    $current
                ),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Overview
    |--------------------------------------------------------------------------
    */

    protected function buildOverview(
        $current,
        $previous
    ): array {
        $currentImport =
            $this->sumByFlow(
                $current,
                'import'
            );

        $previousImport =
            $this->sumByFlow(
                $previous,
                'import'
            );

        $currentExport =
            $this->sumByFlow(
                $current,
                'export'
            );

        $previousExport =
            $this->sumByFlow(
                $previous,
                'export'
            );

        return [

            'import' => [

                'current' =>
                    $currentImport,

                'previous' =>
                    $previousImport,

                'growth_percent' =>
                    $this->growthPercent(
                        $currentImport,
                        $previousImport
                    ),
            ],

            'export' => [

                'current' =>
                    $currentExport,

                'previous' =>
                    $previousExport,

                'growth_percent' =>
                    $this->growthPercent(
                        $currentExport,
                        $previousExport
                    ),
            ],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Subsector Performance
    |--------------------------------------------------------------------------
    */

    protected function buildSubsectorPerformance(
        $rows
    ): array {
        return $rows
            ->groupBy('subsector')
            ->map(
                function (
                    $items,
                    $subsector
                ) {
                    $current =
                        $items->where(
                            'year',
                            $items->max('year')
                        );

                    $years =
                        $items
                            ->pluck('year')
                            ->unique()
                            ->sort()
                            ->values();

                    $currentYear =
                        $years->last();

                    $previousYear =
                        $years
                            ->reverse()
                            ->skip(1)
                            ->first();

                    if (
                        $previousYear === null
                    ) {
                        $previousYear =
                            $currentYear - 1;
                    }

                    $current =
                        $items->where(
                            'year',
                            $currentYear
                        );

                    $previous =
                        $items->where(
                            'year',
                            $previousYear
                        );

                    $currentImport =
                        $this->sumByFlow(
                            $current,
                            'import'
                        );

                    $currentExport =
                        $this->sumByFlow(
                            $current,
                            'export'
                        );

                    $previousImport =
                        $this->sumByFlow(
                            $previous,
                            'import'
                        );

                    $previousExport =
                        $this->sumByFlow(
                            $previous,
                            'export'
                        );

                    $first =
                        $items->first();

                    return [

                        'subsector' =>
                            $subsector,

                        'label_en' =>
                            $first['label_en']
                                ?? null,

                        'label_id' =>
                            $first['label_id']
                                ?? null,

                        'import_value' =>
                            $currentImport,

                        'export_value' =>
                            $currentExport,

                        'import_growth_percent' =>
                            $this->growthPercent(
                                $currentImport,
                                $previousImport
                            ),

                        'export_growth_percent' =>
                            $this->growthPercent(
                                $currentExport,
                                $previousExport
                            ),
                    ];
                }
            )
            ->sortByDesc(
                'import_value'
            )
            ->values()
            ->all();
    }


    /*
    |--------------------------------------------------------------------------
    | Flow Performance
    |--------------------------------------------------------------------------
    */

    protected function buildFlowPerformance(
        $rows
    ): array {
        return $rows
            ->groupBy('flow')
            ->map(
                function (
                    $items,
                    $flow
                ) {
                    $years =
                        $items
                            ->pluck('year')
                            ->unique()
                            ->sort()
                            ->values();

                    $currentYear =
                        $years->last();

                    $previousYear =
                        $years
                            ->reverse()
                            ->skip(1)
                            ->first();

                    if (
                        $previousYear === null
                    ) {
                        $previousYear =
                            $currentYear - 1;
                    }

                    $current =
                        $items->where(
                            'year',
                            $currentYear
                        );

                    $previous =
                        $items->where(
                            'year',
                            $previousYear
                        );

                    $currentValue =
                        $current->sum('value');

                    $previousValue =
                        $previous->sum(
                            'value'
                        );

                    return [

                        'flow' =>
                            $flow,

                        'value' =>
                            $currentValue,

                        'previous_value' =>
                            $previousValue,

                        'growth_percent' =>
                            $this->growthPercent(
                                $currentValue,
                                $previousValue
                            ),
                    ];
                }
            )
            ->values()
            ->all();
    }


    /*
    |--------------------------------------------------------------------------
    | Top Products
    |--------------------------------------------------------------------------
    */

    protected function buildTopProducts(
        $rows,
        string $flow,
        int $limit = 10
    ): array {
        return $rows
            ->filter(
                fn ($row) =>
                    $row['flow'] === $flow
            )
            ->groupBy('hs4')
            ->map(
                function (
                    $items,
                    $hs4
                ) {
                    $first =
                        $items->first();

                    return [

                        'hs4' =>
                            $hs4,

                        'description' =>
                            $first[
                                'description'
                            ],

                        'subsector' =>
                            $first[
                                'subsector'
                            ],

                        'label_en' =>
                            $first[
                                'label_en'
                            ],

                        'label_id' =>
                            $first[
                                'label_id'
                            ],

                        'value' =>
                            $items->sum(
                                'value'
                            ),

                        'volume' =>
                            $items->sum(
                                'volume'
                            ),
                    ];
                }
            )
            ->sortByDesc(
                'value'
            )
            ->take(
                $limit
            )
            ->values()
            ->all();
    }


   /*
|--------------------------------------------------------------------------
| Country Market Share
|--------------------------------------------------------------------------
*/

protected function buildCountryMarketShare(
    $rows,
    string $flow,
    int $limit = 10
): array {
    $items =
        $rows->filter(
            fn ($row) =>
                ($row['flow'] ?? null) === $flow
                && filled(
                    $row['country']
                    ?? null
                )
        );

    $total =
        (float) $items->sum(
            'value'
        );

    if (
        $total <= 0.0
    ) {
        return [];
    }

    return $items
        ->groupBy(
            fn ($row) =>
                $row['country_code']
                ?? $row['country']
        )
        ->map(
            function (
                $group,
                $countryKey
            ) use (
                $total
            ) {
                $first =
                    $group->first();

                $value =
                    (float) $group->sum(
                        'value'
                    );

                return [

                    /*
                    |--------------------------------------------------------------------------
                    | Canonical Country Identity
                    |--------------------------------------------------------------------------
                    */

                    'country_id' =>
                        $first['country_id']
                        ?? null,

                    'country_code' =>
                        $first['country_code']
                        ?? null,

                    'iso3' =>
                        $first['iso3']
                        ?? null,

                    'country_name_en' =>
                        $first['country_name_en']
                        ?? null,

                    'country_name_id' =>
                        $first['country_name_id']
                        ?? null,

                    'flag_emoji' =>
                        $first['flag_emoji']
                        ?? null,

                    /*
                    |--------------------------------------------------------------------------
                    | Legacy Country Name
                    |--------------------------------------------------------------------------
                    */

                    'country' =>
                        $first['country']
                        ?? $countryKey,

                    /*
                    |--------------------------------------------------------------------------
                    | Trade Metrics
                    |--------------------------------------------------------------------------
                    */

                    'value' =>
                        $value,

                    'volume' =>
                        (float) $group->sum(
                            'volume'
                        ),

                    'market_share_percent' =>
                        round(
                            (
                                $value
                                / $total
                            ) * 100,
                            2
                        ),
                ];
            }
        )
        ->sortByDesc(
            'value'
        )
        ->take(
            $limit
        )
        ->values()
        ->all();
}

    /*
    |--------------------------------------------------------------------------
    | Top Countries
    |--------------------------------------------------------------------------
    */

    protected function buildTopCountries(
    $rows,
    string $flow,
    int $limit = 10
): array {
    $items =
        $rows->filter(
            fn ($row) =>
                ($row['flow'] ?? null) === $flow
                && filled(
                    $row['country']
                    ?? null
                )
        );

    return $items
        ->groupBy(
            fn ($row) =>
                $row['country_code']
                ?? $row['country']
        )
        ->map(
            function (
                $group,
                $countryKey
            ) {
                $first =
                    $group->first();

                return [

                    /*
                    |--------------------------------------------------------------------------
                    | Canonical Country Identity
                    |--------------------------------------------------------------------------
                    */

                    'country_id' =>
                        $first['country_id']
                        ?? null,

                    'country_code' =>
                        $first['country_code']
                        ?? null,

                    'iso3' =>
                        $first['iso3']
                        ?? null,

                    'country_name_en' =>
                        $first['country_name_en']
                        ?? null,

                    'country_name_id' =>
                        $first['country_name_id']
                        ?? null,

                    'flag_emoji' =>
                        $first['flag_emoji']
                        ?? null,

                    /*
                    |--------------------------------------------------------------------------
                    | Legacy Country Name
                    |--------------------------------------------------------------------------
                    */

                    'country' =>
                        $first['country']
                        ?? $countryKey,

                    /*
                    |--------------------------------------------------------------------------
                    | Trade Metrics
                    |--------------------------------------------------------------------------
                    */

                    'value' =>
                        (float) $group->sum(
                            'value'
                        ),

                    'volume' =>
                        (float) $group->sum(
                            'volume'
                        ),
                ];
            }
        )
        ->sortByDesc(
            'value'
        )
        ->take(
            $limit
        )
        ->values()
        ->all();
}

    /*
    |--------------------------------------------------------------------------
    | Monthly Trend
    |--------------------------------------------------------------------------
    */

    protected function buildMonthlyTrend(
        $rows
    ): array {
        return $rows
            ->filter(
                fn ($row) =>
                    !empty(
                        $row['month']
                    )
                    && (int) $row['month'] >= 1
                    && (int) $row['month'] <= 12
            )
            ->groupBy(
                function ($row) {
                    return sprintf(
                        '%04d-%02d',
                        $row['year'],
                        $row['month']
                    );
                }
            )
            ->map(
                function (
                    $items,
                    $period
                ) {
                    return [

                        'period' =>
                            $period,

                        'import' =>
                            $this->sumByFlow(
                                $items,
                                'import'
                            ),

                        'export' =>
                            $this->sumByFlow(
                                $items,
                                'export'
                            ),
                    ];
                }
            )
            ->sortKeys()
            ->values()
            ->all();
    }


    /*
    |--------------------------------------------------------------------------
    | Yearly Trend
    |--------------------------------------------------------------------------
    */

    protected function buildYearlyTrend(
        $rows
    ): array {
        return $rows
            ->groupBy('year')
            ->map(
                function (
                    $items,
                    $year
                ) {
                    return [

                        'year' =>
                            (int) $year,

                        'import' =>
                            $this->sumByFlow(
                                $items,
                                'import'
                            ),

                        'export' =>
                            $this->sumByFlow(
                                $items,
                                'export'
                            ),
                    ];
                }
            )
            ->sortKeys()
            ->values()
            ->all();
    }


    /*
    |--------------------------------------------------------------------------
    | HS-8 Products
    |--------------------------------------------------------------------------
    */

    protected function buildHs8Products(
        $rows,
        int $limit = 50
    ): array {
        return $rows
            ->groupBy('hs_code')
            ->map(
                function (
                    $items,
                    $hsCode
                ) {
                    $first =
                        $items->first();

                    return [

                        'hs_code' =>
                            $hsCode,

                        'hs4' =>
                            $first['hs4'],

                        'description' =>
                            $first[
                                'description'
                            ],

                        'subsector' =>
                            $first[
                                'subsector'
                            ],

                        'label_en' =>
                            $first[
                                'label_en'
                            ],

                        'label_id' =>
                            $first[
                                'label_id'
                            ],

                        'flow' =>
                            $first[
                                'flow'
                            ],

                        'value' =>
                            $items->sum(
                                'value'
                            ),

                        'volume' =>
                            $items->sum(
                                'volume'
                            ),
                    ];
                }
            )
            ->sortByDesc(
                'value'
            )
            ->take(
                $limit
            )
            ->values()
            ->all();
    }


    /*
    |--------------------------------------------------------------------------
    | Flow Normalization
    |--------------------------------------------------------------------------
    */

    protected function normalizeFlow(
        mixed $flow
    ): ?string {
        if (
            $flow === null
        ) {
            return null;
        }

        $value =
            strtolower(
                trim(
                    (string) $flow
                )
            );

        if (
            str_contains(
                $value,
                'import'
            )
            || in_array(
                $value,
                [
                    'impor',
                    'i',
                    'm',
                ],
                true
            )
        ) {
            return 'import';
        }

        if (
            str_contains(
                $value,
                'export'
            )
            || in_array(
                $value,
                [
                    'ekspor',
                    'e',
                    'x',
                ],
                true
            )
        ) {
            return 'export';
        }

        return $value !== ''
            ? $value
            : null;
    }


    /*
    |--------------------------------------------------------------------------
    | Sum by Flow
    |--------------------------------------------------------------------------
    */

    protected function sumByFlow(
        $rows,
        string $flow
    ): float {
        return (float)
            $rows
                ->filter(
                    fn ($row) =>
                        $row['flow'] === $flow
                )
                ->sum('value');
    }


    /*
    |--------------------------------------------------------------------------
    | Growth
    |--------------------------------------------------------------------------
    */

    protected function growthPercent(
        float $current,
        float $previous
    ): float {
        if (
            $previous == 0.0
        ) {
            return $current > 0.0
                ? 100.0
                : 0.0;
        }

        return round(
            (
                (
                    $current
                    - $previous
                )
                / $previous
            ) * 100,
            2
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Float Normalization
    |--------------------------------------------------------------------------
    */

    protected function toFloat(
        mixed $value
    ): float {
        if (
            $value === null
        ) {
            return 0.0;
        }

        if (
            is_numeric($value)
        ) {
            return (float) $value;
        }

        $value =
            str_replace(
                ',',
                '',
                (string) $value
            );

        return is_numeric($value)
            ? (float) $value
            : 0.0;
    }


    /*
    |--------------------------------------------------------------------------
    | Database Columns
    |--------------------------------------------------------------------------
    |
    | Schema verified against trade_statistics.
    |--------------------------------------------------------------------------
    */

    protected function resolveColumns(): array
    {
        return [

            'year' =>
                'year',

            'month' =>
                'month',

            'hs_code' =>
                'hs_code',

            'hs_description' =>
                'hs_description',

            'trade_value' =>
                'trade_value',

            'trade_volume' =>
                'trade_volume',

            'flow' =>
                'trade_flow',

            'country' =>
                'country_name',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Runtime Cache Key
    |--------------------------------------------------------------------------
    */

    protected function runtimeCacheKey(
        TradeReportingPeriod $period
    ): string {
        return self::CACHE_KEY
            . '.'
            . $period->snapshotKey();
    }


    /*
    |--------------------------------------------------------------------------
    | Snapshot Period Validation
    |--------------------------------------------------------------------------
    */

    protected function snapshotMatchesPeriod(
        array $snapshot,
        TradeReportingPeriod $period
    ): bool {
        $snapshotPeriodKey =
            data_get(
                $snapshot,
                'meta.snapshot_period_key'
            );

        if (
            $snapshotPeriodKey ===
            $period->snapshotKey()
        ) {
            return true;
        }

        /*
        |--------------------------------------------------------------------------
        | Backward Compatibility
        |--------------------------------------------------------------------------
        |
        | Older snapshots may not yet contain snapshot_period_key.
        | In that situation use current/comparison period fields
        | when available.
        |--------------------------------------------------------------------------
        */

        $currentPeriod =
            data_get(
                $snapshot,
                'meta.current_period'
            );

        $comparisonPeriod =
            data_get(
                $snapshot,
                'meta.comparison_period'
            );

        return
            $currentPeriod ===
                $period->currentPeriod()
            &&
            $comparisonPeriod ===
                $period->comparisonPeriod();
    }


    /*
    |--------------------------------------------------------------------------
    | Data Status
    |--------------------------------------------------------------------------
    */

    protected function dataStatus(
        TradeReportingPeriod $period
    ): string {
        return match (
            $period->status
        ) {
            'buffer_promoted' =>
                'awaiting_latest_data',

            default =>
                'available',
        };
    }


    /*
    |--------------------------------------------------------------------------
    | Empty Snapshot
    |--------------------------------------------------------------------------
    */

    protected function emptySnapshot(
        TradeReportingPeriod $period
    ): array {
        return [

            /*
            |--------------------------------------------------------------------------
            | Metadata
            |--------------------------------------------------------------------------
            */

            'meta' => [

                'sector' =>
                    self::SECTOR,

                'snapshot_key' =>
                    self::CACHE_KEY,

                'snapshot_type' =>
                    self::SNAPSHOT_TYPE,

                'period' =>
                    $period->periodLabel(),

                'period_label_en' =>
                    $period->periodLabel(),

                'period_label_id' =>
                    $period->periodLabel(),

                'display_period_label_en' =>
                    $period
                        ->displayPeriodLabelEn(),

                'display_period_label_id' =>
                    $period
                        ->displayPeriodLabelId(),

                'comparison_period_label_en' =>
                    $period
                        ->comparisonPeriodLabelEn(),

                'comparison_period_label_id' =>
                    $period
                        ->comparisonPeriodLabelId(),

                'current_period' =>
                    $period->currentPeriod(),

                'comparison_period' =>
                    $period->comparisonPeriod(),

                'current_year' =>
                    $period->publicThroughYear,

                'comparison_year' =>
                    $period->comparisonYear,

                'through_month' =>
                    $period->publicThroughMonth,

                'comparison_through_month' =>
                    $period->comparisonThroughMonth,

                'latest_available_period' =>
                    null,

                'buffer_period' =>
                    $period->bufferPeriod(),

                'buffer_status' =>
                    $period->status,

                'data_status' =>
                    $this->dataStatus(
                        $period
                    ),

                'generated_at' =>
                    null,

                'record_count' =>
                    0,

                'snapshot_period_key' =>
                    $period->snapshotKey(),

                'hs4' =>
                    self::THREAD_HS4,
            ],


            /*
            |--------------------------------------------------------------------------
            | Executive Overview
            |--------------------------------------------------------------------------
            */

            'overview' => [

                'import' => [

                    'current' =>
                        0,

                    'previous' =>
                        0,

                    'growth_percent' =>
                        0,
                ],

                'export' => [

                    'current' =>
                        0,

                    'previous' =>
                        0,

                    'growth_percent' =>
                        0,
                ],
            ],


            /*
            |--------------------------------------------------------------------------
            | Intelligence Arrays
            |--------------------------------------------------------------------------
            */

            'by_subsector' => [],

            'by_flow' => [],

            'top_import_products' => [],

            'top_export_products' => [],

            'top_import_origins' => [],

            'top_export_destinations' => [],

            'import_market_share' => [],

            'export_market_share' => [],

            'monthly_trend' => [],

            'yearly_trend' => [],

            'hs8_products' => [],
        ];
    }
}