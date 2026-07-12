<?php

declare(strict_types=1);

namespace App\Services\Knowledge\Builders;

use App\Services\Knowledge\Contracts\NodeBuilderInterface;
use App\Services\Knowledge\KnowledgeNode;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Region Node Builder
 * ==========================================================================
 *
 * Responsible for creating Region Knowledge Nodes.
 *
 * Used by:
 *
 * • GraphBuilder
 * • KnowledgeGraphService
 * • Executive AI
 *
 */

class RegionNodeBuilder implements NodeBuilderInterface
{
    /**
     * Build Region Knowledge Node.
     *
     * Expected source:
     *
     * [
     *     'code' => 'ASEAN',
     *     'name' => 'ASEAN',
     *     'description' => 'Association of Southeast Asian Nations',
     *     'priority' => 10,
     *     'color' => '#2563EB',
     *     'icon' => '🌏',
     * ]
     *
     * @param mixed $source
     */
    public function build(mixed $source): KnowledgeNode
    {
        return new KnowledgeNode(

            id: $source['code'],

            type: 'region',

            label: $source['name'],

            attributes: [

                /*
                |--------------------------------------------------------------------------
                | Identity
                |--------------------------------------------------------------------------
                */

                'code' => $source['code'],

                'name' => $source['name'],

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
                | Countries
                |--------------------------------------------------------------------------
                */

                'countries' => $source['countries'] ?? [],

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