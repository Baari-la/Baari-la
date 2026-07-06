<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Capability\Sustainability;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Sustainability Capability Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Provide reusable sustainability capability knowledge across the
 * global textile and apparel industry.
 *
 * Business Questions
 * --------------------------------------------------------------------------
 * • What sustainability capabilities does this supplier offer?
 * • Can this supplier support sustainable manufacturing?
 * • Can this supplier help buyers achieve ESG targets?
 * • Can this supplier contribute to circular textile initiatives?
 *
 * Business Value
 * --------------------------------------------------------------------------
 * Manufacturer
 *
 * • Showcase sustainability capability
 * • Increase competitiveness
 * • Improve buyer confidence
 * • Support ESG initiatives
 *
 * Buyer / Brand
 *
 * • Identify sustainable suppliers
 * • Reduce supply chain risks
 * • Achieve sustainability targets
 *
 * Industry
 *
 * • Promote responsible manufacturing
 * • Accelerate circular textile ecosystem
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
class SustainabilityCapabilityService
{
    /**
     * --------------------------------------------------------------------------
     * Sustainability Capability Knowledge Base
     * --------------------------------------------------------------------------
     */
    protected const CAPABILITIES = [

        /*
        |--------------------------------------------------------------------------
        | Sustainable Materials
        |--------------------------------------------------------------------------
        */

        'MATERIAL' => [

            'Organic Materials',

            'Recycled Materials',

            'Bio-Based Materials',

            'Renewable Materials',

            'Low Impact Materials',

            'Circular Materials',

        ],

        /*
        |--------------------------------------------------------------------------
        | Sustainable Manufacturing
        |--------------------------------------------------------------------------
        */

        'MANUFACTURING' => [

            'Water Saving Process',

            'Energy Efficient Production',

            'Low Carbon Manufacturing',

            'Waste Reduction',

            'Resource Optimization',

            'Clean Production',

        ],

        /*
        |--------------------------------------------------------------------------
        | Circular Economy
        |--------------------------------------------------------------------------
        */

        'CIRCULAR' => [

            'Circular Textile',

            'Textile Recycling',

            'Material Recovery',

            'Waste Utilization',

            'Closed Loop Production',

            'Product Life Extension',

        ],

        /*
        |--------------------------------------------------------------------------
        | Environmental Management
        |--------------------------------------------------------------------------
        */

        'ENVIRONMENT' => [

            'Wastewater Management',

            'Chemical Management',

            'Emission Reduction',

            'Renewable Energy',

            'Water Reuse',

            'Biodiversity Protection',

        ],

        /*
        |--------------------------------------------------------------------------
        | Sustainable Innovation
        |--------------------------------------------------------------------------
        */

        'INNOVATION' => [

            'Eco Design',

            'Low Impact Dyeing',

            'Digital Printing',

            'Sustainable Packaging',

            'Green Technology',

            'Life Cycle Thinking',

        ],

        /*
        |--------------------------------------------------------------------------
        | Supply Chain Sustainability
        |--------------------------------------------------------------------------
        */

        'SUPPLY_CHAIN' => [

            'Responsible Sourcing',

            'Supplier Sustainability',

            'Traceable Supply Chain',

            'Ethical Procurement',

            'Supply Chain Transparency',

            'Local Sourcing',

        ],

        /*
        |--------------------------------------------------------------------------
        | ESG Commitment
        |--------------------------------------------------------------------------
        */

        'ESG' => [

            'Environmental Stewardship',

            'Social Responsibility',

            'Corporate Governance',

            'Community Engagement',

            'Worker Wellbeing',

            'Responsible Business',

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
     * Sustainable Materials
     * --------------------------------------------------------------------------
     */
    public function material(): array
    {
        return $this->get('MATERIAL');
    }

    /**
     * --------------------------------------------------------------------------
     * Sustainable Manufacturing
     * --------------------------------------------------------------------------
     */
    public function manufacturing(): array
    {
        return $this->get('MANUFACTURING');
    }

    /**
     * --------------------------------------------------------------------------
     * Circular Economy
     * --------------------------------------------------------------------------
     */
    public function circular(): array
    {
        return $this->get('CIRCULAR');
    }

    /**
     * --------------------------------------------------------------------------
     * Environmental Management
     * --------------------------------------------------------------------------
     */
    public function environment(): array
    {
        return $this->get('ENVIRONMENT');
    }

    /**
     * --------------------------------------------------------------------------
     * Sustainable Innovation
     * --------------------------------------------------------------------------
     */
    public function innovation(): array
    {
        return $this->get('INNOVATION');
    }

    /**
     * --------------------------------------------------------------------------
     * Supply Chain Sustainability
     * --------------------------------------------------------------------------
     */
    public function supplyChain(): array
    {
        return $this->get('SUPPLY_CHAIN');
    }

    /**
     * --------------------------------------------------------------------------
     * ESG Commitment
     * --------------------------------------------------------------------------
     */
    public function esg(): array
    {
        return $this->get('ESG');
    }

    /**
     * --------------------------------------------------------------------------
     * Complete Sustainability Capability
     * --------------------------------------------------------------------------
     */
    public function all(): array
    {
        return self::CAPABILITIES;
    }
}