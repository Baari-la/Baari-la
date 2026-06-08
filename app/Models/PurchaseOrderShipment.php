<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderShipment extends Model
{
    protected $fillable = [
        'purchase_order_id',
        'carrier',
        'tracking_number',
        'container_number',
        'bl_number',
        'etd',
        'eta',
        'current_location',
        'remarks',
        'created_by',
    ];

    protected $casts = [
        'etd' => 'date',
        'eta' => 'date',
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

    public function tracks()
{
    return $this->hasMany(
        PurchaseOrderShipmentTrack::class,
        'shipment_id'
    )->orderBy('tracked_at');
}
}