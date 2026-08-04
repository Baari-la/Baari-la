<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\CompanyMachine;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class CompanyFactory extends Model
{
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        /*
        |--------------------------------------------------------------------------
        | Global Identity
        |--------------------------------------------------------------------------
        */

        'uuid',

        /*
        |--------------------------------------------------------------------------
        | Canonical Company
        |--------------------------------------------------------------------------
        */

        'company_identity_id',

        /*
        |--------------------------------------------------------------------------
        | Public Identity
        |--------------------------------------------------------------------------
        */

        'factory_slug',

        /*
        |--------------------------------------------------------------------------
        | Factory Identity
        |--------------------------------------------------------------------------
        */

        'factory_code',
        'factory_name',
        'factory_type',
        'factory_status',

        'is_headquarters',
        'is_main_factory',

        /*
        |--------------------------------------------------------------------------
        | Factory Location
        |--------------------------------------------------------------------------
        */

        'country',
        'province',
        'city',
        'postal_code',
        'address',

        'latitude',
        'longitude',

        /*
        |--------------------------------------------------------------------------
        | General Information
        |--------------------------------------------------------------------------
        */

        'factory_established_year',

        'land_area_sqm',
        'building_area_sqm',
        'number_of_buildings',

        'display_order',

        'production_lines',
        'number_of_shifts',
        'quality_control_system',
        'compliance_standards',

        /*
        |--------------------------------------------------------------------------
        | Digital Factory Passport
        |--------------------------------------------------------------------------
        */

        'visibility_status',
        'verification_status',

        'verified_at',
        'verified_by',

        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        'last_updated_by',
        'last_reviewed_at',

    ];

    /*
    |--------------------------------------------------------------------------
    | Attribute Casting
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'is_headquarters' => 'boolean',
        'is_main_factory' => 'boolean',
        'factory_established_year' => 'integer',

        'land_area_sqm' => 'decimal:2',
        'building_area_sqm' => 'decimal:2',

        'number_of_buildings' => 'integer',
        'production_lines' => 'integer',
        'number_of_shifts' => 'integer',
        'compliance_standards' => 'array',

        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',

        'verified_at' => 'datetime',
        'last_reviewed_at' => 'datetime',

        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',

    ];

    /*
    |--------------------------------------------------------------------------
    | Boot
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::creating(function (CompanyFactory $factory) {

            if (blank($factory->uuid)) {
                $factory->uuid = (string) Str::uuid();
            }

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Canonical Company
     */
    public function companyIdentity(): BelongsTo
    {
        return $this->belongsTo(
            CompanyIdentity::class,
            'company_identity_id'
        );
    }

    /**
     * User that verified this Factory Passport.
     */
    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'verified_by'
        );
    }

    /**
     * Last user updating this record.
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
    | Future Relationships
    |--------------------------------------------------------------------------
    */

    public function machines()
{
    return $this->hasMany(
        CompanyMachine::class,
        'factory_id',
        'id'
    );
}

public function primaryMachine()
{
    return $this->hasOne(
        CompanyMachine::class,
        'factory_id',
        'id'
    )->where(
        'is_primary',
        true
    );
}
    public function capacities(): HasMany
    {
        return $this->hasMany(
            CompanyCapacity::class,
            'company_factory_id'
        );
    }

    public function moqs(): HasMany
    {
        return $this->hasMany(
            CompanyMoq::class,
            'company_factory_id'
        );
    }

    public function leadTimes(): HasMany
    {
        return $this->hasMany(
            CompanyLeadTime::class,
            'company_factory_id'
        );
    }

    public function certifications(): HasMany
    {
        return $this->hasMany(
            CompanyCertification::class,
            'company_factory_id'
        );
    }

    public function images(): HasMany
    {
        return $this->hasMany(
            CompanyImage::class,
            'company_factory_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where(
            'factory_status',
            'ACTIVE'
        );
    }

    public function scopeVerified($query)
    {
        return $query->where(
            'verification_status',
            'VERIFICATION_VERIFIED'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isVerified(): bool
    {
        return $this->verification_status === 'VERIFICATION_VERIFIED';
    }

    public function isPublic(): bool
    {
        return $this->visibility_status === 'VISIBILITY_PUBLIC';
    }

    public function fullLocation(): string
    {
        return collect([
            $this->city,
            $this->province,
            $this->country,
        ])
            ->filter()
            ->implode(', ');
    }

    public function hasCompliance(string $standard): bool
{
    return in_array(
        $standard,
        $this->compliance_standards ?? [],
        true
    );
}

public function factorySummary(): array
{
    return [

        'factory_name' => $this->factory_name,

        'location' => $this->fullLocation(),

        'production_lines' => $this->production_lines,

        'number_of_shifts' => $this->number_of_shifts,

    ];
}
}