<?php

declare(strict_types=1);

namespace App\Services\SupplyChain\Ranking;

use Illuminate\Support\Collection;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Supply Chain Ranking Service
 * ==========================================================================
 *
 * Responsible for ranking supply chain candidates.
 *
 * Current Version
 *
 * • Verified Company
 * • Membership
 * • Profile Completeness
 *
 * Future Version
 *
 * • AI Recommendation Score
 * • Trade Intelligence
 * • Production Capacity
 * • Supply Risk
 * • ESG
 * • Delivery Performance
 *
 * Version:
 * 1.0
 */
class SupplyChainRankingService
{
    /**
     * --------------------------------------------------------------------------
     * Rank Companies
     * --------------------------------------------------------------------------
     */
    public function rank(
        Collection $stages,
    ): Collection {

        return $stages

            ->map(function (array $stage) {

                $companies = collect(

                    $stage['companies'] ?? []

                )

                ->map(function ($company) {

                    $score = 0;

                    /*
                    |--------------------------------------------------------------------------
                    | Verified Company
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $company->status_verifikasi === 'verified'
                    ) {
                        $score += 30;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Membership
                    |--------------------------------------------------------------------------
                    */

                    match ($company->membership_type) {

                        'gold_member' => $score += 25,

                        'silver_member' => $score += 15,

                        'bronze_member' => $score += 10,

                        default => $score += 5,

                    };

                    /*
                    |--------------------------------------------------------------------------
                    | Profile Completeness
                    |--------------------------------------------------------------------------
                    */

                    if ($company->products->isNotEmpty()) {
                        $score += 10;
                    }

                    if ($company->markets->isNotEmpty()) {
                        $score += 10;
                    }

                    if ($company->certifications->isNotEmpty()) {
                        $score += 10;
                    }

                    if ($company->machines->isNotEmpty()) {
                        $score += 5;
                    }

                    if ($company->capacities->isNotEmpty()) {
                        $score += 10;
                    }

                    return [

                        'company_id' => $company->id,

                        'company_name' => $company->nama_perusahaan,

                        'category' => $company->category,

                        'membership' => $company->membership_type,

                        'verification' => $company->status_verifikasi,

                        'city' => $company->city,

                        'country' => $company->country_name,

                        'score' => min($score, 100),

                    ];

                })

                ->sortByDesc('score')

                ->values()

                ->all();

                return [

                    'key' => $stage['key'],

                    'title' => $stage['title'],

                    'companies' => $companies,

                ];

            });

    }
}