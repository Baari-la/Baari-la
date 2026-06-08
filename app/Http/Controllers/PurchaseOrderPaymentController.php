<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderPayment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class PurchaseOrderPaymentController extends Controller
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
                'payment' =>
                    'Only buyer can upload payment.',
            ]);
        }
// dd($request->all());
       $validated = $request->validate([
    'amount' => [
        'required',
        'numeric',
        'min:0.01',
    ],

    'payment_method' => [
        'required',
        'in:bank_transfer,letter_of_credit,cash,other',
    ],

    'payment_reference' => [
        'nullable',
        'string',
        'max:255',
    ],

    'payment_date' => [
        'nullable',
        'date',
    ],

    'remarks' => [
        'nullable',
        'string',
    ],

    'payment_proof' => [
        'nullable',
        'file',
        'mimes:pdf,jpg,jpeg,png',
        'max:10240',
    ],
]);

        $proofPath = null;

        if (
            $request->hasFile(
                'payment_proof'
            )
        ) {
            $proofPath = $request
                ->file('payment_proof')
                ->store(
                    'payment-proofs',
                    'public'
                );
        }
$totalPaid =
    $purchaseOrder
        ->payments()
        ->sum('amount');

$remaining =
    $purchaseOrder->total_amount
    - $totalPaid;

if (
    $validated['amount'] > $remaining
) {
    return back()->withErrors([
        'amount' =>
            'Payment exceeds outstanding amount.',
    ]);
}
        PurchaseOrderPayment::create([
    'purchase_order_id' => $purchaseOrder->id,

    'paid_by' => auth()->id(),

    'payment_reference' =>
        $validated['payment_reference'] ?? null,

    'amount' =>
        $validated['amount'],

    'currency' =>
        $purchaseOrder->currency,

    'payment_method' =>
        $validated['payment_method'],

    'payment_date' =>
        $validated['payment_date']
        ?? now()->toDateString(),

    'payment_proof' =>
        $proofPath,

    'remarks' =>
        $validated['remarks'] ?? null,
]);

        return back()->with(
            'success',
            'Payment recorded successfully.'
        );
    }
}