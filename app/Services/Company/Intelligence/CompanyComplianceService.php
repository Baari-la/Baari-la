<?php

declare(strict_types=1);

namespace App\Services\Company\Intelligence;

use App\Models\Company;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Company Compliance Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Builds the Company Compliance Passport by consolidating all compliance
 * information maintained by the company.
 *
 * This service transforms legal, certification and sustainability data into
 * standardized Compliance Intelligence used throughout the DIGESTEX
 * ecosystem.
 *
 * Responsibilities
 * --------------------------------------------------------------------------
 * ✓ Build Compliance Passport
 * ✓ Calculate Compliance Score
 * ✓ Produce Executive Compliance Summary
 * ✓ Supply Compliance Intelligence
 *
 * Data Sources
 * --------------------------------------------------------------------------
 * • company_certifications
 * • company_social_compliances
 * • company_environmental_compliances
 * • company_traceability
 * • company_audits
 *
 * Used By
 * --------------------------------------------------------------------------
 * • CompanyIntelligenceOrchestrator
 * • Digital Company Passport
 * • Executive Dashboard
 * • Supplier Comparison
 * • Buyer Confidence Engine
 * • Executive AI
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
class CompanyComplianceService
{
    public function __construct()
    {
        //
        // Reserved for future dependency injection.
        //
        // Example:
        //
        // CompanyComplianceRepository
        // ComplianceRuleEngine
        // SustainabilityScoreService
        // AuditAnalyticsService
        //
    }
        /**
     * --------------------------------------------------------------------------
     * Compliance Score
     * --------------------------------------------------------------------------
     *
     * Calculates the overall compliance score based on
     * company compliance information.
     *
     * Scoring Version : v1.0
     */
    protected function complianceScore(Company $company): array
    {
        /*
        |--------------------------------------------------------------------------
        | Score Components
        |--------------------------------------------------------------------------
        */
        /**
 * Company relations must already be eager loaded.
 *
 * Controller:
 * $company->loadPassportRelations();
 */

        $certifications = $company->certifications->count() > 0 ? 20 : 0;

        $social = $company->socialCompliances->count() > 0 ? 20 : 0;

        $environmental = $company->environmentalCompliances->count() > 0 ? 20 : 0;

        $traceability = $company->traceabilityRecords->count() > 0 ? 20 : 0;

        $audits = $company->audits->count() > 0 ? 20 : 0;

        /*
        |--------------------------------------------------------------------------
        | Overall Score
        |--------------------------------------------------------------------------
        */

        $overall =
            $certifications +
            $social +
            $environmental +
            $traceability +
            $audits;

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */
        return [
            'overall' => $overall,
            'level' => $this->scoreLevel($overall),
            'rating' => $this->scoreRating($overall),
            'components' => [
            'certifications' => $certifications,
            'social' => $social,
            'environmental' => $environmental,
            'traceability' => $traceability,
            'audits' => $audits,
            ],
        ];
    }

        /**
     * --------------------------------------------------------------------------
     * Compliance Passport
     * --------------------------------------------------------------------------
     *
     * Builds the Digital Compliance Passport from the company's
     * compliance information.
     *
     * This passport represents the company's compliance readiness
     * for global buyers and international supply chains.
     */
    protected function compliancePassport(
        Company $company
    ): array {

        return [

            /*
            |--------------------------------------------------------------------------
            | Certifications
            |--------------------------------------------------------------------------
            */

            'certifications' => [

                'total' => $company->certifications->count(),

                'items' => $company->certifications,

            ],

            /*
            |--------------------------------------------------------------------------
            | Social Compliance
            |--------------------------------------------------------------------------
            */
            'social' => [
                'total' => $company->socialCompliances->count(),
                'items' => $company->socialCompliances,
            ],

            /*
            |--------------------------------------------------------------------------
            | Environmental Compliance
            |--------------------------------------------------------------------------
            */
            'environmental' => [
                'total' => $company->environmentalCompliances->count(),
                'items' => $company->environmentalCompliances,
            ],

            /*
            |--------------------------------------------------------------------------
            | Traceability
            |--------------------------------------------------------------------------
            */

            'traceability' => [

                'total' => $company->traceabilityRecords->count(),

                'items' => $company->traceabilityRecords,

            ],

            /*
            |--------------------------------------------------------------------------
            | Audits
            |--------------------------------------------------------------------------
            */

            'audits' => [

                'total' => $company->audits->count(),

                'items' => $company->audits,

            ],

            /*
            |--------------------------------------------------------------------------
            | Compliance Status
            |--------------------------------------------------------------------------
            */

            'status' => [

                'verification' => $company->verification_status,

                'last_verified_at' => optional(
                    $company->last_verified_at
                )?->toDateString(),

                'is_verified' => $company->isVerifiedProfile(),

                'is_company_managed' => $company->isCompanyManaged(),

                'is_claimed' => $company->isClaimed(),

            ],

        ];
    }
        /**
     * --------------------------------------------------------------------------
     * Executive Summary
     * --------------------------------------------------------------------------
     *
     * High-level compliance summary for Executive Dashboard,
     * Digital Company Passport and Executive AI.
     */
    protected function executiveSummary(
        Company $company,
        array $score,
        array $passport,
    ): array {

        return [

            /*
            |--------------------------------------------------------------------------
            | Executive Overview
            |--------------------------------------------------------------------------
            */

            'company_id' => $company->id,
            'company_name' => $company->nama_perusahaan,
            'overall_score' => $score['overall'],
            'level' => $score['level'],
            'rating' => $score['rating'],
            'framework' => 'Company Compliance Passport',
            'version' => '1.0',

            /*
            |--------------------------------------------------------------------------
            | Compliance Statistics
            |--------------------------------------------------------------------------
            */
            'certifications' => $passport['certifications']['total'],
            'social_compliances' => $passport['social']['total'],
            'environmental_compliances' => $passport['environmental']['total'],
            'traceability_records' => $passport['traceability']['total'],
            'audits' => $passport['audits']['total'],
            /*
            |--------------------------------------------------------------------------
            | Verification
            |--------------------------------------------------------------------------
            */

            'verification_status' => $passport['status']['verification'],

            'last_verified_at' => $passport['status']['last_verified_at'],

            /*
            |--------------------------------------------------------------------------
            | Completion
            |--------------------------------------------------------------------------
            */

            'completion' => round(

                collect([

                    $passport['certifications']['total'] > 0,
                    $passport['social']['total'] > 0,
                    $passport['environmental']['total'] > 0,
                    $passport['traceability']['total'] > 0,
                    $passport['audits']['total'] > 0,
                ])->filter()->count() / 5 * 100

            ),

        ];
    }
        /**
     * --------------------------------------------------------------------------
     * Executive Level
     * --------------------------------------------------------------------------
     */
    protected function scoreLevel(int $score): string
    {
        return match (true) {

            $score >= 95 => 'World Class',

            $score >= 90 => 'Excellent',

            $score >= 80 => 'Export Ready',

            $score >= 70 => 'Developing',

            $score >= 60 => 'Emerging',

            default => 'Basic',

        };
    }
        /**
     * --------------------------------------------------------------------------
     * Executive Rating
     * --------------------------------------------------------------------------
     */
    protected function scoreRating(int $score): string
    {
        return match (true) {

            $score >= 95 => 'A+',
            $score >= 90 => 'A',
            $score >= 80 => 'B+',
            $score >= 70 => 'B',
            $score >= 60 => 'C',
            default => 'D',
        };
    }
        /**
     * --------------------------------------------------------------------------
     * Complete Compliance Intelligence
     * --------------------------------------------------------------------------
     *
     * Standard response for Company Intelligence Framework.
     */
    public function all(
        Company $company
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Compliance Score
        |--------------------------------------------------------------------------
        */

        $score = $this->complianceScore($company);

        /*
        |--------------------------------------------------------------------------
        | Compliance Passport
        |--------------------------------------------------------------------------
        */
        $passport = $this->compliancePassport($company);

        /*
        |--------------------------------------------------------------------------
        | Executive Summary
        |--------------------------------------------------------------------------
        */
        $summary = $this->executiveSummary(
            company: $company,
            score: $score,
            passport: $passport,
        );

        /*
        |--------------------------------------------------------------------------
        | Standard Intelligence Response
        |--------------------------------------------------------------------------
        */
        return [
            'score' => $score,
            'passport' => $passport,
            'summary' => $summary,
        ];
    }
}