<?php

declare(strict_types=1);

namespace App\Services\Trade\Analytics;

use App\Repositories\Trade\Country\CountryTradeRepository;
use App\Repositories\Trade\Executive\ExecutiveCountryRepository;
use App\Services\MasterData\CountryService;
use Illuminate\Support\Facades\App;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Country Analytics Service
 * ==========================================================================
 *
 * Business Intelligence layer for Country Analytics.
 *
 * Responsible for:
 *
 * - Country Dashboard
 * - Country Summary
 * - Monthly Trend
 * - Top Products
 * - Trade Balance
 * - Growth Analysis
 * - Top Countries
 *
 * Used by:
 *
 * - ExecutiveAnalyticsService
 * - ExecutiveReportService
 * - Dashboard API
 * - AI Executive Summary
 */
class CountryAnalyticsService
{
    public function __construct(
        protected CountryTradeRepository $countryRepository,
        protected ExecutiveCountryRepository $executiveRepository,
        protected CountryService $countryService,
    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Country Intelligence Dashboard
     * --------------------------------------------------------------------------
     */
    public function dashboard(
        string $countryCode,
        array $filters = []
    ): array {

    
        $country = $this->countryService->find($countryCode);

        return [

            'country' => [

                'code'       => $countryCode,

                'name'       => $country?->display_name ?? $countryCode,

                'flag'       => $country?->flag_emoji ?? '',

                'region'     => $country?->display_region,

                'sub_region' => $country?->display_sub_region,

            ],

            'summary' => $this->countryRepository
                ->summary($countryCode, $filters),

            'monthlyTrend' => $this->countryRepository
                ->monthlyTrend($countryCode, $filters),

            'topProducts' => $this->countryRepository
                ->topProducts($countryCode, $filters),

            'tradeBalance' => $this->countryRepository
                ->tradeBalance($countryCode, $filters),

            'growth' => $this->countryRepository
                ->growth($countryCode, $filters),

            'marketShare' => $this->countryRepository
                ->marketShare($countryCode, $filters),

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Top Export Destination Countries
     * --------------------------------------------------------------------------
     *
     * Generic ranking.
     *
     * Example:
     *
     * Apparel :
     * ['61','62','63']
     *
     * Cotton :
     * ['52']
     *
     * Yarn :
     * ['50','51','52','54','55']
     */
    public function topCountries(array $filters = []): array
{
    return collect(
        $this->executiveRepository
            ->topCountries($filters)
    )

    ->map(function ($row) {

    
        $country = $this->countryService
            ->find($row['country_code']);
            
       return [

    /*
    |--------------------------------------------------------------------------
    | Identity
    |--------------------------------------------------------------------------
    */

    'rank' =>
        $row['rank'] ?? 0,

    'country_code' =>
        $row['country_code'],

    'country_name_en' =>
        $country?->country_name_en
        ?? $row['country_code'],

    'country_name_id' =>
        $country?->country_name_id
        ?? $row['country_code'],

    'flag' =>
        $country?->flag_emoji ?? '',

    /*
    |--------------------------------------------------------------------------
    | Export
    |--------------------------------------------------------------------------
    */

    'export_value' =>
        $row['export_value'] ?? 0,

    'export_million' =>
        $row['export_million'] ?? 0,

    'export_volume' =>
        $row['export_volume'] ?? 0,

    /*
    |--------------------------------------------------------------------------
    | Import
    |--------------------------------------------------------------------------
    */

    'import_value' =>
        $row['import_value'] ?? 0,

    'import_volume' =>
        $row['import_volume'] ?? 0,

    /*
    |--------------------------------------------------------------------------
    | Trade Balance
    |--------------------------------------------------------------------------
    */

    'trade_balance' =>
        $row['trade_balance'] ?? 0,

    'trade_balance_million' =>
        $row['trade_balance_million'] ?? 0,

    /*
    |--------------------------------------------------------------------------
    | Intelligence
    |--------------------------------------------------------------------------
    */

    'share' =>
        $row['share'] ?? 0,

    'growth' =>
        $row['growth'] ?? null,

    'growth_volume' =>
        $row['growth_volume'] ?? null,

    /*
    |--------------------------------------------------------------------------
    | Historical
    |--------------------------------------------------------------------------
    */

    'previous_value' =>
        $row['previous_value'] ?? 0,

    'previous_volume' =>
        $row['previous_volume'] ?? 0,
];

    })

    ->values()

    ->toArray();
}


}