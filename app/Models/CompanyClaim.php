<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyClaim extends Model
{
    protected $fillable = [

        'company_id',
        'user_id',

        'full_name',
        'position',

        'email',
        'phone',

        'notes',

        'status',

        'submitted_at',
        'reviewed_at',
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

    public function company()
    {
        return $this->belongsTo(
            Company::class
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class
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