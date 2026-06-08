<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderPayment extends Model
{
    protected $fillable = [
        'purchase_order_id',
        'paid_by',
        'payment_reference',
        'amount',
        'currency',
        'payment_method',
        'payment_date',
        'payment_proof',
        'remarks',
    ];

    protected $casts = [
        'payment_date' => 'date',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(
            PurchaseOrder::class
        );
    }

    public function payer()
    {
        return $this->belongsTo(
            User::class,
            'paid_by'
        );
    }
}