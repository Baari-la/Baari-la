<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph\Repository;

use App\Services\MasterData\KnowledgeGraph\Model\GraphEdge;
use App\Services\MasterData\KnowledgeGraph\Model\GraphNode;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Graph Repository
 * ==========================================================================
 *
 * In-memory Knowledge Graph repository.
 *
 * Responsibilities
 * ----------------
 * - Store GraphNode
 * - Store GraphEdge
 * - Maintain adjacency index
 * - Provide graph lookup API
 *
 * Used by:
 *
 * - KnowledgeGraphBuilder
 * - GraphTraversal
 * - GraphValidator
 * - GraphStatistics
 * - Recommendation Engine
 * - Executive AI
 *
 * ==========================================================================
 */
final class GraphRepository
{
    /**
     * @var array<string,GraphNode>
     */
    protected array $nodes = [];

    /**
     * @var array<string,GraphEdge>
     */
    protected array $edges = [];

    /**
     * source => edge ids
     *
     * @var array<string,array<int,string>>
     */
    protected array $outgoing = [];

    /**
     * target => edge ids
     *
     * @var array<string,array<int,string>>
     */
    protected array $incoming = [];

    /**
     * source => target => edge id
     *
     * @var array<string,array<string,string>>
     */
    protected array $adjacency = [];

    /**
     * =========================================================================
     * Add Node
     * =========================================================================
     */
    public function addNode(
        GraphNode $node
    ): self
    {
        $this->nodes[$node->id()] = $node;

        return $this;
    }

    /**
     * =========================================================================
     * Add Edge
     * =========================================================================
     */
    public function addEdge(
        GraphEdge $edge
    ): self
    {
        if (! $this->hasNode($edge->source())) {
            return $this;
        }

        if (! $this->hasNode($edge->target())) {
            return $this;
        }

        $id = $edge->id();

        $this->edges[$id] = $edge;

        $this->outgoing[$edge->source()][] = $id;

        $this->incoming[$edge->target()][] = $id;

        $this->adjacency
            [$edge->source()]
            [$edge->target()]
            = $id;

        $this->nodes
            [$edge->source()]
            ->addOutgoing($id);

        $this->nodes
            [$edge->target()]
            ->addIncoming($id);

        return $this;
    }

    /**
     * =========================================================================
     * Node
     * =========================================================================
     */
    public function node(
        string $id
    ): ?GraphNode
    {
        return $this->nodes[$id] ?? null;
    }

    /**
     * =========================================================================
     * Edge
     * =========================================================================
     */
    public function edge(
        string $id
    ): ?GraphEdge
    {
        return $this->edges[$id] ?? null;
    }

    /**
     * =========================================================================
     * Nodes
     * =========================================================================
     *
     * @return array<string,GraphNode>
     */
    public function nodes(): array
    {
        return $this->nodes;
    }

    /**
     * =========================================================================
     * Edges
     * =========================================================================
     *
     * @return array<string,GraphEdge>
     */
    public function edges(): array
    {
        return $this->edges;
    }

    /**
     * =========================================================================
     * Has Node
     * =========================================================================
     */
    public function hasNode(
        string $id
    ): bool
    {
        return isset($this->nodes[$id]);
    }

    /**
     * =========================================================================
     * Has Edge
     * =========================================================================
     */
    public function hasEdge(
        string $id
    ): bool
    {
        return isset($this->edges[$id]);
    }

    /**
     * =========================================================================
     * Outgoing
     * =========================================================================
     *
     * @return array<int,GraphEdge>
     */
    public function outgoing(
        string $node
    ): array
    {
        return array_values(

            array_filter(

                array_map(

                    fn (string $id) => $this->edge($id),

                    $this->outgoing[$node] ?? []

                )

            )

        );
    }

    /**
     * =========================================================================
     * Incoming
     * =========================================================================
     *
     * @return array<int,GraphEdge>
     */
    public function incoming(
        string $node
    ): array
    {
        return array_values(

            array_filter(

                array_map(

                    fn (string $id) => $this->edge($id),

                    $this->incoming[$node] ?? []

                )

            )

        );
    }

    /**
     * =========================================================================
     * Neighbors
     * =========================================================================
     *
     * @return array<int,GraphNode>
     */
    public function neighbors(
        string $node
    ): array
    {
        $neighbors = [];

        foreach ($this->outgoing($node) as $edge) {

            $target = $this->node(
                $edge->target()
            );

            if ($target !== null) {

                $neighbors[] = $target;

            }
        }

        return $neighbors;
    }

    /**
     * =========================================================================
     * Degree
     * =========================================================================
     */
    public function degree(
        string $node
    ): int
    {
        return count(
            $this->incoming($node)
        ) + count(
            $this->outgoing($node)
        );
    }

    /**
     * =========================================================================
     * Node Count
     * =========================================================================
     */
    public function nodeCount(): int
    {
        return count($this->nodes);
    }

    /**
     * =========================================================================
     * Edge Count
     * =========================================================================
     */
    public function edgeCount(): int
    {
        return count($this->edges);
    }

    /**
     * =========================================================================
     * Is Empty
     * =========================================================================
     */
    public function isEmpty(): bool
    {
        return $this->nodeCount() === 0;
    }

    /**
     * =========================================================================
     * Clear
     * =========================================================================
     */
    public function clear(): void
    {
        $this->nodes = [];
        $this->edges = [];
        $this->incoming = [];
        $this->outgoing = [];
        $this->adjacency = [];
    }

    /**
     * =========================================================================
     * Statistics
     * =========================================================================
     *
     * @return array<string,int>
     */
    public function statistics(): array
    {
        return [

            'nodes' => $this->nodeCount(),

            'edges' => $this->edgeCount(),

        ];
    }

    /**
     * =========================================================================
     * To Array
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return [

            'nodes' => array_map(

                static fn (
                    GraphNode $node
                ) => $node->toArray(),

                $this->nodes

            ),

            'edges' => array_map(

                static fn (
                    GraphEdge $edge
                ) => $edge->toArray(),

                $this->edges

            ),

        ];
    }
/**
 * =========================================================================
 * Incoming Edges (Backward Compatibility)
 * =========================================================================
 *
 * @return array<int,GraphEdge>
 */
public function incomingEdges(
    string $node
): array
{
    return $this->incoming($node);
}

/**
 * =========================================================================
 * Outgoing Edges (Backward Compatibility)
 * =========================================================================
 *
 * @return array<int,GraphEdge>
 */
public function outgoingEdges(
    string $node
): array
{
    return $this->outgoing($node);
}

    }