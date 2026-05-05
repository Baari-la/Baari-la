<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        // Gunakan helper session() yang lebih global dan stabil
        $locale = session('locale', config('app.locale'));
        
        // Paksa aplikasi menggunakan bahasa tersebut
        App::setLocale($locale);
        
        // Simpan juga ke config agar library lain (seperti Carbon) ikut berubah
        config(['app.locale' => $locale]);

        return $next($request);
    }
}