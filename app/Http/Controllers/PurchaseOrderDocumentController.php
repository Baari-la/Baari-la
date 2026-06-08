<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDocument;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class PurchaseOrderDocumentController extends Controller
{
    public function store(
        Request $request,
        PurchaseOrder $purchaseOrder
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | ONLY SUPPLIER CAN UPLOAD
        |--------------------------------------------------------------------------
        */

        if (
            auth()->user()->company_id !==
            $purchaseOrder->supplier_company_id
        ) {
            return back()->withErrors([
                'document' =>
                    'Only supplier can upload shipment documents.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'document_type' => [
                'required',
                'in:invoice,packing_list,bill_of_lading,air_waybill,certificate_of_origin,insurance_certificate,inspection_certificate,other',
            ],

            'file' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:10240',
            ],

            'document_number' => [
                'nullable',
                'string',
                'max:255',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | STORE FILE
        |--------------------------------------------------------------------------
        */

        $filePath = $request
            ->file('file')
            ->store(
                'purchase-order-documents',
                'public'
            );

        /*
        |--------------------------------------------------------------------------
        | CREATE DOCUMENT
        |--------------------------------------------------------------------------
        */

        PurchaseOrderDocument::create([
            'purchase_order_id' => $purchaseOrder->id,

            'uploaded_by' => auth()->id(),

            'document_type' => $validated['document_type'],

            'document_number' => $validated['document_number'] ?? null,

            'file_path' => $filePath,

            'remarks' => $validated['remarks'] ?? null,
        ]);

        return back()->with(
            'success',
            'Document uploaded successfully.'
        );
    }
}