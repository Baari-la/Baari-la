<?php

namespace App\Services\TradeIntelligence\Historical;

use App\Services\Trade\Taxonomy\TextileTaxonomyService;
use App\Services\Trade\TradeReportingPeriod;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class HistoricalYearlyQueryBuilder
{
    /*
    |--------------------------------------------------------------------------
    | Historical Range
    |--------------------------------------------------------------------------
    */

    private const HISTORICAL_START_YEAR = 2019;

    private const HISTORICAL_END_YEAR = 2024;


    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct(
        protected TextileTaxonomyService $taxonomy,
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | Execute Historical Queries
    |--------------------------------------------------------------------------
    |
    | Main entry point.
    |
    | Returns the two datasets required by the
    | HistoricalYearlyDatasetBuilder:
    |
    |   summary
    |   country
    |
    */

    public function execute(
        TradeReportingPeriod $period
    ): array {

        return [

            'summary' =>
                $this->summary(
                    $period
                )->get(),

            'country' =>
                $this->country(
                    $period
                )->get(),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Historical Yearly Summary Query
    |--------------------------------------------------------------------------
    |
    | Aggregation:
    |
    |     year + trade_flow
    |
    | Result contains:
    |
    |     trade_year
    |     trade_flow
    |     trade_value
    |     trade_volume
    |
    | Month and HS-8 are intentionally NOT part
    | of the result set.
    |
    */

    public function summary(
        TradeReportingPeriod $period
    ): Builder {

        $sectorHsCodes =
            $this->sectorHsCodes();

        return DB::table(
            'trade_statistics'
        )

            /*
            |--------------------------------------------------------------------------
            | Canonical HS-8 Universe
            |--------------------------------------------------------------------------
            */

            ->whereIn(
                'hs_code',
                $sectorHsCodes
            )

            /*
            |--------------------------------------------------------------------------
            | Historical Year Range
            |--------------------------------------------------------------------------
            */

            ->whereBetween(
                'year',
                [
                    self::HISTORICAL_START_YEAR,
                    $this->historicalEndYear(
                        $period
                    ),
                ]
            )

            /*
            |--------------------------------------------------------------------------
            | Select Dimensions
            |--------------------------------------------------------------------------
            */

            ->select([
                'year as trade_year',
                'trade_flow',
            ])

            /*
            |--------------------------------------------------------------------------
            | Aggregate Trade Value
            |--------------------------------------------------------------------------
            */

            ->selectRaw(
                'SUM(trade_value) AS trade_value'
            )

            /*
            |--------------------------------------------------------------------------
            | Aggregate Official Physical Volume
            |--------------------------------------------------------------------------
            */

            ->selectRaw(
                'SUM(trade_volume) AS trade_volume'
            )

            /*
            |--------------------------------------------------------------------------
            | Aggregation Level
            |--------------------------------------------------------------------------
            */

            ->groupBy([
                'year',
                'trade_flow',
            ])

            /*
            |--------------------------------------------------------------------------
            | Stable Ordering
            |--------------------------------------------------------------------------
            */

            ->orderBy(
                'year'
            )

            ->orderBy(
                'trade_flow'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Historical Yearly Country Query
    |--------------------------------------------------------------------------
    |
    | Aggregation:
    |
    |     year + trade_flow + country
    |
    | Result contains:
    |
    |     trade_year
    |     trade_flow
    |     country
    |     trade_value
    |     trade_volume
    |
    | Country identity enrichment is deliberately NOT
    | performed here.
    |
    | That responsibility belongs to the country
    | builder / canonical identity layer.
    |
    */

    public function country(
        TradeReportingPeriod $period
    ): Builder {

        $sectorHsCodes =
            $this->sectorHsCodes();

        return DB::table(
            'trade_statistics'
        )

            /*
            |--------------------------------------------------------------------------
            | Canonical HS-8 Universe
            |--------------------------------------------------------------------------
            */

            ->whereIn(
                'hs_code',
                $sectorHsCodes
            )

            /*
            |--------------------------------------------------------------------------
            | Historical Year Range
            |--------------------------------------------------------------------------
            */

            ->whereBetween(
                'year',
                [
                    self::HISTORICAL_START_YEAR,
                    $this->historicalEndYear(
                        $period
                    ),
                ]
            )

            /*
            |--------------------------------------------------------------------------
            | Select Dimensions
            |--------------------------------------------------------------------------
            */

            ->select([
                'year as trade_year',
                'trade_flow',
                'country_name as country',
            ])

            /*
            |--------------------------------------------------------------------------
            | Aggregate Trade Value
            |--------------------------------------------------------------------------
            */

            ->selectRaw(
                'SUM(trade_value) AS trade_value'
            )

            /*
            |--------------------------------------------------------------------------
            | Aggregate Official Physical Volume
            |--------------------------------------------------------------------------
            */

            ->selectRaw(
                'SUM(trade_volume) AS trade_volume'
            )

            /*
            |--------------------------------------------------------------------------
            | Aggregation Level
            |--------------------------------------------------------------------------
            */

            ->groupBy([
                'year',
                'trade_flow',
                'country_name',
            ])

            /*
            |--------------------------------------------------------------------------
            | Stable Ordering
            |--------------------------------------------------------------------------
            */

            ->orderBy(
                'year'
            )

            ->orderBy(
                'trade_flow'
            )

            ->orderByDesc(
                'trade_value'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Canonical Sector HS-8
    |--------------------------------------------------------------------------
    |
    | The historical query must use exactly the same
    | Canonical HS-8 universe used by DIGESTEX.
    |
    */

    protected function sectorHsCodes(): array
    {
        return $this->taxonomy
            ->hsCodesForSector(
                'garment'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Historical End Year
    |--------------------------------------------------------------------------
    |
    | Do not allow the query to go beyond the historical
    | yearly dataset boundary.
    |
    | Example:
    |
    |     requested 2022 -> through 2022
    |     requested 2024 -> through 2024
    |     requested 2025 -> through 2024
    |
    */

    protected function historicalEndYear(
        TradeReportingPeriod $period
    ): int {

        return min(
            self::HISTORICAL_END_YEAR,
            (int) $period->publicThroughYear
        );
    }
}