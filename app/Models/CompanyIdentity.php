<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Models\CompanyIdentityProfile;
use App\Models\CompanyIdentityBusiness;
use App\Models\CompanyIdentityCapability;
use App\Models\CompanyIdentityCapabilityProfile;

class CompanyIdentity extends Model
{
    protected $fillable = [
        'canonical_name',
        'normalized_name',
        'country_code',
        'country_name',
        'identity_status',
        'verification_status',
        'verified_at',
        'created_from',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Legacy Sources
    |--------------------------------------------------------------------------
    */

    public function sources(): HasMany
    {
        return $this->hasMany(
            CompanyIdentitySource::class,
            'company_identity_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Legacy Company IDs
    |--------------------------------------------------------------------------
    */

    public function sourceCompanyIds(): array
    {
        return $this->sources()
            ->pluck('company_id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | Legacy Capability Union
    |--------------------------------------------------------------------------
    |
    | Union of all capabilities from legacy directory records.
    | Used before owner-managed capability tags become available.
    |
    */

    public function capabilityUnion(): array
    {
        $companyIds = $this->sourceCompanyIds();

        if ($companyIds === []) {
            return [];
        }

        return CompanyCapability::query()
            ->whereIn('company_id', $companyIds)
            ->pluck('capability')
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scope
    |--------------------------------------------------------------------------
    */

    public function scopeReady($query)
    {
        return $query->where(
            'identity_status',
            'READY'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Company Profile
    |--------------------------------------------------------------------------
    */

    public function profile(): HasOne
    {
        return $this->hasOne(
            CompanyIdentityProfile::class,
            'company_identity_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Business Profile
    |--------------------------------------------------------------------------
    */

    public function business(): HasOne
    {
        return $this->hasOne(
            CompanyIdentityBusiness::class,
            'company_identity_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Capability Profile
    |--------------------------------------------------------------------------
    */

    public function capabilityProfile(): HasOne
    {
        return $this->hasOne(
            CompanyIdentityCapabilityProfile::class,
            'company_identity_id'
        );
    }

/*
|--------------------------------------------------------------------------
| Factories
|--------------------------------------------------------------------------
|
| One canonical company may own multiple factories.
|
*/

public function factories(): HasMany
{
    return $this->hasMany(
        CompanyFactory::class,
        'company_identity_id'
    );
} 

    /*
    |--------------------------------------------------------------------------
    | Capability Tags
    |--------------------------------------------------------------------------
    */

    public function capabilities(): HasMany
    {
        return $this->hasMany(
            CompanyIdentityCapability::class,
            'company_identity_id'
        );
    }
    /*
|--------------------------------------------------------------------------
| Business Locations™
|--------------------------------------------------------------------------
*/

public function locations(): HasMany
{
    return $this->hasMany(
        CompanyIdentityLocation::class,
        'company_identity_id'
    )->orderBy('display_order');
}
}