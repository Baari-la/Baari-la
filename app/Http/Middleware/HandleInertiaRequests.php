<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        // Ambil locale dari session, default ke config app
        $currentLocale = session('locale', config('app.locale'));

        return array_merge(parent::share($request), [
            // Status Login & Lokalisasi (Bisa diakses Guest maupun Member)
            'isLoggedIn'   => auth()->check(),
            'locale'       => $currentLocale, 
            
            // Membaca file json bahasa (en.json / id.json)
            'translations' => file_exists(lang_path("$currentLocale.json")) 
                ? json_decode(file_get_contents(lang_path("$currentLocale.json")), true) 
                : [],
                
            // Flash Message untuk notifikasi sukses/gagal
            'flash' => [
                'message' => $request->session()->get('message'),
            ],
                
            // Data Autentikasi User Tergabung Lengkap
            'auth' => [
                'user' => $request->user() ? [
                    'id'            => $request->user()->id,
                    'name'          => $request->user()->name,
                    'email'         => $request->user()->email,
                    'role'          => $request->user()->role,
                    'company_id'    => $request->user()->company_id,
                    'member_status' => $request->user()->member_status ?? 'Free',
                    'locale'        => $request->user()->locale ?? $currentLocale, 
                    'is_api_member' => !empty($request->user()->member_number), 
                    'member_number' => $request->user()->member_number,
                ] : null,
            ],
        ]);
    }
}