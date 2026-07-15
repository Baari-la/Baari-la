<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph\Analysis;

use App\Services\MasterData\KnowledgeGraph\GraphEdge;
use App\Services\MasterData\KnowledgeGraph\Repository\GraphRepository;
use Illuminate\Support\Collection;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Cycle Analyzer
 * ==========================================================================
 *
 * Detects cycles inside the Knowledge Graph.
 *
 * Current Algorithm
 * -----------------
 * - Depth First Search (DFS)
 *
 * Complexity
 * ----------
 * O(V + E)
 *
 * Future
 * ------
 * - Tarjan SCC
 * - Johnson Cycle Enumeration
 *
 * ==========================================================================
 */
final class CycleAnalyzer
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
    public function analyze(): array
    {
        return [

            'has_cycles' => $this->hasCycles(),

            'count' => $this->count(),

            'cycles' => $this->cycles(),

        ];
    }

    /**
     * =========================================================================
     * Detect Cycles
     * =========================================================================
     *
     * @return Collection<int,array<int,string>>
     */
    public function cycles(): Collection
    {
        $visited = [];

        $stack = [];

        $cycles = [];

        foreach ($this->repository->nodes() as $node) {

            if (
                isset($visited[$node->id()])
            ) {
                continue;
            }

            $this->visit(

                $node->id(),

                $visited,

                $stack,

                $cycles

            );

        }

        return collect($cycles);
    }

    /**
     * =========================================================================
     * DFS Visit
     * =========================================================================
     *
     * @param array<string,bool> $visited
     * @param array<string,bool> $stack
     * @param array<int,array<int,string>> $cycles
     */
    protected function visit(
        string $nodeId,
        array &$visited,
        array &$stack,
        array &$cycles
    ): void
    {
        $visited[$nodeId] = true;

        $stack[$nodeId] = true;

        foreach (

            $this->repository
                ->outgoingEdges($nodeId)

            as $edge

        ) {

            /** @var GraphEdge $edge */

            $target = $edge->target();

            if (! isset($visited[$target])) {

                $this->visit(

                    $target,

                    $visited,

                    $stack,

                    $cycles

                );

                continue;

            }

            if (isset($stack[$target])) {

                $cycles[] = array_keys($stack);

            }

        }

        unset($stack[$nodeId]);
    }

    /**
     * =========================================================================
     * Has Cycles
     * =========================================================================
     */
    public function hasCycles(): bool
    {
        return $this->cycles()

            ->isNotEmpty();
    }

    /**
     * =========================================================================
     * Cycle Count
     * =========================================================================
     */
    public function count(): int
    {
        return $this->cycles()

            ->count();
    }

    /**
     * =========================================================================
     * Nodes In Cycles
     * =========================================================================
     *
     * @return Collection<int,string>
     */
    public function nodes(): Collection
    {
        return $this->cycles()

            ->flatten()

            ->unique()

            ->values();
    }

    /**
     * =========================================================================
     * Export
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return [

            'has_cycles' =>

                $this->hasCycles(),

            'cycle_count' =>

                $this->count(),

            'cycle_nodes' =>

                $this->nodes()

                    ->all(),

            'cycles' =>

                $this->cycles()

                    ->all(),

        ];
    }
}