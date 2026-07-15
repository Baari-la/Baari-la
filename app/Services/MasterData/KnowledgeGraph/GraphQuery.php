<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph;

use Illuminate\Support\Collection;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Graph Query
 * ==========================================================================
 *
 * High-level query API for Knowledge Graph.
 *
 * Responsibilities
 * ----------------
 * - Find nodes
 * - Search nodes
 * - Filter by schema
 * - Filter by type
 * - Query neighbors
 * - Query recommendations
 *
 * This class NEVER modifies the graph.
 *
 * ==========================================================================
 */
final class GraphQuery
{
    /**
     * Constructor.
     */
    public function __construct(
        protected GraphRepository $repository,
        protected GraphTraversal $traversal,
    ) {
    }

    /**
     * =========================================================================
     * Find Node
     * =========================================================================
     */
    public function find(
        string $id
    ): ?GraphNode
    {
        return $this->repository
            ->node($id);
    }

    /**
     * =========================================================================
     * Search Label
     * =========================================================================
     *
     * @return Collection<int,GraphNode>
     */
    public function search(
        string $keyword
    ): Collection
    {
        $keyword = mb_strtolower($keyword);

        return $this->repository
            ->nodes()

            ->filter(

                static function (
                    GraphNode $node
                ) use (
                    $keyword
                ): bool {

                    return str_contains(

                        mb_strtolower(
                            $node->label()
                        ),

                        $keyword

                    );

                }

            )

            ->values();
    }

    /**
     * =========================================================================
     * By Schema
     * =========================================================================
     *
     * @return Collection<int,GraphNode>
     */
    public function bySchema(
        string $schema
    ): Collection
    {
        return $this->repository
            ->bySchema($schema);
    }

    /**
     * =========================================================================
     * By Type
     * =========================================================================
     *
     * @return Collection<int,GraphNode>
     */
    public function byType(
        string $type
    ): Collection
    {
        return $this->repository
            ->byType($type);
    }

    /**
     * =========================================================================
     * Neighbors
     * =========================================================================
     *
     * @return Collection<int,GraphNode>
     */
    public function neighbors(
        string $nodeId
    ): Collection
    {
        return $this->repository
            ->neighbors($nodeId);
    }

    /**
     * =========================================================================
     * Upstream
     * =========================================================================
     *
     * @return Collection<int,GraphNode>
     */
    public function upstream(
        string $nodeId
    ): Collection
    {
        return $this->traversal
            ->upstream($nodeId);
    }

    /**
     * =========================================================================
     * Downstream
     * =========================================================================
     *
     * @return Collection<int,GraphNode>
     */
    public function downstream(
        string $nodeId
    ): Collection
    {
        return $this->traversal
            ->downstream($nodeId);
    }

    /**
     * =========================================================================
     * Reachable Nodes
     * =========================================================================
     *
     * @return Collection<int,GraphNode>
     */
    public function reachable(
        string $nodeId
    ): Collection
    {
        return $this->traversal
            ->reachable($nodeId);
    }

    /**
     * =========================================================================
     * Recommendations
     * =========================================================================
     *
     * Returns connected nodes excluding itself.
     *
     * @return Collection<int,GraphNode>
     */
    public function recommendations(
        string $nodeId,
        int $limit = 10
    ): Collection
    {
        return $this->reachable($nodeId)

            ->reject(

                static fn (
                    GraphNode $node
                ) =>

                    $node->id() === $nodeId

            )

            ->take($limit)

            ->values();
    }

    /**
     * =========================================================================
     * Similar Nodes
     * =========================================================================
     *
     * Returns nodes from the same schema.
     *
     * @return Collection<int,GraphNode>
     */
    public function similar(
        string $nodeId
    ): Collection
    {
        $node = $this->find($nodeId);

        if ($node === null) {

            return collect();

        }

        return $this->bySchema(
            $node->schema()
        )

        ->reject(

            static fn (
                GraphNode $item
            ) =>

                $item->id() === $nodeId

        )

        ->values();
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

            'nodes' => $this->repository
                ->nodeCount(),

            'edges' => $this->repository
                ->edgeCount(),

        ];
    }
}