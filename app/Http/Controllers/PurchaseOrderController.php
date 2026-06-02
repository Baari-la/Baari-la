<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Inertia\Inertia;
use Illuminate\Http\RedirectResponse;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $query = PurchaseOrder::with([
            'rfq',
            'supplier',
            'buyer',
        ]);

        /*
        |--------------------------------------------------------------------------
        | BUYER
        |--------------------------------------------------------------------------
        */

        if (!$user->company_id) {

            $query->where(
                'buyer_id',
                $user->id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SUPPLIER
        |--------------------------------------------------------------------------
        */

        else {

            $query->where(
                'supplier_company_id',
                $user->company_id
            );
        }

        return Inertia::render(
            'PurchaseOrders/Index',
            [
                'purchaseOrders' =>
                    $query
                        ->latest()
                        ->get(),
            ]
        );
    }

    public function show(
        PurchaseOrder $purchaseOrder
    ) {

        $purchaseOrder->load([
            'rfq',
            'quotation',
            'buyer',
            'supplier',
        ]);

        return Inertia::render(
            'PurchaseOrders/Show',
            [
                'purchaseOrder' =>
                    $purchaseOrder,
            ]
        );
    }

public function confirm(
    PurchaseOrder $purchaseOrder
)
{
    if (
        $purchaseOrder->status !== 'pending'
    ) {
        return back();
    }

    $purchaseOrder->update([
        'status' => 'confirmed',
    ]);

    return back()->with(
        'success',
        'Order confirmed successfully.'
    );
}
public function production(
    PurchaseOrder $purchaseOrder
)
{
    if (
        $purchaseOrder->status !== 'confirmed'
    ) {
        return back()->withErrors([
            'purchase_order' =>
                'Only confirmed orders can start production.',
        ]);
    }

    $purchaseOrder->update([
        'status' => 'production',
    ]);

    return back()->with(
        'success',
        'Production started.'
    );
}

public function shipped(
    PurchaseOrder $purchaseOrder
)
{
    if (
        $purchaseOrder->status !== 'production'
    ) {
        return back()->withErrors([
            'purchase_order' =>
                'Only production orders can be shipped.',
        ]);
    }

    $purchaseOrder->update([
        'status' => 'shipped',
    ]);

    return back()->with(
        'success',
        'Order shipped successfully.'
    );
}

public function completed(
    PurchaseOrder $purchaseOrder
)
{
    if (
        $purchaseOrder->status !== 'shipped'
    ) {
        return back()->withErrors([
            'purchase_order' =>
                'Only shipped orders can be completed.',
        ]);
    }

    $purchaseOrder->update([
        'status' => 'completed',
    ]);

    return back()->with(
        'success',
        'Order completed successfully.'
    );
}

public function startProduction(PurchaseOrder $purchaseOrder): RedirectResponse
{
    if ($purchaseOrder->status === 'completed') {
        // Mengirimkan kunci lokalisasi, bukan teks mentah
        return back()->with('message', 'production_completed_error');
    }

    $purchaseOrder->update([
        'status' => 'in_production'
    ]);

    // Mengirimkan kunci lokalisasi untuk pesan sukses
    return back()->with('message', 'production_started');
}


}