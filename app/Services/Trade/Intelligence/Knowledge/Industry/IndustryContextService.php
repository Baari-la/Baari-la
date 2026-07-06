<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Knowledge\Industry;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Industry Context Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Provide textile and apparel industry knowledge that can be reused
 * across all Executive Intelligence engines.
 *
 * Business Questions
 * --------------------------------------------------------------------------
 * • Which industry segment does an HS Code belong to?
 * • Is the product upstream or downstream?
 * • Is it industrial or consumer driven?
 * • Is it brand driven?
 * • Is seasonality important?
 *
 * Business Value
 * --------------------------------------------------------------------------
 * Manufacturer
 *
 * • Better product positioning
 * • Better market understanding
 *
 * Buyer
 *
 * • Better sourcing decisions
 *
 * Industry
 *
 * • Standardized industry knowledge
 *
 * Knowledge Source
 * --------------------------------------------------------------------------
 * • Global Textile Value Chain
 * • HS Classification
 * • Textile Industry Best Practice
 *
 * This service NEVER performs analytics.
 *
 * It provides reusable industry knowledge.
 *
 * Used by:
 *
 * - TradeRadarService
 * - RiskAnalysisService
 * - RecommendationService
 * - ExecutiveSummaryService
 * - AIExecutiveSummaryService
 * - KnowledgeOrchestrator
 */
class IndustryContextService
{
    /**
     * --------------------------------------------------------------------------
     * Industry Context
     * --------------------------------------------------------------------------
     */
    protected const CONTEXT = [

        /*
        |--------------------------------------------------------------------------
        | Natural Fiber
        |--------------------------------------------------------------------------
        */

        '52' => [

            'industry' => 'Natural Fiber',

            'segment' => 'Raw Material',

            'valueChain' => 'Upstream',

            'consumerDriven' => false,

            'brandDriven' => false,

            'seasonality' => false,

        ],

        /*
        |--------------------------------------------------------------------------
        | Man-made Staple Fiber
        |--------------------------------------------------------------------------
        */

        '55' => [

            'industry' => 'Synthetic Fiber',

            'segment' => 'Raw Material',

            'valueChain' => 'Upstream',

            'consumerDriven' => false,

            'brandDriven' => false,

            'seasonality' => false,

        ],

        /*
        |--------------------------------------------------------------------------
        | Knitted Apparel
        |--------------------------------------------------------------------------
        */

        '61' => [

            'industry' => 'Apparel',

            'segment' => 'Consumer Product',

            'valueChain' => 'Downstream',

            'consumerDriven' => true,

            'brandDriven' => true,

            'seasonality' => true,

        ],

        /*
        |--------------------------------------------------------------------------
        | Woven Apparel
        |--------------------------------------------------------------------------
        */

        '62' => [

            'industry' => 'Apparel',

            'segment' => 'Consumer Product',

            'valueChain' => 'Downstream',

            'consumerDriven' => true,

            'brandDriven' => true,

            'seasonality' => true,

        ],

    ];

    /**
     * --------------------------------------------------------------------------
     * Get Industry Context
     * --------------------------------------------------------------------------
     */
    public function get(string $hsPrefix): array
    {
        return self::CONTEXT[$hsPrefix] ?? [

            'industry' => 'Unknown',

            'segment' => 'Unknown',

            'valueChain' => 'Unknown',

            'consumerDriven' => false,

            'brandDriven' => false,

            'seasonality' => false,

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Determine Whether Product is Apparel
     * --------------------------------------------------------------------------
     */
    public function isApparel(string $hsPrefix): bool
    {
        return ($this->get($hsPrefix)['industry'] ?? null) === 'Apparel';
    }

    /**
     * --------------------------------------------------------------------------
     * Determine Whether Product is Upstream
     * --------------------------------------------------------------------------
     */
    public function isUpstream(string $hsPrefix): bool
    {
        return ($this->get($hsPrefix)['valueChain'] ?? null) === 'Upstream';
    }

    /**
     * --------------------------------------------------------------------------
     * Determine Whether Product is Downstream
     * --------------------------------------------------------------------------
     */
    public function isDownstream(string $hsPrefix): bool
    {
        return ($this->get($hsPrefix)['valueChain'] ?? null) === 'Downstream';
    }

    /**
     * --------------------------------------------------------------------------
     * All Industry Knowledge
     * --------------------------------------------------------------------------
     */
    public function all(): array
    {
        return self::CONTEXT;
    }
}