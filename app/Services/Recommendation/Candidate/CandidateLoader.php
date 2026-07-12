<?php

declare(strict_types=1);

namespace App\Services\Recommendation\Candidate;

use App\Models\Company;
use Illuminate\Support\Collection;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Candidate Loader
 * ==========================================================================
 *
 * Responsible for loading candidate companies before evaluation.
 *
 * Responsibilities
 *
 * • Load candidate companies
 * • Exclude current company
 * • Apply basic filtering
 * • Return collection
 *
 * Version:
 * 1.0
 */
class CandidateLoader
{
    /**
     * --------------------------------------------------------------------------
     * Load Candidates
     * --------------------------------------------------------------------------
     */
    public function load(
        Company $company,
        array $context = [],
    ): Collection {

        $query = Company::query()

            ->whereKeyNot($company->id);

        /*
        |--------------------------------------------------------------------------
        | Future Filters
        |--------------------------------------------------------------------------
        |
        | Country
        | Product
        | Category
        | Membership
        | Verified Company
        |
        */

        return $query

            ->with([

                'products',

                'markets',

                'certifications',

                'machines',

                'capacities',

            ])

            ->get();

    }

    /**
     * --------------------------------------------------------------------------
     * Total Candidates
     * --------------------------------------------------------------------------
     */
    public function total(
        Collection $candidates
    ): int {

        return $candidates->count();

    }
}