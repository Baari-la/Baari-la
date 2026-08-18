<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TradeStatistic extends Model
{
    use SoftDeletes;

    protected $fillable = [
    'source',
    'import_batch_id',

    'trade_flow',
    'year',
    'month',
    'dimension',

    'hs_code',
    'hs_description',

    'hs_id',

    'country_code',
    'country_name',
    'country_id',

    'province_code',
    'province_name',
    'province_id',

    'port_code',
    'port_name',
    'trade_point_id',
    'trade_point_type_id',

    'trade_identity',


    'product',
    'product_category',
    'industry_segment',

    'hs_code',
    'hs_description',

    'country_code',
    'country_name',
    'country_id',

    'province_code',
    'province_name',

    'port_code',
    'port_name',

    'trade_identity',

    'trade_value',
    'currency',

    'trade_volume',
    'volume_unit',

    'release_date',
    'remarks',
];
    protected $casts = [

        'trade_value' => 'decimal:6',

        'trade_volume' => 'decimal:6',

        'release_date' => 'date',

    ];

    public function importBatch()
    {
        return $this->belongsTo(
            TradeImportBatch::class,
            'import_batch_id'
        );
    }

    
}