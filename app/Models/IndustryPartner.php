<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndustryPartner extends Model
{
   protected $appends = [
    'category_label',
    'category_icon',
    'partner_level_label',
];
    protected $fillable = [
        'company_id',
        'company_name',
        'slug',
        'partner_category',
        'partner_level',
        'logo_url',
        'website_url',
        'short_description',
        'is_featured',
        'is_active',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function company()
    {
        return $this->belongsTo(
            Company::class
        );
    }

    public function homepageSlots()
    {
        return $this->hasMany(
            HomepageSlot::class
        );
    }

    public function sponsoredContents()
    {
        return $this->hasMany(
            SponsoredContent::class
        );
    }

    public function getCategoryLabelAttribute()
{
    return match (
        $this->partner_category
    ) {

        'testing_certification'
            => 'Testing & Certification',

        'technology'
            => 'Technology Solutions',

        'machinery'
            => 'Industrial Machinery',

        'raw_material'
            => 'Raw Materials',

        'logistics'
            => 'Logistics & Supply Chain',

        'finance'
            => 'Trade Finance',

        'institution'
            => 'Institution',

        'association'
            => 'Industry Association',

        default
            => 'Industry Solution',
    };
    
}

public function getCategoryIconAttribute()
{
    return match (
        $this->partner_category
    ) {

        'testing_certification'
            => 'fa-certificate',

        'technology'
            => 'fa-microchip',

        'machinery'
            => 'fa-gears',

        'raw_material'
            => 'fa-boxes-stacked',

        'logistics'
            => 'fa-truck',

        'finance'
            => 'fa-money-bill-wave',

        'institution'
            => 'fa-building-columns',

        'association'
            => 'fa-handshake',

        default
            => 'fa-circle',
    };
}

public function getPartnerLevelLabelAttribute()
{
    return match (
        $this->partner_level
    ) {

        'platinum' =>
            'Platinum Partner',

        'gold' =>
            'Gold Partner',

        'silver' =>
            'Silver Partner',

        default =>
            'Bronze Partner',
    };
}


}