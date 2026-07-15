<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph\Model;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Graph Community
 * ==========================================================================
 *
 * Represents one community (cluster) inside the Knowledge Graph.
 *
 * Responsibilities
 * ----------------
 * - Store community members
 * - Store community metadata
 * - Calculate community metrics
 *
 * This class DOES NOT:
 *
 * - Detect communities
 * - Traverse graphs
 * - Query repositories
 *
 * ==========================================================================
 */
final class GraphCommunity
{
    /**
     * Constructor.
     *
     * @param array<int,GraphNode> $nodes
     * @param array<string,mixed> $metadata
     */
    public function __construct(

        protected string $id,

        protected string $label,

        protected array $nodes = [],

        protected float $confidence = 100.0,

        protected array $metadata = [],

    ) {
    }

    /**
     * =========================================================================
     * ID
     * =========================================================================
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * =========================================================================
     * Label
     * =========================================================================
     */
    public function label(): string
    {
        return $this->label;
    }

    /**
     * =========================================================================
     * Confidence
     * =========================================================================
     */
    public function confidence(): float
    {
        return $this->confidence;
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
     * Metadata
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    public function metadata(): array
    {
        return $this->metadata;
    }

    /**
     * =========================================================================
     * Metadata Value
     * =========================================================================
     *
     * @return mixed
     */
    public function meta(
        string $key,
        mixed $default = null
    ): mixed
    {
        return $this->metadata[$key] ?? $default;
    }

    /**
     * =========================================================================
     * Count
     * =========================================================================
     */
    public function count(): int
    {
        return count(
            $this->nodes
        );
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
     * Contains
     * =========================================================================
     */
    public function contains(
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
     * Find Node
     * =========================================================================
     */
    public function find(
        string $nodeId
    ): ?GraphNode
    {
        foreach ($this->nodes as $node) {

            if ($node->id() === $nodeId) {

                return $node;

            }

        }

        return null;
    }

    /**
     * =========================================================================
     * Node IDs
     * =========================================================================
     *
     * @return array<int,string>
     */
    public function nodeIds(): array
    {
        return array_map(

            static fn (
                GraphNode $node
            ): string => $node->id(),

            $this->nodes

        );
    }

    /**
     * =========================================================================
     * Average Degree
     * =========================================================================
     */
    public function averageDegree(): float
    {
        if ($this->nodes === []) {

            return 0.0;

        }

        $degree = 0;

        foreach ($this->nodes as $node) {

            $degree += $node->degree();

        }

        return round(

            $degree / count($this->nodes),

            2

        );
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

            'id' => $this->id,

            'label' => $this->label,

            'confidence' => $this->confidence,

            'size' => $this->count(),

            'average_degree' => $this->averageDegree(),

            'nodes' => array_map(

                static fn (
                    GraphNode $node
                ) => $node->toArray(),

                $this->nodes

            ),

            'metadata' => $this->metadata,

        ];
    }
}