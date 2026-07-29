<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyCertification extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    protected $table = 'company_certifications';

    /*
    |--------------------------------------------------------------------------
    | Mass Assignable Fields
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'company_id',
        'certification_name',
        'category',
        'certification_code',
        'issuer',
        'certificate_number',
        'description',
        'certificate_file',
        'logo_url',
        'is_verified',
        'is_featured',
        'sort_order',
        'valid_until',
        'issued_at',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | Attribute Casting
    |--------------------------------------------------------------------------
    |
    | Standardized values for:
    |
    | - Digital Company Passport
    | - Compliance Intelligence
    | - Readiness Engine
    | - Supplier Matching
    |
    */

    protected $casts = [
        'is_verified' => 'boolean',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',

        'issued_at' => 'date',
        'valid_until' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}