<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyIdentityCapability extends Model
{
    protected $fillable = [
        'company_identity_id',
        'capability',
        'source',
        'is_verified',
        'verified_at',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function identity(): BelongsTo
    {
        return $this->belongsTo(
            CompanyIdentity::class,
            'company_identity_id'
        );
    }
}