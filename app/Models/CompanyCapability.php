<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompanyCapability extends Model
{
    protected $fillable = [
        'company_id',
        'capability',
        'is_primary',
        'source',
        'is_verified',
        'verified_at',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    /**
 * Structured business capabilities owned by the company.
 *
 * Examples:
 * - yarn_spinner
 * - weaving_mill
 * - knitting_mill
 * - dyeing_finishing_mill
 * - garment_manufacturer
 */
public function capabilities(): HasMany
{
    return $this->hasMany(
        CompanyCapability::class,
        'company_id'
    );
}
}