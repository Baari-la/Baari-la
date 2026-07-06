<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Executive;

use App\Services\Trade\Analytics\CountryAnalyticsService;
use App\Services\Trade\Analytics\ExecutiveAnalyticsService;
use App\Services\Trade\Analytics\HSCodeAnalyticsService;
use App\Services\Trade\Intelligence\Core\TradeAggregationService;
use App\Services\Trade\Intelligence\Forecast\TradeForecastService;
use App\Services\Trade\Score\GrowthScoreService;
use App\Services\Trade\Score\DiversificationScoreService;
use App\Services\Trade\Score\ConcentrationScoreService;
use App\Services\Trade\Score\VolatilityScoreService;
use App\Services\Trade\Score\ForecastConfidenceService;
use App\Services\Trade\Score\OverallTradeScoreService;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Trade Radar Service
 * ==========================================================================
 *
 * Executive Business Intelligence Engine.
 *
 * Responsible for:
 *
 * - Overall Trade Score
 * - Growth Analysis
 * - Market Diversification
 * - Market Concentration
 * - Product Performance
 * - Emerging Markets
 * - Volatility Index
 *
 * This service NEVER queries the database directly.
 *
 * Used by:
 *
 * - ExecutiveSummaryService
 * - EarlyWarningService
 * - OpportunityService
 * - RiskAnalysisService
 * - RecommendationService
 * - ExecutiveReportService
 */
class TradeRadarService
{
    public function __construct(

        protected ExecutiveAnalyticsService $executiveAnalytics,
        protected CountryAnalyticsService $countryAnalytics,
        protected HSCodeAnalyticsService $hsCodeAnalytics,
        protected TradeAggregationService $aggregation,
        protected TradeForecastService $forecast,
        protected GrowthScoreService $growthScore,
        protected DiversificationScoreService $diversificationScore,
        protected ConcentrationScoreService $concentrationScore,
        protected VolatilityScoreService $volatilityScore,
        protected ForecastConfidenceService $forecastConfidence,
        protected OverallTradeScoreService $overallTradeScore,
    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Analyze Trade Radar
     * --------------------------------------------------------------------------
     */
    public function analyze(array $filters = []): array
    {
        $score = $this->buildScore($filters);

        return [

            /*
            |--------------------------------------------------------------------------
            | Executive KPI
            |--------------------------------------------------------------------------
            */

            'overallScore' => $score['overall'],

            /*
            |--------------------------------------------------------------------------
            | Score Engine
            |--------------------------------------------------------------------------
            */

            'score' => $score,

            /*
            |--------------------------------------------------------------------------
            | Executive Status
            |--------------------------------------------------------------------------
            */

            'status' => $this->buildStatus($score),

            /*
            |--------------------------------------------------------------------------
            | Intelligence
            |--------------------------------------------------------------------------
            */

            'intelligence' => $this->buildIntelligence($filters),

            /*
            |--------------------------------------------------------------------------
            | Metadata
            |--------------------------------------------------------------------------
            */

            'metadata' => $this->buildMetadata(),

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Score Engine
     * --------------------------------------------------------------------------
     */
    protected function buildScore(array $filters = []): array
    {
        $growth = $this->growthScore->score($filters);
        $diversification = $this->diversificationScore->score($filters);
        $concentration = $this->concentrationScore->score($filters);
        $volatility = $this->volatilityScore->score($filters);
        $forecast = $this->forecastConfidence->score($filters);
        $overall = $this->overallTradeScore->score([
            'growth' => $growth,
            'diversification' => $diversification,
            'concentration' => $concentration,
            'volatility' => $volatility,
            'forecast' => $forecast,
        ]);

        return [
            'overall' => $overall,
            'growth' => $growth,
            'diversification' => $diversification,
            'concentration' => $concentration,
            'volatility' => $volatility,
            'forecastConfidence' => $forecast,
        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Executive Status
     * --------------------------------------------------------------------------
     */
    protected function buildStatus(array $score): array
    {
        $overall = $score['overall']['score'] ?? 0;

        return [

            'marketHealth' => [

                'label' => match (true) {

                    $overall >= 90 => 'Excellent',
                    $overall >= 80 => 'Strong',
                    $overall >= 70 => 'Healthy',
                    $overall >= 60 => 'Moderate',
                    default => 'Weak',
                },

                'score' => $overall,
            ],

            'riskLevel' => match (true) {
                $overall >= 80 => 'Low',
                $overall >= 60 => 'Medium',
                default => 'High',

            },

            'opportunityLevel' => match (true) {

                $overall >= 85 => 'Very High',
                $overall >= 70 => 'High',
                $overall >= 55 => 'Moderate',
                default => 'Limited',
            },
        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Executive Intelligence
     * --------------------------------------------------------------------------
     */
    protected function buildIntelligence(array $filters = []): array
    {
        return [

            'summary' => null,

            'countries' => [

                'top' => $this->countryAnalytics->topCountries($filters),

                'emerging' => [],

                'declining' => [],

            ],

            'products' => [

                'top' => $this->hsCodeAnalytics->topProducts($filters),

                'emerging' => [],

                'declining' => [],

            ],

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Metadata
     * --------------------------------------------------------------------------
     */
    protected function buildMetadata(): array
    {
        return [
            'generated_at' => now()->toDateTimeString(),
            'engine' => 'TradeRadar',
            'engine_version' => '1.0.0',
            'algorithm_version' => '1.0',
            'data_source' => 'Kemendag RI',
        ];
    }
}