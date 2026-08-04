<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyMachine extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Mass Assignable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        /*
        |--------------------------------------------------------------------------
        | Legacy Relationship
        |--------------------------------------------------------------------------
        */

        'company_id',

        /*
        |--------------------------------------------------------------------------
        | Canonical Factory
        |--------------------------------------------------------------------------
        */

        'factory_id',
        'company_identity_id',

        /*
        |--------------------------------------------------------------------------
        | Machine Identity
        |--------------------------------------------------------------------------
        */

        'machine_category',
        'machine_type',

        'machine_brand',
        'machine_model',

        'quantity',

        'year_installed',

        'country_origin',

        /*
        |--------------------------------------------------------------------------
        | Production
        |--------------------------------------------------------------------------
        */

        'production_capacity',
        'capacity_unit',

        'working_width',
        'gauge_specification',

        /*
        |--------------------------------------------------------------------------
        | Operations
        |--------------------------------------------------------------------------
        */

        'machine_condition',
        'automation_level',

        'energy_consumption',
        'energy_unit',

        /*
        |--------------------------------------------------------------------------
        | Factory Passport
        |--------------------------------------------------------------------------
        */

        'is_primary',

        'is_active',

        'notes',
    ];

    /*
    |--------------------------------------------------------------------------
    | Attribute Casting
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'quantity' => 'integer',

        'production_capacity' => 'decimal:2',

        'energy_consumption' => 'decimal:2',

        'year_installed' => 'integer',

        'is_primary' => 'boolean',

        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Legacy Relationship
    |--------------------------------------------------------------------------
    */

    public function company(): BelongsTo
    {
        return $this->belongsTo(
            Company::class,
            'company_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Canonical Factory
    |--------------------------------------------------------------------------
    */

    public function factory(): BelongsTo
    {
        return $this->belongsTo(
            CompanyFactory::class,
            'factory_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Canonical Company Identity
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
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePrimary(
        Builder $query
    ): Builder {
        return $query->where(
            'is_primary',
            true
        );
    }

    public function scopeActive(
        Builder $query
    ): Builder {
        return $query->where(
            'is_active',
            true
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isPrimary(): bool
    {
        return $this->is_primary;
    }

    public function isLegacyMachine(): bool
    {
        return $this->factory_id === null;
    }
}