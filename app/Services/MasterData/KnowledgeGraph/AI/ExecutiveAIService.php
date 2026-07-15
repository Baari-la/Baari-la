<?php

declare(strict_types=1);

namespace App\Services\MasterData\KnowledgeGraph\AI;

use App\Services\MasterData\KnowledgeGraph\AI\Insight\CompanyInsightEngine;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Executive AI Service
 * ==========================================================================
 *
 * Entry point for AI-generated executive analysis.
 *
 * Pipeline
 * --------
 *
 * Company
 *      ↓
 * Company Intelligence
 *      ↓
 * Knowledge Graph
 *      ↓
 * Company Insight
 *      ↓
 * Prompt
 *      ↓
 * AI Provider
 *
 * This class NEVER calculates business logic.
 * It only orchestrates AI generation.
 *
 * ==========================================================================
 */
final class ExecutiveAIService
{
    /**
     * Constructor.
     */
    public function __construct(
        protected CompanyInsightEngine $insightEngine,
    ) {
    }

    /**
     * =========================================================================
     * Generate Executive Report
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    public function generate(
        int $companyId,
        string $graphNode
    ): array
    {
        $insight = $this->insightEngine
            ->generate(
                $companyId,
                $graphNode
            );

        return [

            'prompt' =>

                $this->buildPrompt(
                    $insight
                ),

            'context' =>

                $insight,

            /*
            |--------------------------------------------------------------------------
            | AI Response
            |--------------------------------------------------------------------------
            |
            | Placeholder.
            | Future:
            | OpenAI
            | Azure OpenAI
            | Claude
            | Gemini
            | Local LLM
            |
            */

            'response' => null,

        ];
    }

    /**
     * =========================================================================
     * Build Prompt
     * =========================================================================
     */
    protected function buildPrompt(
        array $insight
    ): string
    {
        return implode("\n", [

            'You are a senior textile industry strategy consultant.',

            'Analyze the following company objectively.',

            'Generate:',

            '- Executive Summary',

            '- Strengths',

            '- Weaknesses',

            '- Opportunities',

            '- Risks',

            '- Technology Recommendations',

            '- Certification Recommendations',

            '- Market Recommendations',

            '- Strategic Actions',

            '',

            json_encode(

                $insight,

                JSON_PRETTY_PRINT
                | JSON_UNESCAPED_UNICODE

            ),

        ]);
    }

    /**
     * =========================================================================
     * Prompt Only
     * =========================================================================
     */
    public function prompt(
        int $companyId,
        string $graphNode
    ): string
    {
        return $this->generate(

            $companyId,

            $graphNode

        )['prompt'];
    }

    /**
     * =========================================================================
     * Insight Only
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    public function insight(
        int $companyId,
        string $graphNode
    ): array
    {
        return $this->insightEngine
            ->generate(
                $companyId,
                $graphNode
            );
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