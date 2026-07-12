<?php

declare(strict_types=1);

namespace App\Services\Executive;

use App\Models\Company;
use App\Services\Knowledge\GraphReasoningService;
use App\Services\Knowledge\GraphTraversalService;
use App\Services\Knowledge\KnowledgeGraphService;

use App\Services\Company\CompanyCapabilityService;
use App\Services\Company\CompanyComplianceService;
use App\Services\Company\CompanyMarketService;
use App\Services\Company\CompanySupplyChainService;
use App\Services\Company\CompanyReadinessService;
use App\Services\Company\CompanyScoreService;
use App\Services\Company\CompanyRecommendationService;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Executive Intelligence Service
 * ==========================================================================
 *
 * Top Level Intelligence Orchestrator
 *
 * Responsibilities
 *
 * • Company Intelligence
 * • Knowledge Graph
 * • Executive AI
 * • Recommendation
 * • Explainability
 * • Executive Dashboard
 *
 */

class ExecutiveIntelligenceService
{
    public function __construct(

        protected KnowledgeGraphService $graph,

        protected CompanyCapabilityService $capability,

        protected CompanyComplianceService $compliance,

        protected CompanyMarketService $market,

        protected CompanySupplyChainService $supplyChain,

        protected CompanyReadinessService $readiness,

        protected CompanyScoreService $score,

        protected CompanyRecommendationService $recommendation,

    ) {
    }
    /*
|--------------------------------------------------------------------------
| Build Intelligence
|--------------------------------------------------------------------------
*/

public function intelligence(
    Company $company
): array {

    /*
    |--------------------------------------------------------------------------
    | Build Knowledge Graph
    |--------------------------------------------------------------------------
    */

    $graph = $this->graph->build($company);

    /*
    |--------------------------------------------------------------------------
    | Traversal
    |--------------------------------------------------------------------------
    */

    $traversal = new GraphTraversalService(
        $graph
    );

    /*
    |--------------------------------------------------------------------------
    | Reasoning
    |--------------------------------------------------------------------------
    */

    $reasoning = new GraphReasoningService(
        $traversal
    );

    return [

        'company'

            => $company,

        'graph'

            => $graph,

        'dashboard'

            => $this->dashboard($company),

        'reasoning'

            => $reasoning->executiveSummary(

                $company->business_role

            ),

        'explanation'

            => $reasoning->explain(

                $company->business_role

            ),

        'recommendations'

            => $this->recommendation

                ->recommend($company),

    ];

}
/*
|--------------------------------------------------------------------------
| Executive Dashboard
|--------------------------------------------------------------------------
*/

protected function dashboard(
    Company $company
): array {

    return [

        'executive_score'

            => $this->score
                ->score($company),

        'capability'

            => $this->capability
                ->score($company),

        'compliance'

            => $this->compliance
                ->score($company),

        'market'

            => $this->market
                ->score($company),

        'supply_chain'

            => $this->supplyChain
                ->score($company),

        'readiness'

            => $this->readiness
                ->score($company),

    ];

}
/*
|--------------------------------------------------------------------------
| Executive AI
|--------------------------------------------------------------------------
*/

public function executiveAI(
    Company $company
): array {

    $result = $this->intelligence(
        $company
    );

    return [

        'executive_score'

            => $result['dashboard'],

        'reasoning'

            => $result['reasoning'],

        'recommendations'

            => $result['recommendations'],

        'explanation'

            => $result['explanation'],

    ];

}
/*
|--------------------------------------------------------------------------
| Explainability
|--------------------------------------------------------------------------
*/

public function explain(
    Company $company
): array {

    return $this->intelligence(
        $company
    )['explanation'];

}
/*
|--------------------------------------------------------------------------
| Recommendation
|--------------------------------------------------------------------------
*/

public function recommendations(
    Company $company
): array {

    return $this->recommendation
        ->recommend($company);

}
/*
|--------------------------------------------------------------------------
| Export
|--------------------------------------------------------------------------
*/

public function toArray(
    Company $company
): array {

    return $this->intelligence(
        $company
    );

}
}