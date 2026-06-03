<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\Rfq;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\PurchaseOrder;

class QuotationController extends Controller
{
public function index()
{
    $companyId = auth()->user()->company_id;

    $quotations = Quotation::with('rfq')
        ->where('company_id', $companyId)
        ->latest()
        ->get();

    return Inertia::render(
        'Quotation/Index',
        [
            'quotations' => $quotations,
        ]
    );
}    


/**
     * Simpan quotation supplier
     */
    public function store(
    Request $request,
    Rfq $rfq
)
{
    $validated = $request->validate([

        'unit_price' => [
            'required',
            'numeric',
            'min:0',
        ],

        'minimum_order_quantity' => [
            'nullable',
            'numeric',
            'min:0',
        ],

        'lead_time_days' => [
            'nullable',
            'integer',
            'min:0',
        ],

        'remarks' => [
            'nullable',
            'string',
        ],
    ]);

    /*
    |--------------------------------------------------------------------------
    | COMPANY ID
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
    | BUYER CANNOT QUOTE OWN RFQ
    |--------------------------------------------------------------------------
    */

    if ($rfq->company_id == $companyId) {

        return back()->withErrors([
            'quotation' =>
                'You cannot submit a quotation to your own RFQ.',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | RFQ MUST BE OPEN
    |--------------------------------------------------------------------------
    */

    if ($rfq->status !== 'open') {

        return back()->withErrors([
            'rfq' =>
                'This RFQ is no longer accepting quotations.',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | QUOTATION DEADLINE
    |--------------------------------------------------------------------------
    */

    if (
        $rfq->quotation_deadline &&
        now()->gt(
            \Carbon\Carbon::parse(
                $rfq->quotation_deadline
            )->endOfDay()
        )
    ) {

        return back()->withErrors([
            'quotation' =>
                'Quotation deadline has passed.',
        ]);
    }


$exists = Quotation::where('rfq_id', $rfq->id)
    ->where('company_id', $companyId)
    ->exists();

if ($exists) {
    return back()->withErrors([
        'quotation' => 'You have already submitted a quotation.',
    ]);
}

/*
|--------------------------------------------------------------------------
| CREATE QUOTATION
|--------------------------------------------------------------------------
*/

Quotation::create([
    'rfq_id' => $rfq->id,

    'company_id' => $companyId,

    'unit_price' => $validated['unit_price'],

    'minimum_order_quantity' =>
        $validated['minimum_order_quantity'] ?? null,

    'lead_time_days' =>
        $validated['lead_time_days'] ?? null,

    'remarks' =>
        $validated['remarks'] ?? null,

    'status' => 'submitted',
]);

return redirect()
    ->route('rfqs.show', $rfq)
    ->with(
        'success',
        'Quotation submitted successfully.'
    );
    }

    /**
     * Detail quotation
     */
    public function show(Quotation $quotation)
    {
        $quotation->load([
            'rfq',
            'company',
        ]);

        return Inertia::render(
            'Quotation/Show',
            [
                'quotation' => $quotation,
            ]
        );
    }
public function accept(Quotation $quotation)
{
    if (
        $quotation->rfq->company_id != auth()->user()->company_id
    ) {
        abort(403);
    }

    $quotation->update([
        'status' => 'accepted',
    ]);

    return back()->with(
        'success',
        'Quotation accepted.'
    );
}

public function reject(Quotation $quotation)
{
    if (
        $quotation->rfq->company_id != auth()->user()->company_id
    ) {
        abort(403);
    }

    $quotation->update([
        'status' => 'rejected',
    ]);

    return back()->with(
        'success',
        'Quotation rejected.'
    );
}
   
public function award(Quotation $quotation)
{
    /*
    |--------------------------------------------------------------------------
    | ONLY ACCEPTED QUOTATION
    |--------------------------------------------------------------------------
    */

    if ($quotation->status !== 'accepted') {
        return back()->withErrors([
            'quotation' =>
                'Only accepted quotations can be awarded.',
        ]);
    }

   $rfq = $quotation->rfq;

if (
    $rfq->company_id != auth()->user()->company_id
) {
    abort(403);
}

    /*
    |--------------------------------------------------------------------------
    | RFQ ALREADY AWARDED ?
    |--------------------------------------------------------------------------
    */

    if ($rfq->awarded_quotation_id) {
        return back()->withErrors([
            'quotation' =>
                'This RFQ already has an awarded supplier.',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE QUOTATION
    |--------------------------------------------------------------------------
    */

    $quotation->update([
        'status' => 'awarded',
    ]);

    /*
    |--------------------------------------------------------------------------
    | UPDATE RFQ
    |--------------------------------------------------------------------------
    */

    $rfq->update([
    'status' => 'awarded',
    'awarded_quotation_id' => $quotation->id,
    'awarded_at' => now(),
]);

    /*
    |--------------------------------------------------------------------------
    | PREVENT DUPLICATE PO
    |--------------------------------------------------------------------------
    */

    $exists = PurchaseOrder::where(
        'quotation_id',
        $quotation->id
    )->exists();

    if (!$exists) {

        $poNumber =
            'PO-' .
            now()->format('YmdHis');

        PurchaseOrder::create([

            'rfq_id' =>
                $rfq->id,

            'quotation_id' =>
                $quotation->id,

            'buyer_id' =>
                $rfq->user_id,

            'supplier_company_id' =>
                $quotation->company_id,

            'po_number' =>
                $poNumber,

            'unit_price' =>
                $quotation->unit_price,

            'quantity' =>
                $rfq->required_quantity,

            'total_amount' =>
                $quotation->unit_price *
                $rfq->required_quantity,

            'currency' => $rfq->currency ?? 'USD',

            'delivery_date' =>
                $rfq->required_delivery_date,

            'status' =>
                'pending',
        ]);
    }

    return back()->with(
        'success',
        'Supplier awarded and PO created successfully.'
    );
}
public function close(Rfq $rfq)
{
    /*
    |--------------------------------------------------------------------------
    | OWNER ONLY
    |--------------------------------------------------------------------------
    */

    if (
    $rfq->company_id != auth()->user()->company_id
) {
    abort(403);
}

    /*
    |--------------------------------------------------------------------------
    | MUST BE AWARDED FIRST
    |--------------------------------------------------------------------------
    */

    if ($rfq->status !== 'awarded') {
        return back()->withErrors([
            'rfq' => 'Only awarded RFQs can be closed.',
        ]);
    }

    $rfq->update([
        'status' => 'closed',
    ]);

    return back()->with(
        'success',
        'RFQ closed successfully.'
    );
}

public function myQuotations()
{
    $companyId = auth()->user()->company_id;

    $quotations = Quotation::with([
        'rfq',
    ])
    ->where('company_id', $companyId)
    ->latest()
    ->paginate(20);

    return Inertia::render(
        'Quotation/MyQuotations',
        [
            'quotations' => $quotations,
        ]
    );
}
    /**
     * Hapus quotation
     */
   public function destroy(Quotation $quotation)
{
    if (
        $quotation->company_id != auth()->user()->company_id
    ) {
        abort(403);
    }

    $quotation->delete();

    return back()->with(
        'success',
        'Quotation deleted successfully.'
    );
}
}