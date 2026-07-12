<?php

declare(strict_types=1);

namespace App\Services\Knowledge\Builders\Taxonomies;

use App\Services\Knowledge\Builders\AbstractTaxonomyNodeBuilder;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Certification Node Builder
 * ==========================================================================
 *
 * Responsible for creating Certification Knowledge Nodes.
 *
 * Used by:
 *
 * • GraphBuilder
 * • KnowledgeGraphService
 * • KnowledgeEvaluationService
 * • Executive AI
 *
 */

class CertificationNodeBuilder extends AbstractTaxonomyNodeBuilder
{
    /**
     * Node type.
     */
    protected function nodeType(): string
    {
        return 'certification';
    }

    /**
     * Load certification rules.
     */
    protected function rules(string $id): array
    {
        return $this->rules->forCertification($id);
    }

    /**
     * Certification specific attributes.
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

            'scope'
                => $source['scope'] ?? null,

            'issuing_body'
                => $source['issuing_body'] ?? null,

            /*
            |--------------------------------------------------------------------------
            | Business Coverage
            |--------------------------------------------------------------------------
            */

            'industry_segments'
                => $source['industry_segments'] ?? [],

            'business_roles'
                => $source['business_roles'] ?? [],

            /*
            |--------------------------------------------------------------------------
            | Related Products
            |--------------------------------------------------------------------------
            */

            'products'
                => $rules['products'] ?? [],

            /*
            |--------------------------------------------------------------------------
            | Related Technologies
            |--------------------------------------------------------------------------
            */

            'technologies'
                => $rules['technologies'] ?? [],

            /*
            |--------------------------------------------------------------------------
            | Related Machinery
            |--------------------------------------------------------------------------
            */

            'machineries'
                => $rules['machineries'] ?? [],

            /*
            |--------------------------------------------------------------------------
            | Sustainability
            |--------------------------------------------------------------------------
            */

            'sustainability'
                => $rules['sustainability'] ?? [],

            /*
            |--------------------------------------------------------------------------
            | Export Markets
            |--------------------------------------------------------------------------
            */

            'markets'
                => $rules['markets'] ?? [],

            /*
            |--------------------------------------------------------------------------
            | Compliance
            |--------------------------------------------------------------------------
            */

            'mandatory'
                => $source['mandatory'] ?? false,

            'buyer_required'
                => $source['buyer_required'] ?? false,

            'renewable'
                => $source['renewable'] ?? true,

            'validity_years'
                => $source['validity_years'] ?? null,

            /*
            |--------------------------------------------------------------------------
            | Recognition
            |--------------------------------------------------------------------------
            */

            'recognized_by'
                => $rules['recognized_by'] ?? [],

            'equivalent_to'
                => $rules['equivalent_to'] ?? [],

            /*
            |--------------------------------------------------------------------------
            | Standards
            |--------------------------------------------------------------------------
            */

            'standard'
                => $source['standard'] ?? null,

            'reference'
                => $source['reference'] ?? null,

            /*
            |--------------------------------------------------------------------------
            | Knowledge
            |--------------------------------------------------------------------------
            */

            'knowledge_key'
                => 'certification.' . $source['id'],

        ];
    }
}