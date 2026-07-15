<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph\Model;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Graph Path
 * ==========================================================================
 *
 * Represents one traversal path in the Knowledge Graph.
 *
 * Responsibilities
 * ----------------
 * - Store ordered node sequence
 * - Store traversed edges
 * - Calculate path metrics
 * - Export path information
 *
 * This class DOES NOT:
 *
 * - Traverse graphs
 * - Query repositories
 * * - Perform graph algorithms
 *
 * ==========================================================================
 */
final class GraphPath
{
    /**
     * Constructor.
     *
     * @param array<int,GraphNode> $nodes
     * @param array<int,GraphEdge> $edges
     */
    public function __construct(

        protected array $nodes = [],

        protected array $edges = [],

    ) {
    }

    /**
     * =========================================================================
     * Nodes
     * =========================================================================
     *
     * @return array<int,GraphNode>
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
     * @return array<int,GraphEdge>
     */
    public function edges(): array
    {
        return $this->edges;
    }

    /**
     * =========================================================================
     * First Node
     * =========================================================================
     */
    public function first(): ?GraphNode
    {
        return $this->nodes[0] ?? null;
    }

    /**
     * =========================================================================
     * Last Node
     * =========================================================================
     */
    public function last(): ?GraphNode
    {
        if ($this->nodes === []) {
            return null;
        }

        return $this->nodes[array_key_last($this->nodes)];
    }

    /**
     * =========================================================================
     * Length
     * =========================================================================
     *
     * Number of edges.
     */
    public function length(): int
    {
        return count($this->edges);
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
     * Total Weight
     * =========================================================================
     */
    public function weight(): float
    {
        $weight = 0.0;

        foreach ($this->edges as $edge) {

            $weight += $edge->weight();

        }

        return $weight;
    }

    /**
     * =========================================================================
     * Average Confidence
     * =========================================================================
     */
    public function confidence(): float
    {
        if ($this->edges === []) {
            return 100.0;
        }

        $total = 0.0;

        foreach ($this->edges as $edge) {

            $total += $edge->confidence();

        }

        return round(

            $total / count($this->edges),

            2

        );
    }

    /**
     * =========================================================================
     * Contains Node
     * =========================================================================
     */
    public function containsNode(
        string $nodeId
    ): bool
    {
        foreach ($this->nodes as $node) {

            if ($node->id() === $nodeId) {

                return true;

            }

        }

        return false;
    }

    /**
     * =========================================================================
     * Contains Edge
     * =========================================================================
     */
    public function containsEdge(
        string $edgeId
    ): bool
    {
        foreach ($this->edges as $edge) {

            if ($edge->id() === $edgeId) {

                return true;

            }

        }

        return false;
    }

    /**
     * =========================================================================
     * Is Empty
     * =========================================================================
     */
    public function isEmpty(): bool
    {
        return $this->nodes === [];
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

            'length' => $this->length(),

            'weight' => $this->weight(),

            'confidence' => $this->confidence(),

            'nodes' => array_map(

                static fn (GraphNode $node) => $node->toArray(),

                $this->nodes

            ),

            'edges' => array_map(

                static fn (GraphEdge $edge) => $edge->toArray(),

                $this->edges

            ),

        ];
    }
}