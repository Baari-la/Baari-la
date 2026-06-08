<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PurchaseOrderController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST
    |--------------------------------------------------------------------------
    */

    public function index()
{
    $user = auth()->user();

    $purchaseOrders = PurchaseOrder::with([
        'rfq',
        'supplier',
        'buyer',
    ])
    ->where(function ($query) use ($user) {

        $query->where(
            'buyer_id',
            $user->id
        );

        if ($user->company_id) {

            $query->orWhere(
                'supplier_company_id',
                $user->company_id
            );
        }
    })
    ->latest()
    ->get();

    return Inertia::render(
        'PurchaseOrders/Index',
        [
            'purchaseOrders' => $purchaseOrders,
        ]
    );
}

    /*
    |--------------------------------------------------------------------------
    | DETAIL
    |--------------------------------------------------------------------------
    */

    public function show(
    PurchaseOrder $purchaseOrder
): Response {

    $purchaseOrder->load([
        'rfq',
        'quotation',
        'buyer',
        'supplier',
        'review',
        'documents',
        'documents.uploader',

        'payments',
        'payments.payer',
        'shipment',
        'shipment.tracks',
        'disputes',
        'disputes.creator',
    ]);

    $totalPaid = $purchaseOrder
    ->payments
    ->sum('amount');

$outstanding = max(
    0,
    $purchaseOrder->total_amount - $totalPaid
);

$percentage =
    $purchaseOrder->total_amount > 0
        ? min(
            100,
            round(
                ($totalPaid / $purchaseOrder->total_amount) * 100,
                2
            )
        )
        : 0;

$paymentStatus = 'unpaid';

if ($totalPaid > 0) {
    $paymentStatus = 'partial';
}

if ($totalPaid >= $purchaseOrder->total_amount) {
    $paymentStatus = 'paid';
}

if ($totalPaid > $purchaseOrder->total_amount) {
    $paymentStatus = 'overpaid';
}
$overpaidAmount = max(
    0,
    $totalPaid - $purchaseOrder->total_amount
);

$shipmentProgress = 0;

$latestTracking = $purchaseOrder
    ->shipment
    ?->tracks()
    ->orderByDesc('tracked_at')
    ->orderByDesc('id')
    ->first();
    
if ($latestTracking) {

    $shipmentProgress = match (
        $latestTracking->status
    ) {
        'picked_up' => 10,
        'export_clearance' => 20,
        'departed_port' => 40,
        'in_transit' => 60,
        'arrived_port' => 80,
        'out_for_delivery' => 90,
        'delivered' => 100,
        default => 0,
    };
}


    return Inertia::render(
        'PurchaseOrders/Show',
        [
            'purchaseOrder' => $purchaseOrder,

    'paymentSummary' => [
    'total_paid' => $totalPaid,
    'outstanding' => $outstanding,
    'percentage' => $percentage,
    'status' => $paymentStatus,
    'overpaid_amount' => $overpaidAmount,
    'payment_count' => $purchaseOrder
       ->payments
       ->count(),
],
    'shipmentProgress' => [
    'percentage' => $shipmentProgress,
    'latest_status' => $latestTracking?->status,
        ],

    'goodsReceived' => [
    'received_at' => $purchaseOrder->goods_received_at,
    'received_by' => $purchaseOrder->goods_received_by,
],

        ]
    );
}

public function creator()
{
    return $this->belongsTo(
        User::class,
        'created_by'
    );
}

public function confirmReceived(
    PurchaseOrder $purchaseOrder
): RedirectResponse {
   
    /*
    |--------------------------------------------------------------------------
    | BUYER ONLY
    |--------------------------------------------------------------------------
    */

    if (
        auth()->id() !== $purchaseOrder->buyer_id
    ) {
        abort(403);
    }

    /*
    |--------------------------------------------------------------------------
    | MUST BE COMPLETED
    |--------------------------------------------------------------------------
    */

    if (
        $purchaseOrder->status !== 'completed'
    ) {
        return back()->withErrors([
            'received' =>
                'Order has not been delivered yet.',
        ]);
    }

    $purchaseOrder->update([
        'goods_received_at' => now(),
        'goods_received_by' => auth()->id(),
    ]);

    return back()->with(
        'success',
        'Goods received confirmed successfully.'
    );
}

    /*
    |--------------------------------------------------------------------------
    | CONFIRM ORDER
    |--------------------------------------------------------------------------
    */

    public function confirm(
        PurchaseOrder $purchaseOrder
    ): RedirectResponse {

        if (
            $purchaseOrder->status !== 'pending'
        ) {
            return back()->withErrors([
                'purchase_order' =>
                    'Only pending orders can be confirmed.',
            ]);
        }

        $purchaseOrder->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);

        return back()->with(
            'success',
            'Order confirmed successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | START PRODUCTION
    |--------------------------------------------------------------------------
    */

    public function startProduction(
        PurchaseOrder $purchaseOrder
    ): RedirectResponse {

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
            'production_started_at' => now(),
        ]);

        return back()->with(
            'success',
            'Production started.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SHIPPED
    |--------------------------------------------------------------------------
    */

    public function shipped(
        PurchaseOrder $purchaseOrder
    ): RedirectResponse {

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
            'shipped_at' => now(),
        ]);

        return back()->with(
            'success',
            'Order shipped successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | COMPLETED
    |--------------------------------------------------------------------------
    */

    public function completed(
        PurchaseOrder $purchaseOrder
    ): RedirectResponse {

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
            'completed_at' => now(),
        ]);

        return back()->with(
            'success',
            'Order completed successfully.'
        );
    }

    
}