<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyProduct extends Model
{
    use HasFactory;

    protected $table = 'company_products';

    protected $fillable = [
        'company_id',
        'product_name',
        'hs_code',
        'description'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}