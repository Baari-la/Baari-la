<?php

declare(strict_types=1);

namespace App\Services\Company\Intelligence;

use App\Models\Company;

class CompanyMatchingService
{
    /**
     * ==========================================================================
     * DIGESTEX CORE
     * ==========================================================================
     * Smart Business Matching
     * ==========================================================================
     *
     * Generates intelligent business recommendations based on:
     *
     * Company
     *      ↓
     * Business Role
     *      ↓
     * Business Ecosystem
     *      ↓
     * Business Needs
     *      ↓
     * Matching Companies
     *
     * Used by:
     *
     * • Smart Business Matching
     * • Build My Supply Chain™
     * • Executive AI
     * • Buyer Discovery
     *
     * Version : 2.0
     */

     public function __construct(

        protected BusinessRoleService $roleService,

        protected BusinessEcosystemService $ecosystemService,

        protected BusinessNeedService $needService,

    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Smart Business Matching
     * --------------------------------------------------------------------------
     */
    public function all(
        Company $company
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Resolve Business Role
        |--------------------------------------------------------------------------
        */

        $role = $this->roleService
            ->resolve($company);

        /*
        |--------------------------------------------------------------------------
        | Resolve Business Ecosystem
        |--------------------------------------------------------------------------
        */

        $ecosystem = $this->ecosystemService
            ->resolve($role);

        /*
        |--------------------------------------------------------------------------
        | Build Business Needs
        |--------------------------------------------------------------------------
        */

        $categories = $this->needService
            ->matchingPayload($ecosystem);

        /*
        |--------------------------------------------------------------------------
        | Populate Every Category
        |--------------------------------------------------------------------------
        */

        foreach ($categories as &$category) {

            $category['companies'] =

                $this->recommendCompanies(

                    company: $company,

                    category: $category['category']

                );

        }

        unset($category);

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return [

            'title' =>

                'Smart Business Matching',

            'description' =>

                'AI-powered business ecosystem recommendations.',

            'role' =>

                $role,

            'company_type' =>

                $ecosystem['name'] ?? null,

            'ecosystem' =>

                $ecosystem,

            'categories' =>

                $categories,

        ];

    }

    /**
     * --------------------------------------------------------------------------
     * Recommend Companies
     * --------------------------------------------------------------------------
     *
     * Recommendation layer.
     *
     * Future:
     *
     * • Product Matching
     * • HS Code Matching
     * • Export Market Matching
     * • Capacity Matching
     * • Compliance Matching
     * • AI Recommendation Engine
     */
   protected function recommendCompanies(
    Company $company,
    string $category,
): array {

    return Company::query()

        ->whereKeyNot($company->id)

        ->limit(5)

        ->get()

        ->map(function (Company $candidate) use ($company, $category) {

            $score = 80;

            return [

                'company_id' => $candidate->id,

                'company_name' => $candidate->nama_perusahaan,

                'membership' => $candidate->membership_type,

                'country' => $candidate->country_name,

                'city' => $candidate->city,

                'matching_score' => $score,

                'matching_level' => 'Good Match',

                'matching_reasons' => [

                    'Business ecosystem compatibility',

                    'Same textile value chain',

                ],

            ];

        })

        ->values()

        ->all();

}
}