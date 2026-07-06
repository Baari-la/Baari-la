<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Compliance\Regulatory\Market;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Market Regulatory Compliance Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Provide reusable knowledge of international market regulatory
 * requirements across the global textile and apparel industry.
 *
 * Market regulations determine whether textile products
 * can legally enter specific export markets.
 *
 * Business Questions
 * --------------------------------------------------------------------------
 * • Which regulations apply to the destination market?
 * • What compliance is required before export?
 * • Which regulations affect buyer decisions?
 * • How can suppliers improve market readiness?
 *
 * Business Value
 * --------------------------------------------------------------------------
 * Manufacturer
 *
 * • Improve export readiness
 * • Reduce market entry risks
 * • Prepare for international buyers
 *
 * Buyer / Brand
 *
 * • Verify supplier market compliance
 * • Reduce import risks
 *
 * Industry
 *
 * • Improve international trade readiness
 * • Support global market access
 *
 * This service NEVER performs:
 *
 * • Database Query
 * • Country-specific Validation
 * • Analytics
 * • Matching
 * • Recommendation
 *
 * Used by:
 *
 * - RegulatoryOrchestrator
 * - ComplianceOrchestrator
 * - Supplier Readiness Intelligence
 * - Company Intelligence
 * - Market Intelligence
 */
class MarketRegulatoryComplianceService
{
    /**
     * --------------------------------------------------------------------------
     * Market Regulatory Knowledge Base
     * --------------------------------------------------------------------------
     */
    protected const REGULATIONS = [

        /*
        |--------------------------------------------------------------------------
        | Product Safety
        |--------------------------------------------------------------------------
        */

        'PRODUCT_SAFETY' => [

            'Product Safety',

            'Consumer Protection',

            'Restricted Chemicals',

            'Product Testing',

            'Safety Documentation',

        ],

        /*
        |--------------------------------------------------------------------------
        | Chemical Compliance
        |--------------------------------------------------------------------------
        */

        'CHEMICAL' => [

            'Restricted Substance Requirements',

            'Chemical Declaration',

            'Hazardous Substance Control',

            'Chemical Traceability',

            'Chemical Risk Assessment',

        ],

        /*
        |--------------------------------------------------------------------------
        | Product Labeling
        |--------------------------------------------------------------------------
        */

        'LABELING' => [

            'Fiber Composition',

            'Country of Origin',

            'Care Label',

            'Product Identification',

            'Language Requirements',

        ],

        /*
        |--------------------------------------------------------------------------
        | Sustainability
        |--------------------------------------------------------------------------
        */

        'SUSTAINABILITY' => [

            'Digital Product Passport',

            'Supply Chain Transparency',

            'Carbon Disclosure',

            'Circular Economy',

            'Recycled Content',

            'Due Diligence',

        ],

        /*
        |--------------------------------------------------------------------------
        | Market Documentation
        |--------------------------------------------------------------------------
        */

        'DOCUMENTATION' => [

            'Certificate of Origin',

            'Commercial Invoice',

            'Packing List',

            'Import Documentation',

            'Product Declaration',

        ],

        /*
        |--------------------------------------------------------------------------
        | Customs & Trade
        |--------------------------------------------------------------------------
        */

        'TRADE' => [

            'Import Regulation',

            'Customs Compliance',

            'Tariff Classification',

            'Rules of Origin',

            'Trade Agreement',

        ],

        /*
        |--------------------------------------------------------------------------
        | Buyer Requirements
        |--------------------------------------------------------------------------
        */

        'BUYER' => [

            'Supplier Code of Conduct',

            'Approved Supplier Program',

            'Factory Audit',

            'Social Compliance',

            'Environmental Compliance',

            'Quality Management',

        ],

        /*
        |--------------------------------------------------------------------------
        | Market Readiness
        |--------------------------------------------------------------------------
        */

        'READINESS' => [

            'Export Readiness',

            'Market Entry Readiness',

            'Supply Chain Readiness',

            'Traceability Readiness',

            'Regulatory Readiness',

        ],

    ];

    /**
     * --------------------------------------------------------------------------
     * Get Regulation Group
     * --------------------------------------------------------------------------
     */
    public function get(string $group): array
    {
        return self::REGULATIONS[strtoupper($group)] ?? [];
    }

    /**
     * Product Safety
     */
    public function productSafety(): array
    {
        return $this->get('PRODUCT_SAFETY');
    }

    /**
     * Chemical Compliance
     */
    public function chemical(): array
    {
        return $this->get('CHEMICAL');
    }

    /**
     * Product Labeling
     */
    public function labeling(): array
    {
        return $this->get('LABELING');
    }

    /**
     * Sustainability Requirements
     */
    public function sustainability(): array
    {
        return $this->get('SUSTAINABILITY');
    }

    /**
     * Market Documentation
     */
    public function documentation(): array
    {
        return $this->get('DOCUMENTATION');
    }

    /**
     * Customs & Trade
     */
    public function trade(): array
    {
        return $this->get('TRADE');
    }

    /**
     * Buyer Requirements
     */
    public function buyer(): array
    {
        return $this->get('BUYER');
    }

    /**
     * Market Readiness
     */
    public function readiness(): array
    {
        return $this->get('READINESS');
    }

    /**
     * --------------------------------------------------------------------------
     * Complete Market Regulatory Knowledge
     * --------------------------------------------------------------------------
     */
    public function all(): array
    {
        return self::REGULATIONS;
    }
}