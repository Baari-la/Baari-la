<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TradePointType extends Model
{
    protected $fillable = [
        'code',
        'name',
        'name_en',
        'description',
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
            'trade_point_type_id'
        );
    }
}