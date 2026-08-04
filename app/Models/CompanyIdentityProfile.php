<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\CompanyIdentityProfile;

class CompanyIdentityProfile extends Model
{
    protected $fillable = [

        'company_identity_id',

        'company_type',

        'phone',
        'website',

        'country',
        'province',
        'city',
        'postal_code',

        'address',

        'data_source',

        'last_reviewed_at',

        'last_updated_by',
    ];

    protected $casts = [

        'last_reviewed_at' => 'datetime',

        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function companyIdentity(): BelongsTo
    {
        return $this->belongsTo(
            CompanyIdentity::class
        );
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'last_updated_by'
        );
    }
    public function profile()
{
    return $this->hasOne(
        CompanyIdentityProfile::class
    );
}
}