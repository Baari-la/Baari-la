<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph\Analysis;

use App\Services\MasterData\KnowledgeGraph\Repository\GraphRepository;
use Illuminate\Support\Collection;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Centrality Analyzer
 * ==========================================================================
 *
 * Calculates node importance inside the Knowledge Graph.
 *
 * Current Algorithms
 * ------------------
 * - Degree Centrality
 *
 * Future
 * ------
 * - Betweenness Centrality
 * - Closeness Centrality
 * - Eigenvector Centrality
 * - PageRank
 *
 * This class NEVER modifies the graph.
 *
 * ==========================================================================
 */
final class CentralityAnalyzer
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
     * @return Collection<int,array<string,mixed>>
     */
    public function analyze(): Collection
    {
        return $this->degreeCentrality();
    }

    /**
     * =========================================================================
     * Degree Centrality
     * =========================================================================
     *
     * Degree = Incoming + Outgoing edges.
     *
     * @return Collection<int,array<string,mixed>>
     */
    public function degreeCentrality(): Collection
    {
        $totalNodes = max(
            1,
            $this->repository->nodeCount() - 1
        );

        return $this->repository
            ->nodes()

            ->map(function ($node) use ($totalNodes) {

                $incoming = $this->repository
                    ->incomingEdges(
                        $node->id()
                    )
                    ->count();

                $outgoing = $this->repository
                    ->outgoingEdges(
                        $node->id()
                    )
                    ->count();

                $degree = $incoming + $outgoing;

                return [

                    'id' => $node->id(),

                    'label' => $node->label(),

                    'schema' => $node->schema(),

                    'type' => $node->type(),

                    'incoming' => $incoming,

                    'outgoing' => $outgoing,

                    'degree' => $degree,

                    /*
                    |--------------------------------------------------------------------------
                    | Normalized Degree Centrality
                    |--------------------------------------------------------------------------
                    |
                    | Range:
                    | 0.0 - 1.0
                    |
                    */

                    'score' => round(

                        $degree / $totalNodes,

                        4

                    ),

                ];

            })

            ->sortByDesc('score')

            ->values();
    }

    /**
     * =========================================================================
     * Top Nodes
     * =========================================================================
     *
     * @return Collection<int,array<string,mixed>>
     */
    public function top(
        int $limit = 10
    ): Collection
    {
        return $this->degreeCentrality()

            ->take($limit)

            ->values();
    }

    /**
     * =========================================================================
     * Centrality Score
     * =========================================================================
     */
    public function score(
        string $nodeId
    ): float
    {
        $node = $this->degreeCentrality()

            ->firstWhere(
                'id',
                $nodeId
            );

        if ($node === null) {

            return 0.0;

        }

        return (float) $node['score'];
    }

    /**
     * =========================================================================
     * Most Important Node
     * =========================================================================
     *
     * @return array<string,mixed>|null
     */
    public function mostImportant(): ?array
    {
        return $this->degreeCentrality()

            ->first();
    }

    /**
     * =========================================================================
     * Export
     * =========================================================================
     *
     * @return array<int,array<string,mixed>>
     */
    public function toArray(): array
    {
        return $this->degreeCentrality()

            ->all();
    }
}