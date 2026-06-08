<?php

namespace App\Models;
use App\Models\PurchaseOrderDispute;
use Illuminate\Database\Eloquent\Model;
use App\Models\PurchaseOrderPayment;
use App\Models\SupplierReview;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'rfq_id',
        'quotation_id',
        'buyer_id',
        'supplier_company_id',
        'po_number',
        'unit_price',
        'quantity',
        'total_amount',
        'currency',
        'delivery_date',
        'status',
        'confirmed_at',
    'production_started_at',
    'shipped_at',
    'completed_at',
    'goods_received_at',
    'goods_received_by',

    ];

    protected $casts = [
        'delivery_date' => 'date',

        'confirmed_at' => 'datetime',
        'production_started_at' => 'datetime',
        'shipped_at' => 'datetime',
        'completed_at' => 'datetime',
        'goods_received_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function rfq()
    {
        return $this->belongsTo(
            Rfq::class
        );
    }

    public function quotation()
    {
        return $this->belongsTo(
            Quotation::class
        );
    }

    public function buyer()
    {
        return $this->belongsTo(
            User::class,
            'buyer_id'
        );
    }

    public function supplier()
    {
        return $this->belongsTo(
            Company::class,
            'supplier_company_id'
        );
    }

    public function documents()
    {
        return $this->hasMany(
            PurchaseOrderDocument::class,
            'purchase_order_id'
        );
    }
public function payments()
{
    return $this->hasMany(
        PurchaseOrderPayment::class,
        'purchase_order_id'
    );
}
public function shipment()
{
    return $this->hasOne(
        PurchaseOrderShipment::class,
        'purchase_order_id'
    );
}
 public function disputes()
{
    return $this->hasMany(
        PurchaseOrderDispute::class,
        'purchase_order_id'
    );
}   

public function review()
{
    return $this->hasOne(
        SupplierReview::class
    );
}
}