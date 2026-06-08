<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDispute;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class PurchaseOrderDisputeController extends Controller
{
    public function store(
        Request $request,
        PurchaseOrder $purchaseOrder
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | BUYER ONLY
        |--------------------------------------------------------------------------
        */

        if (
            auth()->id() !==
            $purchaseOrder->buyer_id
        ) {
            return back()->withErrors([
                'dispute' =>
                    'Only buyer can create disputes.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | GOODS MUST BE RECEIVED
        |--------------------------------------------------------------------------
        */

        if (!$purchaseOrder->goods_received_at) {
            return back()->withErrors([
                'dispute' =>
                    'Goods must be received before creating dispute.',
            ]);
        }

        $validated = $request->validate([
            'category' => [
                'required',
                'string',
                'max:100',
            ],

            'description' => [
                'required',
                'string',
            ],
        ]);

$activeDispute = $purchaseOrder
    ->disputes()
    ->whereIn('status', [
        'open',
        'under_review',
    ])
    ->exists();

if ($activeDispute) {
    return back()->withErrors([
        'dispute' =>
            'There is already an active dispute for this Purchase Order.',
    ]);
}
        

        PurchaseOrderDispute::create([
            'purchase_order_id' =>
                $purchaseOrder->id,

            'created_by' =>
                auth()->id(),

            'dispute_number' =>
                'DSP-' . now()->format('YmdHis'),

            'category' =>
                $validated['category'],

            'description' =>
                $validated['description'],

            'status' =>
                'open',
        ]);

        return back()->with(
            'success',
            'Dispute submitted successfully.'
        );
    }
public function respond(
    Request $request,
    PurchaseOrderDispute $dispute
): RedirectResponse {

    $validated = $request->validate([
        'supplier_response' => [
            'required',
            'string',
        ],
    ]);

    $purchaseOrder =
        $dispute->purchaseOrder;

    if (
        auth()->user()->company_id !==
        $purchaseOrder->supplier_company_id
    ) {
        abort(403);
    }

    $dispute->update([
        'supplier_response' =>
            $validated['supplier_response'],

        'status' =>
            'under_review',

        'reviewed_at' =>
            now(),
    ]);

    return back()->with(
        'success',
        'Supplier response submitted.'
    );
}

public function resolve(
    PurchaseOrderDispute $dispute
): RedirectResponse {

    $purchaseOrder = $dispute->purchaseOrder;

    /*
    |--------------------------------------------------------------------------
    | BUYER ONLY
    |--------------------------------------------------------------------------
    */

    if (
        auth()->id() !==
        $purchaseOrder->buyer_id
    ) {
        return back()->withErrors([
            'dispute' =>
                'Only buyer can resolve dispute.',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ONLY UNDER REVIEW
    |--------------------------------------------------------------------------
    */

    if (
        $dispute->status !== 'under_review'
    ) {
        return back()->withErrors([
            'dispute' =>
                'Only disputes under review can be resolved.',
        ]);
    }

    $dispute->update([
        'status' => 'resolved',
        'resolved_at' => now(),
    ]);

    return back()->with(
        'success',
        'Dispute resolved successfully.'
    );
}

public function close(
    PurchaseOrderDispute $dispute
): RedirectResponse {

    if (
        $dispute->status !== 'resolved'
    ) {
        return back()->withErrors([
            'dispute' =>
                'Only resolved disputes can be closed.',
        ]);
    }

    $dispute->update([
        'status' => 'closed',
        'closed_at' => now(),
    ]);

    return back()->with(
        'success',
        'Dispute closed successfully.'
    );
}
    }