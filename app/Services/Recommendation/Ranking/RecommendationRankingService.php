<?php

declare(strict_types=1);

namespace App\Services\Recommendation\Ranking;

use Illuminate\Support\Collection;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Recommendation Ranking Service
 * ==========================================================================
 *
 * Produces the final ranking score for recommendation candidates.
 *
 * Ranking combines:
 *
 * - Recommendation quality
 * - Confidence strength
 * - Compatibility strength
 *
 * The recommendation score remains dominant because ranking should primarily
 * reflect business relevance.
 *
 * Confidence represents evidence quality.
 *
 * Compatibility reinforces structural business and ecosystem relevance.
 *
 * Version:
 * 1.2
 */
class RecommendationRankingService
{
    /**
     * --------------------------------------------------------------------------
     * Ranking Weights
     * --------------------------------------------------------------------------
     */

    private const RECOMMENDATION_WEIGHT = 0.70;

    private const CONFIDENCE_WEIGHT = 0.20;

    private const COMPATIBILITY_WEIGHT = 0.10;

    /**
     * --------------------------------------------------------------------------
     * Rank Recommendations
     * --------------------------------------------------------------------------
     */
    public function rank(
        Collection $candidates
    ): Collection {

        return $candidates

            ->map(function (array $candidate): array {

                /*
                |--------------------------------------------------------------------------
                | Input Scores
                |--------------------------------------------------------------------------
                */

                $recommendation = $this->normalizeScore(
                    $candidate['recommendation_score'] ?? 0
                );

                $confidence = $this->normalizeScore(
                    $candidate['confidence_score'] ?? 0
                );

                $compatibility = $this->normalizeScore(
                    $candidate['compatibility_score'] ?? 0
                );

                /*
                |--------------------------------------------------------------------------
                | Weighted Components
                |--------------------------------------------------------------------------
                */

                $recommendationWeighted = round(
                    $recommendation
                    * self::RECOMMENDATION_WEIGHT,
                    2
                );

                $confidenceWeighted = round(
                    $confidence
                    * self::CONFIDENCE_WEIGHT,
                    2
                );

                $compatibilityWeighted = round(
                    $compatibility
                    * self::COMPATIBILITY_WEIGHT,
                    2
                );

                /*
                |--------------------------------------------------------------------------
                | Final Ranking Score
                |--------------------------------------------------------------------------
                */

                $rankingScore =
                    $recommendationWeighted
                    + $confidenceWeighted
                    + $compatibilityWeighted;

                $candidate['ranking_score'] = round(
                    $rankingScore,
                    2
                );

                /*
                |--------------------------------------------------------------------------
                | Ranking Breakdown
                |--------------------------------------------------------------------------
                |
                | Preserve both raw score and weighted contribution so the
                | ranking decision remains fully explainable.
                |
                */

                $candidate['ranking_breakdown'] = [

                    'recommendation' => [
                        'raw' => $recommendation,
                        'weight' => 70,
                        'weighted' =>
                            $recommendationWeighted,
                    ],

                    'confidence' => [
                        'raw' => $confidence,
                        'weight' => 20,
                        'weighted' =>
                            $confidenceWeighted,
                    ],

                    'compatibility' => [
                        'raw' => $compatibility,
                        'weight' => 10,
                        'weighted' =>
                            $compatibilityWeighted,
                    ],

                ];

                return $candidate;

            })

            /*
            |--------------------------------------------------------------------------
            | Primary Sort
            |--------------------------------------------------------------------------
            */

            ->sortByDesc('ranking_score')

            ->values();
    }

    /**
     * --------------------------------------------------------------------------
     * Top Recommendations
     * --------------------------------------------------------------------------
     */
    public function top(
        Collection $recommendations,
        int $limit = 10,
    ): Collection {

        return $recommendations
            ->take($limit)
            ->values();
    }

    /**
     * --------------------------------------------------------------------------
     * Normalize Score
     * --------------------------------------------------------------------------
     *
     * Protects ranking against invalid upstream score values.
     *
     * Ranking inputs are expected to remain within the normalized
     * DIGESTEX intelligence score range of 0–100.
     *
     */
    private function normalizeScore(
        mixed $score
    ): float {

        return round(
            max(
                0,
                min(
                    100,
                    (float) $score
                )
            ),
            2
        );
    }
}