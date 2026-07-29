<?php

declare(strict_types=1);

namespace App\Services\Company\Intelligence;
use App\Services\Company\Intelligence\BuildMySupplyChainService;

use App\Models\Company;
use App\Services\Company\Passport\CompanyPassportAssembler;
use App\Services\Company\Passport\CompanyPassportMetadata;
use App\Services\Company\Passport\CompanyRecommendationService;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Company Intelligence Orchestrator
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Central orchestrator for the entire Company Intelligence Framework.
 *
 * This class is the Single Entry Point (SEP) for all Company Intelligence
 * modules and is responsible for coordinating every intelligence service
 * required to build the Digital Company Passport.
 *
 * Responsibilities
 * --------------------------------------------------------------------------
 * ✓ Coordinate Company Intelligence modules
 * ✓ Build Digital Company Passport
 * ✓ Produce standardized Company Passport JSON Contract
 * ✓ Provide unified data for Web, API, Mobile and Executive AI
 *
 * This service NEVER:
 *
 * ✗ Calculates business scores directly
 * ✗ Executes business rules
 * ✗ Performs AI analysis
 * ✗ Executes supplier matching
 * ✗ Contains presentation logic
 *
 * Architecture
 * --------------------------------------------------------------------------
 *
 * Company
 *      │
 *      ▼
 * CompanyProfileService
 * CompanyCapabilityService
 * CompanyComplianceService
 * CompanyMarketService
 * CompanySupplyChainService
 * CompanyReadinessService
 * CompanyScoreService
 * CompanyRecommendationService
 * CompanyPassportMetadata
 *      │
 *      ▼
 * CompanyPassportAssembler
 *      │
 *      ▼
 * CompanyPassportData (DTO)
 *      │
 *      ▼
 * React / API / Mobile / Executive AI
 *
 * Used By
 * --------------------------------------------------------------------------
 * • Digital Company Passport
 * • Company Workspace
 * • Executive Dashboard
 * • Company Directory
 * • Matching Intelligence
 * • Opportunity Intelligence
 * • Executive Intelligence
 * • Executive AI
 */
class CompanyIntelligenceOrchestrator
{
    public function __construct(

          /*
        |--------------------------------------------------------------------------
        | Core Intelligence Services
        |--------------------------------------------------------------------------
        */
        
        protected CompanyProfileService $profile,

        protected CompanyMetricsService $metrics,

        /*
        |--------------------------------------------------------------------------
        | Business Intelligence
        |--------------------------------------------------------------------------
        */

        protected BusinessRoleService $role,

        protected BusinessEcosystemService $ecosystem,

        protected BusinessNeedService $needs,

        protected CompanyMatchingService $matching,

        /*
        |--------------------------------------------------------------------------
        | Operational Intelligence
        |--------------------------------------------------------------------------
        */

        protected CompanyCapabilityService $capability,

        protected CompanyComplianceService $compliance,

        protected CompanyMarketService $market,

        protected CompanySupplyChainService $supplyChain,

        protected CompanyReadinessService $readiness,

        /*
        |--------------------------------------------------------------------------
        | Executive Intelligence
        |--------------------------------------------------------------------------
        */

        protected CompanyScoreService $score,

        protected CompanyRecommendationService $recommendation,

        /*
        |--------------------------------------------------------------------------
        | Passport
        |--------------------------------------------------------------------------
        */

        protected CompanyPassportMetadata $metadata,

        protected CompanyPassportAssembler $assembler,

        /*
    |--------------------------------------------------------------------------
    | Build My Supply Chain
    |--------------------------------------------------------------------------
    */

    protected BuildMySupplyChainService $buildSupplyChain,

    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Company Profile
    |--------------------------------------------------------------------------
    */

    /**
     * Digital Company Profile
     */
    public function profile(
        Company $company
    ): array {

        return $this->profile->all(
            $company
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Company Metrics
    |--------------------------------------------------------------------------
    */

    /**
     * Executive Statistics
     */
    public function metrics(
        Company $company
    ): array {

        return $this->metrics->generate(
            $company
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Business Intelligence
    |--------------------------------------------------------------------------
    */

    /**
     * Resolve Business Role
     */
    public function role(
        Company $company
    ): string {

        return $this->role->resolve(
            $company
        );

    }

/**
 * --------------------------------------------------------------------------
 * Business Role Classification
 * --------------------------------------------------------------------------
 *
 * Structured DIGESTEX business role intelligence.
 */
public function roleClassification(
    Company $company
): array {

    return $this->role->classify(
        $company
    );
}
    
    /**
     * Resolve Business Ecosystem
     */
    public function ecosystem(
        Company $company
    ): array {

        return $this->ecosystem->resolve(

            $this->role($company)

        );

    }

    /**
     * Resolve Business Needs
     */
    public function needs(
        Company $company
    ): array {

        return $this->needs->build(

            $this->ecosystem($company)

        );

    }

    /**
     * Smart Business Matching
     */
    public function matching(
        Company $company
    ): array {

        return $this->matching->all(
            $company
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Operational Intelligence
    |--------------------------------------------------------------------------
    */

    /**
     * Company Capability
     */
    public function capability(
        Company $company
    ): array {

        return $this->capability->all(
            $company
        );

    }

    /**
     * Compliance Intelligence
     */
    public function compliance(
        Company $company
    ): array {

        return $this->compliance->all(
            $company
        );
    }
        /**
     * --------------------------------------------------------------------------
     * Market Intelligence
     * --------------------------------------------------------------------------
     */
    public function market(
        Company $company
    ): array {

        return $this->market->all(
            $company
        );

    }

    /**
     * --------------------------------------------------------------------------
     * Supply Chain Intelligence
     * --------------------------------------------------------------------------
     */
    public function supplyChain(
        Company $company
    ): array {

        return $this->supplyChain->all(
            $company
        );

    }
    /**
     * --------------------------------------------------------------------------
     * Business Readiness
     * --------------------------------------------------------------------------
     */
    public function readiness(
        Company $company
    ): array {

        return $this->readiness->all(
            $company
        );

    }

    /**
     * --------------------------------------------------------------------------
     * Executive Intelligence Score
     * --------------------------------------------------------------------------
     *
     * Overall Company Intelligence Score
     * and all executive scoring components.
     */
    public function score(Company $company): array
    {
        return $this->score->all($company);
    }

   /**
 * --------------------------------------------------------------------------
 * Executive Recommendations
 * --------------------------------------------------------------------------
 *
 * NOTE:
 * The additional intelligence parameters are intentionally prepared
 * for Recommendation Engine v2.
 *
 * Current implementation still uses CompanyRecommendationService::all($company)
 * to maintain backward compatibility.
 */
public function recommendations(
    Company $company,
    array $capability,
    array $compliance,
    array $market,
    array $supplyChain,
    array $readiness,
): array {

    return $this->recommendation->all(
        $company
    );
}

    /**
     * --------------------------------------------------------------------------
     * Passport Metadata
     * --------------------------------------------------------------------------
     *
     * Metadata for Digital Company Passport.
     */
    public function metadata(Company $company): array
    {
        return $this->metadata->build($company);
    }

/**
 * --------------------------------------------------------------------------
 * Build My Supply Chain
 * --------------------------------------------------------------------------
 */
public function buildMySupplyChain(
    Company $company,
): array {

    return $this->buildSupplyChain->build(

        $company

    );

}
    
        /**
     * --------------------------------------------------------------------------
     * Digital Company Passport
     * --------------------------------------------------------------------------
     *
     * Build complete Company Passport using the standardized
     * Company Passport JSON Contract.
     *
     * This method orchestrates all Company Intelligence services
     * and delegates the final payload construction to the
     * CompanyPassportAssembler.
     */
    public function passport(
    Company $company,
): array {

    /*
    |--------------------------------------------------------------------------
    | Core Intelligence
    |--------------------------------------------------------------------------
    */

    $capability = $this->capability->all($company);

    $compliance = $this->compliance->all($company);

    $market = $this->market->all($company);

    $supplyChain = $this->supplyChain->all($company);

    $readiness = $this->readiness->all($company);

    $scores = $this->score->all($company);

    $metrics = $this->metrics($company);

    /*
    |--------------------------------------------------------------------------
    | Business Intelligence
    |--------------------------------------------------------------------------
    */

    $role = $this->role($company);
    $roleClassification = $this->roleClassification($company);

    $ecosystem = $this->ecosystem->resolve($role);

    $businessNeeds = $this->needs->build($ecosystem);

    $matching = $this->matching($company);

    $buildSupplyChain = $this->buildMySupplyChain($company);

    /*
    |--------------------------------------------------------------------------
    | Recommendation Engine
    |--------------------------------------------------------------------------
    */

    $recommendations = $this->recommendations(

        company: $company,

        capability: $capability,

        compliance: $compliance,

        market: $market,

        supplyChain: $supplyChain,

        readiness: $readiness,

    );

    /*
    |--------------------------------------------------------------------------
    | Passport Metadata
    |--------------------------------------------------------------------------
    */

    $metadata = $this->metadata($company);

    /*
    |--------------------------------------------------------------------------
    | Company Intelligence
    |--------------------------------------------------------------------------
    */

    $companyIntelligence = [

        /*
        |--------------------------------------------------------------------------
        | Profile
        |--------------------------------------------------------------------------
        */

        'profile' => $this->profile($company),

        'metrics' => $metrics,

        /*
        |--------------------------------------------------------------------------
        | Business Intelligence
        |--------------------------------------------------------------------------
        */

        'role' => $role,

        'role_classification' => $roleClassification,

        'ecosystem' => $ecosystem,

        'business_needs' => $businessNeeds,

        'matching' => $matching,

        'build_supply_chain' => $buildSupplyChain,

        /*
        |--------------------------------------------------------------------------
        | Intelligence Modules
        |--------------------------------------------------------------------------
        */

        'capability' => $capability,

        'compliance' => $compliance,

        'market' => $market,

        'supply_chain' => $supplyChain,

        'readiness' => $readiness,

        /*
        |--------------------------------------------------------------------------
        | Executive Intelligence
        |--------------------------------------------------------------------------
        */

        'scores' => $scores,

        'recommendations' => $recommendations,

    ];

    /*
    |--------------------------------------------------------------------------
    | Build Passport
    |--------------------------------------------------------------------------
    */

    $passport = $this->assembler->build(

        $company,

        $companyIntelligence,

        $metadata,

    );

    /*
    |--------------------------------------------------------------------------
    | DTO
    |--------------------------------------------------------------------------
    */

    return method_exists($passport, 'toArray')

        ? $passport->toArray()

        : $passport;
}

}