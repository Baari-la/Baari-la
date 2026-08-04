<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyIdentityCapabilityProfile extends Model
{
    protected $fillable = [

        'company_identity_id',

        /*
        |--------------------------------------------------------------------------
        | Production Capacity
        |--------------------------------------------------------------------------
        */

        'production_capacity',
        'production_capacity_unit',
        'monthly_capacity',
        'annual_capacity',

        'production_capacity',
        'production_capacity_unit',

        'current_utilized_capacity',
        'current_utilized_capacity_unit',

        'monthly_capacity',
        'annual_capacity',

        'minimum_order_quantity',
        'minimum_order_unit',

        'lead_time_days',

        /*
        |--------------------------------------------------------------------------
        | Commercial Capability
        |--------------------------------------------------------------------------
        */

        'minimum_order_quantity',
        'minimum_order_unit',
        'lead_time_days',
        'sampling_service',
        'export_ready',

        /*
        |--------------------------------------------------------------------------
        | Manufacturing Services
        |--------------------------------------------------------------------------
        */

        'supports_oem',
        'supports_odm',
        'supports_private_label',
        'supports_full_package',
        'supports_cmt',
        'supports_design_support',

        /*
        |--------------------------------------------------------------------------
        | Production Flexibility
        |--------------------------------------------------------------------------
        */

        'supports_small_batch',
        'supports_fast_sampling',
        'supports_quick_response',
        'supports_custom_development',

        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        'last_updated_by',
        'last_reviewed_at',

    ];

    protected $casts = [

        /*
        |--------------------------------------------------------------------------
        | Numeric
        |--------------------------------------------------------------------------
        */

        'lead_time_days' => 'integer',

        /*
        |--------------------------------------------------------------------------
        | Commercial
        |--------------------------------------------------------------------------
        */

        'sampling_service' => 'boolean',
        'export_ready' => 'boolean',

        /*
        |--------------------------------------------------------------------------
        | Manufacturing Services
        |--------------------------------------------------------------------------
        */

        'supports_oem' => 'boolean',
        'supports_odm' => 'boolean',
        'supports_private_label' => 'boolean',
        'supports_full_package' => 'boolean',
        'supports_cmt' => 'boolean',
        'supports_design_support' => 'boolean',

        /*
        |--------------------------------------------------------------------------
        | Production Flexibility
        |--------------------------------------------------------------------------
        */

        'supports_small_batch' => 'boolean',
        'supports_fast_sampling' => 'boolean',
        'supports_quick_response' => 'boolean',
        'supports_custom_development' => 'boolean',

        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        'last_reviewed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',

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

    /*
|--------------------------------------------------------------------------
| Capacity Intelligence™
|--------------------------------------------------------------------------
*/

public function getAvailableCapacityAttribute()
{
    if (
        !$this->production_capacity ||
        !$this->current_utilized_capacity
    ) {
        return null;
    }

    return max(
        0,
        (float) $this->production_capacity -
        (float) $this->current_utilized_capacity
    );
}

public function getCapacityUtilizationAttribute()
{
    if (
        !$this->production_capacity ||
        !$this->current_utilized_capacity
    ) {
        return null;
    }

    return round(
        (
            (float) $this->current_utilized_capacity
            /
            (float) $this->production_capacity
        ) * 100,
        1
    );
}

public function getFactoryStatusAttribute()
{
    $rate = $this->capacity_utilization;

    if ($rate === null) {
        return null;
    }

    if ($rate <= 60) {
        return 'Available Capacity';
    }

    if ($rate <= 85) {
        return 'Moderate Utilization';
    }

    return 'Nearly Full Capacity';
}
protected $appends = [

    'available_capacity',

    'capacity_utilization',

    'factory_status',

];
}