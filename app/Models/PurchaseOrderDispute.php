<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDispute extends Model
{
    protected $fillable = [
    'purchase_order_id',
    'created_by',
    'dispute_number',
    'category',
    'description',
    'supplier_response',
    'status',
    'reviewed_at',
    'resolved_at',
    'closed_at',
    ];

    protected $casts = [
    'reviewed_at' => 'datetime',
    'resolved_at' => 'datetime',
    'closed_at' => 'datetime',
];

    public function purchaseOrder()
    {
        return $this->belongsTo(
            PurchaseOrder::class
        );
    }

    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function disputes()
{
    return $this->hasMany(
        PurchaseOrderDispute::class,
        'purchase_order_id'
    );
}
}