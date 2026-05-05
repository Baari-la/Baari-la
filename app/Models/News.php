<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class News extends Model
{
    // Nama tabel secara default adalah 'news', jika berbeda silakan tambahkan:
    // protected $table = 'news';

     protected $fillable = ['title_id', 'content_id', 'title_en', 'content_en', 'slug', 'author_id', 'image'];


    // TAMBAHKAN BARIS INI: Agar kolom 'title' & 'content' muncul di React
    protected $appends = ['title', 'content']; 

    // Fungsi "Sakti" otomatis pilih bahasa (Accessor)
    // Di React nanti, Bapak cukup panggil 'title' dan 'content' saja
   public function getTitleAttribute() {
        return app()->getLocale() == 'en' ? $this->title_en : $this->title_id;
    }

    public function getContentAttribute() {
        return app()->getLocale() == 'en' ? $this->content_en : $this->content_id;
    }
public function getRouteKeyName()
{
    return 'slug';
}
    
}