<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Capability\Material;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Material Capability Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Provide reusable material capability intelligence across the
 * global textile and apparel industry.
 *
 * Business Questions
 * --------------------------------------------------------------------------
 * • What materials can this company process?
 * • Which sustainable materials are supported?
 * • Which materials are suitable for buyer requirements?
 * • Which materials support innovation opportunities?
 *
 * Business Value
 * --------------------------------------------------------------------------
 * Manufacturer
 *
 * • Showcase material expertise
 * • Improve buyer confidence
 * • Support product development
 *
 * Buyer
 *
 * • Find capable suppliers
 * • Evaluate sourcing options
 *
 * Industry
 *
 * • Improve material visibility
 * • Accelerate material innovation
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
 * - Company Intelligence
 */
class MaterialCapabilityService
{
    /**
     * --------------------------------------------------------------------------
     * Material Capability Knowledge Base
     * --------------------------------------------------------------------------
     */
    protected const MATERIALS = [

        /*
        |--------------------------------------------------------------------------
        | Natural Fibers
        |--------------------------------------------------------------------------
        */

        'NATURAL' => [

            'Cotton',

            'Organic Cotton',

            'BCI Cotton',

            'Linen',

            'Hemp',

            'Ramie',

            'Wool',

            'Silk',

            'Bamboo',

        ],

        /*
        |--------------------------------------------------------------------------
        | Regenerated Fibers
        |--------------------------------------------------------------------------
        */

        'REGENERATED' => [

            'Viscose',

            'Modal',

            'Lyocell',

            'Cupro',

            'Acetate',

        ],

        /*
        |--------------------------------------------------------------------------
        | Synthetic Fibers
        |--------------------------------------------------------------------------
        */

        'SYNTHETIC' => [

            'Polyester',

            'Recycled Polyester',

            'Nylon',

            'Recycled Nylon',

            'Acrylic',

            'Polypropylene',

            'Spandex',

        ],

        /*
        |--------------------------------------------------------------------------
        | Sustainable Materials
        |--------------------------------------------------------------------------
        */

        'SUSTAINABLE' => [

            'Organic Cotton',

            'Recycled Polyester',

            'Recycled Nylon',

            'Bamboo',

            'Lyocell',

            'Hemp',

        ],

        /*
        |--------------------------------------------------------------------------
        | Functional Materials
        |--------------------------------------------------------------------------
        */

        'FUNCTIONAL' => [

            'Moisture Management',

            'Cooling Fabric',

            'UV Protection',

            'Anti Odor',

            'Flame Retardant',

            'Water Repellent',

            'Anti Bacterial',

        ],

    ];

    /**
     * --------------------------------------------------------------------------
     * Get Capability Group
     * --------------------------------------------------------------------------
     */
    public function get(string $group): array
    {
        return self::MATERIALS[strtoupper($group)] ?? [];
    }

    /**
     * --------------------------------------------------------------------------
     * Natural Fibers
     * --------------------------------------------------------------------------
     */
    public function natural(): array
    {
        return $this->get('NATURAL');
    }

    /**
     * --------------------------------------------------------------------------
     * Regenerated Fibers
     * --------------------------------------------------------------------------
     */
    public function regenerated(): array
    {
        return $this->get('REGENERATED');
    }

    /**
     * --------------------------------------------------------------------------
     * Synthetic Fibers
     * --------------------------------------------------------------------------
     */
    public function synthetic(): array
    {
        return $this->get('SYNTHETIC');
    }

    /**
     * --------------------------------------------------------------------------
     * Sustainable Materials
     * --------------------------------------------------------------------------
     */
    public function sustainable(): array
    {
        return $this->get('SUSTAINABLE');
    }

    /**
     * --------------------------------------------------------------------------
     * Functional Materials
     * --------------------------------------------------------------------------
     */
    public function functional(): array
    {
        return $this->get('FUNCTIONAL');
    }

    /**
     * --------------------------------------------------------------------------
     * Complete Material Capability
     * --------------------------------------------------------------------------
     */
    public function all(): array
    {
        return self::MATERIALS;
    }
}