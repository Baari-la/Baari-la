<?php

declare(strict_types=1);

namespace App\Services\Trade\Analytics;

use App\Repositories\Trade\Executive\ExecutiveSummaryRepository;
use App\Repositories\Trade\Executive\ExecutiveComparisonRepository;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Trade Analytics Service
 * ==========================================================================
 *
 * Business Intelligence layer for Executive Trade Analytics.
 *
 * Responsible for:
 *
 * - Executive KPI Summary
 * - Monthly Comparison
 * - Monthly Pieces Comparison
 */
class TradeAnalyticsService
{
    public function __construct(

        protected ExecutiveSummaryRepository $summaryRepository,

        protected ExecutiveComparisonRepository $comparisonRepository,

    ) {
    }

    /**
     * Executive KPI Summary
     */
    public function summary(array $filters = []): array
    {
        return $this->summaryRepository
            ->summary($filters);
    }

    /**
     * Monthly Comparison (USD)
     */
    public function monthlyComparison(array $filters = []): array
    {
        return $this->comparisonRepository
            ->monthlyComparison($filters);
    }

    /**
     * Monthly Comparison (Estimated Pieces)
     */
    public function monthlyComparisonPieces(array $filters = []): array
    {
        return $this->comparisonRepository
            ->monthlyComparisonPieces($filters);
    }
}