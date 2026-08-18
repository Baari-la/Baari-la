<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HsCode extends Model
{
    protected $table = 'mst_hscode';

    protected $primaryKey = 'id_hs';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'hs_code',
        'uraian_hs_id',
        'uraian_hs_en',
        'chapter',
        'heading',
        'subheading',
        'sector_id',
        'product_family',
        'is_textile',
        'is_fiber',
        'is_yarn',
        'is_fabric',
        'is_technical_textile',
        'is_apparel',
        'is_madeup',
        'is_active',
    ];

    protected $casts = [
        'is_textile' => 'boolean',
        'is_fiber' => 'boolean',
        'is_yarn' => 'boolean',
        'is_fabric' => 'boolean',
        'is_technical_textile' => 'boolean',
        'is_apparel' => 'boolean',
        'is_madeup' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function sector(): BelongsTo
    {
        return $this->belongsTo(
            TextileSector::class,
            'sector_id'
        );
    }
}