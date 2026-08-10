<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\IndustryPartner;

class IndustryPartnerInquiry extends Model
{
    protected $fillable = [
        'industry_partner_id',
        'company_name',
        'website_url',
        'contact_name',
        'job_title',
        'email',
        'phone',
        'partner_category',
        'solution_description',
        'partnership_interest',
        'target_market',
        'proposed_value',
        'status',
        'admin_notes',
        'reviewed_at',
        'source',
        'locale',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function industryPartner()
    {
        return $this->belongsTo(
            IndustryPartner::class
        );
    }
}