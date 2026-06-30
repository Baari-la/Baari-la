<?php

namespace App\Services\Trade;

use App\Repositories\Trade\TradeStatisticsRepository;
use App\Services\MasterData\CountryService;

class CountryAnalyticsService
{
    public function __construct(
        protected TradeStatisticsRepository $repository,
        protected CountryService $countryService
    ) {
    }

    /**
     * Country Intelligence Dashboard
     */
    public function dashboard(
        string $countryCode,
        array $filters = []
    ): array {

        $country = $this->countryService->find($countryCode);

        return [

            'country' => [
                'code' => $countryCode,
                'name' => $country?->display_name ?? $countryCode,
                'flag' => $country?->flag_emoji ?? '',
                'region' => $country?->display_region,
                'sub_region' => $country?->display_sub_region,
            ],

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
        return collect(
            $this->repository->topCountries($filters)
        )->map(function ($row) {

            return [

                'country_code' => $row['country_code'],

                'country' => $this->countryService
                    ->displayName($row['country_code']),

                'flag' => $this->countryService
                    ->flag($row['country_code']),

                'export_value' => $row['export_value'],

                'growth' => $row['growth'] ?? null,

            ];

        })->values()->toArray();
    }

    /**
     * Top Garment Export Destination Countries
     */
    public function topGarmentCountries(array $filters = []): array
    {
        return collect(
            $this->repository->topGarmentExportCountries($filters)
        )->map(function ($row) {

            return [
    'country_code' => $row['country_code'],
    'country' => $this->countryService->displayName($row['country_code']),
    'flag' => $this->countryService->flag($row['country_code']),
    'export_value' => $row['export_value'],  // ✅
    'export_million' => $row['export_million'],
    'share' => $row['share'],
];
        })->values()->toArray();
    }
}