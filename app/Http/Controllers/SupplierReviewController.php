<?php

namespace App\Http\Controllers;
use App\Models\PurchaseOrder;
use App\Models\SupplierReview;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;


class SupplierReviewController extends Controller
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
                'review' =>
                    'Only buyer can submit supplier review.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | ONLY COMPLETED ORDER
        |--------------------------------------------------------------------------
        */

        if (
            $purchaseOrder->status !== 'completed'
        ) {
            return back()->withErrors([
                'review' =>
                    'Review can only be submitted after completion.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | PREVENT DUPLICATE
        |--------------------------------------------------------------------------
        */

        if ($purchaseOrder->review) {

            return back()->withErrors([
                'review' =>
                    'Review already exists.',
            ]);
        }

        $validated = $request->validate([
            'quality_rating' => [
                'required',
                'integer',
                'between:1,5',
            ],

            'delivery_rating' => [
                'required',
                'integer',
                'between:1,5',
            ],

            'communication_rating' => [
                'required',
                'integer',
                'between:1,5',
            ],

            'comment' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $overallRating = round(
            (
                $validated['quality_rating']
                + $validated['delivery_rating']
                + $validated['communication_rating']
            ) / 3,
            2
        );

        SupplierReview::create([
    'purchase_order_id' => $purchaseOrder->id,

    'supplier_company_id' =>
        $purchaseOrder->supplier_company_id,

    'buyer_id' =>
        $purchaseOrder->buyer_id,

    'quality_rating' =>
        $validated['quality_rating'],

    'delivery_rating' =>
        $validated['delivery_rating'],

    'communication_rating' =>
        $validated['communication_rating'],

    'overall_rating' =>
     $overallRating,


    'comment' =>
        $validated['comment'] ?? null,
]);

        return back()->with(
            'success',
            'Supplier review submitted successfully.'
        );
    }
}