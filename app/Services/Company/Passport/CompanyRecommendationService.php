<?php

declare(strict_types=1);

namespace App\Services\Company\Intelligenc\Passport;

use App\Models\Company;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Company Recommendation Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Generates business recommendations based on Company Intelligence.
 *
 * Unlike other Intelligence Services, this service does NOT calculate
 * business scores.
 *
 * Instead, it analyzes the output produced by:
 *
 * • CompanyCapabilityService
 * • CompanyComplianceService
 * • CompanyMarketService
 * • CompanySupplyChainService
 * • CompanyReadinessService
 * • CompanyScoreService
 *
 * and transforms those insights into actionable business recommendations.
 *
 * Responsibilities
 * --------------------------------------------------------------------------
 * ✓ Generate Capability Recommendations
 * ✓ Generate Compliance Recommendations
 * ✓ Generate Market Recommendations
 * ✓ Generate Supply Chain Recommendations
 * ✓ Generate Business Readiness Recommendations
 * ✓ Generate Executive Recommendations
 *
 * This service NEVER:
 * --------------------------------------------------------------------------
 * ✗ Reads database directly
 * ✗ Calculates business scores
 * ✗ Performs AI reasoning
 * ✗ Executes business rules outside recommendations
 *
 * Used By
 * --------------------------------------------------------------------------
 * • CompanyPassportAssembler
 * • Digital Company Passport
 * • Executive Dashboard
 * • Executive AI
 * • Supplier Improvement Program
 * • Buyer Readiness Program
 *
 * Response Standard
 * --------------------------------------------------------------------------
 * Every Company Intelligence Service returns:
 *
 * [
 *      'score' => [],
 *      'passport' => [],
 *      'summary' => [],
 * ]
 *
 * Version
 * --------------------------------------------------------------------------
 * DIGESTEX Company Intelligence Framework v1.0
 */
class CompanyRecommendationService
{
    public function __construct(

        protected CompanyCapabilityService $capability,

        protected CompanyComplianceService $compliance,

        protected CompanyMarketService $market,

        protected CompanySupplyChainService $supplyChain,

        protected CompanyReadinessService $readiness,

        protected CompanyScoreService $score,

    ) {
    }
    /**
     * --------------------------------------------------------------------------
     * Capability Recommendations
     * --------------------------------------------------------------------------
     *
     * Generate recommendations based on Capability Intelligence.
     */
    protected function capabilityRecommendations(
        Company $company
    ): array {

        $capability = $this->capability->all($company);

        $passport = $capability['passport'];

        $recommendations = [];

        /*
        |--------------------------------------------------------------------------
        | Products
        |--------------------------------------------------------------------------
        */

        if ($passport['products']['total'] === 0) {

            $recommendations[] = [

                'priority' => 'critical',

                'category' => 'Capability',

                'title' => 'Complete Product Portfolio',

                'description' =>
                    'Add manufactured products to improve supplier visibility.',

                'action' => 'Add Products',

            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Machinery
        |--------------------------------------------------------------------------
        */

        if ($passport['machines']['total'] === 0) {

            $recommendations[] = [

                'priority' => 'high',

                'category' => 'Capability',

                'title' => 'Add Machinery Information',

                'description' =>
                    'Provide production machinery information to strengthen manufacturing credibility.',

                'action' => 'Add Machines',

            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Capacity
        |--------------------------------------------------------------------------
        */

        if ($passport['capacities']['total'] === 0) {

            $recommendations[] = [

                'priority' => 'high',

                'category' => 'Capability',

                'title' => 'Specify Production Capacity',

                'description' =>
                    'Production capacity helps buyers evaluate manufacturing capability.',

                'action' => 'Add Capacity',

            ];
        }

        /*
        |--------------------------------------------------------------------------
        | MOQ
        |--------------------------------------------------------------------------
        */

        if ($passport['moqs']['total'] === 0) {

            $recommendations[] = [

                'priority' => 'medium',

                'category' => 'Capability',

                'title' => 'Specify Minimum Order Quantity',

                'description' =>
                    'MOQ information helps buyers prepare RFQs more accurately.',

                'action' => 'Add MOQ',

            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Lead Time
        |--------------------------------------------------------------------------
        */

        if ($passport['lead_times']['total'] === 0) {

            $recommendations[] = [

                'priority' => 'medium',

                'category' => 'Capability',

                'title' => 'Specify Production Lead Time',

                'description' =>
                    'Lead time improves planning accuracy for international buyers.',

                'action' => 'Add Lead Time',

            ];
        }

        return $recommendations;
    }
        /**
     * --------------------------------------------------------------------------
     * Compliance Recommendations
     * --------------------------------------------------------------------------
     *
     * Generate recommendations based on Compliance Intelligence.
     */
    protected function complianceRecommendations(
        Company $company
    ): array {

        $compliance = $this->compliance->all($company);

        $passport = $compliance['passport'];

        $recommendations = [];

        /*
        |--------------------------------------------------------------------------
        | Certifications
        |--------------------------------------------------------------------------
        */

        if ($passport['certifications']['total'] === 0) {

            $recommendations[] = [

                'priority' => 'critical',

                'category' => 'Compliance',

                'title' => 'Upload Company Certifications',

                'description' =>
                    'Add ISO, OEKO-TEX, GRS or other certifications to increase buyer confidence.',

                'action' => 'Add Certifications',

            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Social Compliance
        |--------------------------------------------------------------------------
        */

        if ($passport['social']['total'] === 0) {

            $recommendations[] = [

                'priority' => 'high',

                'category' => 'Compliance',

                'title' => 'Complete Social Compliance',

                'description' =>
                    'Provide labor and workplace compliance information.',

                'action' => 'Add Social Compliance',

            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Environmental Compliance
        |--------------------------------------------------------------------------
        */

        if ($passport['environmental']['total'] === 0) {

            $recommendations[] = [

                'priority' => 'high',

                'category' => 'Compliance',

                'title' => 'Complete Environmental Compliance',

                'description' =>
                    'Add environmental management information and sustainability initiatives.',

                'action' => 'Add Environmental Compliance',

            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Traceability
        |--------------------------------------------------------------------------
        */

        if ($passport['traceability']['total'] === 0) {

            $recommendations[] = [

                'priority' => 'medium',

                'category' => 'Compliance',

                'title' => 'Provide Traceability Information',

                'description' =>
                    'Traceability improves transparency across the textile supply chain.',

                'action' => 'Add Traceability',

            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Audits
        |--------------------------------------------------------------------------
        */

        if ($passport['audits']['total'] === 0) {

            $recommendations[] = [

                'priority' => 'medium',

                'category' => 'Compliance',

                'title' => 'Upload Audit Information',

                'description' =>
                    'Audit reports strengthen supplier credibility for international buyers.',

                'action' => 'Add Audit Records',

            ];
        }

        return $recommendations;
    }
    /**
     * --------------------------------------------------------------------------
     * Market Recommendations
     * --------------------------------------------------------------------------
     *
     * Generate recommendations based on Market Intelligence.
     */
    protected function marketRecommendations(
        Company $company
    ): array {

        $market = $this->market->all($company);

        $passport = $market['passport'];

        $recommendations = [];

        /*
        |--------------------------------------------------------------------------
        | Export Markets
        |--------------------------------------------------------------------------
        */

        if ($passport['markets']['total'] === 0) {

            $recommendations[] = [

                'priority' => 'critical',

                'category' => 'Market',

                'title' => 'Add Export Markets',

                'description' =>
                    'Specify export destination countries to improve buyer confidence and global visibility.',

                'action' => 'Add Export Markets',

            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Product Portfolio
        |--------------------------------------------------------------------------
        */

        if ($passport['products']['total'] === 0) {

            $recommendations[] = [

                'priority' => 'high',

                'category' => 'Market',

                'title' => 'Complete Product Portfolio',

                'description' =>
                    'Associate products with target export markets to improve matching quality.',

                'action' => 'Update Products',

            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Country Information
        |--------------------------------------------------------------------------
        */

        if (empty($passport['country']['code'])) {

            $recommendations[] = [

                'priority' => 'medium',

                'category' => 'Market',

                'title' => 'Complete Country Information',

                'description' =>
                    'Country information improves international supplier identification.',

                'action' => 'Update Company Location',

            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Export Experience
        |--------------------------------------------------------------------------
        */

        if (empty($passport['export_experience']['markets'])) {

            $recommendations[] = [

                'priority' => 'high',

                'category' => 'Market',

                'title' => 'Add Export Experience',

                'description' =>
                    'Share export market experience to strengthen credibility with global buyers.',

                'action' => 'Update Export Markets',

            ];
        }

        /*
        |--------------------------------------------------------------------------
        | International Presence
        |--------------------------------------------------------------------------
        */

        if (!$passport['status']['international_presence']) {

            $recommendations[] = [

                'priority' => 'medium',

                'category' => 'Market',

                'title' => 'Expand International Presence',

                'description' =>
                    'Increasing international market coverage improves business opportunities.',

                'action' => 'Develop New Export Markets',

            ];
        }

        return $recommendations;
    }
        /**
     * --------------------------------------------------------------------------
     * Supply Chain Recommendations
     * --------------------------------------------------------------------------
     *
     * Generate recommendations based on Supply Chain Intelligence.
     */
    protected function supplyChainRecommendations(
        Company $company
    ): array {

        $supplyChain = $this->supplyChain->all($company);

        $passport = $supplyChain['passport'];

        $recommendations = [];

        /*
        |--------------------------------------------------------------------------
        | Production Capacity
        |--------------------------------------------------------------------------
        */

        if ($passport['capacities']['total'] === 0) {

            $recommendations[] = [

                'priority' => 'critical',

                'category' => 'Supply Chain',

                'title' => 'Specify Production Capacity',

                'description' =>
                    'Production capacity information helps buyers evaluate manufacturing capability.',

                'action' => 'Add Production Capacity',

            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Machinery
        |--------------------------------------------------------------------------
        */

        if ($passport['machines']['total'] === 0) {

            $recommendations[] = [

                'priority' => 'high',

                'category' => 'Supply Chain',

                'title' => 'Add Machinery Information',

                'description' =>
                    'Manufacturing equipment information increases buyer confidence.',

                'action' => 'Add Machines',

            ];
        }

        /*
        |--------------------------------------------------------------------------
        | MOQ
        |--------------------------------------------------------------------------
        */

        if ($passport['moqs']['total'] === 0) {

            $recommendations[] = [

                'priority' => 'medium',

                'category' => 'Supply Chain',

                'title' => 'Specify Minimum Order Quantity',

                'description' =>
                    'MOQ information enables accurate RFQ preparation.',

                'action' => 'Add MOQ',

            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Lead Time
        |--------------------------------------------------------------------------
        */

        if ($passport['lead_times']['total'] === 0) {

            $recommendations[] = [

                'priority' => 'high',

                'category' => 'Supply Chain',

                'title' => 'Specify Lead Time',

                'description' =>
                    'Lead time information supports production planning and delivery scheduling.',

                'action' => 'Add Lead Time',

            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Factory
        |--------------------------------------------------------------------------
        */

        if ($passport['factories']['total'] === 0) {

            $recommendations[] = [

                'priority' => 'low',

                'category' => 'Supply Chain',

                'title' => 'Register Factory Information',

                'description' =>
                    'Factory information provides additional transparency for buyers.',

                'action' => 'Add Factory',

            ];
        }

        return $recommendations;
    }
        /**
     * --------------------------------------------------------------------------
     * Business Readiness Recommendations
     * --------------------------------------------------------------------------
     *
     * Generate recommendations based on Business Readiness Intelligence.
     */
    protected function businessReadinessRecommendations(
        Company $company
    ): array {

        $readiness = $this->readiness->all($company);

        $passport = $readiness['passport'];

        $recommendations = [];

        /*
        |--------------------------------------------------------------------------
        | Contacts
        |--------------------------------------------------------------------------
        */

        if ($passport['contacts']['total'] === 0) {

            $recommendations[] = [

                'priority' => 'critical',

                'category' => 'Business Readiness',

                'title' => 'Complete Business Contacts',

                'description' =>
                    'Provide complete contact information so buyers can easily reach your company.',

                'action' => 'Add Contacts',

            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Company Links
        |--------------------------------------------------------------------------
        */

        if ($passport['commercial']['links']['total'] === 0) {

            $recommendations[] = [
                'priority' => 'high',
                'category' => 'Business Readiness',
                'title' => 'Add Company Website & Social Links',
                'description' =>
                    'A complete online presence improves buyer trust and supplier visibility.',
                'action' => 'Add Company Links',

            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Images
        |--------------------------------------------------------------------------
        */

        if ($passport['commercial']['images']['total'] === 0) {

            $recommendations[] = [
                'priority' => 'high',
                'category' => 'Business Readiness',
                'title' => 'Upload Company Images',
                'description' =>
                    'Factory, product and company images help buyers understand your business.',
                'action' => 'Upload Images',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Export Markets
        |--------------------------------------------------------------------------
        */
        if ($passport['export']['markets']['total'] === 0) {
            $recommendations[] = [
                'priority' => 'medium',
                'category' => 'Business Readiness',
                'title' => 'Expand Export Market Profile',
                'description' =>
                    'Include export destinations to improve international visibility.',
                'action' => 'Update Export Markets',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Website
        |--------------------------------------------------------------------------
        */

        if (!$passport['digital']['website_available']) {
            $recommendations[] = [
                'priority' => 'medium',
                'category' => 'Business Readiness',
                'title' => 'Add Company Website',
                'description' =>
                    'A professional website increases buyer confidence.',
                'action' => 'Add Website',
            ];
        }

        return $recommendations;
    }
        /**
     * --------------------------------------------------------------------------
     * Executive Recommendations
     * --------------------------------------------------------------------------
     *
     * Consolidates recommendations from all intelligence modules.
     */
    protected function executiveRecommendations(
        Company $company
    ): array {
        $recommendations = array_merge(
            $this->capabilityRecommendations($company),
            $this->complianceRecommendations($company),
            $this->marketRecommendations($company),
            $this->supplyChainRecommendations($company),
            $this->businessReadinessRecommendations($company),
        );
        /*
        |--------------------------------------------------------------------------
        | Sort by Priority
        |--------------------------------------------------------------------------
        */
        $priorityOrder = [
            'critical' => 1,
            'high' => 2,
            'medium' => 3,
            'low' => 4,
        ];

        usort(
            $recommendations,
            fn ($a, $b)
                => $priorityOrder[$a['priority']]
                <=> $priorityOrder[$b['priority']]
        );
        return $recommendations;
    }
        /**
     * --------------------------------------------------------------------------
     * Complete Company Recommendations
     * --------------------------------------------------------------------------
     *
     * Standard response for Company Intelligence Framework.
     */
    public function all(
        Company $company
    ): array {
        $recommendations =
            $this->executiveRecommendations($company);
        return [

            /*
            |--------------------------------------------------------------------------
            | Recommendation Statistics
            |--------------------------------------------------------------------------
            */
            'score' => [

                'total' => count($recommendations),
            ],

            /*
            |--------------------------------------------------------------------------
            | Recommendation Passport
            |--------------------------------------------------------------------------
            */
            'passport' => [

                'recommendations' => $recommendations,
            ],

            /*
            |--------------------------------------------------------------------------
            | Executive Summary
            |--------------------------------------------------------------------------
            */
            'summary' => [
                'total' => count($recommendations),
                'critical' => collect($recommendations)
                    ->where('priority', 'critical')
                    ->count(),
                'high' => collect($recommendations)
                    ->where('priority', 'high')
                    ->count(),
                'medium' => collect($recommendations)
                    ->where('priority', 'medium')
                    ->count(),
                'low' => collect($recommendations)
                    ->where('priority', 'low')
                    ->count(),
            ],
        ];
    }
}