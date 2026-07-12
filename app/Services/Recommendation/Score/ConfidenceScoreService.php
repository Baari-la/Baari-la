<?php

declare(strict_types=1);

namespace App\Services\Recommendation\Score;

use App\Models\Company;
use Illuminate\Support\Collection;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Confidence Score Service
 * ==========================================================================
 *
 * Measures confidence level of recommendation.
 *
 * Confidence depends on data completeness.
 *
 * Version:
 * 1.0
 */
class ConfidenceScoreService
{
    /**
     * --------------------------------------------------------------------------
     * Calculate Confidence Score
     * --------------------------------------------------------------------------
     */
    public function calculate(
        Company $company,
        Collection $candidates,
        array $context = [],
    ): Collection {

        return $candidates->map(function (array $candidate) {

            $confidence = 0;

            /*
            |--------------------------------------------------------------------------
            | Company Information
            |--------------------------------------------------------------------------
            */

            if (! empty($candidate['category'])) {
                $confidence += 20;
            }

            if (! empty($candidate['membership'])) {
                $confidence += 10;
            }

            if (! empty($candidate['country'])) {
                $confidence += 10;
            }

            /*
            |--------------------------------------------------------------------------
            | Relations
            |--------------------------------------------------------------------------
            */

            if ($candidate['products']->count() > 0) {
                $confidence += 20;
            }

            if ($candidate['markets']->count() > 0) {
                $confidence += 15;
            }

            if ($candidate['certifications']->count() > 0) {
                $confidence += 15;
            }

            if ($candidate['machines']->count() > 0) {
                $confidence += 5;
            }

            if ($candidate['capacities']->count() > 0) {
                $confidence += 5;
            }

            $candidate['confidence_score'] = min($confidence, 100);

            return $candidate;

        });

    }
}