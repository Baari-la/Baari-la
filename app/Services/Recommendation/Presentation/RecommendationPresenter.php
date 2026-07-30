<?php

declare(strict_types=1);

namespace App\Services\Recommendation\Presentation;

use App\Services\Recommendation\DTO\RecommendationResult;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Recommendation Presenter
 * ==========================================================================
 *
 * Converts internal DRIE recommendation output into a stable,
 * frontend-safe presentation contract.
 *
 * Responsibilities:
 *
 * - Hide internal engine implementation details
 * - Normalize recommendation output
 * - Expose business intelligence required by UI/API consumers
 * - Keep React independent from DRIE internal structures
 *
 * This service DOES NOT:
 *
 * - classify companies
 * - resolve relationships
 * - calculate scores
 * - calculate ranking
 * - generate reasons
 * - generate explanations
 *
 * Version:
 * 1.0
 */
class RecommendationPresenter
{
    /**
     * --------------------------------------------------------------------------
     * Present Recommendation Result
     * --------------------------------------------------------------------------
     */
    public function present(
        RecommendationResult $result
    ): array {

        return [
            'engine' => [
                'name' => $result->engine,
                'version' => $result->version,
                'generated_at' =>
                    $result->generatedAt->toISOString(),
            ],

            'source_company_id' =>
                $result->companyId,

            'statistics' =>
                $result->statistics(),

            'recommendations' =>
                collect($result->recommendations())
                    ->map(
                        fn (array $candidate): array =>
                            $this->presentCandidate($candidate)
                    )
                    ->values()
                    ->all(),
        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Present Candidate
     * --------------------------------------------------------------------------
     */
    public function presentCandidate(
        array $candidate
    ): array {

        return [
            /*
            |--------------------------------------------------------------------------
            | Identity
            |--------------------------------------------------------------------------
            */

            'rank' =>
                $candidate['rank'] ?? null,

            'company' => [
                'id' =>
                    $candidate['company_id'] ?? null,

                'name' =>
                    $candidate['company_name'] ?? null,

                'country' =>
                    $candidate['country'] ?? null,

                'city' =>
                    $candidate['city'] ?? null,

                'membership' =>
                    $candidate['membership'] ?? null,
            ],

            /*
            |--------------------------------------------------------------------------
            | Business Match
            |--------------------------------------------------------------------------
            */

            'match' => [
                'relationship' =>
                    $candidate['inverse_relationship']
                    ?? null,

                'source_relationship' =>
                    $candidate['business_relationship']
                    ?? null,

                'discovery_mode' =>
                    $candidate['discovery_mode']
                    ?? null,

                'semantic_type' =>
                    $candidate['semantic_type']
                    ?? null,

                'role' =>
                    $candidate['canonical_role']
                    ?? null,

                'specific_roles' =>
                    array_values(
                        $candidate['specific_roles']
                        ?? []
                    ),

                'relationship_confidence' =>
                    $this->number(
                        $candidate[
                            'relationship_confidence'
                        ] ?? null
                    ),
            ],

            /*
            |--------------------------------------------------------------------------
            | Supply Chain Intelligence
            |--------------------------------------------------------------------------
            */

            'supply_chain' => [
                'reachable' =>
                    (bool) (
                        $candidate[
                            'supply_chain_reachable'
                        ] ?? false
                    ),

                'depth' =>
                    $candidate[
                        'supply_chain_depth'
                    ] ?? null,

                'depth_class' =>
                    $candidate[
                        'supply_chain_depth_class'
                    ] ?? null,

                'direction' =>
                    $candidate[
                        'supply_chain_direction'
                    ] ?? null,

                'source_role' =>
                    $candidate[
                        'supply_chain_source_role'
                    ] ?? null,

                'target_role' =>
                    $candidate[
                        'supply_chain_target_role'
                    ] ?? null,

                'path' =>
                    array_values(
                        $candidate[
                            'supply_chain_path'
                        ] ?? []
                    ),
            ],

            /*
            |--------------------------------------------------------------------------
            | Scores
            |--------------------------------------------------------------------------
            */

            'scores' => [
                'compatibility' =>
                    $this->number(
                        $candidate[
                            'compatibility_score'
                        ] ?? null
                    ),

                'recommendation' =>
                    $this->number(
                        $candidate[
                            'recommendation_score'
                        ] ?? null
                    ),

                'confidence' =>
                    $this->number(
                        $candidate[
                            'confidence_score'
                        ] ?? null
                    ),

                'ranking' =>
                    $this->number(
                        $candidate[
                            'ranking_score'
                        ] ?? null
                    ),
            ],

            /*
            |--------------------------------------------------------------------------
            | Reasons
            |--------------------------------------------------------------------------
            */

            'reasons' =>
                array_values(
                    $candidate['reasons'] ?? []
                ),

            /*
            |--------------------------------------------------------------------------
            | Explanation
            |--------------------------------------------------------------------------
            */

            'explanation' =>
                $this->presentExplanation(
                    $candidate['explanation']
                    ?? []
                ),
        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Present Explanation
     * --------------------------------------------------------------------------
     */
    private function presentExplanation(
        array $explanation
    ): array {

        return [
            'headline' =>
                $explanation['headline'] ?? null,

            'summary' =>
                $explanation['summary'] ?? null,

            'relationship' =>
                $explanation['relationship'] ?? null,

            'evidence' =>
                $explanation['evidence'] ?? null,

            'confidence' =>
                $explanation['confidence'] ?? null,

            'strength' =>
                $explanation['strength'] ?? null,

            'narrative' =>
                $explanation['narrative'] ?? null,
        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Normalize Number
     * --------------------------------------------------------------------------
     */
    private function number(
        mixed $value
    ): ?float {

        if (! is_numeric($value)) {
            return null;
        }

        return round(
            (float) $value,
            2
        );
    }
}