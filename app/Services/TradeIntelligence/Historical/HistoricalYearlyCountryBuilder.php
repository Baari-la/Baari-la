<?php

namespace App\Services\TradeIntelligence\Historical;

use App\Services\Trade\CountryResolverService;

class HistoricalYearlyCountryBuilder
{
    /*
    |--------------------------------------------------------------------------
    | Default Country Limit
    |--------------------------------------------------------------------------
    */

    private const DEFAULT_LIMIT = 10;


    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    |
    | Country identity MUST come from the canonical Country Master.
    |
    | No manual country-to-flag mapping is maintained here.
    |
    */

    public function __construct(
        protected CountryResolverService $countryResolver,
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | Build Country Intelligence
    |--------------------------------------------------------------------------
    |
    | Input:
    |
    | [
    |     2019 => [
    |         'import' => [...],
    |         'export' => [...],
    |     ],
    | ]
    |
    | Output:
    |
    | [
    |     'major_import_sources' => [...],
    |     'major_export_destinations' => [...],
    |     'top_import_origins' => [...],
    |     'top_export_destinations' => [...],
    | ]
    |
    */

    public function build(
        array $countries,
        int $limit = self::DEFAULT_LIMIT
    ): array {

        $majorImportSources =
            $this->buildMajorCountries(
                $countries,
                'import',
                $limit
            );

        $majorExportDestinations =
            $this->buildMajorCountries(
                $countries,
                'export',
                $limit
            );

        return [

            'major_import_sources' =>
                $majorImportSources,

            'major_export_destinations' =>
                $majorExportDestinations,

            'top_import_origins' =>
                $this->buildCurrentYearCards(
                    $majorImportSources
                ),

            'top_export_destinations' =>
                $this->buildCurrentYearCards(
                    $majorExportDestinations
                ),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Major Countries
    |--------------------------------------------------------------------------
    |
    | Build yearly country intelligence.
    |
    | Import:
    |
    |     country = source/origin
    |
    | Export:
    |
    |     country = destination
    |
    */

    protected function buildMajorCountries(
        array $countries,
        string $flow,
        int $limit
    ): array {

        $result = [];

        foreach (
            $countries as $year => $yearData
        ) {

            $year =
                (int) $year;

            $items =
                $yearData[$flow]
                ?? [];

            if (
                !is_array($items)
                || empty($items)
            ) {
                $result[$year] = [];

                continue;
            }

            $normalized =
                collect($items)
                    ->map(
                        fn (array $country): array =>
                            $this->normalizeCountry(
                                $country,
                                $flow
                            )
                    )
                    ->filter(
                        fn (array $country): bool =>
                            $country['country'] !== ''
                    )
                    ->sortByDesc(
                        'trade_value'
                    )
                    ->take(
                        $limit
                    )
                    ->values()
                    ->all();

            $result[$year] =
                $normalized;
        }

        /*
        |--------------------------------------------------------------------------
        | Numeric Year Ordering
        |--------------------------------------------------------------------------
        */

        ksort(
            $result,
            SORT_NUMERIC
        );

        return $result;
    }


    /*
    |--------------------------------------------------------------------------
    | Normalize Country
    |--------------------------------------------------------------------------
    |
    | Historical trade data supplies the raw country name.
    |
    | Canonical identity is resolved through Country Master.
    |
    | This method does NOT maintain a manual country mapping.
    |
    */

    protected function normalizeCountry(
        array $country,
        string $flow
    ): array {

        $countryName =
            trim(
                (string) (
                    $country['country']
                    ?? $country['trade_country']
                    ?? $country['name']
                    ?? ''
                )
            );

        $value =
            $this->toFloat(
                $country['trade_value']
                ?? $country['value']
                ?? 0
            );

        $volume =
            $this->toFloat(
                $country['trade_volume']
                ?? $country['volume']
                ?? 0
            );


        /*
        |--------------------------------------------------------------------------
        | Canonical Country Identity
        |--------------------------------------------------------------------------
        |
        | Country Master is the authoritative source.
        |
        */

        $identity =
            $this->resolveCountry(
                $countryName
            );


        /*
        |--------------------------------------------------------------------------
        | Display Country Name
        |--------------------------------------------------------------------------
        |
        | Prefer canonical English name when available.
        | Fall back to the original trade country name.
        |
        */

        $displayName =
            trim(
                (string) (
                    $identity['country_name_en']
                    ?? $countryName
                )
            );


        /*
        |--------------------------------------------------------------------------
        | Base Result
        |--------------------------------------------------------------------------
        */

        $result = [

            /*
            |--------------------------------------------------------------------------
            | Canonical Identity
            |--------------------------------------------------------------------------
            */

            'country_id' =>
                $identity['country_id']
                ?? null,

            'country_code' =>
                $identity['country_code']
                ?? null,

            'iso3' =>
                $identity['iso3']
                ?? null,

            'country_name_en' =>
                $identity['country_name_en']
                ?? $displayName,

            'country_name_id' =>
                $identity['country_name_id']
                ?? $displayName,

            'flag_emoji' =>
                $identity['flag_emoji']
                ?? null,


            /*
            |--------------------------------------------------------------------------
            | Historical / Display Compatibility
            |--------------------------------------------------------------------------
            */

            'country' =>
                $displayName,

            'name' =>
                $displayName,

            'trade_country' =>
                $countryName,


            /*
            |--------------------------------------------------------------------------
            | Trade Metrics
            |--------------------------------------------------------------------------
            */

            'value' =>
                $value,

            'trade_value' =>
                $value,

            'volume' =>
                $volume,

            'trade_volume' =>
                $volume,
        ];


        /*
        |--------------------------------------------------------------------------
        | Flow-Specific Alias
        |--------------------------------------------------------------------------
        */

        if ($flow === 'export') {

            $result['destination'] =
                $displayName;
        }


        return $result;
    }


    /*
    |--------------------------------------------------------------------------
    | Resolve Canonical Country
    |--------------------------------------------------------------------------
    |
    | Keep the resolver isolated from the historical aggregation logic.
    |
    */

    protected function resolveCountry(
        string $countryName
    ): array {

        if ($countryName === '') {
            return [];
        }

        $resolved =
            $this->countryResolver->resolve(
                $countryName
            );

        if (
            is_array($resolved)
        ) {
            return $resolved;
        }

        if (
            is_object($resolved)
        ) {
            return [
                'country_id' =>
                    $resolved->country_id
                    ?? $resolved->id
                    ?? null,

                'country_code' =>
                    $resolved->country_code
                    ?? null,

                'iso3' =>
                    $resolved->iso3
                    ?? null,

                'country_name_en' =>
                    $resolved->country_name_en
                    ?? null,

                'country_name_id' =>
                    $resolved->country_name_id
                    ?? null,

                'flag_emoji' =>
                    $resolved->flag_emoji
                    ?? null,
            ];
        }

        return [];
    }


    /*
    |--------------------------------------------------------------------------
    | Current-Year Country Cards
    |--------------------------------------------------------------------------
    |
    | The frontend expects:
    |
    |     top_import_origins
    |     top_export_destinations
    |
    | These are derived from the already ranked yearly
    | country intelligence.
    |
    */

    protected function buildCurrentYearCards(
        array $majorCountries
    ): array {

        if (empty($majorCountries)) {
            return [];
        }

        /*
        |--------------------------------------------------------------------------
        | Last Available Year
        |--------------------------------------------------------------------------
        */

        $years =
            array_keys(
                $majorCountries
            );

        $latestYear =
            max(
                array_map(
                    'intval',
                    $years
                )
            );

        return $majorCountries[
            $latestYear
        ] ?? [];
    }


    /*
    |--------------------------------------------------------------------------
    | Countries For Year
    |--------------------------------------------------------------------------
    */

    public function forYear(
        array $majorCountries,
        int $year
    ): array {

        return $majorCountries[
            $year
        ] ?? [];
    }


    /*
    |--------------------------------------------------------------------------
    | Import Origins For Year
    |--------------------------------------------------------------------------
    */

    public function importOriginsForYear(
        array $countries,
        int $year,
        int $limit = self::DEFAULT_LIMIT
    ): array {

        $major =
            $this->buildMajorCountries(
                $countries,
                'import',
                $limit
            );

        return $major[
            $year
        ] ?? [];
    }


    /*
    |--------------------------------------------------------------------------
    | Export Destinations For Year
    |--------------------------------------------------------------------------
    */

    public function exportDestinationsForYear(
        array $countries,
        int $year,
        int $limit = self::DEFAULT_LIMIT
    ): array {

        $major =
            $this->buildMajorCountries(
                $countries,
                'export',
                $limit
            );

        return $major[
            $year
        ] ?? [];
    }


    /*
    |--------------------------------------------------------------------------
    | Numeric Normalization
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