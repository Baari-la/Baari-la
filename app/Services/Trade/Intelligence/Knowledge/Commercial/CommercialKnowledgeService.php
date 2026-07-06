<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Knowledge\Commercial;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Commercial Knowledge Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Provide reusable commercial knowledge for executive decision making
 * across the global textile and apparel industry.
 *
 * Business Questions
 * --------------------------------------------------------------------------
 * • What commercial model is appropriate?
 * • What negotiation strategy should be applied?
 * • How can business margins be protected?
 * • What contract approach should be considered?
 * • How can sustainable partnerships be developed?
 *
 * Business Value
 * --------------------------------------------------------------------------
 * Manufacturer
 *
 * • Protect business margins
 * • Improve negotiation quality
 * • Improve quotation accuracy
 * • Reduce commercial risk
 *
 * Buyer
 *
 * • Improve supply continuity
 * • Improve pricing transparency
 *
 * Industry
 *
 * • Encourage healthy commercial practices
 * • Promote long-term collaboration
 *
 * Knowledge Sources
 * --------------------------------------------------------------------------
 * • Textile Industry Experience
 * • International Commercial Practices
 * • Executive Negotiation Experience
 * • Global Supply Chain Management
 *
 * This service NEVER performs analytics.
 *
 * It provides reusable commercial knowledge.
 *
 * Used by:
 *
 * - RecommendationService
 * - RiskAnalysisService
 * - BuyerIntelligenceService
 * - BrandIntelligenceService
 * - AIExecutiveSummaryService
 * - KnowledgeOrchestrator
 */
class CommercialKnowledgeService
{
    /**
     * --------------------------------------------------------------------------
     * Commercial Knowledge Base
     * --------------------------------------------------------------------------
     */
    protected const MODELS = [

        /*
        |--------------------------------------------------------------------------
        | Strategic Partnership
        |--------------------------------------------------------------------------
        */

        'STRATEGIC' => [

            'businessModel' => 'Strategic Partnership',

            'contractType' => 'Long-term',

            'pricingModel' => 'Collaborative',

            'forecastSharing' => true,

            'marginProtection' => true,

            'rawMaterialAdjustment' => true,

            'capacityReservation' => true,

            'recommendedNegotiation' =>
                'Joint Business Planning',

        ],

        /*
        |--------------------------------------------------------------------------
        | Seasonal Business
        |--------------------------------------------------------------------------
        */

        'SEASONAL' => [

            'businessModel' => 'Seasonal',

            'contractType' => 'Seasonal',

            'pricingModel' => 'Negotiated',

            'forecastSharing' => true,

            'marginProtection' => true,

            'rawMaterialAdjustment' => true,

            'capacityReservation' => false,

            'recommendedNegotiation' =>
                'Season Planning',

        ],

        /*
        |--------------------------------------------------------------------------
        | Spot Business
        |--------------------------------------------------------------------------
        */

        'SPOT' => [

            'businessModel' => 'Spot Order',

            'contractType' => 'Spot',

            'pricingModel' => 'Market Price',

            'forecastSharing' => false,

            'marginProtection' => false,

            'rawMaterialAdjustment' => false,

            'capacityReservation' => false,

            'recommendedNegotiation' =>
                'Price Optimization',

        ],

        /*
        |--------------------------------------------------------------------------
        | OEM / ODM Collaboration
        |--------------------------------------------------------------------------
        */

        'OEM_ODM' => [

            'businessModel' => 'OEM / ODM',

            'contractType' => 'Development',

            'pricingModel' => 'Value Based',

            'forecastSharing' => true,

            'marginProtection' => true,

            'rawMaterialAdjustment' => true,

            'capacityReservation' => true,

            'recommendedNegotiation' =>
                'Innovation Partnership',

        ],

    ];

    /**
     * --------------------------------------------------------------------------
     * Get Commercial Knowledge
     * --------------------------------------------------------------------------
     */
    public function get(string $model): array
    {
        return self::MODELS[strtoupper($model)] ?? [];
    }

    /**
     * --------------------------------------------------------------------------
     * Supports Forecast Sharing
     * --------------------------------------------------------------------------
     */
    public function supportsForecastSharing(string $model): bool
    {
        return (bool) ($this->get($model)['forecastSharing'] ?? false);
    }

    /**
     * --------------------------------------------------------------------------
     * Supports Margin Protection
     * --------------------------------------------------------------------------
     */
    public function supportsMarginProtection(string $model): bool
    {
        return (bool) ($this->get($model)['marginProtection'] ?? false);
    }

    /**
     * --------------------------------------------------------------------------
     * Supports Raw Material Adjustment
     * --------------------------------------------------------------------------
     */
    public function supportsRawMaterialAdjustment(string $model): bool
    {
        return (bool) ($this->get($model)['rawMaterialAdjustment'] ?? false);
    }

    /**
     * --------------------------------------------------------------------------
     * Recommended Negotiation
     * --------------------------------------------------------------------------
     */
    public function recommendedNegotiation(string $model): string
    {
        return $this->get($model)['recommendedNegotiation']
            ?? 'Standard Negotiation';
    }

    /**
     * --------------------------------------------------------------------------
     * All Commercial Knowledge
     * --------------------------------------------------------------------------
     */
    public function all(): array
    {
        return self::MODELS;
    }
}