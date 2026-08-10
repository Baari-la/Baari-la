<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyIdentityMediaAsset extends Model
{
    protected $table = 'company_identity_media_assets';

    protected $fillable = [
        'company_identity_id',
        'media_type',
        'file_path',
        'disk',
        'file_url',
        'mime_type',
        'file_size',
        'title',
        'caption',
        'sort_order',
        'is_featured',
        'verification_status',
        'verified_at',
        'verified_by',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'sort_order' => 'integer',
        'is_featured' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function companyIdentity(): BelongsTo
    {
        return $this->belongsTo(
            CompanyIdentity::class,
            'company_identity_id'
        );
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'verified_by'
        );
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }
}