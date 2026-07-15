<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph\Analysis;

use App\Services\MasterData\KnowledgeGraph\GraphQuery;
use App\Services\MasterData\KnowledgeGraph\Repository\GraphRepository;
use Illuminate\Support\Collection;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Graph Analyzer
 * ==========================================================================
 *
 * Performs high-level analysis of the Knowledge Graph.
 *
 * Responsibilities
 * ----------------
 * - Graph statistics
 * - Schema distribution
 * - Type distribution
 * - Connectivity analysis
 * - Density calculation
 *
 * This class NEVER modifies the graph.
 *
 * ==========================================================================
 */
final class GraphAnalyzer
{
    /**
     * Constructor.
     */
    public function __construct(
        protected GraphRepository $repository,
        protected GraphQuery $query,
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

            'summary' => $this->summary(),

            'schemas' => $this->schemaDistribution(),

            'types' => $this->typeDistribution(),

            'density' => $this->density(),

            'isolated_nodes' => $this->isolatedNodes(),

            'top_connected' => $this->topConnected(),

        ];
    }

    /**
     * =========================================================================
     * Summary
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    public function summary(): array
    {
        return [

            'nodes' => $this->repository->nodeCount(),

            'edges' => $this->repository->edgeCount(),

            'schemas' => count(
                $this->schemaDistribution()
            ),

            'types' => count(
                $this->typeDistribution()
            ),

        ];
    }

    /**
     * =========================================================================
     * Schema Distribution
     * =========================================================================
     *
     * @return array<string,int>
     */
    public function schemaDistribution(): array
    {
        $distribution = [];

        foreach (
            $this->repository->nodes()
            as $node
        ) {

            $schema = $node->schema();

            $distribution[$schema] ??= 0;

            $distribution[$schema]++;

        }

        ksort($distribution);

        return $distribution;
    }

    /**
     * =========================================================================
     * Type Distribution
     * =========================================================================
     *
     * @return array<string,int>
     */
    public function typeDistribution(): array
    {
        $distribution = [];

        foreach (
            $this->repository->nodes()
            as $node
        ) {

            $type = $node->type();

            $distribution[$type] ??= 0;

            $distribution[$type]++;

        }

        ksort($distribution);

        return $distribution;
    }

    /**
     * =========================================================================
     * Density
     * =========================================================================
     */
    public function density(): float
    {
        $nodes = $this->repository->nodeCount();

        if ($nodes <= 1) {

            return 0.0;

        }

        $maximum = $nodes * ($nodes - 1);

        return round(

            $this->repository->edgeCount()
            / $maximum,

            4

        );
    }

    /**
     * =========================================================================
     * Isolated Nodes
     * =========================================================================
     *
     * @return Collection<int,string>
     */
    public function isolatedNodes(): Collection
    {
        return $this->repository
            ->nodes()

            ->filter(function ($node) {

                return $this->repository
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

            ->map(fn ($node) => $node->id())

            ->values();
    }

    /**
     * =========================================================================
     * Top Connected Nodes
     * =========================================================================
     *
     * @return Collection<int,array<string,mixed>>
     */
    public function topConnected(
        int $limit = 10
    ): Collection
    {
        return $this->repository
            ->nodes()

            ->map(function ($node) {

                $degree =

                    $this->repository
                        ->incomingEdges(
                            $node->id()
                        )->count()

                    +

                    $this->repository
                        ->outgoingEdges(
                            $node->id()
                        )->count();

                return [

                    'id' => $node->id(),

                    'label' => $node->label(),

                    'schema' => $node->schema(),

                    'degree' => $degree,

                ];

            })

            ->sortByDesc('degree')

            ->take($limit)

            ->values();
    }
}