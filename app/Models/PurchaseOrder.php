<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function rfq()
    {
        return $this->belongsTo(Rfq::class);
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
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
}