<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Capability\Production;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Production Capability Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Provide reusable production capability knowledge across the
 * global textile and apparel value chain.
 *
 * Business Questions
 * --------------------------------------------------------------------------
 * • What production processes are supported?
 * • Which manufacturing stages are available?
 * • Which production capabilities fit buyer requirements?
 * • Which factories can support integrated production?
 *
 * Business Value
 * --------------------------------------------------------------------------
 * Manufacturer
 *
 * • Showcase manufacturing capability
 * • Improve factory visibility
 * • Support capability matching
 *
 * Buyer
 *
 * • Understand production capabilities
 * • Identify integrated suppliers
 *
 * Industry
 *
 * • Improve supply chain transparency
 * • Strengthen industrial collaboration
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
 * - MatchingEngine
 * - OpportunityEngine
 * - Company Intelligence
 */
class ProductionCapabilityService
{
    /**
     * --------------------------------------------------------------------------
     * Production Capability Knowledge Base
     * --------------------------------------------------------------------------
     */
    protected const CAPABILITIES = [

        /*
        |--------------------------------------------------------------------------
        | Fiber Processing
        |--------------------------------------------------------------------------
        */

        'FIBER' => [

            'Fiber Preparation',

            'Fiber Blending',

            'Fiber Recycling',

        ],

        /*
        |--------------------------------------------------------------------------
        | Spinning
        |--------------------------------------------------------------------------
        */

        'SPINNING' => [

            'Ring Spinning',

            'Open End Spinning',

            'Air Jet Spinning',

            'Compact Spinning',

            'Fancy Yarn',

            'Core Spun Yarn',

            'Melange Yarn',

        ],

        /*
        |--------------------------------------------------------------------------
        | Fabric Formation
        |--------------------------------------------------------------------------
        */

        'FABRIC' => [

            'Circular Knitting',

            'Warp Knitting',

            'Flat Knitting',

            'Weaving',

            'Jacquard Weaving',

            'Denim Weaving',

            'Nonwoven',

        ],

        /*
        |--------------------------------------------------------------------------
        | Wet Processing
        |--------------------------------------------------------------------------
        */

        'WET_PROCESSING' => [

            'Scouring',

            'Bleaching',

            'Mercerizing',

            'Dyeing',

            'Printing',

            'Digital Printing',

            'Finishing',

            'Coating',

            'Laminating',

        ],

        /*
        |--------------------------------------------------------------------------
        | Garment Manufacturing
        |--------------------------------------------------------------------------
        */

        'GARMENT' => [

            'Pattern Making',

            'Marker Planning',

            'Cutting',

            'Sewing',

            'Embroidery',

            'Heat Transfer',

            'Washing',

            'Garment Dyeing',

            'Packing',

        ],

        /*
        |--------------------------------------------------------------------------
        | Quality & Testing
        |--------------------------------------------------------------------------
        */

        'QUALITY' => [

            'Quality Control',

            'Laboratory Testing',

            'Physical Testing',

            'Chemical Testing',

            'Color Fastness Testing',

            'Final Inspection',

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
     * Fiber Processing
     * --------------------------------------------------------------------------
     */
    public function fiber(): array
    {
        return $this->get('FIBER');
    }

    /**
     * --------------------------------------------------------------------------
     * Spinning
     * --------------------------------------------------------------------------
     */
    public function spinning(): array
    {
        return $this->get('SPINNING');
    }

    /**
     * --------------------------------------------------------------------------
     * Fabric Formation
     * --------------------------------------------------------------------------
     */
    public function fabric(): array
    {
        return $this->get('FABRIC');
    }

    /**
     * --------------------------------------------------------------------------
     * Wet Processing
     * --------------------------------------------------------------------------
     */
    public function wetProcessing(): array
    {
        return $this->get('WET_PROCESSING');
    }

    /**
     * --------------------------------------------------------------------------
     * Garment Manufacturing
     * --------------------------------------------------------------------------
     */
    public function garment(): array
    {
        return $this->get('GARMENT');
    }

    /**
     * --------------------------------------------------------------------------
     * Quality & Testing
     * --------------------------------------------------------------------------
     */
    public function quality(): array
    {
        return $this->get('QUALITY');
    }

    /**
     * --------------------------------------------------------------------------
     * Complete Production Capability
     * --------------------------------------------------------------------------
     */
    public function all(): array
    {
        return self::CAPABILITIES;
    }
}