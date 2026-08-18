<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    protected $fillable = [
        'code',
        'name',
        'name_en',
        'island_group',
        'region_group',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function tradePoints(): HasMany
    {
        return $this->hasMany(
            TradePoint::class,
            'province_id'
        );
    }

    public function tradeStatistics(): HasMany
    {
        return $this->hasMany(
            TradeStatistic::class,
            'province_id'
        );
    }
}