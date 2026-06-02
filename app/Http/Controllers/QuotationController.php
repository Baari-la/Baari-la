<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\Rfq;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
    public function store(Request $request, Rfq $rfq)
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
        |
        | Untuk sementara ambil dari user login.
        | Nanti bisa disesuaikan jika satu user
        | memiliki lebih dari satu perusahaan.
        |
        */

        $companyId = auth()->user()->company_id;

if (!$companyId) {
    return back()->withErrors([
        'company' => 'Your account is not linked to a company.',
    ]);
}

/*
|--------------------------------------------------------------------------
| RFQ MUST BE OPEN
|--------------------------------------------------------------------------
*/

if ($rfq->status !== 'open') {
    return back()->withErrors([
        'rfq' => 'This RFQ is no longer accepting quotations.',
    ]);
}

/*
|--------------------------------------------------------------------------
| PREVENT DUPLICATE QUOTATION
|--------------------------------------------------------------------------
*/

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
    if ($quotation->status !== 'accepted') {
        return back()->withErrors([
            'quotation' =>
                'Only accepted quotations can be awarded.',
        ]);
    }

    $rfq = $quotation->rfq;

    $quotation->update([
        'status' => 'awarded',
    ]);

    $rfq->update([
        'status' => 'awarded',
        'awarded_quotation_id' => $quotation->id,
    ]);

    return back()->with(
        'success',
        'Supplier awarded successfully.'
    );
}

public function close(Rfq $rfq)
{
    /*
    |--------------------------------------------------------------------------
    | OWNER ONLY
    |--------------------------------------------------------------------------
    */

    if ($rfq->user_id !== auth()->id()) {
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
        $quotation->delete();

        return back()->with(
            'success',
            'Quotation deleted successfully.'
        );
    }
}