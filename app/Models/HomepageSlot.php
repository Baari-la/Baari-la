<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageSlot extends Model
{
    protected $fillable = [
        'industry_partner_id',
        'slot_type',
        'title',
        'description',
        'banner_image',
        'cta_text',
        'cta_url',
        'display_order',
        'start_date',
        'end_date',
        'is_active',
    ];

    public function partner()
    {
        return $this->belongsTo(
            IndustryPartner::class,
            'industry_partner_id'
        );
    }
}