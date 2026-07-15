<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph\Model;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Graph Statistics Result
 * ==========================================================================
 *
 * Immutable value object representing Knowledge Graph statistics.
 *
 * Responsibilities
 * ----------------
 * - Store graph statistics
 * - Provide graph metrics
 * - Export statistics
 *
 * This class DOES NOT:
 *
 * - Analyze graphs
 * - Build graphs
 * - Traverse graphs
 *
 * ==========================================================================
 */
final class GraphStatisticsResult
{
    /**
     * Constructor.
     *
     * @param array<string,mixed> $metrics
     */
    public function __construct(

        protected int $nodeCount,

        protected int $edgeCount,

        protected int $communityCount = 0,

        protected int $connectedComponents = 0,

        protected int $orphanNodes = 0,

        protected int $cycleCount = 0,

        protected float $density = 0.0,

        protected float $averageDegree = 0.0,

        protected int $maxDegree = 0,

        protected int $minDegree = 0,

        protected array $metrics = [],

    ) {
    }

    /**
     * =========================================================================
     * Node Count
     * =========================================================================
     */
    public function nodeCount(): int
    {
        return $this->nodeCount;
    }

    /**
     * =========================================================================
     * Edge Count
     * =========================================================================
     */
    public function edgeCount(): int
    {
        return $this->edgeCount;
    }

    /**
     * =========================================================================
     * Community Count
     * =========================================================================
     */
    public function communityCount(): int
    {
        return $this->communityCount;
    }

    /**
     * =========================================================================
     * Connected Components
     * =========================================================================
     */
    public function connectedComponents(): int
    {
        return $this->connectedComponents;
    }

    /**
     * =========================================================================
     * Orphan Nodes
     * =========================================================================
     */
    public function orphanNodes(): int
    {
        return $this->orphanNodes;
    }

    /**
     * =========================================================================
     * Cycle Count
     * =========================================================================
     */
    public function cycleCount(): int
    {
        return $this->cycleCount;
    }

    /**
     * =========================================================================
     * Density
     * =========================================================================
     */
    public function density(): float
    {
        return $this->density;
    }

    /**
     * =========================================================================
     * Average Degree
     * =========================================================================
     */
    public function averageDegree(): float
    {
        return $this->averageDegree;
    }

    /**
     * =========================================================================
     * Maximum Degree
     * =========================================================================
     */
    public function maxDegree(): int
    {
        return $this->maxDegree;
    }

    /**
     * =========================================================================
     * Minimum Degree
     * =========================================================================
     */
    public function minDegree(): int
    {
        return $this->minDegree;
    }

    /**
     * =========================================================================
     * Metrics
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    public function metrics(): array
    {
        return $this->metrics;
    }

    /**
     * =========================================================================
     * Metric
     * =========================================================================
     *
     * @return mixed
     */
    public function metric(
        string $key,
        mixed $default = null
    ): mixed
    {
        return $this->metrics[$key] ?? $default;
    }

    /**
     * =========================================================================
     * Has Cycles
     * =========================================================================
     */
    public function hasCycles(): bool
    {
        return $this->cycleCount > 0;
    }

    /**
     * =========================================================================
     * Has Orphans
     * =========================================================================
     */
    public function hasOrphans(): bool
    {
        return $this->orphanNodes > 0;
    }

    /**
     * =========================================================================
     * Is Connected
     * =========================================================================
     */
    public function isConnected(): bool
    {
        return $this->connectedComponents <= 1;
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

            'nodes' => $this->nodeCount,

            'edges' => $this->edgeCount,

            'communities' => $this->communityCount,

            'components' => $this->connectedComponents,

            'orphans' => $this->orphanNodes,

            'cycles' => $this->cycleCount,

            'density' => $this->density,

            'average_degree' => $this->averageDegree,

            'max_degree' => $this->maxDegree,

            'min_degree' => $this->minDegree,

        ];
    }

    /**
     * =========================================================================
     * To Array
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return [

            'summary' => $this->summary(),

            'metrics' => $this->metrics,

        ];
    }
}