<?php

declare(strict_types=1);

namespace App\Services\Company\Passport;

use App\Models\Company;

use App\Services\Company\Intelligence\CompanyCapabilityService;
use App\Services\Company\Intelligence\CompanyComplianceService;
use App\Services\Company\Intelligence\CompanyMarketService;
use App\Services\Company\Intelligence\CompanySupplyChainService;
use App\Services\Company\Intelligence\CompanyReadinessService;
use App\Services\Company\Intelligence\CompanyScoreService;
use App\Services\Company\Passport\CompanyRecommendationService;
use App\Services\Company\Passport\CompanyPassportMetadata;
use App\Services\Company\DTO\CompanyPassportData;


/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Digital Company Passport Assembler
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Assembles all Company Intelligence modules into a single,
 * standardized Digital Company Passport.
 *
 * This class acts as the Single Source of Truth (SSOT) for all
 * company intelligence consumed by:
 *
 * • Digital Company Passport
 * • Executive Dashboard
 * • Company Profile
 * • Supplier Comparison
 * • Buyer Matching
 * • Executive AI
 * • REST API
 * • Mobile Applications
 *
 * Responsibilities
 * --------------------------------------------------------------------------
 * ✓ Build Passport Metadata
 * ✓ Assemble Intelligence Modules
 * ✓ Aggregate Executive Scores
 * ✓ Aggregate Business Recommendations
 * ✓ Generate Executive Summary
 * ✓ Generate Passport Statistics
 * ✓ Produce Standard DTO Output
 *
 * This service NEVER:
 * --------------------------------------------------------------------------
 * ✗ Reads database directly
 * ✗ Calculates business scores
 * ✗ Executes business rules
 * ✗ Performs AI reasoning
 * ✗ Applies business validation
 *
 * Design Principles
 * --------------------------------------------------------------------------
 * • Single Responsibility Principle
 * • Single Source of Truth
 * • Composition over Inheritance
 * • Immutable Response Structure
 * • Backend Contract Stability
 *
 * Output Contract
 * --------------------------------------------------------------------------
 *
 * [
 *     'metadata'        => [],
 *     'summary'         => [],
 *     'passport'        => [],
 *     'scores'          => [],
 *     'recommendations' => [],
 *     'statistics'      => [],
 * ]
 *
 * Version
 * --------------------------------------------------------------------------
 * DIGESTEX Company Intelligence Framework v1.0
 */
class CompanyPassportAssembler
{
        /**
     * --------------------------------------------------------------------------
     * Constructor
     * --------------------------------------------------------------------------
     */
    public function __construct(

        protected CompanyCapabilityService $capability,

        protected CompanyComplianceService $compliance,

        protected CompanyMarketService $market,

        protected CompanySupplyChainService $supplyChain,

        protected CompanyReadinessService $readiness,

        protected CompanyScoreService $score,

        protected CompanyRecommendationService $recommendation,

        protected CompanyPassportMetadata $metadata,

    ) {
    }
        /**
     * --------------------------------------------------------------------------
     * Passport Metadata Builder
     * --------------------------------------------------------------------------
     *
     * Delegates metadata generation to the dedicated
     * CompanyPassportMetadata service.
     */
//     public function build(
//     Company $company,
//     array $companyIntelligence,
//     array $metadata = []
// ): CompanyPassportData {

//         return $this->metadata->build($company);

//     }
        /**
     * --------------------------------------------------------------------------
     * Passport Builder
     * --------------------------------------------------------------------------
     *
     * Builds the complete Digital Company Passport by aggregating
     * all Company Intelligence modules.
     *
     * NOTE
     * --------------------------------------------------------------------------
     * Executive Score and Recommendations are intentionally excluded
     * and built separately to keep the Passport domain focused only on
     * business intelligence modules.
     */
    protected function passport(
        Company $company
    ): array {

        return [

            /*
            |--------------------------------------------------------------------------
            | Capability Intelligence
            |--------------------------------------------------------------------------
            */

            'capability' =>

                $this->capability->all($company),

            /*
            |--------------------------------------------------------------------------
            | Compliance Intelligence
            |--------------------------------------------------------------------------
            */

            'compliance' =>

                $this->compliance->all($company),

            /*
            |--------------------------------------------------------------------------
            | Market Intelligence
            |--------------------------------------------------------------------------
            */

            'market' =>

                $this->market->all($company),

            /*
            |--------------------------------------------------------------------------
            | Supply Chain Intelligence
            |--------------------------------------------------------------------------
            */

            'supply_chain' =>

                $this->supplyChain->all($company),

            /*
            |--------------------------------------------------------------------------
            | Business Readiness Intelligence
            |--------------------------------------------------------------------------
            */

            'business_readiness' =>

                $this->readiness->all($company),

        ];

    }
        /**
     * --------------------------------------------------------------------------
     * Executive Score Builder
     * --------------------------------------------------------------------------
     *
     * Builds the Executive Intelligence Score.
     */
    protected function scores(
        Company $company
    ): array {

        return $this->score->all($company);

    }
        /**
     * --------------------------------------------------------------------------
     * Recommendation Builder
     * --------------------------------------------------------------------------
     *
     * Builds executive business recommendations generated
     * from all Company Intelligence modules.
     */
    protected function recommendations(
        Company $company
    ): array {

        return $this->recommendation->all($company);

    }
        /**
     * --------------------------------------------------------------------------
     * Passport Statistics Builder
     * --------------------------------------------------------------------------
     *
     * Generates general statistics describing the generated
     * Digital Company Passport.
     */
    protected function statistics(
        Company $company,
        array $scores,
        array $recommendations,
    ): array {

        return [

            /*
            |--------------------------------------------------------------------------
            | Framework
            |--------------------------------------------------------------------------
            */

            'framework' => 'Digital Company Passport',

            'version' => '1.0',

            /*
            |--------------------------------------------------------------------------
            | Company
            |--------------------------------------------------------------------------
            */

            'company_id' => $company->id,

            /*
            |--------------------------------------------------------------------------
            | Intelligence Modules
            |--------------------------------------------------------------------------
            */

            'modules' => 7,

            /*
            |--------------------------------------------------------------------------
            | Executive Score
            |--------------------------------------------------------------------------
            */

            'overall_score' =>

                $scores['score']['overall'],

            'level' =>

                $scores['score']['level'],

            'rating' =>

                $scores['score']['rating'],

            /*
            |--------------------------------------------------------------------------
            | Recommendations
            |--------------------------------------------------------------------------
            */

            'recommendation_total' =>

                $recommendations['summary']['total'],

            /*
            |--------------------------------------------------------------------------
            | Passport
            |--------------------------------------------------------------------------
            */

            'verification_status' =>

                $company->verification_status,

            'claimed' =>

                $company->isClaimed(),

            'verified_company' =>

                $company->isVerifiedProfile(),

            /*
            |--------------------------------------------------------------------------
            | Timestamp
            |--------------------------------------------------------------------------
            */

            'generated_at' =>

                now()->toDateTimeString(),

        ];

    }
        /**
     * --------------------------------------------------------------------------
     * Executive Summary Builder
     * --------------------------------------------------------------------------
     *
     * Generates executive summary used by
     * Executive Dashboard and Executive Header.
     */
    protected function summary(
        Company $company,
        array $scores,
        array $recommendations,
    ): array {

        return [

            /*
            |--------------------------------------------------------------------------
            | Company
            |--------------------------------------------------------------------------
            */

            'company_id' =>$company->id,

            'company_name' => $company->nama_perusahaan,

            /*
|--------------------------------------------------------------------------
| Company KPI
|--------------------------------------------------------------------------
*/

'employees' => $company->tenaga_kerja,

'products' => $company->products->count(),

'markets' => $company->markets->count(),

'machines' => $company->machines->count(),

'capacities' => $company->capacities->count(),

'moqs' => $company->moqs->count(),

'lead_times' => $company->leadTimes->count(),
            /*
            |--------------------------------------------------------------------------
            | Executive Score
            |--------------------------------------------------------------------------
            */

            'overall_score' =>

                $scores['score']['overall'],

            'level' =>

                $scores['score']['level'],

            'rating' =>

                $scores['score']['rating'],

            /*
            |--------------------------------------------------------------------------
            | Recommendation Summary
            |--------------------------------------------------------------------------
            */

            'recommendations' =>

                $recommendations['summary']['total'],

            'critical' =>

                $recommendations['summary']['critical'],

            'high' =>

                $recommendations['summary']['high'],

            'medium' =>

                $recommendations['summary']['medium'],

            'low' =>

                $recommendations['summary']['low'],

            /*
            |--------------------------------------------------------------------------
            | Verification
            |--------------------------------------------------------------------------
            */

            'verification_status' =>

                $company->verification_status,

            'claimed' =>

                $company->isClaimed(),

            'verified_company' =>

                $company->isVerifiedProfile(),

        ];

    }
        /**
     * --------------------------------------------------------------------------
     * Build Digital Company Passport
     * --------------------------------------------------------------------------
     *
     * Main entry point.
     */
    public function build(
    Company $company,
    array $companyIntelligence,
    array $metadata = []
): CompanyPassportData{

        $capability = $companyIntelligence['capability'] ?? [];

$compliance = $companyIntelligence['compliance'] ?? [];

$market = $companyIntelligence['market'] ?? [];

$supplyChain = $companyIntelligence['supply_chain'] ?? [];

$readiness = $companyIntelligence['readiness'] ?? [];

$scores = $companyIntelligence['scores'] ?? [];

$recommendations = $companyIntelligence['recommendations'] ?? [];
    
/*
|--------------------------------------------------------------------------
| Business Intelligence
|--------------------------------------------------------------------------
*/

$role = $companyIntelligence['role'] ?? null;

$ecosystem = $companyIntelligence['ecosystem'] ?? [];

$businessNeeds = $companyIntelligence['business_needs'] ?? [];

$matching = $companyIntelligence['matching'] ?? [];
$buildSupplyChain = $companyIntelligence['build_supply_chain'] ?? [];
      $passport = [

    'profile' => [

    'company_id'        => $company->id,
    'company_name'      => $company->nama_perusahaan,

    'membership_type'   => $company->membership_type,

    'country_name'      => $company->country_name,
    'country_code'      => $company->country_code,

    'city'              => $company->city,
    'sector'            => $company->sektor,

    'leader'            => $company->pimpinan,

    'employees'         => $company->tenaga_kerja,

    'verification_status' => $company->verification_status,

    'claimed'           => $company->isClaimed(),

    'verified_company'  => $company->isVerifiedProfile(),

],

    'capability' => $capability['passport'] ?? [],

    'compliance' => $compliance['passport'] ?? [],

    'market' => $market['passport'] ?? [],

    'supply_chain' => $supplyChain['passport'] ?? [],

    'readiness' => $readiness['passport'] ?? [],

]; 
     
$statistics =

    $this->statistics(

        $company,

        $scores,

        $recommendations,

    );
            /*

        
        |--------------------------------------------------------------------------
        | Executive Summary
        |--------------------------------------------------------------------------
        */

        $summary =

            $this->summary(

                $company,

                $scores,

                $recommendations,

            );

        /*
        |--------------------------------------------------------------------------
        | DTO
        |--------------------------------------------------------------------------
        */

       return CompanyPassportData::fromArray([
        'identity' => [

        'company_id' => $company->id,
        'company_name' => $company->nama_perusahaan,
        'membership_type' => $company->membership_type,
        'country_name' => $company->country_name,
        'country_code' => $company->country_code,
        'city' => $company->city,
        'sector' => $company->sektor,
        'leader' => $company->pimpinan,
        'employees' => $company->tenaga_kerja,

    ],
    'metadata' => $metadata,

    'summary' => $summary,

    'passport' => $passport,
 /*
    |--------------------------------------------------------------------------
    | Business Intelligence
    |--------------------------------------------------------------------------
    */

    'role' => $role,

'ecosystem' => $ecosystem,

'business_needs' => $businessNeeds,

'matching' => $matching,

'build_supply_chain' => $buildSupplyChain,

'scores' => $scores,

'recommendations' => $recommendations,

'statistics' => $statistics,

]);

}
}