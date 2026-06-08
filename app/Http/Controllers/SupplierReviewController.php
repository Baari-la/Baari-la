<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\SupplierReview;
use Illuminate\Http\Request;

class SupplierReviewController extends Controller
{
    public function store(
        Request $request,
        PurchaseOrder $purchaseOrder
    ) {

        // hanya buyer PO yang boleh review
        if (auth()->id() !== $purchaseOrder->buyer_id) {
            abort(403);
        }

        // cegah review ganda
        if ($purchaseOrder->review) {
            return back()->with(
                'error',
                'Review already submitted.'
            );
        }

        $request->validate([
            'quality_rating' => 'required|integer|min:1|max:5',
            'delivery_rating' => 'required|integer|min:1|max:5',
            'communication_rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        SupplierReview::create([
            'purchase_order_id' => $purchaseOrder->id,

            'supplier_company_id'
                => $purchaseOrder->supplier_company_id,

            'buyer_id'
                => auth()->id(),

            'quality_rating'
                => $request->quality_rating,

            'delivery_rating'
                => $request->delivery_rating,

            'communication_rating'
                => $request->communication_rating,

            'comment'
                => $request->comment,
        ]);

        return back()->with(
            'success',
            'Review submitted successfully.'
        );
    }
}