<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Compliance\Certification;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Certification Compliance Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Provide reusable certification compliance knowledge for the
 * global textile and apparel industry.
 *
 * Business Questions
 * --------------------------------------------------------------------------
 * • Which certifications are required by buyers?
 * • Which certifications support sustainability?
 * • Which certifications improve supplier readiness?
 * • Which certifications are relevant for export markets?
 *
 * Business Value
 * --------------------------------------------------------------------------
 * Manufacturer
 *
 * • Understand global certification requirements
 * • Improve export readiness
 * • Increase buyer confidence
 *
 * Buyer / Brand
 *
 * • Evaluate supplier compliance
 * • Reduce sourcing risks
 *
 * Industry
 *
 * • Promote internationally recognized standards
 * • Improve transparency
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
 * - OpportunityEngine
 * - Company Intelligence
 */
class CertificationComplianceService
{
    /**
     * --------------------------------------------------------------------------
     * Certification Knowledge Base
     * --------------------------------------------------------------------------
     */
    protected const CERTIFICATIONS = [

        /*
        |--------------------------------------------------------------------------
        | Sustainability
        |--------------------------------------------------------------------------
        */

        'SUSTAINABILITY' => [

            'Global Recycled Standard (GRS)',

            'Global Organic Textile Standard (GOTS)',

            'OEKO-TEX Standard 100',

            'OEKO-TEX MADE IN GREEN',

            'bluesign®',

            'Better Cotton',

        ],

        /*
        |--------------------------------------------------------------------------
        | Quality Management
        |--------------------------------------------------------------------------
        */

        'QUALITY' => [

            'ISO 9001',

            'ISO 14001',

            'ISO 45001',

            'ISO 50001',

        ],

        /*
        |--------------------------------------------------------------------------
        | Social Compliance
        |--------------------------------------------------------------------------
        */

        'SOCIAL' => [

            'BSCI',

            'SMETA',

            'WRAP',

            'SA8000',

        ],

        /*
        |--------------------------------------------------------------------------
        | Environmental
        |--------------------------------------------------------------------------
        */

        'ENVIRONMENT' => [

            'ZDHC',

            'Higg FEM',

            'Higg FSLM',

        ],

        /*
        |--------------------------------------------------------------------------
        | Product & Safety
        |--------------------------------------------------------------------------
        */

        'PRODUCT' => [

            'RDS',

            'RWS',

            'FSC Chain of Custody',

            'Leather Working Group (LWG)',

        ],

        /*
        |--------------------------------------------------------------------------
        | Traceability
        |--------------------------------------------------------------------------
        */

        'TRACEABILITY' => [

            'Digital Product Passport',

            'TextileGenesis',

            'OEKO-TEX ECO PASSPORT',

        ],

    ];

    /**
     * --------------------------------------------------------------------------
     * Get Certification Group
     * --------------------------------------------------------------------------
     */
    public function get(string $group): array
    {
        return self::CERTIFICATIONS[strtoupper($group)] ?? [];
    }

    /**
     * --------------------------------------------------------------------------
     * Sustainability Certifications
     * --------------------------------------------------------------------------
     */
    public function sustainability(): array
    {
        return $this->get('SUSTAINABILITY');
    }

    /**
     * --------------------------------------------------------------------------
     * Quality Certifications
     * --------------------------------------------------------------------------
     */
    public function quality(): array
    {
        return $this->get('QUALITY');
    }

    /**
     * --------------------------------------------------------------------------
     * Social Compliance Certifications
     * --------------------------------------------------------------------------
     */
    public function social(): array
    {
        return $this->get('SOCIAL');
    }

    /**
     * --------------------------------------------------------------------------
     * Environmental Certifications
     * --------------------------------------------------------------------------
     */
    public function environment(): array
    {
        return $this->get('ENVIRONMENT');
    }

    /**
     * --------------------------------------------------------------------------
     * Product Certifications
     * --------------------------------------------------------------------------
     */
    public function product(): array
    {
        return $this->get('PRODUCT');
    }

    /**
     * --------------------------------------------------------------------------
     * Traceability Certifications
     * --------------------------------------------------------------------------
     */
    public function traceability(): array
    {
        return $this->get('TRACEABILITY');
    }

    /**
     * --------------------------------------------------------------------------
     * Complete Certification Knowledge
     * --------------------------------------------------------------------------
     */
    public function all(): array
    {
        return self::CERTIFICATIONS;
    }
}