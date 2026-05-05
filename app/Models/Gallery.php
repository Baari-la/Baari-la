<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    // Ini sudah benar, untuk mengizinkan input data
    protected $fillable = [
        'title_id',
        'title_en',
        'category',
        'image_path',
        'event_date'
    ];
}