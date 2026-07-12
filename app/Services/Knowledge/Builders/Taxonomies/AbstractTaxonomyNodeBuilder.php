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
 * Abstract Taxonomy Node Builder
 * ==========================================================================
 *
 * Base class for taxonomy-based Knowledge Nodes.
 *
 * Used by:
 *
 * • BusinessEcosystemNodeBuilder
 * • IndustrySegmentNodeBuilder
 * • BusinessRoleNodeBuilder
 * • TechnologyNodeBuilder
 * • CertificationNodeBuilder
 * • SustainabilityNodeBuilder
 * • MachineryCategoryNodeBuilder
 *
 */

abstract class AbstractTaxonomyNodeBuilder implements NodeBuilderInterface
{
    public function __construct(
        protected KnowledgeRulesService $rules,
    ) {
    }

    /**
     * Node Type.
     *
     * Example:
     * business_role
     * industry_segment
     * technology
     */
    abstract protected function nodeType(): string;

    /**
     * Rules lookup.
     *
     * Example:
     * $this->rules->forBusinessRole(...)
     */
    abstract protected function rules(string $id): array;

    /**
     * Extra attributes supplied by child class.
     */
    protected function extraAttributes(
        array $source,
        array $rules
    ): array {
        return [];
    }

    /**
     * Build Knowledge Node.
     */
    public function build(mixed $source): KnowledgeNode
    {
        $rules = $this->rules($source['id']);

        $attributes = [

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
            | Metadata
            |--------------------------------------------------------------------------
            */

            'source' => 'DMF',

            'version' => config('app.version'),

        ];

        $attributes = array_merge(

            $attributes,

            $this->extraAttributes(
                $source,
                $rules
            )

        );

        return new KnowledgeNode(

            id: $source['id'],

            type: $this->nodeType(),

            label: $source['label'],

            attributes: $attributes

        );
    }
}