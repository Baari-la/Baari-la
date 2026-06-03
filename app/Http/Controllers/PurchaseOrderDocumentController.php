<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDocument;
use Illuminate\Http\Request;

class PurchaseOrderDocumentController extends Controller
{
    public function store(
        Request $request,
        PurchaseOrder $purchaseOrder
    ) {

        $request->validate([
            'document_type' => [
                'required',
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

        $path = $request
            ->file('file')
            ->store(
                'purchase-order-documents',
                'public'
            );

        PurchaseOrderDocument::create([
            'purchase_order_id' =>
                $purchaseOrder->id,

            'uploaded_by' =>
                auth()->id(),

            'document_type' =>
                $request->document_type,

            'document_number' =>
                $request->document_number,

            'file_path' =>
                $path,

            'remarks' =>
                $request->remarks,
        ]);

        return back()->with(
            'success',
            'Document uploaded successfully.'
        );
    }
}