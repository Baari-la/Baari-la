<?php

declare(strict_types=1);

namespace App\Services\Recommendation\Score;

use App\Models\Company;
use Illuminate\Support\Collection;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Compatibility Score Service
 * ==========================================================================
 *
 * Calculates business compatibility between companies.
 *
 * This service ONLY calculates compatibility.
 *
 * Version:
 * 1.0
 */
class CompatibilityScoreService
{
    /**
     * --------------------------------------------------------------------------
     * Calculate Compatibility Score
     * --------------------------------------------------------------------------
     */
    public function calculate(
        Company $company,
        Collection $candidates,
        array $context = [],
    ): Collection {

        return $candidates->map(function (array $candidate) use ($company) {

            $score = 0;

            /*
            |--------------------------------------------------------------------------
            | Business Category
            |--------------------------------------------------------------------------
            */

            if (
                $candidate['category'] === $company->category
            ) {
                $score += 30;
            }

            /*
            |--------------------------------------------------------------------------
            | Membership
            |--------------------------------------------------------------------------
            */

            if (
                ! empty($candidate['membership'])
            ) {
                $score += 5;
            }

            /*
            |--------------------------------------------------------------------------
            | Country
            |--------------------------------------------------------------------------
            */

            if (
                $candidate['country'] === $company->country_name
            ) {
                $score += 5;
            }

            /*
            |--------------------------------------------------------------------------
            | Placeholder
            |--------------------------------------------------------------------------
            |
            | Sprint berikutnya:
            | - Product
            | - Market
            | - Capacity
            | - Certification
            | - Visibility
            |
            */

            $candidate['compatibility_score'] = min($score, 100);

            return $candidate;

        });

    }
}