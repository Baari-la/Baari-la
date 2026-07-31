<?php

namespace App\Http\Controllers;

use App\Services\Company\Identity\CompanyIdentityLookupService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OnboardingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | STEP 0
    |--------------------------------------------------------------------------
    */

  public function companyLookup(
    Request $request,
    CompanyIdentityLookupService $lookup
): Response {
    /*
    |--------------------------------------------------------------------------
    | Search Keyword
    |--------------------------------------------------------------------------
    */

    $keyword = trim(
        (string) $request->input(
            'keyword',
            ''
        )
    );

    /*
    |--------------------------------------------------------------------------
    | Canonical Company Identity Search
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    |
    | Company lookup no longer searches the legacy `companies` table
    | directly.
    |
    | Search flow:
    |
    | User keyword
    |      ↓
    | CompanyIdentityLookupService
    |      ↓
    | company_identities
    |      ↓
    | company_identity_sources
    |      ↓
    | legacy companies
    |
    | Name normalization is handled by the identity resolver/service.
    |
    */

    $identities = collect();

    if ($keyword !== '') {

        $identities = $lookup
            ->search(
                $keyword,
                20
            )
            ->loadMissing([
                'capabilities',
                'sources',
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Build Lookup Results
    |--------------------------------------------------------------------------
    |
    | Frontend receives canonical companies, not duplicate legacy records.
    |
    | `id` below intentionally represents company_identity_id.
    |
    */

    $companies = $identities
        ->map(function ($identity) {

            /*
            |--------------------------------------------------------------------------
            | Capabilities
            |--------------------------------------------------------------------------
            */

            $capabilities = $identity
                ->capabilities
                ->pluck('capability')
                ->filter()
                ->unique()
                ->sort()
                ->values();

            /*
            |--------------------------------------------------------------------------
            | Legacy Source IDs
            |--------------------------------------------------------------------------
            |
            | These remain available for traceability.
            |
            */

            $sourceCompanyIds = $identity
                ->sources
                ->pluck('company_id')
                ->filter()
                ->unique()
                ->values();

            return [

                /*
                |--------------------------------------------------------------------------
                | Canonical Identity
                |--------------------------------------------------------------------------
                */

                'id' =>
                    $identity->id,

                'company_identity_id' =>
                    $identity->id,

                'name' =>
                    $identity->canonical_name,

                'canonical_name' =>
                    $identity->canonical_name,

                'normalized_name' =>
                    $identity->normalized_name,

                /*
                |--------------------------------------------------------------------------
                | Location
                |--------------------------------------------------------------------------
                */

                'country_code' =>
                    $identity->country_code,

                'country_name' =>
                    $identity->country_name,

                /*
                |--------------------------------------------------------------------------
                | Identity Status
                |--------------------------------------------------------------------------
                */

                'identity_status' =>
                    $identity->identity_status,

                'verification_status' =>
                    $identity->verification_status,

                'verified_at' =>
                    $identity->verified_at,

                /*
                |--------------------------------------------------------------------------
                | Capabilities
                |--------------------------------------------------------------------------
                */

                'capabilities' =>
                    $capabilities->all(),

                'capability_count' =>
                    $capabilities->count(),

                /*
                |--------------------------------------------------------------------------
                | Legacy Evidence
                |--------------------------------------------------------------------------
                */

                'source_company_ids' =>
                    $sourceCompanyIds->all(),

                'source_count' =>
                    $sourceCompanyIds->count(),

                /*
                |--------------------------------------------------------------------------
                | Compatibility Marker
                |--------------------------------------------------------------------------
                */

                'record_type' =>
                    'company_identity',
            ];
        })
        ->values();

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    return Inertia::render(
        'Onboarding/Step0CompanyLookup',
        [
            'companies' =>
                $companies,

            'filters' => [
                'keyword' =>
                    $keyword,
            ],

            /*
            |--------------------------------------------------------------------------
            | Lookup Metadata
            |--------------------------------------------------------------------------
            */

            'lookup' => [
                'source' =>
                    'company_identities',

                'canonical' =>
                    true,

                'result_count' =>
                    $companies->count(),
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