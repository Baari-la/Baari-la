<?php

declare(strict_types=1);

namespace App\Services\Recommendation\Candidate;

use App\Models\Company;
use Illuminate\Support\Collection;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Candidate Evaluator
 * ==========================================================================
 *
 * Normalizes candidate data before entering the Recommendation Engine.
 *
 * This service DOES NOT calculate scores.
 *
 * Version:
 * 1.0
 */
class CandidateEvaluator
{
    /**
     * --------------------------------------------------------------------------
     * Evaluate Candidates
     * --------------------------------------------------------------------------
     */
    public function evaluate(
        Company $company,
        Collection $candidates,
        array $context = [],
    ): Collection {

        return $candidates

            ->map(function (Company $candidate) {

                return [

                    /*
                    |--------------------------------------------------------------------------
                    | Identity
                    |--------------------------------------------------------------------------
                    */

                    'company' => $candidate,

                    'company_id' => $candidate->id,

                    'company_name' => $candidate->nama_perusahaan,

                    /*
                    |--------------------------------------------------------------------------
                    | Business
                    |--------------------------------------------------------------------------
                    */

                    'category' => $candidate->category,

                    'membership' => $candidate->membership_type,

                    'country' => $candidate->country_name,

                    'city' => $candidate->city,

                    /*
                    |--------------------------------------------------------------------------
                    | Relations
                    |--------------------------------------------------------------------------
                    */

                    'products' => $candidate->products,

                    'markets' => $candidate->markets,

                    'certifications' => $candidate->certifications,

                    'machines' => $candidate->machines,

                    'capacities' => $candidate->capacities,

                    /*
                    |--------------------------------------------------------------------------
                    | Recommendation
                    |--------------------------------------------------------------------------
                    |
                    | Filled by subsequent services.
                    |
                    */

                    'compatibility_score' => 0,

                    'recommendation_score' => 0,

                    'confidence_score' => 0,

                    'ranking_score' => 0,

                    'reasons' => [],

                ];

            })

            ->values();

    }
}