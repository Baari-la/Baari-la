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
'email',
'created_at',
'updated_at',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}