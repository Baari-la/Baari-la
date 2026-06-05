<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\GenerateRFQService;
use App\Models\CollectiveSourcingGroup;
use App\Models\CollectiveSourcingRequest;

class CollectiveSourcingController extends Controller
{
    /**
     * Open Demand Groups
     */
    public function index(): Response
    {
        $groups = CollectiveSourcingGroup::query()
            ->withCount('requests')
            ->latest()
            ->paginate(20)
            ->through(function ($group) {

                $progress = 0;

                if ($group->moq_quantity > 0) {

                    $progress = round(
                        (
                            $group->current_quantity /
                            $group->moq_quantity
                        ) * 100,
                        2
                    );
                }

                $progress = min(
    100,
    round(
        ($group->current_quantity / $group->moq_quantity) * 100,
        2
    )
);

                return [

                    'id' => $group->id,

                    'group_code' => $group->group_code,

                    'product_category' =>
                        $group->product_category,

                    'product_name' =>
                        $group->product_name,

                    'specification' =>
                        $group->specification,

                    'unit' =>
                        $group->unit,

                    'moq_quantity' =>
                        $group->moq_quantity,

                    'current_quantity' =>
                        $group->current_quantity,

                    'status' =>
                        $group->status,

                    'members_count' =>
                        $group->requests_count,

                    'progress' =>
                        $progress,
                ];
            });

        return Inertia::render(
            'CollectiveSourcing/Index',
            [
                'groups' => $groups,
            ]
        );
    }

    /**
     * Create Requirement
     */
    public function create(): Response
    {
        return Inertia::render(
            'CollectiveSourcing/Create'
        );
    }

    /**
     * Store Requirement
     */
    public function store(Request $request)
{
    $validated = $request->validate([

        'product_category' => [
            'required',
            'string',
            'max:255',
        ],

        'product_name' => [
            'required',
            'string',
            'max:255',
        ],

        'specification' => [
            'nullable',
            'string',
        ],

        'unit' => [
            'required',
            'string',
            'max:50',
        ],

        'moq_quantity' => [
            'required',
            'numeric',
            'min:1',
        ],

        'quantity' => [
            'required',
            'numeric',
            'min:1',
        ],

        'required_month' => [
            'nullable',
            'date_format:Y-m',
        ],

        'destination_country' => [
            'nullable',
            'string',
            'max:255',
        ],

        'destination_city' => [
            'nullable',
            'string',
            'max:255',
        ],

        'notes' => [
            'nullable',
            'string',
        ],
    ]);

    /*
    |--------------------------------------------------------------------------
    | COMPANY CHECK
    |--------------------------------------------------------------------------
    */

    $companyId = auth()->user()->company_id;

    if (!$companyId) {

        return back()->withErrors([
            'company' =>
                'Your account is not linked to a company.',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND EXISTING OPEN GROUP
    |--------------------------------------------------------------------------
    */

    $group = CollectiveSourcingGroup::where(
            'product_name',
            $validated['product_name']
        )
        ->where(
            'specification',
            $validated['specification']
        )
        ->where(
            'unit',
            $validated['unit']
        )
        ->where(
            'status',
            'open'
        )
        ->first();

    /*
    |--------------------------------------------------------------------------
    | CREATE GROUP IF NOT FOUND
    |--------------------------------------------------------------------------
    */

    if (!$group) {

        $group = CollectiveSourcingGroup::create([

            'group_code' =>
                'CSG-' . now()->format('YmdHis'),

            'product_category' =>
                $validated['product_category'],

            'product_name' =>
                $validated['product_name'],

            'specification' =>
                $validated['specification'],

            'unit' =>
                $validated['unit'],

            'moq_quantity' =>
                $validated['moq_quantity'],

            'current_quantity' =>
                0,

            'status' =>
                'open',

            'created_by' =>
                auth()->id(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PREVENT DUPLICATE JOIN
    |--------------------------------------------------------------------------
    */

    $alreadyJoined = CollectiveSourcingRequest::where(
            'group_id',
            $group->id
        )
        ->where(
            'company_id',
            $companyId
        )
        ->exists();

    if ($alreadyJoined) {

        return back()->withErrors([
            'group' =>
                'Your company has already joined this demand group.',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE REQUEST
    |--------------------------------------------------------------------------
    */

    CollectiveSourcingRequest::create([

        'group_id' =>
            $group->id,

        'company_id' =>
            $companyId,

        'quantity' =>
            $validated['quantity'],

        'required_month' =>
            $validated['required_month']
            ?? null,

        'destination_country' =>
            $validated['destination_country']
            ?? null,

        'destination_city' =>
            $validated['destination_city']
            ?? null,

        'notes' =>
            $validated['notes']
            ?? null,

        'status' =>
            'joined',
    ]);

    /*
    |--------------------------------------------------------------------------
    | RECALCULATE GROUP QUANTITY
    |--------------------------------------------------------------------------
    */

    $currentQuantity =
        CollectiveSourcingRequest::where(
            'group_id',
            $group->id
        )->sum('quantity');

    $group->update([
        'current_quantity' =>
            $currentQuantity,
    ]);

    $group->refresh();

    /*
    |--------------------------------------------------------------------------
    | MOQ REACHED
    |--------------------------------------------------------------------------
    */

    if (
        $group->current_quantity >=
        $group->moq_quantity
    ) {

        $group->update([
            'status' =>
                'moq_reached',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | REDIRECT
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route(
            'collective-sourcing.my-requests'
        )
        ->with(
            'success',
            'Requirement submitted successfully.'
        );
}

    /**
     * My Requests
     */
    public function myRequests(): Response
    {

    
        $companyId = auth()->user()->company_id;

        $requests = CollectiveSourcingRequest::with([
                'group',
                'company',
            ])
            ->where(
                'company_id',
                $companyId
            )
            ->latest()
            ->paginate(20);

        return Inertia::render(
            'CollectiveSourcing/MyRequests',
            [
                'requests' => $requests,
            ]
        );
    }


   
    /**
     * My Groups
     */
    public function myGroups(): Response
    {
        $companyId = auth()->user()->company_id;

        $groupIds = CollectiveSourcingRequest::where(
                'company_id',
                $companyId
            )
            ->pluck('group_id');

        $groups = CollectiveSourcingGroup::whereIn(
                'id',
                $groupIds
            )
            ->withCount('requests')
            ->latest()
            ->paginate(20);

        return Inertia::render(
            'CollectiveSourcing/MyGroups',
            [
                'groups' => $groups,
            ]
        );
    }
public function show(
    CollectiveSourcingGroup $group
) {
    $group->load([
        'members.company',
    ]);

    return Inertia::render(
        'CollectiveSourcing/Groups/Show',
        [
            'group' => $group,
        ]
    );
}

public function generateRfq(
    CollectiveSourcingGroup $group
) {

   if (
    $group->current_quantity <
    $group->moq_quantity
) {
    return back()->with(
        'error',
        'MOQ target not reached.'
    );
}

    $rfq = GenerateRFQService::run(
        $group
    );

    return redirect()->route(
        'rfqs.show',
        $rfq
    );
}

    public function showGroup(CollectiveSourcingGroup $group
) 

{
    $group->load([
        'requests.company',
    ]);

   
    return Inertia::render(
        'CollectiveSourcing/Show',
        [
            'group' => $group,
        ]
    );
}
}