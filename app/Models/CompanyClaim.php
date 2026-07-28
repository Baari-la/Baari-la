<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyClaim extends Model
{
    protected $fillable = [

        /*
        |--------------------------------------------------------------------------
        | Company
        |--------------------------------------------------------------------------
        */

        'company_id',
        'claimed_company_name',

        /*
        |--------------------------------------------------------------------------
        | Applicant
        |--------------------------------------------------------------------------
        */

        'user_id',

        'full_name',
        'position',

        'email',
        'phone',

        /*
        |--------------------------------------------------------------------------
        | Ownership Verification
        |--------------------------------------------------------------------------
        */

        'nib',

        'verification_document_type',
        'verification_document',

        /*
        |--------------------------------------------------------------------------
        | Notes
        |--------------------------------------------------------------------------
        */

        'notes',

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        'status',

        'submitted_at',
        'reviewed_at',

        /*
        |--------------------------------------------------------------------------
        | Admin Review
        |--------------------------------------------------------------------------
        */

        'reviewed_by',
        'rejection_reason',
    ];

    protected $casts = [

        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',

        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function company(): BelongsTo
    {
        return $this->belongsTo(
            Company::class
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class
        );
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'reviewed_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isPending(): bool
    {
        return $this->status ===
            'pending';
    }

    public function isApproved(): bool
    {
        return $this->status ===
            'approved';
    }

    public function isRejected(): bool
    {
        return $this->status ===
            'rejected';
    }
}