<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradeImportBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'source',
        'filename',
        'trade_flow',
        'year',
        'release_date',
        'total_rows',
        'inserted_rows',
        'updated_rows',
        'skipped_rows',
        'failed_rows',
        'status',
        'remarks',
        'created_by',
    ];

    protected $casts = [
        'release_date' => 'date',
        'total_rows' => 'integer',
        'inserted_rows' => 'integer',
        'updated_rows' => 'integer',
        'skipped_rows' => 'integer',
        'failed_rows' => 'integer',
    ];
public function tradeStatistics()
{
    return $this->hasMany(
        TradeStatistic::class,
        'import_batch_id'
    );
}
    /**
     * User yang melakukan import
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}