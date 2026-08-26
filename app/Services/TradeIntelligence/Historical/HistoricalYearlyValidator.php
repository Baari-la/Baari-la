<?php

namespace App\Services\TradeIntelligence\Historical;

class HistoricalYearlyValidator
{
    /*
    |--------------------------------------------------------------------------
    | Validate Historical Yearly Dataset
    |--------------------------------------------------------------------------
    |
    | Validation rule:
    |
    |     yearly summary
    |          ==
    |     sum of all countries
    |
    | for every:
    |
    |     year + trade_flow
    |
    | This class is diagnostic / quality-control only.
    |
    */

    public function validate(
        array $yearlyTrend,
        array $majorExportDestinations,
        array $majorImportSources
    ): array {

        $validation = [];

        foreach (
            $yearlyTrend as $yearData
        ) {

            $year =
                (int) (
                    $yearData['year']
                    ?? $yearData['trade_year']
                    ?? 0
                );

            if ($year <= 0) {
                continue;
            }

            foreach (
                [
                    'export',
                    'import',
                ] as $flow
            ) {

                /*
                |--------------------------------------------------------------------------
                | Expected Yearly Values
                |--------------------------------------------------------------------------
                */

                $expectedValue =
                    $this->tradeValue(
                        $yearData,
                        $flow
                    );

                $expectedVolume =
                    $this->tradeVolume(
                        $yearData,
                        $flow
                    );


                /*
                |--------------------------------------------------------------------------
                | Select Country Dataset
                |--------------------------------------------------------------------------
                */

                $countryData =
                    $flow === 'export'
                        ? (
                            $majorExportDestinations[
                                $year
                            ] ?? []
                        )
                        : (
                            $majorImportSources[
                                $year
                            ] ?? []
                        );


                /*
                |--------------------------------------------------------------------------
                | Aggregate Country Values
                |--------------------------------------------------------------------------
                */

                $countryValue =
                    $this->sumCountryMetric(
                        $countryData,
                        'trade_value'
                    );

                $countryVolume =
                    $this->sumCountryMetric(
                        $countryData,
                        'trade_volume'
                    );


                /*
                |--------------------------------------------------------------------------
                | Tolerance
                |--------------------------------------------------------------------------
                |
                | SQL SUM() and PHP aggregation may produce extremely
                | small floating-point differences.
                |
                */

                $valueTolerance =
                    $this->tolerance(
                        $expectedValue
                    );

                $volumeTolerance =
                    $this->tolerance(
                        $expectedVolume
                    );


                /*
                |--------------------------------------------------------------------------
                | Differences
                |--------------------------------------------------------------------------
                */

                $valueDifference =
                    $expectedValue
                    - $countryValue;

                $volumeDifference =
                    $expectedVolume
                    - $countryVolume;


                /*
                |--------------------------------------------------------------------------
                | Validation Result
                |--------------------------------------------------------------------------
                */

                $valid =
                    abs($valueDifference)
                        <= $valueTolerance
                    &&
                    abs($volumeDifference)
                        <= $volumeTolerance;


                $validation[] = [

                    'year' =>
                        $year,

                    'trade_flow' =>
                        $flow,

                    'yearly_trade_value' =>
                        round(
                            $expectedValue,
                            6
                        ),

                    'country_trade_value' =>
                        round(
                            $countryValue,
                            6
                        ),

                    'value_difference' =>
                        round(
                            $valueDifference,
                            6
                        ),

                    'yearly_trade_volume' =>
                        round(
                            $expectedVolume,
                            6
                        ),

                    'country_trade_volume' =>
                        round(
                            $countryVolume,
                            6
                        ),

                    'volume_difference' =>
                        round(
                            $volumeDifference,
                            6
                        ),

                    'valid' =>
                        $valid,
                ];
            }
        }

        return $validation;
    }


    /*
    |--------------------------------------------------------------------------
    | Validate All
    |--------------------------------------------------------------------------
    |
    | Convenience method for callers that only need a boolean.
    |
    */

    public function passes(
        array $yearlyTrend,
        array $majorExportDestinations,
        array $majorImportSources
    ): bool {

        $results =
            $this->validate(
                $yearlyTrend,
                $majorExportDestinations,
                $majorImportSources
            );

        foreach (
            $results as $result
        ) {

            if (
                !($result['valid'] ?? false)
            ) {
                return false;
            }
        }

        return true;
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
            ?? $yearData[$flow]['volume']
            ?? $yearData[$flow]['volume_kg']
            ?? 0
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Sum Country Metric
    |--------------------------------------------------------------------------
    */

    protected function sumCountryMetric(
        array $countries,
        string $metric
    ): float {

        $total = 0.0;

        foreach (
            $countries as $country
        ) {

            if (
                !is_array($country)
            ) {
                continue;
            }

            $total += (float) (
                $country[$metric]
                ?? 0
            );
        }

        return $total;
    }


    /*
    |--------------------------------------------------------------------------
    | Floating Point Tolerance
    |--------------------------------------------------------------------------
    |
    | Same tolerance rule as the original implementation:
    |
    |     max(
    |         0.00001,
    |         abs(expected) * 0.000000000001
    |     )
    |
    */

    protected function tolerance(
        float $expected
    ): float {

        return max(
            0.00001,
            abs($expected)
                * 0.000000000001
        );
    }
}