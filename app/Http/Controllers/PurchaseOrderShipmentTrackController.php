<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderShipmentTrack;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class PurchaseOrderShipmentTrackController extends Controller
{
    public function store(
    Request $request,
    PurchaseOrder $purchaseOrder
): RedirectResponse {

    if (
        auth()->user()->company_id !==
        $purchaseOrder->supplier_company_id
    ) {
        return back()->withErrors([
            'shipment_track' =>
                'Only supplier can update shipment tracking.',
        ]);
    }

    if (!$purchaseOrder->shipment) {
        return back()->withErrors([
            'shipment_track' =>
                'Shipment information must be created first.',
        ]);
    }

    $validated = $request->validate([
        'status' => [
            'required',
            'string',
            'max:100',
        ],

        'location' => [
            'nullable',
            'string',
            'max:255',
        ],

        'remarks' => [
            'nullable',
            'string',
        ],

        'tracked_at' => [
            'required',
            'date',
        ],
    ]);

    PurchaseOrderShipmentTrack::create([
        'shipment_id' =>
            $purchaseOrder->shipment->id,

        'status' =>
            $validated['status'],

        'location' =>
            $validated['location'] ?? null,

        'remarks' =>
            $validated['remarks'] ?? null,

        'tracked_at' =>
            $validated['tracked_at'],
    ]);

    /*
    |--------------------------------------------------------------------------
    | UPDATE CURRENT LOCATION
    |--------------------------------------------------------------------------
    */

    if (!empty($validated['location'])) {

        $purchaseOrder->shipment->update([
            'current_location' =>
                $validated['location'],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | AUTO COMPLETE PURCHASE ORDER
    |--------------------------------------------------------------------------
    */

    if (
        $validated['status'] === 'delivered' &&
        $purchaseOrder->status !== 'completed'
    ) {
        $purchaseOrder->update([
            'status' => 'completed',
        ]);
    }

    return back()->with(
        'success',
        'Shipment tracking updated successfully.'
    );
}
}