<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyIdentityBusiness extends Model
{
    protected $fillable = [

        'company_identity_id',

        /*
        |--------------------------------------------------------------------------
        | Company Overview
        |--------------------------------------------------------------------------
        */

        'business_description',
        'year_established',
        'legal_entity',
        'employee_range',
        'factory_count',

        /*
        |--------------------------------------------------------------------------
        | Business Model
        |--------------------------------------------------------------------------
        */

        'is_fiber_producer',
        'is_spinner',
        'is_weaving',
        'is_knitting',
        'is_dyeing_finishing',
        'is_printing',
        'is_garment',
        'is_trader',
        'is_brand',
        'is_buying_office',
        'is_testing_laboratory',
        'is_certification_body',
        'is_machinery_supplier',
        'is_accessories_supplier',
        'is_chemical_supplier',

        'primary_business_category',
        'secondary_business_categories',
        'value_chain_position',

        /*
        |--------------------------------------------------------------------------
        | Business Strategy
        |--------------------------------------------------------------------------
        */

        'oem',
        'odm',
        'obm',
        'private_label',

        /*
        |--------------------------------------------------------------------------
        | Market
        |--------------------------------------------------------------------------
        */

        'domestic_market',
        'export_market',
        'export_experience_years',

        /*
        |--------------------------------------------------------------------------
        | Sustainability
        |--------------------------------------------------------------------------
        */

        'esg_program',
        'renewable_energy',
        'recycled_material',
        'wastewater_treatment',
        'sustainability_notes',

        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        'last_updated_by',
        'last_reviewed_at',
    ];

    protected $casts = [

        'year_established' => 'integer',

        'factory_count' => 'integer',

        'export_experience_years' => 'integer',

        'last_reviewed_at' => 'datetime',

        'created_at' => 'datetime',

        'updated_at' => 'datetime',

        /*
        |--------------------------------------------------------------------------
        | Business Model
        |--------------------------------------------------------------------------
        */

        'is_fiber_producer' => 'boolean',
        'is_spinner' => 'boolean',
        'is_weaving' => 'boolean',
        'is_knitting' => 'boolean',
        'is_dyeing_finishing' => 'boolean',
        'is_printing' => 'boolean',
        'is_garment' => 'boolean',
        'is_trader' => 'boolean',
        'is_brand' => 'boolean',
        'is_buying_office' => 'boolean',
        'is_testing_laboratory',
        'is_certification_body',
        'is_machinery_supplier',
        'is_accessories_supplier',
        'is_chemical_supplier',

        'secondary_business_categories' => 'array',

        /*
        |--------------------------------------------------------------------------
        | Strategy
        |--------------------------------------------------------------------------
        */

        'oem' => 'boolean',
        'odm' => 'boolean',
        'obm' => 'boolean',
        'private_label' => 'boolean',

        /*
        |--------------------------------------------------------------------------
        | Market
        |--------------------------------------------------------------------------
        */

        'domestic_market' => 'boolean',
        'export_market' => 'boolean',

        /*
        |--------------------------------------------------------------------------
        | Sustainability
        |--------------------------------------------------------------------------
        */

        'esg_program' => 'boolean',
        'renewable_energy' => 'boolean',
        'recycled_material' => 'boolean',
        'wastewater_treatment' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Canonical Company
    |--------------------------------------------------------------------------
    */

    public function companyIdentity(): BelongsTo
    {
        return $this->belongsTo(
            CompanyIdentity::class,
            'company_identity_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Updated By
    |--------------------------------------------------------------------------
    */

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'last_updated_by'
        );
    }
}