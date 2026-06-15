<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SponsoredContent extends Model
{
    protected $fillable = [
        'industry_partner_id',
        'title',
        'slug',
        'content',
        'featured_image',
        'content_type',
        'published_at',
        'is_active',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function partner()
    {
        return $this->belongsTo(
            IndustryPartner::class,
            'industry_partner_id'
        );
    }
}