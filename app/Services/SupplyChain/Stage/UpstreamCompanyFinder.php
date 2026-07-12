<?php

declare(strict_types=1);

namespace App\Services\SupplyChain\Stage;

use App\Models\Company;
use Illuminate\Support\Collection;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Upstream Company Finder
 * ==========================================================================
 *
 * Finds upstream companies based on
 * the business ecosystem definition.
 *
 * Responsibilities:
 *
 * • Resolve upstream stages
 * • Find companies by role/category
 * • Exclude current company
 *
 * Does NOT:
 *
 * • Score companies
 * • Rank companies
 * • Generate recommendations
 *
 * Version:
 * 1.0
 */
class UpstreamCompanyFinder
{
    /**
     * --------------------------------------------------------------------------
     * Find Upstream Companies
     * --------------------------------------------------------------------------
     */
    public function find(
        Company $company,
        array $upstreamStages,
    ): Collection {

        return collect($upstreamStages)

            ->map(function (array $stage) use ($company) {

                return [

                    'key' => $stage['key'],

                    'title' => $stage['name'],

                    'companies' =>

                        Company::query()

                            ->where('category', $stage['key'])

                            ->whereKeyNot($company->id)

                            ->with([

                                'products',

                                'markets',

                                'certifications',

                            ])

                            ->limit(10)

                            ->get(),

                ];

            });

    }
}