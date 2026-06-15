<?php

namespace App\Http\Controllers;
use App\Models\CompanyUpdate;
use App\Models\Company;
use App\Services\CompanyLocationService;
use Illuminate\Http\Request;

class CompanyLocationController extends Controller
{
    public function update(
        Request $request,
        Company $company
    ) {

       CompanyUpdate::create([
    'company_id' => $company->id,
    'user_id' => auth()->id(),

    'proposed_data' => [
        'type' => 'locations',
        'locations' => $request->locations ?? [],
    ],

    'status' => 'pending',
]);

        return back()->with(
            'Location update has been submitted and is awaiting verification.'
        );
    }
}