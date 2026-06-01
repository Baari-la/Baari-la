<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyLeadTime extends Model
{
    protected $fillable = [
    'company_id',
    'lead_time_type',
    'days',
    'notes',
];
}