<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TradeIntelligenceSnapshot extends Model
{
    protected $table = 'trade_intelligence_snapshots';

    protected $fillable = [
        'snapshot_key',
        'snapshot_type',
        'sector',
        'period_key',
        'version',
        'status',
        'payload',
        'checksum',
        'generated_at',
        'validated_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'generated_at' => 'datetime',
        'validated_at' => 'datetime',
    ];
}