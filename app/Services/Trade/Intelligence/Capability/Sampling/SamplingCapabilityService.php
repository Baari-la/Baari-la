<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Capability\Sampling;

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
 * • How can this supplier support commercial collaboration?
 * • What commercial models are available?
 * • Can the supplier support OEM / ODM?
 * • Can the supplier collaborate on long-term business?
 * • Can the supplier provide flexible commercial solutions?
 *
 * Business Value
 * --------------------------------------------------------------------------
 * Manufacturer
 *
 * • Showcase commercial strengths
 * • Improve buyer confidence
 * • Support strategic partnerships
 *
 * Buyer / Brand
 *
 * • Evaluate commercial flexibility
 * • Reduce sourcing risks
 * • Build long-term relationships
 *
 * Industry
 *
 * • Encourage sustainable business collaboration
 * * • Improve supply chain resilience
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
class SamplingCapabilityService
{
    /**
     * --------------------------------------------------------------------------
     * Commercial Capability Knowledge Base
     * --------------------------------------------------------------------------
     */
    protected const CAPABILITIES = [

        /*
        |--------------------------------------------------------------------------
        | Business Models
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
        | Commercial Flexibility
        |--------------------------------------------------------------------------
        */

        'FLEXIBILITY' => [

            'Flexible MOQ',

            'Forecast Collaboration',

            'Capacity Reservation',

            'Seasonal Production',

            'Rapid Quotation',

            'Flexible Delivery Schedule',

        ],

        /*
        |--------------------------------------------------------------------------
        | Buyer Support
        |--------------------------------------------------------------------------
        */

        'BUYER_SUPPORT' => [

            'Merchandising Support',

            'Technical Consultation',

            'Product Costing',

            'Material Recommendation',

            'Value Engineering',

            'Commercial Negotiation',

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

            'Vendor Managed Inventory',

            'Collaborative Forecasting',

            'Strategic Sourcing',

        ],

        /*
        |--------------------------------------------------------------------------
        | Export Capability
        |--------------------------------------------------------------------------
        */

        'EXPORT' => [

            'Export Documentation',

            'International Shipping',

            'Country Compliance',

            'FTA Knowledge',

            'Trade Regulation Support',

            'Multi-Country Export',

        ],

        /*
        |--------------------------------------------------------------------------
        | Partnership
        |--------------------------------------------------------------------------
        */

        'PARTNERSHIP' => [

            'Innovation Partnership',

            'Long-term Collaboration',

            'Preferred Supplier Program',

            'Technology Collaboration',

            'Business Expansion',

            'Joint Investment',

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
     * Commercial Flexibility
     * --------------------------------------------------------------------------
     */
    public function flexibility(): array
    {
        return $this->get('FLEXIBILITY');
    }

    /**
     * --------------------------------------------------------------------------
     * Buyer Support
     * --------------------------------------------------------------------------
     */
    public function buyerSupport(): array
    {
        return $this->get('BUYER_SUPPORT');
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
     * Export Capability
     * --------------------------------------------------------------------------
     */
    public function export(): array
    {
        return $this->get('EXPORT');
    }

    /**
     * --------------------------------------------------------------------------
     * Partnership Capability
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