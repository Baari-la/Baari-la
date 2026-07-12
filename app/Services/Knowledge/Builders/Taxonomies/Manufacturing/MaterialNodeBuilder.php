<?php

declare(strict_types=1);

namespace App\Services\Knowledge\Builders\Taxonomies;

use App\Services\Knowledge\Builders\AbstractTaxonomyNodeBuilder;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Material Node Builder
 * ==========================================================================
 *
 * Responsible for creating Material Knowledge Nodes.
 *
 * Used by:
 *
 * • GraphBuilder
 * • KnowledgeGraphService
 * • Executive AI
 * • Material Intelligence
 *
 */

class MaterialNodeBuilder extends AbstractTaxonomyNodeBuilder
{
    /**
     * Node Type.
     */
    protected function nodeType(): string
    {
        return 'material';
    }

    /**
     * Material Rules.
     */
    protected function rules(string $id): array
    {
        return $this->rules->get(
            "materials.{$id}",
            []
        );
    }

    /**
     * Material specific attributes.
     */
    protected function extraAttributes(
        array $source,
        array $rules
    ): array {

        return [

            /*
            |--------------------------------------------------------------------------
            | Classification
            |--------------------------------------------------------------------------
            */

            'material_category'
                => $source['category'] ?? null,

            'material_group'
                => $source['group'] ?? null,

            'origin'
                => $source['origin'] ?? null,

            /*
            |--------------------------------------------------------------------------
            | Fiber Information
            |--------------------------------------------------------------------------
            */

            'fiber_type'
                => $source['fiber_type'] ?? null,

            'natural'
                => $source['natural'] ?? false,

            'synthetic'
                => $source['synthetic'] ?? false,

            'regenerated'
                => $source['regenerated'] ?? false,

            'recycled'
                => $source['recycled'] ?? false,

            /*
            |--------------------------------------------------------------------------
            | Applications
            |--------------------------------------------------------------------------
            */

            'applications'
                => $rules['applications'] ?? [],

            /*
            |--------------------------------------------------------------------------
            | Products
            |--------------------------------------------------------------------------
            */

            'products'
                => $rules['products'] ?? [],

            /*
            |--------------------------------------------------------------------------
            | Technologies
            |--------------------------------------------------------------------------
            */

            'technologies'
                => $rules['technologies'] ?? [],

            /*
            |--------------------------------------------------------------------------
            | Certifications
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
            | Technical Properties
            |--------------------------------------------------------------------------
            */

            'properties'
                => $source['properties'] ?? [],

            /*
            |--------------------------------------------------------------------------
            | End Uses
            |--------------------------------------------------------------------------
            */

            'end_uses'
                => $source['end_uses'] ?? [],

            /*
            |--------------------------------------------------------------------------
            | Knowledge
            |--------------------------------------------------------------------------
            */

            'knowledge_key'
                => 'material.' . $source['id'],

        ];
    }
}