<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Compliance\Testing;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Testing Compliance Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Provide reusable testing compliance knowledge across the
 * global textile and apparel industry.
 *
 * Business Questions
 * --------------------------------------------------------------------------
 * • Which tests are commonly required by buyers?
 * • Which laboratory tests support certification?
 * • Which tests are required before shipment?
 * • Which product performance tests are commonly used?
 *
 * Business Value
 * --------------------------------------------------------------------------
 * Manufacturer
 *
 * • Improve product quality
 * • Prepare for buyer audits
 * • Increase export readiness
 *
 * Buyer / Brand
 *
 * • Verify product quality
 * • Reduce product risks
 *
 * Industry
 *
 * • Promote internationally accepted testing practices
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
class TestingComplianceService
{
    /**
     * --------------------------------------------------------------------------
     * Testing Knowledge Base
     * --------------------------------------------------------------------------
     */
    protected const TESTS = [

        /*
        |--------------------------------------------------------------------------
        | Physical Testing
        |--------------------------------------------------------------------------
        */

        'PHYSICAL' => [

            'Tensile Strength',

            'Tear Strength',

            'Bursting Strength',

            'Seam Strength',

            'Abrasion Resistance',

            'Pilling Resistance',

            'Dimensional Stability',

            'Shrinkage',

        ],

        /*
        |--------------------------------------------------------------------------
        | Color Fastness
        |--------------------------------------------------------------------------
        */

        'COLOR_FASTNESS' => [

            'Washing',

            'Rubbing',

            'Perspiration',

            'Water',

            'Sea Water',

            'Chlorinated Water',

            'Light',

            'Dry Cleaning',

        ],

        /*
        |--------------------------------------------------------------------------
        | Chemical Testing
        |--------------------------------------------------------------------------
        */

        'CHEMICAL' => [

            'Restricted Substances',

            'Formaldehyde',

            'Heavy Metals',

            'Azo Dyes',

            'Phthalates',

            'PFAS',

            'pH Value',

        ],

        /*
        |--------------------------------------------------------------------------
        | Functional Testing
        |--------------------------------------------------------------------------
        */

        'FUNCTIONAL' => [

            'Water Repellency',

            'Waterproof',

            'Breathability',

            'Moisture Management',

            'UV Protection',

            'Flame Resistance',

            'Anti Bacterial',

        ],

        /*
        |--------------------------------------------------------------------------
        | Sustainability Testing
        |--------------------------------------------------------------------------
        */

        'SUSTAINABILITY' => [

            'Recycled Content Verification',

            'Biodegradability',

            'Carbon Footprint',

            'Chemical Compliance',

            'Microplastic Assessment',

        ],

        /*
        |--------------------------------------------------------------------------
        | Garment Performance
        |--------------------------------------------------------------------------
        */

        'GARMENT' => [

            'Fit Evaluation',

            'Wear Trial',

            'Appearance Retention',

            'Durability',

            'Care Label Verification',

        ],

    ];

    /**
     * --------------------------------------------------------------------------
     * Get Testing Group
     * --------------------------------------------------------------------------
     */
    public function get(string $group): array
    {
        return self::TESTS[strtoupper($group)] ?? [];
    }

    /**
     * Physical Testing
     */
    public function physical(): array
    {
        return $this->get('PHYSICAL');
    }

    /**
     * Color Fastness Testing
     */
    public function colorFastness(): array
    {
        return $this->get('COLOR_FASTNESS');
    }

    /**
     * Chemical Testing
     */
    public function chemical(): array
    {
        return $this->get('CHEMICAL');
    }

    /**
     * Functional Testing
     */
    public function functional(): array
    {
        return $this->get('FUNCTIONAL');
    }

    /**
     * Sustainability Testing
     */
    public function sustainability(): array
    {
        return $this->get('SUSTAINABILITY');
    }

    /**
     * Garment Performance Testing
     */
    public function garment(): array
    {
        return $this->get('GARMENT');
    }

    /**
     * Complete Testing Knowledge
     */
    public function all(): array
    {
        return self::TESTS;
    }
}