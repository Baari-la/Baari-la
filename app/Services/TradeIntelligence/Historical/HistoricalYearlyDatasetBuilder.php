<?php

namespace App\Services\TradeIntelligence\Historical;

use App\Services\Trade\TradeReportingPeriod;

class HistoricalYearlyDatasetBuilder
{
    /*
    |--------------------------------------------------------------------------
    | Historical Year Range
    |--------------------------------------------------------------------------
    */

    protected const MIN_YEAR = 2019;

    protected const MAX_YEAR = 2024;


    /*
    |--------------------------------------------------------------------------
    | Dependencies
    |--------------------------------------------------------------------------
    */

    public function __construct(
        protected HistoricalYearlyQueryBuilder $queryBuilder,
        protected HistoricalYearlyCountryBuilder $countryBuilder,
        protected HistoricalYearlyValidator $validator,
        protected HistoricalYearlyOverviewBuilder $overviewBuilder,
        
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | Supports
    |--------------------------------------------------------------------------
    */

    public function supports(
        TradeReportingPeriod $period
    ): bool {
        return
            $period->publicThroughYear >= self::MIN_YEAR
            &&
            $period->publicThroughYear <= self::MAX_YEAR;
    }


    /*
    |--------------------------------------------------------------------------
    | Build Historical Yearly Dataset
    |--------------------------------------------------------------------------
    */

    public function build(
        TradeReportingPeriod $period,
        string $sector,
        string $cacheKey,
        string $snapshotType,
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Execute Historical Queries
        |--------------------------------------------------------------------------
        */

        $queryResult =
            $this->queryBuilder->execute(
                $period
            );


        /*
        |--------------------------------------------------------------------------
        | Extract Query Results
        |--------------------------------------------------------------------------
        */

        $historicalYearlySummary =
            $queryResult['summary'] ?? collect();

        $historicalYearlyCountry =
            $queryResult['country'] ?? collect();


        /*
        |--------------------------------------------------------------------------
        | Build Yearly Trend
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | This is intentionally the original business logic.
        |
        | Historical yearly means the TOTAL for each calendar year.
        | It does not build twelve monthly records for every year.
        |
        */

        $historicalYearlyTrend = [];

        foreach (
            $historicalYearlySummary as $row
        ) {

            $year =
                (int) $row->trade_year;

            $flow =
                strtolower(
                    trim(
                        (string) $row->trade_flow
                    )
                );


            if (
                !isset(
                    $historicalYearlyTrend[$year]
                )
            ) {

                $historicalYearlyTrend[$year] = [

                    'year' =>
                        $year,

                    'import' => [

                        'trade_value' =>
                            0,

                        'trade_volume' =>
                            0,
                    ],

                    'export' => [

                        'trade_value' =>
                            0,

                        'trade_volume' =>
                            0,
                    ],
                ];
            }


            /*
            |--------------------------------------------------------------------------
            | Ignore Unknown Flow
            |--------------------------------------------------------------------------
            */

            if (
                !in_array(
                    $flow,
                    [
                        'export',
                        'import',
                    ],
                    true
                )
            ) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Assign Trade Metrics
            |--------------------------------------------------------------------------
            */

            $historicalYearlyTrend[$year][$flow] = [

                'trade_value' =>
                    (float) $row->trade_value,

                'trade_volume' =>
                    (float) $row->trade_volume,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Convert Year-Keyed Map To Sequential Array
        |--------------------------------------------------------------------------
        */

        $historicalYearlyTrend =
            array_values(
                $historicalYearlyTrend
            );

/*
|--------------------------------------------------------------------------
| Build Executive Overview
|--------------------------------------------------------------------------
|
| Historical yearly overview is calculated directly from
| the completed yearly trend.
|
| IMPORTANT:
|
| This is yearly data:
|
|     2019 total
|     2020 total
|     ...
|     2024 total
|
| It does NOT rebuild twelve monthly records.
|
*/

$overview =
    $this->overviewBuilder->build(
        $historicalYearlyTrend,
        $period
    );
        /*
        |--------------------------------------------------------------------------
        | Build Historical Country Input
        |--------------------------------------------------------------------------
        |
        | HistoricalYearlyQueryBuilder returns a Laravel Collection.
        |
        | HistoricalYearlyCountryBuilder intentionally receives the
        | normalized yearly array structure expected by its contract:
        |
        | [
        |     2019 => [
        |         'import' => [...],
        |         'export' => [...],
        |     ],
        |     ...
        | ]
        |
        | Therefore the query result must be grouped here before
        | delegation.
        |
        */

        $countriesByYear = [];


        foreach (
            $historicalYearlyCountry as $row
        ) {

            $year =
                (int) $row->trade_year;

            $flow =
                strtolower(
                    trim(
                        (string) $row->trade_flow
                    )
                );


            /*
            |--------------------------------------------------------------------------
            | Ignore Invalid Year
            |--------------------------------------------------------------------------
            */

            if ($year <= 0) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Ignore Unknown Flow
            |--------------------------------------------------------------------------
            */

            if (
                !in_array(
                    $flow,
                    [
                        'export',
                        'import',
                    ],
                    true
                )
            ) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Initialize Year
            |--------------------------------------------------------------------------
            */

            if (
                !isset(
                    $countriesByYear[$year]
                )
            ) {

                $countriesByYear[$year] = [

                    'import' => [],

                    'export' => [],
                ];
            }


            /*
            |--------------------------------------------------------------------------
            | Normalize Country Row
            |--------------------------------------------------------------------------
            |
            | Preserve the values returned by the original query.
            |
            */

            $countriesByYear[$year][$flow][] = [

                'country' =>
                    trim(
                        (string) (
                            $row->country
                            ?? ''
                        )
                    ),

                'trade_value' =>
                    (float) $row->trade_value,

                'trade_volume' =>
                    (float) $row->trade_volume,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Stable Numeric Year Ordering
        |--------------------------------------------------------------------------
        */

        ksort(
            $countriesByYear,
            SORT_NUMERIC
        );


        /*
        |--------------------------------------------------------------------------
        | Build Country Intelligence
        |--------------------------------------------------------------------------
        */

        $countryIntelligence =
            $this->countryBuilder->build(
                $countriesByYear
            );


        $majorImportSources =
            $countryIntelligence[
                'major_import_sources'
            ] ?? [];

        $majorExportDestinations =
            $countryIntelligence[
                'major_export_destinations'
            ] ?? [];


        /*
        |--------------------------------------------------------------------------
        | Historical Validation
        |--------------------------------------------------------------------------
        */

        $historicalValidation =
            $this->validator->validate(
                yearlyTrend:
                    $historicalYearlyTrend,

                majorExportDestinations:
                    $majorExportDestinations,

                majorImportSources:
                    $majorImportSources,
            );


        /*
        |--------------------------------------------------------------------------
        | Record Count
        |--------------------------------------------------------------------------
        |
        | Keep the original query-result count.
        |
        */

        $historicalRecordCount =
            $historicalYearlyCountry->count();


        /*
        |--------------------------------------------------------------------------
        | Assemble Dataset
        |--------------------------------------------------------------------------
        */

       return $this->assemble(
    period:
        $period,

    sector:
        $sector,

    cacheKey:
        $cacheKey,

    snapshotType:
        $snapshotType,

    yearlyTrend:
        $historicalYearlyTrend,

    majorExportDestinations:
        $majorExportDestinations,

    majorImportSources:
        $majorImportSources,

    overview:
        $overview,

    historicalValidation:
        $historicalValidation,

    recordCount:
        $historicalRecordCount,
);
    }


    /*
    |--------------------------------------------------------------------------
    | Assemble Historical Dataset
    |--------------------------------------------------------------------------
    */

    protected function assemble(
    TradeReportingPeriod $period,
    string $sector,
    string $cacheKey,
    string $snapshotType,
    array $yearlyTrend,
    array $majorExportDestinations,
    array $majorImportSources,
    array $overview,
    array $historicalValidation,
    int $recordCount,
): array {

        $currentYear =
            (int) $period->publicThroughYear;


        return [

            /*
            |--------------------------------------------------------------------------
            | Meta
            |--------------------------------------------------------------------------
            */

            'meta' => [

                'sector' =>
                    $sector,

                'snapshot_key' =>
                    $cacheKey,

                'snapshot_type' =>
                    $snapshotType,

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
                    $period->comparisonPeriodLabelEn(),

                'comparison_period_label_id' =>
                    $period->comparisonPeriodLabelId(),

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

                'buffer_period' =>
                    $period->bufferPeriod(),

                'buffer_status' =>
                    $period->status,

                'data_status' =>
                    $this->dataStatus(
                        $period
                    ),

                'generated_at' =>
                    now(),

                'record_count' =>
                    $recordCount,

                'snapshot_period_key' =>
                    $period->snapshotKey(),

                'hs_codes' =>
                    [],
            ],


            /*
            |--------------------------------------------------------------------------
            | Historical Yearly Intelligence
            |--------------------------------------------------------------------------
            */

            'yearly_trend' =>
                $yearlyTrend,

            'major_export_destinations' =>
                $majorExportDestinations,

            'major_import_sources' =>
                $majorImportSources,


            /*
            |--------------------------------------------------------------------------
            | Historical Validation
            |--------------------------------------------------------------------------
            */

            'historical_validation' =>
                $historicalValidation,


            /*
            |--------------------------------------------------------------------------
            | Executive Overview
            |--------------------------------------------------------------------------
            */

            'overview' => $overview,


            /*
            |--------------------------------------------------------------------------
            | Current / Previous
            |--------------------------------------------------------------------------
            */

            'current' =>
                [],

            'previous' =>
                [],

            'dataset' =>
                [],


            /*
            |--------------------------------------------------------------------------
            | Historical Detail Placeholders
            |--------------------------------------------------------------------------
            */

            'by_subsector' =>
                [],

            'by_flow' =>
                [],

            'top_import_products' =>
                [],

            'top_export_products' =>
                [],


            /*
            |--------------------------------------------------------------------------
            | Current-Year Country Cards
            |--------------------------------------------------------------------------
            */

            'top_import_origins' =>
                $majorImportSources[
                    $currentYear
                ] ?? [],

            'top_export_destinations' =>
                $majorExportDestinations[
                    $currentYear
                ] ?? [],


            /*
            |--------------------------------------------------------------------------
            | Market Share
            |--------------------------------------------------------------------------
            */

            'import_market_share' =>
                [],

            'export_market_share' =>
                [],


            /*
            |--------------------------------------------------------------------------
            | Monthly Intelligence
            |--------------------------------------------------------------------------
            */

            'monthly_trend' =>
                [],


            /*
            |--------------------------------------------------------------------------
            | HS-8 Product Intelligence
            |--------------------------------------------------------------------------
            */

            'hs8_products' =>
                [],
        ];
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
}