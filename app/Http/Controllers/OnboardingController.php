<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | STEP 0
    |--------------------------------------------------------------------------
    */

  public function companyLookup(Request $request)
{
    $keyword = trim(
        $request->keyword ?? ''
    );

    /*
    |--------------------------------------------------------------------------
    | Normalize Search Keyword
    |--------------------------------------------------------------------------
    |
    | Contoh:
    |
    | PT Indorama       → Indorama
    | PT. Indorama      → Indorama
    | Indorama, PT      → Indorama
    | Indorama, PT.     → Indorama
    | CV Sinar Textile  → Sinar Textile
    |
    */

    $searchKeyword = $keyword;

    // Hapus badan usaha di depan
    $searchKeyword = preg_replace(
        '/^(pt|cv)\.?\s+/i',
        '',
        $searchKeyword
    );

    // Hapus badan usaha di belakang
    $searchKeyword = preg_replace(
        '/\s*,?\s*(pt|cv)\.?$/i',
        '',
        $searchKeyword
    );

    $searchKeyword = trim(
        $searchKeyword
    );

    /*
    |--------------------------------------------------------------------------
    | Search Companies
    |--------------------------------------------------------------------------
    */

    $companies = Company::query()
        ->select([
            'id',
            'nama_perusahaan',
            'category',
            'company_type',
            'company_role',
            'city',
            'country_name',
            'produk',
            'pasar_ekspor',
            'membership_type',
            'status_verifikasi',
            'photo_url',
            'last_verified_at',
            'tahun_berdiri',
        ])
        ->when(
            $searchKeyword !== '',
            function ($query) use ($searchKeyword) {

                $query->where(
                    'nama_perusahaan',
                    'like',
                    "%{$searchKeyword}%"
                );
            }
        )
        ->orderBy('nama_perusahaan')
        ->limit(20)
        ->get();

    return Inertia::render(
        'Onboarding/Step0CompanyLookup',
        [
            'companies' => $companies,

            'filters' => [
                'keyword' => $keyword,
            ],
        ]
    );
}

    /*
    |--------------------------------------------------------------------------
    | STEP 1
    |--------------------------------------------------------------------------
    */

    public function companyInformation(): Response
    {
        return Inertia::render(
            'Onboarding/CompanyInformation',
            [
                'company' => auth()->user()?->company,
            ]
        );
    }
    public function storeCompanyInformation(
    Request $request
        )
        {


            // TODO:
            // Simpan data Company

            auth()->user()->update([
                'onboarding_step' => 2,
            ]);

            return redirect()->route(
                'onboarding.business-information'
            );
        }
    
    /*
    |--------------------------------------------------------------------------
    | STEP 2
    |--------------------------------------------------------------------------
    */

    public function businessInformation(): Response
    {
        return Inertia::render(
            'Onboarding/BusinessInformation',
            [
                'company' => auth()->user()?->company,
            ]
        );
    }

    public function storeBusinessInformation(
    Request $request
        )
        {
            // TODO:
            // Simpan Business Information

            auth()->user()->update([
                'onboarding_step' => 3,
            ]);

            return redirect()->route(
                'onboarding.capabilities'
            );
        }

    /*
    |--------------------------------------------------------------------------
    | STEP 3
    |--------------------------------------------------------------------------
    */

    public function capabilities(): Response
    {
        return Inertia::render(
            'Onboarding/Capabilities',
            [
                'company' => auth()->user()?->company,
            ]
        );
    }

    public function storeCapabilities(
    Request $request
    )
    {
        // TODO:
        // Simpan Capabilities

        auth()->user()->update([
            'onboarding_step' => 4,
        ]);

        return redirect()->route(
            'onboarding.manufacturing'
        );
    }
    
    /*
    |--------------------------------------------------------------------------
    | STEP 4
    |--------------------------------------------------------------------------
    */

    public function manufacturing(): Response
    {
        return Inertia::render(
            'Onboarding/Manufacturing',
            [
                'company' => auth()->user()?->company,
            ]
        );
    }

    public function storeManufacturing(
    Request $request
    )
    {
        // TODO:
        // Simpan Manufacturing

        auth()->user()->update([
            'onboarding_step' => 5,
        ]);

        return redirect()->route(
            'onboarding.media-catalog'
        );
    }
    /*
    |--------------------------------------------------------------------------
    | STEP 5
    |--------------------------------------------------------------------------
    */

    public function mediaCatalog(): Response
    {
        return Inertia::render(
            'Onboarding/MediaCatalog',
            [
                'company' => auth()->user()?->company,
            ]
        );
    }

    public function storeMediaCatalog(
    Request $request
        )
        {
            // TODO:
            // Simpan Media & Catalog

            auth()->user()->update([
                'onboarding_step' => 6,
            ]);

            return redirect()->route(
                'onboarding.review-submit'
            );
        }

    /*
    |--------------------------------------------------------------------------
    | STEP 6
    |--------------------------------------------------------------------------
    */

    public function reviewSubmit(): Response
    {
        return Inertia::render(
            'Onboarding/ReviewSubmit',
            [
                'company' => auth()->user()?->company,
            ]
        );
    }

    public function submitReview()
{
    auth()->user()->update([

        'onboarding_step' => 7,

        'onboarding_completed' => true,

        'onboarding_completed_at' => now(),
    ]);

    return redirect()->route(
        'dashboard'
    );
}
}