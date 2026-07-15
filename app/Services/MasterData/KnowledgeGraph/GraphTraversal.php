<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph;

use Illuminate\Support\Collection;
use SplQueue;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Graph Traversal
 * ==========================================================================
 *
 * Performs graph traversal algorithms.
 *
 * Responsibilities
 * ----------------
 * - Breadth First Search (BFS)
 * - Depth First Search (DFS)
 * - Upstream traversal
 * - Downstream traversal
 * - Reachable nodes
 *
 * This class NEVER modifies the graph.
 *
 * ==========================================================================
 */
final class GraphTraversal
{
    /**
     * Constructor.
     */
    public function __construct(
        protected GraphRepository $repository
    ) {
    }

    /**
     * =========================================================================
     * Breadth First Search
     * =========================================================================
     *
     * @return Collection<int,GraphNode>
     */
    public function bfs(
        string $startNode
    ): Collection
    {
        if (! $this->repository->hasNode($startNode)) {
            return collect();
        }

        $visited = [];

        $queue = new SplQueue();

        $queue->enqueue($startNode);

        while (! $queue->isEmpty()) {

            $current = $queue->dequeue();

            if (isset($visited[$current])) {
                continue;
            }

            $visited[$current] = true;

            foreach (
                $this->repository
                    ->neighbors($current)
                as $neighbor
            ) {

                $queue->enqueue(
                    $neighbor->id()
                );

            }

        }

        return collect(array_keys($visited))

            ->map(fn (
                string $id
            ) => $this->repository->node($id))

            ->filter()

            ->values();
    }

    /**
     * =========================================================================
     * Depth First Search
     * =========================================================================
     *
     * @return Collection<int,GraphNode>
     */
    public function dfs(
        string $startNode
    ): Collection
    {
        $visited = [];

        $this->visit(
            $startNode,
            $visited
        );

        return collect(array_keys($visited))

            ->map(fn (
                string $id
            ) => $this->repository->node($id))

            ->filter()

            ->values();
    }

    /**
     * =========================================================================
     * Recursive Visit
     * =========================================================================
     *
     * @param array<string,bool> $visited
     */
    protected function visit(
        string $nodeId,
        array &$visited
    ): void
    {
        if (
            isset($visited[$nodeId])
        ) {
            return;
        }

        if (
            ! $this->repository->hasNode($nodeId)
        ) {
            return;
        }

        $visited[$nodeId] = true;

        foreach (
            $this->repository
                ->neighbors($nodeId)
            as $neighbor
        ) {

            $this->visit(
                $neighbor->id(),
                $visited
            );

        }
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
        return $this->repository
            ->outgoingEdges($nodeId)

            ->filter(fn (
                GraphEdge $edge
            ) =>

                in_array(

                    $edge->relation(),

                    [

                        'graph_edge',

                        'downstream',

                    ],

                    true

                ))

            ->map(fn (
                GraphEdge $edge
            ) =>

                $this->repository
                    ->node($edge->target()))

            ->filter()

            ->values();
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
        return $this->repository
            ->incomingEdges($nodeId)

            ->filter(fn (
                GraphEdge $edge
            ) =>

                in_array(

                    $edge->relation(),

                    [

                        'graph_edge',

                        'upstream',

                    ],

                    true

                ))

            ->map(fn (
                GraphEdge $edge
            ) =>

                $this->repository
                    ->node($edge->source()))

            ->filter()

            ->values();
    }

    /**
     * =========================================================================
     * Reachable Nodes
     * =========================================================================
     *
     * Alias of BFS.
     *
     * @return Collection<int,GraphNode>
     */
    public function reachable(
        string $nodeId
    ): Collection
    {
        return $this->bfs(
            $nodeId
        );
    }
}