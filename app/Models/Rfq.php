<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rfq extends Model
{
 protected $fillable = [

        'user_id',
        'rfq_number',
        'rfq_id',
        'file_path',
        'file_name',
        'product_name',
        'hs_code',
        'company_id',
        'incoterm',
        'currency',
        'quotation_deadline',
        'awarded_at',

        'description',

        'required_quantity',
        'unit',

        'required_delivery_date',

        'destination_country',

        'status',
        'awarded_quotation_id',
    ];

    protected $attributes = [

    'status' => 'open',
];

    protected $casts = [

        'required_quantity' => 'decimal:2',

        'required_delivery_date' => 'date',
    ];

   public function user()
{
    return $this->belongsTo(User::class);
}
public function files()
{
    return $this->hasMany(RfqFile::class);
}

public function quotations()
{
    return $this->hasMany(Quotation::class);
}
public function awardedQuotation()
{
    return $this->belongsTo(
        Quotation::class,
        'awarded_quotation_id'
    );
}
public function purchaseOrder()
{
    return $this->hasOne(
        PurchaseOrder::class
    );
}

public function buyerCompany()
{
    return $this->belongsTo(
        \App\Models\Company::class,
        'buyer_company_id'
    );
}
public function company()
{
    return $this->belongsTo(
        Company::class,
        'company_id',
        'id'
    );
}

}