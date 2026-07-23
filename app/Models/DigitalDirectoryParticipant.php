<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DigitalDirectoryParticipant extends Model
{
    /**
     * --------------------------------------------------------------------------
     * Table
     * --------------------------------------------------------------------------
     */

    protected $table =
        'digital_directory_participants';

    /**
     * --------------------------------------------------------------------------
     * Mass Assignment
     * --------------------------------------------------------------------------
     */

    protected $fillable = [

        'invoice_number',
        'activated_at',
        /*
        |--------------------------------------------------------------------------
        | Package
        |--------------------------------------------------------------------------
        */

        'package',

        /*
        |--------------------------------------------------------------------------
        | Company Information
        |--------------------------------------------------------------------------
        */

        'company_name',
        'pic_name',
        'position',
        'email',
        'phone',
        'website',
        'company_type',
        'country',
        'city',

        /*
        |--------------------------------------------------------------------------
        | Payment
        |--------------------------------------------------------------------------
        */

        'payment_method',
        'payment_gateway',
        'transaction_id',
        'external_transaction_id',
        'amount',
        'currency',
        'payment_reference',
        'payment_receipt',

        /*
        |--------------------------------------------------------------------------
        | QRIS
        |--------------------------------------------------------------------------
        */

        'qris_reference',
        'qris_payload',

        /*
        |--------------------------------------------------------------------------
        | Virtual Account
        |--------------------------------------------------------------------------
        */

        'virtual_account_number',

        /*
        |--------------------------------------------------------------------------
        | Gateway
        |--------------------------------------------------------------------------
        */

        'gateway_response',

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        'payment_status',
        'activation_status',

        /*
        |--------------------------------------------------------------------------
        | Services
        |--------------------------------------------------------------------------
        */

        'visibility_score_active',
        'company_passport_active',
        'executive_dashboard_active',
        'smart_matching_active',
        'build_supply_chain_active',

        /*
        |--------------------------------------------------------------------------
        | Verification
        |--------------------------------------------------------------------------
        */

        'paid_at',
        'payment_verified_at',
        'verified_by',

        /*
        |--------------------------------------------------------------------------
        | Notes
        |--------------------------------------------------------------------------
        */

        'admin_notes',

        /*
        |--------------------------------------------------------------------------
        | Relations
        |--------------------------------------------------------------------------
        */

        'user_id',
        'company_id',
    ];

    /**
     * --------------------------------------------------------------------------
     * Casts
     * --------------------------------------------------------------------------
     */

    protected $casts = [

        'amount' => 'decimal:2',

        'gateway_response' => 'array',

        'paid_at' =>
            'datetime',

        'payment_verified_at' =>
            'datetime',

        'visibility_score_active' =>
            'boolean',

        'company_passport_active' =>
            'boolean',

        'executive_dashboard_active' =>
            'boolean',

        'smart_matching_active' =>
            'boolean',

        'build_supply_chain_active' =>
            'boolean',
    ];

    /**
     * --------------------------------------------------------------------------
     * User
     * --------------------------------------------------------------------------
     */

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Company
     * --------------------------------------------------------------------------
     */

    public function company(): BelongsTo
    {
        return $this->belongsTo(
            Company::class
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Verified By
     * --------------------------------------------------------------------------
     */

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'verified_by'
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Helper
     * --------------------------------------------------------------------------
     */

    public function isPaid(): bool
    {
        return in_array(
            $this->payment_status,
            [
                'paid',
                'verified',
            ]
        );
    }

    public function isActive(): bool
    {
        return $this->activation_status
            === 'active';
    }

    public function isVerified(): bool
    {
        return $this->payment_status
            === 'verified';
    }
}