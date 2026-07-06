<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Knowledge\Brand;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Brand Intelligence Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Provide reusable brand intelligence knowledge for the global textile
 * and apparel industry.
 *
 * Business Questions
 * --------------------------------------------------------------------------
 * • Is the market brand-driven?
 * • What type of brand dominates the market?
 * • How important are quality and compliance?
 * • How does brand strategy affect suppliers?
 * • What executive actions should be considered?
 *
 * Business Value
 * --------------------------------------------------------------------------
 * Manufacturer
 *
 * • Better customer targeting
 * • Better product positioning
 * • Better commercial strategy
 * • Higher business sustainability
 *
 * Buyer / Brand
 *
 * • Better supplier alignment
 * • Better product development
 *
 * Industry
 *
 * • Promote value creation
 * • Encourage innovation
 *
 * Knowledge Sources
 * --------------------------------------------------------------------------
 * • Global Apparel Industry
 * • International Brand Practices
 * • Fashion Supply Chain
 * • Executive Commercial Experience
 *
 * This service NEVER performs analytics.
 *
 * It provides reusable brand knowledge.
 *
 * Used by:
 *
 * - RecommendationService
 * - RiskAnalysisService
 * - BuyerIntelligenceService
 * - CommercialKnowledgeService
 * - AIExecutiveSummaryService
 * - KnowledgeOrchestrator
 */
class BrandIntelligenceService
{
    /**
     * --------------------------------------------------------------------------
     * Brand Knowledge Base
     * --------------------------------------------------------------------------
     */
    protected const BRANDS = [

        /*
        |--------------------------------------------------------------------------
        | Global Premium Brand
        |--------------------------------------------------------------------------
        */

        'PREMIUM' => [

            'segment' => 'Premium',

            'marketPosition' => 'High Value',

            'priceSensitivity' => 'Low',

            'qualityExpectation' => 'Very High',

            'innovationExpectation' => 'Very High',

            'complianceRequirement' => 'Very High',

            'sustainabilityRequirement' => 'Very High',

            'relationshipModel' => 'Strategic',

            'preferredSupplier' => 'Long-term Partner',

            'businessFocus' => 'Value Creation',

        ],

        /*
        |--------------------------------------------------------------------------
        | Mass Market Brand
        |--------------------------------------------------------------------------
        */

        'MASS' => [

            'segment' => 'Mass Market',

            'marketPosition' => 'Volume',

            'priceSensitivity' => 'High',

            'qualityExpectation' => 'High',

            'innovationExpectation' => 'Medium',

            'complianceRequirement' => 'High',

            'sustainabilityRequirement' => 'Medium',

            'relationshipModel' => 'Collaborative',

            'preferredSupplier' => 'Reliable Supplier',

            'businessFocus' => 'Efficiency',

        ],

        /*
        |--------------------------------------------------------------------------
        | Fast Fashion
        |--------------------------------------------------------------------------
        */

        'FAST_FASHION' => [

            'segment' => 'Fast Fashion',

            'marketPosition' => 'Trend',

            'priceSensitivity' => 'High',

            'qualityExpectation' => 'Medium',

            'innovationExpectation' => 'Very High',

            'complianceRequirement' => 'High',

            'sustainabilityRequirement' => 'Increasing',

            'relationshipModel' => 'Flexible',

            'preferredSupplier' => 'Agile Supplier',

            'businessFocus' => 'Speed',

        ],

        /*
        |--------------------------------------------------------------------------
        | Private Label
        |--------------------------------------------------------------------------
        */

        'PRIVATE_LABEL' => [

            'segment' => 'Private Label',

            'marketPosition' => 'Retail',

            'priceSensitivity' => 'Very High',

            'qualityExpectation' => 'High',

            'innovationExpectation' => 'Medium',

            'complianceRequirement' => 'Medium',

            'sustainabilityRequirement' => 'Medium',

            'relationshipModel' => 'Commercial',

            'preferredSupplier' => 'Cost Competitive',

            'businessFocus' => 'Cost Efficiency',

        ],

    ];

    /**
     * --------------------------------------------------------------------------
     * Get Brand Knowledge
     * --------------------------------------------------------------------------
     */
    public function get(string $brandType): array
    {
        return self::BRANDS[strtoupper($brandType)] ?? [];
    }

    /**
     * --------------------------------------------------------------------------
     * Premium Brand
     * --------------------------------------------------------------------------
     */
    public function isPremium(string $brandType): bool
    {
        return ($this->get($brandType)['segment'] ?? '') === 'Premium';
    }

    /**
     * --------------------------------------------------------------------------
     * Fast Fashion
     * --------------------------------------------------------------------------
     */
    public function isFastFashion(string $brandType): bool
    {
        return ($this->get($brandType)['segment'] ?? '') === 'Fast Fashion';
    }

    /**
     * --------------------------------------------------------------------------
     * Sustainability Driven
     * --------------------------------------------------------------------------
     */
    public function requiresHighSustainability(string $brandType): bool
    {
        return in_array(
            $this->get($brandType)['sustainabilityRequirement'] ?? '',
            ['Very High', 'High'],
            true
        );
    }

    /**
     * --------------------------------------------------------------------------
     * All Brand Knowledge
     * --------------------------------------------------------------------------
     */
    public function all(): array
    {
        return self::BRANDS;
    }
}