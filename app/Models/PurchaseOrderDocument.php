<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDocument extends Model
{
    protected $fillable = [
        'purchase_order_id',
        'uploaded_by',
        'document_type',
        'document_number',
        'file_path',
        'remarks',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(
            PurchaseOrder::class
        );
    }

    public function uploader()
    {
        return $this->belongsTo(
            User::class,
            'uploaded_by'
        );
    }

    public function uploadedDocuments()
{
    return $this->hasMany(
        PurchaseOrderDocument::class,
        'uploaded_by'
    );
}

}