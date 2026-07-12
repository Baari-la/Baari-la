<?php

declare(strict_types=1);

namespace App\Services\Knowledge\Builders;

use App\Services\Knowledge\Contracts\NodeBuilderInterface;
use App\Services\Knowledge\KnowledgeNode;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Business Role Node Builder
 * ==========================================================================
 *
 * Responsible for creating Business Role Knowledge Nodes.
 *
 * Used by:
 *
 * • GraphBuilder
 * • KnowledgeGraphService
 * • Executive AI
 *
 */

class BusinessRoleNodeBuilder implements NodeBuilderInterface
{
    /**
     * Build Business Role Knowledge Node.
     *
     * Expected source:
     *
     * [
     *     'id' => 'garment_manufacturer',
     *     'label' => 'Garment Manufacturer',
     *     'icon' => '👕',
     *     'color' => '#2563EB',
     *     'priority' => 80,
     *     'ecosystem' => 'apparel',
     *     'segment' => 'garment',
     *     'upstream' => [...],
     *     'downstream' => [...],
     * ]
     */
    public function build(mixed $source): KnowledgeNode
    {
        return new KnowledgeNode(

            id: $source['id'],

            type: 'business_role',

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
                | Display
                |--------------------------------------------------------------------------
                */

                'icon' => $source['icon'] ?? null,

                'color' => $source['color'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | Classification
                |--------------------------------------------------------------------------
                */

                'ecosystem' => $source['ecosystem'] ?? null,

                'segment' => $source['segment'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | Supply Chain
                |--------------------------------------------------------------------------
                */

                'upstream' => $source['upstream'] ?? [],

                'downstream' => $source['downstream'] ?? [],

                /*
                |--------------------------------------------------------------------------
                | Priority
                |--------------------------------------------------------------------------
                */

                'priority' => $source['priority'] ?? 0,

                /*
                |--------------------------------------------------------------------------
                | Rules
                |--------------------------------------------------------------------------
                */

                'recommended_products'
                    => config(
                        "masterdata.role_rules.{$source['id']}.products",
                        []
                    ),

                'recommended_technologies'
                    => config(
                        "masterdata.role_rules.{$source['id']}.technologies",
                        []
                    ),

                'recommended_machineries'
                    => config(
                        "masterdata.role_rules.{$source['id']}.machineries",
                        []
                    ),

                'recommended_certifications'
                    => config(
                        "masterdata.role_rules.{$source['id']}.certifications",
                        []
                    ),

                'recommended_sustainability'
                    => config(
                        "masterdata.role_rules.{$source['id']}.sustainability",
                        []
                    ),

                'recommended_markets'
                    => config(
                        "masterdata.role_rules.{$source['id']}.markets",
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