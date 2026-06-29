<?php

namespace App\Services\Trade;

use App\Repositories\Trade\TradeStatisticsRepository;

class TradeAnalyticsService
{
    public function __construct(
        protected TradeStatisticsRepository $repository
    ) {
    }

    /**
     * Dashboard Trade Intelligence
     */
    public function dashboard(array $filters = []): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | KPI Summary
            |--------------------------------------------------------------------------
            */

            'summary' => $this->repository->summary($filters),

            /*
            |--------------------------------------------------------------------------
            | Monthly Export vs Import Trend
            |--------------------------------------------------------------------------
            */

            'trend' => $this->repository->monthlyTrend($filters),

            /*
            |--------------------------------------------------------------------------
            | Top Export / Import Countries
            |--------------------------------------------------------------------------
            */

            'topCountries' => $this->repository->topCountries($filters),

            /*
            |--------------------------------------------------------------------------
            | Top HS Code
            |--------------------------------------------------------------------------
            */

            'topHsCodes' => $this->repository->topHsCodes($filters),

        ];
    }

    /**
     * Summary Only
     */
    public function summary(array $filters = []): array
    {
        return $this->repository->summary($filters);
    }

    /**
     * Monthly Trend
     */
    public function trend(array $filters = []): array
    {
        return $this->repository->monthlyTrend($filters);
    }

    /**
     * Top Countries
     */
    public function topCountries(array $filters = []): array
    {
        return $this->repository->topCountries($filters);
    }

    /**
     * Top HS Code
     */
    public function topHsCodes(array $filters = []): array
    {
        return $this->repository->topHsCodes($filters);
    }
/**
 * Monthly Comparison
 */
public function monthlyComparison(array $filters = []): array
{
    return $this->repository->monthlyComparison($filters);
}
public function monthlyComparisonPieces(array $filters = [])
{
    return $this->repository->monthlyComparisonPieces($filters);
}
    
}