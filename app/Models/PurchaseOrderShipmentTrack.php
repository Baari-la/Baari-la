<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderShipmentTrack extends Model
{
  protected $fillable = [
        'shipment_id',
        'status',
        'location',
        'remarks',
        'tracked_at',
    ];

    protected $casts = [
        'tracked_at' => 'datetime',
    ];

    public function shipment()
    {
        return $this->belongsTo(
            PurchaseOrderShipment::class,
            'shipment_id'
        );
    }
}