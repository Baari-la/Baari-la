<?php

namespace App\Services\Trade;

use App\Repositories\Trade\TradeStatisticsRepository;

class CountryAnalyticsService
{
    public function __construct(
        protected TradeStatisticsRepository $repository
    ) {
    }

    /**
     * Country Intelligence Dashboard
     */
    public function dashboard(
        string $countryCode,
        array $filters = []
    ): array {

        return [

            'summary' => $this->repository->getCountrySummary(
                $countryCode,
                $filters
            ),

            'monthlyTrend' => $this->repository->getCountryMonthlyTrend(
                $countryCode,
                $filters
            ),

            'topProducts' => $this->repository->getCountryTopProducts(
                $countryCode,
                $filters
            ),

            'tradeBalance' => $this->repository->getCountryTradeBalance(
                $countryCode,
                $filters
            ),

            'growth' => $this->repository->getCountryGrowth(
                $countryCode,
                $filters
            ),

        ];
    }
    /**
 * Top Export Destination Countries
 */
public function topCountries(array $filters = []): array
{
    return $this->repository
        ->topCountries($filters)
        ->toArray();
}
}