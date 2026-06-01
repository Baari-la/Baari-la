<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyMachine extends Model
{
   protected $fillable = [

    'company_id',

    'machine_category',
    'machine_type',

    'machine_brand',
    'machine_model',

    'quantity',

    'production_capacity',
    'capacity_unit',

    'working_width',
    'gauge_specification',

    'year_installed',

    'machine_condition',
    'automation_level',

    'country_origin',

    'is_active',

    'notes',
];
}