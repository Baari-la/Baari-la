<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Executive;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Opportunity Service
 * ==========================================================================
 *
 * Executive Opportunity Intelligence Engine.
 *
 * Responsible for:
 *
 * - Identify export opportunities
 * - Identify investment opportunities
 * - Identify production opportunities
 * - Identify market opportunities
 *
 * Data Source:
 *
 * - TradeRadarService
 *
 * This service NEVER:
 *
 * - Queries database
 * - Calculates trade statistics
 * - Calculates score
 *
 * This service ONLY evaluates business opportunities
 * from Executive Trade Radar.
 *
 * Used by:
 *
 * - RecommendationService
 * - IntelligenceOrchestrator
 * - Executive Dashboard
 * - Executive PDF
 */
class OpportunityService
{
    /**
     * --------------------------------------------------------------------------
     * Opportunity Thresholds
     * --------------------------------------------------------------------------
     */
    protected const THRESHOLDS = [

        'overall' => 85,

        'growth' => 80,

        'forecast' => 80,

        'diversification' => 75,

    ];

    /**
     * --------------------------------------------------------------------------
     * Build Opportunity Intelligence
     * --------------------------------------------------------------------------
     */
    public function build(array $tradeRadar): array
    {
        $opportunities = [];

        /*
        |--------------------------------------------------------------------------
        | Evaluate Opportunities
        |--------------------------------------------------------------------------
        */

        $this->checkExportOpportunity(
            $opportunities,
            $tradeRadar
        );

        $this->checkInvestmentOpportunity(
            $opportunities,
            $tradeRadar
        );

        $this->checkProductionOpportunity(
            $opportunities,
            $tradeRadar
        );

        $this->checkMarketOpportunity(
            $opportunities,
            $tradeRadar
        );

        /*
        |--------------------------------------------------------------------------
        | Executive Priority
        |--------------------------------------------------------------------------
        */

        usort(
            $opportunities,
            fn ($a, $b) => $a['priority'] <=> $b['priority']
        );

        return [

            /*
            |--------------------------------------------------------------------------
            | Opportunity Index
            |--------------------------------------------------------------------------
            */

            'opportunityScore'
                => $this->calculateOpportunityScore(
                    $opportunities
                ),

            /*
            |--------------------------------------------------------------------------
            | Opportunity List
            |--------------------------------------------------------------------------
            */

            'opportunities'
                => $opportunities,

            /*
            |--------------------------------------------------------------------------
            | Statistics
            |--------------------------------------------------------------------------
            */

            'statistics'
                => $this->statistics(
                    $opportunities
                ),

            /*
            |--------------------------------------------------------------------------
            | Executive Priority
            |--------------------------------------------------------------------------
            */

            'executivePriority' => [

                'highest'
                    => $opportunities[0] ?? null,

            ],

            /*
            |--------------------------------------------------------------------------
            | Metadata
            |--------------------------------------------------------------------------
            */

            'metadata' => [

                'engine'
                    => 'Opportunity',

                'engine_version'
                    => '1.0.0',

                'algorithm_version'
                    => '1.0',

                'generated_at'
                    => now()->toDateTimeString(),

            ],

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Export Opportunity
     * --------------------------------------------------------------------------
     */
    protected function checkExportOpportunity(
        array &$items,
        array $tradeRadar
    ): void {

        $growth = $tradeRadar['score']['growth']['score'] ?? 0;

        $risk = $tradeRadar['status']['riskLevel'] ?? 'High';

        if (
            $growth < self::THRESHOLDS['growth']
            || $risk !== 'Low'
        ) {
            return;
        }

        $items[] = $this->opportunity(

            id: 'OP001',

            level: 'HIGH',

            category: 'Export',

            title: 'Expand Export Market',

            description:
                'Current trade conditions support export expansion.',

            reason:
                'Strong export growth with low market risk.',

            action:
                'Prioritize expansion into high-growth destination markets.',

            score: $growth,

            priority: 1

        );
    }

    /**
     * --------------------------------------------------------------------------
     * Investment Opportunity
     * --------------------------------------------------------------------------
     */
    protected function checkInvestmentOpportunity(
        array &$items,
        array $tradeRadar
    ): void {

        $overall = $tradeRadar['overallScore']['score'] ?? 0;

        if (
            $overall < self::THRESHOLDS['overall']
        ) {
            return;
        }

        $items[] = $this->opportunity(

            id: 'OP002',

            level: 'HIGH',

            category: 'Investment',

            title: 'Accelerate Strategic Investment',

            description:
                'Overall market conditions remain highly favorable.',

            reason:
                'Executive Trade Score remains strong.',

            action:
                'Evaluate strategic expansion and capital investment.',

            score: $overall,

            priority: 2

        );
    }

    /**
     * --------------------------------------------------------------------------
     * Production Opportunity
     * --------------------------------------------------------------------------
     */
    protected function checkProductionOpportunity(
        array &$items,
        array $tradeRadar
    ): void {

        $forecast =
            $tradeRadar['score']['forecastConfidence']['score']
            ?? 0;

        if (
            $forecast < self::THRESHOLDS['forecast']
        ) {
            return;
        }

        $items[] = $this->opportunity(

            id: 'OP003',

            level: 'MEDIUM',

            category: 'Production',

            title: 'Increase Production Capacity',

            description:
                'Forecast indicates sustainable market demand.',

            reason:
                'Forecast confidence remains high.',

            action:
                'Review production planning and capacity allocation.',

            score: $forecast,

            priority: 3

        );
    }

    /**
     * --------------------------------------------------------------------------
     * Market Opportunity
     * --------------------------------------------------------------------------
     */
    protected function checkMarketOpportunity(
        array &$items,
        array $tradeRadar
    ): void {

        $diversification =
            $tradeRadar['score']['diversification']['score']
            ?? 0;

        if (
            $diversification < self::THRESHOLDS['diversification']
        ) {
            return;
        }

        $items[] = $this->opportunity(

            id: 'OP004',

            level: 'MEDIUM',

            category: 'Market',

            title: 'Expand into Premium Markets',

            description:
                'Current export diversification supports market expansion.',

            reason:
                'Healthy export diversification.',

            action:
                'Evaluate premium and emerging market opportunities.',

            score: $diversification,

            priority: 4

        );
    }
        /**
     * --------------------------------------------------------------------------
     * Opportunity Factory
     * --------------------------------------------------------------------------
     */
    protected function opportunity(
        string $id,
        string $level,
        string $category,
        string $title,
        string $description,
        string $reason,
        string $action,
        float|int $score,
        int $priority
    ): array {

        return [

            /*
            |--------------------------------------------------------------------------
            | Identity
            |--------------------------------------------------------------------------
            */

            'id' => $id,

            'category' => $category,

            /*
            |--------------------------------------------------------------------------
            | Executive Priority
            |--------------------------------------------------------------------------
            */

            'priority' => $priority,

            'level' => $level,

            /*
            |--------------------------------------------------------------------------
            | Business Information
            |--------------------------------------------------------------------------
            */

            'title' => $title,

            'description' => $description,

            'reason' => $reason,

            'action' => $action,

            /*
            |--------------------------------------------------------------------------
            | Opportunity Assessment
            |--------------------------------------------------------------------------
            */

            'score' => round((float) $score, 1),

            'confidence' => round((float) $score, 1),

            'impact' => match ($level) {

                'HIGH' => 'High',

                'MEDIUM' => 'Medium',

                default => 'Low',

            },

            /*
            |--------------------------------------------------------------------------
            | UI
            |--------------------------------------------------------------------------
            */

            'color' => match ($level) {

                'HIGH' => 'green',

                'MEDIUM' => 'blue',

                default => 'gray',

            },

            'icon' => match ($category) {

                'Export' => 'globe',

                'Investment' => 'badge-dollar-sign',

                'Production' => 'factory',

                'Market' => 'line-chart',

                default => 'circle',

            },

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Opportunity Statistics
     * --------------------------------------------------------------------------
     */
    protected function statistics(array $items): array
    {
        return [

            'total' => count($items),

            'high' => count(array_filter(

                $items,

                fn ($item) => $item['level'] === 'HIGH'

            )),

            'medium' => count(array_filter(

                $items,

                fn ($item) => $item['level'] === 'MEDIUM'

            )),

            'low' => count(array_filter(

                $items,

                fn ($item) => $item['level'] === 'LOW'

            )),

            'averageScore' => empty($items)
                ? 0
                : round(

                    array_sum(array_column($items, 'score'))
                    / count($items),

                    1

                ),

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Opportunity Index
     * --------------------------------------------------------------------------
     *
     * Overall executive opportunity score.
     */
    protected function calculateOpportunityScore(array $items): float
    {
        if (empty($items)) {
            return 0;
        }

        return round(

            array_sum(array_column($items, 'score'))
            / count($items),

            1

        );
    }
}