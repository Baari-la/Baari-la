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
 * Sorts recommendation result.
 *
 * Version:
 * 1.0
 */
class RecommendationRankingService
{
    /**
     * --------------------------------------------------------------------------
     * Rank Recommendations
     * --------------------------------------------------------------------------
     */
    public function rank(
        Collection $candidates
    ): Collection {

        return $candidates

            ->sortByDesc(

                'recommendation_score'

            )

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

        return

            $recommendations

                ->take($limit)

                ->values();

    }
}