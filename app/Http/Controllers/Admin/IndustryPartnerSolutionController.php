<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IndustryPartner;
use App\Models\IndustryPartnerSolution;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class IndustryPartnerSolutionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(IndustryPartner $partner)
    {
        $solutions = $partner->solutions()
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        return Inertia::render(
            'Admin/StrategicPartnership/Solutions/Index',
            [
                'partner' => $partner,
                'solutions' => $solutions,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(IndustryPartner $partner)
    {
        return Inertia::render(
            'Admin/StrategicPartnership/Solutions/Create',
            [
                'partner' => $partner,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        IndustryPartner $partner
    ) {
        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'short_description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'problem_solved' => [
                'nullable',
                'string',
            ],

            'solution_description' => [
                'nullable',
                'string',
            ],

            'industry_applications' => [
                'nullable',
                'string',
            ],

            'technology' => [
                'nullable',
                'string',
            ],

            'key_benefits' => [
                'nullable',
                'string',
            ],

            'is_featured' => [
                'nullable',
                'boolean',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ]);


        $validated['industry_partner_id'] =
            $partner->id;

        $validated['slug'] =
            Str::slug($validated['title']);

        $validated['is_featured'] =
            $request->boolean('is_featured');

        /*
        |--------------------------------------------------------------------------
        | NEW SOLUTION STARTS AS DRAFT
        |--------------------------------------------------------------------------
        */

        $validated['is_active'] = false;


        IndustryPartnerSolution::create(
            $validated
        );


        return redirect()
            ->route(
                'admin.industry-partner-solutions.index',
                $partner
            )
            ->with(
                'success',
                'Solution created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        IndustryPartner $partner,
        IndustryPartnerSolution $solution
    ) {
        abort_unless(
            $solution->industry_partner_id === $partner->id,
            404
        );


        return Inertia::render(
            'Admin/StrategicPartnership/Solutions/Edit',
            [
                'partner' => $partner,
                'solution' => $solution,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        IndustryPartner $partner,
        IndustryPartnerSolution $solution
    ) {
        abort_unless(
            $solution->industry_partner_id === $partner->id,
            404
        );


        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'short_description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'problem_solved' => [
                'nullable',
                'string',
            ],

            'solution_description' => [
                'nullable',
                'string',
            ],

            'industry_applications' => [
                'nullable',
                'string',
            ],

            'technology' => [
                'nullable',
                'string',
            ],

            'key_benefits' => [
                'nullable',
                'string',
            ],

            'is_featured' => [
                'nullable',
                'boolean',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ]);


        $validated['slug'] =
            Str::slug($validated['title']);

        $validated['is_featured'] =
            $request->boolean('is_featured');


        $solution->update(
            $validated
        );


        return redirect()
            ->route(
                'admin.industry-partner-solutions.index',
                $partner
            )
            ->with(
                'success',
                'Solution updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PUBLISH
    |--------------------------------------------------------------------------
    */

    public function publish(
        IndustryPartner $partner,
        IndustryPartnerSolution $solution
    ) {
        abort_unless(
            $solution->industry_partner_id === $partner->id,
            404
        );


        $solution->update([
            'is_active' => true,
        ]);


        return back()
            ->with(
                'success',
                'Solution published successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | UNPUBLISH
    |--------------------------------------------------------------------------
    */

    public function unpublish(
        IndustryPartner $partner,
        IndustryPartnerSolution $solution
    ) {
        abort_unless(
            $solution->industry_partner_id === $partner->id,
            404
        );


        $solution->update([
            'is_active' => false,
        ]);


        return back()
            ->with(
                'success',
                'Solution unpublished successfully.'
            );
    }
}