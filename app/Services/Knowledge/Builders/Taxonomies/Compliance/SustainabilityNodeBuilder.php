<?php

declare(strict_types=1);

namespace App\Services\Knowledge\Builders\Taxonomies;

use App\Services\Knowledge\Builders\AbstractTaxonomyNodeBuilder;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Sustainability Node Builder
 * ==========================================================================
 *
 * Responsible for creating Sustainability Knowledge Nodes.
 *
 * Used by:
 *
 * • GraphBuilder
 * • KnowledgeGraphService
 * • KnowledgeEvaluationService
 * • KnowledgeRecommendationService
 * • Executive AI
 *
 */

class SustainabilityNodeBuilder extends AbstractTaxonomyNodeBuilder
{
    /**
     * Node type.
     */
    protected function nodeType(): string
    {
        return 'sustainability';
    }

    /**
     * Load Sustainability Rules.
     */
    protected function rules(string $id): array
    {
        return $this->rules->forSustainability($id);
    }

    /**
     * Sustainability specific attributes.
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

            'pillar'
                => $source['pillar'] ?? null,

            'framework'
                => $source['framework'] ?? null,

            /*
            |--------------------------------------------------------------------------
            | Industry Coverage
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
            | Markets
            |--------------------------------------------------------------------------
            */

            'markets'
                => $rules['markets'] ?? [],

            /*
            |--------------------------------------------------------------------------
            | ESG
            |--------------------------------------------------------------------------
            */

            'esg'
                => $source['esg'] ?? null,

            'sdgs'
                => $source['sdgs'] ?? [],

            'carbon_reduction'
                => $source['carbon_reduction'] ?? false,

            'water_reduction'
                => $source['water_reduction'] ?? false,

            'renewable_energy'
                => $source['renewable_energy'] ?? false,

            'circular_economy'
                => $source['circular_economy'] ?? false,

            /*
            |--------------------------------------------------------------------------
            | Compliance
            |--------------------------------------------------------------------------
            */

            'mandatory'
                => $source['mandatory'] ?? false,

            'buyer_required'
                => $source['buyer_required'] ?? false,

            /*
            |--------------------------------------------------------------------------
            | References
            |--------------------------------------------------------------------------
            */

            'related_tags'
                => $rules['related_tags'] ?? [],

            'related_regulations'
                => $rules['related_regulations'] ?? [],

            /*
            |--------------------------------------------------------------------------
            | Knowledge
            |--------------------------------------------------------------------------
            */

            'knowledge_key'
                => 'sustainability.' . $source['id'],

        ];
    }
}