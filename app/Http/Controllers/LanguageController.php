<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch(Request $request)
    {
        // Ambil data locale dari request body (POST)
        $locale = $request->locale;

        if (in_array($locale, ['en', 'id'])) {
            // 1. Simpan ke Session secara permanen
            session(['locale' => $locale]);
            
            // 2. Paksa simpan session saat ini juga agar tidak hilang saat refresh
            session()->save();
            
            // 3. Set locale aplikasi
            App::setLocale($locale);
        }
        
        return redirect()->back();
    }
}