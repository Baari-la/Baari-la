<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $fillable = [

        'rfq_id',
        'company_id',

        'unit_price',

        'minimum_order_quantity',

        'lead_time_days',

        'remarks',

        'status',
    ];

    protected $attributes = [

        'status' => 'submitted',
    ];

    protected $casts = [

        'unit_price' => 'decimal:2',
        'minimum_order_quantity' => 'decimal:2',
    ];

    public function rfq()
    {
        return $this->belongsTo(Rfq::class);
    }

    public function company()
{
    return $this->belongsTo(
        Company::class,
        'company_id'
    );
}
public function purchaseOrder()
{
    return $this->hasOne(
        PurchaseOrder::class
    );
}
}