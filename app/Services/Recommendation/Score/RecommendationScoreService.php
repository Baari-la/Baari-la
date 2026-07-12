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
 * Generates final recommendation score.
 *
 * Version:
 * 1.0
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

        return $candidates->map(function (array $candidate) {

            /*
            |--------------------------------------------------------------------------
            | Current Formula
            |--------------------------------------------------------------------------
            */

            $candidate['recommendation_score'] =

                $candidate['compatibility_score'];

            /*
            |--------------------------------------------------------------------------
            | Future Formula
            |--------------------------------------------------------------------------
            |
            | Compatibility
            | Buyer Intelligence
            | Supply Chain Intelligence
            | Opportunity Intelligence
            | AI Recommendation
            |
            */

            return $candidate;

        });

    }
}