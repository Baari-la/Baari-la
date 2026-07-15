<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph\Builder;

use App\Services\MasterData\KnowledgeGraph\Repository\GraphRepository;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Graph Builder Result
 * ==========================================================================
 *
 * Immutable result returned by KnowledgeGraphBuilder.
 *
 * Responsibilities
 * ----------------
 * - Expose built GraphRepository
 * - Provide build statistics
 * - Store build warnings
 * - Store build errors
 *
 * Used by:
 *
 * - GraphValidator
 * - GraphStatistics
 * - GenerateKnowledgeGraphCommand
 * - ValidateKnowledgeGraphCommand
 * - Executive AI
 *
 * ==========================================================================
 */
final class GraphBuilderResult
{
    /**
     * Constructor.
     *
     * @param array<int,string> $warnings
     * @param array<int,string> $errors
     */
    public function __construct(

        protected GraphRepository $repository,

        protected int $schemaCount,

        protected int $nodeCount,

        protected int $edgeCount,

        protected float $buildTime = 0.0,

        protected array $warnings = [],

        protected array $errors = [],

    ) {
    }

    /**
     * =========================================================================
     * Repository
     * =========================================================================
     */
    public function repository(): GraphRepository
    {
        return $this->repository;
    }

    /**
     * =========================================================================
     * Schema Count
     * =========================================================================
     */
    public function schemaCount(): int
    {
        return $this->schemaCount;
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
     * Build Time
     * =========================================================================
     */
    public function buildTime(): float
    {
        return $this->buildTime;
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
     * Has Warnings
     * =========================================================================
     */
    public function hasWarnings(): bool
    {
        return $this->warnings !== [];
    }

    /**
     * =========================================================================
     * Has Errors
     * =========================================================================
     */
    public function hasErrors(): bool
    {
        return $this->errors !== [];
    }

    /**
     * =========================================================================
     * Is Valid
     * =========================================================================
     */
    public function isValid(): bool
    {
        return ! $this->hasErrors();
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

            'schemas' => $this->schemaCount,

            'nodes' => $this->nodeCount,

            'edges' => $this->edgeCount,

            'build_time' => $this->buildTime,

            'warnings' => count($this->warnings),

            'errors' => count($this->errors),

            'valid' => $this->isValid(),

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

            'warnings' => $this->warnings,

            'errors' => $this->errors,

        ];
    }
}