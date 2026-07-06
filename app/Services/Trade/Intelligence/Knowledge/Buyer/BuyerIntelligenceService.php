<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Knowledge\Buyer;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Buyer Intelligence Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Provide reusable buyer intelligence knowledge for the global textile
 * and apparel industry to support executive decision making,
 * commercial strategy, negotiation planning, and long-term partnership
 * development.
 *
 * Business Questions
 * --------------------------------------------------------------------------
 * • What type of buyer is this?
 * • How mature is the buyer relationship?
 * • Is the buyer price-driven or value-driven?
 * • What commercial strategy is recommended?
 * • What executive actions should be considered?
 *
 * Business Value
 * --------------------------------------------------------------------------
 * Manufacturer
 *
 * • Improve customer segmentation
 * • Improve negotiation strategy
 * • Protect business margin
 * • Build sustainable partnerships
 *
 * Buyer
 *
 * • Build healthier long-term collaboration
 * • Improve supply reliability
 *
 * Industry
 *
 * • Promote sustainable business relationships
 *
 * Knowledge Sources
 * --------------------------------------------------------------------------
 * • Textile Industry Experience
 * • International Buyer Behaviour
 * • Executive Commercial Practices
 * • Global Apparel Supply Chain
 *
 * This service NEVER performs analytics.
 *
 * It provides reusable buyer knowledge.
 *
 * Used by:
 *
 * - RecommendationService
 * - RiskAnalysisService
 * - OpportunityService
 * - CommercialKnowledgeService
 * - ExecutiveSummaryService
 * - AIExecutiveSummaryService
 * - KnowledgeOrchestrator
 */
class BuyerIntelligenceService
{
    /**
     * --------------------------------------------------------------------------
     * Buyer Knowledge Base
     * --------------------------------------------------------------------------
     */
    protected const BUYERS = [

        /*
        |--------------------------------------------------------------------------
        | Strategic Partner
        |--------------------------------------------------------------------------
        */

        'STRATEGIC' => [

            'type' => 'Strategic Partner',

            'maturityLevel' => 5,

            'relationship' => 'Long-term',

            'businessModel' => 'Collaborative',

            'priceSensitivity' => 'Medium',

            'qualitySensitivity' => 'Very High',

            'complianceRequirement' => 'Very High',

            'innovationExpectation' => 'Very High',

            'forecastSharing' => true,

            'preferredContract' => 'Long-term',

            'paymentReliability' => 'High',

            'partnershipScore' => 95,

            'riskLevel' => 'Low',

            'recommendedApproach' =>
                'Strategic Partnership & Joint Business Planning',

        ],

        /*
        |--------------------------------------------------------------------------
        | Brand Owner
        |--------------------------------------------------------------------------
        */

        'BRAND' => [

            'type' => 'Brand Owner',

            'maturityLevel' => 4,

            'relationship' => 'Strategic',

            'businessModel' => 'Brand Driven',

            'priceSensitivity' => 'Medium',

            'qualitySensitivity' => 'Very High',

            'complianceRequirement' => 'Very High',

            'innovationExpectation' => 'Very High',

            'forecastSharing' => true,

            'preferredContract' => 'Seasonal',

            'paymentReliability' => 'High',

            'partnershipScore' => 88,

            'riskLevel' => 'Medium',

            'recommendedApproach' =>
                'Innovation Partnership',

        ],

        /*
        |--------------------------------------------------------------------------
        | Retail Chain
        |--------------------------------------------------------------------------
        */

        'RETAIL' => [

            'type' => 'Retail Chain',

            'maturityLevel' => 3,

            'relationship' => 'Medium-term',

            'businessModel' => 'Retail',

            'priceSensitivity' => 'High',

            'qualitySensitivity' => 'High',

            'complianceRequirement' => 'High',

            'innovationExpectation' => 'Medium',

            'forecastSharing' => true,

            'preferredContract' => 'Seasonal',

            'paymentReliability' => 'Medium',

            'partnershipScore' => 75,

            'riskLevel' => 'Medium',

            'recommendedApproach' =>
                'Demand-driven Collaboration',

        ],

        /*
        |--------------------------------------------------------------------------
        | Transactional Buyer
        |--------------------------------------------------------------------------
        */

        'TRANSACTIONAL' => [

            'type' => 'Transactional Buyer',

            'maturityLevel' => 1,

            'relationship' => 'Short-term',

            'businessModel' => 'Price Driven',

            'priceSensitivity' => 'Very High',

            'qualitySensitivity' => 'High',

            'complianceRequirement' => 'Medium',

            'innovationExpectation' => 'Low',

            'forecastSharing' => false,

            'preferredContract' => 'Spot',

            'paymentReliability' => 'Medium',

            'partnershipScore' => 40,

            'riskLevel' => 'High',

            'recommendedApproach' =>
                'Margin Protection & Commercial Negotiation',

        ],

    ];

    /**
     * --------------------------------------------------------------------------
     * Get Buyer Knowledge
     * --------------------------------------------------------------------------
     */
    public function get(string $buyerType): array
    {
        return self::BUYERS[strtoupper($buyerType)] ?? [];
    }

    /**
     * --------------------------------------------------------------------------
     * Strategic Buyer
     * --------------------------------------------------------------------------
     */
    public function isStrategic(string $buyerType): bool
    {
        return ($this->get($buyerType)['maturityLevel'] ?? 0) >= 4;
    }

    /**
     * --------------------------------------------------------------------------
     * Transactional Buyer
     * --------------------------------------------------------------------------
     */
    public function isTransactional(string $buyerType): bool
    {
        return ($this->get($buyerType)['maturityLevel'] ?? 0) <= 2;
    }

    /**
     * --------------------------------------------------------------------------
     * Shares Forecast
     * --------------------------------------------------------------------------
     */
    public function sharesForecast(string $buyerType): bool
    {
        return (bool) ($this->get($buyerType)['forecastSharing'] ?? false);
    }

    /**
     * --------------------------------------------------------------------------
     * Recommended Commercial Strategy
     * --------------------------------------------------------------------------
     */
    public function recommendedApproach(string $buyerType): string
    {
        return $this->get($buyerType)['recommendedApproach']
            ?? 'Standard Commercial Strategy';
    }

    /**
     * --------------------------------------------------------------------------
     * Partnership Score
     * --------------------------------------------------------------------------
     */
    public function partnershipScore(string $buyerType): int
    {
        return (int) ($this->get($buyerType)['partnershipScore'] ?? 0);
    }

    /**
     * --------------------------------------------------------------------------
     * Buyer Maturity Level
     * --------------------------------------------------------------------------
     */
    public function maturityLevel(string $buyerType): int
    {
        return (int) ($this->get($buyerType)['maturityLevel'] ?? 0);
    }

    /**
     * --------------------------------------------------------------------------
     * All Buyer Knowledge
     * --------------------------------------------------------------------------
     */
    public function all(): array
    {
        return self::BUYERS;
    }
}