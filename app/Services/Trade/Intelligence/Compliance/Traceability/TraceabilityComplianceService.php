<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Compliance\Traceability;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Traceability Compliance Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Provide reusable traceability compliance knowledge across the
 * global textile and apparel industry.
 *
 * Traceability enables buyers and brands to understand
 * where products originate, how they are manufactured,
 * and how they move throughout the supply chain.
 *
 * Business Questions
 * --------------------------------------------------------------------------
 * • Can raw materials be traced?
 * • Can production processes be traced?
 * • Can suppliers support Digital Product Passport?
 * • Can buyers verify supply chain transparency?
 *
 * Business Value
 * --------------------------------------------------------------------------
 * Manufacturer
 *
 * • Improve supply chain transparency
 * • Prepare for Digital Product Passport
 * • Increase buyer confidence
 *
 * Buyer / Brand
 *
 * • Verify product origin
 * • Improve responsible sourcing
 * • Reduce supply chain risks
 *
 * Industry
 *
 * • Promote transparent supply chains
 * • Support circular textile economy
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
 * - ComplianceOrchestrator
 * - Supplier Readiness Intelligence
 * - MatchingEngine
 * - Company Intelligence
 */
class TraceabilityComplianceService
{
    /**
     * --------------------------------------------------------------------------
     * Traceability Knowledge Base
     * --------------------------------------------------------------------------
     */
    protected const TRACEABILITY = [

        /*
        |--------------------------------------------------------------------------
        | Raw Material
        |--------------------------------------------------------------------------
        */

        'RAW_MATERIAL' => [

            'Fiber Origin',

            'Raw Material Supplier',

            'Farm Identification',

            'Country of Origin',

            'Material Composition',

        ],

        /*
        |--------------------------------------------------------------------------
        | Yarn Traceability
        |--------------------------------------------------------------------------
        */

        'YARN' => [

            'Spinning Mill',

            'Yarn Batch',

            'Yarn Lot Number',

            'Yarn Composition',

            'Yarn Manufacturing Date',

        ],

        /*
        |--------------------------------------------------------------------------
        | Fabric Traceability
        |--------------------------------------------------------------------------
        */

        'FABRIC' => [

            'Knitting Mill',

            'Weaving Mill',

            'Dyeing Mill',

            'Finishing Mill',

            'Fabric Batch',

        ],

        /*
        |--------------------------------------------------------------------------
        | Chemical Traceability
        |--------------------------------------------------------------------------
        */

        'CHEMICAL' => [

            'Chemical Supplier',

            'Chemical Batch',

            'MRSL Compliance',

            'Chemical Declaration',

            'Safety Data Sheet',

        ],

        /*
        |--------------------------------------------------------------------------
        | Manufacturing
        |--------------------------------------------------------------------------
        */

        'MANUFACTURING' => [

            'Factory Identification',

            'Production Line',

            'Production Batch',

            'Machine Traceability',

            'Production Date',

        ],

        /*
        |--------------------------------------------------------------------------
        | Supply Chain
        |--------------------------------------------------------------------------
        */

        'SUPPLY_CHAIN' => [

            'Tier 1 Supplier',

            'Tier 2 Supplier',

            'Tier 3 Supplier',

            'Logistics Tracking',

            'Warehouse Tracking',

        ],

        /*
        |--------------------------------------------------------------------------
        | Product Identity
        |--------------------------------------------------------------------------
        */

        'PRODUCT' => [

            'Product Identifier',

            'QR Code',

            'RFID',

            'Barcode',

            'Digital Product Passport',

        ],

        /*
        |--------------------------------------------------------------------------
        | Circular Economy
        |--------------------------------------------------------------------------
        */

        'CIRCULAR' => [

            'Recycled Content',

            'Material Recovery',

            'Product Lifecycle',

            'Reuse Information',

            'End-of-Life Guidance',

        ],

    ];

    /**
     * --------------------------------------------------------------------------
     * Get Traceability Group
     * --------------------------------------------------------------------------
     */
    public function get(string $group): array
    {
        return self::TRACEABILITY[strtoupper($group)] ?? [];
    }

    public function rawMaterial(): array
    {
        return $this->get('RAW_MATERIAL');
    }

    public function yarn(): array
    {
        return $this->get('YARN');
    }

    public function fabric(): array
    {
        return $this->get('FABRIC');
    }

    public function chemical(): array
    {
        return $this->get('CHEMICAL');
    }

    public function manufacturing(): array
    {
        return $this->get('MANUFACTURING');
    }

    public function supplyChain(): array
    {
        return $this->get('SUPPLY_CHAIN');
    }

    public function product(): array
    {
        return $this->get('PRODUCT');
    }

    public function circular(): array
    {
        return $this->get('CIRCULAR');
    }

    /**
     * --------------------------------------------------------------------------
     * Complete Traceability Knowledge
     * --------------------------------------------------------------------------
     */
    public function all(): array
    {
        return self::TRACEABILITY;
    }
}