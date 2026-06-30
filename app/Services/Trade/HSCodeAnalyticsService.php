<?php

namespace App\Services\Trade;

use App\Repositories\Trade\TradeStatisticsRepository;

class HSCodeAnalyticsService
{
    public function __construct(
        protected TradeStatisticsRepository $repository
    ) {
    }

    /**
     * HS Code Intelligence
     */
    public function dashboard(
        string $hsCode,
        array $filters = []
    ): array {

        return [

            'summary' => $this->repository->getHSCodeSummary(
                $hsCode,
                $filters
            ),

            'monthlyTrend' => $this->repository->getHSCodeMonthlyTrend(
                $hsCode,
                $filters
            ),

            'topCountries' => $this->repository->getHSCodeTopCountries(
                $hsCode,
                $filters
            ),

            'growth' => $this->repository->getHSCodeGrowth(
                $hsCode,
                $filters
            ),

            'seasonality' => $this->repository->getHSCodeSeasonality(
                $hsCode,
                $filters
            ),

        ];
    }
    /**
 * Top HS Codes
 *
 * Used by Executive Report
 */
public function topHsCodes(array $filters = []): array
{
    return $this->repository
        ->topHsCodes($filters)
        ->toArray();
}
/**
 * Top Garment Products (HS 61–63)
 */
public function topGarmentProducts(array $filters = []): array
{
    return $this->repository
        ->topGarmentProducts($filters);
}
}