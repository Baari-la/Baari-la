<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Inertia\Inertia;
use Inertia\Response;

use App\Models\Company;
use App\Models\CompanyClaim;
use App\Models\CompanyUpdate;

class AdminCompanyController extends Controller
{
    public function index()
    {
        return Inertia::render(
            'Admin/Companies/Index',
            [
                'companies' =>
                    Company::latest()
                        ->paginate(20),

                'stats' => [
                    'total' =>
                        Company::count(),

                    'verified' =>
                        Company::where(
                            'status_verifikasi',
                            'verified'
                        )->count(),

                    'pending' =>
                        Company::where(
                            'status_verifikasi',
                            'pending'
                        )->count(),

                    'gold' =>
                        Company::where(
                            'membership_type',
                            'gold_member'
                        )->count(),
                ],
            ]
        );
    }

    public function pending()
    {
        return Inertia::render(
            'Admin/Companies/Pending',
            [
                'companies' =>
                    Company::where(
                        'status_verifikasi',
                        'pending'
                    )
                    ->latest()
                    ->get(),
            ]
        );
    }

    public function updates()
    {
        return Inertia::render(
            'Admin/Companies/Updates',
            [
                'updates' =>
                    CompanyUpdate::with(
                        [
                            'company',
                            'user',
                        ]
                    )
                    ->latest()
                    ->get(),
            ]
        );
    }

    public function claims()
    {
        return Inertia::render(
            'Admin/Companies/Claims',
            [
                'claims' =>
                    CompanyClaim::with(
                        [
                            'company',
                            'user',
                        ]
                    )
                    ->latest()
                    ->get(),
            ]
        );
    }

    public function show(
        Company $company
    ) {

        $company->load([
            'products',
            'markets',
            'certifications',
            'machines',
            'contacts',
            'links',
            'capacities',
            'moqs',
            'leadTimes',
        ]);

        return Inertia::render(
            'Admin/Companies/Show',
            [
                'company' => $company,

                'updates' =>
                    CompanyUpdate::where(
                        'company_id',
                        $company->id
                    )
                    ->latest()
                    ->get(),

                'claims' =>
                    CompanyClaim::with(
                        'user'
                    )
                    ->where(
                        'company_id',
                        $company->id
                    )
                    ->latest()
                    ->get(),
            ]
        );
    }
}