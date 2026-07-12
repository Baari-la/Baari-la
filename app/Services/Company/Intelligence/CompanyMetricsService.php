<?php

declare(strict_types=1);

namespace App\Services\Company\Intelligence;

use App\Models\Company;

class CompanyMetricsService
{
    /**
     * Generate Company Metrics.
     */
    public function generate(Company $company): array
    {
        return [

            'employees' => $this->employees($company),

            'products' => $company->products->count(),

            'markets' => $company->markets->count(),

            'machines' => $company->machines->count(),

            'capacities' => $company->capacities->count(),

            'moqs' => $company->moqs->count(),

            'lead_times' => $company->leadTimes->count(),

            'certifications' => $company->certifications->count(),

            'contacts' => $company->contacts->count(),

            'images' => $company->images->count(),

            'links' => $company->links->count(),

            'profile_completeness' => $this->profileCompleteness($company),

        ];
    }

    /**
     * Employee count.
     */
    protected function employees(Company $company): int
    {
        return (int) ($company->tenaga_kerja ?? 0);
    }

    /**
     * Calculate profile completeness.
     */
    protected function profileCompleteness(
        Company $company
    ): int {

        $checks = [

            !empty($company->nama_perusahaan),

            !empty($company->alamat_lengkap),

            !empty($company->country_code),

            !empty($company->city),

            !empty($company->telepon),

            !empty($company->email_web),

            $company->products->isNotEmpty(),

            $company->markets->isNotEmpty(),

            $company->machines->isNotEmpty(),

            $company->capacities->isNotEmpty(),

            $company->moqs->isNotEmpty(),

            $company->leadTimes->isNotEmpty(),

            $company->certifications->isNotEmpty(),

            $company->contacts->isNotEmpty(),

            $company->links->isNotEmpty(),

            $company->images->isNotEmpty(),

        ];

        $completed = collect($checks)
            ->filter()
            ->count();

        return (int) round(
            ($completed / count($checks)) * 100
        );
    }
}