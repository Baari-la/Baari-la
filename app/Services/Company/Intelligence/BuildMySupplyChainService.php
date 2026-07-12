<?php

declare(strict_types=1);

namespace App\Services\Company\Intelligence;

use App\Models\Company;
use App\Services\SupplyChain\Contracts\SupplyChainRecommendationContract;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Build My Supply Chain Service
 * ==========================================================================
 *
 * Lightweight facade for Build My Supply Chain™.
 *
 * Responsibilities
 *
 * • Resolve Business Role
 * • Resolve Business Ecosystem
 * • Execute Supply Chain Recommendation Engine
 * • Prepare frontend payload
 *
 * Business Logic:
 *
 * SupplyChainRecommendationEngine
 *
 * Version:
 * 3.0
 */
class BuildMySupplyChainService
{
    public function __construct(

        protected BusinessRoleService $roleService,

        protected BusinessEcosystemService $ecosystemService,

        protected SupplyChainRecommendationContract $supplyChainRecommendation,

    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Build Supply Chain Blueprint
     * --------------------------------------------------------------------------
     */
    public function build(
        Company $company,
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Resolve Business Role
        |--------------------------------------------------------------------------
        */

        $role =

            $this->roleService->resolve(

                $company

            );

        /*
        |--------------------------------------------------------------------------
        | Resolve Business Ecosystem
        |--------------------------------------------------------------------------
        */

        $ecosystem =

            $this->ecosystemService->resolve(

                $role

            );

        /*
        |--------------------------------------------------------------------------
        | Supply Chain Recommendation Engine
        |--------------------------------------------------------------------------
        */

        $result =

            $this->supplyChainRecommendation

                ->recommend(

                    company: $company,

                );

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return [

            'title' =>

                'Build My Supply Chain',

            'description' =>

                'Recommended upstream and downstream textile ecosystem.',

            'role' =>

                $role,

            'ecosystem' =>

                $ecosystem['name'] ?? null,

            /*
            |--------------------------------------------------------------------------
            | Supply Chain
            |--------------------------------------------------------------------------
            */

            'upstream' =>

                $result->upstream(),

            'current' => [

                'company_id' =>

                    $company->id,

                'company_name' =>

                    $company->nama_perusahaan,

                'role' =>

                    $role,

            ],

            'downstream' =>

                $result->downstream(),

            /*
            |--------------------------------------------------------------------------
            | Metadata
            |--------------------------------------------------------------------------
            */

            'statistics' =>

                $result->statistics(),

            'metadata' =>

                $result->metadata(),

        ];

    }
}