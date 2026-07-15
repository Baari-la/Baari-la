<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph\Model;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Graph Validation Result
 * ==========================================================================
 *
 * Immutable validation result for the Knowledge Graph.
 *
 * ==========================================================================
 */
final class GraphValidationResult
{
    /**
     * Constructor.
     *
     * @param array<int,string>              $errors
     * @param array<int,string>              $warnings
     * @param array<int,string>              $missingTargets
     * @param array<int,string>              $duplicateNodes
     * @param array<int,string>              $duplicateEdges
     * @param array<int,string>              $orphanNodes
     * @param array<int,string>              $cycles
     * @param array<string,int|float|string> $statistics
     */
    public function __construct(

        protected bool $valid,

        protected array $errors = [],

        protected array $warnings = [],

        protected array $missingTargets = [],

        protected array $duplicateNodes = [],

        protected array $duplicateEdges = [],

        protected array $orphanNodes = [],

        protected array $cycles = [],

        protected array $statistics = [],

    ) {
    }

    /**
     * =========================================================================
     * Valid
     * =========================================================================
     */
    public function isValid(): bool
    {
        return $this->valid;
    }

    /**
     * =========================================================================
     * Errors
     * =========================================================================
     *
     * @return array<int,string>
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * =========================================================================
     * Warnings
     * =========================================================================
     *
     * @return array<int,string>
     */
    public function warnings(): array
    {
        return $this->warnings;
    }

    /**
     * =========================================================================
     * Missing Targets
     * =========================================================================
     *
     * @return array<int,string>
     */
    public function missingTargets(): array
   {
        return $this->missingTargets;
    }

    /**
     * =========================================================================
     * Duplicate Nodes
     * =========================================================================
     *
     * @return array<int,string>
     */
    public function duplicateNodes(): array
    {
        return $this->duplicateNodes;
    }

    /**
     * =========================================================================
     * Duplicate Edges
     * =========================================================================
     *
     * @return array<int,string>
     */
    public function duplicateEdges(): array
    {
        return $this->duplicateEdges;
    }

    /**
     * =========================================================================
     * Orphan Nodes
     * =========================================================================
     *
     * @return array<int,string>
     */
    public function orphanNodes(): array
    {
        return $this->orphanNodes;
    }

    /**
     * =========================================================================
     * Cycles
     * =========================================================================
     *
     * @return array<int,string>
     */
    public function cycles(): array
    {
        return $this->cycles;
    }

    /**
     * =========================================================================
     * Statistics
     * =========================================================================
     *
     * @return array<string,int|float|string>
     */
    public function statistics(): array
    {
        return $this->statistics;
    }

    /**
     * =========================================================================
     * Has Errors
     * =========================================================================
     */
    public function hasErrors(): bool
    {
        return ! empty($this->errors);
    }

    /**
     * =========================================================================
     * Has Warnings
     * =========================================================================
     */
    public function hasWarnings(): bool
    {
        return ! empty($this->warnings);
    }

    /**
     * =========================================================================
     * Error Count
     * =========================================================================
     */
    public function errorCount(): int
    {
        return count($this->errors);
    }

    /**
     * =========================================================================
     * Warning Count
     * =========================================================================
     */
    public function warningCount(): int
    {
        return count($this->warnings);
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
        return array_merge(

            [

                'valid' => $this->valid,

                'errors' => $this->errorCount(),

                'warnings' => $this->warningCount(),

                'missing_targets' => count($this->missingTargets),

                'duplicate_nodes' => count($this->duplicateNodes),

                'duplicate_edges' => count($this->duplicateEdges),

                'orphan_nodes' => count($this->orphanNodes),

                'cycles' => count($this->cycles),

            ],

            $this->statistics

        );
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

            'statistics' => $this->statistics,

            'errors' => $this->errors,

            'warnings' => $this->warnings,

            'missing_targets' => $this->missingTargets,

            'duplicate_nodes' => $this->duplicateNodes,

            'duplicate_edges' => $this->duplicateEdges,

            'orphan_nodes' => $this->orphanNodes,

            'cycles' => $this->cycles,

        ];
    }
}