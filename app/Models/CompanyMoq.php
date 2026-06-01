<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyMoq extends Model
{
    protected $fillable = [
    'company_id',
    'product_name',
    'minimum_quantity',
    'unit',
    'notes',
];
}