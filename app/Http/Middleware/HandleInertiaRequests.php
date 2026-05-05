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

    return [
        ...parent::share($request),
        
        // 1. PINDAHKAN LOCALE KE SINI (Agar bisa diakses Guest & Member)
        'locale' => $currentLocale, 
 // TAMBAHKAN BARIS INI: Membaca file en.json atau id.json
        'translations' => file_exists(lang_path("$currentLocale.json")) 
            ? json_decode(file_get_contents(lang_path("$currentLocale.json")), true) 
            : [],
            'flash' => [
    'message' => $request->session()->get('message'),
],
            
        'auth' => [
            'user' => $request->user() ? [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'role' => $request->user()->role,
                // Baris locale di sini boleh tetap ada atau dihapus
                'locale' => $currentLocale, 
                'is_api_member' => !empty($request->user()->member_number), 
                'member_number' => $request->user()->member_number,
                'company_id' => $request->user()->company_id,
            ] : null,
        ],
    ];
}
}