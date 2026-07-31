<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyIdentitySource extends Model
{
    protected $fillable = [
        'company_identity_id',
        'company_id',
        'source_type',
    ];

    /**
     * Canonical identity represented by this source record.
     */
    public function identity(): BelongsTo
    {
        return $this->belongsTo(
            CompanyIdentity::class,
            'company_identity_id'
        );
    }

    /**
     * Original legacy company record.
     *
     * There is intentionally no database FK from
     * company_identity_sources.company_id to companies.id,
     * but Eloquent can still use the logical relationship.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(
            Company::class,
            'company_id'
        );
    }
}