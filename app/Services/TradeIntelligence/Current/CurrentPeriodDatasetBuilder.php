<?php

declare(strict_types=1);

namespace App\Services\TradeIntelligence\Current;

use App\Services\Trade\Taxonomy\TextileTaxonomyService;
use App\Services\TradeIntelligence\Support\TradeColumnResolver;
use App\Services\TradeIntelligence\Support\TradeFlowNormalizer;
use App\Services\TradeIntelligence\Support\TradeMetricCalculator;
use App\Services\TradeIntelligence\TradeReportingPeriod;
use Illuminate\Support\Collection;
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
        protected CurrentOverviewBuilder $overviewBuilder,
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
        /*
        |--------------------------------------------------------------------------
        | Resolve Period
        |--------------------------------------------------------------------------
        */

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
        |
        | IMPORTANT:
        |
        | The garment universe comes from the canonical taxonomy
        | layer.
        |
        | We deliberately DO NOT use:
        |
        |     hs_code LIKE '61%'
        |     hs_code LIKE '62%'
        |     chapter filters
        |
        | The database query must be restricted to the exact
        | canonical garment HS-8 list.
        |
        */

        $sectorHsCodes =
            $this->sectorHsCodes();


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
        | Acquire Current Period Rows
        |--------------------------------------------------------------------------
        |
        | ONE aggregated query.
        |
        | The query is restricted by:
        |
        |     canonical garment HS-8
        |     selected year
        |     selected month window
        |
        */

        $rows =
            $this->queryRows(
                columns: $columns,
                sectorHsCodes: $sectorHsCodes,
                year: $year,
                monthStart: $monthStart,
                monthEnd: $monthEnd,
            );


        /*
        |--------------------------------------------------------------------------
        | Normalize Trade Rows
        |--------------------------------------------------------------------------
        */

        $dataset =
            $this->normalizeRows(
                $rows
            );


        /*
        |--------------------------------------------------------------------------
        | Canonical Collection
        |--------------------------------------------------------------------------
        */

        $collection =
            collect(
                $dataset
            );


        /*
        |--------------------------------------------------------------------------
        | Current Intelligence
        |--------------------------------------------------------------------------
        |
        | Every Current/* builder works against the same canonical
        | period collection.
        |
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
            | Executive Overview
            |--------------------------------------------------------------------------
            */

            'overview' =>
                $this->overviewBuilder->build(
                    $collection
                ),


            /*
            |--------------------------------------------------------------------------
            | Subsector
            |--------------------------------------------------------------------------
            */

            'by_subsector' =>
                $this->subsectorBuilder->build(
                    $collection
                ),


            /*
            |--------------------------------------------------------------------------
            | Flow
            |--------------------------------------------------------------------------
            */

            'by_flow' =>
                $this->flowBuilder->build(
                    $collection
                ),


            /*
            |--------------------------------------------------------------------------
            | Top Products
            |--------------------------------------------------------------------------
            */

            'top_import_products' =>
                $this->productBuilder->build(
                    $collection,
                    'import'
                ),

            'top_export_products' =>
                $this->productBuilder->build(
                    $collection,
                    'export'
                ),


            /*
            |--------------------------------------------------------------------------
            | Top Countries
            |--------------------------------------------------------------------------
            */

            'top_import_origins' =>
                $this->countryBuilder->buildTopCountries(
                    $collection,
                    'import'
                ),

            'top_export_destinations' =>
                $this->countryBuilder->buildTopCountries(
                    $collection,
                    'export'
                ),


            /*
            |--------------------------------------------------------------------------
            | Country Market Share
            |--------------------------------------------------------------------------
            */

            'import_market_share' =>
                $this->countryBuilder->buildMarketShare(
                    $collection,
                    'import'
                ),

            'export_market_share' =>
                $this->countryBuilder->buildMarketShare(
                    $collection,
                    'export'
                ),


            /*
            |--------------------------------------------------------------------------
            | Trends Inside This Period
            |--------------------------------------------------------------------------
            |
            | These are trends contained inside this single period.
            |
            | Comparison-period concatenation is NOT performed here.
            |
            */

            'monthly_trend' =>
                $this->trendBuilder->monthly(
                    $collection
                ),

            'yearly_trend' =>
                $this->trendBuilder->yearly(
                    $collection
                ),


            /*
            |--------------------------------------------------------------------------
            | HS-8 Intelligence
            |--------------------------------------------------------------------------
            */

            'hs8_products' =>
                $this->hs8Builder->build(
                    $collection
                ),
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
        return $rows
            ->map(
                function ($row): array {

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
                        */

                        'country' =>
                            $row->country
                                ?? null,


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

            'year' =>
                $year,

            'month_start' =>
                $monthStart,

            'month_end' =>
                $monthEnd,

            'mode' =>
                $mode,

            'overview' =>
                $this->overviewBuilder->build(
                    $empty
                ),

            'by_subsector' =>
                [],

            'by_flow' =>
                [],

            'top_import_products' =>
                [],

            'top_export_products' =>
                [],

            'top_import_origins' =>
                [],

            'top_export_destinations' =>
                [],

            'import_market_share' =>
                [],

            'export_market_share' =>
                [],

            'monthly_trend' =>
                [],

            'yearly_trend' =>
                [],

            'hs8_products' =>
                [],
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
}