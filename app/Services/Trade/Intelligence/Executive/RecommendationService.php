<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Executive;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Executive Recommendation Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Transform Executive Intelligence into strategic business recommendations
 * for textile and apparel executives.
 *
 * Business Questions
 * --------------------------------------------------------------------------
 * • What actions should management consider?
 * • How can export performance be improved?
 * • How can business risks be reduced?
 * • How can commercial sustainability be strengthened?
 * • How can long-term buyer relationships be maintained?
 *
 * Business Value
 * --------------------------------------------------------------------------
 * Manufacturer
 *
 * • Improve executive decision quality
 * • Protect profitability
 * • Support sustainable growth
 *
 * Buyer
 *
 * • Strengthen strategic partnerships
 * • Improve supply reliability
 *
 * Industry
 *
 * • Encourage sustainable business
 * • Promote healthy competition
 *
 * Knowledge Sources
 * --------------------------------------------------------------------------
 * • Trade Analytics
 * • Executive Intelligence
 * • Textile Industry Knowledge
 * • Commercial Best Practices
 * • Buyer–Supplier Relationship
 *
 * This service NEVER makes decisions.
 *
 * It provides executive recommendations
 * supported by business intelligence.
 *
 * Used by:
 *
 * - IntelligenceOrchestrator
 * - ExecutiveSummaryService
 * - ExecutiveReportService
 * - AIExecutiveSummaryService
 */
class RecommendationService
{
    /**
     * --------------------------------------------------------------------------
     * Build Executive Recommendations
     * --------------------------------------------------------------------------
     */
    public function build(array $tradeRadar): array
    {
        $recommendations = [];

        /*
        |--------------------------------------------------------------------------
        | Trade Recommendation
        |--------------------------------------------------------------------------
        */

        $this->buildTradeRecommendation(
            $recommendations,
            $tradeRadar
        );

        /*
        |--------------------------------------------------------------------------
        | Market Recommendation
        |--------------------------------------------------------------------------
        */

        $this->buildMarketRecommendation(
            $recommendations,
            $tradeRadar
        );

        /*
        |--------------------------------------------------------------------------
        | Commercial Recommendation
        |--------------------------------------------------------------------------
        */

        $this->buildCommercialRecommendation(
            $recommendations,
            $tradeRadar
        );

        /*
        |--------------------------------------------------------------------------
        | Relationship Recommendation
        |--------------------------------------------------------------------------
        */

        $this->buildRelationshipRecommendation(
            $recommendations,
            $tradeRadar
        );

        /*
        |--------------------------------------------------------------------------
        | Strategic Recommendation
        |--------------------------------------------------------------------------
        */

        $this->buildStrategicRecommendation(
            $recommendations,
            $tradeRadar
        );

        usort(

            $recommendations,

            fn ($a, $b) => $a['priority'] <=> $b['priority']

        );

        return [

            'recommendations' => $recommendations,

            'statistics' => $this->statistics($recommendations),

            'metadata' => [

                'engine' => 'Recommendation',

                'engine_version' => '1.0.0',

                'algorithm_version' => '1.0',

                'generated_at' => now()->toDateTimeString(),

            ],

        ];
    }
        /**
     * --------------------------------------------------------------------------
     * Trade Recommendation
     * --------------------------------------------------------------------------
     *
     * Improve overall trade performance.
     */
    protected function buildTradeRecommendation(
        array &$recommendations,
        array $tradeRadar
    ): void {

        $growth = $tradeRadar['score']['growth']['score'] ?? 100;

        if ($growth >= 70) {
            return;
        }

        $recommendations[] = $this->recommendation(

            id: 'RC001',

            level: 'HIGH',

            category: 'Trade',

            title: 'Strengthen Export Growth',

            description: 'Export growth is below the expected performance level.',

            reason: 'Growth momentum has weakened based on Trade Radar analysis.',

            recommendedAction:
                'Review export strategy and strengthen high-potential markets.',

            expectedBenefit:
                'Improve export growth and reduce dependence on existing markets.',

            score: $growth,

            priority: 1

        );
    }

    /**
     * --------------------------------------------------------------------------
     * Market Recommendation
     * --------------------------------------------------------------------------
     *
     * Diversify export markets.
     */
    protected function buildMarketRecommendation(
        array &$recommendations,
        array $tradeRadar
    ): void {

        $diversification =
            $tradeRadar['score']['diversification']['score'] ?? 100;

        if ($diversification >= 70) {
            return;
        }

        $recommendations[] = $this->recommendation(

            id: 'RC002',

            level: 'MEDIUM',

            category: 'Market',

            title: 'Increase Market Diversification',

            description:
                'Current exports remain concentrated in limited markets.',

            reason:
                'Market diversification score is below the recommended level.',

            recommendedAction:
                'Evaluate new consumer markets while maintaining strategic existing buyers.',

            expectedBenefit:
                'Improve resilience against regional demand fluctuations.',

            score: $diversification,

            priority: 2

        );
    }

    /**
     * --------------------------------------------------------------------------
     * Commercial Recommendation
     * --------------------------------------------------------------------------
     *
     * Protect business margins.
     */
    protected function buildCommercialRecommendation(
        array &$recommendations,
        array $tradeRadar
    ): void {

        $forecast =
            $tradeRadar['score']['forecastConfidence']['score'] ?? 100;

        if ($forecast >= 70) {
            return;
        }

        $recommendations[] = $this->recommendation(

            id: 'RC003',

            level: 'HIGH',

            category: 'Commercial',

            title: 'Review Long-term Contract Assumptions',

            description:
                'Future margin may be affected by changing market conditions.',

            reason:
                'Forecast confidence indicates increasing business uncertainty.',

            recommendedAction:
                'Review quotation assumptions and consider raw material adjustment clauses during negotiation.',

            expectedBenefit:
                'Reduce margin erosion and improve contract sustainability.',

            score: $forecast,

            priority: 1

        );
    }

    /**
     * --------------------------------------------------------------------------
     * Relationship Recommendation
     * --------------------------------------------------------------------------
     *
     * Future:
     *
     * Buyer Intelligence
     * Supplier Intelligence
     * Partnership Intelligence
     */
    protected function buildRelationshipRecommendation(
        array &$recommendations,
        array $tradeRadar
    ): void {

        /*
        |--------------------------------------------------------------------------
        | Placeholder
        |--------------------------------------------------------------------------
        |
        | Version 1.0
        |
        | This recommendation will use:
        |
        | Buyer Intelligence
        | Relationship Intelligence
        | Partnership Intelligence
        |
        */

    }

    /**
     * --------------------------------------------------------------------------
     * Strategic Recommendation
     * --------------------------------------------------------------------------
     *
     * Executive-level recommendation.
     */
    protected function buildStrategicRecommendation(
        array &$recommendations,
        array $tradeRadar
    ): void {

        $overall =
            $tradeRadar['score']['overall']['score'] ?? 100;

        if ($overall >= 75) {
            return;
        }

        $recommendations[] = $this->recommendation(

            id: 'RC005',

            level: 'HIGH',

            category: 'Strategic',

            title: 'Strengthen Long-term Competitiveness',

            description:
                'Current executive indicators suggest strengthening long-term competitiveness.',

            reason:
                'Overall Executive Trade Score remains below strategic target.',

            recommendedAction:
                'Strengthen innovation, expand strategic partnerships, improve market diversification, and enhance commercial resilience.',

            expectedBenefit:
                'Improve long-term competitiveness across the textile and apparel value chain.',

            score: $overall,

            priority: 1

        );
    }
        /**
     * --------------------------------------------------------------------------
     * Recommendation Factory
     * --------------------------------------------------------------------------
     *
     * Standard Recommendation Object
     * used across Executive Intelligence.
     */
    protected function recommendation(
        string $id,
        string $level,
        string $category,
        string $title,
        string $description,
        string $reason,
        string $recommendedAction,
        string $expectedBenefit,
        float|int $score,
        int $priority,
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
            | Recommendation
            |--------------------------------------------------------------------------
            */

            'title' => $title,

            'description' => $description,

            'reason' => $reason,

            'recommendedAction' => $recommendedAction,

            'expectedBenefit' => $expectedBenefit,

            /*
            |--------------------------------------------------------------------------
            | Business Impact
            |--------------------------------------------------------------------------
            */

            'businessImpact' => [

                'manufacturer' =>
                    $this->manufacturerImpact($category),

                'buyer' =>
                    $this->buyerImpact($category),

                'ecosystem' =>
                    $this->ecosystemImpact($category),

            ],

            /*
            |--------------------------------------------------------------------------
            | Executive Assessment
            |--------------------------------------------------------------------------
            */

            'score' => round((float) $score, 1),

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

                'Trade' => 'activity',

                'Market' => 'globe',

                'Commercial' => 'badge-dollar-sign',

                'Relationship' => 'handshake',

                'Strategic' => 'briefcase',

                default => 'circle',

            },

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Executive Statistics
     * --------------------------------------------------------------------------
     */
    protected function statistics(array $recommendations): array
    {
        return [

            'total' => count($recommendations),

            'high' => count(array_filter(

                $recommendations,

                fn ($item) => $item['level'] === 'HIGH'

            )),

            'medium' => count(array_filter(

                $recommendations,

                fn ($item) => $item['level'] === 'MEDIUM'

            )),

            'low' => count(array_filter(

                $recommendations,

                fn ($item) => $item['level'] === 'LOW'

            )),

            'averageScore' => empty($recommendations)

                ? 0

                : round(

                    array_sum(array_column(
                        $recommendations,
                        'score'
                    )) / count($recommendations),

                    1

                ),

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Manufacturer Impact
     * --------------------------------------------------------------------------
     */
    protected function manufacturerImpact(
        string $category
    ): string {

        return match ($category) {

            'Trade' =>
                'Improve export performance and business resilience.',

            'Market' =>
                'Reduce dependency on existing export destinations.',

            'Commercial' =>
                'Protect profit margins during commercial negotiation.',

            'Relationship' =>
                'Strengthen long-term buyer relationships.',

            'Strategic' =>
                'Improve long-term competitiveness.',

            default =>
                'Support better executive decisions.',

        };
    }

    /**
     * --------------------------------------------------------------------------
     * Buyer Impact
     * --------------------------------------------------------------------------
     */
    protected function buyerImpact(
        string $category
    ): string {

        return match ($category) {

            'Commercial' =>
                'Improve supply continuity and pricing transparency.',

            'Relationship' =>
                'Strengthen strategic collaboration.',

            default =>
                'Improve supply reliability.',

        };
    }

    /**
     * --------------------------------------------------------------------------
     * Ecosystem Impact
     * --------------------------------------------------------------------------
     */
    protected function ecosystemImpact(
        string $category
    ): string {

        return match ($category) {

            'Commercial' =>
                'Promote sustainable commercial practices.',

            'Relationship' =>
                'Strengthen long-term industry partnerships.',

            'Strategic' =>
                'Improve ecosystem competitiveness.',

            default =>
                'Support a healthier textile ecosystem.',

        };
    }
}