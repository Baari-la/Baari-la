<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TradeUnitClassification extends Model
{
    protected $fillable = [
        'hs_code',
        'hs_description',
        'sector',
        'product_type',
        'product_group',
        'official_unit',
        'intelligence_unit',
        'conversion_enabled',
        'conversion_factor',
        'conversion_method',
        'conversion_source',
        'conversion_confidence',
        'status',
        'classification_source',
        'notes',
    ];

    protected $casts = [
        'conversion_enabled' => 'boolean',
        'conversion_factor' => 'decimal:6',
    ];
}