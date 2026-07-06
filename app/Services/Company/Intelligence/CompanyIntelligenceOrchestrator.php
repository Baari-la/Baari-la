<?php

declare(strict_types=1);

namespace App\Services\Company\Intelligence;

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

        protected CompanyCapabilityService $capability,

        protected CompanyComplianceService $compliance,

        protected CompanyMarketService $market,

        protected CompanySupplyChainService $supplyChain,

        protected CompanyReadinessService $readiness,

        protected CompanyScoreService $score,

        /*
        |--------------------------------------------------------------------------
        | Passport Services
        |--------------------------------------------------------------------------
        */

        protected CompanyRecommendationService $recommendation,

        protected CompanyPassportMetadata $metadata,

        protected CompanyPassportAssembler $assembler,

    ) {
    }
        /**
     * --------------------------------------------------------------------------
     * Company Profile
     * --------------------------------------------------------------------------
     *
     * Digital identity and company master information.
     */
    public function profile(Company $company): array
    {
        return $this->profile->all($company);
    }

    /**
     * --------------------------------------------------------------------------
     * Capability Intelligence
     * --------------------------------------------------------------------------
     *
     * Manufacturing capability, machinery, production,
     * MOQ, lead time and operational readiness.
     */
    public function capability(Company $company): array
    {
        return $this->capability->all($company);
    }

    /**
     * --------------------------------------------------------------------------
     * Compliance Intelligence
     * --------------------------------------------------------------------------
     *
     * Certifications, social compliance,
     * environmental compliance and audit readiness.
     */
    public function compliance(Company $company): array
    {
        return $this->compliance->all($company);
    }

    /**
     * --------------------------------------------------------------------------
     * Market Intelligence
     * --------------------------------------------------------------------------
     *
     * Export markets, market diversification,
     * international presence and trade intelligence.
     */
    public function market(Company $company): array
    {
        return $this->market->all($company);
    }

    /**
     * --------------------------------------------------------------------------
     * Supply Chain Intelligence
     * --------------------------------------------------------------------------
     *
     * Factory capability, material sourcing,
     * logistics, MOQ and lead time.
     */
    public function supplyChain(Company $company): array
    {
        return $this->supplyChain->all($company);
    }

    /**
     * --------------------------------------------------------------------------
     * Business Readiness
     * --------------------------------------------------------------------------
     *
     * Overall operational readiness for
     * international business.
     */
    public function readiness(Company $company): array
    {
        return $this->readiness->all($company);
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
     * Company Recommendations
     * --------------------------------------------------------------------------
     *
     * Business recommendations generated from
     * Company Intelligence modules.
     */
    public function recommendations(
        Company $company,
        array $capability,
        array $compliance,
        array $market,
        array $supplyChain,
        array $readiness,
    ): array {

        return $this->recommendation->generate(
            company: $company,
            capability: $capability,
            compliance: $compliance,
            market: $market,
            supplyChain: $supplyChain,
            readiness: $readiness,
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
    public function passport(Company $company): array
    {
        /*
        |--------------------------------------------------------------------------
        | Core Intelligence
        |--------------------------------------------------------------------------
        */

        $profile = $this->profile($company);

        $capability = $this->capability($company);

        $compliance = $this->compliance($company);

        $market = $this->market($company);

        $supplyChain = $this->supplyChain($company);

        $readiness = $this->readiness($company);

        $scores = $this->score($company);

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
        | Intelligence Layer
        |--------------------------------------------------------------------------
        |
        | Reserved for:
        | - Market Intelligence
        | - Matching Intelligence
        | - Opportunity Intelligence
        | - Executive Intelligence
        |
        */

        $intelligence = [

            'market' => [],

            'matching' => [],

            'opportunities' => [],

            'executive' => [],

        ];

        /*
        |--------------------------------------------------------------------------
        | Build Passport
        |--------------------------------------------------------------------------
        */

        $passport = $this->assembler->build(

            company: $company,

            capability: $capability,

            compliance: $compliance,

            market: $market,

            supplyChain: $supplyChain,

            readiness: $readiness,

            scores: $scores,

            recommendations: $recommendations,

            intelligence: $intelligence,

            metadata: $metadata,

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