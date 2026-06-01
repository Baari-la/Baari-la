<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyCertification extends Model
{
    use HasFactory;

    protected $table = 'company_certifications';

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

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}