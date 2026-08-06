<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompanyIdentityLocation extends Model
{
    protected $fillable = [

        'company_identity_id',

        'location_type',
        'location_code',
        'location_name',
        'location_label',

        'is_primary',

        'address',
        'country',
        'province',
        'city',
        'district',
        'subdistrict',
        'postal_code',

        'contact_person',
        'phone',
        'email',
        'website',

        'latitude',
        'longitude',
        'google_maps_url',

        'metadata',

        'is_active',
        'display_order',
    ];

    protected $casts = [

        'is_primary' => 'boolean',

        'is_active' => 'boolean',

        'metadata' => 'array',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(
            CompanyIdentity::class,
            'company_identity_id'
        );
    }
 
    public function locations(): HasMany
{
    return $this->hasMany(
        CompanyIdentityLocation::class
    )->orderBy('display_order');
}
}