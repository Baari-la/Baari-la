<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyClaim extends Model
{
    protected $fillable = [

        /*
        |--------------------------------------------------------------------------
        | Company References
        |--------------------------------------------------------------------------
        |
        | company_id:
        | Legacy company record reference.
        |
        | company_identity_id:
        | Canonical company identity reference used by the new
        | Digital Directory claim flow.
        |
        */

        'company_id',
        'company_identity_id',
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

    /**
     * Legacy company record.
     *
     * Kept for compatibility with the existing claim flow.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(
            Company::class,
            'company_id'
        );
    }

    /**
     * Canonical company identity.
     *
     * New company claims should preferably reference this relation.
     */
    public function companyIdentity(): BelongsTo
    {
        return $this->belongsTo(
            CompanyIdentity::class,
            'company_identity_id'
        );
    }

    /**
     * User submitting the claim.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    /**
     * Admin/user reviewing the claim.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'reviewed_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS HELPERS
    |--------------------------------------------------------------------------
    */

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /*
    |--------------------------------------------------------------------------
    | CLAIM TYPE HELPERS
    |--------------------------------------------------------------------------
    */

    /**
     * Determine whether this claim references a canonical identity.
     */
    public function hasCanonicalIdentity(): bool
    {
        return $this->company_identity_id !== null;
    }

    /**
     * Determine whether this claim references a legacy company record.
     */
    public function hasLegacyCompany(): bool
    {
        return $this->company_id !== null;
    }

    /**
     * Claim created through the canonical identity flow.
     */
    public function isCanonicalClaim(): bool
    {
        return $this->hasCanonicalIdentity();
    }

    /**
     * Legacy-only claim.
     *
     * A future claim may contain both company_identity_id and company_id
     * during migration/backward compatibility, so legacy-only means that
     * no canonical identity has been assigned.
     */
    public function isLegacyClaim(): bool
    {
        return !$this->hasCanonicalIdentity()
            && $this->hasLegacyCompany();
    }

    /**
     * Best available company name for displaying this claim.
     *
     * Priority:
     * 1. Canonical identity
     * 2. Submitted claim name
     * 3. Legacy company name
     */
    public function displayCompanyName(): ?string
    {
        if ($this->companyIdentity) {
            return $this->companyIdentity
                ->canonical_name;
        }

        if (
            is_string($this->claimed_company_name)
            && trim($this->claimed_company_name) !== ''
        ) {
            return trim(
                $this->claimed_company_name
            );
        }

        return $this->company
            ?->nama_perusahaan;
    }
}