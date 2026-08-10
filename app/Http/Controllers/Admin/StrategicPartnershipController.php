<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IndustryPartner;
use App\Models\IndustryPartnerInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class StrategicPartnershipController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = IndustryPartnerInquiry::query();

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where(
                    'company_name',
                    'like',
                    "%{$search}%"
                )
                    ->orWhere(
                        'contact_name',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'email',
                        'like',
                        "%{$search}%"
                    );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CATEGORY
        |--------------------------------------------------------------------------
        */

        if ($request->filled('category')) {
            $query->where(
                'partner_category',
                $request->category
            );
        }

        /*
        |--------------------------------------------------------------------------
        | STATS
        |--------------------------------------------------------------------------
        */

        $stats = [
            'total' => IndustryPartnerInquiry::count(),

            'pending' => IndustryPartnerInquiry::where(
                'status',
                'pending'
            )->count(),

            'reviewing' => IndustryPartnerInquiry::where(
                'status',
                'reviewing'
            )->count(),

            'contacted' => IndustryPartnerInquiry::where(
                'status',
                'contacted'
            )->count(),

            'approved' => IndustryPartnerInquiry::where(
                'status',
                'approved'
            )->count(),

            'rejected' => IndustryPartnerInquiry::where(
                'status',
                'rejected'
            )->count(),
        ];

        $inquiries = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render(
            'Admin/StrategicPartnership/Index',
            [
                'inquiries' => $inquiries,

                'stats' => $stats,

                'filters' => [
                    'search' => $request->search,
                    'status' => $request->status,
                    'category' => $request->category,
                ],
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        IndustryPartnerInquiry $inquiry
    ) {
        $inquiry->load(
            'industryPartner'
        );

        return Inertia::render(
            'Admin/StrategicPartnership/Show',
            [
                'inquiry' => $inquiry,
                'locale' => app()->getLocale(),
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE STATUS
    |--------------------------------------------------------------------------
    */

    public function updateStatus(
        Request $request,
        IndustryPartnerInquiry $inquiry
    ) {
        $validated = $request->validate([
            'status' => [
                'required',
                'in:pending,reviewing,contacted,approved,rejected',
            ],

            'admin_notes' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $inquiry->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? null,
            'reviewed_at' => now(),
        ]);

        return back()->with(
            'success',
            'Inquiry review has been updated successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | APPROVE
    |--------------------------------------------------------------------------
    */

    public function approve(
        IndustryPartnerInquiry $inquiry
    ) {
        /*
        |--------------------------------------------------------------------------
        | Prevent duplicate approval
        |--------------------------------------------------------------------------
        */

        if ($inquiry->industry_partner_id) {
            return back()->with(
                'success',
                'This inquiry has already been converted into an Industry Partner.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Create Industry Partner
        |--------------------------------------------------------------------------
        */

        $partner = IndustryPartner::create([
            'company_id' => null,

            'company_name' =>
                $inquiry->company_name,

            'slug' =>
                Str::slug(
                    $inquiry->company_name
                ),

            'partner_category' =>
                $inquiry->partner_category,

            'partner_level' =>
                'platinum',

            'logo_url' => null,

            'website_url' =>
                $inquiry->website_url,

            'short_description' =>
                $inquiry->solution_description,

            'is_featured' => false,

            'is_active' => false,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Link Inquiry
        |--------------------------------------------------------------------------
        */

        $inquiry->update([
            'industry_partner_id' =>
                $partner->id,

            'status' =>
                'approved',

            'reviewed_at' =>
                now(),
        ]);

        return back()->with(
            'success',
            'Strategic Partner approved successfully. Complete the partner profile before publishing.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT PARTNER PROFILE
    |--------------------------------------------------------------------------
    */

    public function edit(
        IndustryPartner $partner
    ) {
        $categories = [
            [
                'value' => 'machinery',
                'label' => 'Textile & Garment Machinery',
            ],

            [
                'value' => 'testing_certification',
                'label' => 'Testing & Certification',
            ],

            [
                'value' => 'technology',
                'label' => 'Technology Solutions',
            ],

            [
                'value' => 'energy',
                'label' => 'Energy & Utilities',
            ],

            [
                'value' => 'logistics',
                'label' => 'Logistics & Supply Chain',
            ],

            [
                'value' => 'erp_plm',
                'label' => 'ERP & PLM',
            ],

            [
                'value' => 'ai_digital',
                'label' => 'AI & Digital Transformation',
            ],

            [
                'value' => 'digital_printing',
                'label' => 'Digital Textile Printing',
            ],

            [
                'value' => 'sustainability',
                'label' => 'Sustainability & Circularity',
            ],

            [
                'value' => 'raw_material',
                'label' => 'Raw Materials & Textile Chemicals',
            ],

            [
                'value' => 'finance',
                'label' => 'Trade Finance & Insurance',
            ],

            [
                'value' => 'association',
                'label' => 'Exhibitions & Events',
            ],

            [
                'value' => 'institution',
                'label' => 'Research & Education',
            ],
        ];


        $levels = [
            [
                'value' => 'bronze',
                'label' => 'Bronze Partner',
            ],

            [
                'value' => 'silver',
                'label' => 'Silver Partner',
            ],

            [
                'value' => 'gold',
                'label' => 'Gold Partner',
            ],

            [
                'value' => 'platinum',
                'label' => 'Platinum Partner',
            ],
        ];


        $completeness = $this->profileCompleteness(
            $partner
        );


        return Inertia::render(
            'Admin/StrategicPartnership/Edit',
            [
                'partner' => $partner,

                'categories' => $categories,

                'levels' => $levels,

                'completeness' => $completeness,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE PARTNER PROFILE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        IndustryPartner $partner
    ) {
        $validated = $request->validate([
            'company_name' => [
                'required',
                'string',
                'max:255',
            ],

            'partner_category' => [
                'required',
                'in:machinery,testing_certification,technology,energy,logistics,erp_plm,ai_digital,digital_printing,sustainability,raw_material,finance,association,institution',
            ],

            'partner_level' => [
                'required',
                'in:bronze,silver,gold,platinum',
            ],

            'logo_url' => [
                'nullable',
                'url',
                'max:255',
            ],

            'website_url' => [
                'nullable',
                'url',
                'max:255',
            ],

            'short_description' => [
                'required',
                'string',
                'min:20',
                'max:5000',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Slug
        |--------------------------------------------------------------------------
        */

        $validated['slug'] =
            Str::slug(
                $validated['company_name']
            );


        $partner->update(
            $validated
        );


        return back()->with(
            'success',
            'Industry Partner profile has been updated successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PUBLISH
    |--------------------------------------------------------------------------
    */

    public function publish(
        IndustryPartner $partner
    ) {
        $missing = [];


        if (
            blank(
                $partner->company_name
            )
        ) {
            $missing[] =
                'Company Name';
        }


        if (
            blank(
                $partner->partner_category
            )
        ) {
            $missing[] =
                'Partner Category';
        }


        if (
            blank(
                $partner->short_description
            )
        ) {
            $missing[] =
                'Short Description';
        }


        if (
            blank(
                $partner->website_url
            )
        ) {
            $missing[] =
                'Website';
        }


        if (
            blank(
                $partner->logo_url
            )
        ) {
            $missing[] =
                'Logo';
        }


        if (
            count($missing) > 0
        ) {
            return back()
                ->withErrors([
                    'publish' =>
                        'Partner profile is not complete. Missing: ' .
                        implode(
                            ', ',
                            $missing
                        ),
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Publish
        |--------------------------------------------------------------------------
        */

        $partner->update([
            'is_active' => true,
        ]);


        return redirect()
            ->route(
                'admin.industry-partners.edit',
                $partner->id
            )
            ->with(
                'success',
                'Industry Partner has been published successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PROFILE COMPLETENESS
    |--------------------------------------------------------------------------
    */

    private function profileCompleteness(
        IndustryPartner $partner
    ): array {
        $fields = [
            'company_name' =>
                filled(
                    $partner->company_name
                ),

            'partner_category' =>
                filled(
                    $partner->partner_category
                ),

            'partner_level' =>
                filled(
                    $partner->partner_level
                ),

            'short_description' =>
                filled(
                    $partner->short_description
                ),

            'website_url' =>
                filled(
                    $partner->website_url
                ),

            'logo_url' =>
                filled(
                    $partner->logo_url
                ),
        ];


        $total =
            count($fields);

        $completed =
            count(
                array_filter(
                    $fields
                )
            );


        $percentage =
            $total > 0
                ? round(
                    ($completed / $total) * 100
                )
                : 0;


        return [
            'percentage' =>
                $percentage,

            'completed' =>
                $completed,

            'total' =>
                $total,

            'fields' =>
                $fields,
        ];
    }
}