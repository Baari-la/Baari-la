<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\IndustryPartnerSolutionSpecification;

class IndustryPartnerSolution extends Model
{
    protected $fillable = [
        'industry_partner_id',
        'title',
        'slug',
        'short_description',
        'problem_solved',
        'solution_description',
        'industry_applications',
        'technology',
        'key_benefits',
        'is_featured',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function partner()
    {
        return $this->belongsTo(
            IndustryPartner::class,
            'industry_partner_id'
        );
    }

    protected static function booted(): void
    {
        static::creating(function ($solution) {
            if (empty($solution->slug)) {
                $solution->slug = Str::slug(
                    $solution->title
                );
            }
        });
    }
    /*
|--------------------------------------------------------------------------
| TECHNICAL SPECIFICATIONS
|--------------------------------------------------------------------------
*/

public function specifications()
{
    return $this->hasMany(
        IndustryPartnerSolutionSpecification::class,
        'industry_partner_solution_id'
    );
}
}