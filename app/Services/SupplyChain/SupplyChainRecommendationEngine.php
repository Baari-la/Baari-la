<?php

declare(strict_types=1);

namespace App\Services\SupplyChain;

use App\Models\Company;
use Carbon\Carbon;

use App\Services\Company\Intelligence\BusinessRoleService;
use App\Services\Company\Intelligence\BusinessEcosystemService;

use App\Services\SupplyChain\Contracts\SupplyChainRecommendationContract;
use App\Services\SupplyChain\DTO\SupplyChainRecommendationResult;

use App\Services\SupplyChain\Stage\UpstreamCompanyFinder;
use App\Services\SupplyChain\Stage\DownstreamCompanyFinder;

use App\Services\SupplyChain\Ranking\SupplyChainRankingService;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Supply Chain Recommendation Engine
 * ==========================================================================
 *
 * Generates upstream and downstream supply chain recommendations
 * based on the company's business role.
 *
 * Responsibilities
 *
 * • Resolve Business Role
 * • Resolve Business Ecosystem
 * • Find Upstream Companies
 * • Find Downstream Companies
 * • Rank Companies
 * • Produce DTO
 *
 * Used by:
 *
 * • Build My Supply Chain™
 * • Buyer Discovery™
 * • Executive AI™
 *
 * Version:
 * 1.0
 */
class SupplyChainRecommendationEngine
    implements SupplyChainRecommendationContract
{
    public function __construct(

        protected BusinessRoleService $roleService,

        protected BusinessEcosystemService $ecosystemService,

        protected UpstreamCompanyFinder $upstreamFinder,

        protected DownstreamCompanyFinder $downstreamFinder,

        protected SupplyChainRankingService $ranking,

    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Generate Recommendation
     * --------------------------------------------------------------------------
     */
    public function recommend(
        Company $company,
        array $context = [],
    ): SupplyChainRecommendationResult {

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
        | Find Upstream Companies
        |--------------------------------------------------------------------------
        */

        $upstream =

            $this->upstreamFinder->find(

                company: $company,

                upstreamStages:

                    $ecosystem['upstream'] ?? [],

            );

        /*
        |--------------------------------------------------------------------------
        | Find Downstream Companies
        |--------------------------------------------------------------------------
        */

        $downstream =

            $this->downstreamFinder->find(

                company: $company,

                downstreamStages:

                    $ecosystem['downstream'] ?? [],

            );

        /*
        |--------------------------------------------------------------------------
        | Ranking
        |--------------------------------------------------------------------------
        */

        $upstream =

            $this->ranking->rank(

                $upstream

            );

        $downstream =

            $this->ranking->rank(

                $downstream

            );

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $statistics = [

            'upstream_stage_count' =>

                count(

                    $ecosystem['upstream'] ?? []

                ),

            'downstream_stage_count' =>

                count(

                    $ecosystem['downstream'] ?? []

                ),

            'upstream_company_count' =>

                $upstream->sum(

                    fn (array $stage)

                        => count(

                            $stage['companies'] ?? []

                        )

                ),

            'downstream_company_count' =>

                $downstream->sum(

                    fn (array $stage)

                        => count(

                            $stage['companies'] ?? []

                        )

                ),

        ];

        $statistics['total_recommendations'] =

            $statistics['upstream_company_count']

            +

            $statistics['downstream_company_count'];

        /*
        |--------------------------------------------------------------------------
        | Metadata
        |--------------------------------------------------------------------------
        */

        $metadata = [

            'framework' =>

                'DIGESTEX Supply Chain Intelligence',

            'framework_version' =>

                '1.0',

            'generated_by' =>

                self::class,

            'generated_at' =>

                Carbon::now()->toDateTimeString(),

        ];
                /*
        |--------------------------------------------------------------------------
        | Build DTO
        |--------------------------------------------------------------------------
        */

        return new SupplyChainRecommendationResult(

            /*
            |--------------------------------------------------------------------------
            | Engine
            |--------------------------------------------------------------------------
            */

            engine:

                'DIGESTEX Supply Chain Recommendation Engine',

            version:

                '1.0',

            generatedAt:

                Carbon::now(),

            /*
            |--------------------------------------------------------------------------
            | Company
            |--------------------------------------------------------------------------
            */

            companyId:

                $company->id,

            role:

                $role,

            ecosystem:

                $ecosystem['name'] ?? '',

            /*
            |--------------------------------------------------------------------------
            | Recommendations
            |--------------------------------------------------------------------------
            */

            upstream:

                $upstream

                    ->values()

                    ->all(),

            downstream:

                $downstream

                    ->values()

                    ->all(),

            /*
            |--------------------------------------------------------------------------
            | Statistics
            |--------------------------------------------------------------------------
            */

            statistics:

                $statistics,

            /*
            |--------------------------------------------------------------------------
            | Metadata
            |--------------------------------------------------------------------------
            */

            metadata:

                $metadata,

        );

    }
}