<?php

namespace App\Http\Controllers;

use App\Models\IndustryPartnerInquiry;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EcosystemPartnerController extends Controller
{
    public function index()
    {
        return Inertia::render(
            'EcosystemPartner/Index'
        );
    }

    /**
     * Strategic Solution Partner Inquiry Form
     */
    public function inquiry()
    {
        return Inertia::render(
            'EcosystemPartner/Inquiry'
        );
    }

    /**
     * Submit Strategic Solution Partner Inquiry
     */
    public function submitInquiry(Request $request)
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
            ],

            'partnership_interest' => [
                'nullable',
                'string',
            ],

            'target_market' => [
                'nullable',
                'string',
                'max:255',
            ],

            'proposed_value' => [
                'nullable',
                'string',
            ],
        ]);

        IndustryPartnerInquiry::create([
            'industry_partner_id' => null,

            'company_name' => $validated['company_name'],

            'website_url' => $validated['website_url'] ?? null,

            'contact_name' => $validated['contact_name'],

            'job_title' => $validated['job_title'] ?? null,

            'email' => $validated['email'],

            'phone' => $validated['phone'] ?? null,

            'partner_category' => $validated['partner_category'],

            'solution_description' =>
                $validated['solution_description'],

            'partnership_interest' =>
                $validated['partnership_interest'] ?? null,

            'target_market' =>
                $validated['target_market'] ?? null,

            'proposed_value' =>
                $validated['proposed_value'] ?? null,

            'status' => 'pending',

            'admin_notes' => null,

            'reviewed_at' => null,

            'source' => 'strategic_partnership',

            'locale' => app()->getLocale() === 'en'
                ? 'en'
                : 'id',
        ]);

        return redirect()
            ->route('ecosystem-partner.inquiry')
            ->with(
                'success',
                app()->getLocale() === 'en'
                    ? 'Thank you. Your Strategic Solution Partner inquiry has been submitted successfully.'
                    : 'Terima kasih. Inquiry Strategic Solution Partner Anda berhasil dikirim.'
            );
    }
}