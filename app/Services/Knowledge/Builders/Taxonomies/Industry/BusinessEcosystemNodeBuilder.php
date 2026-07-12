<?php

declare(strict_types=1);

namespace App\Services\Knowledge\Builders;

use App\Services\Knowledge\Contracts\NodeBuilderInterface;
use App\Services\Knowledge\KnowledgeNode;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Business Ecosystem Node Builder
 * ==========================================================================
 *
 * Responsible for creating Business Ecosystem Knowledge Nodes.
 *
 * Used by:
 *
 * • GraphBuilder
 * • KnowledgeGraphService
 * • Executive AI
 *
 */

class BusinessEcosystemNodeBuilder implements NodeBuilderInterface
{
    /**
     * Build Business Ecosystem Node.
     *
     * Expected source:
     *
     * [
     *     'id' => 'apparel',
     *     'label' => 'Apparel Manufacturing',
     *     'description' => 'Garment & Fashion Industry',
     *     'icon' => '👕',
     *     'color' => '#2563EB',
     *     'priority' => 10,
     * ]
     */
    public function build(mixed $source): KnowledgeNode
    {
        return new KnowledgeNode(

            id: $source['id'],

            type: 'business_ecosystem',

            label: $source['label'],

            attributes: [

                /*
                |--------------------------------------------------------------------------
                | Identity
                |--------------------------------------------------------------------------
                */

                'code' => $source['id'],

                'name' => $source['label'],

                /*
                |--------------------------------------------------------------------------
                | Description
                |--------------------------------------------------------------------------
                */

                'description' => $source['description'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | Display
                |--------------------------------------------------------------------------
                */

                'icon' => $source['icon'] ?? null,

                'color' => $source['color'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | Priority
                |--------------------------------------------------------------------------
                */

                'priority' => $source['priority'] ?? 0,

                /*
                |--------------------------------------------------------------------------
                | Relationships
                |--------------------------------------------------------------------------
                */

                'industry_segments'
                    => config(
                        "masterdata.business_ecosystem_rules.{$source['id']}.segments",
                        []
                    ),

                'business_roles'
                    => config(
                        "masterdata.business_ecosystem_rules.{$source['id']}.roles",
                        []
                    ),

                'products'
                    => config(
                        "masterdata.business_ecosystem_rules.{$source['id']}.products",
                        []
                    ),

                'technologies'
                    => config(
                        "masterdata.business_ecosystem_rules.{$source['id']}.technologies",
                        []
                    ),

                'certifications'
                    => config(
                        "masterdata.business_ecosystem_rules.{$source['id']}.certifications",
                        []
                    ),

                'markets'
                    => config(
                        "masterdata.business_ecosystem_rules.{$source['id']}.markets",
                        []
                    ),

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