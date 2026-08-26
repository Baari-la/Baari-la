<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GarmentConversionFactor extends Model
{
    use HasFactory;

    protected $table = 'garment_conversion_factors';

    protected $fillable = [
        'hs_code',

        'factor',
        'methodology',

        'evidence_type',
        'weight_unit',

        'evidence_count',
        'total_sample_size',

        'calculation_method',

        'observed_minimum',
        'observed_maximum',

        'evidence_references',

        'reviewer',
        'reviewer_role',

        'activator',
        'activator_role',

        'status',
    ];

    protected $casts = [
        'factor' => 'decimal:6',

        'evidence_count' => 'integer',
        'total_sample_size' => 'integer',

        'observed_minimum' => 'decimal:6',
        'observed_maximum' => 'decimal:6',

        'evidence_references' => 'array',
    ];
}