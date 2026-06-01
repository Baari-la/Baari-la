<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TextileStat extends Model
{
    // Nama tabel database yang disesuaikan
    protected $table = 'textile_stats';

    // Membuka proteksi kolom agar Laravel bisa memasukkan data massal dari API
    protected $guarded = [];

    // Mengubah string tanggal otomatis menjadi objek Carbon/Date
    protected $casts = [
        'period' => 'date',
    ];
}