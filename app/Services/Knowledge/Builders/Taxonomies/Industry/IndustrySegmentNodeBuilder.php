<?php

declare(strict_types=1);

namespace App\Services\Knowledge\Builders;

use App\Services\Knowledge\Contracts\NodeBuilderInterface;
use App\Services\Knowledge\KnowledgeNode;
use App\Services\Knowledge\KnowledgeRulesService;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Industry Segment Node Builder
 * ==========================================================================
 *
 * Responsible for creating Industry Segment Knowledge Nodes.
 *
 * Used by:
 *
 * • GraphBuilder
 * • KnowledgeGraphService
 * • Executive AI
 *
 */

class IndustrySegmentNodeBuilder implements NodeBuilderInterface
{
    public function __construct(
        protected KnowledgeRulesService $rules,
    ) {
    }

    /**
     * Build Industry Segment Node.
     *
     * Expected source:
     *
     * [
     *     'id' => 'sportswear',
     *     'label' => 'Sportswear',
     *     'description' => 'Sportswear & Activewear',
     *     'ecosystem' => 'apparel',
     *     'icon' => '🏃',
     *     'color' => '#2563EB',
     *     'priority' => 10,
     * ]
     */
    public function build(mixed $source): KnowledgeNode
    {
        $rules = $this->rules
            ->forIndustrySegment($source['id']);

        return new KnowledgeNode(

            id: $source['id'],

            type: 'industry_segment',

            label: $source['label'],

            attributes: [

                /*
                |--------------------------------------------------------------------------
                | Identity
                |--------------------------------------------------------------------------
                */

                'code' => $source['id'],

                'name' => $source['label'],

                'description'
                    => $source['description'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | Parent Ecosystem
                |--------------------------------------------------------------------------
                */

                'business_ecosystem'
                    => $source['ecosystem'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | Display
                |--------------------------------------------------------------------------
                */

                'icon'
                    => $source['icon'] ?? null,

                'color'
                    => $source['color'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | Priority
                |--------------------------------------------------------------------------
                */

                'priority'
                    => $source['priority'] ?? 0,

                /*
                |--------------------------------------------------------------------------
                | Recommended Business Roles
                |--------------------------------------------------------------------------
                */

                'business_roles'
                    => $rules['business_roles'] ?? [],

                /*
                |--------------------------------------------------------------------------
                | Recommended Products
                |--------------------------------------------------------------------------
                */

                'products'
                    => $rules['products'] ?? [],

                /*
                |--------------------------------------------------------------------------
                | Recommended Technologies
                |--------------------------------------------------------------------------
                */

                'technologies'
                    => $rules['technologies'] ?? [],

                /*
                |--------------------------------------------------------------------------
                | Recommended Machinery
                |--------------------------------------------------------------------------
                */

                'machineries'
                    => $rules['machineries'] ?? [],

                /*
                |--------------------------------------------------------------------------
                | Recommended Certifications
                |--------------------------------------------------------------------------
                */

                'certifications'
                    => $rules['certifications'] ?? [],

                /*
                |--------------------------------------------------------------------------
                | Sustainability
                |--------------------------------------------------------------------------
                */

                'sustainability'
                    => $rules['sustainability'] ?? [],

                /*
                |--------------------------------------------------------------------------
                | Markets
                |--------------------------------------------------------------------------
                */

                'markets'
                    => $rules['markets'] ?? [],

                /*
                |--------------------------------------------------------------------------
                | Metadata
                |--------------------------------------------------------------------------
                */

                'source' => 'DMF',

                'version' => config('app.version'),

            ]

        );
    }
}