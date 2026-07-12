<?php

declare(strict_types=1);

namespace App\Services\Knowledge\Builders\Taxonomies;

use App\Services\Knowledge\Builders\AbstractTaxonomyNodeBuilder;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Machinery Node Builder
 * ==========================================================================
 *
 * Responsible for creating Machinery Knowledge Nodes.
 *
 * Used by:
 *
 * • GraphBuilder
 * • KnowledgeGraphService
 * • Company Intelligence
 * • Executive AI
 *
 */

class MachineryNodeBuilder extends AbstractTaxonomyNodeBuilder
{
    /**
     * Node Type.
     */
    protected function nodeType(): string
    {
        return 'machinery';
    }

    /**
     * Machinery Rules.
     */
    protected function rules(string $id): array
    {
        return $this->rules->forMachinery($id);
    }

    /**
     * Machinery specific attributes.
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

            'machine_type'
                => $source['machine_type'] ?? null,

            /*
            |--------------------------------------------------------------------------
            | Manufacturer
            |--------------------------------------------------------------------------
            */

            'brand'
                => $source['brand'] ?? null,

            'model'
                => $source['model'] ?? null,

            'manufacturer_country'
                => $source['manufacturer_country'] ?? null,

            /*
            |--------------------------------------------------------------------------
            | Manufacturing
            |--------------------------------------------------------------------------
            */

            'industry_segments'
                => $source['industry_segments'] ?? [],

            'business_roles'
                => $source['business_roles'] ?? [],

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
            | Capabilities
            |--------------------------------------------------------------------------
            */

            'automation_level'
                => $source['automation_level'] ?? null,

            'industry_4'
                => $source['industry_4'] ?? false,

            'iot_ready'
                => $source['iot_ready'] ?? false,

            'ai_ready'
                => $source['ai_ready'] ?? false,

            'energy_efficient'
                => $source['energy_efficient'] ?? false,

            /*
            |--------------------------------------------------------------------------
            | Capacity
            |--------------------------------------------------------------------------
            */

            'capacity'
                => $source['capacity'] ?? null,

            'speed'
                => $source['speed'] ?? null,

            'working_width'
                => $source['working_width'] ?? null,

            /*
            |--------------------------------------------------------------------------
            | Knowledge
            |--------------------------------------------------------------------------
            */

            'knowledge_key'
                => 'machinery.' . $source['id'],

        ];
    }
}