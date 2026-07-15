<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph\Analysis;

use App\Services\MasterData\KnowledgeGraph\Repository\GraphRepository;
use App\Services\MasterData\KnowledgeGraph\GraphTraversal;
use Illuminate\Support\Collection;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Connectivity Analyzer
 * ==========================================================================
 *
 * Measures graph connectivity.
 *
 * Responsibilities
 * ----------------
 * - Connected nodes
 * - Isolated nodes
 * - Reachability
 * - Connectivity ratio
 * - Connectivity report
 *
 * Future
 * ------
 * - Connected Components
 * - Strongly Connected Components
 * - Weakly Connected Components
 *
 * ==========================================================================
 */
final class ConnectivityAnalyzer
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
     * Analyze
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    public function analyze(): array
    {
        return [

            'connectivity_ratio' => $this->connectivityRatio(),

            'isolated_nodes' => $this->isolatedNodes(),

            'connected_nodes' => $this->connectedNodes(),

            'reachability' => $this->reachability(),

        ];
    }

    /**
     * =========================================================================
     * Connected Nodes
     * =========================================================================
     *
     * @return Collection<int,array<string,mixed>>
     */
    public function connectedNodes(): Collection
    {
        return $this->repository

            ->nodes()

            ->filter(function ($node) {

                return

                    $this->repository
                        ->incomingEdges(
                            $node->id()
                        )
                        ->isNotEmpty()

                    ||

                    $this->repository
                        ->outgoingEdges(
                            $node->id()
                        )
                        ->isNotEmpty();

            })

            ->map(fn ($node) => [

                'id' => $node->id(),

                'label' => $node->label(),

                'schema' => $node->schema(),

            ])

            ->values();
    }

    /**
     * =========================================================================
     * Isolated Nodes
     * =========================================================================
     *
     * @return Collection<int,array<string,mixed>>
     */
    public function isolatedNodes(): Collection
    {
        return $this->repository

            ->nodes()

            ->filter(function ($node) {

                return

                    $this->repository
                        ->incomingEdges(
                            $node->id()
                        )
                        ->isEmpty()

                    &&

                    $this->repository
                        ->outgoingEdges(
                            $node->id()
                        )
                        ->isEmpty();

            })

            ->map(fn ($node) => [

                'id' => $node->id(),

                'label' => $node->label(),

                'schema' => $node->schema(),

            ])

            ->values();
    }

    /**
     * =========================================================================
     * Reachability
     * =========================================================================
     *
     * @return Collection<int,array<string,mixed>>
     */
    public function reachability(): Collection
    {
        return $this->repository

            ->nodes()

            ->map(function ($node) {

                $reachable =

                    $this->traversal
                        ->reachable(
                            $node->id()
                        )
                        ->count();

                return [

                    'id' => $node->id(),

                    'label' => $node->label(),

                    'reachable' => $reachable,

                ];

            })

            ->sortByDesc('reachable')

            ->values();
    }

    /**
     * =========================================================================
     * Connectivity Ratio
     * =========================================================================
     */
    public function connectivityRatio(): float
    {
        $total = $this->repository
            ->nodeCount();

        if ($total === 0) {

            return 0.0;

        }

        return round(

            $this->connectedNodes()->count()

            /

            $total,

            4

        );
    }

    /**
     * =========================================================================
     * Is Fully Connected
     * =========================================================================
     */
    public function isFullyConnected(): bool
    {
        return $this->isolatedNodes()
            ->isEmpty();
    }

    /**
     * =========================================================================
     * Connectivity Score
     * =========================================================================
     *
     * Returns percentage.
     */
    public function score(): float
    {
        return round(

            $this->connectivityRatio()

            * 100,

            2

        );
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

            'ratio' =>

                $this->connectivityRatio(),

            'score' =>

                $this->score(),

            'connected_nodes' =>

                $this->connectedNodes()

                    ->all(),

            'isolated_nodes' =>

                $this->isolatedNodes()

                    ->all(),

            'reachability' =>

                $this->reachability()

                    ->all(),

        ];
    }
}