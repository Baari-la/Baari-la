<?php

declare(strict_types=1);

namespace App\Services\Company\Intelligence;

use App\Models\Company;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Company Market Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Builds the Company Market Passport by consolidating the company's
 * market presence, export experience and international business coverage.
 *
 * This service transforms market information into standardized
 * Market Intelligence used throughout the DIGESTEX ecosystem.
 *
 * Responsibilities
 * --------------------------------------------------------------------------
 * ✓ Build Market Passport
 * ✓ Calculate Market Score
 * ✓ Produce Executive Market Summary
 * ✓ Supply Market Intelligence
 *
 * Data Sources
 * --------------------------------------------------------------------------
 * • company_markets
 * • company_products
 * • companies.country_code
 * • companies.country_name
 * • companies.pasar_ekspor
 *
 * Used By
 * --------------------------------------------------------------------------
 * • CompanyIntelligenceOrchestrator
 * • Digital Company Passport
 * • Executive Dashboard
 * • Company Comparison
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
class CompanyMarketService
{
    public function __construct()
    {
        //
        // Reserved for future dependency injection.
        //
        // Future integrations:
        //
        // Trade Intelligence
        // Export Intelligence
        // Country Intelligence
        // HS Intelligence
        // Global Market Analytics
        //
    }
        /**
     * --------------------------------------------------------------------------
     * Market Score
     * --------------------------------------------------------------------------
     *
     * Calculates the overall Market Intelligence Score.
     *
     * Scoring Version : v1.0
     */
    protected function marketScore(
        Company $company
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Score Components
        |--------------------------------------------------------------------------
        */

        $markets =
            $company->markets->count() > 0
                ? 20
                : 0;

        $products =
            $company->products->count() > 0
                ? 20
                : 0;

        $country =
            filled($company->country_code)
                ? 20
                : 0;

        $exportExperience =
            filled($company->pasar_ekspor)
                ? 20
                : 0;

        $internationalPresence =
            (
                $company->markets->count() > 1
                ||
                filled($company->pasar_ekspor)
            )
                ? 20
                : 0;

        /*
        |--------------------------------------------------------------------------
        | Overall Score
        |--------------------------------------------------------------------------
        */

        $overall =

            $markets +

            $products +

            $country +

            $exportExperience +

            $internationalPresence;

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

                'markets' => $markets,

                'products' => $products,

                'country' => $country,

                'export_experience' => $exportExperience,

                'international_presence' => $internationalPresence,

            ],

        ];
    }
    /**
     * --------------------------------------------------------------------------
     * Market Passport
     * --------------------------------------------------------------------------
     *
     * Builds the Digital Market Passport from the company's
     * market profile and international business coverage.
     */
    protected function marketPassport(
    Company $company
): array {

    /*
    |--------------------------------------------------------------------------
    | Normalized Markets
    |--------------------------------------------------------------------------
    */

    $markets = $company->markets;

    $exportMarkets = $markets
        ->filter(function ($market) {
            return strtolower(
                trim((string) $market->market_type)
            ) === 'export';
        })
        ->values();

    /*
    |--------------------------------------------------------------------------
    | Export Market Items
    |--------------------------------------------------------------------------
    */

    $exportMarketItems = $exportMarkets
        ->map(function ($market) {
            return [
                'id' => $market->id,
                'country_name' => $market->country_name,
                'market_type' => $market->market_type,
            ];
        })
        ->values()
        ->all();

    return [

        /*
        |--------------------------------------------------------------------------
        | Markets
        |--------------------------------------------------------------------------
        */

        'markets' => [

            'total' => $markets->count(),

            'items' => $markets,

        ],

        /*
        |--------------------------------------------------------------------------
        | Product Portfolio
        |--------------------------------------------------------------------------
        */

        'products' => [

            'total' => $company->products->count(),

            'items' => $company->products,

        ],

        /*
        |--------------------------------------------------------------------------
        | Country Information
        |--------------------------------------------------------------------------
        */

        'country' => [

            'code' => $company->country_code,

            'name' => $company->country_name,

            'city' => $company->city,

        ],

        /*
        |--------------------------------------------------------------------------
        | Export Experience
        |--------------------------------------------------------------------------
        */

        'export_experience' => [

            'market_count' => $exportMarkets->count(),

            'markets' => $exportMarketItems,

        ],

        /*
        |--------------------------------------------------------------------------
        | Market Status
        |--------------------------------------------------------------------------
        */

        'status' => [

            'international_presence' =>
                $exportMarkets->isNotEmpty(),

            'has_export_market' =>
                $exportMarkets->isNotEmpty(),

            'verified_company' =>
                $company->isVerifiedProfile(),

        ],

    ];
}
        /**
     * --------------------------------------------------------------------------
     * Executive Summary
     * --------------------------------------------------------------------------
     *
     * Executive summary for Market Intelligence.
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
            | Market Statistics
            |--------------------------------------------------------------------------
            */

            'markets' =>

                $passport['markets']['total'],

            'products' =>

                $passport['products']['total'],

            'country' =>

                $passport['country']['name'],

            'international_presence' =>

                $passport['status']['international_presence'],

            /*
            |--------------------------------------------------------------------------
            | Completion
            |--------------------------------------------------------------------------
            */

            'completion' => round(

                collect([

                    $passport['markets']['total'] > 0,

                    $passport['products']['total'] > 0,

                    filled($passport['country']['code']),

                    filled($passport['export_experience']['markets']),

                    $passport['status']['international_presence'],

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
     * Complete Market Intelligence
     * --------------------------------------------------------------------------
     *
     * Standard response for Company Intelligence Framework.
     */
    public function all(
        Company $company
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Market Score
        |--------------------------------------------------------------------------
        */

        $score = $this->marketScore($company);

        /*
        |--------------------------------------------------------------------------
        | Market Passport
        |--------------------------------------------------------------------------
        */

        $passport = $this->marketPassport($company);

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