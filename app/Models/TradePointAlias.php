<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TradePointAlias extends Model
{
    protected $fillable = [
        'trade_point_id',
        'source_name',
        'normalized_name',
        'source_system',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function tradePoint(): BelongsTo
    {
        return $this->belongsTo(
            TradePoint::class,
            'trade_point_id'
        );
    }
}