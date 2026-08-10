<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndustryPartner extends Model
{
    protected $appends = [
        'category_label',
        'category_icon',
        'partner_level_label',
        'status_label',
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

        // Strategic Solution Partner
        'solution_title',
        'solution_description',
        'solution_value',
        'target_market',
        'target_industry',

        // Contact
        'country',
        'contact_person',
        'job_title',
        'contact_email',
        'contact_phone',

        // Partnership
        'status',
        'annual_fee',
        'currency',
        'partnership_start_date',
        'partnership_end_date',

        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',

        'annual_fee' => 'decimal:2',

        'partnership_start_date' => 'date',
        'partnership_end_date' => 'date',
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

    public function inquiries()
    {
        return $this->hasMany(
            IndustryPartnerInquiry::class
        );
    }
    /*
    |--------------------------------------------------------------------------
    | CATEGORY
    |--------------------------------------------------------------------------
    */

   public function getCategoryLabelAttribute()
{
    return match ($this->partner_category) {

        'testing_certification'
            => 'Testing & Certification',

        'technology'
            => 'Technology Solutions',

        'machinery'
            => 'Textile & Garment Machinery',

        'energy'
            => 'Energy & Utilities',

        'logistics'
            => 'Logistics & Supply Chain',

        'erp_plm'
            => 'ERP & PLM',

        'ai_digital'
            => 'AI & Digital Transformation',

        'digital_printing'
            => 'Digital Textile Printing',

        'sustainability'
            => 'Sustainability & Circularity',

        'raw_material'
            => 'Raw Materials & Textile Chemicals',

        'finance'
            => 'Trade Finance & Insurance',

        'association'
            => 'Exhibitions & Events',

        'institution'
            => 'Research & Education',

        default
            => 'Industry Solution',
    };
}

   public function getCategoryIconAttribute()
{
    return match ($this->partner_category) {

        'testing_certification'
            => 'fa-certificate',

        'technology'
            => 'fa-microchip',

        'machinery'
            => 'fa-gears',

        'energy'
            => 'fa-bolt',

        'logistics'
            => 'fa-truck',

        'erp_plm'
            => 'fa-diagram-project',

        'ai_digital'
            => 'fa-brain',

        'digital_printing'
            => 'fa-print',

        'sustainability'
            => 'fa-leaf',

        'raw_material'
            => 'fa-boxes-stacked',

        'finance'
            => 'fa-money-bill-wave',

        'association'
            => 'fa-calendar-days',

        'institution'
            => 'fa-graduation-cap',

        default
            => 'fa-circle',
    };
}

    /*
    |--------------------------------------------------------------------------
    | PARTNER LEVEL
    |--------------------------------------------------------------------------
    */

    public function getPartnerLevelLabelAttribute()
    {
        return match ($this->partner_level) {

            'strategic_solution_partner'
                => 'Strategic Solution Partner',

            'platinum'
                => 'Platinum Partner',

            'gold'
                => 'Gold Partner',

            'silver'
                => 'Silver Partner',

            default
                => 'Bronze Partner',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | PARTNERSHIP STATUS
    |--------------------------------------------------------------------------
    */

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {

            'inquiry'
                => 'Inquiry',

            'new'
                => 'New',

            'contacted'
                => 'Contacted',

            'discussion'
                => 'Discussion',

            'proposal'
                => 'Proposal',

            'negotiation'
                => 'Negotiation',

            'active'
                => 'Active Partner',

            'expired'
                => 'Expired',

            'rejected'
                => 'Rejected',

            default
                => 'New',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isStrategicSolutionPartner(): bool
    {
        return $this->partner_level === 'strategic_solution_partner';
    }

    public function isActivePartner(): bool
    {
        return $this->status === 'active'
            && $this->is_active;
    }
}