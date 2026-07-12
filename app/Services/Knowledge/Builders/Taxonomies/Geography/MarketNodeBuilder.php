<?php

declare(strict_types=1);

namespace App\Services\Knowledge\Builders\Taxonomies;

use App\Services\Knowledge\Builders\AbstractTaxonomyNodeBuilder;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Market Node Builder
 * ==========================================================================
 *
 * Responsible for creating Market Knowledge Nodes.
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

class MarketNodeBuilder extends AbstractTaxonomyNodeBuilder
{
    /**
     * Node type.
     */
    protected function nodeType(): string
    {
        return 'market';
    }

    /**
     * Market rules.
     */
    protected function rules(string $id): array
    {
        return $this->rules->forMarket($id);
    }

    /**
     * Market specific attributes.
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

            'market_type'
                => $source['market_type'] ?? 'country',

            'region'
                => $source['region'] ?? null,

            'countries'
                => $source['countries'] ?? [],

            /*
            |--------------------------------------------------------------------------
            | Trade
            |--------------------------------------------------------------------------
            */

            'trade_agreements'
                => $rules['trade_agreements'] ?? [],

            'trade_programs'
                => $rules['trade_programs'] ?? [],

            'import_requirements'
                => $rules['import_requirements'] ?? [],

            /*
            |--------------------------------------------------------------------------
            | Products
            |--------------------------------------------------------------------------
            */

            'preferred_products'
                => $rules['products'] ?? [],

            /*
            |--------------------------------------------------------------------------
            | Technologies
            |--------------------------------------------------------------------------
            */

            'preferred_technologies'
                => $rules['technologies'] ?? [],

            /*
            |--------------------------------------------------------------------------
            | Certifications
            |--------------------------------------------------------------------------
            */

            'required_certifications'
                => $rules['certifications'] ?? [],

            /*
            |--------------------------------------------------------------------------
            | Sustainability
            |--------------------------------------------------------------------------
            */

            'required_sustainability'
                => $rules['sustainability'] ?? [],

            /*
            |--------------------------------------------------------------------------
            | Regulations
            |--------------------------------------------------------------------------
            */

            'regulations'
                => $rules['regulations'] ?? [],

            'mandatory_regulations'
                => $rules['mandatory_regulations'] ?? [],

            /*
            |--------------------------------------------------------------------------
            | Buyer Requirements
            |--------------------------------------------------------------------------
            */

            'buyer_requirements'
                => $rules['buyer_requirements'] ?? [],

            /*
            |--------------------------------------------------------------------------
            | Logistics
            |--------------------------------------------------------------------------
            */

            'major_ports'
                => $rules['major_ports'] ?? [],

            'shipping_terms'
                => $rules['shipping_terms'] ?? [],

            /*
            |--------------------------------------------------------------------------
            | Market Intelligence
            |--------------------------------------------------------------------------
            */

            'growth'
                => $source['growth'] ?? null,

            'risk_level'
                => $source['risk_level'] ?? null,

            'market_size'
                => $source['market_size'] ?? null,

            /*
            |--------------------------------------------------------------------------
            | Knowledge
            |--------------------------------------------------------------------------
            */

            'knowledge_key'
                => 'market.' . $source['id'],

        ];
    }
}