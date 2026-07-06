<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Compliance\Certification;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Certification Body Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Provide reusable knowledge about internationally recognized
 * certification bodies, testing organizations and auditing agencies
 * used throughout the global textile and apparel industry.
 *
 * Business Questions
 * --------------------------------------------------------------------------
 * • Which certification body issued this certificate?
 * • Which organizations are internationally recognized?
 * • Which body performs certification, testing or auditing?
 * • Which organization is appropriate for buyer requirements?
 *
 * Business Value
 * --------------------------------------------------------------------------
 * Manufacturer
 *
 * • Improve certification awareness
 * • Select appropriate certification partners
 * • Increase buyer confidence
 *
 * Buyer / Brand
 *
 * • Verify certification credibility
 * • Reduce compliance risks
 *
 * Industry
 *
 * • Promote internationally recognized certification bodies
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
 * - Company Intelligence
 */
class CertificationBodyService
{
    /**
     * --------------------------------------------------------------------------
     * Certification Body Knowledge Base
     * --------------------------------------------------------------------------
     */
    protected const ORGANIZATIONS = [

        /*
        |--------------------------------------------------------------------------
        | Global Certification Bodies
        |--------------------------------------------------------------------------
        */

        'CERTIFICATION' => [

            'Control Union',

            'Ecocert',

            'ICEA',

            'SCS Global Services',

            'TÜV SÜD',

            'TÜV Rheinland',

            'Bureau Veritas',

            'DNV',

        ],

        /*
        |--------------------------------------------------------------------------
        | Testing & Inspection
        |--------------------------------------------------------------------------
        */

        'TESTING' => [

            'SGS',

            'TESTEX',

            'Intertek',

            'Eurofins',

            'UL Solutions',

            'Bureau Veritas',

            'TÜV Rheinland',

        ],

        /*
        |--------------------------------------------------------------------------
        | Sustainability Organizations
        |--------------------------------------------------------------------------
        */

        'SUSTAINABILITY' => [

            'OEKO-TEX',

            'bluesign technologies',

            'Better Cotton',

            'ZDHC Foundation',

            'Textile Exchange',

            'Sustainable Apparel Coalition',

        ],

        /*
        |--------------------------------------------------------------------------
        | Social Compliance
        |--------------------------------------------------------------------------
        */

        'SOCIAL' => [

            'amfori',

            'Sedex',

            'WRAP',

            'SAI',

        ],

        /*
        |--------------------------------------------------------------------------
        | Traceability
        |--------------------------------------------------------------------------
        */

        'TRACEABILITY' => [

            'TextileGenesis',

            'TrusTrace',

            'Circularise',

        ],

    ];

    /**
     * --------------------------------------------------------------------------
     * Get Organization Group
     * --------------------------------------------------------------------------
     */
    public function get(string $group): array
    {
        return self::ORGANIZATIONS[strtoupper($group)] ?? [];
    }

    /**
     * --------------------------------------------------------------------------
     * Certification Bodies
     * --------------------------------------------------------------------------
     */
    public function certification(): array
    {
        return $this->get('CERTIFICATION');
    }

    /**
     * --------------------------------------------------------------------------
     * Testing Organizations
     * --------------------------------------------------------------------------
     */
    public function testing(): array
    {
        return $this->get('TESTING');
    }

    /**
     * --------------------------------------------------------------------------
     * Sustainability Organizations
     * --------------------------------------------------------------------------
     */
    public function sustainability(): array
    {
        return $this->get('SUSTAINABILITY');
    }

    /**
     * --------------------------------------------------------------------------
     * Social Compliance Organizations
     * --------------------------------------------------------------------------
     */
    public function social(): array
    {
        return $this->get('SOCIAL');
    }

    /**
     * --------------------------------------------------------------------------
     * Traceability Organizations
     * --------------------------------------------------------------------------
     */
    public function traceability(): array
    {
        return $this->get('TRACEABILITY');
    }

    /**
     * --------------------------------------------------------------------------
     * Complete Certification Body Knowledge
     * --------------------------------------------------------------------------
     */
    public function all(): array
    {
        return self::ORGANIZATIONS;
    }
}