<?php

declare(strict_types=1);

namespace App\Services\Trade\Analytics;

use App\Repositories\Trade\Country\CountryTradeRepository;
use App\Repositories\Trade\Executive\ExecutiveCountryRepository;
use App\Services\MasterData\CountryService;

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

        )->map(function ($row) {

            return [

                'country_code' => $row['country_code'],

                'country' => $this->countryService
                    ->displayName($row['country_code']),

                'flag' => $this->countryService
                    ->flag($row['country_code']),

                'export_value' => $row['export_value'],

                'export_million' => $row['export_million'] ?? null,

                'share' => $row['share'] ?? null,

                'growth' => $row['growth'] ?? null,

            ];

        })

        ->values()

        ->toArray();
    }
}