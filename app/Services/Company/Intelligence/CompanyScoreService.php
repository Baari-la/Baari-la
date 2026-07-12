<?php

declare(strict_types=1);

namespace App\Services\Company\Intelligence;

use App\Models\Company;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Company Executive Score Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Aggregates all Company Intelligence Services into a unified
 * Executive Intelligence Score.
 *
 * IMPORTANT
 * --------------------------------------------------------------------------
 * This service NEVER reads the database directly.
 *
 * It only aggregates results produced by:
 *
 * • CompanyCapabilityService
 * • CompanyComplianceService
 * • CompanyMarketService
 * • CompanySupplyChainService
 * • CompanyReadinessService
 *
 * Responsibilities
 * --------------------------------------------------------------------------
 * ✓ Calculate Executive Intelligence Score
 * ✓ Build Executive Score Breakdown
 * ✓ Determine Executive Level
 * ✓ Determine Executive Rating
 * ✓ Produce Executive Summary
 *
 * Used By
 * --------------------------------------------------------------------------
 * • CompanyIntelligenceOrchestrator
 * • Executive Dashboard
 * • Digital Company Passport
 * • Supplier Ranking
 * • Buyer Matching
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
class CompanyScoreService
{
    public function __construct(

        protected CompanyCapabilityService $capability,

        protected CompanyComplianceService $compliance,

        protected CompanyMarketService $market,

        protected CompanySupplyChainService $supplyChain,

        protected CompanyReadinessService $readiness,

    ) {
    }
        /**
     * --------------------------------------------------------------------------
     * Executive Intelligence Score
     * --------------------------------------------------------------------------
     *
     * Calculates the overall Executive Intelligence Score by
     * aggregating all Company Intelligence Services.
     */
    protected function executiveScore(
        Company $company
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Intelligence Scores
        |--------------------------------------------------------------------------
        */

        $capability = $this->capability
            ->all($company)['score'];

        $compliance = $this->compliance
            ->all($company)['score'];

        $market = $this->market
            ->all($company)['score'];

        $supplyChain = $this->supplyChain
            ->all($company)['score'];

        $readiness = $this->readiness
            ->all($company)['score'];

        /*
        |--------------------------------------------------------------------------
        | Overall Executive Score
        |--------------------------------------------------------------------------
        */

        $overall = round(

            (

                $capability['overall']

                +

                $compliance['overall']

                +

                $market['overall']

                +

                $supplyChain['overall']

                +

                $readiness['overall']

            ) / 5

        );

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return [

            'overall' => $overall,

            'level' => $this->scoreLevel($overall),

            'rating' => $this->scoreRating($overall),

            'capability' => $capability['overall'],

            'compliance' => $compliance['overall'],

            'market' => $market['overall'],

            'supply_chain' => $supplyChain['overall'],

            'business_readiness' => $readiness['overall'],

        ];
    }
    /**
     * --------------------------------------------------------------------------
     * Executive Score Breakdown
     * --------------------------------------------------------------------------
     *
     * Detailed breakdown of all intelligence scores.
     */
    protected function scoreBreakdown(
        Company $company
    ): array {

        $score = $this->executiveScore($company);

        return [

            'overall' => $score['overall'],

            'capability' => [

                'score' => $score['capability'],

                'weight' => 20,

            ],

            'compliance' => [

                'score' => $score['compliance'],

                'weight' => 20,

            ],

            'market' => [

                'score' => $score['market'],

                'weight' => 20,

            ],

            'supply_chain' => [

                'score' => $score['supply_chain'],

                'weight' => 20,

            ],

            'business_readiness' => [

                'score' => $score['business_readiness'],

                'weight' => 20,

            ],

        ];
    }
    /**
     * --------------------------------------------------------------------------
     * Executive Summary
     * --------------------------------------------------------------------------
     *
     * High-level executive summary for Digital Company Passport,
     * Executive Dashboard and Executive AI.
     */
    protected function executiveSummary(
        Company $company,
        array $score,
        array $breakdown,
    ): array {

        $areas = [

            'Capability' => $score['capability'],

            'Compliance' => $score['compliance'],

            'Market' => $score['market'],

            'Supply Chain' => $score['supply_chain'],

            'Business Readiness' => $score['business_readiness'],

        ];

        $bestArea = array_search(
            max($areas),
            $areas,
            true
        );

        $improvementArea = array_search(
            min($areas),
            $areas,
            true
        );

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
            | Executive Insights
            |--------------------------------------------------------------------------
            */

            'best_area' => $bestArea,

            'improvement_area' => $improvementArea,

            'score_breakdown' => $breakdown,

        ];
    }
    
        /**
     * --------------------------------------------------------------------------
     * Executive Level
     * --------------------------------------------------------------------------
     */
    protected function scoreLevel(
    int|float $score
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
    int|float $score
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
     * Complete Executive Intelligence Score
     * --------------------------------------------------------------------------
     *
     * Standard response for Company Intelligence Framework.
     */
    public function all(
        Company $company
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Executive Score
        |--------------------------------------------------------------------------
        */

        $score = $this->executiveScore($company);

        /*
        |--------------------------------------------------------------------------
        | Score Breakdown
        |--------------------------------------------------------------------------
        */

        $breakdown = $this->scoreBreakdown($company);

        /*
        |--------------------------------------------------------------------------
        | Executive Summary
        |--------------------------------------------------------------------------
        */

        $summary = $this->executiveSummary(

            company: $company,

            score: $score,

            breakdown: $breakdown,

        );

        /*
        |--------------------------------------------------------------------------
        | Standard Intelligence Response
        |--------------------------------------------------------------------------
        */

        return [

            'score' => $score,
            'passport' => [
            'breakdown' => $breakdown,
            ],
            'summary' => $summary,
        ];
    }
}