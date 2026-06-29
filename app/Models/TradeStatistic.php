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
        'product',
        'product_category',
        'industry_segment',
        'hs_code',
        'hs_description',
        'country_code',
        'country_name',
        'country_id',
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