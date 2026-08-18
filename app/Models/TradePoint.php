<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\TradePointAlias;

class TradePoint extends Model
{
    protected $fillable = [
        'code',
        'name',
        'name_en',
        'trade_point_type_id',
        'province_id',
        'city',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(
            TradePointType::class,
            'trade_point_type_id'
        );
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(
            Province::class,
            'province_id'
        );
    }

    public function tradeStatistics(): HasMany
    {
        return $this->hasMany(
            TradeStatistic::class,
            'trade_point_id'
        );
    }
    public function aliases()
{
    return $this->hasMany(
        TradePointAlias::class,
        'trade_point_id'
    );
}
}