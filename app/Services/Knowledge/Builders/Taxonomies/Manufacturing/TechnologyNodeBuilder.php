<?php

declare(strict_types=1);

namespace App\Services\Knowledge\Builders\Taxonomies;

use App\Services\Knowledge\Builders\AbstractTaxonomyNodeBuilder;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Technology Node Builder
 * ==========================================================================
 *
 * Responsible for creating Technology Knowledge Nodes.
 *
 * Used by:
 *
 * • GraphBuilder
 * • KnowledgeGraphService
 * • Executive AI
 *
 */

class TechnologyNodeBuilder extends AbstractTaxonomyNodeBuilder
{
    /**
     * Node Type.
     */
    protected function nodeType(): string
    {
        return 'technology';
    }

    /**
     * Load Technology Rules.
     */
    protected function rules(string $id): array
    {
        return $this->rules->forTechnology($id);
    }

    /**
     * Technology-specific attributes.
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

            'category'
                => $source['category'] ?? null,

            'subcategory'
                => $source['subcategory'] ?? null,

            /*
            |--------------------------------------------------------------------------
            | Industry
            |--------------------------------------------------------------------------
            */

            'industry_segments'
                => $source['industry_segments'] ?? [],

            'business_roles'
                => $source['business_roles'] ?? [],

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
            | Machinery
            |--------------------------------------------------------------------------
            */

            'machineries'
                => $rules['machineries'] ?? [],

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
            | Capabilities
            |--------------------------------------------------------------------------
            */

            'automation_level'
                => $source['automation_level'] ?? null,

            'digitalization'
                => $source['digitalization'] ?? null,

            'energy_efficiency'
                => $source['energy_efficiency'] ?? null,

            /*
            |--------------------------------------------------------------------------
            | References
            |--------------------------------------------------------------------------
            */

            'standards'
                => $rules['standards'] ?? [],

            'related_technologies'
                => $rules['related_technologies'] ?? [],

        ];
    }
}