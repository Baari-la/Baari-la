<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndustryPartnerSolutionSpecification extends Model
{
    protected $fillable = [
        'industry_partner_solution_id',
        'name',
        'value',
        'unit',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP
    |--------------------------------------------------------------------------
    */

    public function solution()
    {
        return $this->belongsTo(
            IndustryPartnerSolution::class,
            'industry_partner_solution_id'
        );
    }
}