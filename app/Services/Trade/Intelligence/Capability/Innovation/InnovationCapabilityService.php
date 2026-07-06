<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Capability\Innovation;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Innovation Capability Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Provide reusable innovation capability knowledge across the
 * global textile and apparel industry.
 *
 * Business Questions
 * --------------------------------------------------------------------------
 * • What innovations can this company offer?
 * • Can this company support buyer innovation?
 * • Does this company develop sustainable products?
 * • Can this company adopt new technologies?
 * • Can this company create value beyond manufacturing?
 *
 * Business Value
 * --------------------------------------------------------------------------
 * Manufacturer
 *
 * • Showcase innovation capability
 * • Increase business competitiveness
 * • Support buyer collaboration
 * • Improve value proposition
 *
 * Buyer / Brand
 *
 * • Identify innovation partners
 * • Accelerate product innovation
 * • Reduce development time
 *
 * Industry
 *
 * • Encourage continuous innovation
 * • Strengthen global competitiveness
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
 * - Supplier Readiness Intelligence
 * - MatchingEngine
 * - OpportunityEngine
 * - Company Intelligence
 */
class InnovationCapabilityService
{
    /**
     * --------------------------------------------------------------------------
     * Innovation Capability Knowledge Base
     * --------------------------------------------------------------------------
     */
    protected const CAPABILITIES = [

        /*
        |--------------------------------------------------------------------------
        | Product Innovation
        |--------------------------------------------------------------------------
        */

        'PRODUCT' => [

            'Performance Textile',

            'Functional Fabric',

            'Smart Textile',

            'Technical Textile',

            'Protective Textile',

            'Medical Textile',

        ],

        /*
        |--------------------------------------------------------------------------
        | Material Innovation
        |--------------------------------------------------------------------------
        */

        'MATERIAL' => [

            'Recycled Material',

            'Bio-Based Material',

            'Natural Fiber Innovation',

            'Circular Material',

            'Low Carbon Material',

            'Alternative Fiber',

        ],

        /*
        |--------------------------------------------------------------------------
        | Manufacturing Innovation
        |--------------------------------------------------------------------------
        */

        'MANUFACTURING' => [

            'Digital Printing',

            'Automation',

            'Industry 4.0',

            'Smart Manufacturing',

            'Lean Manufacturing',

            'Flexible Production',

        ],

        /*
        |--------------------------------------------------------------------------
        | Sustainability Innovation
        |--------------------------------------------------------------------------
        */

        'SUSTAINABILITY' => [

            'Water Saving Technology',

            'Energy Efficiency',

            'Carbon Reduction',

            'Circular Economy',

            'Zero Waste Production',

            'Green Manufacturing',

        ],

        /*
        |--------------------------------------------------------------------------
        | Business Innovation
        |--------------------------------------------------------------------------
        */

        'BUSINESS' => [

            'Buyer Co-Creation',

            'Joint Product Development',

            'Innovation Partnership',

            'Rapid Innovation',

            'Customized Solution',

            'Market Driven Innovation',

        ],

        /*
        |--------------------------------------------------------------------------
        | Digital Innovation
        |--------------------------------------------------------------------------
        */

        'DIGITAL' => [

            'Digital Product Development',

            '3D Product Visualization',

            'Digital Sampling',

            'PLM Integration',

            'Digital Traceability',

            'Digital Product Passport',

        ],

        /*
        |--------------------------------------------------------------------------
        | Technology Adoption
        |--------------------------------------------------------------------------
        */

        'TECHNOLOGY' => [

            'AI Assisted Manufacturing',

            'IoT Monitoring',

            'Predictive Maintenance',

            'Advanced Quality Control',

            'Data Analytics',

            'Cloud Manufacturing',

        ],

    ];

    /**
     * --------------------------------------------------------------------------
     * Get Innovation Capability Group
     * --------------------------------------------------------------------------
     */
    public function get(string $group): array
    {
        return self::CAPABILITIES[strtoupper($group)] ?? [];
    }

    /**
     * --------------------------------------------------------------------------
     * Product Innovation
     * --------------------------------------------------------------------------
     */
    public function product(): array
    {
        return $this->get('PRODUCT');
    }

    /**
     * --------------------------------------------------------------------------
     * Material Innovation
     * --------------------------------------------------------------------------
     */
    public function material(): array
    {
        return $this->get('MATERIAL');
    }

    /**
     * --------------------------------------------------------------------------
     * Manufacturing Innovation
     * --------------------------------------------------------------------------
     */
    public function manufacturing(): array
    {
        return $this->get('MANUFACTURING');
    }

    /**
     * --------------------------------------------------------------------------
     * Sustainability Innovation
     * --------------------------------------------------------------------------
     */
    public function sustainability(): array
    {
        return $this->get('SUSTAINABILITY');
    }

    /**
     * --------------------------------------------------------------------------
     * Business Innovation
     * --------------------------------------------------------------------------
     */
    public function business(): array
    {
        return $this->get('BUSINESS');
    }

    /**
     * --------------------------------------------------------------------------
     * Digital Innovation
     * --------------------------------------------------------------------------
     */
    public function digital(): array
    {
        return $this->get('DIGITAL');
    }

    /**
     * --------------------------------------------------------------------------
     * Technology Adoption
     * --------------------------------------------------------------------------
     */
    public function technology(): array
    {
        return $this->get('TECHNOLOGY');
    }

    /**
     * --------------------------------------------------------------------------
     * Complete Innovation Capability
     * --------------------------------------------------------------------------
     */
    public function all(): array
    {
        return self::CAPABILITIES;
    }
}