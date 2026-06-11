<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyClaim;
use Illuminate\Http\Request;

class CompanyClaimController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(
        Company $company
    )
    {
if (
    auth()->user()->company_id
) {
    return back()->with(
        'error',
        'You already manage a company.'
    );
}
    
        if (
            $company->claimed_by_user_id
        ) {

            return back()->with(
                'error',
                'Company already claimed.'
            );
        }

        $existingClaim =
            CompanyClaim::where(
                'company_id',
                $company->id
            )
            ->where(
                'user_id',
                auth()->id()
            )
            ->where(
                'status',
                'pending'
            )
            ->exists();

        if ($existingClaim) {

            return back()->with(
                'error',
                'You already have a pending claim.'
            );
        }
$pendingClaim =
    CompanyClaim::where(
        'company_id',
        $company->id
    )
    ->where(
        'status',
        'pending'
    )
    ->exists();

if ($pendingClaim) {

    return back()->with(
        'error',
        'This company already has a pending claim request.'
    );
}


        CompanyClaim::create([

            'company_id' =>
                $company->id,

            'user_id' =>
                auth()->id(),

            'full_name' =>
                auth()->user()->name,

            'email' =>
                auth()->user()->email,

            'status' =>
                'pending',

            'submitted_at' =>
                now(),
        ]);

        return back()->with(
            'success',
            'Claim request submitted successfully.'
        );
    }
}