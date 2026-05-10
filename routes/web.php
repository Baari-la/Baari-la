<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\TradeIntelligenceController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TradeDashboardController;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\MarketIntelligenceController;

/*

|--------------------------------------------------------------------------
| LEVEL 1: PUBLIK (Akses Tanpa Login)
|--------------------------------------------------------------------------
*/
Route::post('/language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session()->put('locale', $locale);
        App::setLocale($locale);
    }
    return back();
})->name('language.switch');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');

// Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', function () {
    return Inertia::render('Auth/Login'); // Sesuaikan dengan lokasi file Login.jsx Anda
})->name('login');
// Route::get('/', [MarketIntelligenceController::class, 'getHomeData'])->name('home');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::inertia('/join-us', 'Company/JoinUs')->name('join.us');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/partnership', fn() => Inertia::render('Partnership/Index'))->name('partnership');
Route::get('/pricing', fn() => Inertia::render('Auth/PricingPage'))->name('pricing.index');
Route::get('/regulation', fn() => inertia('Regulation/Index'))->name('regulation.index');
Route::get('/matchmaking', fn() => inertia('Matchmaking/Index'))->name('matchmaking.index');

// News - Akses Umum (Filter Premium dilakukan di dalam Controller)
Route::get('/news/{news:slug}', [NewsController::class, 'show'])->name('news.show');

// Google Auth
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Verifikasi Publik (QR Scan)
Route::get('/v/{nomor_anggota}', [CompanyController::class, 'publicVerify'])->name('companies.public_verify');

/*

|--------------------------------------------------------------------------
| LEVEL 2: MEMBER (Wajib Login - Free, API, Premium)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard Utama User
    // Route::get('/dashboard', [MarketIntelligenceController::class, 'getDashboardData'])->name('dashboard');
    Route::get('/dashboard', [TradeDashboardController::class, 'index'])->name('dashboard');
    // Jika ada dashboard admin khusus
    Route::get('/admin/dashboard', [HomeController::class, 'adminDashboard'])->name('admin.dashboard');
    // Profil Akun
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    // Perusahaan & Direktori
    Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
    Route::get('/companies/{company}', [CompanyController::class, 'show'])->name('companies.show');
    Route::get('/members', function (Request $request) {
        return Inertia::render('Members/Index', [
            'members' => Member::all(),
            'initialSearch' => $request->query('search')
        ]);
    })->name('members.list');

    // Trade & Inventory Dasar
    Route::get('/trade-dashboard', [TradeDashboardController::class, 'index'])->name('trade.dashboard');
    Route::get('/inventory', [TradeIntelligenceController::class, 'indexInventory'])->name('inventory.index');
    Route::post('/premium-request', [CompanyController::class, 'requestPremium'])->name('premium.request');
});

/*

|--------------------------------------------------------------------------
| LEVEL 3: KHUSUS ANGGOTA API & PREMIUM (High-Level Access)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Intelligence & Radar
     Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/intelligence-center', [AnalyticsController::class, 'deepAnalysis'])->name('intelligence.center');
    Route::get('/trade-radar', [TradeIntelligenceController::class, 'index'])->name('trade.radar');
    
    // Inventory Management
    Route::get('/inventory/create', [TradeIntelligenceController::class, 'create'])->name('inventory.create');
    Route::post('/inventory', [TradeIntelligenceController::class, 'storeInventory'])->name('inventory.store');

    // Tools & Sertifikat
    Route::get('/industrial-tools/calculator', fn() => Inertia::render('Tools/GarmentCalculatorPage'))->name('tools.calculator');
    Route::get('/my-company/certificate', [CompanyController::class, 'downloadMyCertificate'])->name('companies.my_certificate');
    Route::get('/companies/{company}/download-qr', [CompanyController::class, 'downloadQrCode'])->name('companies.download_qr');
});

/*

|--------------------------------------------------------------------------
| LEVEL 4: ADMIN ONLY
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/approve-update/{id}', [AdminDashboardController::class, 'approveUpdate'])->name('approve-update');
    
    // Gallery Management
    Route::resource('gallery', GalleryController::class)->names('gallery');

    // News Management (Internal Admin)
    Route::controller(NewsController::class)->group(function () {
        Route::get('/news/create', 'create')->name('news.create');
        Route::post('/news', 'store')->name('news.store');
        Route::get('/news/{news}/edit', 'edit')->name('news.edit');
        Route::put('/news/{news}', 'update')->name('news.update');
        Route::delete('/news/{news}', 'destroy')->name('news.destroy');
        Route::post('/news/translate', 'translate')->name('news.translate');
    });
});