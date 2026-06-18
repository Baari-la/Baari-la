<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyCapacity extends Model
{
    protected $fillable = [
        'company_id',
        'capacity_type',
        'item_name',
        'capacity_value',
        'capacity_unit',
        'capacity_category',
        'shift_info',
        'machine_count',
        'notes',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    
}