<?php

namespace App\Http\Controllers;

use App\Models\IndustryPartnerInquiry;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StrategicPartnershipController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return Inertia::render(
            'Partnership/Create'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => [
                'required',
                'string',
                'max:255',
            ],

            'website_url' => [
                'nullable',
                'url',
                'max:255',
            ],

            'contact_name' => [
                'required',
                'string',
                'max:255',
            ],

            'job_title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'partner_category' => [
                'required',
                'string',
                'max:100',
            ],

            'solution_description' => [
                'required',
                'string',
                'min:20',
            ],

            'partnership_interest' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'target_market' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'proposed_value' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $validated['status'] = 'pending';

        $validated['source'] = 'strategic_partnership';

        $validated['locale'] =
            app()->getLocale();

        $inquiry =
            IndustryPartnerInquiry::create(
                $validated
            );

        return redirect()
            ->route(
                'strategic-partnership.thank-you'
            )
            ->with(
                'success',
                'Your Strategic Partnership Inquiry has been submitted successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | THANK YOU
    |--------------------------------------------------------------------------
    */

    public function thankYou()
    {
        return Inertia::render(
            'Partnership/ThankYou'
        );
    }
}