<?php

declare(strict_types=1);

namespace App\Services\TradeIntelligence\Period;

use App\Services\TradeIntelligence\Current\CurrentPeriodDatasetBuilder;
use App\Services\TradeIntelligence\Current\CurrentOverviewBuilder;
use App\Services\TradeIntelligence\Historical\HistoricalYearlyDatasetBuilder;
use App\Services\TradeIntelligence\Current\CurrentFlowBuilder;
use App\Services\TradeIntelligence\Current\CurrentSubsectorBuilder;
use App\Services\Trade\TradeReportingPeriod;

class TradePeriodDatasetBuilder
{
    /*
    |--------------------------------------------------------------------------
    | Dataset Format
    |--------------------------------------------------------------------------
    */

    protected const DATASET_FORMAT =
        'period_datasets_v1';


    /*
    |--------------------------------------------------------------------------
    | Historical Year Range
    |--------------------------------------------------------------------------
    |
    | The dedicated historical yearly pipeline is used for the
    | completed historical years supported by the historical
    | intelligence layer.
    |
    */

    protected const HISTORICAL_YEAR_MIN = 2019;

    protected const HISTORICAL_YEAR_MAX = 2024;


    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct(
        protected CurrentPeriodDatasetBuilder $currentBuilder,
        protected HistoricalYearlyDatasetBuilder $historicalBuilder,
        protected CurrentOverviewBuilder $overviewBuilder,
        protected CurrentFlowBuilder $flowBuilder,
        protected CurrentSubsectorBuilder $subsectorBuilder,
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | Build
    |--------------------------------------------------------------------------
    |
    | Central period orchestration.
    |
    | This class owns:
    |
    | - current/comparison period resolution
    | - period descriptors
    | - period_datasets
    | - trend_rows
    |
    | This class does NOT own:
    |
    | - database acquisition
    | - HS taxonomy
    | - overview
    | - products
    | - countries
    | - snapshot metadata
    | - validation
    | - fallback
    |
    */

    public function build(
        array $snapshot,
        TradeReportingPeriod $period
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Existing Canonical Period Datasets
        |--------------------------------------------------------------------------
        */

        $existingDatasets =
            $this->existingPeriodDatasets(
                $snapshot
            );


        /*
        |--------------------------------------------------------------------------
        | Current / Comparison Requests
        |--------------------------------------------------------------------------
        */

        $requests =
            $this->periodDatasetRequests(
                $period
            );


        /*
        |--------------------------------------------------------------------------
        | Current Dataset
        |--------------------------------------------------------------------------
        */

        $currentDescriptor =
            $requests['current']['descriptor'];

        $currentDataset =
            $this->resolveDataset(
                period:
                    $period,

                descriptor:
                    $currentDescriptor,

                request:
                    $requests['current'],

                existingDatasets:
                    $existingDatasets,
            );


        /*
        |--------------------------------------------------------------------------
        | Comparison Dataset
        |--------------------------------------------------------------------------
        */

        $comparisonDescriptor =
            $requests['comparison']['descriptor'];

        /*
        |--------------------------------------------------------------------------
        | Same Descriptor
        |--------------------------------------------------------------------------
        |
        | If current and comparison identify exactly the same period,
        | there is no reason to build the same dataset twice.
        |
        */

        if (
            $comparisonDescriptor ===
            $currentDescriptor
        ) {
            $comparisonDataset =
                $currentDataset;
        } else {
            $comparisonDataset =
                $this->resolveDataset(
                    period:
                        $period,

                    descriptor:
                        $comparisonDescriptor,

                    request:
                        $requests['comparison'],

                    existingDatasets:
                        $existingDatasets,
                );
        }
        /*
        |--------------------------------------------------------------------------
        | Executive Overview
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | CurrentPeriodDatasetBuilder builds ONE period only.
        | Therefore the executive overview must be assembled here,
        | where both current and comparison datasets are available.
        |
        */

        $startedAt = microtime(true);

        $overview =
            $this->overviewBuilder->build(
                collect(
                    $currentDataset['dataset'] ?? []
                ),
                collect(
                    $comparisonDataset['dataset'] ?? []
                ),
            );

        \Log::info(
            'GARMENT PERF: PERIOD OVERVIEW',
            [
                'current_descriptor' =>
                    $currentDescriptor,

                'comparison_descriptor' =>
                    $comparisonDescriptor,

                'time_ms' =>
                    round(
                        (microtime(true) - $startedAt) * 1000,
                        2
                    ),
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Flow Performance
        |--------------------------------------------------------------------------
        |
        | Flow comparison requires both current and comparison
        | period datasets.
        |
        */

        $startedAt = microtime(true);

        $flow =
            $this->flowBuilder->build(
                collect(
                    $currentDataset['dataset'] ?? []
                ),
                collect(
                    $comparisonDataset['dataset'] ?? []
                ),
            );



        \Log::info(
            'GARMENT PERF: PERIOD FLOW',
            [
                'current_descriptor' =>
                    $currentDescriptor,

                'comparison_descriptor' =>
                    $comparisonDescriptor,

                'time_ms' =>
                    round(
                        (microtime(true) - $startedAt) * 1000,
                        2
                    ),
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Subsector Performance
        |--------------------------------------------------------------------------
        |
        | Subsector comparison requires both current and comparison
        | period datasets.
        |
        */

        $startedAt = microtime(true);

        $bySubsector =
            $this->subsectorBuilder->build(
                collect(
                    $currentDataset['dataset'] ?? []
                ),
                collect(
                    $comparisonDataset['dataset'] ?? []
                ),
            );
/*
|--------------------------------------------------------------------------
| Time Intelligence Projection
|--------------------------------------------------------------------------
|
| CurrentPeriodDatasetBuilder already calculates the trend
| for each individual period.
|
| At the period level, current and comparison trends must
| both be exposed because the frontend renders them side
| by side.
|
*/

$monthlyTrend = array_values(
    array_merge(
        $comparisonDataset['monthly_trend'] ?? [],
        $currentDataset['monthly_trend'] ?? [],
    )
);


$yearlyTrend = array_values(
    array_merge(
        $comparisonDataset['yearly_trend'] ?? [],
        $currentDataset['yearly_trend'] ?? [],
    )
);


$hs8Products =
    $currentDataset['hs8_products']
    ?? [];
    
            
        \Log::info(
            'GARMENT PERF: PERIOD SUBSECTOR',
            [
                'current_descriptor' =>
                    $currentDescriptor,

                'comparison_descriptor' =>
                    $comparisonDescriptor,

                'time_ms' =>
                    round(
                        (microtime(true) - $startedAt) * 1000,
                        2
                    ),
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Period Datasets
        |--------------------------------------------------------------------------
        */

        $periodDatasets = [
            $currentDescriptor =>
                $currentDataset,
        ];

        if (
            $comparisonDescriptor !==
            $currentDescriptor
        ) {
            $periodDatasets[
                $comparisonDescriptor
            ] =
                $comparisonDataset;
        }


        /*
        |--------------------------------------------------------------------------
        | Trend Rows
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | trend_rows contains trade rows, NOT the complete dataset
        | structure.
        |
        */

        $startedAt = microtime(true);

        $trendRows =
            $this->buildTrendRows(
                current:
                    $currentDataset,

                previous:
                    $comparisonDataset,

                samePeriod:
                    $currentDescriptor ===
                    $comparisonDescriptor,
            );

        \Log::info(
            'GARMENT PERF: PERIOD TREND ROWS',
            [
                'current_descriptor' =>
                    $currentDescriptor,

                'comparison_descriptor' =>
                    $comparisonDescriptor,

                'rows' =>
                    count($trendRows),

                'time_ms' =>
                    round(
                        (microtime(true) - $startedAt) * 1000,
                        2
                    ),
            ]
        );

        
        /*
        |--------------------------------------------------------------------------
        | Result
        |--------------------------------------------------------------------------
        */
        \Log::info(
            'GARMENT PERF: PERIOD BUILDER TOTAL',
            [
                'current_descriptor' =>
                    $currentDescriptor,

                'comparison_descriptor' =>
                    $comparisonDescriptor,

                'current_rows' =>
                    count(
                        $currentDataset['dataset'] ?? []
                    ),

                'comparison_rows' =>
                    count(
                        $comparisonDataset['dataset'] ?? []
                    ),

                'trend_rows' =>
                    count($trendRows),
            ]
        );
        

        return [

    /*
    |--------------------------------------------------------------------------
    | Canonical Period Datasets
    |--------------------------------------------------------------------------
    */

    'period_datasets' =>
        $periodDatasets,


    /*
    |--------------------------------------------------------------------------
    | Current
    |--------------------------------------------------------------------------
    */

    'current' =>
        $currentDataset,


    /*
    |--------------------------------------------------------------------------
    | Previous
    |--------------------------------------------------------------------------
    */

    'previous' =>
        $comparisonDataset,


    /*
    |--------------------------------------------------------------------------
    | Executive Overview
    |--------------------------------------------------------------------------
    */

    'overview' =>
        $overview,


    /*
    |--------------------------------------------------------------------------
    | Flow Performance
    |--------------------------------------------------------------------------
    */

    'by_flow' =>
        $flow,


    /*
    |--------------------------------------------------------------------------
    | Subsector Performance
    |--------------------------------------------------------------------------
    */

    'by_subsector' =>
        $bySubsector,


    /*
    |--------------------------------------------------------------------------
    | Top Products
    |--------------------------------------------------------------------------
    */

    'top_import_products' =>
        $currentDataset['top_import_products'] ?? [],

    'top_export_products' =>
        $currentDataset['top_export_products'] ?? [],


    /*
    |--------------------------------------------------------------------------
    | Top Countries
    |--------------------------------------------------------------------------
    */

    'top_import_origins' =>
        $currentDataset['top_import_origins'] ?? [],

    'top_export_destinations' =>
        $currentDataset['top_export_destinations'] ?? [],


    /*
    |--------------------------------------------------------------------------
    | Country Market Share
    |--------------------------------------------------------------------------
    */

    'import_market_share' =>
        $currentDataset['import_market_share'] ?? [],

    'export_market_share' =>
        $currentDataset['export_market_share'] ?? [],


    /*
    |--------------------------------------------------------------------------
    | Trends
    |--------------------------------------------------------------------------
    */

    'monthly_trend' =>
        $currentDataset['monthly_trend'] ?? [],

    'yearly_trend' =>
        $currentDataset['yearly_trend'] ?? [],


    /*
    |--------------------------------------------------------------------------
    | HS-8 Intelligence
    |--------------------------------------------------------------------------
    */

    'hs8_products' =>
        $currentDataset['hs8_products'] ?? [],


    /*
    |--------------------------------------------------------------------------
    | Trend Rows
    |--------------------------------------------------------------------------
    */

    'trend_rows' =>
        $trendRows,


    /*
    |--------------------------------------------------------------------------
    | Period Descriptors
    |--------------------------------------------------------------------------
    */

    'current_descriptor' =>
        $currentDescriptor,

    'comparison_descriptor' =>
        $comparisonDescriptor,


    /*
    |--------------------------------------------------------------------------
    | Dataset Format
    |--------------------------------------------------------------------------
    */

    'dataset_format' =>
        self::DATASET_FORMAT,
];
    }


    /*
    |--------------------------------------------------------------------------
    | Period Dataset Requests
    |--------------------------------------------------------------------------
    |
    | SINGLE SOURCE OF TRUTH for current/comparison dataset identity.
    |
    */

    public function periodDatasetRequests(
        TradeReportingPeriod $period
    ): array {

        return [

            'current' => [

                'descriptor' =>
                    $this->descriptorFor(
                        year:
                            $period->publicThroughYear,

                        throughMonth:
                            $period->publicThroughMonth,

                        mode:
                            $period->mode,
                    ),

                'year' =>
                    $period->publicThroughYear,

                'through_month' =>
                    $period->publicThroughMonth,

                'mode' =>
                    $period->mode,

                'role' =>
                    'current',
            ],


            'comparison' => [

                'descriptor' =>
                    $this->descriptorFor(
                        year:
                            $period->comparisonYear,

                        throughMonth:
                            $period->comparisonThroughMonth,

                        mode:
                            $period->mode,
                    ),

                'year' =>
                    $period->comparisonYear,

                'through_month' =>
                    $period->comparisonThroughMonth,

                'mode' =>
                    $period->mode,

                'role' =>
                    'comparison',
            ],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Descriptor
    |--------------------------------------------------------------------------
    |
    | Canonical format:
    |
    |     YYYY-MM..MM:mode
    |
    | Examples:
    |
    |     2024-01..12:full_year
    |     2026-01..06:ytd
    |     2026-06..06:monthly
    |
    */

    public function descriptorFor(
        int $year,
        int $throughMonth,
        string $mode
    ): string {

        $mode =
            strtolower(
                trim($mode)
            );


        /*
        |--------------------------------------------------------------------------
        | Monthly
        |--------------------------------------------------------------------------
        */

        if (
            $mode === 'monthly'
        ) {
            return sprintf(
                '%04d-%02d..%02d:%s',
                $year,
                $throughMonth,
                $throughMonth,
                $mode,
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Full Year
        |--------------------------------------------------------------------------
        */

        if (
            $mode === 'full_year'
        ) {
            return sprintf(
                '%04d-01..12:%s',
                $year,
                $mode,
            );
        }


        /*
        |--------------------------------------------------------------------------
        | YTD
        |--------------------------------------------------------------------------
        */

        return sprintf(
            '%04d-01..%02d:%s',
            $year,
            $throughMonth,
            $mode,
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Resolve Dataset
    |--------------------------------------------------------------------------
    */

    protected function resolveDataset(
        TradeReportingPeriod $period,
        string $descriptor,
        array $request,
        array $existingDatasets,
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Reuse Existing Dataset
        |--------------------------------------------------------------------------
        |
        | Snapshot data has priority.
        |
        */

        if (
            array_key_exists(
                $descriptor,
                $existingDatasets
            )
        ) {
            return $this->normalizeDataset(
                $existingDatasets[
                    $descriptor
                ]
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Historical Yearly
        |--------------------------------------------------------------------------
        |
        | Completed historical full-year periods use the dedicated
        | historical aggregation pipeline.
        |
        */

        if (
            $this->isHistoricalYearly(
                $request
            )
        ) {
            return $this->normalizeDataset(
                $this->historicalBuilder->build(
                    $period,
                    $request,
                )
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Current / YTD / Monthly
        |--------------------------------------------------------------------------
        */

        return $this->normalizeDataset(
            $this->currentBuilder->build(
                $period,
                $request,
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Historical Yearly
    |--------------------------------------------------------------------------
    */

    protected function isHistoricalYearly(
        array $request
    ): bool {

        $mode =
            strtolower(
                trim(
                    (string) (
                        $request['mode']
                        ?? ''
                    )
                )
            );

        $year =
            (int) (
                $request['year']
                ?? 0
            );


        return
            $mode === 'full_year'
            &&
            $year >=
                self::HISTORICAL_YEAR_MIN
            &&
            $year <=
                self::HISTORICAL_YEAR_MAX;
    }


    /*
    |--------------------------------------------------------------------------
    | Existing Period Datasets
    |--------------------------------------------------------------------------
    */

    protected function existingPeriodDatasets(
        array $snapshot
    ): array {

        $datasets =
            $snapshot['period_datasets']
            ?? [];


        return is_array(
            $datasets
        )
            ? $datasets
            : [];
    }


    /*
    |--------------------------------------------------------------------------
    | Trend Rows
    |--------------------------------------------------------------------------
    |
    | Extract only the canonical raw trade rows from each dataset.
    |
    */

    protected function buildTrendRows(
        array $current,
        array $previous,
        bool $samePeriod
    ): array {

        $currentRows =
            $this->datasetRows(
                $current
            );


        /*
        |--------------------------------------------------------------------------
        | Same Period
        |--------------------------------------------------------------------------
        */

        if (
            $samePeriod
        ) {
            return $currentRows;
        }


        /*
        |--------------------------------------------------------------------------
        | Current + Comparison
        |--------------------------------------------------------------------------
        */

        $previousRows =
            $this->datasetRows(
                $previous
            );


        return array_values(
            array_merge(
                $currentRows,
                $previousRows,
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Dataset Rows
    |--------------------------------------------------------------------------
    |
    | CurrentPeriodDatasetBuilder returns:
    |
    |     [
    |         'dataset' => [...],
    |         'overview' => [...],
    |         ...
    |     ]
    |
    | Historical builder may return its own canonical array.
    |
    */

    protected function datasetRows(
        array $dataset
    ): array {

        if (
            isset(
                $dataset['dataset']
            )
            &&
            is_array(
                $dataset['dataset']
            )
        ) {
            return $dataset['dataset'];
        }


        /*
        |--------------------------------------------------------------------------
        | Legacy / Historical Dataset
        |--------------------------------------------------------------------------
        |
        | If the historical builder already returns raw rows,
        | preserve them.
        |
        */

        if (
    $this->looksLikeTradeRows(
        $dataset
            )
        ) {
            return $dataset;
        }


        return [];
    }


    /*
    |--------------------------------------------------------------------------
    | Trade Row Detection
    |--------------------------------------------------------------------------
    */

    protected function looksLikeTradeRows(
        array $dataset
    ): bool {

        if (
            empty($dataset)
        ) {
            return true;
        }


        $first =
            reset(
                $dataset
            );


        if (
            !is_array($first)
        ) {
            return false;
        }


        return
            array_key_exists(
                'hs_code',
                $first
            )
            &&
            (
                array_key_exists(
                    'value',
                    $first
                )
                ||
                array_key_exists(
                    'trade_value',
                    $first
                )
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Dataset Normalization
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    |
    | Do NOT array_values() the complete current dataset.
    |
    | The complete dataset is associative and contains:
    |
    |     dataset
    |     overview
    |     by_subsector
    |     by_flow
    |     ...
    |
    | We must preserve those keys.
    |
    */

    protected function normalizeDataset(
        mixed $dataset
    ): array {

        if (
            !is_array(
                $dataset
            )
        ) {
            return [];
        }


        return $dataset;
    }


    /*
    |--------------------------------------------------------------------------
    | Dataset Format
    |--------------------------------------------------------------------------
    */

    public function datasetFormat(): string
    {
        return self::DATASET_FORMAT;
    }

    
}