<?php

declare(strict_types=1);

namespace App\Services\Company\Intelligence;

use App\Models\Company;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Company Business Readiness Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Builds the Business Readiness Passport by evaluating how prepared
 * a company is to engage with global buyers, sourcing teams and
 * international business opportunities.
 *
 * Unlike Capability or Supply Chain, this service focuses on
 * business readiness rather than manufacturing readiness.
 *
 * Responsibilities
 * --------------------------------------------------------------------------
 * ✓ Build Business Readiness Passport
 * ✓ Calculate Business Readiness Score
 * ✓ Produce Executive Readiness Summary
 * ✓ Supply Business Readiness Intelligence
 *
 * Data Sources
 * --------------------------------------------------------------------------
 * • company_contacts
 * • company_links
 * • company_images
 * • company_markets
 * • company_products
 * • company_certifications
 * • companies.verification_status
 *
 * Used By
 * --------------------------------------------------------------------------
 * • CompanyIntelligenceOrchestrator
 * • Digital Company Passport
 * • Executive Dashboard
 * • Buyer Matching
 * • Business Opportunity Engine
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
class CompanyReadinessService
{
    public function __construct()
    {
        //
        // Reserved for future dependency injection.
        //
        // Future integrations:
        //
        // CRM Intelligence
        // Buyer Readiness Engine
        // RFQ Intelligence
        // Supplier Readiness Analytics
        // Executive AI
        //
    }
        /**
     * --------------------------------------------------------------------------
     * Business Readiness Score
     * --------------------------------------------------------------------------
     *
     * Calculates the overall Business Readiness Score.
     *
     * Scoring Version : v1.0
     */
    protected function readinessScore(
        Company $company
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Score Components
        |--------------------------------------------------------------------------
        */

        $companyProfile =
            $company->products->count() > 0
                ? 20
                : 0;

        $contactReadiness =
            $company->contacts->count() > 0
                ? 20
                : 0;

        $commercialReadiness =
            (
                $company->links->count() > 0
                &&
                $company->images->count() > 0
            )
                ? 20
                : 0;

        $exportReadiness =
            (
                $company->markets->count() > 0
                ||
                filled($company->pasar_ekspor)
            )
                ? 20
                : 0;

        $digitalPresence =
            (
                filled($company->email_web)
                ||
                $company->links->count() > 0
            )
                ? 20
                : 0;

        /*
        |--------------------------------------------------------------------------
        | Overall Score
        |--------------------------------------------------------------------------
        */

        $overall =

            $companyProfile +

            $contactReadiness +

            $commercialReadiness +

            $exportReadiness +

            $digitalPresence;

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

                'company_profile' => $companyProfile,

                'contact_readiness' => $contactReadiness,

                'commercial_readiness' => $commercialReadiness,

                'export_readiness' => $exportReadiness,

                'digital_presence' => $digitalPresence,

            ],

        ];
    }
    /**
     * --------------------------------------------------------------------------
     * Business Readiness Passport
     * --------------------------------------------------------------------------
     *
     * Builds the Business Readiness Passport from the company's
     * commercial profile, communication channels and digital presence.
     */
    protected function readinessPassport(
        Company $company
    ): array {

        return [

            /*
            |--------------------------------------------------------------------------
            | Company Profile
            |--------------------------------------------------------------------------
            */

            'profile' => [

                'products' => [

                    'total' => $company->products->count(),

                    'items' => $company->products,

                ],

                'company_role' => $company->company_role,

                'category' => $company->category,

            ],

            /*
            |--------------------------------------------------------------------------
            | Contact Readiness
            |--------------------------------------------------------------------------
            */

            'contacts' => [

                'total' => $company->contacts->count(),

                'items' => $company->contacts,

                'phone' => $company->telepon,

                'email_web' => $company->email_web,

            ],

            /*
            |--------------------------------------------------------------------------
            | Commercial Readiness
            |--------------------------------------------------------------------------
            */

            'commercial' => [

                'links' => [

                    'total' => $company->links->count(),

                    'items' => $company->links,

                ],

                'images' => [

                    'total' => $company->images->count(),

                    'items' => $company->images,

                ],

            ],

            /*
            |--------------------------------------------------------------------------
            | Export Readiness
            |--------------------------------------------------------------------------
            */

            'export' => [

                'markets' => [

                    'total' => $company->markets->count(),

                    'items' => $company->markets,

                ],

                'export_markets' => $company->pasar_ekspor,

                'certifications' => [

                    'total' => $company->certifications->count(),

                    'items' => $company->certifications,

                ],

            ],

            /*
            |--------------------------------------------------------------------------
            | Digital Presence
            |--------------------------------------------------------------------------
            */

            'digital' => [

                'website_available' =>

                    filled($company->email_web),

                'images_available' =>

                    $company->images->count() > 0,

                'links_available' =>

                    $company->links->count() > 0,

            ],

            /*
            |--------------------------------------------------------------------------
            | Readiness Status
            |--------------------------------------------------------------------------
            */

            'status' => [

                'verification' =>

                    $company->verification_status,

                'claimed' =>

                    $company->isClaimed(),

                'verified_company' =>

                    $company->isVerifiedProfile(),

                'company_managed' =>

                    $company->isCompanyManaged(),

            ],

        ];
    }
    
        /**
     * --------------------------------------------------------------------------
     * Executive Summary
     * --------------------------------------------------------------------------
     *
     * Executive summary for Business Readiness.
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

            /*
            |--------------------------------------------------------------------------
            | Readiness Statistics
            |--------------------------------------------------------------------------
            */

            'products' =>

                $passport['profile']['products']['total'],

            'contacts' =>

                $passport['contacts']['total'],

            'links' =>

                $passport['commercial']['links']['total'],

            'images' =>

                $passport['commercial']['images']['total'],

            'markets' =>

                $passport['export']['markets']['total'],

            'certifications' =>

                $passport['export']['certifications']['total'],

            /*
            |--------------------------------------------------------------------------
            | Digital Presence
            |--------------------------------------------------------------------------
            */

            'website_available' =>

                $passport['digital']['website_available'],

            /*
            |--------------------------------------------------------------------------
            | Completion
            |--------------------------------------------------------------------------
            */

            'completion' => round(

                collect([

                    $passport['profile']['products']['total'] > 0,

                    $passport['contacts']['total'] > 0,

                    $passport['commercial']['links']['total'] > 0,

                    $passport['export']['markets']['total'] > 0,

                    $passport['digital']['website_available'],

                ])->filter()->count() / 5 * 100

            ),

        ];
    }
        /**
     * --------------------------------------------------------------------------
     * Executive Level
     * --------------------------------------------------------------------------
     */
    protected function scoreLevel(
        int $score
    ): string {

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
    protected function scoreRating(
        int $score
    ): string {

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
     * Complete Business Readiness Intelligence
     * --------------------------------------------------------------------------
     *
     * Standard response for Company Intelligence Framework.
     */
    public function all(
        Company $company
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Readiness Score
        |--------------------------------------------------------------------------
        */

        $score = $this->readinessScore($company);

        /*
        |--------------------------------------------------------------------------
        | Business Readiness Passport
        |--------------------------------------------------------------------------
        */

        $passport = $this->readinessPassport($company);

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