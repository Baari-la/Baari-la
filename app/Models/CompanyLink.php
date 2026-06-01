<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyLink extends Model
{
    use HasFactory;

    protected $table = 'company_links';

    protected $fillable = [
        'company_id',
       'link_type',
        'url'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}