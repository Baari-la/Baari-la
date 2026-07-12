<?php

declare(strict_types=1);

namespace App\Services\Knowledge;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Knowledge Path
 * ==========================================================================
 *
 * Represents a reasoning path inside the Textile Knowledge Graph.
 *
 * Example:
 *
 * Company
 *    ↓
 * Product
 *    ↓
 * Technology
 *    ↓
 * Certification
 *    ↓
 * Export Market
 *
 * Used by:
 *
 * • KnowledgeGraphService
 * • KnowledgeEvaluationService
 * • KnowledgeRecommendationService
 * • Executive AI
 *
 */

class KnowledgePath
{
    /**
     * Path ID.
     */
    protected string $id;

    /**
     * Path name.
     */
    protected string $name;

    /**
     * Ordered node IDs.
     */
    protected array $nodes = [];

    /**
     * Ordered edges.
     *
     * @var KnowledgeEdge[]
     */
    protected array $edges = [];

    /**
     * Total path weight.
     */
    protected float $score = 0;

    /**
     * Optional metadata.
     */
    protected array $metadata = [];

    /**
     * Constructor.
     */
    public function __construct(
        string $id,
        string $name
    ) {
        $this->id = $id;

        $this->name = $name;
    }

    /**
     * Path ID.
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * Path name.
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Add node.
     */
    public function addNode(KnowledgeNode $node): self
    {
        $this->nodes[] = $node;

        return $this;
    }

    /**
     * Add edge.
     */
    public function addEdge(KnowledgeEdge $edge): self
    {
        $this->edges[] = $edge;

        $this->score += $edge->weight();

        return $this;
    }

    /**
     * Nodes.
     *
     * @return KnowledgeNode[]
     */
    public function nodes(): array
    {
        return $this->nodes;
    }

    /**
     * Edges.
     *
     * @return KnowledgeEdge[]
     */
    public function edges(): array
    {
        return $this->edges;
    }

    /**
     * Score.
     */
    public function score(): float
    {
        return round($this->score, 2);
    }

    /**
     * Metadata.
     */
    public function metadata(): array
    {
        return $this->metadata;
    }

    /**
     * Set metadata.
     */
    public function setMetadata(array $metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * Add metadata.
     */
    public function addMetadata(
        string $key,
        mixed $value
    ): self {

        $this->metadata[$key] = $value;

        return $this;
    }

    /**
     * Total nodes.
     */
    public function nodeCount(): int
    {
        return count($this->nodes);
    }

    /**
     * Total edges.
     */
    public function edgeCount(): int
    {
        return count($this->edges);
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return [

            'id' => $this->id,

            'name' => $this->name,

            'score' => $this->score(),

            'node_count' => $this->nodeCount(),

            'edge_count' => $this->edgeCount(),

            'nodes' => array_map(

                fn(KnowledgeNode $node)
                    => $node->toArray(),

                $this->nodes

            ),

            'edges' => array_map(

                fn(KnowledgeEdge $edge)
                    => $edge->toArray(),

                $this->edges

            ),

            'metadata' => $this->metadata,

        ];
    }
}