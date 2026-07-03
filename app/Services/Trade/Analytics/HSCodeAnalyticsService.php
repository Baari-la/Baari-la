<?php

declare(strict_types=1);

namespace App\Services\Trade\Analytics;

use App\Repositories\Trade\Executive\ExecutiveProductRepository;
use App\Repositories\Trade\HSCode\HSCodeTradeRepository;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * HS Code Analytics Service
 * ==========================================================================
 *
 * Business Intelligence layer for HS Code Analytics.
 *
 * Responsible for:
 *
 * - HS Code Dashboard
 * - Product Performance
 * - Country Performance
 * - Growth Analysis
 * - Executive Report
 *
 * Used by:
 *
 * - ExecutiveAnalyticsService
 * - ExecutiveReportService
 * - Dashboard API
 * - AI Executive Summary
 */
class HSCodeAnalyticsService
{
    public function __construct(
        protected HSCodeTradeRepository $hsRepository,
        protected ExecutiveProductRepository $executiveRepository,
    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * HS Code Intelligence Dashboard
     * --------------------------------------------------------------------------
     */
    public function dashboard(
        string $hsCode,
        array $filters = []
    ): array {

        return [

            'summary' => $this->hsRepository
                ->summary($hsCode, $filters),

            'monthlyTrend' => $this->hsRepository
                ->monthlyTrend($hsCode, $filters),

            'topCountries' => $this->hsRepository
                ->topCountries($hsCode, $filters),

            'growth' => $this->hsRepository
                ->growth($hsCode, $filters),

            'seasonality' => $this->hsRepository
                ->seasonality($hsCode, $filters),

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Top HS Codes
     * --------------------------------------------------------------------------
     *
     * Used by:
     * - Dashboard
     * - Market Intelligence
     */
    public function topHsCodes(array $filters = []): array
    {
        return $this->executiveRepository
            ->topHsCodes($filters);
    }

    /**
     * --------------------------------------------------------------------------
     * Top Products
     * --------------------------------------------------------------------------
     *
     * Generic Product Ranking.
     *
     * Examples:
     *
     * Apparel:
     * ['61','62','63']
     *
     * Cotton:
     * ['52']
     *
     * Yarn:
     * ['50','51','52','54','55']
     *
     * Fabric:
     * ['52','54','55','58','60']
     *
     * The product category is determined entirely
     * by the supplied filters.
     */
    public function topProducts(array $filters = []): array
    {
        return $this->executiveRepository
            ->topProducts($filters);
    }
}