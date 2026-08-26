<?php

namespace App\Services\TradeIntelligence\Historical;

use App\Services\Trade\TradeReportingPeriod;

class HistoricalYearlyOverviewBuilder
{
    /*
    |--------------------------------------------------------------------------
    | Build Historical Yearly Overview
    |--------------------------------------------------------------------------
    |
    | Source:
    |
    |     yearly_trend
    |
    | Historical yearly means the TOTAL trade value and
    | physical volume for each calendar year.
    |
    | This builder does NOT:
    |
    | - query database
    | - aggregate countries
    | - build monthly trend
    | - calculate HS-8 products
    | - modify official trade values
    |
    */

    public function build(
        array $yearlyTrend,
        TradeReportingPeriod $period
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Resolve Current / Comparison Year
        |--------------------------------------------------------------------------
        */

        $currentYear =
            (int) $period->publicThroughYear;

        $comparisonYear =
            (int) $period->comparisonYear;


        /*
        |--------------------------------------------------------------------------
        | Resolve Year Data
        |--------------------------------------------------------------------------
        */

        $currentYearData =
            $this->findYear(
                $yearlyTrend,
                $currentYear
            );

        $comparisonYearData =
            $this->findYear(
                $yearlyTrend,
                $comparisonYear
            );


        /*
        |--------------------------------------------------------------------------
        | Safe Defaults
        |--------------------------------------------------------------------------
        */

        $currentYearData ??=
            $this->emptyYear(
                $currentYear
            );

        $comparisonYearData ??=
            $this->emptyYear(
                $comparisonYear
            );


        /*
        |--------------------------------------------------------------------------
        | Import
        |--------------------------------------------------------------------------
        */

        $currentImportValue =
            $this->tradeValue(
                $currentYearData,
                'import'
            );

        $previousImportValue =
            $this->tradeValue(
                $comparisonYearData,
                'import'
            );

        $currentImportVolume =
            $this->tradeVolume(
                $currentYearData,
                'import'
            );

        $previousImportVolume =
            $this->tradeVolume(
                $comparisonYearData,
                'import'
            );


        /*
        |--------------------------------------------------------------------------
        | Export
        |--------------------------------------------------------------------------
        */

        $currentExportValue =
            $this->tradeValue(
                $currentYearData,
                'export'
            );

        $previousExportValue =
            $this->tradeValue(
                $comparisonYearData,
                'export'
            );

        $currentExportVolume =
            $this->tradeVolume(
                $currentYearData,
                'export'
            );

        $previousExportVolume =
            $this->tradeVolume(
                $comparisonYearData,
                'export'
            );


        /*
        |--------------------------------------------------------------------------
        | Return
        |--------------------------------------------------------------------------
        */

        return [

            'import' => [

                'current' =>
                    $currentImportValue,

                'previous' =>
                    $previousImportValue,

                'growth_percent' =>
                    $this->growthPercent(
                        $currentImportValue,
                        $previousImportValue
                    ),

                'physical_volume_kg' =>
                    $currentImportVolume,

                'previous_physical_volume_kg' =>
                    $previousImportVolume,

                'physical_volume_growth_percent' =>
                    $this->growthPercent(
                        $currentImportVolume,
                        $previousImportVolume
                    ),
            ],


            'export' => [

                'current' =>
                    $currentExportValue,

                'previous' =>
                    $previousExportValue,

                'growth_percent' =>
                    $this->growthPercent(
                        $currentExportValue,
                        $previousExportValue
                    ),

                'physical_volume_kg' =>
                    $currentExportVolume,

                'previous_physical_volume_kg' =>
                    $previousExportVolume,

                'physical_volume_growth_percent' =>
                    $this->growthPercent(
                        $currentExportVolume,
                        $previousExportVolume
                    ),
            ],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Build Market Signal
    |--------------------------------------------------------------------------
    |
    | Market signal is intentionally kept separate from the Executive
    | Overview calculation.
    |
    | It consumes already aggregated country intelligence.
    |
    */

    public function buildMarketSignal(
        array $overview,
        array $majorImportSources,
        array $majorExportDestinations,
        TradeReportingPeriod $period
    ): array {

        $currentYear =
            (int) $period->publicThroughYear;


        /*
        |--------------------------------------------------------------------------
        | Leading Import Origin
        |--------------------------------------------------------------------------
        */

        $leadingImportOrigin =
            $majorImportSources[$currentYear][0]
            ?? null;


        /*
        |--------------------------------------------------------------------------
        | Leading Export Destination
        |--------------------------------------------------------------------------
        */

        $leadingExportDestination =
            $majorExportDestinations[$currentYear][0]
            ?? null;


        /*
        |--------------------------------------------------------------------------
        | Return Market Signal
        |--------------------------------------------------------------------------
        */

        return [

            'import_growth_percent' =>
                (float) (
                    $overview['import']['growth_percent']
                    ?? 0
                ),

            'export_growth_percent' =>
                (float) (
                    $overview['export']['growth_percent']
                    ?? 0
                ),

            'leading_origin' =>
                $this->normalizeLeadingCountry(
                    $leadingImportOrigin
                ),

            'leading_destination' =>
                $this->normalizeLeadingCountry(
                    $leadingExportDestination
                ),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Find Year
    |--------------------------------------------------------------------------
    */

    protected function findYear(
        array $yearlyTrend,
        int $targetYear
    ): ?array {

        foreach (
            $yearlyTrend as $yearData
        ) {

            $year =
                (int) (
                    $yearData['year']
                    ?? $yearData['trade_year']
                    ?? 0
                );

            if (
                $year === $targetYear
            ) {
                return $yearData;
            }
        }

        return null;
    }


    /*
    |--------------------------------------------------------------------------
    | Empty Year
    |--------------------------------------------------------------------------
    */

    protected function emptyYear(
        int $year
    ): array {

        return [

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
    | Trade Value
    |--------------------------------------------------------------------------
    */

    protected function tradeValue(
        array $yearData,
        string $flow
    ): float {

        return (float) (
            $yearData[$flow]['trade_value']
            ?? $yearData[$flow]['value']
            ?? 0
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Trade Volume
    |--------------------------------------------------------------------------
    */

    protected function tradeVolume(
        array $yearData,
        string $flow
    ): float {

        return (float) (
            $yearData[$flow]['trade_volume']
            ?? $yearData[$flow]['volume_kg']
            ?? $yearData[$flow]['volume']
            ?? 0
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Growth
    |--------------------------------------------------------------------------
    |
    | Historical yearly growth:
    |
    | previous == 0
    |     => 0
    |
    | Otherwise:
    |
    |     ((current - previous) / previous) * 100
    |
    */

    protected function growthPercent(
        float $current,
        float $previous
    ): float {

        if (
            $previous == 0.0
        ) {
            return 0.0;
        }

        return round(
            (
                (
                    $current
                    - $previous
                )
                / $previous
            ) * 100,
            6
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Normalize Leading Country
    |--------------------------------------------------------------------------
    |
    | Country aggregation has already been performed by
    | HistoricalYearlyCountryBuilder.
    |
    | This method only creates the small structure consumed
    | by Market Signal.
    |
    */

    protected function normalizeLeadingCountry(
        ?array $country
    ): ?array {

        if (
            !is_array($country)
        ) {
            return null;
        }

        return [

            'country' =>
                $country['country']
                ?? $country['trade_country']
                ?? $country['name']
                ?? null,

            'trade_value' =>
                (float) (
                    $country['trade_value']
                    ?? $country['value']
                    ?? 0
                ),

            'trade_volume' =>
                (float) (
                    $country['trade_volume']
                    ?? $country['volume']
                    ?? 0
                ),
        ];
    }
}