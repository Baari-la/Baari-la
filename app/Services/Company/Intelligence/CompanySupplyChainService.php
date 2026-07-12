<?php

declare(strict_types=1);

namespace App\Services\Company\Intelligence;

use App\Models\Company;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Company Supply Chain Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Builds the Company Supply Chain Passport by consolidating operational
 * readiness, production resources and manufacturing fulfillment capability.
 *
 * This service transforms production and logistics information into
 * standardized Supply Chain Intelligence used throughout the DIGESTEX
 * ecosystem.
 *
 * Responsibilities
 * --------------------------------------------------------------------------
 * ✓ Build Supply Chain Passport
 * ✓ Calculate Supply Chain Score
 * ✓ Produce Executive Supply Chain Summary
 * ✓ Supply Supply Chain Intelligence
 *
 * Data Sources
 * --------------------------------------------------------------------------
 * • company_capacities
 * • company_machines
 * • company_moqs
 * • company_lead_times
 * • company_factories (Roadmap)
 * • company_locations (Roadmap)
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
class CompanySupplyChainService
{
    public function __construct()
    {
        //
        // Reserved for future dependency injection.
        //
        // Future integrations:
        //
        // Logistics Intelligence
        // Production Planning
        // Factory Intelligence
        // Shipping Intelligence
        // Supply Chain Analytics
        //
    }

        /**
     * --------------------------------------------------------------------------
     * Supply Chain Score
     * --------------------------------------------------------------------------
     *
     * Calculates the overall Supply Chain Intelligence Score.
     *
     * Scoring Version : v1.0
     */
    protected function supplyChainScore(
        Company $company
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Score Components
        |--------------------------------------------------------------------------
        */

        $capacity =
            $company->capacities->count() > 0
                ? 20
                : 0;

        $machines =
            $company->machines->count() > 0
                ? 20
                : 0;

        $moqs =
            $company->moqs->count() > 0
                ? 20
                : 0;

        $leadTimes =
            $company->leadTimes->count() > 0
                ? 20
                : 0;

        /*
        |--------------------------------------------------------------------------
        | Factory Readiness
        |--------------------------------------------------------------------------
        */

       $factory =

    $company->locations
        ->where('location_type', 'factory')
        ->count() > 0

    ? 20

    : 0;

        /*
        |--------------------------------------------------------------------------
        | Overall Score
        |--------------------------------------------------------------------------
        */

        $overall =

            $capacity +

            $machines +

            $moqs +

            $leadTimes +

            $factory;

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

                'capacity' => $capacity,

                'machines' => $machines,

                'moq' => $moqs,

                'lead_time' => $leadTimes,

                'factory' => $factory,

            ],

        ];
    }
    /**
     * --------------------------------------------------------------------------
     * Supply Chain Passport
     * --------------------------------------------------------------------------
     *
     * Builds the Digital Supply Chain Passport from the company's
     * operational and manufacturing information.
     */
    protected function supplyChainPassport(
        Company $company
    ): array {

        return [

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
            | Machinery
            |--------------------------------------------------------------------------
            */

            'machines' => [

                'total' => $company->machines->count(),

                'items' => $company->machines,

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
            | Factory (Roadmap Ready)
            |--------------------------------------------------------------------------
            */

            'factories' => [

    'total' =>

        $company->locations
            ->where('location_type', 'factory')
            ->count(),

    'items' =>

        $company->locations
            ->where('location_type', 'factory')
            ->values(),

],

            /*
            |--------------------------------------------------------------------------
            | Supply Chain Status
            |--------------------------------------------------------------------------
            */

            'status' => [

                'production_ready' =>

                    $company->capacities->count() > 0,

                'accept_moq' =>

                    $company->moqs->count() > 0,

                'lead_time_available' =>

                    $company->leadTimes->count() > 0,

                'factory_registered' =>

                    $company->locations
                    ->where('location_type','factory')
                    ->count() > 0,

            ],

        ];
    }
          /**
     * --------------------------------------------------------------------------
     * Executive Summary
     * --------------------------------------------------------------------------
     *
     * Executive summary for Supply Chain Intelligence.
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
            | Supply Chain Statistics
            |--------------------------------------------------------------------------
            */

            'capacities' =>

                $passport['capacities']['total'],

            'machines' =>

                $passport['machines']['total'],

            'moqs' =>

                $passport['moqs']['total'],

            'lead_times' =>

                $passport['lead_times']['total'],

            'factories' =>

                $passport['factories']['total'],

            /*
            |--------------------------------------------------------------------------
            | Completion
            |--------------------------------------------------------------------------
            */

            'completion' => round(

                collect([

                    $passport['capacities']['total'] > 0,

                    $passport['machines']['total'] > 0,

                    $passport['moqs']['total'] > 0,

                    $passport['lead_times']['total'] > 0,

                    $passport['factories']['total'] > 0,

                ])->filter()->count() / 5 * 100

            ),

        ];
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
     * Complete Supply Chain Intelligence
     * --------------------------------------------------------------------------
     *
     * Standard response for Company Intelligence Framework.
     */
    public function all(
        Company $company
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Supply Chain Score
        |--------------------------------------------------------------------------
        */

        $score = $this->supplyChainScore($company);

        /*
        |--------------------------------------------------------------------------
        | Supply Chain Passport
        |--------------------------------------------------------------------------
        */

        $passport = $this->supplyChainPassport($company);

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

        $score >= 90 => 'Enterprise',

        $score >= 80 => 'Advanced',

        $score >= 70 => 'Developing',

        $score >= 60 => 'Basic',

        default => 'Starter',

    };
}
}