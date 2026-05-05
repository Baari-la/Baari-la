<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    // Agar data bisa disimpan dari form
    protected $fillable = [
        'name', 'category', 'stock', 'unit', 
        'warehouse_location', 'whatsapp_contact', 'description', 'price'
    ];
}