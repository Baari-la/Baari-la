<?php

declare(strict_types=1);

namespace App\Services\Knowledge;

use App\Models\Company;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Graph Builder
 * ==========================================================================
 *
 * Responsible for constructing the Textile Knowledge Graph.
 *
 * Responsibilities
 * ----------------
 * • Build Knowledge Nodes
 * • Build Knowledge Edges
 * • Build Knowledge Paths
 * • Produce KnowledgeResult
 *
 * Used by
 * --------
 * • KnowledgeGraphService
 * • Executive AI
 * • Recommendation Engine
 * • Company Intelligence
 *
 */

class GraphBuilder
{
    /**
     * Result object.
     */
    protected KnowledgeResult $result;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->result = new KnowledgeResult();
    }

    /**
     * ==============================================================
     * Start Graph
     * ==============================================================
     */
    public function build(Company $company): KnowledgeResult
    {
        $this->buildCompanyNode($company);

        return $this->result;
    }

    /**
     * ==============================================================
     * Company Node
     * ==============================================================
     */
    protected function buildCompanyNode(
        Company $company
    ): void {

        $node = new KnowledgeNode(

            id: $company->id,

            type: 'company',

            label: $company->nama_perusahaan,

            attributes: [

                'slug' => $company->slug,

                'membership' => $company->membership_type,

                'category' => $company->category,

            ]

        );

        $this->result->addNode($node);
    }

    /**
     * ==============================================================
     * Add Node
     * ==============================================================
     */
    public function addNode(
        KnowledgeNode $node
    ): self {

        $this->result->addNode($node);

        return $this;
    }

    /**
     * ==============================================================
     * Add Edge
     * ==============================================================
     */
    public function addEdge(
        KnowledgeEdge $edge
    ): self {

        $this->result->addEdge($edge);

        return $this;
    }

    /**
     * ==============================================================
     * Add Path
     * ==============================================================
     */
    public function addPath(
        KnowledgePath $path
    ): self {

        $this->result->addPath($path);

        return $this;
    }

    /**
     * ==============================================================
     * Rules
     * ==============================================================
     */
    public function rules(
        array $rules
    ): self {

        $this->result->setRules($rules);

        return $this;
    }

    /**
     * ==============================================================
     * Evaluation
     * ==============================================================
     */
    public function evaluation(
        array $evaluation
    ): self {

        $this->result->setEvaluation($evaluation);

        return $this;
    }

    /**
     * ==============================================================
     * Scores
     * ==============================================================
     */
    public function scores(
        array $scores
    ): self {

        $this->result->setScores($scores);

        return $this;
    }

    /**
     * ==============================================================
     * Recommendation
     * ==============================================================
     */
    public function recommendations(
        array $recommendations
    ): self {

        $this->result->setRecommendations($recommendations);

        return $this;
    }

    /**
     * ==============================================================
     * Summary
     * ==============================================================
     */
    public function summary(
        array $summary
    ): self {

        $this->result->setSummary($summary);

        return $this;
    }

    /**
     * ==============================================================
     * Metadata
     * ==============================================================
     */
    public function metadata(
        array $metadata
    ): self {

        $this->result->setMetadata($metadata);

        return $this;
    }

    /**
     * ==============================================================
     * Result
     * ==============================================================
     */
    public function result(): KnowledgeResult
    {
        return $this->result;
    }
}