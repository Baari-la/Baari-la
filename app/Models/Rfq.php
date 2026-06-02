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

}