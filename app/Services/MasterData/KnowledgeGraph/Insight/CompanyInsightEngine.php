<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph\AI\Insight;

use App\Services\Company\Intelligence\CompanyIntelligenceOrchestrator;
use App\Services\MasterData\KnowledgeGraph\AI\Context\KnowledgeGraphContextBuilder;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Company Insight Engine
 * ==========================================================================
 *
 * Generates deterministic business insights by combining:
 *
 * • Company Intelligence
 * • Knowledge Graph Context
 *
 * This class NEVER calls any AI model.
 *
 * ExecutiveAIService consumes the output generated here.
 *
 * ==========================================================================
 */
final class CompanyInsightEngine
{
    /**
     * Constructor.
     */
    public function __construct(

        protected CompanyIntelligenceOrchestrator $intelligence,

        protected KnowledgeGraphContextBuilder $contextBuilder,

    ) {
    }

    /**
     * =========================================================================
     * Generate Insight
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    public function generate(
        int $companyId,
        string $graphNode
    ): array
    {
        $company =

            $this->intelligence
                ->generate($companyId);

        $context =

            $this->contextBuilder
                ->build($graphNode);

        return [

            'company' => $company,

            'context' => $context,

            'summary' =>

                $this->summary(
                    $company,
                    $context
                ),

            'strengths' =>

                $this->strengths(
                    $company,
                    $context
                ),

            'weaknesses' =>

                $this->weaknesses(
                    $company,
                    $context
                ),

            'opportunities' =>

                $this->opportunities(
                    $company,
                    $context
                ),

            'risks' =>

                $this->risks(
                    $company,
                    $context
                ),

            'recommendations' =>

                $this->recommendations(
                    $context
                ),

        ];
    }

    /**
     * =========================================================================
     * Executive Summary
     * =========================================================================
     */
    protected function summary(
        array $company,
        array $context
    ): array
    {
        return [

            'overall_score' =>

                $company['score']['overall']
                    ?? null,

            'graph_node' =>

                $context['node']['label']
                    ?? null,

            'business_role' =>

                $company['capability']['business_role']
                    ?? null,

        ];
    }

    /**
     * =========================================================================
     * Strengths
     * =========================================================================
     *
     * @return array<int,string>
     */
    protected function strengths(
        array $company,
        array $context
    ): array
    {
        return [];
    }

    /**
     * =========================================================================
     * Weaknesses
     * =========================================================================
     *
     * @return array<int,string>
     */
    protected function weaknesses(
        array $company,
        array $context
    ): array
    {
        return [];
    }

    /**
     * =========================================================================
     * Opportunities
     * =========================================================================
     *
     * @return array<int,string>
     */
    protected function opportunities(
        array $company,
        array $context
    ): array
    {
        return [];
    }

    /**
     * =========================================================================
     * Risks
     * =========================================================================
     *
     * @return array<int,string>
     */
    protected function risks(
        array $company,
        array $context
    ): array
    {
        return [];
    }

    /**
     * =========================================================================
     * Recommendations
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    protected function recommendations(
        array $context
    ): array
    {
        return $context['recommendations']
            ?? [];
    }

    /**
     * =========================================================================
     * Export
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    public function toArray(
        int $companyId,
        string $graphNode
    ): array
    {
        return $this->generate(
            $companyId,
            $graphNode
        );
    }
}