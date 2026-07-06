<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Executive;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Intelligence Orchestrator
 * ==========================================================================
 *
 * Master orchestration layer for Executive Intelligence.
 *
 * Responsibilities:
 *
 * - Coordinate all Intelligence Engines
 * - Produce a unified Executive Intelligence Object
 *
 * This service NEVER performs calculations.
 *
 * Used by:
 *
 * - ExecutiveReportService
 * - Dashboard
 * - REST API
 * - Mobile App
 * - AIExecutiveSummaryService
 */
class IntelligenceOrchestrator
{
    public function __construct(

        protected TradeRadarService $tradeRadar,

        protected ExecutiveSummaryService $executiveSummary,

        protected EarlyWarningService $earlyWarning,

        protected OpportunityService $opportunity,

        protected RiskAnalysisService $riskAnalysis,

        protected RecommendationService $recommendation,

    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Build Executive Intelligence
     * --------------------------------------------------------------------------
     */
    public function build(array $filters = []): array
    {
        /*
        |--------------------------------------------------------------------------
        | Trade Radar
        |--------------------------------------------------------------------------
        */

        $tradeRadarResult = $this->tradeRadar->analyze($filters);

        /*
        |--------------------------------------------------------------------------
        | Executive Summary
        |--------------------------------------------------------------------------
        */

        $summary = $this->executiveSummary->build(
            $tradeRadarResult
        );

        /*
        |--------------------------------------------------------------------------
        | Executive Intelligence Object
        |--------------------------------------------------------------------------
        */

        $intelligence = [

            /*
            |--------------------------------------------------------------------------
            | Executive KPI
            |--------------------------------------------------------------------------
            */

            'overallScore' => $tradeRadarResult['overallScore'] ?? [],

            /*
            |--------------------------------------------------------------------------
            | Intelligence Engines
            |--------------------------------------------------------------------------
            */

            'tradeRadar' => $tradeRadarResult,

            'executiveSummary' => $summary,

            'earlyWarnings' => $this->earlyWarning
                ->build($tradeRadarResult),

            'opportunities' => $this->opportunity
                ->build($tradeRadarResult),

            'riskAnalysis' => $this->riskAnalysis
                ->build($tradeRadarResult),

            'recommendations' => $this->recommendation
                ->build($tradeRadarResult),

            /*
            |--------------------------------------------------------------------------
            | Future Intelligence
            |--------------------------------------------------------------------------
            */

            'intelligence' => [

                'supplyChain' => null,

                'marketInsight' => null,

                'competitor' => null,

            ],

            /*
            |--------------------------------------------------------------------------
            | Engine Status
            |--------------------------------------------------------------------------
            */

            'engineStatus' => [

                'tradeRadar' => true,

                'executiveSummary' => true,

                'earlyWarning' => true,

                'opportunity' => true,

                'riskAnalysis' => true,

                'recommendation' => true,

            ],

            /*
            |--------------------------------------------------------------------------
            | Metadata
            |--------------------------------------------------------------------------
            */

            'metadata' => [

                'generated_at' => now()->toDateTimeString(),

                'engine' => 'IntelligenceOrchestrator',

                'engine_version' => '1.0.0',

                'algorithm_version' => '1.0',

            ],

        ];

        return $intelligence;
    }
}