<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Executive\Score;

use App\Services\Trade\Analytics\ExecutiveAnalyticsService;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Growth Score Service
 * ==========================================================================
 *
 * Calculates Executive Growth Score.
 *
 * Responsible for:
 *
 * - Export Growth Score
 * - Trade Momentum Score
 * - Growth KPI
 *
 * This service NEVER queries the database directly.
 *
 * Data source:
 *
 * - ExecutiveAnalyticsService
 *
 * Used by:
 *
 * - OverallTradeScoreService
 * - TradeRadarService
 */
class GrowthScoreService
{
    public function __construct(
        protected ExecutiveAnalyticsService $analytics,
    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Analyze Growth Score
     * --------------------------------------------------------------------------
     *
     * Returns score between 0–100.
     *
     * Sprint 1.2:
     * Framework only.
     *
     * Sprint 1.3:
     * Score will be calculated using actual growth percentage.
     */
    public function analyze(array $filters = []): float
    {
        return 0.0;
    }
}