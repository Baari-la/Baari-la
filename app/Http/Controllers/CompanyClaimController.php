<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyClaim;
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

        $validated =
            $request->validate([

                'company_id' => [
                    'nullable',
                    'integer',
                    'exists:companies,id',
                ],

                'claimed_company_name' => [
                    'required',
                    'string',
                    'max:255',
                ],

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

                'notes' => [
                    'nullable',
                    'string',
                    'max:2000',
                ],
            ]);

        /*
        |--------------------------------------------------------------------------
        | Existing Company
        |--------------------------------------------------------------------------
        */

        $company = null;

        if (!empty($validated['company_id'])) {

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
            | Pending Claim By Another User
            |--------------------------------------------------------------------------
            */

            $pendingCompanyClaim =
                CompanyClaim::where(
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
        | Existing Pending Claim By User
        |--------------------------------------------------------------------------
        */

        $existingUserClaim =
    CompanyClaim::where(
        'user_id',
        $user->id
    )
    ->where(
        'status',
        'pending'
    )
    ->latest('submitted_at')
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
        | IMPORTANT:
        | Dokumen ownership jangan disimpan di disk public.
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
        | Create Claim
        |--------------------------------------------------------------------------
        */

        CompanyClaim::create([

            'company_id' =>
                $company?->id,

            'claimed_company_name' =>
                $validated[
                    'claimed_company_name'
                ],

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

            'notes' =>
                $validated[
                    'notes'
                ] ?? null,

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
    ->route('companies.claim.submitted')
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