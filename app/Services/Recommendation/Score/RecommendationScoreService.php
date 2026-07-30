<?php

declare(strict_types=1);

namespace App\Services\Recommendation\Score;

use App\Models\Company;
use Illuminate\Support\Collection;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Recommendation Score Service
 * ==========================================================================
 *
 * Generates business recommendation score after compatibility evaluation.
 *
 * Score architecture:
 *
 * - Compatibility Score  : 60%
 * - Business Relevance   : 20%
 * - Data Strength        : 10%
 * - Market Strength      : 10%
 *
 * This service is deterministic and explainable.
 *
 * Future intelligence layers:
 *
 * - Buyer Intelligence
 * - Supply Chain Intelligence
 * - Trade Opportunity Intelligence
 * - AI Recommendation
 *
 * Version:
 * 2.0
 */
class RecommendationScoreService
{
    /**
     * --------------------------------------------------------------------------
     * Calculate Recommendation Score
     * --------------------------------------------------------------------------
     */
    public function calculate(
        Company $company,
        Collection $candidates,
        array $context = [],
    ): Collection {

        return $candidates
            ->map(function (array $candidate) use ($company, $context) {

                /*
                |--------------------------------------------------------------------------
                | Compatibility
                |--------------------------------------------------------------------------
                */

                $compatibilityScore = $this->normalizeScore(
                    $candidate['compatibility_score'] ?? 0
                );

                /*
                |--------------------------------------------------------------------------
                | Business Relevance
                |--------------------------------------------------------------------------
                |
                | Measures how strongly the candidate participates in a useful
                | business relationship with the source company.
                |
                */

                $businessRelevance =
                    $this->calculateBusinessRelevance($candidate);

                /*
                |--------------------------------------------------------------------------
                | Data Strength
                |--------------------------------------------------------------------------
                |
                | Measures how much usable business intelligence exists for the
                | candidate.
                |
                */

                $dataStrength =
                    $this->calculateDataStrength($candidate);

                /*
                |--------------------------------------------------------------------------
                | Market Strength
                |--------------------------------------------------------------------------
                |
                | Measures available market/export evidence.
                |
                */

                $marketStrength =
                    $this->calculateMarketStrength($candidate);

                /*
                |--------------------------------------------------------------------------
                | Weighted Recommendation Score
                |--------------------------------------------------------------------------
                */

                $score =
                    ($compatibilityScore * 0.60)
                    + ($businessRelevance * 0.20)
                    + ($dataStrength * 0.10)
                    + ($marketStrength * 0.10);

                /*
                |--------------------------------------------------------------------------
                | Eligibility Guard
                |--------------------------------------------------------------------------
                */

                if (! ($candidate['eligible'] ?? true)) {
                    $score = 0;
                }

                /*
                |--------------------------------------------------------------------------
                | Output
                |--------------------------------------------------------------------------
                */

                $candidate['recommendation_score'] =
                    round(min(100, max(0, $score)), 2);

                $candidate['recommendation_breakdown'] = [

                    'compatibility' => [
                        'raw' => $compatibilityScore,
                        'weight' => 60,
                        'weighted' => round(
                            $compatibilityScore * 0.60,
                            2
                        ),
                    ],

                    'business_relevance' => [
                        'raw' => $businessRelevance,
                        'weight' => 20,
                        'weighted' => round(
                            $businessRelevance * 0.20,
                            2
                        ),
                    ],

                    'data_strength' => [
                        'raw' => $dataStrength,
                        'weight' => 10,
                        'weighted' => round(
                            $dataStrength * 0.10,
                            2
                        ),
                    ],

                    'market_strength' => [
                        'raw' => $marketStrength,
                        'weight' => 10,
                        'weighted' => round(
                            $marketStrength * 0.10,
                            2
                        ),
                    ],
                ];

                return $candidate;

            })
            ->values();
    }

    /**
     * --------------------------------------------------------------------------
     * Business Relevance
     * --------------------------------------------------------------------------
     */
    protected function calculateBusinessRelevance(
        array $candidate
    ): float {

        $score = 0.0;

        /*
        |--------------------------------------------------------------------------
        | Valid Business Relationship
        |--------------------------------------------------------------------------
        */

        if (! empty($candidate['primary_relationship'])) {
            $score += 35;
        }

        /*
        |--------------------------------------------------------------------------
        | Semantic Relationship
        |--------------------------------------------------------------------------
        */

        $semanticType =
            $candidate['semantic_type'] ?? null;

        if ($semanticType === 'material_flow') {
            $score += 25;
        } elseif ($semanticType === 'solution_supply') {
            $score += 22;
        } elseif ($semanticType === 'technology_solution') {
            $score += 22;
        } elseif (! empty($semanticType)) {
            $score += 15;
        }

        /*
        |--------------------------------------------------------------------------
        | Relationship Confidence
        |--------------------------------------------------------------------------
        */

        $relationshipConfidence =
            (float) ($candidate['relationship_confidence'] ?? 0);

        $score += min(
            20,
            max(0, $relationshipConfidence * 20)
        );

        /*
        |--------------------------------------------------------------------------
        | Multiple Relationship Paths
        |--------------------------------------------------------------------------
        */

        $relationships =
            $candidate['business_relationships'] ?? [];

        $relationshipCount =
            is_countable($relationships)
                ? count($relationships)
                : 0;

        $score += min(
            10,
            $relationshipCount * 5
        );

        /*
        |--------------------------------------------------------------------------
        | Discovery Relevance
        |--------------------------------------------------------------------------
        */

        if (! empty($candidate['discovery_mode'])) {
            $score += 10;
        }

        return min(100, $score);
    }

    /**
     * --------------------------------------------------------------------------
     * Data Strength
     * --------------------------------------------------------------------------
     */
    protected function calculateDataStrength(
        array $candidate
    ): float {

        $score = 0.0;

        /*
        |--------------------------------------------------------------------------
        | Role Intelligence
        |--------------------------------------------------------------------------
        */

        if (! empty($candidate['canonical_role'])) {
            $score += 20;
        }

        if (! empty($candidate['specific_roles'])) {
            $score += 10;
        }

        if (! empty($candidate['role_evidence'])) {
            $score += 15;
        }

        /*
        |--------------------------------------------------------------------------
        | Product Intelligence
        |--------------------------------------------------------------------------
        */

        if ($this->hasItems($candidate['products'] ?? null)) {
            $score += 20;
        }

        /*
        |--------------------------------------------------------------------------
        | Market Intelligence
        |--------------------------------------------------------------------------
        */

        if ($this->hasItems($candidate['markets'] ?? null)) {
            $score += 15;
        }

        /*
        |--------------------------------------------------------------------------
        | Certification Intelligence
        |--------------------------------------------------------------------------
        */

        if ($this->hasItems($candidate['certifications'] ?? null)) {
            $score += 10;
        }

        /*
        |--------------------------------------------------------------------------
        | Manufacturing Intelligence
        |--------------------------------------------------------------------------
        */

        if (
            $this->hasItems($candidate['machines'] ?? null)
            || $this->hasItems($candidate['capacities'] ?? null)
        ) {
            $score += 10;
        }

        return min(100, $score);
    }

    /**
     * --------------------------------------------------------------------------
     * Market Strength
     * --------------------------------------------------------------------------
     */
    protected function calculateMarketStrength(
        array $candidate
    ): float {

        $markets =
            $candidate['markets'] ?? null;

        if (! $this->hasItems($markets)) {
            return 0.0;
        }

        $count =
            $markets instanceof Collection
                ? $markets->count()
                : (
                    is_countable($markets)
                        ? count($markets)
                        : 0
                );

        /*
        |--------------------------------------------------------------------------
        | Market Coverage Score
        |--------------------------------------------------------------------------
        |
        | 1 market   = 25
        | 2 markets  = 40
        | 3 markets  = 55
        | 4 markets  = 70
        | 5 markets  = 80
        | 6 markets  = 90
        | 7+ markets = 100
        |
        */

        return match (true) {

            $count >= 7 => 100.0,

            $count === 6 => 90.0,

            $count === 5 => 80.0,

            $count === 4 => 70.0,

            $count === 3 => 55.0,

            $count === 2 => 40.0,

            $count === 1 => 25.0,

            default => 0.0,
        };
    }

    /**
     * --------------------------------------------------------------------------
     * Has Items
     * --------------------------------------------------------------------------
     */
    protected function hasItems(
        mixed $value
    ): bool {

        if ($value instanceof Collection) {
            return $value->isNotEmpty();
        }

        if (is_array($value)) {
            return ! empty($value);
        }

        if ($value instanceof \Countable) {
            return count($value) > 0;
        }

        return ! empty($value);
    }

    /**
     * --------------------------------------------------------------------------
     * Normalize Score
     * --------------------------------------------------------------------------
     */
    protected function normalizeScore(
        mixed $score
    ): float {

        return min(
            100,
            max(
                0,
                (float) $score
            )
        );
    }
}