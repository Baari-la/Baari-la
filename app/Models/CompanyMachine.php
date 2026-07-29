<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyMachine extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Mass Assignable Fields
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'company_id',

        'machine_category',
        'machine_type',

        'machine_brand',
        'machine_model',

        'quantity',

        'production_capacity',
        'capacity_unit',

        'energy_consumption',
        'energy_unit',

        'working_width',
        'gauge_specification',

        'year_installed',

        'machine_condition',
        'automation_level',

        'country_origin',

        'is_active',

        'notes',
    ];

    /*
    |--------------------------------------------------------------------------
    | Attribute Casting
    |--------------------------------------------------------------------------
    |
    | Keep database/model values consistent for Company Intelligence,
    | Digital Company Passport and future Matching Engine processing.
    |
    */

    protected $casts = [
        'quantity' => 'integer',

        'production_capacity' => 'decimal:2',
        'energy_consumption' => 'decimal:2',

        'year_installed' => 'integer',

        'is_active' => 'boolean',
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