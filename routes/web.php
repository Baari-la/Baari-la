<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
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

/*

|--------------------------------------------------------------------------
| LEVEL 1: PUBLIK (Tanpa Login)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/login', fn() => Inertia::render('Auth/Login'))->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
// LEVEL 1: PUBLIK
Route::get('/regulation', fn() => inertia('Regulation/Index'))->name('regulation.index');
Route::get('/matchmaking', fn() => inertia('Matchmaking/Index'))->name('matchmaking.index');

Route::get('/news/{news:slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('/partnership', fn() => Inertia::render('Partnership/Index'))->name('partnership');
Route::get('/pricing', fn() => Inertia::render('Auth/PricingPage'))->name('pricing.index');
Route::get('/v/{nomor_anggota}', [CompanyController::class, 'publicVerify'])->name('companies.public_verify');

// Google Auth
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Language Switch
Route::post('/language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session()->put('locale', $locale);
    }
    return back();
})->name('language.switch');

/*

|--------------------------------------------------------------------------
| LEVEL 2: MEMBER (Login Google / Free)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Akses Dashboard Dasar
    Route::get('/dashboard', [TradeDashboardController::class, 'index'])->name('dashboard');
    
    // Profil User
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    // Direktori Perusahaan & Join Us
    Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
    Route::get('/companies/{company}', [CompanyController::class, 'show'])->name('companies.show');
    Route::inertia('/join-us', 'Company/JoinUs')->name('join.us');
    Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
    Route::post('/premium-request', [CompanyController::class, 'requestPremium'])->name('premium.request');
});

/*

|--------------------------------------------------------------------------
| LEVEL 3: ANGGOTA API & PREMIUM (High-Level Data)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    
    // Fitur Update Data Perusahaan (Tombol Update di Dashboard)
    Route::get('/my-company/edit', [CompanyController::class, 'edit'])->name('companies.edit_self');
    Route::get('/companies/{company}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
    Route::post('/companies/{company}', [CompanyController::class, 'update'])->name('companies.update');

    // Intelligence & Radar
    Route::get('/intelligence-center', [AnalyticsController::class, 'deepAnalysis'])->name('intelligence.center');
    Route::get('/trade-radar', [TradeIntelligenceController::class, 'index'])->name('trade.radar');
    Route::get('/inventory/create', [TradeIntelligenceController::class, 'create'])->name('inventory.create');
    Route::post('/inventory', [TradeIntelligenceController::class, 'storeInventory'])->name('inventory.store');
    // Inventory & Tools
    Route::get('/inventory', [TradeIntelligenceController::class, 'indexInventory'])->name('inventory.index');
    Route::get('/industrial-tools/calculator', fn() => Inertia::render('Tools/GarmentCalculatorPage'))->name('tools.calculator');
    
    // QR & Sertifikat
    Route::get('/my-company/certificate', [CompanyController::class, 'downloadMyCertificate'])->name('companies.my_certificate');
});

/*

|--------------------------------------------------------------------------
| LEVEL 4: ADMIN ONLY
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('gallery', GalleryController::class);
    Route::post('/companies/{company}/verify', [CompanyController::class, 'verify'])->name('companies.verify');
});