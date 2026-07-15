<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph\Analysis;

use App\Services\MasterData\KnowledgeGraph\GraphEdge;
use App\Services\MasterData\KnowledgeGraph\GraphNode;
use App\Services\MasterData\KnowledgeGraph\Repository\GraphRepository;
use Illuminate\Support\Collection;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Dependency Analyzer
 * ==========================================================================
 *
 * Analyzes node dependencies inside the Knowledge Graph.
 *
 * Responsibilities
 * ----------------
 * - Direct dependencies
 * - Reverse dependencies
 * - Dependency count
 * - Most dependent nodes
 * - Dependency report
 *
 * This class NEVER modifies the graph.
 *
 * ==========================================================================
 */
final class DependencyAnalyzer
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

            'most_dependent' => $this->mostDependent(),

            'most_referenced' => $this->mostReferenced(),

            'dependency_matrix' => $this->dependencyMatrix(),

        ];
    }

    /**
     * =========================================================================
     * Direct Dependencies
     * =========================================================================
     *
     * Returns outgoing dependency nodes.
     *
     * @return Collection<int,GraphNode>
     */
    public function dependencies(
        string $nodeId
    ): Collection
    {
        return $this->repository

            ->outgoingEdges($nodeId)

            ->map(

                fn (GraphEdge $edge) =>

                    $this->repository
                        ->node($edge->target())

            )

            ->filter()

            ->values();
    }

    /**
     * =========================================================================
     * Reverse Dependencies
     * =========================================================================
     *
     * Returns nodes depending on this node.
     *
     * @return Collection<int,GraphNode>
     */
    public function dependents(
        string $nodeId
    ): Collection
    {
        return $this->repository

            ->incomingEdges($nodeId)

            ->map(

                fn (GraphEdge $edge) =>

                    $this->repository
                        ->node($edge->source())

            )

            ->filter()

            ->values();
    }

    /**
     * =========================================================================
     * Dependency Count
     * =========================================================================
     */
    public function dependencyCount(
        string $nodeId
    ): int
    {
        return $this->repository

            ->outgoingEdges($nodeId)

            ->count();
    }

    /**
     * =========================================================================
     * Dependent Count
     * =========================================================================
     */
    public function dependentCount(
        string $nodeId
    ): int
    {
        return $this->repository

            ->incomingEdges($nodeId)

            ->count();
    }

    /**
     * =========================================================================
     * Most Dependent Nodes
     * =========================================================================
     *
     * @return Collection<int,array<string,mixed>>
     */
    public function mostDependent(
        int $limit = 10
    ): Collection
    {
        return $this->repository

            ->nodes()

            ->map(function (GraphNode $node) {

                return [

                    'id' => $node->id(),

                    'label' => $node->label(),

                    'schema' => $node->schema(),

                    'dependencies' => $this->dependencyCount(
                        $node->id()
                    ),

                ];

            })

            ->sortByDesc('dependencies')

            ->take($limit)

            ->values();
    }

    /**
     * =========================================================================
     * Most Referenced Nodes
     * =========================================================================
     *
     * @return Collection<int,array<string,mixed>>
     */
    public function mostReferenced(
        int $limit = 10
    ): Collection
    {
        return $this->repository

            ->nodes()

            ->map(function (GraphNode $node) {

                return [

                    'id' => $node->id(),

                    'label' => $node->label(),

                    'schema' => $node->schema(),

                    'dependents' => $this->dependentCount(
                        $node->id()
                    ),

                ];

            })

            ->sortByDesc('dependents')

            ->take($limit)

            ->values();
    }

    /**
     * =========================================================================
     * Dependency Matrix
     * =========================================================================
     *
     * @return array<string,array<int,string>>
     */
    public function dependencyMatrix(): array
    {
        $matrix = [];

        foreach (
            $this->repository->nodes()
            as $node
        ) {

            $matrix[
                $node->id()
            ] = $this->dependencies(
                $node->id()
            )

            ->pluck('id')

            ->values()

            ->all();

        }

        ksort($matrix);

        return $matrix;
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

            'most_dependent' =>

                $this->mostDependent()

                    ->all(),

            'most_referenced' =>

                $this->mostReferenced()

                    ->all(),

            'dependency_matrix' =>

                $this->dependencyMatrix(),

        ];
    }
}