<?php

namespace App\Models;

// Baris krusial untuk memperbaiki TypeError
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Member extends Authenticatable
{
    use Notifiable;

    protected $table = 'perusahaan_api';
    protected $primaryKey = 'id_perusahaan';
    public $timestamps = false;

    protected $fillable = [
        'nama_perusahaan', 'email', 'password', 'is_premium', 
        'status_iuran', 'periode_selesai', 'last_login'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}