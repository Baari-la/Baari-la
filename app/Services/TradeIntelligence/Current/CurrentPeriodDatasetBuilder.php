<?php

declare(strict_types=1);

namespace App\Services\TradeIntelligence\Current;

use App\Services\Trade\Taxonomy\TextileTaxonomyService;
use App\Services\TradeIntelligence\Support\TradeColumnResolver;
use App\Services\TradeIntelligence\Support\TradeFlowNormalizer;
use App\Services\TradeIntelligence\Support\TradeMetricCalculator;
use App\Services\Trade\TradeReportingPeriod;
use App\Services\Trade\CountryResolverService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CurrentPeriodDatasetBuilder
{
    /*
    |--------------------------------------------------------------------------
    | Sector
    |--------------------------------------------------------------------------
    */

    private const SECTOR = 'garment';


    /*
    |--------------------------------------------------------------------------
    | Canonical HS-8 Cache
    |--------------------------------------------------------------------------
    |
    | The canonical garment HS-8 universe is resolved from
    | TextileTaxonomyService once per builder instance.
    |
    | This prevents repeated taxonomy resolution when the
    | period layer requests current and comparison datasets.
    |
    */

    protected ?array $cachedSectorHsCodes = null;


    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct(
        protected TextileTaxonomyService $taxonomy,
        protected TradeColumnResolver $columnResolver,
        protected TradeFlowNormalizer $flowNormalizer,
        protected TradeMetricCalculator $metricCalculator,
        // protected CurrentOverviewBuilder $overviewBuilder,
        protected CountryResolverService $countryResolver,
        protected CurrentSubsectorBuilder $subsectorBuilder,
        protected CurrentFlowBuilder $flowBuilder,
        protected CurrentProductBuilder $productBuilder,
        protected CurrentCountryBuilder $countryBuilder,
        protected CurrentTrendBuilder $trendBuilder,
        protected CurrentHs8Builder $hs8Builder,
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | Build One Canonical Current Period Dataset
    |--------------------------------------------------------------------------
    |
    | This builder is responsible for ONE period only.
    |
    | It does NOT know:
    |
    | - current descriptor
    | - comparison descriptor
    | - previous period
    | - trendRows composition
    | - snapshot metadata
    | - snapshot validation
    | - fallback
    |
    | TradePeriodDatasetBuilder owns period composition.
    |
    */

    public function build(
        TradeReportingPeriod $period,
        array $request
    ): array {

        \Log::info(
            'GARMENT PERF: ENTER CURRENT BUILDER',
            [
                'period' =>
                    $period->snapshotKey(),

                'request' =>
                    $request,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Resolve Period
        |--------------------------------------------------------------------------
        */

        $startedAt = microtime(true);

        $year =
            (int) (
                $request['year']
                ?? $period->publicThroughYear
            );

        $throughMonth =
            (int) (
                $request['through_month']
                ?? $request['month']
                ?? $period->publicThroughMonth
            );

        $mode =
            strtolower(
                trim(
                    (string) (
                        $request['mode']
                        ?? $period->mode
                    )
                )
            );


        /*
        |--------------------------------------------------------------------------
        | Resolve Month Window
        |--------------------------------------------------------------------------
        */

        [
            $monthStart,
            $monthEnd,
        ] =
            $this->resolveMonthWindow(
                $throughMonth,
                $mode
            );


        /*
        |--------------------------------------------------------------------------
        | Database Columns
        |--------------------------------------------------------------------------
        */

        $columns =
            $this->columnResolver->resolve();


        /*
        |--------------------------------------------------------------------------
        | Canonical Garment HS-8
        |--------------------------------------------------------------------------
        */

        $sectorHsCodes =
            $this->sectorHsCodes();


        \Log::info(
            'GARMENT PERF: PERIOD RESOLUTION',
            [
                'year' =>
                    $year,

                'month_start' =>
                    $monthStart,

                'month_end' =>
                    $monthEnd,

                'mode' =>
                    $mode,

                'hs8_count' =>
                    count($sectorHsCodes),

                'time_ms' =>
                    round(
                        (microtime(true) - $startedAt) * 1000,
                        2
                    ),
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Empty Canonical Universe
        |--------------------------------------------------------------------------
        */

        if (
            empty($sectorHsCodes)
        ) {
            return $this->emptyDataset(
                $year,
                $monthStart,
                $monthEnd,
                $mode
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Shared Canonical Dataset Cache
        |--------------------------------------------------------------------------
        */

        $cacheKey =
            $this->canonicalDatasetCacheKey(
                year:
                    $year,

                monthStart:
                    $monthStart,

                monthEnd:
                    $monthEnd,

                mode:
                    $mode,
            );


        $startedAt = microtime(true);

        $dataset =
            Cache::store('redis')->remember(
                $cacheKey,
                now()->addHours(6),
                function () use (
                    $columns,
                    $sectorHsCodes,
                    $year,
                    $monthStart,
                    $monthEnd
                ): array {

                    /*
                    |--------------------------------------------------------------------------
                    | Memory Before Query
                    |--------------------------------------------------------------------------
                    */

                    \Log::info(
                        'GARMENT PERF: MEMORY BEFORE QUERY',
                        [
                            'year' =>
                                $year,

                            'month_start' =>
                                $monthStart,

                            'month_end' =>
                                $monthEnd,

                            'memory_mb' =>
                                round(
                                    memory_get_usage(true) / 1024 / 1024,
                                    2
                                ),

                            'peak_memory_mb' =>
                                round(
                                    memory_get_peak_usage(true) / 1024 / 1024,
                                    2
                                ),
                        ]
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Database Acquisition
                    |--------------------------------------------------------------------------
                    */

                    $rows =
                        $this->queryRows(
                            columns:
                                $columns,

                            sectorHsCodes:
                                $sectorHsCodes,

                            year:
                                $year,

                            monthStart:
                                $monthStart,

                            monthEnd:
                                $monthEnd,
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Memory After Query
                    |--------------------------------------------------------------------------
                    */

                    \Log::info(
                        'GARMENT PERF: MEMORY AFTER QUERY',
                        [
                            'rows' =>
                                $rows->count(),

                            'memory_mb' =>
                                round(
                                    memory_get_usage(true) / 1024 / 1024,
                                    2
                                ),

                            'peak_memory_mb' =>
                                round(
                                    memory_get_peak_usage(true) / 1024 / 1024,
                                    2
                                ),
                        ]
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Canonical Normalization
                    |--------------------------------------------------------------------------
                    */

                    $dataset =
                        $this->normalizeRows(
                            $rows
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Release Query Collection
                    |--------------------------------------------------------------------------
                    */

                    unset($rows);


                    /*
                    |--------------------------------------------------------------------------
                    | Memory After Normalize
                    |--------------------------------------------------------------------------
                    */

                    \Log::info(
                        'GARMENT PERF: MEMORY AFTER NORMALIZE',
                        [
                            'rows' =>
                                count($dataset),

                            'memory_mb' =>
                                round(
                                    memory_get_usage(true) / 1024 / 1024,
                                    2
                                ),

                            'peak_memory_mb' =>
                                round(
                                    memory_get_peak_usage(true) / 1024 / 1024,
                                    2
                                ),
                        ]
                    );


                    return $dataset;
                }
            );


        /*
        |--------------------------------------------------------------------------
        | Canonical Dataset Cache
        |--------------------------------------------------------------------------
        */

        \Log::info(
            'GARMENT PERF: CANONICAL DATASET CACHE',
            [
                'cache_key' =>
                    $cacheKey,

                'year' =>
                    $year,

                'month_start' =>
                    $monthStart,

                'month_end' =>
                    $monthEnd,

                'mode' =>
                    $mode,

                'rows' =>
                    count($dataset),

                'time_ms' =>
                    round(
                        (microtime(true) - $startedAt) * 1000,
                        2
                    ),
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Canonical Collection
        |--------------------------------------------------------------------------
        |
        | One Collection wrapper is created around the canonical
        | dataset and shared by all Current/* builders.
        |
        | IMPORTANT:
        |
        | collect() does not duplicate every row here.
        | The collection references the existing dataset array.
        |
        */

        $startedAt = microtime(true);

        $collection =
            collect(
                $dataset
            );


        \Log::info(
            'GARMENT PERF: COLLECTION',
            [
                'year' =>
                    $year,

                'rows' =>
                    $collection->count(),

                'time_ms' =>
                    round(
                        (microtime(true) - $startedAt) * 1000,
                        2
                    ),
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Current Intelligence
        |--------------------------------------------------------------------------
        |
        | All builders receive the same canonical collection.
        |
        | No business logic is changed here.
        |
        */


        /*
        |--------------------------------------------------------------------------
        | Top Import Products
        |--------------------------------------------------------------------------
        */

        $startedAt = microtime(true);

        $topImportProducts =
            $this->productBuilder->build(
                $collection,
                'import'
            );

        \Log::info(
            'GARMENT PERF: TOP IMPORT PRODUCTS',
            [
                'year' =>
                    $year,

                'time_ms' =>
                    round(
                        (microtime(true) - $startedAt) * 1000,
                        2
                    ),
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Top Export Products
        |--------------------------------------------------------------------------
        */

        $startedAt = microtime(true);

        $topExportProducts =
            $this->productBuilder->build(
                $collection,
                'export'
            );

        \Log::info(
            'GARMENT PERF: TOP EXPORT PRODUCTS',
            [
                'year' =>
                    $year,

                'time_ms' =>
                    round(
                        (microtime(true) - $startedAt) * 1000,
                        2
                    ),
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Top Import Origins
        |--------------------------------------------------------------------------
        */

        $startedAt = microtime(true);

        $topImportOrigins =
            $this->countryBuilder->buildTopCountries(
                $collection,
                'import'
            );

        \Log::info(
    'GARMENT DEBUG: TOP IMPORT ORIGINS OUTPUT',
    [
        'rows' =>
            count($topImportOrigins),

        'sample' =>
            array_slice(
                $topImportOrigins,
                0,
                3
            ),
    ]
);


        /*
        |--------------------------------------------------------------------------
        | Top Export Destinations
        |--------------------------------------------------------------------------
        */

        $startedAt = microtime(true);

        $topExportDestinations =
            $this->countryBuilder->buildTopCountries(
                $collection,
                'export'
            );

       \Log::info(
    'GARMENT DEBUG: TOP EXPORT DESTINATIONS OUTPUT',
    [
        'rows' =>
            count($topExportDestinations),

        'sample' =>
            array_slice(
                $topExportDestinations,
                0,
                3
            ),
    ]
);


        /*
        |--------------------------------------------------------------------------
        | Import Market Share
        |--------------------------------------------------------------------------
        */

        $startedAt = microtime(true);

        $importMarketShare =
            $this->countryBuilder->buildMarketShare(
                $collection,
                'import'
            );

        \Log::info(
            'GARMENT PERF: IMPORT MARKET SHARE',
            [
                'year' =>
                    $year,

                'time_ms' =>
                    round(
                        (microtime(true) - $startedAt) * 1000,
                        2
                    ),
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Export Market Share
        |--------------------------------------------------------------------------
        */

        $startedAt = microtime(true);

        $exportMarketShare =
            $this->countryBuilder->buildMarketShare(
                $collection,
                'export'
            );

        \Log::info(
            'GARMENT PERF: EXPORT MARKET SHARE',
            [
                'year' =>
                    $year,

                'time_ms' =>
                    round(
                        (microtime(true) - $startedAt) * 1000,
                        2
                    ),
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Monthly Trend
        |--------------------------------------------------------------------------
        */

        $startedAt = microtime(true);

        $monthlyTrend =
            $this->trendBuilder->buildMonthly(
                $collection
            );
\Log::info(
    'GARMENT DEBUG: MONTHLY TREND SAMPLE',
    [
        'year' => $year,

        'rows' =>
            is_countable($monthlyTrend)
                ? count($monthlyTrend)
                : null,

        'sample' =>
            is_array($monthlyTrend)
                ? array_slice(
                    $monthlyTrend,
                    0,
                    2
                )
                : null,
    ]
);

        \Log::info(
            'GARMENT PERF: MONTHLY TREND',
            [
                'year' =>
                    $year,

                'time_ms' =>
                    round(
                        (microtime(true) - $startedAt) * 1000,
                        2
                    ),

                 'rows' =>
                is_countable($monthlyTrend)
                    ? count($monthlyTrend)
                    : null,
                        ]
        );


        /*
        |--------------------------------------------------------------------------
        | Yearly Trend
        |--------------------------------------------------------------------------
        */

        $startedAt = microtime(true);

        $yearlyTrend =
            $this->trendBuilder->buildYearly(
                $collection
            );

        \Log::info(
            'GARMENT PERF: YEARLY TREND',
            [
                'year' =>
                    $year,

                'time_ms' =>
                    round(
                        (microtime(true) - $startedAt) * 1000,
                        2
                    ),
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | HS-8 Intelligence
        |--------------------------------------------------------------------------
        */

        $startedAt = microtime(true);

        $hs8Products =
            $this->hs8Builder->build(
                $collection
            );

        \Log::info(
            'GARMENT PERF: HS8 PRODUCTS',
            [
                'year' =>
                    $year,

                'time_ms' =>
                    round(
                        (microtime(true) - $startedAt) * 1000,
                        2
                    ),
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Release Collection Wrapper
        |--------------------------------------------------------------------------
        |
        | All Current/* builders have finished consuming the shared
        | canonical collection.
        |
        | We deliberately keep $dataset because it is part of the
        | canonical return contract.
        |
        */

        unset($collection);


        /*
        |--------------------------------------------------------------------------
        | Return Dataset
        |--------------------------------------------------------------------------
        */

        return [

            /*
            |--------------------------------------------------------------------------
            | Canonical Dataset
            |--------------------------------------------------------------------------
            */

            'dataset' =>
                $dataset,


            /*
            |--------------------------------------------------------------------------
            | Period Identity
            |--------------------------------------------------------------------------
            */

            'year' =>
                $year,

            'month_start' =>
                $monthStart,

            'month_end' =>
                $monthEnd,

            'mode' =>
                $mode,


            /*
            |--------------------------------------------------------------------------
            | Top Products
            |--------------------------------------------------------------------------
            */

            'top_import_products' =>
                $topImportProducts,

            'top_export_products' =>
                $topExportProducts,


            /*
            |--------------------------------------------------------------------------
            | Top Countries
            |--------------------------------------------------------------------------
            */

            'top_import_origins' =>
                $topImportOrigins,

            'top_export_destinations' =>
                $topExportDestinations,


            /*
            |--------------------------------------------------------------------------
            | Country Market Share
            |--------------------------------------------------------------------------
            */

            'import_market_share' =>
                $importMarketShare,

            'export_market_share' =>
                $exportMarketShare,


            /*
            |--------------------------------------------------------------------------
            | Trends
            |--------------------------------------------------------------------------
            */

            'monthly_trend' =>
                $monthlyTrend,

            'yearly_trend' =>
                $yearlyTrend,


            /*
            |--------------------------------------------------------------------------
            | HS-8 Intelligence
            |--------------------------------------------------------------------------
            */

            'hs8_products' =>
                $hs8Products,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Resolve Month Window
    |--------------------------------------------------------------------------
    */

    protected function resolveMonthWindow(
        int $throughMonth,
        string $mode
    ): array {

        $throughMonth =
            max(
                1,
                min(
                    12,
                    $throughMonth
                )
            );


        /*
        |--------------------------------------------------------------------------
        | Monthly
        |--------------------------------------------------------------------------
        */

        if (
            $mode === 'monthly'
        ) {
            return [
                $throughMonth,
                $throughMonth,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Full Year
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $mode,
                [
                    'full_year',
                    'yearly',
                    'annual',
                ],
                true
            )
        ) {
            return [
                1,
                12,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | YTD / Default
        |--------------------------------------------------------------------------
        */

        return [
            1,
            $throughMonth,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Query Current Period
    |--------------------------------------------------------------------------
    |
    | This is the critical acquisition path.
    |
    | trade_statistics is NEVER queried without the canonical
    | garment HS-8 restriction.
    |
    */

    protected function queryRows(
        array $columns,
        array $sectorHsCodes,
        int $year,
        int $monthStart,
        int $monthEnd
    ): Collection {

        return DB::table(
            'trade_statistics'
        )
            ->whereIn(
                $columns['hs_code'],
                $sectorHsCodes
            )
            ->where(
                $columns['year'],
                $year
            )
            ->whereBetween(
                $columns['month'],
                [
                    $monthStart,
                    $monthEnd,
                ]
            )
            ->select([
                $columns['year']
                    . ' as year',

                $columns['month']
                    . ' as month',

                $columns['hs_code']
                    . ' as hs_code',

                $columns['hs_description']
                    . ' as description',

                $columns['flow']
                    . ' as flow',

                $columns['country']
                    . ' as country',
            ])
            ->selectRaw(
                'SUM(' .
                $columns['trade_value'] .
                ') AS value'
            )
            ->selectRaw(
                'SUM(' .
                $columns['trade_volume'] .
                ') AS volume'
            )
            ->groupBy([
                $columns['year'],
                $columns['month'],
                $columns['hs_code'],
                $columns['hs_description'],
                $columns['flow'],
                $columns['country'],
            ])
            ->orderBy(
                $columns['year']
            )
            ->orderBy(
                $columns['month']
            )
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | Normalize Rows
    |--------------------------------------------------------------------------
    |
    | Establish the canonical trade row contract before the
    | Current/* builders consume the dataset.
    |
    */

   protected function normalizeRows(
    Collection $rows
): array {

    /*
    |--------------------------------------------------------------------------
    | Country resolution cache
    |--------------------------------------------------------------------------
    |
    | Many trade rows share the same country name.
    | Resolve each distinct source country only once.
    |
    */

    $countryCache = [];

    return $rows
        ->map(
            function ($row) use (&$countryCache): array {

                $flow =
                    $this->flowNormalizer->normalize(
                        $row->flow
                            ?? null
                    );

                $value =
                    $this->toFloat(
                        $row->value
                            ?? 0
                    );

                $volume =
                    $this->toFloat(
                        $row->volume
                            ?? 0
                    );

                /*
                |--------------------------------------------------------------------------
                | Country
                |--------------------------------------------------------------------------
                */

                $sourceCountry =
                    $row->country
                        ?? null;

                $countryKey =
                    $this->countryResolver->normalize(
                        $sourceCountry
                    );

                if (
                    $countryKey !== ''
                    && !array_key_exists(
                        $countryKey,
                        $countryCache
                    )
                ) {
                    $countryCache[$countryKey] =
                        $this->countryResolver->resolve(
                            $sourceCountry,
                            'KEMENDAG'
                        );
                }

                $country =
                    $countryCache[$countryKey]
                        ?? null;

                /*
                |--------------------------------------------------------------------------
                | Canonical country contract
                |--------------------------------------------------------------------------
                */

                return [

                    /*
                    |--------------------------------------------------------------------------
                    | Period
                    |--------------------------------------------------------------------------
                    */

                    'year' =>
                        (int) (
                            $row->year
                            ?? 0
                        ),

                    'month' =>
                        (int) (
                            $row->month
                            ?? 0
                        ),

                    /*
                    |--------------------------------------------------------------------------
                    | HS-8
                    |--------------------------------------------------------------------------
                    */

                    'hs_code' =>
                        (string) (
                            $row->hs_code
                            ?? ''
                        ),

                    'description' =>
                        $row->description
                            ?? null,

                    /*
                    |--------------------------------------------------------------------------
                    | Country
                    |--------------------------------------------------------------------------
                    |
                    | Keep the original source value for traceability,
                    | but expose the canonical country fields required
                    | by Trade Intelligence / frontend.
                    |
                    */

                    'country' =>
                        $sourceCountry,

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

                    /*
                    |--------------------------------------------------------------------------
                    | Flow
                    |--------------------------------------------------------------------------
                    */

                    'flow' =>
                        $flow,

                    /*
                    |--------------------------------------------------------------------------
                    | Official Trade Metrics
                    |--------------------------------------------------------------------------
                    |
                    | trade_volume remains authoritative.
                    |
                    */

                    'value' =>
                        $value,

                    'trade_value' =>
                        $value,

                    'volume' =>
                        $volume,

                    'trade_volume' =>
                        $volume,

                    'volume_unit' =>
                        'KG',
                ];
            }
        )
        ->filter(
            fn (array $row): bool =>
                $row['hs_code'] !== ''
                &&
                $row['flow'] !== null
        )
        ->values()
        ->all();
}

    /*
    |--------------------------------------------------------------------------
    | Canonical Garment HS-8 Universe
    |--------------------------------------------------------------------------
    |
    | The canonical list comes exclusively from
    | TextileTaxonomyService.
    |
    | This is the same acquisition principle used by the
    | pre-refactor GarmentTradeIntelligenceService.
    |
    */

    protected function sectorHsCodes(): array
    {

        if (
            $this->cachedSectorHsCodes !== null
        ) {
            return $this->cachedSectorHsCodes;
        }


        $codes =
            $this->taxonomy->hsCodesForSector(
                self::SECTOR
            );


        /*
        |--------------------------------------------------------------------------
        | Normalize HS-8 Values
        |--------------------------------------------------------------------------
        */

        $normalized =
            collect(
                is_array($codes)
                    ? $codes
                    : []
            )
                ->map(
                    static function ($code): string {

                        return preg_replace(
                            '/\D+/',
                            '',
                            (string) $code
                        ) ?? '';
                    }
                )
                ->filter(
                    static function (string $code): bool {

                        return strlen($code) === 8;
                    }
                )
                ->unique()
                ->values()
                ->all();


        $this->cachedSectorHsCodes =
            $normalized;


        return $this->cachedSectorHsCodes;
    }


    /*
    |--------------------------------------------------------------------------
    | Empty Dataset
    |--------------------------------------------------------------------------
    */

    protected function emptyDataset(
        int $year,
        int $monthStart,
        int $monthEnd,
        string $mode
    ): array {

        $empty =
            collect();


return [

    'dataset' => [],

    'year' => $year,

    'month_start' => $monthStart,

    'month_end' => $monthEnd,

    'mode' => $mode,

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
    | Shared Canonical Dataset Cache
    |--------------------------------------------------------------------------
    |
    | Cache hanya berlaku untuk Current / YTD / Monthly dataset.
    |
    | Historical 2019–2024 TIDAK melewati builder ini.
    |
    */

   protected function canonicalDatasetCacheKey(
    int $year,
    int $monthStart,
    int $monthEnd,
    string $mode
): string {
    return sprintf(
        'trade_intelligence:garment:canonical:%04d:%02d-%02d:%s:v2',
        $year,
        $monthStart,
        $monthEnd,
        $mode,
    );
    }
}