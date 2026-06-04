<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollectiveSourcingRequest extends Model
{
    protected $fillable = [
        'group_id',
        'company_id',
        'quantity',
        'required_month',
        'destination_country',
        'destination_city',
        'notes',
        'status',
    ];

    public function group()
    {
        return $this->belongsTo(
            CollectiveSourcingGroup::class,
            'group_id'
        );
    }

    public function company()
    {
        return $this->belongsTo(
            Company::class
        );
    }
}