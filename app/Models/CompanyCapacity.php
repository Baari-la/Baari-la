<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyCapacity extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Mass Assignable Fields
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | Attribute Casting
    |--------------------------------------------------------------------------
    |
    | Standardize numeric values used by:
    |
    | - Digital Company Passport
    | - Company Intelligence
    | - Readiness Engine
    | - Matching Engine
    |
    */

    protected $casts = [
        'capacity_value' => 'decimal:2',
        'machine_count' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}