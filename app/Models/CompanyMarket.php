<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyMarket extends Model
{
    use HasFactory;

    protected $table = 'company_markets';

    protected $fillable = [
        'company_id',
        'country_name',
        'market_type'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function markets()
{
    return $this->hasMany(
        CompanyMarket::class,
        'company_id'
    );
}
}