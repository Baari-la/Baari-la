<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Knowledge\SupplyChain;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Supply Chain Knowledge Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Provide reusable supply chain knowledge across the global textile
 * and apparel industry.
 *
 * Business Questions
 * --------------------------------------------------------------------------
 * • Where is the current bottleneck?
 * • Which stage has the highest commercial risk?
 * • Which stage requires strategic partnership?
 * • How can supply continuity be improved?
 * • Which inventory strategy should be applied?
 *
 * Business Value
 * --------------------------------------------------------------------------
 * Manufacturer
 *
 * • Improve production planning
 * • Reduce supply disruption
 * • Improve inventory control
 * • Improve supplier collaboration
 *
 * Buyer
 *
 * • Improve delivery reliability
 * • Improve production visibility
 *
 * Industry
 *
 * • Encourage resilient supply chains
 * • Improve industry competitiveness
 *
 * Knowledge Sources
 * --------------------------------------------------------------------------
 * • Textile Manufacturing Experience
 * • Global Supply Chain Practices
 * • Apparel Manufacturing
 * • Executive Industry Experience
 *
 * This service NEVER performs analytics.
 *
 * It provides reusable supply chain knowledge.
 *
 * Used by:
 *
 * - RecommendationService
 * - RiskAnalysisService
 * - MarketInsightService
 * - ExecutiveSummaryService
 * - AIExecutiveSummaryService
 * - KnowledgeOrchestrator
 */
class SupplyChainKnowledgeService
{
    /**
     * --------------------------------------------------------------------------
     * Supply Chain Knowledge Base
     * --------------------------------------------------------------------------
     */
    protected const STAGES = [

        /*
        |--------------------------------------------------------------------------
        | Fiber
        |--------------------------------------------------------------------------
        */

        'FIBER' => [

            'stage' => 'Fiber',

            'businessPurpose' => 'Raw Material',

            'inventoryStrategy' => 'Safety Stock',

            'leadTime' => 'Medium',

            'riskLevel' => 'High',

            'collaboration' => 'Strategic Supplier',

            'recommendation' =>
                'Secure long-term raw material availability.',

        ],

        /*
        |--------------------------------------------------------------------------
        | Spinning
        |--------------------------------------------------------------------------
        */

        'SPINNING' => [

            'stage' => 'Spinning',

            'businessPurpose' => 'Yarn Production',

            'inventoryStrategy' => 'Demand Planning',

            'leadTime' => 'Medium',

            'riskLevel' => 'Medium',

            'collaboration' => 'Preferred Supplier',

            'recommendation' =>
                'Optimize production scheduling.',

        ],

        /*
        |--------------------------------------------------------------------------
        | Fabric Manufacturing
        |--------------------------------------------------------------------------
        */

        'FABRIC' => [

            'stage' => 'Fabric',

            'businessPurpose' => 'Weaving / Knitting',

            'inventoryStrategy' => 'Rolling Forecast',

            'leadTime' => 'Medium',

            'riskLevel' => 'Medium',

            'collaboration' => 'Production Partnership',

            'recommendation' =>
                'Improve production flexibility.',

        ],

        /*
        |--------------------------------------------------------------------------
        | Dyeing & Finishing
        |--------------------------------------------------------------------------
        */

        'FINISHING' => [

            'stage' => 'Dyeing & Finishing',

            'businessPurpose' => 'Value Addition',

            'inventoryStrategy' => 'Production Based',

            'leadTime' => 'High',

            'riskLevel' => 'High',

            'collaboration' => 'Capacity Planning',

            'recommendation' =>
                'Maintain chemical and utility availability.',

        ],

        /*
        |--------------------------------------------------------------------------
        | Garment Manufacturing
        |--------------------------------------------------------------------------
        */

        'GARMENT' => [

            'stage' => 'Garment',

            'businessPurpose' => 'Final Assembly',

            'inventoryStrategy' => 'Order Based',

            'leadTime' => 'Medium',

            'riskLevel' => 'Medium',

            'collaboration' => 'Buyer Collaboration',

            'recommendation' =>
                'Synchronize production with buyer forecast.',

        ],

        /*
        |--------------------------------------------------------------------------
        | Distribution
        |--------------------------------------------------------------------------
        */

        'DISTRIBUTION' => [

            'stage' => 'Distribution',

            'businessPurpose' => 'Global Delivery',

            'inventoryStrategy' => 'Logistics Planning',

            'leadTime' => 'High',

            'riskLevel' => 'High',

            'collaboration' => 'Logistics Partner',

            'recommendation' =>
                'Improve logistics visibility and shipment planning.',

        ],

    ];

    /**
     * --------------------------------------------------------------------------
     * Get Supply Chain Knowledge
     * --------------------------------------------------------------------------
     */
    public function get(string $stage): array
    {
        return self::STAGES[strtoupper($stage)] ?? [];
    }

    /**
     * --------------------------------------------------------------------------
     * Risk Level
     * --------------------------------------------------------------------------
     */
    public function riskLevel(string $stage): string
    {
        return $this->get($stage)['riskLevel'] ?? 'Unknown';
    }

    /**
     * --------------------------------------------------------------------------
     * Lead Time
     * --------------------------------------------------------------------------
     */
    public function leadTime(string $stage): string
    {
        return $this->get($stage)['leadTime'] ?? 'Unknown';
    }

    /**
     * --------------------------------------------------------------------------
     * Inventory Strategy
     * --------------------------------------------------------------------------
     */
    public function inventoryStrategy(string $stage): string
    {
        return $this->get($stage)['inventoryStrategy']
            ?? 'Standard';
    }

    /**
     * --------------------------------------------------------------------------
     * Recommended Collaboration
     * --------------------------------------------------------------------------
     */
    public function collaboration(string $stage): string
    {
        return $this->get($stage)['collaboration']
            ?? 'Standard';
    }

    /**
     * --------------------------------------------------------------------------
     * Recommendation
     * --------------------------------------------------------------------------
     */
    public function recommendation(string $stage): string
    {
        return $this->get($stage)['recommendation']
            ?? '';
    }

    /**
     * --------------------------------------------------------------------------
     * All Supply Chain Knowledge
     * --------------------------------------------------------------------------
     */
    public function all(): array
    {
        return self::STAGES;
    }
}