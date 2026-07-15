<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph\Analysis;

use App\Services\MasterData\KnowledgeGraph\Repository\GraphRepository;
use Illuminate\Support\Collection;
use SplQueue;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Path Analyzer
 * ==========================================================================
 *
 * Finds paths inside the Knowledge Graph.
 *
 * Current Algorithms
 * ------------------
 * - Breadth First Search (Shortest Path)
 *
 * Future
 * ------
 * - Dijkstra
 * - A*
 * - Yen K Shortest Paths
 *
 * ==========================================================================
 */
final class PathAnalyzer
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
     * Analyze
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    public function analyze(
        string $from,
        string $to
    ): array
    {
        $path = $this->shortestPath(
            $from,
            $to
        );

        return [

            'found' => $path->isNotEmpty(),

            'distance' => max(
                0,
                $path->count() - 1
            ),

            'path' => $path,

        ];
    }

    /**
     * =========================================================================
     * Shortest Path
     * =========================================================================
     *
     * Uses BFS.
     *
     * @return Collection<int,string>
     */
    public function shortestPath(
        string $source,
        string $target
    ): Collection
    {
        if (
            ! $this->repository->hasNode($source)
            ||
            ! $this->repository->hasNode($target)
        ) {

            return collect();

        }

        if ($source === $target) {

            return collect([
                $source
            ]);

        }

        $queue = new SplQueue();

        $queue->enqueue($source);

        $visited = [

            $source => true,

        ];

        $previous = [];

        while (! $queue->isEmpty()) {

            $current = $queue->dequeue();

            foreach (

                $this->repository
                    ->neighbors($current)

                as $neighbor

            ) {

                $id = $neighbor->id();

                if (
                    isset($visited[$id])
                ) {
                    continue;
                }

                $visited[$id] = true;

                $previous[$id] = $current;

                if ($id === $target) {

                    return $this->reconstructPath(

                        $previous,

                        $source,

                        $target

                    );

                }

                $queue->enqueue($id);

            }

        }

        return collect();
    }

    /**
     * =========================================================================
     * Path Exists
     * =========================================================================
     */
    public function exists(
        string $source,
        string $target
    ): bool
    {
        return $this->shortestPath(
            $source,
            $target
        )->isNotEmpty();
    }

    /**
     * =========================================================================
     * Distance
     * =========================================================================
     */
    public function distance(
        string $source,
        string $target
    ): int
    {
        $path = $this->shortestPath(
            $source,
            $target
        );

        if ($path->isEmpty()) {

            return -1;

        }

        return $path->count() - 1;
    }

    /**
     * =========================================================================
     * Reconstruct Path
     * =========================================================================
     *
     * @param array<string,string> $previous
     *
     * @return Collection<int,string>
     */
    protected function reconstructPath(
        array $previous,
        string $source,
        string $target
    ): Collection
    {
        $path = [];

        $current = $target;

        while (true) {

            array_unshift(
                $path,
                $current
            );

            if ($current === $source) {
                break;
            }

            if (! isset($previous[$current])) {

                return collect();

            }

            $current = $previous[$current];

        }

        return collect($path);
    }

    /**
     * =========================================================================
     * All Reachable Paths
     * =========================================================================
     *
     * @return Collection<int,string>
     */
    public function reachable(
        string $source
    ): Collection
    {
        $reachable = [];

        foreach (

            $this->repository
                ->nodes()

            as $node

        ) {

            if (

                $this->exists(

                    $source,

                    $node->id()

                )

            ) {

                $reachable[] = $node->id();

            }

        }

        sort($reachable);

        return collect(
            $reachable
        );
    }

    /**
     * =========================================================================
     * Export
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    public function toArray(
        string $source,
        string $target
    ): array
    {
        return $this->analyze(
            $source,
            $target
        );
    }
}