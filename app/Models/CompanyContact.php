<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyContact extends Model
{
    use HasFactory;

    protected $table = 'company_contacts';

 protected $fillable = [
    'company_id',
    'contact_name',
    'position',
    'phone',
    'whatsapp',
    'email',
    'photo_url',
    'is_primary',
];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}