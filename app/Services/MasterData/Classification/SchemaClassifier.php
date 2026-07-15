<?php

declare(strict_types=1);

namespace App\Services\MasterData\Classification;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Schema Classifier
 * ==========================================================================
 *
 * Classifies Master Data schema using the Classification Engine.
 *
 * Pipeline
 * --------
 *
 * Analysis
 *      ↓
 * Scoring Engine
 *      ↓
 * Classification Result
 *
 * This class performs NO scoring.
 * This class contains NO hardcoded rules.
 *
 * ==========================================================================
 */
final class SchemaClassifier
{
    /**
     * Constructor.
     */
    public function __construct(
        protected ScoringEngine $engine
    ) {
    }

    /**
     * =========================================================================
     * Classify
     * =========================================================================
     *
     * @param array<string,mixed> $analysis
     */
    public function classify(
        array $analysis
    ): ClassificationResult
    {
        $fields = $analysis['fields'] ?? [];

        if (! is_array($fields)) {

            $fields = [];

        }

        $scores = $this->engine->score(
            $fields
        );

        return new ClassificationResult(

            type: $this->engine
                ->winningType($scores),

            winningScore: $this->engine
                ->highestScore($scores),

            confidence: $this->engine
                ->confidence($scores),

            scores: $scores

        );
    }

    /**
     * =========================================================================
     * Detect Type
     * =========================================================================
     *
     * Returns only the detected schema type.
     */
    public function detectType(
        array $analysis
    ): string
    {
        return $this
            ->classify($analysis)
            ->type();
    }

    /**
     * =========================================================================
     * Confidence
     * =========================================================================
     *
     * Returns confidence only.
     */
    public function confidence(
        array $analysis
    ): float
    {
        return $this
            ->classify($analysis)
            ->confidence();
    }

    /**
     * =========================================================================
     * Scores
     * =========================================================================
     *
     * @return array<string,int>
     */
    public function scores(
        array $analysis
    ): array
    {
        return $this
            ->classify($analysis)
            ->scores();
    }
}