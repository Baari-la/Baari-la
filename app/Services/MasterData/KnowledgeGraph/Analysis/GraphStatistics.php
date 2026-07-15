<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph\Analysis;

use App\Services\MasterData\KnowledgeGraph\Repository\GraphRepository;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Graph Statistics
 * ==========================================================================
 *
 * Calculates basic statistics for the Knowledge Graph.
 *
 * Responsibilities
 * ----------------
 * - Node count
 * - Edge count
 * - Average degree
 * - Graph density
 * - Degree distribution
 * - Schema distribution
 * - Type distribution
 *
 * This class NEVER modifies the graph.
 *
 * ==========================================================================
 */
final class GraphStatistics
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
     * Summary
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    public function summary(): array
    {
        return [

            'nodes' => $this->nodeCount(),

            'edges' => $this->edgeCount(),

            'average_degree' => $this->averageDegree(),

            'density' => $this->density(),

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
     * Node Count
     * =========================================================================
     */
    public function nodeCount(): int
    {
        return $this->repository
            ->nodeCount();
    }

    /**
     * =========================================================================
     * Edge Count
     * =========================================================================
     */
    public function edgeCount(): int
    {
        return $this->repository
            ->edgeCount();
    }

    /**
     * =========================================================================
     * Average Degree
     * =========================================================================
     */
    public function averageDegree(): float
    {
        $nodes = $this->nodeCount();

        if ($nodes === 0) {

            return 0.0;

        }

        return round(

            (2 * $this->edgeCount())
            / $nodes,

            2

        );
    }

    /**
     * =========================================================================
     * Density
     * =========================================================================
     */
    public function density(): float
    {
        $nodes = $this->nodeCount();

        if ($nodes <= 1) {

            return 0.0;

        }

        return round(

            $this->edgeCount()

            /

            ($nodes * ($nodes - 1)),

            4

        );
    }

    /**
     * =========================================================================
     * Degree Distribution
     * =========================================================================
     *
     * @return array<string,int>
     */
    public function degreeDistribution(): array
    {
        $distribution = [];

        foreach (
            $this->repository->nodes()
            as $node
        ) {

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

            $distribution[
                (string) $degree
            ] ??= 0;

            $distribution[
                (string) $degree
            ]++;

        }

        ksort(
            $distribution,
            SORT_NUMERIC
        );

        return $distribution;
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
        $schemas = [];

        foreach (
            $this->repository->nodes()
            as $node
        ) {

            $schemas[
                $node->schema()
            ] ??= 0;

            $schemas[
                $node->schema()
            ]++;

        }

        ksort($schemas);

        return $schemas;
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
        $types = [];

        foreach (
            $this->repository->nodes()
            as $node
        ) {

            $types[
                $node->type()
            ] ??= 0;

            $types[
                $node->type()
            ]++;

        }

        ksort($types);

        return $types;
    }

    /**
     * =========================================================================
     * Maximum Degree
     * =========================================================================
     */
    public function maxDegree(): int
    {
        $max = 0;

        foreach (
            $this->repository->nodes()
            as $node
        ) {

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

            $max = max(
                $max,
                $degree
            );

        }

        return $max;
    }

    /**
     * =========================================================================
     * Minimum Degree
     * =========================================================================
     */
    public function minDegree(): int
    {
        if (
            $this->nodeCount() === 0
        ) {

            return 0;

        }

        $min = PHP_INT_MAX;

        foreach (
            $this->repository->nodes()
            as $node
        ) {

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

            $min = min(
                $min,
                $degree
            );

        }

        return $min;
    }

    /**
     * =========================================================================
     * Export Statistics
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return [

            'summary' => $this->summary(),

            'degree_distribution' =>

                $this->degreeDistribution(),

            'schema_distribution' =>

                $this->schemaDistribution(),

            'type_distribution' =>

                $this->typeDistribution(),

            'maximum_degree' =>

                $this->maxDegree(),

            'minimum_degree' =>

                $this->minDegree(),

        ];
    }
}