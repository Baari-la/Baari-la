<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

use App\Services\Company\Identity\CompanyIdentityLookupService;
use App\Services\Company\Identity\CanonicalCompanyProfileService;
use App\Services\Company\Identity\CanonicalCompanyCapabilityService;
use App\Services\Company\Identity\CanonicalCompanyFactoryService;
use App\Services\Business\BusinessClassificationService;
use App\Models\CompanyIdentityCapabilityProfile;
use App\Models\CompanyIdentity;
use App\Models\CompanyIdentityLocation;
use App\Models\CompanyIdentityProfile;
use App\Models\CompanyIdentityBusiness;
use App\Services\Company\Identity\CanonicalCompanyBusinessService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use RuntimeException;

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

    public function companyInformation(
    CanonicalCompanyProfileService $profiles
): Response {

    return Inertia::render(
        'Onboarding/CompanyInformation',
        [

            'company' =>

                $profiles->buildFromUser(
                    auth()->user()
                ),

        ]
    );
}

    public function storeCompanyInformation(Request $request)
{
    $user = auth()->user();

    if (!$user->company_identity_id) {
        return back()->with(
            'error',
            'No canonical company identity is connected to this account.'
        );
    }

    $validated = $request->validate([
        'company_type' => ['nullable', 'string', 'max:255'],
        'phone'        => ['nullable', 'string', 'max:1000'],
        'website'      => ['nullable', 'url', 'max:255'],
        'country'      => ['nullable', 'string', 'max:255'],
        'province'     => ['nullable', 'string', 'max:255'],
        'city'         => ['nullable', 'string', 'max:255'],
        'postal_code'  => ['nullable', 'string', 'max:50'],
        'address'      => ['nullable', 'string'],

        /*
        |--------------------------------------------------------------------------
        | Business Locations™
        |--------------------------------------------------------------------------
        */
        'locations'                       => ['nullable', 'array'],
        'locations.*.location_type'       => ['required', 'string', 'max:50'],
        'locations.*.location_name'       => ['nullable', 'string', 'max:255'],
        'locations.*.location_label'      => ['nullable', 'string', 'max:255'],
        'locations.*.location_code'       => ['nullable', 'string', 'max:50'],
        'locations.*.address'             => ['nullable', 'string'],
        'locations.*.country'             => ['nullable', 'string', 'max:100'],
        'locations.*.province'            => ['nullable', 'string', 'max:100'],
        'locations.*.city'                => ['nullable', 'string', 'max:100'],
        'locations.*.district'            => ['nullable', 'string', 'max:100'],
        'locations.*.subdistrict'         => ['nullable', 'string', 'max:100'],
        'locations.*.postal_code'         => ['nullable', 'string', 'max:20'],
        'locations.*.phone'               => ['nullable', 'string', 'max:100'],
        'locations.*.email'               => ['nullable', 'email', 'max:255'],
        'locations.*.website'             => ['nullable', 'url', 'max:255'],
        'locations.*.contact_person'      => ['nullable', 'string', 'max:255'],
        'locations.*.google_maps_url'     => ['nullable', 'url'],
        'locations.*.display_order'       => ['nullable', 'integer'],
        'locations.*.is_primary'          => ['nullable', 'boolean'],
        'locations.*.is_active'           => ['nullable', 'boolean'],
    ]);


    DB::transaction(function () use ($user, $validated) {
        
        // 1. Cari CompanyIdentity secara eksplisit
        $company = CompanyIdentity::find($user->company_identity_id);

        if (!$company) {
            throw new RuntimeException('Canonical company identity not found.');
        }

        // 2. Update / Create Profile via Relasi Aggregate Root
        $company->profile()->updateOrCreate(
            [], // Kondisi 'company_identity_id' otomatis di-handle oleh relasi Eloquent
            [
                'company_type'     => $validated['company_type'] ?? null,
                'phone'            => $validated['phone'] ?? null,
                'website'          => $validated['website'] ?? null,
                'country'          => $validated['country'] ?? null,
                'province'         => $validated['province'] ?? null,
                'city'             => $validated['city'] ?? null,
                'postal_code'      => $validated['postal_code'] ?? null,
                'address'          => $validated['address'] ?? null,
                'last_updated_by'  => $user->id,
                'last_reviewed_at' => now(),
            ]
        );

        // 3. Refresh & Re-create Business Locations
        if ($company->locations()->exists()) {
            $company->locations()->delete();
          }

        foreach ($validated['locations'] ?? [] as $location) {
            if (empty($location['location_name']) && empty($location['address'])) {
                continue;
            }

            $company->locations()->create([
                'location_type'   => $location['location_type'],
                'location_name'   => $location['location_name'] ?? null,
                'location_label'  => $location['location_label'] ?? null,
                'location_code'   => $location['location_code'] ?? null,
                'address'         => $location['address'] ?? null,
                'country'         => $location['country'] ?? null,
                'province'        => $location['province'] ?? null,
                'city'            => $location['city'] ?? null,
                'district'        => $location['district'] ?? null,
                'subdistrict'     => $location['subdistrict'] ?? null,
                'postal_code'     => $location['postal_code'] ?? null,
                'phone'           => $location['phone'] ?? null,
                'email'           => $location['email'] ?? null,
                'website'         => $location['website'] ?? null,
                'contact_person'  => $location['contact_person'] ?? null,
                'google_maps_url' => $location['google_maps_url'] ?? null,
                'display_order'   => $location['display_order'] ?? 1,
                'is_primary'      => filter_var($location['is_primary'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'is_active'       => filter_var($location['is_active'] ?? true, FILTER_VALIDATE_BOOLEAN),
            ]);
        }

        // 4. Update Status Onboarding User
        $user->update([
            'onboarding_step' => 2,
        ]);
    });

    return redirect()->route('onboarding.business-information');
}
    /*
    |--------------------------------------------------------------------------
    | STEP 2
    |--------------------------------------------------------------------------
    */

   public function businessInformation(
    CanonicalCompanyBusinessService $business
        ): Response {

            return Inertia::render(
                'Onboarding/BusinessInformation',
                [

                    'company' =>
                        $business->buildFromUser(
                            auth()->user()
                        ),

                ]
            );
        }

    public function storeBusinessInformation(Request $request,
    BusinessClassificationService $classification): RedirectResponse
{
    $user = auth()->user();

    /*
    |--------------------------------------------------------------------------
    | Canonical Company Required
    |--------------------------------------------------------------------------
    */
    if (!$user->company_identity_id) {
        return back()->with(
            'error',
            'No canonical company identity is connected to this account.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */
    $validated = $request->validate([
        // Company Overview
        'business_description'    => ['nullable', 'string'],
        'year_established'        => ['nullable', 'integer', 'between:1900,' . date('Y')],
        'legal_entity'            => ['nullable', 'string', 'max:255'],
        'employee_range'          => ['nullable', 'string', 'max:255'],
        'factory_count'           => ['nullable', 'integer', 'min:0'],

        // Business Model
        'is_fiber_producer'       => ['boolean'],
        'is_spinner'              => ['boolean'],
        'is_weaving'              => ['boolean'],
        'is_knitting'             => ['boolean'],
        'is_dyeing_finishing'     => ['boolean'],
        'is_printing'             => ['boolean'],
        'is_garment'              => ['boolean'],
        'is_trader'               => ['boolean'],
        'is_brand'                => ['boolean'],
        'is_buying_office'        => ['boolean'],

        'is_testing_laboratory'   => ['boolean'],
        'is_certification_body'   => ['boolean'],
        'is_machinery_supplier'   => ['boolean'],
        'is_accessories_supplier' => ['boolean'],
        'is_chemical_supplier'    => ['boolean'],

        // Strategy
        'oem'                     => ['boolean'],
        'odm'                     => ['boolean'],
        'obm'                     => ['boolean'],
        'private_label'           => ['boolean'],

        // Market
        'domestic_market'         => ['boolean'],
        'export_market'           => ['boolean'],
        'export_experience_years' => ['nullable', 'integer', 'min:0', 'max:100'],

        // Sustainability
        'esg_program'             => ['boolean'],
        'renewable_energy'        => ['boolean'],
        'recycled_material'       => ['boolean'],
        'wastewater_treatment'    => ['boolean'],
        'sustainability_notes'    => ['nullable', 'string'],
    ]);

  /*
|--------------------------------------------------------------------------
| Save Canonical Business Profile
|--------------------------------------------------------------------------
*/

$business = CompanyIdentityBusiness::updateOrCreate(

    [
        'company_identity_id' => $user->company_identity_id,
    ],

    array_merge(

        $validated,

        [

            'last_updated_by' => $user->id,

            'last_reviewed_at' => now(),

        ]

    )

);

/*
|--------------------------------------------------------------------------
| Business Classification™
|--------------------------------------------------------------------------
*/

$result = $classification->classify($business);


$business->update([

    'primary_business_category'
        => $result['primary_business_category'],

    'secondary_business_categories'
        => $result['secondary_business_categories'],

    'value_chain_position'
        => $result['value_chain_position'],

]);

/*
|--------------------------------------------------------------------------
| Update Onboarding Progress
|--------------------------------------------------------------------------
*/

$user->update([

    'onboarding_step' => 3,

]);

/*
|--------------------------------------------------------------------------
| Continue to Step 3
|--------------------------------------------------------------------------
*/

return redirect()->route(
    'onboarding.capabilities'
);


}

    public function storeCapabilities(
    Request $request
): RedirectResponse
{

 
    $user = auth()->user();

    /*
    |--------------------------------------------------------------------------
    | Canonical Company Required
    |--------------------------------------------------------------------------
    */

    if (!$user->company_identity_id) {

        return back()->with(
            'error',
            'No canonical company identity is connected.'
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    $validated = $request->validate([

    /*
    |--------------------------------------------------------------------------
    | Capacity Intelligence™
    |--------------------------------------------------------------------------
    */

    'production_capacity' => ['nullable', 'numeric', 'min:0'],
    'production_capacity_unit' => ['nullable', 'string', 'max:30'],

    'current_utilized_capacity' => ['nullable', 'numeric', 'min:0'],
    'current_utilized_capacity_unit' => ['nullable', 'string', 'max:30'],

    'monthly_capacity' => ['nullable', 'numeric', 'min:0'],
    'annual_capacity' => ['nullable', 'numeric', 'min:0'],

    /*
    |--------------------------------------------------------------------------
    | Commercial
    |--------------------------------------------------------------------------
    */

    'minimum_order_quantity' => ['nullable', 'numeric', 'min:0'],
    'minimum_order_unit' => ['nullable', 'string', 'max:30'],

    'lead_time_days' => ['nullable', 'integer', 'min:0'],

    /*
    |--------------------------------------------------------------------------
    | Manufacturing Services
    |--------------------------------------------------------------------------
    */

    'supports_oem' => ['boolean'],
    'supports_odm' => ['boolean'],
    'supports_private_label' => ['boolean'],
    'supports_full_package' => ['boolean'],
    'supports_cmt' => ['boolean'],
    'supports_design_support' => ['boolean'],

    /*
    |--------------------------------------------------------------------------
    | Service Capability
    |--------------------------------------------------------------------------
    */

    'export_ready' => ['boolean'],
    'sampling_service' => ['boolean'],

    /*
    |--------------------------------------------------------------------------
    | Production Flexibility
    |--------------------------------------------------------------------------
    */

    'supports_small_batch' => ['boolean'],
    'supports_fast_sampling' => ['boolean'],
    'supports_quick_response' => ['boolean'],
    'supports_custom_development' => ['boolean'],

]);

    /*
    |--------------------------------------------------------------------------
    | Save Canonical Capability Profile
    |--------------------------------------------------------------------------
    */

   $profile = CompanyIdentityCapabilityProfile::updateOrCreate(

        [
            'company_identity_id' => $user->company_identity_id,
        ],

        array_merge(

            $validated,

            [

                'last_updated_by' => $user->id,

                'last_reviewed_at' => now(),

            ]

        )

    );

    /*
    |--------------------------------------------------------------------------
    | Update Progress
    |--------------------------------------------------------------------------
    */

    $user->update([

        'onboarding_step' => 4,

    ]);

    /*
    |--------------------------------------------------------------------------
    | Continue
    |--------------------------------------------------------------------------
    */

    return redirect()->route(
        'onboarding.manufacturing'
    );
}
    
    /*
    |--------------------------------------------------------------------------
    | STEP 4
    |--------------------------------------------------------------------------
    */

    public function manufacturing(
    CanonicalCompanyFactoryService $factory
): Response {

    return Inertia::render(
        'Onboarding/Manufacturing',
        [

            'company' =>

                $factory->buildFromUser(
                    auth()->user()
                ),

        ]
    );
}

    public function storeManufacturing(
    Request $request,
    CanonicalCompanyFactoryService $factoryService
) {

    $user = auth()->user();

    $identity = $user->companyIdentity;

    if (! $identity) {

        abort(404, 'Company Identity not found.');

    }

    /*
    |--------------------------------------------------------------------------
    | Save Factory Passport
    |--------------------------------------------------------------------------
    */

    $factoryService->saveFactoryPassport(

        $identity,

        $request->input('factory', []),

        $request->input('primary_machine', [])

    );

    /*
    |--------------------------------------------------------------------------
    | Update Onboarding Progress
    |--------------------------------------------------------------------------
    */

    $user->update([

        'onboarding_step' => 5,

    ]);

    /*
    |--------------------------------------------------------------------------
    | Next Step
    |--------------------------------------------------------------------------
    */


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
public function capabilities(
    CanonicalCompanyCapabilityService $capabilities
): Response {

    return Inertia::render(
        'Onboarding/Capabilities',
        [

            'company' => $capabilities->buildFromUser(
                auth()->user()
            ),

        ]
    );
}

}