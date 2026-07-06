<?php

declare(strict_types=1);

namespace App\Services\Company\Intelligence;

use App\Models\Company;
use App\Services\Trade\Intelligence\Capability\CapabilityOrchestrator;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Company Capability Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Builds the Company Capability Passport by consolidating all capability
 * information stored in the Company Profile.
 *
 * The service converts operational company data into standardized capability
 * intelligence used throughout the DIGESTEX ecosystem.
 *
 * Responsibilities
 * --------------------------------------------------------------------------
 * ✓ Build Capability Passport
 * ✓ Calculate Capability Score
 * ✓ Produce Capability Summary
 * ✓ Supply data for Company Intelligence
 *
 * Data Sources
 * --------------------------------------------------------------------------
 * • company_products
 * • company_machines
 * • company_capacities
 * • company_moqs
 * • company_lead_times
 *
 * Trade Intelligence
 * --------------------------------------------------------------------------
 * This service integrates with Trade Capability Intelligence to enrich the
 * Digital Company Passport.
 *
 * Used By
 * --------------------------------------------------------------------------
 * • CompanyIntelligenceOrchestrator
 * • Digital Company Passport
 * • Executive Dashboard
 * • Company Comparison
 * • Matching Intelligence
 * • Executive AI
 *
 * Response Standard
 * --------------------------------------------------------------------------
 * Every Company Intelligence Service returns:
 *
 * [
 *     'score' => [],
 *     'passport' => [],
 *     'summary' => [],
 * ]
 */
class CompanyCapabilityService
{
    public function __construct(
        /**
         * Trade Capability Intelligence Orchestrator.
         *
         * Reuses the capability framework that has already been
         * developed under Trade Intelligence.
         */
        protected CapabilityOrchestrator $capability,
    ) {
    }
        /**
     * --------------------------------------------------------------------------
     * Capability Score
     * --------------------------------------------------------------------------
     *
     * Calculates the overall manufacturing capability score based on
     * available company operational data.
     *
     * Scoring Version : v1.0
     */
    protected function capabilityScore(Company $company): array
    {
        /*
        |--------------------------------------------------------------------------
        | Score Components
        |--------------------------------------------------------------------------
        */

        $products = $company->products->count() > 0 ? 20 : 0;

        $machines = $company->machines->count() > 0 ? 20 : 0;

        $capacities = $company->capacities->count() > 0 ? 20 : 0;

        $moqs = $company->moqs->count() > 0 ? 20 : 0;

        $leadTimes = $company->leadTimes->count() > 0 ? 20 : 0;

        /*
        |--------------------------------------------------------------------------
        | Overall Score
        |--------------------------------------------------------------------------
        */

        $overall =

            $products +
            $machines +
            $capacities +
            $moqs +
            $leadTimes;

        /*
        |--------------------------------------------------------------------------
        | Executive Level
        |--------------------------------------------------------------------------
        */

        return [

            'overall' => $overall,

            'level' => $this->scoreLevel($overall),

            'rating' => $this->scoreRating($overall),

            'components' => [

                'products' => $products,

                'machines' => $machines,

                'capacity' => $capacities,

                'moq' => $moqs,

                'lead_time' => $leadTimes,

            ],

        ];
    }
    /**
     * --------------------------------------------------------------------------
     * Capability Passport
     * --------------------------------------------------------------------------
     *
     * Builds the Digital Capability Passport from the company's
     * operational data.
     *
     * This passport will later be enriched by the Trade Capability
     * Intelligence Framework.
     */
    protected function capabilityPassport(Company $company): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Manufacturing
            |--------------------------------------------------------------------------
            */

            'products' => [

                'total' => $company->products->count(),

                'items' => $company->products,
            ],

            /*
            |--------------------------------------------------------------------------
            | Machinery
            |--------------------------------------------------------------------------
            */

            'machines' => [

                'total' => $company->machines->count(),

                'items' => $company->machines,
            ],

            /*
            |--------------------------------------------------------------------------
            | Production Capacity
            |--------------------------------------------------------------------------
            */

            'capacities' => [

                'total' => $company->capacities->count(),

                'items' => $company->capacities,
            ],

            /*
            |--------------------------------------------------------------------------
            | Minimum Order Quantity
            |--------------------------------------------------------------------------
            */

            'moqs' => [

                'total' => $company->moqs->count(),

                'items' => $company->moqs,
            ],

            /*
            |--------------------------------------------------------------------------
            | Lead Time
            |--------------------------------------------------------------------------
            */

            'lead_times' => [

                'total' => $company->leadTimes->count(),

                'items' => $company->leadTimes,
            ],

            /*
            |--------------------------------------------------------------------------
            | Trade Intelligence
            |--------------------------------------------------------------------------
            |
            | Reserved for integration with
            | CapabilityOrchestrator.
            |
            */

            'trade_capability' => [

                'material' => [],

                'production' => [],

                'development' => [],

                'innovation' => [],

                'sampling' => [],

                'commercial' => [],

                'sustainability' => [],

            ],

        ];
    }
        /**
     * --------------------------------------------------------------------------
     * Executive Summary
     * --------------------------------------------------------------------------
     *
     * High-level summary for Executive Dashboard,
     * Company Passport and Executive AI.
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
            | Operational Statistics
            |--------------------------------------------------------------------------
            */

            'products' => $passport['products']['total'],

            'machines' => $passport['machines']['total'],

            'capacities' => $passport['capacities']['total'],

            'moqs' => $passport['moqs']['total'],

            'lead_times' => $passport['lead_times']['total'],

            /*
            |--------------------------------------------------------------------------
            | Completion
            |--------------------------------------------------------------------------
            */

            'completion' => round(

                collect([

                    $passport['products']['total'] > 0,

                    $passport['machines']['total'] > 0,

                    $passport['capacities']['total'] > 0,

                    $passport['moqs']['total'] > 0,

                    $passport['lead_times']['total'] > 0,

                ])->filter()->count() / 5 * 100

            ),

        ];
    }
        /**
     * --------------------------------------------------------------------------
     * Complete Capability Intelligence
     * --------------------------------------------------------------------------
     *
     * Standard response for Company Intelligence Framework.
     */
    public function all(
        Company $company
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Capability Score
        |--------------------------------------------------------------------------
        */

        $score = $this->capabilityScore($company);

        /*
        |--------------------------------------------------------------------------
        | Capability Passport
        |--------------------------------------------------------------------------
        */

        $passport = $this->capabilityPassport($company);

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
        | Standard Response
        |--------------------------------------------------------------------------
        */

        return [

            'score' => $score,

            'passport' => $passport,

            'summary' => $summary,

        ];
    }
}
    