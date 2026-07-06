<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Capability\Commercial;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Commercial Capability Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Provide reusable commercial capability knowledge across the
 * global textile and apparel industry.
 *
 * Business Questions
 * --------------------------------------------------------------------------
 * • What commercial value can this supplier offer?
 * • Can this supplier support OEM / ODM projects?
 * • Can this supplier collaborate with global brands?
 * • Can this supplier support long-term partnerships?
 * • Can this supplier provide commercial flexibility?
 *
 * Business Value
 * --------------------------------------------------------------------------
 * Manufacturer
 *
 * • Showcase commercial strengths
 * • Build buyer confidence
 * • Support long-term business
 * • Improve competitiveness
 *
 * Buyer / Brand
 *
 * • Evaluate commercial readiness
 * • Reduce sourcing risks
 * • Improve collaboration
 *
 * Industry
 *
 * • Strengthen business ecosystem
 * • Encourage strategic partnerships
 *
 * This service NEVER performs:
 *
 * • Database Query
 * • Analytics
 * • Matching
 * • Recommendation
 *
 * Used by:
 *
 * - CapabilityOrchestrator
 * - SupplierReadinessIntelligence
 * - MatchingEngine
 * - OpportunityEngine
 * - Company Intelligence
 */
class CommercialCapabilityService
{
    /**
     * --------------------------------------------------------------------------
     * Commercial Capability Knowledge Base
     * --------------------------------------------------------------------------
     */
    protected const CAPABILITIES = [

        /*
        |--------------------------------------------------------------------------
        | Business Model
        |--------------------------------------------------------------------------
        */

        'BUSINESS_MODEL' => [

            'OEM Manufacturing',

            'ODM Manufacturing',

            'Private Label',

            'Contract Manufacturing',

            'Strategic Partnership',

            'Joint Business Development',

        ],

        /*
        |--------------------------------------------------------------------------
        | Buyer Collaboration
        |--------------------------------------------------------------------------
        */

        'BUYER_COLLABORATION' => [

            'Product Development Collaboration',

            'Collection Development',

            'Technical Consultation',

            'Material Recommendation',

            'Cost Optimization',

            'Value Engineering',

        ],

        /*
        |--------------------------------------------------------------------------
        | Commercial Flexibility
        |--------------------------------------------------------------------------
        */

        'COMMERCIAL_FLEXIBILITY' => [

            'Flexible MOQ',

            'Forecast Collaboration',

            'Capacity Reservation',

            'Seasonal Production',

            'Flexible Delivery Schedule',

            'Rapid Quotation',

        ],

        /*
        |--------------------------------------------------------------------------
        | Supply Chain Collaboration
        |--------------------------------------------------------------------------
        */

        'SUPPLY_CHAIN' => [

            'Vendor Coordination',

            'Raw Material Planning',

            'Supply Chain Visibility',

            'Collaborative Forecasting',

            'Strategic Sourcing',

            'Vendor Partnership',

        ],

        /*
        |--------------------------------------------------------------------------
        | Export Business
        |--------------------------------------------------------------------------
        */

        'EXPORT' => [

            'International Export',

            'FTA Utilization',

            'Trade Documentation',

            'Country Compliance',

            'Multi-Country Export',

            'Global Logistics Coordination',

        ],

        /*
        |--------------------------------------------------------------------------
        | Commercial Services
        |--------------------------------------------------------------------------
        */

        'SERVICES' => [

            'Merchandising Support',

            'Dedicated Account Management',

            'Buyer Communication',

            'After Sales Support',

            'Technical Support',

            'Project Management',

        ],

        /*
        |--------------------------------------------------------------------------
        | Strategic Partnership
        |--------------------------------------------------------------------------
        */

        'PARTNERSHIP' => [

            'Innovation Partnership',

            'Preferred Supplier Program',

            'Technology Collaboration',

            'Long-term Supply Agreement',

            'Joint Investment',

            'Business Expansion',

        ],

    ];

    /**
     * --------------------------------------------------------------------------
     * Get Capability Group
     * --------------------------------------------------------------------------
     */
    public function get(string $group): array
    {
        return self::CAPABILITIES[strtoupper($group)] ?? [];
    }

    /**
     * --------------------------------------------------------------------------
     * Business Models
     * --------------------------------------------------------------------------
     */
    public function businessModels(): array
    {
        return $this->get('BUSINESS_MODEL');
    }

    /**
     * --------------------------------------------------------------------------
     * Buyer Collaboration
     * --------------------------------------------------------------------------
     */
    public function buyerCollaboration(): array
    {
        return $this->get('BUYER_COLLABORATION');
    }

    /**
     * --------------------------------------------------------------------------
     * Commercial Flexibility
     * --------------------------------------------------------------------------
     */
    public function commercialFlexibility(): array
    {
        return $this->get('COMMERCIAL_FLEXIBILITY');
    }

    /**
     * --------------------------------------------------------------------------
     * Supply Chain Collaboration
     * --------------------------------------------------------------------------
     */
    public function supplyChain(): array
    {
        return $this->get('SUPPLY_CHAIN');
    }

    /**
     * --------------------------------------------------------------------------
     * Export Business
     * --------------------------------------------------------------------------
     */
    public function export(): array
    {
        return $this->get('EXPORT');
    }

    /**
     * --------------------------------------------------------------------------
     * Commercial Services
     * --------------------------------------------------------------------------
     */
    public function services(): array
    {
        return $this->get('SERVICES');
    }

    /**
     * --------------------------------------------------------------------------
     * Strategic Partnership
     * --------------------------------------------------------------------------
     */
    public function partnership(): array
    {
        return $this->get('PARTNERSHIP');
    }

    /**
     * --------------------------------------------------------------------------
     * Complete Commercial Capability
     * --------------------------------------------------------------------------
     */
    public function all(): array
    {
        return self::CAPABILITIES;
    }
}