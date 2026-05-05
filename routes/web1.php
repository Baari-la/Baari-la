<?php
use App\Http\Controllers\CompanyController; // <--- WAJIB ADA BARIS INI
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TradeIntelligenceController;
use App\Http\Controllers\HomeController;
use App\Models\News;
use App\Models\Member;
use App\Models\MarketHistory; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\GalleryController;
use Inertia\Inertia;
use App\Http\Controllers\TradeDashboardController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\Auth\GoogleController;


Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
/*

|--------------------------------------------------------------------------
| LEVEL 1: PUBLIK (FREE / CENTRIC) - Tanpa Login
|--------------------------------------------------------------------------
*/

// Partnership
Route::get('/partnership', function () {
    return Inertia::render('Partnership/Index');
})->name('partnership');
// Definisi rute praktis tanpa controller
Route::inertia('/join-us', 'Company/JoinUs')->name('join.us');

// Route untuk mengarahkan ke Google
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');

// Route callback setelah user login di Google
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Route::get('/', [LandingPageController::class, 'index'])->name('home');

Route::get('/about', [HomeController::class, 'about'])->name('about');
// Big Data
Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');

Route::get('/pricing', function () {
    return Inertia::render('Auth/PricingPage');
})->name('pricing.index');


Route::middleware(['auth'])->group(function () {
    Route::get('/intelligence-center', [AnalyticsController::class, 'deepAnalysis'])
         ->name('intelligence.center');
});
// Tambahkan di web.php jika belum ada
// Route::get('/partnership', [PageController::class, 'partnership'])->name('partnership');

/*


|--------------------------------------------------------------------------
| LEVEL 2: ANGGOTA (Wajib Login)
|--------------------------------------------------------------------------
*/
// Kalkulator garmen
Route::get('/industrial-tools/calculator', function () {
    return Inertia::render('Tools/GarmentCalculatorPage');
})->name('tools.calculator');

Route::get('/trade-dashboard', [TradeDashboardController::class, 'index'])->name('trade.dashboard');

// Rute Publik untuk Verifikasi Profil (Bisa dibuka siapa saja)
Route::get('/v/{nomor_anggota}', [CompanyController::class, 'publicVerify'])
    ->name('companies.public_verify');

Route::get('/companies/{company}/download-qr', [CompanyController::class, 'downloadQrCode'])
    ->name('companies.download_qr')
    ->middleware('auth');


Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/approve-update/{id}', [AdminDashboardController::class, 'approveUpdate'])->name('admin.approve-update');
    // Gunakan path lengkap ke folder Admin agar tidak bentrok dengan yang lama
    Route::get('/admin/gallery', [GalleryController::class, 'index'])->name('admin.gallery.index');
    Route::post('/admin/gallery', [GalleryController::class, 'store'])->name('admin.gallery.store');
    Route::delete('/admin/gallery/{gallery}', [GalleryController::class, 'destroy'])->name('admin.gallery.destroy');
});


Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard Utama
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard', [
            'cottonPrice' => '71.31',
            'exportValue' => '11.9',
            'memberStatus' => auth()->user()->is_premium ? 'Premium Member' : 'Regular Member'
        ]);
    })->name('dashboard');

    // Direktori Anggota
    Route::get('/members', function (Request $request) {
        return Inertia::render('Members/Index', [
            'members' => Member::all(),
            'initialSearch' => $request->query('search')
        ]);
    })->name('members.list');



// Tambahan trade ekspor impor
Route::get('/trade-radar', [TradeIntelligenceController::class, 'index'])->name('trade.radar');
 Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    // Route::get('/analytics', [AnalyticsController::class, 'index'])->name('admin.analytics'); // Nama harus unik


Route::middleware(['auth'])->group(function () {
    // ... rute lainnya ...
    
    // Rute untuk download sertifikat PDF milik sendiri
    Route::get('/my-company/certificate', [CompanyController::class, 'downloadMyCertificate'])
        ->name('companies.my_certificate');
});



    // --- TAMBAHAN RUTE BURSA BAHAN (INVENTORY) ---
    // Pastikan memanggil fungsi yang benar di Controller (misal: indexInventory & createInventory)
    // --- RUTE BURSA BAHAN (INVENTORY) ---
// Gunakan satu blok rute yang konsisten mengarah ke TradeIntelligenceController
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Tampilan Daftar Barang
    Route::get('/inventory', [TradeIntelligenceController::class, 'indexInventory'])->name('inventory.index');
    
    // Tampilan Form Tambah (Pastikan di Controller namanya 'create' atau 'createInventory')
    Route::get('/inventory/create', [TradeIntelligenceController::class, 'create'])->name('inventory.create');
    
    // Proses Simpan ke Database
    Route::post('/inventory', [TradeIntelligenceController::class, 'storeInventory'])->name('inventory.store');

});
Route::get('/regulation', function () {
    return inertia('Regulation/Index'); // Gunakan huruf kecil 'inertia' agar aman
})->name('regulation.index');

Route::get('/matchmaking', function () {
    return inertia('Matchmaking/Index'); // Gunakan huruf kecil 'inertia' agar aman
})->name('matchmaking.index');
// routes/web.php

// Buku Direktori atau Big Data
// Rute Privat (Hanya untuk yang sudah Login)
Route::middleware('auth')->group(function () {
Route::get('/company/create', [CompanyController::class, 'create'])->name('companies.create'); 
Route::get('/companies/{company}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
    Route::post('/companies/{company}', [CompanyController::class, 'update'])->name('companies.update');
 
    Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
    Route::post('/companies/{company}/verify', [CompanyController::class, 'verify'])
    ->name('companies.verify');
    });

Route::get('/companies/{company}', [CompanyController::class, 'show'])->name('companies.show');
Route::post('/premium-request', [CompanyController::class, 'requestPremium'])->name('premium.request');



// 1. Rute Statis (Wajib di atas)
Route::get('/news/create', [NewsController::class, 'create'])->name('news.create');
Route::post('/news', [NewsController::class, 'store'])->name('news.store');
Route::post('/news/translate', [NewsController::class, 'translate'])->name('news.translate');

// 2. Rute dengan Parameter (Wajib di bawah rute statis)
// Route::get('/news/{news:slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('/news/{news}/edit', [NewsController::class, 'edit'])->name('news.edit');
Route::put('/news/{news}', [NewsController::class, 'update'])->name('news.update');
Route::delete('/news/{news}', [NewsController::class, 'destroy'])->name('news.destroy');


    // Profil Akun
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/news/{news:slug}', [NewsController::class, 'show'])->name('news.show');
// Admin


// Kelompokkan rute yang hanya bisa diakses oleh Admin
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Rute Khusus Admin
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Bapak bisa tambah rute admin lainnya di sini nanti, contoh:
        // Route::get('/users', [AdminUserController::class, 'index'])->name('users');
    });

});




/*

|--------------------------------------------------------------------------
| LEVEL 3: PREMIUM (Wajib Login + Status Premium)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'premium'])->group(function () {
    Route::get('/intelligence-report', function () {
        return Inertia::render('Reports/PremiumIntelligence'); 
    })->name('report.vip');
});

// Pastikan namanya 'membership.benefits' (pakai titik)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return inertia('Members/Benefits');
    })->name('membership.benefits');
});

// Kerjasama teknologi
Route::get('/green-technology-hub', function () {
    return inertia('Members/GreenTech');
})->name('green.tech.hub');


// Letakkan di bagian atas atau bawah, yang penting tidak terkurung middleware auth jika ingin bebas
// Ubah .name('lang.switch') menjadi .name('language.switch')
Route::post('language-switch', function (Request $request) {
    // 1. Simpan ke session
    session(['locale' => $request->locale]);
    
    // 2. PAKSA SIMPAN (Ini kuncinya agar tidak balik lagi ke 'id')
    session()->save(); 
    
    // 3. Set locale aplikasi
    App::setLocale($request->locale);
    
    return back();
})->name('language.switch');


require __DIR__.'/auth.php';