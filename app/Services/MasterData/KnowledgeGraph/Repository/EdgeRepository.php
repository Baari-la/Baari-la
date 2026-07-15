<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph\Repository;

use App\Services\MasterData\KnowledgeGraph\Model\GraphEdge;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Edge Repository
 * ==========================================================================
 *
 * Repository for GraphEdge objects.
 *
 * Responsibilities
 * ----------------
 * - Store GraphEdge
 * - Lookup GraphEdge
 * - Remove GraphEdge
 * - Export GraphEdge
 *
 * This class DOES NOT:
 *
 * - Store GraphNode
 * - Traverse graph
 * - Build graph
 * - Maintain adjacency index
 *
 * ==========================================================================
 */
final class EdgeRepository
{
    /**
     * Edge storage.
     *
     * @var array<string,GraphEdge>
     */
    protected array $edges = [];

    /**
     * =========================================================================
     * Add
     * =========================================================================
     */
    public function add(
        GraphEdge $edge
    ): self
    {
        $this->edges[$edge->id()] = $edge;

        return $this;
    }

    /**
     * =========================================================================
     * Get
     * =========================================================================
     */
    public function get(
        string $id
    ): ?GraphEdge
    {
        return $this->edges[$id] ?? null;
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
        return isset($this->edges[$id]);
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
        if (! isset($this->edges[$id])) {

            return false;

        }

        unset($this->edges[$id]);

        return true;
    }

    /**
     * =========================================================================
     * All
     * =========================================================================
     *
     * @return array<string,GraphEdge>
     */
    public function all(): array
    {
        ksort($this->edges);

        return $this->edges;
    }

    /**
     * =========================================================================
     * Values
     * =========================================================================
     *
     * @return array<int,GraphEdge>
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
            $this->edges
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
            $this->edges
        );
    }

    /**
     * =========================================================================
     * First
     * =========================================================================
     */
    public function first(): ?GraphEdge
    {
        foreach ($this->edges as $edge) {

            return $edge;

        }

        return null;
    }

    /**
     * =========================================================================
     * Filter
     * =========================================================================
     *
     * @param callable(GraphEdge):bool $callback
     *
     * @return array<int,GraphEdge>
     */
    public function filter(
        callable $callback
    ): array
    {
        return array_values(

            array_filter(

                $this->edges,

                $callback

            )

        );
    }

    /**
     * =========================================================================
     * By Relation
     * =========================================================================
     *
     * @return array<int,GraphEdge>
     */
    public function byRelation(
        string $relation
    ): array
    {
        return $this->filter(

            static fn (
                GraphEdge $edge
            ): bool =>

                $edge->relation() === $relation

        );
    }

    /**
     * =========================================================================
     * From Source
     * =========================================================================
     *
     * @return array<int,GraphEdge>
     */
    public function fromSource(
        string $source
    ): array
    {
        return $this->filter(

            static fn (
                GraphEdge $edge
            ): bool =>

                $edge->source() === $source

        );
    }

    /**
     * =========================================================================
     * To Target
     * =========================================================================
     *
     * @return array<int,GraphEdge>
     */
    public function toTarget(
        string $target
    ): array
    {
        return $this->filter(

            static fn (
                GraphEdge $edge
            ): bool =>

                $edge->target() === $target

        );
    }

    /**
     * =========================================================================
     * Self References
     * =========================================================================
     *
     * @return array<int,GraphEdge>
     */
    public function selfReferences(): array
    {
        return $this->filter(

            static fn (
                GraphEdge $edge
            ): bool =>

                $edge->isSelfReference()

        );
    }

    /**
     * =========================================================================
     * Clear
     * =========================================================================
     */
    public function clear(): void
    {
        $this->edges = [];
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
                GraphEdge $edge
            ): array =>

                $edge->toArray(),

            $this->values()

        );
    }
}