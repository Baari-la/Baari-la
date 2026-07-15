<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph\Recommendation;

use App\Services\MasterData\KnowledgeGraph\GraphNode;
use App\Services\MasterData\KnowledgeGraph\Repository\GraphRepository;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Similarity Engine
 * ==========================================================================
 *
 * Calculates similarity between two Knowledge Graph nodes.
 *
 * Responsibilities
 * ----------------
 * - Schema similarity
 * - Type similarity
 * - Neighbor similarity
 * - Property similarity
 * - Composite similarity score
 *
 * Future
 * ------
 * - Cosine Similarity
 * - Graph Embedding
 * - Node2Vec
 * - Semantic AI Similarity
 *
 * ==========================================================================
 */
final class SimilarityEngine
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
     * Similarity Score
     * =========================================================================
     *
     * Returns score (0-100).
     */
    public function score(
        GraphNode $source,
        GraphNode $candidate
    ): float
    {
        $score = 0.0;

        $score += $this->schemaSimilarity(
            $source,
            $candidate
        );

        $score += $this->typeSimilarity(
            $source,
            $candidate
        );

        $score += $this->neighborSimilarity(
            $source,
            $candidate
        );

        $score += $this->propertySimilarity(
            $source,
            $candidate
        );

        return round(
            min($score, 100),
            2
        );
    }

    /**
     * =========================================================================
     * Schema Similarity
     * =========================================================================
     */
    protected function schemaSimilarity(
        GraphNode $source,
        GraphNode $candidate
    ): float
    {
        return $source->schema() === $candidate->schema()
            ? 30.0
            : 0.0;
    }

    /**
     * =========================================================================
     * Type Similarity
     * =========================================================================
     */
    protected function typeSimilarity(
        GraphNode $source,
        GraphNode $candidate
    ): float
    {
        return $source->type() === $candidate->type()
            ? 20.0
            : 0.0;
    }

    /**
     * =========================================================================
     * Neighbor Similarity
     * =========================================================================
     */
    protected function neighborSimilarity(
        GraphNode $source,
        GraphNode $candidate
    ): float
    {
        $left = $this->repository
            ->neighbors($source->id())
            ->pluck('id')
            ->all();

        $right = $this->repository
            ->neighbors($candidate->id())
            ->pluck('id')
            ->all();

        if ($left === [] || $right === []) {
            return 0.0;
        }

        $shared = array_intersect(
            $left,
            $right
        );

        $union = array_unique(
            array_merge(
                $left,
                $right
            )
        );

        return round(
            count($shared)
            / max(count($union), 1)
            * 30,
            2
        );
    }

    /**
     * =========================================================================
     * Property Similarity
     * =========================================================================
     */
    protected function propertySimilarity(
        GraphNode $source,
        GraphNode $candidate
    ): float
    {
        $left = $source->properties();

        $right = $candidate->properties();

        $shared = 0;

        $total = count(
            array_unique(
                array_merge(
                    array_keys($left),
                    array_keys($right)
                )
            )
        );

        foreach ($left as $key => $value) {

            if (
                isset($right[$key]) &&
                $right[$key] === $value
            ) {
                $shared++;
            }

        }

        if ($total === 0) {
            return 0.0;
        }

        return round(
            ($shared / $total) * 20,
            2
        );
    }

    /**
     * =========================================================================
     * Compare
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    public function compare(
        GraphNode $source,
        GraphNode $candidate
    ): array
    {
        return [

            'score' => $this->score(
                $source,
                $candidate
            ),

            'schema' => $this->schemaSimilarity(
                $source,
                $candidate
            ),

            'type' => $this->typeSimilarity(
                $source,
                $candidate
            ),

            'neighbors' => $this->neighborSimilarity(
                $source,
                $candidate
            ),

            'properties' => $this->propertySimilarity(
                $source,
                $candidate
            ),

        ];
    }
}