<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollectiveSourcingGroup extends Model
{
    protected $fillable = [

    'group_code',

    'product_category',

    'product_name',

    'specification',

    'unit',

    'moq_quantity',

    'current_quantity',

    'hs_code',

    'currency',

    'incoterm',

    'destination_country',

    'required_delivery_date',

    'quotation_deadline',

    'status',

    'rfq_id',

    'created_by',
];

    public function requests()
    {
        return $this->hasMany(
            CollectiveSourcingRequest::class,
            'group_id'
        );
    }

    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }
}