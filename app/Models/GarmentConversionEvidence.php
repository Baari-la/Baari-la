<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GarmentConversionEvidence extends Model
{
    protected $table = 'garment_conversion_evidence';

    protected $fillable = [
        'hs_code',

        'product_group',
        'product_type',
        'conversion_sub_group',
        'methodology',

        'evidence_type',

        'sample_size',

        'average_weight',
        'minimum_weight',
        'maximum_weight',
        'weight_unit',

        'material_composition',
        'product_specification',

        'source_type',
        'source_reference',
        'source_date',

        'country',
        'market',

        'confidence_level',
        'validation_status',

        'reviewed_by',
        'reviewed_at',

        'notes',
    ];

    protected $casts = [
        'source_date' => 'date',

        'reviewed_at' => 'datetime',

        'sample_size' => 'integer',

        'average_weight' => 'decimal:6',
        'minimum_weight' => 'decimal:6',
        'maximum_weight' => 'decimal:6',
    ];
}