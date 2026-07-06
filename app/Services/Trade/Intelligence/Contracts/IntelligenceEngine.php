<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Contracts;

use App\Services\Trade\Intelligence\DTO\IntelligenceResult;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Intelligence Engine Contract
 * ==========================================================================
 *
 * Standard contract for all Intelligence Engines.
 *
 * Every Intelligence Engine must implement this interface.
 *
 * Implementations:
 *
 * - GrowthScoreService
 * - DiversificationScoreService
 * - ConcentrationScoreService
 * - VolatilityScoreService
 * - ForecastConfidenceService
 * - OverallTradeScoreService
 * - TradeRadarService
 * - EarlyWarningService
 * - OpportunityService
 * - RiskAnalysisService
 * - RecommendationService
 * - ExecutiveSummaryService
 * - AIExecutiveSummaryService
 */
interface IntelligenceEngine
{
    /**
     * --------------------------------------------------------------------------
     * Analyze Intelligence
     * --------------------------------------------------------------------------
     *
     * Performs business intelligence analysis and returns
     * a standardized IntelligenceResult object.
     */
    public function analyze(
        array $filters = []
    ): IntelligenceResult;
}