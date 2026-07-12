<?php

declare(strict_types=1);

namespace App\Services\Recommendation\Reason;

use App\Models\Company;
use Illuminate\Support\Collection;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Recommendation Reason Service
 * ==========================================================================
 *
 * Generates explainable recommendation reasons.
 *
 * Every recommendation produced by DIGESTEX must explain WHY
 * a company is recommended.
 *
 * Version:
 * 1.0
 */
class RecommendationReasonService
{
    /**
     * --------------------------------------------------------------------------
     * Build Recommendation Reasons
     * --------------------------------------------------------------------------
     */
    public function build(
        Company $company,
        Collection $candidates,
        array $context = [],
    ): Collection {

        return $candidates->map(function (array $candidate) use ($company) {

            $reasons = [];

            /*
            |--------------------------------------------------------------------------
            | Business Category
            |--------------------------------------------------------------------------
            */

            if ($candidate['category'] === $company->category) {

                $reasons[] = 'Same business category';

            }

            /*
            |--------------------------------------------------------------------------
            | Membership
            |--------------------------------------------------------------------------
            */

            if (! empty($candidate['membership'])) {

                $reasons[] = 'Verified participation member';

            }

            /*
            |--------------------------------------------------------------------------
            | Products
            |--------------------------------------------------------------------------
            */

            if ($candidate['products']->count() > 0) {

                $reasons[] = 'Product portfolio available';

            }

            /*
            |--------------------------------------------------------------------------
            | Export Markets
            |--------------------------------------------------------------------------
            */

            if ($candidate['markets']->count() > 0) {

                $reasons[] = 'Export market experience';

            }

            /*
            |--------------------------------------------------------------------------
            | Certifications
            |--------------------------------------------------------------------------
            */

            if ($candidate['certifications']->count() > 0) {

                $reasons[] = 'International certifications available';

            }

            /*
            |--------------------------------------------------------------------------
            | Production Capacity
            |--------------------------------------------------------------------------
            */

            if ($candidate['capacities']->count() > 0) {

                $reasons[] = 'Production capacity declared';

            }

            /*
            |--------------------------------------------------------------------------
            | Future
            |--------------------------------------------------------------------------
            |
            | Buyer Intelligence
            | Supply Chain
            | Sustainability
            | Opportunity
            |
            */

            $candidate['reasons'] = $reasons;

            return $candidate;

        });

    }
}