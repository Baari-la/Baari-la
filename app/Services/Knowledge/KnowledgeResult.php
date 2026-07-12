<?php

declare(strict_types=1);

namespace App\Services\Knowledge;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Knowledge Result
 * ==========================================================================
 *
 * Standard output object for the Textile Knowledge Graph.
 *
 * Used by:
 *
 * • KnowledgeGraphService
 * • KnowledgeEvaluationService
 * • KnowledgeRecommendationService
 * • Executive AI
 *
 */

class KnowledgeResult
{
    /**
     * Graph nodes.
     *
     * @var KnowledgeNode[]
     */
    protected array $nodes = [];

    /**
     * Graph edges.
     *
     * @var KnowledgeEdge[]
     */
    protected array $edges = [];

    /**
     * Knowledge paths.
     *
     * @var KnowledgePath[]
     */
    protected array $paths = [];

    /**
     * Business rules.
     */
    protected array $rules = [];

    /**
     * Evaluation results.
     */
    protected array $evaluation = [];

    /**
     * Scores.
     */
    protected array $scores = [];

    /**
     * Recommendations.
     */
    protected array $recommendations = [];

    /**
     * Summary.
     */
    protected array $summary = [];

    /**
     * Metadata.
     */
    protected array $metadata = [];

    /*
    |--------------------------------------------------------------------------
    | Nodes
    |--------------------------------------------------------------------------
    */

    public function addNode(KnowledgeNode $node): self
    {
        $this->nodes[] = $node;

        return $this;
    }

    public function nodes(): array
    {
        return $this->nodes;
    }

    /*
    |--------------------------------------------------------------------------
    | Edges
    |--------------------------------------------------------------------------
    */

    public function addEdge(KnowledgeEdge $edge): self
    {
        $this->edges[] = $edge;

        return $this;
    }

    public function edges(): array
    {
        return $this->edges;
    }

    /*
    |--------------------------------------------------------------------------
    | Paths
    |--------------------------------------------------------------------------
    */

    public function addPath(KnowledgePath $path): self
    {
        $this->paths[] = $path;

        return $this;
    }

    public function paths(): array
    {
        return $this->paths;
    }

    /*
    |--------------------------------------------------------------------------
    | Rules
    |--------------------------------------------------------------------------
    */

    public function setRules(array $rules): self
    {
        $this->rules = $rules;

        return $this;
    }

    public function rules(): array
    {
        return $this->rules;
    }

    /*
    |--------------------------------------------------------------------------
    | Evaluation
    |--------------------------------------------------------------------------
    */

    public function setEvaluation(array $evaluation): self
    {
        $this->evaluation = $evaluation;

        return $this;
    }

    public function evaluation(): array
    {
        return $this->evaluation;
    }

    /*
    |--------------------------------------------------------------------------
    | Scores
    |--------------------------------------------------------------------------
    */

    public function setScores(array $scores): self
    {
        $this->scores = $scores;

        return $this;
    }

    public function scores(): array
    {
        return $this->scores;
    }

    /*
    |--------------------------------------------------------------------------
    | Recommendations
    |--------------------------------------------------------------------------
    */

    public function setRecommendations(array $recommendations): self
    {
        $this->recommendations = $recommendations;

        return $this;
    }

    public function recommendations(): array
    {
        return $this->recommendations;
    }

    /*
    |--------------------------------------------------------------------------
    | Summary
    |--------------------------------------------------------------------------
    */

    public function setSummary(array $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function summary(): array
    {
        return $this->summary;
    }

    /*
    |--------------------------------------------------------------------------
    | Metadata
    |--------------------------------------------------------------------------
    */

    public function setMetadata(array $metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }

    public function metadata(): array
    {
        return $this->metadata;
    }

    /*
    |--------------------------------------------------------------------------
    | Export
    |--------------------------------------------------------------------------
    */

    public function toArray(): array
    {
        return [

            'nodes' => array_map(
                fn (KnowledgeNode $node) => $node->toArray(),
                $this->nodes
            ),

            'edges' => array_map(
                fn (KnowledgeEdge $edge) => $edge->toArray(),
                $this->edges
            ),

            'paths' => array_map(
                fn (KnowledgePath $path) => $path->toArray(),
                $this->paths
            ),

            'rules' => $this->rules,

            'evaluation' => $this->evaluation,

            'scores' => $this->scores,

            'recommendations' => $this->recommendations,

            'summary' => $this->summary,

            'metadata' => $this->metadata,

        ];
    }
}