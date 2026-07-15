<?php

declare(strict_types=1);

namespace App\Services\MasterData\Classification;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Classification Rules
 * ==========================================================================
 *
 * Rule repository for Master Data schema classification.
 *
 * Every schema profile contains:
 *
 * - mandatory
 * - preferred
 * - minimum_score
 * - weights
 *
 * This class contains NO scoring logic.
 *
 * ==========================================================================
 */
final class ClassificationRules
{
    /**
     * @var array<string,array<string,mixed>>
     */
    protected const RULES = [

        /*
        |--------------------------------------------------------------------------
        | Knowledge Node
        |--------------------------------------------------------------------------
        */

        'knowledge_node' => [

            /*
            |--------------------------------------------------------------
            | Mandatory Indicators
            |--------------------------------------------------------------
            */

            'mandatory' => [

                'description',

            ],

            /*
            |--------------------------------------------------------------
            | Preferred Indicators
            |--------------------------------------------------------------
            */

            'preferred' => [

                'applications',

                'advantages',

                'limitations',

                'related_products',

                'related_certifications',

                'related_markets',

                'typical_products',

                'typical_markets',

                'common_business_roles',

                'common_certifications',

                'common_sustainability',

            ],

            /*
            |--------------------------------------------------------------
            | Minimum score
            |--------------------------------------------------------------
            */

            'minimum_score' => 40,

            /*
            |--------------------------------------------------------------
            | Weights
            |--------------------------------------------------------------
            */

            'weights' => [

                'description' => 20,

                'summary' => 15,

                'category' => 5,

                'applications' => 20,

                'advantages' => 20,

                'limitations' => 20,

                'related_products' => 20,

                'related_certifications' => 20,

                'related_markets' => 15,

                'typical_products' => 20,

                'typical_markets' => 15,

                'common_business_roles' => 25,

                'common_certifications' => 25,

                'common_sustainability' => 20,

            ],

        ],

        /*
        |--------------------------------------------------------------------------
        | Taxonomy
        |--------------------------------------------------------------------------
        */

        'taxonomy' => [

            'mandatory' => [

                'parent',

                'parent_id',

            ],

            'preferred' => [

                'children',

                'level',

                'path',

                'tree',

            ],

            'minimum_score' => 30,

            'weights' => [

                'parent' => 30,

                'parent_id' => 30,

                'children' => 20,

                'level' => 15,

                'path' => 15,

                'tree' => 15,

            ],

        ],

        /*
        |--------------------------------------------------------------------------
        | Lookup
        |--------------------------------------------------------------------------
        */

        'lookup' => [

            'mandatory' => [

                'id',

                'label',

            ],

            'preferred' => [

                'priority',

                'sort_order',

                'icon',

            ],

            'minimum_score' => 10,

            'weights' => [

                'id' => 5,

                'label' => 5,

                'name' => 5,

                'priority' => 2,

                'sort_order' => 2,

                'icon' => 1,

            ],

        ],

        /*
        |--------------------------------------------------------------------------
        | Relationship
        |--------------------------------------------------------------------------
        */

        'relationship' => [

            'mandatory' => [

                'source',

                'target',

            ],

            'preferred' => [

                'relationship',

                'weight',

            ],

            'minimum_score' => 30,

            'weights' => [

                'source' => 30,

                'target' => 30,

                'relationship' => 20,

                'weight' => 10,

            ],

        ],

        /*
        |--------------------------------------------------------------------------
        | Configuration
        |--------------------------------------------------------------------------
        */

        'configuration' => [

            'mandatory' => [

                'key',

                'value',

            ],

            'preferred' => [

                'group',

                'default',

            ],

            'minimum_score' => 30,

            'weights' => [

                'key' => 30,

                'value' => 30,

                'group' => 10,

                'default' => 10,

            ],

        ],

    ];

    /**
     * Returns all rule profiles.
     *
     * @return array<string,array<string,mixed>>
     */
    public function all(): array
    {
        return self::RULES;
    }

    /**
     * Returns one rule profile.
     *
     * @return array<string,mixed>
     */
    public function rule(
        string $type
    ): array
    {
        return self::RULES[$type] ?? [];
    }

    /**
     * Returns supported schema types.
     *
     * @return array<int,string>
     */
    public function types(): array
    {
        return array_keys(self::RULES);
    }

    /**
     * Checks whether a schema type exists.
     */
    public function has(
        string $type
    ): bool
    {
        return isset(self::RULES[$type]);
    }

    /**
     * Returns number of supported schema types.
     */
    public function count(): int
    {
        return count(self::RULES);
    }
}