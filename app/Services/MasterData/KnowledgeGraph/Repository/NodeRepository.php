<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph\Repository;

use App\Services\MasterData\KnowledgeGraph\Model\GraphNode;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Node Repository
 * ==========================================================================
 *
 * Repository for GraphNode objects.
 *
 * Responsibilities
 * ----------------
 * - Store GraphNode
 * - Lookup GraphNode
 * - Remove GraphNode
 * - Export GraphNode
 *
 * This class DOES NOT:
 *
 * - Store GraphEdge
 * - Traverse graph
 * - Build graph
 * - Perform analytics
 *
 * ==========================================================================
 */
final class NodeRepository
{
    /**
     * Node storage.
     *
     * @var array<string,GraphNode>
     */
    protected array $nodes = [];

    /**
     * =========================================================================
     * Add
     * =========================================================================
     */
    public function add(
        GraphNode $node
    ): self
    {
        $this->nodes[$node->id()] = $node;

        return $this;
    }

    /**
     * =========================================================================
     * Get
     * =========================================================================
     */
    public function get(
        string $id
    ): ?GraphNode
    {
        return $this->nodes[$id] ?? null;
    }

    /**
     * =========================================================================
     * Has
     * =========================================================================
     */
    public function has(
        string $id
    ): bool
    {
        return isset($this->nodes[$id]);
    }

    /**
     * =========================================================================
     * Remove
     * =========================================================================
     */
    public function remove(
        string $id
    ): bool
    {
        if (! isset($this->nodes[$id])) {

            return false;

        }

        unset($this->nodes[$id]);

        return true;
    }

    /**
     * =========================================================================
     * All
     * =========================================================================
     *
     * @return array<string,GraphNode>
     */
    public function all(): array
    {
        ksort($this->nodes);

        return $this->nodes;
    }

    /**
     * =========================================================================
     * Values
     * =========================================================================
     *
     * @return array<int,GraphNode>
     */
    public function values(): array
    {
        return array_values(
            $this->all()
        );
    }

    /**
     * =========================================================================
     * IDs
     * =========================================================================
     *
     * @return array<int,string>
     */
    public function ids(): array
    {
        return array_keys(
            $this->all()
        );
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
        return empty(
            $this->nodes
        );
    }

    /**
     * =========================================================================
     * First
     * =========================================================================
     */
    public function first(): ?GraphNode
    {
        foreach ($this->nodes as $node) {

            return $node;

        }

        return null;
    }

    /**
     * =========================================================================
     * Filter
     * =========================================================================
     *
     * @return array<int,GraphNode>
     */
    public function filter(
        callable $callback
    ): array
    {
        return array_values(

            array_filter(

                $this->nodes,

                $callback

            )

        );
    }

    /**
     * =========================================================================
     * By Type
     * =========================================================================
     *
     * @return array<int,GraphNode>
     */
    public function byType(
        string $type
    ): array
    {
        return $this->filter(

            static fn (
                GraphNode $node
            ): bool =>

                $node->type() === $type

        );
    }

    /**
     * =========================================================================
     * Clear
     * =========================================================================
     */
    public function clear(): void
    {
        $this->nodes = [];
    }

    /**
     * =========================================================================
     * To Array
     * =========================================================================
     *
     * @return array<int,array<string,mixed>>
     */
    public function toArray(): array
    {
        return array_map(

            static fn (
                GraphNode $node
            ): array =>

                $node->toArray(),

            $this->values()

        );
    }
}