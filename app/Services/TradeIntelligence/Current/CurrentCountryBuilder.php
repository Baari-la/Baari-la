<?php

namespace App\Services\TradeIntelligence\Current;

use Illuminate\Support\Collection;

class CurrentCountryBuilder
{
    /*
    |--------------------------------------------------------------------------
    | Current Country Intelligence
    |--------------------------------------------------------------------------
    |
    | Responsible only for country-level intelligence:
    |
    |   - Top import origins
    |   - Top export destinations
    |   - Import market share
    |   - Export market share
    |
    | This component does NOT build:
    |
    |   - executive overview
    |   - subsectors
    |   - flows
    |   - products
    |   - trends
    |   - HS-8 products
    |
    */


    /*
    |--------------------------------------------------------------------------
    | Top Countries
    |--------------------------------------------------------------------------
    |
    | Equivalent to the existing buildTopCountries().
    |
    */

    public function buildTopCountries(
        Collection $rows,
        string $flow,
        int $limit = 10
    ): array {
        $items =
            $this->filterCountryRows(
                $rows,
                $flow
            );

        return $items
            ->groupBy(
                static function ($row) {
                    return $row['country_code']
                        ?? $row['country']
                        ?? null;
                }
            )
            ->map(
                function (
                    Collection $group,
                    $countryKey
                ) {
                    return $this->buildCountry(
                        $group,
                        $countryKey
                    );
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
    |
    | Equivalent to the existing buildCountryMarketShare().
    |
    */

    public function buildMarketShare(
        Collection $rows,
        string $flow,
        int $limit = 10
    ): array {
        $items =
            $this->filterCountryRows(
                $rows,
                $flow
            );

        $total =
            (float) $items->sum(
                'value'
            );

        /*
        |--------------------------------------------------------------------------
        | No Trade Value
        |--------------------------------------------------------------------------
        */

        if ($total <= 0.0) {
            return [];
        }

        return $items
            ->groupBy(
                static function ($row) {
                    return $row['country_code']
                        ?? $row['country']
                        ?? null;
                }
            )
            ->map(
                function (
                    Collection $group,
                    $countryKey
                ) use (
                    $total
                ) {
                    return $this->buildCountryMarketShareRow(
                        $group,
                        $countryKey,
                        $total
                    );
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
    | Filter Country Rows
    |--------------------------------------------------------------------------
    |
    | A country must have a non-empty country identity.
    |
    */

    protected function filterCountryRows(
        Collection $rows,
        string $flow
    ): Collection {
        return $rows->filter(
            static function ($row) use ($flow) {

                return
                    ($row['flow'] ?? null)
                        === $flow
                    &&
                    filled(
                        $row['country']
                        ?? null
                    );
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Build Country
    |--------------------------------------------------------------------------
    */

    protected function buildCountry(
        Collection $group,
        $countryKey
    ): array {
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
            |
            | Keep the existing country field for backward
            | compatibility with existing consumers.
            |
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


    /*
    |--------------------------------------------------------------------------
    | Build Country Market Share Row
    |--------------------------------------------------------------------------
    */

    protected function buildCountryMarketShareRow(
        Collection $group,
        $countryKey,
        float $total
    ): array {
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


            /*
            |--------------------------------------------------------------------------
            | Market Share
            |--------------------------------------------------------------------------
            */

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
}