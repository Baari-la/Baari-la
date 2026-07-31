<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    /**
     * Legacy records that support this canonical identity.
     */
    public function sources(): HasMany
    {
        return $this->hasMany(
            CompanyIdentitySource::class,
            'company_identity_id'
        );
    }

    /**
     * Convenience helper.
     *
     * Returns the legacy company IDs behind this identity.
     */
    public function sourceCompanyIds(): array
    {
        return $this->sources()
            ->pluck('company_id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    /**
     * Union of capabilities from all legacy company records.
     *
     * This is calculated from existing company_capabilities.
     * Nothing is copied or modified.
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

    /**
     * Only identities ready to participate in canonical lookup.
     */
    public function scopeReady($query)
    {
        return $query->where(
            'identity_status',
            'READY'
        );
    }

    public function capabilities()
{
    return $this->hasMany(
        CompanyIdentityCapability::class,
        'company_identity_id'
    );
}
}