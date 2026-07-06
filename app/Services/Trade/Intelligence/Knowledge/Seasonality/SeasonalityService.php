<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Knowledge\Seasonality;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Seasonality Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Provide reusable seasonality knowledge for the global textile and apparel
 * industry to support executive planning, sourcing, production, commercial
 * negotiation, and market intelligence.
 *
 * Business Questions
 * --------------------------------------------------------------------------
 * • Which selling season is approaching?
 * • When should sourcing begin?
 * • When should production start?
 * • When should shipment be completed?
 * • Which regions are affected?
 * • What commercial actions should executives prepare?
 *
 * Business Value
 * --------------------------------------------------------------------------
 * Manufacturer
 * • Improve production planning
 * • Improve sourcing strategy
 * • Improve inventory management
 * • Protect business margins
 *
 * Buyer
 * • Improve purchasing planning
 * • Improve delivery scheduling
 *
 * Industry
 * • Standardized seasonality knowledge
 *
 * Knowledge Sources
 * --------------------------------------------------------------------------
 * • Global Fashion Calendar
 * • Apparel Buying Cycle
 * • Textile Production Planning
 * • International Retail Calendar
 *
 * This service NEVER performs analytics.
 *
 * It provides reusable business knowledge.
 *
 * Used by:
 *
 * - OpportunityService
 * - RiskAnalysisService
 * - RecommendationService
 * - ExecutiveSummaryService
 * - AIExecutiveSummaryService
 * - KnowledgeOrchestrator
 */
class SeasonalityService
{
    /**
     * --------------------------------------------------------------------------
     * Global Seasonality Knowledge Base
     * --------------------------------------------------------------------------
     */
    protected const SEASONS = [

        /*
        |--------------------------------------------------------------------------
        | Spring / Summer
        |--------------------------------------------------------------------------
        */

        'SS' => [

            'season' => 'Spring / Summer',

            'marketType' => 'Fashion',

            'collectionLaunch' => 'January - March',

            'productionWindow' => 'July - October',

            'sourcingWindow' => 'May - August',

            'shipmentWindow' => 'November - January',

            'consumerDemand' => 'High',

            'regions' => [

                'North America',

                'Europe',

                'Japan',

                'South Korea',

            ],

            /*
            |--------------------------------------------------------------------------
            | Executive Planning
            |--------------------------------------------------------------------------
            */

            'planning' => [

                'quotation',

                'buyerNegotiation',

                'sampleDevelopment',

                'materialBooking',

                'production',

                'shipment',

                'retailLaunch',

            ],

        ],

        /*
        |--------------------------------------------------------------------------
        | Fall / Winter
        |--------------------------------------------------------------------------
        */

        'FW' => [

            'season' => 'Fall / Winter',

            'marketType' => 'Fashion',

            'collectionLaunch' => 'August - October',

            'productionWindow' => 'January - April',

            'sourcingWindow' => 'November - February',

            'shipmentWindow' => 'May - July',

            'consumerDemand' => 'High',

            'regions' => [

                'North America',

                'Europe',

                'Japan',

                'South Korea',

            ],

            'planning' => [

                'quotation',

                'buyerNegotiation',

                'sampleDevelopment',

                'materialBooking',

                'production',

                'shipment',

                'retailLaunch',

            ],

        ],

        /*
        |--------------------------------------------------------------------------
        | Ramadan
        |--------------------------------------------------------------------------
        */

        'RAMADAN' => [

            'season' => 'Ramadan',

            'marketType' => 'Religious',

            'collectionLaunch' => 'Before Ramadan',

            'productionWindow' => '4-6 Months Before',

            'sourcingWindow' => '5-7 Months Before',

            'shipmentWindow' => '2 Months Before',

            'consumerDemand' => 'Very High',

            'regions' => [

                'Indonesia',

                'Malaysia',

                'Middle East',

            ],

            'planning' => [

                'quotation',

                'buyerNegotiation',

                'materialBooking',

                'production',

                'shipment',

                'retailLaunch',

            ],

        ],

        /*
        |--------------------------------------------------------------------------
        | Back To School
        |--------------------------------------------------------------------------
        */

        'BTS' => [

            'season' => 'Back To School',

            'marketType' => 'Retail',

            'collectionLaunch' => 'June - August',

            'productionWindow' => 'February - May',

            'sourcingWindow' => 'January - April',

            'shipmentWindow' => 'May - June',

            'consumerDemand' => 'High',

            'regions' => [

                'North America',

                'Europe',

            ],

            'planning' => [

                'quotation',

                'buyerNegotiation',

                'production',

                'shipment',

                'retailLaunch',

            ],

        ],

    ];

    /**
     * --------------------------------------------------------------------------
     * Get Season Knowledge
     * --------------------------------------------------------------------------
     */
    public function get(string $season): array
    {
        return self::SEASONS[strtoupper($season)] ?? [];
    }

    /**
     * --------------------------------------------------------------------------
     * Get All Seasonality Knowledge
     * --------------------------------------------------------------------------
     */
    public function all(): array
    {
        return self::SEASONS;
    }

    /**
     * --------------------------------------------------------------------------
     * Determine Peak Season
     * --------------------------------------------------------------------------
     */
    public function isPeakSeason(string $season): bool
    {
        return ($this->get($season)['consumerDemand'] ?? '')
            === 'Very High';
    }

    /**
     * --------------------------------------------------------------------------
     * Determine Fashion Season
     * --------------------------------------------------------------------------
     */
    public function isFashionSeason(string $season): bool
    {
        return ($this->get($season)['marketType'] ?? '')
            === 'Fashion';
    }

    /**
     * --------------------------------------------------------------------------
     * Executive Planning Flow
     * --------------------------------------------------------------------------
     */
    public function planning(string $season): array
    {
        return $this->get($season)['planning'] ?? [];
    }
}