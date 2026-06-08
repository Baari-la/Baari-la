<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderShipment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class PurchaseOrderShipmentController extends Controller
{
    public function store(
        Request $request,
        PurchaseOrder $purchaseOrder
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | SUPPLIER ONLY
        |--------------------------------------------------------------------------
        */

        if (
            auth()->user()->company_id !==
            $purchaseOrder->supplier_company_id
        ) {
            return back()->withErrors([
                'shipment' =>
                    'Only supplier can manage shipment information.',
            ]);
        }

        $validated = $request->validate([
            'carrier' => [
                'nullable',
                'string',
                'max:255',
            ],

            'tracking_number' => [
                'nullable',
                'string',
                'max:255',
            ],

            'container_number' => [
                'nullable',
                'string',
                'max:255',
            ],

            'bl_number' => [
                'nullable',
                'string',
                'max:255',
            ],

            'etd' => [
                'nullable',
                'date',
            ],

            'eta' => [
                'nullable',
                'date',
            ],

            'current_location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        PurchaseOrderShipment::updateOrCreate(
            [
                'purchase_order_id' =>
                    $purchaseOrder->id,
            ],
            [
                'carrier' =>
                    $validated['carrier'] ?? null,

                'tracking_number' =>
                    $validated['tracking_number'] ?? null,

                'container_number' =>
                    $validated['container_number'] ?? null,

                'bl_number' =>
                    $validated['bl_number'] ?? null,

                'etd' =>
                    $validated['etd'] ?? null,

                'eta' =>
                    $validated['eta'] ?? null,

                'current_location' =>
                    $validated['current_location'] ?? null,

                'remarks' =>
                    $validated['remarks'] ?? null,

                'created_by' =>
                    auth()->id(),
            ]
        );

        return back()->with(
            'success',
            'Shipment information saved successfully.'
        );
    }
}