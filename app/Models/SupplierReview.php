<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierReview extends Model
{
    protected $fillable = [
        'purchase_order_id',
        'supplier_company_id',
        'buyer_id',

        'quality_rating',
        'delivery_rating',
        'communication_rating',

        'comment',
    ];

public function purchaseOrder()
{
    return $this->belongsTo(
        PurchaseOrder::class
    );
}

public function supplier()
{
    return $this->belongsTo(
        Company::class,
        'supplier_company_id'
    );
}

public function buyer()
{
    return $this->belongsTo(
        User::class,
        'buyer_id'
    );
}

    }