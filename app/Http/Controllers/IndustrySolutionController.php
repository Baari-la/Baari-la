<?php

namespace App\Http\Controllers;

use App\Models\IndustryPartner;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class IndustrySolutionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDUSTRY SOLUTIONS INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $partners = IndustryPartner::query()
            ->where('is_active', true)
            ->orderBy('partner_category')
            ->orderByDesc('is_featured')
            ->orderBy('company_name')
            ->get();

        return Inertia::render(
            'IndustrySolutions/Index',
            [
                'partners' => $partners,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | INDUSTRY SOLUTION CATEGORY
    |--------------------------------------------------------------------------
    */

    public function show(string $category)
    {
        $categories = [

            'testing-certification' => [
                'db' => 'testing_certification',
                'title' => 'Testing & Certification',
                'icon' => 'fa-shield-halved',
                'description' =>
                    'Quality assurance, laboratory testing, certification, and compliance solutions.',
                'cta_title' =>
                    'Become A Testing & Certification Partner',
            ],

            'industrial-machinery' => [
                'db' => 'machinery',
                'title' => 'Industrial Machinery',
                'icon' => 'fa-gears',
                'description' =>
                    'Knitting, weaving, dyeing, finishing, and textile manufacturing technologies.',
                'cta_title' =>
                    'Become An Industrial Machinery Partner',
            ],

            'technology-solutions' => [
                'db' => 'technology',
                'title' => 'Technology Solutions',
                'icon' => 'fa-microchip',
                'description' =>
                    'ERP, PLM, AI, Industry 4.0, and digital transformation solutions.',
                'cta_title' =>
                    'Become A Technology Partner',
            ],

            'raw-materials' => [
                'db' => 'raw_material',
                'title' => 'Raw Materials',
                'icon' => 'fa-boxes-stacked',
                'description' =>
                    'Fiber, yarn, fabrics, chemicals, and textile materials.',
                'cta_title' =>
                    'Become A Raw Materials Partner',
            ],

            'logistics-supply-chain' => [
                'db' => 'logistics',
                'title' => 'Logistics & Supply Chain',
                'icon' => 'fa-truck',
                'description' =>
                    'Domestic and international logistics, warehousing, and trade support.',
                'cta_title' =>
                    'Become A Logistics & Supply Chain Partner',
            ],

            'trade-finance' => [
                'db' => 'finance',
                'title' => 'Trade Finance',
                'icon' => 'fa-building-columns',
                'description' =>
                    'Financing solutions supporting industrial growth and export activities.',
                'cta_title' =>
                    'Become A Trade Finance Partner',
            ],

            'exhibitions-events' => [
                'db' => 'association',
                'title' => 'Exhibitions & Events',
                'icon' => 'fa-calendar-days',
                'description' =>
                    'Trade fairs, business matching, networking, and industry events.',
                'cta_title' =>
                    'Become An Exhibitions & Events Partner',
            ],

            'research-education' => [
                'db' => 'institution',
                'title' => 'Research & Education',
                'icon' => 'fa-graduation-cap',
                'description' =>
                    'Universities, research institutions, training centers, and workforce development.',
                'cta_title' =>
                    'Become A Research & Education Partner',
            ],
        ];


        abort_unless(
            isset($categories[$category]),
            404
        );


        $config = $categories[$category];


        $partners = IndustryPartner::query()
            ->where(
                'partner_category',
                $config['db']
            )
            ->where('is_active', true)
            ->orderByDesc('is_featured')
            ->orderBy('company_name')
            ->get();


        return Inertia::render(
            'IndustrySolutions/Show',
            [
                'category' => $config,
                'partners' => $partners,
            ]
        );
    }

    /*
|--------------------------------------------------------------------------
| PUBLIC PARTNER PROFILE
|--------------------------------------------------------------------------
*/

public function showPartner(
    \App\Models\IndustryPartner $partner
) {
    abort_unless(
        $partner->is_active,
        404
    );

    return Inertia::render(
        'IndustrySolutions/PartnerShow',
        [
            'partner' => $partner,
        ]
    );
}

    /*
    |--------------------------------------------------------------------------
    | STRATEGIC SOLUTION PARTNER INQUIRY
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return Inertia::render(
            'EcosystemPartner/Inquiry'
        );
    }
    

    /*
    |--------------------------------------------------------------------------
    | STORE STRATEGIC PARTNER INQUIRY
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

            'country' => [
                'nullable',
                'string',
                'max:100',
            ],

            'contact_person' => [
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

            'website' => [
                'nullable',
                'url',
                'max:255',
            ],

            'solution_category' => [
                'required',
                'string',
                'max:100',
            ],

            'solution_description' => [
                'required',
                'string',
                'max:5000',
            ],

            'discussion_topic' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'preferred_contact_method' => [
                'required',
                'string',
                'max:100',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | MAP SOLUTION CATEGORY
        |--------------------------------------------------------------------------
        */

        $categoryMap = [

            'Technology Solutions'
                => 'technology',

            'Testing & Certification'
                => 'testing_certification',

            'Industrial Machinery'
                => 'machinery',

            'Raw Materials'
                => 'raw_material',

            'Logistics & Supply Chain'
                => 'logistics',

            'Trade Finance'
                => 'finance',

            'Sustainability Solutions'
                => 'technology',

            'Exhibitions & Events'
                => 'association',

            'Research & Education'
                => 'institution',

            'Lainnya'
                => 'institution',
        ];


        $partnerCategory =
            $categoryMap[
                $validated['solution_category']
            ] ?? 'technology';


        /*
        |--------------------------------------------------------------------------
        | GENERATE SLUG
        |--------------------------------------------------------------------------
        */

        $baseSlug = Str::slug(
            $validated['company_name']
        );

        $slug = $baseSlug;

        $counter = 1;

        while (
            IndustryPartner::where(
                'slug',
                $slug
            )->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }


        /*
        |--------------------------------------------------------------------------
        | CREATE INDUSTRY PARTNER RECORD
        |--------------------------------------------------------------------------
        */

        IndustryPartner::create([

            'company_name' =>
                $validated['company_name'],

            'slug' =>
                $slug,

            'partner_category' =>
                $partnerCategory,

            'partner_level' =>
                'strategic_solution_partner',

            'country' =>
                $validated['country'] ?? null,

            'website_url' =>
                $validated['website'] ?? null,

            'contact_person' =>
                $validated['contact_person'],

            'job_title' =>
                $validated['job_title'] ?? null,

            'contact_email' =>
                $validated['email'],

            'contact_phone' =>
                $validated['phone'] ?? null,

            'solution_title' =>
                $validated['solution_category'],

            'solution_description' =>
                $validated['solution_description'],

            'solution_value' =>
                null,

            'target_market' =>
                null,

            'target_industry' =>
                'Textile & Apparel',

            'partnership_objectives' =>
                $validated['discussion_topic'] ?? null,

            'status' =>
                'inquiry',

            'annual_fee' =>
                12000,

            'currency' =>
                'USD',

            'is_featured' =>
                false,

            'is_active' =>
                false,
        ]);


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'strategic-partnership.create'
            )
            ->with(
                'success',
                'Your Strategic Solution Partner inquiry has been submitted successfully.'
            );
    }
}