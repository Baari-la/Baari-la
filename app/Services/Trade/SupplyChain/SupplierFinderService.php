<?php

declare(strict_types=1);

namespace App\Services\Trade\SupplyChain;

use App\Models\Company;

class SupplierFinderService
{
    /**
     * --------------------------------------------------------------------------
     * Find Suppliers
     * --------------------------------------------------------------------------
     */
   public function find(
    array $requirements = []
): array {

    $companies = Company::query()

        ->where(
            'status_verifikasi',
            'verified'
        )

        ->with([
            'products',
            'markets',
            'certifications',
        ])

        ->limit(20)

        ->get();

    return $companies

        ->map(function (
            Company $company
        ) {

            return [

                'company_id' =>

                    $company->id,

                'name' =>

                    $company->nama_perusahaan,

                'score' =>

                    $this->score(
                        $company
                    ),

                'segment' =>

                    $company
                        ->products
                        ->first()
                        ?->category

                    ?? 'N/A',

                'country' =>

                optional(
                    $company
                        ->markets
                        ->first()
                )

                ->country_name_en

                ?? 'Indonesia',


                'membership' =>

                    $company
                        ->membership_type,

                'certifications' =>

                    $company
                        ->certifications
                        ->pluck(
                            'certification_name'
                        )
                        ->values()
                        ->toArray(),

                'markets' =>

                    $company
                        ->markets
                        ->pluck(
                            'country_name_en'
                        )
                        ->values()
                        ->toArray(),
            ];
        })

        ->sortByDesc(
            'score'
        )

        ->values()

        ->toArray();
}

    /**
     * --------------------------------------------------------------------------
     * Match Score
     * --------------------------------------------------------------------------
     */
    protected function score(
    Company $company
): int {

    $score = 0;

    if (
        $company->products
            ->count() > 0
    ) {
        $score += 40;
    }

    if (
        $company->markets
            ->count() > 0
    ) {
        $score += 30;
    }

    if (
        $company->certifications
            ->count() > 0
    ) {
        $score += 20;
    }

    if (
        $company->membership_type ===
        'gold_member'
    ) {
        $score += 10;
    }

    return min(
        $score,
        100
    );
}
} 