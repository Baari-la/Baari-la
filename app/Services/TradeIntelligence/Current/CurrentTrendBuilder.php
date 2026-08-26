<?php

namespace App\Services\TradeIntelligence\Current;

use Illuminate\Support\Collection;

class CurrentTrendBuilder
{
    /*
    |--------------------------------------------------------------------------
    | Current Trend Intelligence
    |--------------------------------------------------------------------------
    |
    | Responsible only for:
    |
    |   - Monthly trend
    |   - Yearly trend
    |
    | Monthly trend uses:
    |
    |   year + month + flow
    |
    | Yearly trend uses:
    |
    |   year + flow
    |
    | This component does NOT build:
    |
    |   - overview
    |   - subsector
    |   - flow performance
    |   - products
    |   - countries
    |   - market share
    |   - HS-8 products
    |
    */


    /*
    |--------------------------------------------------------------------------
    | Monthly Trend
    |--------------------------------------------------------------------------
    |
    | Equivalent to the existing buildMonthlyTrend().
    |
    | Output:
    |
    | [
    |     [
    |         'period' => '2024-01',
    |         'import' => ...,
    |         'export' => ...,
    |     ],
    | ]
    |
    */

    public function buildMonthly(
        Collection $rows
    ): array {
        return $rows
            ->filter(
                static function ($row) {
                    return
                        !empty(
                            $row['month']
                        )
                        &&
                        (int) $row['month'] >= 1
                        &&
                        (int) $row['month'] <= 12;
                }
            )
            ->groupBy(
                static function ($row) {
                    return sprintf(
                        '%04d-%02d',
                        (int) $row['year'],
                        (int) $row['month']
                    );
                }
            )
            ->map(
                function (
                    Collection $items,
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
    |
    | Equivalent to the existing buildYearlyTrend().
    |
    | Output:
    |
    | [
    |     [
    |         'year' => 2024,
    |         'import' => ...,
    |         'export' => ...,
    |     ],
    | ]
    |
    */

    public function buildYearly(
        Collection $rows
    ): array {
        return $rows
            ->groupBy('year')
            ->map(
                function (
                    Collection $items,
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
    | Sum Trade Value By Flow
    |--------------------------------------------------------------------------
    |
    | Shared internally by monthly and yearly trends.
    |
    */

    protected function sumByFlow(
        Collection $rows,
        string $flow
    ): float {
        return (float) $rows
            ->filter(
                static function ($row) use ($flow) {
                    return (
                        ($row['flow'] ?? null)
                        === $flow
                    );
                }
            )
            ->sum(
                'value'
            );
    }
}