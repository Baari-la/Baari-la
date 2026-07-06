<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Capability\Development;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Development Capability Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Provide reusable product development capability knowledge across
 * the global textile and apparel industry.
 *
 * Business Questions
 * --------------------------------------------------------------------------
 * • Can this company develop new products?
 * • Can this company support buyer innovation?
 * • Can this company perform OEM / ODM development?
 * • Can this company develop materials before receiving orders?
 * • Can this company collaborate in product innovation?
 *
 * Business Value
 * --------------------------------------------------------------------------
 * Manufacturer
 *
 * • Showcase development capability
 * • Improve buyer confidence
 * • Promote innovation
 * • Support business development
 *
 * Buyer / Brand
 *
 * • Identify innovation partners
 * • Accelerate product development
 * • Reduce development risks
 *
 * Industry
 *
 * • Encourage value-added manufacturing
 * • Strengthen innovation ecosystem
 *
 * This service NEVER performs:
 *
 * • Database Query
 * • Matching
 * • Recommendation
 * • Analytics
 *
 * Used by:
 *
 * - CapabilityOrchestrator
 * - MatchingEngine
 * - OpportunityEngine
 * - Supplier Readiness Intelligence
 * - Company Intelligence
 */
class DevelopmentCapabilityService
{
    /**
     * --------------------------------------------------------------------------
     * Development Capability Knowledge Base
     * --------------------------------------------------------------------------
     */
    protected const CAPABILITIES = [

        /*
        |--------------------------------------------------------------------------
        | Product Development
        |--------------------------------------------------------------------------
        */

        'PRODUCT' => [

            'New Product Development',

            'Product Customization',

            'Buyer Exclusive Development',

            'Collection Development',

            'Seasonal Collection',

            'Private Label Development',

        ],

        /*
        |--------------------------------------------------------------------------
        | Material Development
        |--------------------------------------------------------------------------
        */

        'MATERIAL' => [

            'Material Innovation',

            'Fiber Development',

            'Yarn Development',

            'Fabric Development',

            'Functional Material',

            'Sustainable Material',

        ],

        /*
        |--------------------------------------------------------------------------
        | Design Development
        |--------------------------------------------------------------------------
        */

        'DESIGN' => [

            'Design Support',

            'Print Development',

            'Pattern Development',

            'Color Development',

            'Graphic Development',

            'Trend Interpretation',

        ],

        /*
        |--------------------------------------------------------------------------
        | Sampling
        |--------------------------------------------------------------------------
        */

        'SAMPLING' => [

            'Rapid Sampling',

            'Prototype Development',

            'Fit Sample',

            'Salesman Sample',

            'Pre-Production Sample',

            'Pilot Production',

        ],

        /*
        |--------------------------------------------------------------------------
        | Manufacturing Development
        |--------------------------------------------------------------------------
        */

        'MANUFACTURING' => [

            'OEM',

            'ODM',

            'Process Development',

            'Production Optimization',

            'Value Engineering',

            'Cost Optimization',

        ],

        /*
        |--------------------------------------------------------------------------
        | Sustainability Development
        |--------------------------------------------------------------------------
        */

        'SUSTAINABILITY' => [

            'Circular Product Development',

            'Eco Design',

            'Recycled Material Development',

            'Low Carbon Development',

            'Water Saving Process',

            'Sustainable Packaging',

        ],

        /*
        |--------------------------------------------------------------------------
        | Business Development
        |--------------------------------------------------------------------------
        */

        'BUSINESS' => [

            'Buyer Collaboration',

            'Joint Development',

            'Innovation Partnership',

            'Technology Adoption',

            'Pilot Project',

            'Market Driven Development',

        ],

    ];

    /**
     * --------------------------------------------------------------------------
     * Get Development Capability Group
     * --------------------------------------------------------------------------
     */
    public function get(string $group): array
    {
        return self::CAPABILITIES[strtoupper($group)] ?? [];
    }

    /**
     * --------------------------------------------------------------------------
     * Product Development
     * --------------------------------------------------------------------------
     */
    public function product(): array
    {
        return $this->get('PRODUCT');
    }

    /**
     * --------------------------------------------------------------------------
     * Material Development
     * --------------------------------------------------------------------------
     */
    public function material(): array
    {
        return $this->get('MATERIAL');
    }

    /**
     * --------------------------------------------------------------------------
     * Design Development
     * --------------------------------------------------------------------------
     */
    public function design(): array
    {
        return $this->get('DESIGN');
    }

    /**
     * --------------------------------------------------------------------------
     * Sampling Capability
     * --------------------------------------------------------------------------
     */
    public function sampling(): array
    {
        return $this->get('SAMPLING');
    }

    /**
     * --------------------------------------------------------------------------
     * Manufacturing Development
     * --------------------------------------------------------------------------
     */
    public function manufacturing(): array
    {
        return $this->get('MANUFACTURING');
    }

    /**
     * --------------------------------------------------------------------------
     * Sustainability Development
     * --------------------------------------------------------------------------
     */
    public function sustainability(): array
    {
        return $this->get('SUSTAINABILITY');
    }

    /**
     * --------------------------------------------------------------------------
     * Business Development
     * --------------------------------------------------------------------------
     */
    public function business(): array
    {
        return $this->get('BUSINESS');
    }

    /**
     * --------------------------------------------------------------------------
     * Complete Development Capability
     * --------------------------------------------------------------------------
     */
    public function all(): array
    {
        return self::CAPABILITIES;
    }
}