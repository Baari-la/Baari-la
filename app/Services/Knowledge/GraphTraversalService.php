<?php

declare(strict_types=1);

namespace App\Services\Knowledge;

use Illuminate\Support\Collection;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Graph Traversal Service
 * ==========================================================================
 *
 * Traverses Textile Knowledge Graph.
 *
 * Responsibilities
 * ----------------
 * • Find Nodes
 * • Find Edges
 * • Find Neighbors
 * • Find Paths
 * • Find Connected Nodes
 * • Supply Chain Traversal
 * • Executive AI Traversal
 *
 */

class GraphTraversalService
{
    /**
     * Graph Result.
     */
    protected KnowledgeResult $graph;

    /**
     * Constructor.
     */
    public function __construct(
        KnowledgeResult $graph
    ) {
        $this->graph = $graph;
    }

    /*
    |--------------------------------------------------------------------------
    | Nodes
    |--------------------------------------------------------------------------
    */

    public function nodes(): Collection
    {
        return collect(
            $this->graph->nodes()
        );
    }

    public function node(string|int $id): ?KnowledgeNode
    {
        return $this->nodes()

            ->first(
                fn ($node) => $node->id() == $id
            );
    }

    public function nodesByType(
        string $type
    ): Collection {

        return $this->nodes()

            ->filter(
                fn ($node) => $node->type() === $type
            )

            ->values();
    }

    /*
    |--------------------------------------------------------------------------
    | Edges
    |--------------------------------------------------------------------------
    */

    public function edges(): Collection
    {
        return collect(
            $this->graph->edges()
        );
    }

    public function edgesFrom(
        string|int $id
    ): Collection {

        return $this->edges()

            ->filter(
                fn ($edge) => $edge->from() == $id
            )

            ->values();
    }

    public function edgesTo(
        string|int $id
    ): Collection {

        return $this->edges()

            ->filter(
                fn ($edge) => $edge->to() == $id
            )

            ->values();
    }

    public function edgesByRelationship(
        string $relationship
    ): Collection {

        return $this->edges()

            ->filter(
                fn ($edge)
                    => $edge->relationship() === $relationship
            )

            ->values();
    }

    /*
    |--------------------------------------------------------------------------
    | Neighbors
    |--------------------------------------------------------------------------
    */

    public function neighbors(
        string|int $id
    ): Collection {

        return $this->edgesFrom($id)

            ->map(
                fn ($edge)
                    => $this->node($edge->to())
            )

            ->filter()

            ->values();
    }

    public function neighborsByRelationship(
        string|int $id,
        string $relationship
    ): Collection {

        return $this->edgesFrom($id)

            ->filter(
                fn ($edge)
                    => $edge->relationship() === $relationship
            )

            ->map(
                fn ($edge)
                    => $this->node($edge->to())
            )

            ->filter()

            ->values();
    }

    /*
    |--------------------------------------------------------------------------
    | Connected Nodes
    |--------------------------------------------------------------------------
    */

    public function connectedNodes(
        string|int $id
    ): Collection {

        return collect()

            ->merge(
                $this->edgesFrom($id)

                    ->pluck('to')
            )

            ->merge(
                $this->edgesTo($id)

                    ->pluck('from')
            )

            ->unique()

            ->map(
                fn ($nodeId)
                    => $this->node($nodeId)
            )

            ->filter()

            ->values();
    }

    /*
    |--------------------------------------------------------------------------
    | Supply Chain
    |--------------------------------------------------------------------------
    */

    public function supplyChain(
        string|int $businessRole
    ): Collection {

        return collect()

            ->merge(

                $this->neighborsByRelationship(

                    $businessRole,

                    'uses'

                )

            )

            ->values();
    }

    /*
    |--------------------------------------------------------------------------
    | Export Readiness
    |--------------------------------------------------------------------------
    */

    public function exportMarkets(
        string|int $certification
    ): Collection {

        return $this->neighborsByRelationship(

            $certification,

            'recognized_in'

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Recommendation
    |--------------------------------------------------------------------------
    */

    public function recommendedTechnologies(
        string|int $role
    ): Collection {

        return $this->neighborsByRelationship(

            $role,

            'uses'

        );
    }

    public function requiredCertifications(
        string|int $technology
    ): Collection {

        return $this->neighborsByRelationship(

            $technology,

            'validated_by'

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Statistics
    |--------------------------------------------------------------------------
    */

    public function statistics(): array
    {
        return [

            'nodes' => $this->nodes()->count(),

            'edges' => $this->edges()->count(),

            'companies'
                => $this->nodesByType('company')->count(),

            'products'
                => $this->nodesByType('product')->count(),

            'technologies'
                => $this->nodesByType('technology')->count(),

            'markets'
                => $this->nodesByType('market')->count(),

            'certifications'
                => $this->nodesByType('certification')->count(),

        ];
    }
}