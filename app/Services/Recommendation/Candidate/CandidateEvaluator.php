<?php

declare(strict_types=1);

namespace App\Services\Recommendation\Candidate;

use App\Models\Company;
use App\Services\Company\Intelligence\BusinessRoleService;
use Illuminate\Support\Collection;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Candidate Evaluator
 * ==========================================================================
 *
 * Normalizes and classifies candidate companies before they enter DRIE.
 *
 * Responsibilities:
 *
 * - Normalize candidate company data
 * - Resolve ecosystem role
 * - Resolve canonical business role
 * - Preserve specific business roles
 * - Preserve classification evidence and confidence
 *
 * This service DOES NOT calculate recommendation scores.
 *
 * Version:
 * 2.0
 */
class CandidateEvaluator
{
    public function __construct(
        protected BusinessRoleService $businessRole,
    ) {
    }

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

                /*
                |--------------------------------------------------------------------------
                | Business Role Classification
                |--------------------------------------------------------------------------
                */

                $classification =
                    $this->businessRole->classify($candidate);

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
                    | Business Role Intelligence
                    |--------------------------------------------------------------------------
                    */

                    'ecosystem_role' =>
                        $classification['ecosystem_role'] ?? null,

                    'canonical_role' =>
                        $classification['canonical_role'] ?? null,

                    'specific_roles' =>
                        $classification['specific_roles'] ?? [],

                    'role_confidence' =>
                        $classification['confidence'] ?? 0,

                    'role_source' =>
                        $classification['source'] ?? null,

                    'role_evidence' =>
                        $classification['evidence'] ?? [],

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
                    */

                    'eligible' => true,

                    'eligibility_reasons' => [],

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