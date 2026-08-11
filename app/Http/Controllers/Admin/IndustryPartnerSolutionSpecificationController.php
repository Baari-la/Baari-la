<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IndustryPartner;
use App\Models\IndustryPartnerSolution;
use App\Models\IndustryPartnerSolutionSpecification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class IndustryPartnerSolutionSpecificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    |
    | Display all technical specifications belonging to a solution.
    |
    */

    public function index(
        IndustryPartner $partner,
        IndustryPartnerSolution $solution
    ) {
        $this->ensureSolutionBelongsToPartner(
            $partner,
            $solution
        );

        $specifications = $solution
            ->specifications()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return Inertia::render(
            'Admin/StrategicPartnership/Solutions/Specifications/Index',
            [
                'partner' => $partner,
                'solution' => $solution,
                'specifications' => $specifications,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    |
    | Display form for creating a new specification.
    |
    */

    public function create(
        IndustryPartner $partner,
        IndustryPartnerSolution $solution
    ) {
        $this->ensureSolutionBelongsToPartner(
            $partner,
            $solution
        );

        return Inertia::render(
            'Admin/StrategicPartnership/Solutions/Specifications/Create',
            [
                'partner' => $partner,
                'solution' => $solution,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    |
    | Store a new technical specification.
    |
    */

    public function store(
        Request $request,
        IndustryPartner $partner,
        IndustryPartnerSolution $solution
    ) {
        $this->ensureSolutionBelongsToPartner(
            $partner,
            $solution
        );

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'value' => [
                'required',
                'string',
            ],

            'unit' => [
                'nullable',
                'string',
                'max:100',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);


        $validated['sort_order'] =
            $validated['sort_order'] ?? 0;

        $validated['is_active'] =
            $request->boolean(
                'is_active',
                true
            );

        $solution->specifications()->create(
            $validated
        );


        return Redirect::route(
            'admin.industry-partner-solution-specifications.index',
            [
                'partner' => $partner->id,
                'solution' => $solution->id,
            ]
        )->with(
            'success',
            'Technical specification added successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    |
    | Display edit form.
    |
    */

    public function edit(
        IndustryPartner $partner,
        IndustryPartnerSolution $solution,
        IndustryPartnerSolutionSpecification $specification
    ) {
        $this->ensureSolutionBelongsToPartner(
            $partner,
            $solution
        );

        $this->ensureSpecificationBelongsToSolution(
            $solution,
            $specification
        );

        return Inertia::render(
            'Admin/StrategicPartnership/Solutions/Specifications/Edit',
            [
                'partner' => $partner,
                'solution' => $solution,
                'specification' => $specification,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    |
    | Update an existing technical specification.
    |
    */

    public function update(
        Request $request,
        IndustryPartner $partner,
        IndustryPartnerSolution $solution,
        IndustryPartnerSolutionSpecification $specification
    ) {
        $this->ensureSolutionBelongsToPartner(
            $partner,
            $solution
        );

        $this->ensureSpecificationBelongsToSolution(
            $solution,
            $specification
        );

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'value' => [
                'required',
                'string',
            ],

            'unit' => [
                'nullable',
                'string',
                'max:100',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);


        $validated['sort_order'] =
            $validated['sort_order'] ?? 0;

        $validated['is_active'] =
            $request->boolean(
                'is_active'
            );


        $specification->update(
            $validated
        );


        return Redirect::route(
            'admin.industry-partner-solution-specifications.index',
            [
                'partner' => $partner->id,
                'solution' => $solution->id,
            ]
        )->with(
            'success',
            'Technical specification updated successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    |
    | Delete a technical specification.
    |
    */

    public function destroy(
        IndustryPartner $partner,
        IndustryPartnerSolution $solution,
        IndustryPartnerSolutionSpecification $specification
    ) {
        $this->ensureSolutionBelongsToPartner(
            $partner,
            $solution
        );

        $this->ensureSpecificationBelongsToSolution(
            $solution,
            $specification
        );

        $specification->delete();


        return Redirect::route(
            'admin.industry-partner-solution-specifications.index',
            [
                'partner' => $partner->id,
                'solution' => $solution->id,
            ]
        )->with(
            'success',
            'Technical specification deleted successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | TOGGLE STATUS
    |--------------------------------------------------------------------------
    |
    | Activate / deactivate a technical specification.
    |
    */

    public function toggleStatus(
        IndustryPartner $partner,
        IndustryPartnerSolution $solution,
        IndustryPartnerSolutionSpecification $specification
    ) {
        $this->ensureSolutionBelongsToPartner(
            $partner,
            $solution
        );

        $this->ensureSpecificationBelongsToSolution(
            $solution,
            $specification
        );

        $specification->update([
            'is_active' => ! $specification->is_active,
        ]);


        return Redirect::route(
            'admin.industry-partner-solution-specifications.index',
            [
                'partner' => $partner->id,
                'solution' => $solution->id,
            ]
        )->with(
            'success',
            $specification->is_active
                ? 'Technical specification activated.'
                : 'Technical specification deactivated.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PROTECTION
    |--------------------------------------------------------------------------
    |
    | Make sure the requested solution really belongs to the
    | requested industry partner.
    |
    */

    protected function ensureSolutionBelongsToPartner(
        IndustryPartner $partner,
        IndustryPartnerSolution $solution
    ): void {
        abort_unless(
            (int) $solution->industry_partner_id ===
                (int) $partner->id,
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PROTECTION
    |--------------------------------------------------------------------------
    |
    | Make sure the requested specification really belongs to
    | the requested solution.
    |
    */

    protected function ensureSpecificationBelongsToSolution(
        IndustryPartnerSolution $solution,
        IndustryPartnerSolutionSpecification $specification
    ): void {
        abort_unless(
            (int) $specification->industry_partner_solution_id ===
                (int) $solution->id,
            404
        );
    }
}