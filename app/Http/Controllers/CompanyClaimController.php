<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyClaim;
use App\Models\CompanyIdentity;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CompanyClaimController extends Controller
{
    
    /*
    |--------------------------------------------------------------------------
    | EXISTING COMPANY
    |--------------------------------------------------------------------------
    |
    | User memilih perusahaan yang sudah ada di tabel companies.
    |
    */

    public function create(
        Company $company
    ): Response|RedirectResponse {

        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | User Already Owns Company
        |--------------------------------------------------------------------------
        */

        if ($user->company_id) {

            return redirect()
                ->route('dashboard')
                ->with(
                    'error',
                    'You already manage a company.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Company Already Claimed
        |--------------------------------------------------------------------------
        */

        if ($company->claimed_by_user_id) {

            return redirect()
                ->route('onboarding.company-lookup')
                ->with(
                    'error',
                    'This company is already managed by another user.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Existing Pending Claim
        |--------------------------------------------------------------------------
        */

        $existingClaim =
            CompanyClaim::where(
                'company_id',
                $company->id
            )
            ->where(
                'user_id',
                $user->id
            )
            ->where(
                'status',
                'pending'
            )
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Render Ownership Verification
        |--------------------------------------------------------------------------
        */

        return Inertia::render(
            'Onboarding/OwnershipVerification',
            [
                'company' => [
                    'id' =>
                        $company->id,

                    'name' =>
                        $company->nama_perusahaan,

                    'city' =>
                        $company->city,

                    'country' =>
                        $company->country_name,
                ],

                'manualCompany' => false,

                'existingClaim' =>
                    $existingClaim,

                'user' => [
                    'name' =>
                        $user->name,

                    'email' =>
                        $user->email,
                ],
            ]
        );
    }



/*
|--------------------------------------------------------------------------
| CANONICAL COMPANY IDENTITY
|--------------------------------------------------------------------------
|
| User selects a canonical company identity produced by the
| DIGESTEX Company Identity Layer.
|
| No legacy company record is selected or modified here.
|
*/

public function createIdentity(
    CompanyIdentity $companyIdentity
): Response|RedirectResponse {

    $user = auth()->user();

    /*
    |--------------------------------------------------------------------------
    | User Already Owns Company
    |--------------------------------------------------------------------------
    */

    if ($user->company_id) {
        return redirect()
            ->route('dashboard')
            ->with(
                'error',
                'You already manage a company.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Only READY Identity Can Be Claimed
    |--------------------------------------------------------------------------
    |
    | REVIEW identities must never enter the ownership verification flow
    | until they have been resolved by DIGESTEX.
    |
    */

    if (
        $companyIdentity->identity_status
        !== 'READY'
    ) {
        return redirect()
            ->route('onboarding.company-lookup')
            ->with(
                'error',
                'This company identity is not currently available for ownership verification.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Pending Claim By Another User
    |--------------------------------------------------------------------------
    |
    | Only one active pending ownership claim should exist for a canonical
    | company identity at a time.
    |
    */

    $pendingClaim = CompanyClaim::query()
        ->where(
            'company_identity_id',
            $companyIdentity->id
        )
        ->where(
            'status',
            'pending'
        )
        ->first();

    if (
        $pendingClaim &&
        (int) $pendingClaim->user_id
            !== (int) $user->id
    ) {
        return redirect()
            ->route('onboarding.company-lookup')
            ->with(
                'error',
                'This company already has a pending ownership verification request.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Existing Pending Claim By Current User
    |--------------------------------------------------------------------------
    */

    $existingClaim = CompanyClaim::query()
        ->where(
            'company_identity_id',
            $companyIdentity->id
        )
        ->where(
            'user_id',
            $user->id
        )
        ->where(
            'status',
            'pending'
        )
        ->latest('submitted_at')
        ->first();

    /*
    |--------------------------------------------------------------------------
    | Load Canonical Evidence
    |--------------------------------------------------------------------------
    |
    | Legacy company records remain source evidence only.
    | They are not selected as the ownership target.
    |
    */

    $companyIdentity->loadMissing([
        'capabilities',
        'sources',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Canonical Capabilities
    |--------------------------------------------------------------------------
    */

    $capabilities = $companyIdentity
        ->capabilities
        ->pluck('capability')
        ->filter()
        ->unique()
        ->sort()
        ->values()
        ->all();

    /*
    |--------------------------------------------------------------------------
    | Render Ownership Verification
    |--------------------------------------------------------------------------
    */

    return Inertia::render(
        'Onboarding/OwnershipVerification',
        [

            /*
            |--------------------------------------------------------------------------
            | Company Presentation Data
            |--------------------------------------------------------------------------
            |
            | `company.id` is the canonical company_identity_id in this flow.
            |
            */

            'company' => [
                'id' =>
                    $companyIdentity->id,

                'company_identity_id' =>
                    $companyIdentity->id,

                'name' =>
                    $companyIdentity->canonical_name,

                'country' =>
                    $companyIdentity->country_name,

                'country_code' =>
                    $companyIdentity->country_code,

                'identity_status' =>
                    $companyIdentity->identity_status,

                'verification_status' =>
                    $companyIdentity->verification_status,

                'capabilities' =>
                    $capabilities,

                'capability_count' =>
                    count($capabilities),

                'source_count' =>
                    $companyIdentity
                        ->sources
                        ->count(),

                'record_type' =>
                    'company_identity',
            ],

            /*
            |--------------------------------------------------------------------------
            | Canonical Identity Contract
            |--------------------------------------------------------------------------
            |
            | IMPORTANT:
            |
            | OwnershipVerification.jsx receives this separately so the
            | canonical ID can never be interpreted as companies.id.
            |
            */

            'companyIdentityId' =>
                $companyIdentity->id,

            'canonicalCompany' =>
                true,

            'manualCompany' =>
                false,

            /*
            |--------------------------------------------------------------------------
            | Existing Claim
            |--------------------------------------------------------------------------
            */

            'existingClaim' =>
                $existingClaim,

            /*
            |--------------------------------------------------------------------------
            | Authenticated User
            |--------------------------------------------------------------------------
            */

            'user' => [
                'name' =>
                    $user->name,

                'email' =>
                    $user->email,
            ],
        ]
    );
}

    /*
    |--------------------------------------------------------------------------
    | MANUAL / UNMATCHED COMPANY
    |--------------------------------------------------------------------------
    |
    | Nama perusahaan tidak ditemukan di tabel companies.
    | Tidak membuat record baru di companies.
    |
    */

    public function createManual(
        Request $request
    ): Response|RedirectResponse {

        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | User Already Owns Company
        |--------------------------------------------------------------------------
        */

        if ($user->company_id) {

            return redirect()
                ->route('dashboard')
                ->with(
                    'error',
                    'You already manage a company.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Company Name
        |--------------------------------------------------------------------------
        */

        $companyName = trim(
            (string) $request->query(
                'company_name',
                ''
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Render Ownership Verification
        |--------------------------------------------------------------------------
        */

        return Inertia::render(
            'Onboarding/OwnershipVerification',
            [
                'company' => null,

                'manualCompany' => true,

                'claimedCompanyName' =>
                    $companyName,

                'existingClaim' => null,

                'user' => [
                    'name' =>
                        $user->name,

                    'email' =>
                        $user->email,
                ],
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE OWNERSHIP CLAIM
    |--------------------------------------------------------------------------
    |
    | company_id:
    |
    | Existing Company → company_id = companies.id
    | Manual Company   → company_id = NULL
    |
    */

   public function store(
    Request $request
): RedirectResponse {

    $user = auth()->user();

    /*
    |--------------------------------------------------------------------------
    | User Already Owns Company
    |--------------------------------------------------------------------------
    */

    if ($user->company_id) {

        return back()->with(
            'error',
            'You already manage a company.'
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
        | Canonical Company Identity
        |--------------------------------------------------------------------------
        */

        'company_identity_id' => [
            'nullable',
            'integer',
            'exists:company_identities,id',
        ],

        /*
        |--------------------------------------------------------------------------
        | Legacy Company
        |--------------------------------------------------------------------------
        */

        'company_id' => [
            'nullable',
            'integer',
            'exists:companies,id',
        ],

        /*
        |--------------------------------------------------------------------------
        | Company Name
        |--------------------------------------------------------------------------
        */

        'claimed_company_name' => [
            'required',
            'string',
            'max:255',
        ],

        /*
        |--------------------------------------------------------------------------
        | Applicant
        |--------------------------------------------------------------------------
        */

        'full_name' => [
            'required',
            'string',
            'max:255',
        ],

        'position' => [
            'required',
            'string',
            'max:255',
        ],

        'phone' => [
            'required',
            'string',
            'max:100',
        ],

        /*
        |--------------------------------------------------------------------------
        | Ownership Verification
        |--------------------------------------------------------------------------
        */

        'nib' => [
            'required',
            'string',
            'max:255',
        ],

        'verification_document_type' => [
            'required',
            'string',
            'max:100',
        ],

        'verification_document' => [
            'required',
            'file',
            'mimes:pdf,jpg,jpeg,png',
            'max:10240',
        ],

        /*
        |--------------------------------------------------------------------------
        | Notes
        |--------------------------------------------------------------------------
        */

        'notes' => [
            'nullable',
            'string',
            'max:2000',
        ],
    ]);

    /*
    |--------------------------------------------------------------------------
    | Initialize Claim Targets
    |--------------------------------------------------------------------------
    |
    | A claim may target:
    |
    | 1. Canonical company identity
    | 2. Legacy company
    | 3. Manual / unmatched company
    |
    */

    $companyIdentity = null;
    $company = null;

    /*
    |--------------------------------------------------------------------------
    | Claim Target Validation
    |--------------------------------------------------------------------------
    |
    | Canonical identity and legacy company must never be submitted
    | together in the same ownership claim.
    |
    */

    if (
        !empty($validated['company_identity_id']) &&
        !empty($validated['company_id'])
    ) {

        return back()
            ->withErrors([
                'company_identity_id' =>
                    'Invalid company ownership verification target.',
            ])
            ->withInput();
    }

    /*
    |--------------------------------------------------------------------------
    | Canonical Company Identity
    |--------------------------------------------------------------------------
    */

    if (
        !empty(
            $validated['company_identity_id']
        )
    ) {

        $companyIdentity =
            CompanyIdentity::query()
                ->where(
                    'identity_status',
                    'READY'
                )
                ->findOrFail(
                    $validated[
                        'company_identity_id'
                    ]
                );

        /*
        |--------------------------------------------------------------------------
        | Pending Claim For Canonical Identity
        |--------------------------------------------------------------------------
        |
        | Prevent multiple pending ownership requests against the same
        | canonical company identity.
        |
        */

        $pendingIdentityClaim =
            CompanyClaim::query()
                ->where(
                    'company_identity_id',
                    $companyIdentity->id
                )
                ->where(
                    'status',
                    'pending'
                )
                ->exists();

        if ($pendingIdentityClaim) {

            return back()->with(
                'error',
                'This company already has a pending ownership verification request.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Always Trust Canonical Company Name
        |--------------------------------------------------------------------------
        |
        | Never trust company name submitted by the browser when a
        | canonical identity has already been selected.
        |
        */

        $validated[
            'claimed_company_name'
        ] = $companyIdentity->canonical_name;
    }

    /*
    |--------------------------------------------------------------------------
    | Legacy Company
    |--------------------------------------------------------------------------
    */

    if (
        !empty(
            $validated['company_id']
        )
    ) {

        $company = Company::findOrFail(
            $validated['company_id']
        );

        /*
        |--------------------------------------------------------------------------
        | Company Already Claimed
        |--------------------------------------------------------------------------
        */

        if ($company->claimed_by_user_id) {

            return back()->with(
                'error',
                'This company is already managed by another user.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pending Claim For Legacy Company
        |--------------------------------------------------------------------------
        */

        $pendingCompanyClaim =
            CompanyClaim::query()
                ->where(
                    'company_id',
                    $company->id
                )
                ->where(
                    'status',
                    'pending'
                )
                ->exists();

        if ($pendingCompanyClaim) {

            return back()->with(
                'error',
                'This company already has a pending ownership verification request.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Always Trust Database Company Name
        |--------------------------------------------------------------------------
        */

        $validated[
            'claimed_company_name'
        ] = $company->nama_perusahaan;
    }

    /*
    |--------------------------------------------------------------------------
    | Manual / Unmatched Company
    |--------------------------------------------------------------------------
    |
    | If both IDs are NULL, the ownership request represents a company
    | that is not yet connected to either a canonical identity or a
    | legacy company record.
    |
    | The submitted company name is retained for admin review.
    |
    */

    if (
        $companyIdentity === null &&
        $company === null
    ) {

        $validated[
            'claimed_company_name'
        ] = trim(
            $validated[
                'claimed_company_name'
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Existing Pending Claim By User
    |--------------------------------------------------------------------------
    |
    | One user should not create multiple simultaneous ownership
    | verification requests.
    |
    */

    $existingUserClaim =
        CompanyClaim::query()
            ->where(
                'user_id',
                $user->id
            )
            ->where(
                'status',
                'pending'
            )
            ->latest(
                'submitted_at'
            )
            ->first();

    if ($existingUserClaim) {

        return redirect()
            ->route(
                'companies.claim.submitted'
            )
            ->with(
                'info',
                'You already have a pending ownership verification request.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Upload Verification Document
    |--------------------------------------------------------------------------
    |
    | Ownership verification documents must remain private.
    | Do not store them on the public disk.
    |
    */

    $documentPath =
        $request
            ->file(
                'verification_document'
            )
            ->store(
                'company-ownership-verification'
            );

    /*
    |--------------------------------------------------------------------------
    | Create Ownership Claim
    |--------------------------------------------------------------------------
    */

    CompanyClaim::create([

        /*
        |--------------------------------------------------------------------------
        | Canonical Identity
        |--------------------------------------------------------------------------
        */

        'company_identity_id' =>
            $companyIdentity?->id,

        /*
        |--------------------------------------------------------------------------
        | Legacy Company
        |--------------------------------------------------------------------------
        */

        'company_id' =>
            $company?->id,

        /*
        |--------------------------------------------------------------------------
        | Company Name
        |--------------------------------------------------------------------------
        */

        'claimed_company_name' =>
            $validated[
                'claimed_company_name'
            ],

        /*
        |--------------------------------------------------------------------------
        | Applicant
        |--------------------------------------------------------------------------
        */

        'user_id' =>
            $user->id,

        'full_name' =>
            $validated[
                'full_name'
            ],

        'position' =>
            $validated[
                'position'
            ],

        /*
        |--------------------------------------------------------------------------
        | Email From Authenticated Account
        |--------------------------------------------------------------------------
        */

        'email' =>
            $user->email,

        'phone' =>
            $validated[
                'phone'
            ],

        /*
        |--------------------------------------------------------------------------
        | Ownership Verification
        |--------------------------------------------------------------------------
        */

        'nib' =>
            $validated[
                'nib'
            ],

        'verification_document_type' =>
            $validated[
                'verification_document_type'
            ],

        'verification_document' =>
            $documentPath,

        /*
        |--------------------------------------------------------------------------
        | Notes
        |--------------------------------------------------------------------------
        */

        'notes' =>
            $validated[
                'notes'
            ] ?? null,

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        'status' =>
            'pending',

        'submitted_at' =>
            now(),
    ]);

    /*
    |--------------------------------------------------------------------------
    | Success
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route(
            'companies.claim.submitted'
        )
        ->with(
            'success',
            'Ownership verification request submitted successfully.'
        );
}
    public function submitted(): Response
{
    $claim = CompanyClaim::query()
        ->with([
            'company:id,nama_perusahaan',
            'companyIdentity:id,canonical_name,country_name',
        ])
        ->where(
            'user_id',
            auth()->id()
        )
        ->latest('submitted_at')
        ->first();

    return Inertia::render(
        'Onboarding/OwnershipVerificationSubmitted',
        [
            'claim' => $claim,
        ]
    );
}
}