<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Executive\Score;

use App\Services\Trade\Analytics\ExecutiveAnalyticsService;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Forecast Confidence Service
 * ==========================================================================
 *
 * Calculates Forecast Confidence Score.
 *
 * Responsible for:
 *
 * - Forecast Reliability
 * - Prediction Confidence
 * - Future Trade Outlook
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
class ForecastConfidenceService
{
    public function __construct(
        protected ExecutiveAnalyticsService $analytics,
    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Analyze Forecast Confidence
     * --------------------------------------------------------------------------
     *
     * Returns score between 0–100.
     *
     * Sprint 1.2:
     * Framework only.
     *
     * Sprint 1.3:
     * Score will be calculated using forecast confidence level.
     */
    public function analyze(array $filters = []): float
    {
        return 0.0;
    }
}