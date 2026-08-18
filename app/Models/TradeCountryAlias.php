<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TradeCountryAlias extends Model
{
    protected $fillable = [
        'country_id',
        'source_name',
        'normalized_name',
        'source_system',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(
            MstCountry::class,
            'country_id'
        );
    }
}