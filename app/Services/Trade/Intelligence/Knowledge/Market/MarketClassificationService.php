<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Knowledge\Market;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Market Classification Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Classify export destination markets based on their strategic role
 * within the global textile and apparel ecosystem.
 *
 * Business Questions
 * --------------------------------------------------------------------------
 * • Is this country a consumer market?
 * • Is it a manufacturing hub?
 * • Is it a distribution hub?
 * • Is it an emerging market?
 * • What business strategy fits this market?
 *
 * Business Value
 * --------------------------------------------------------------------------
 * Manufacturer
 *
 * • Better export targeting
 * • Better market expansion strategy
 *
 * Buyer
 *
 * • Better sourcing decisions
 *
 * Industry
 *
 * • Standardized market intelligence
 *
 * Knowledge Sources
 * --------------------------------------------------------------------------
 * • UN Trade Patterns
 * • Global Textile Supply Chain
 * • Textile Industry Experience
 * • International Buyer Behaviour
 *
 * This service NEVER performs analytics.
 *
 * It provides reusable market knowledge.
 *
 * Used by:
 *
 * - TradeRadarService
 * - OpportunityService
 * - RecommendationService
 * - RiskAnalysisService
 * - ExecutiveSummaryService
 * - KnowledgeOrchestrator
 */
class MarketClassificationService
{
    /**
     * --------------------------------------------------------------------------
     * Market Knowledge Base
     * --------------------------------------------------------------------------
     */
    protected const MARKETS = [

        /*
        |--------------------------------------------------------------------------
        | Consumer Markets
        |--------------------------------------------------------------------------
        */

        'US' => [

            'classification' => 'Consumer Market',

            'region' => 'North America',

            'brandDriven' => true,

            'seasonality' => true,

            'maturity' => 'Mature',

        ],

        'DE' => [

            'classification' => 'Consumer Market',

            'region' => 'Europe',

            'brandDriven' => true,

            'seasonality' => true,

            'maturity' => 'Mature',

        ],

        'JP' => [

            'classification' => 'Consumer Market',

            'region' => 'Asia',

            'brandDriven' => true,

            'seasonality' => true,

            'maturity' => 'Mature',

        ],

        /*
        |--------------------------------------------------------------------------
        | Manufacturing Hubs
        |--------------------------------------------------------------------------
        */

        'VN' => [

            'classification' => 'Manufacturing Hub',

            'region' => 'ASEAN',

            'brandDriven' => false,

            'seasonality' => false,

            'maturity' => 'Advanced',

        ],

        'BD' => [

            'classification' => 'Manufacturing Hub',

            'region' => 'South Asia',

            'brandDriven' => false,

            'seasonality' => false,

            'maturity' => 'Advanced',

        ],

        'CN' => [

            'classification' => 'Manufacturing Hub',

            'region' => 'East Asia',

            'brandDriven' => false,

            'seasonality' => false,

            'maturity' => 'Advanced',

        ],

        /*
        |--------------------------------------------------------------------------
        | Distribution Hub
        |--------------------------------------------------------------------------
        */

        'SG' => [

            'classification' => 'Distribution Hub',

            'region' => 'ASEAN',

            'brandDriven' => false,

            'seasonality' => false,

            'maturity' => 'Advanced',

        ],

        'HK' => [

            'classification' => 'Distribution Hub',

            'region' => 'East Asia',

            'brandDriven' => false,

            'seasonality' => false,

            'maturity' => 'Advanced',

        ],

    ];

    /**
     * --------------------------------------------------------------------------
     * Get Market Classification
     * --------------------------------------------------------------------------
     */
    public function get(string $countryCode): array
    {
        return self::MARKETS[strtoupper($countryCode)] ?? [

            'classification' => 'Emerging Market',

            'region' => 'Unknown',

            'brandDriven' => false,

            'seasonality' => false,

            'maturity' => 'Emerging',

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Determine Consumer Market
     * --------------------------------------------------------------------------
     */
    public function isConsumerMarket(string $countryCode): bool
    {
        return $this->get($countryCode)['classification']
            === 'Consumer Market';
    }

    /**
     * --------------------------------------------------------------------------
     * Determine Manufacturing Hub
     * --------------------------------------------------------------------------
     */
    public function isManufacturingHub(string $countryCode): bool
    {
        return $this->get($countryCode)['classification']
            === 'Manufacturing Hub';
    }

    /**
     * --------------------------------------------------------------------------
     * Determine Distribution Hub
     * --------------------------------------------------------------------------
     */
    public function isDistributionHub(string $countryCode): bool
    {
        return $this->get($countryCode)['classification']
            === 'Distribution Hub';
    }

    /**
     * --------------------------------------------------------------------------
     * Determine Emerging Market
     * --------------------------------------------------------------------------
     */
    public function isEmergingMarket(string $countryCode): bool
    {
        return $this->get($countryCode)['classification']
            === 'Emerging Market';
    }

    /**
     * --------------------------------------------------------------------------
     * All Market Knowledge
     * --------------------------------------------------------------------------
     */
    public function all(): array
    {
        return self::MARKETS;
    }
}