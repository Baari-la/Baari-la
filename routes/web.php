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
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PortTrackerController;
use Inertia\Inertia;
use App\Models\Company;
use App\Http\Controllers\RfqController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\PurchaseOrderController;

/*

|--------------------------------------------------------------------------
| LEVEL 1: PUBLIK (Tanpa Login)
|--------------------------------------------------------------------------
*/
// Hanya test



Route::get('/test-company', function () {

   $company = Company::with([
         'products',
         'markets',
         'certifications',
         'contacts',
         'links',
         'images',
          'capacities'
     ])->find(1);

     return response()->json($company);

 });

// Batas test

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/login', fn() => Inertia::render('Auth/Login'))->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Tabel Direktory
Route::post(
    '/companies/{company}/machines',
    [CompanyController::class, 'updateMachines']
)->name('companies.machines.update');
Route::post(
    '/companies/{company}/moqs',
    [CompanyController::class, 'updateMoqs']
)->name('companies.moqs.update');
Route::post(
    '/companies/{company}/products',
    [CompanyController::class, 'updateProducts']
)->name('companies.products.update');

Route::post(
    '/companies/{company}/markets',
    [CompanyController::class, 'updateMarkets']
)->name('companies.markets.update');

Route::post(
    '/companies/{company}/certifications',
    [CompanyController::class, 'updateCertifications']
)->name('companies.certifications.update');

Route::post(
    '/companies/{company}/capacities',
    [CompanyController::class, 'updateCapacities']
)->name('companies.capacities.update');

Route::post(
    '/companies/{company}/contacts',
    [CompanyController::class, 'updateContacts']
)->name('companies.contacts.update');

Route::post(
    '/companies/{company}/links',
    [CompanyController::class, 'updateLinks']
)->name('companies.links.update');

Route::post(
    '/companies/{company}/images',
    [CompanyController::class, 'updateImages']
)->name('companies.images.update');

Route::post(
    '/companies/{company}/lead-times',
    [CompanyController::class, 'updateLeadTimes']
)->name('companies.leadtimes.update');

// Hapus
Route::delete(
    '/companies/{company}/machines/{machine}',
    [CompanyController::class, 'destroyMachine']
)->name('companies.machines.destroy');

Route::delete(
    '/companies/{company}/products/{product}',
    [CompanyController::class, 'destroyProduct']
)->name('companies.products.destroy');
// Market
Route::delete(
    '/companies/{company}/markets/{market}',
    [CompanyController::class, 'destroyMarket']
)->name('companies.markets.destroy');
// Certification
Route::delete(
    '/companies/{company}/certifications/{certification}',
    [CompanyController::class, 'destroyCertification']
)->name('companies.certifications.destroy');
// Contact
Route::delete(
    '/companies/{company}/contacts/{contact}',
    [CompanyController::class, 'destroyContact']
)->name('companies.contacts.destroy');
// Link
Route::delete(
    '/companies/{company}/links/{link}',
    [CompanyController::class, 'destroyLink']
)->name('companies.links.destroy');

// Images
Route::delete(
    '/companies/{company}/images/{image}',
    [CompanyController::class, 'destroyImage']
)->name('companies.images.destroy');

// Capacities
Route::delete(
    '/companies/{company}/capacities/{capacity}',
    [CompanyController::class, 'destroyCapacity']
)->name('companies.capacities.destroy');

// MOQ
Route::delete(
    '/companies/{company}/moqs/{moq}',
    [CompanyController::class, 'destroyMoq']
)->name('companies.moqs.destroy');
// LeadTimes
Route::delete(
    '/companies/{company}/lead-times/{leadTime}',
    [CompanyController::class, 'destroyLeadTime']
)->name('companies.lead-times.destroy');



/// 🚢 PERLUASAN PIPA DATA: Menambahkan endpoint penarik status EWS Domestik untuk React Frontend
Route::prefix('api/v2')->group(function () {
    Route::post('/port-tracker/stream-input', [PortTrackerController::class, 'storeFeedData'])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
    
    // Endpoint Baru penarik data agregat EWS riil
    Route::get('/ews/domestic-status', [PortTrackerController::class, 'getLiveEwsStatus']);
});

// LEVEL 1: PUBLIK
Route::get('/regulation', fn() => inertia('Regulation/Index'))->name('regulation.index');
Route::get('/matchmaking', fn() => inertia('Matchmaking/Index'))->name('matchmaking.index');
Route::post('/companies/register-umum', [CompanyController::class, 'publicRegister'])->name('companies.public_register');
  Route::get('/matchmaking/create', [TradeIntelligenceController::class, 'createMatchmaking'])->name('matchmaking.create');

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

// Untuk download materi
// Taruh di luar middleware auth agar bisa diakses oleh pengunjung umum yang lolos sensor promosi
Route::get('/download-file', function (Illuminate\Http\Request $request) {
    $path = $request->query('path');
    
    // Validasi pencegahan hacker mengintip file sistem (.env)
    if (!$path || str_contains($path, '..')) {
        abort(403, 'Akses ilegal.');
    }

    // Cek file fisik di storage/app/public/
    if (!Storage::disk('public')->exists($path)) {
        abort(404, 'File presentasi/regulasi tidak ditemukan di server.');
    }

    // Ambil file dan paksa browser membuka/mengunduh (Bypass 403 Apache 100%)
    return Storage::disk('public')->response($path);
})->name('document.download');

/*

|--------------------------------------------------------------------------
| LEVEL 2: MEMBER (Login Google / Free)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Akses Dashboard Dasar
    Route::get('/dashboard', [TradeDashboardController::class, 'index'])->name('dashboard');
    
    // Matchmaking
Route::get('/matchmaking/create', [TradeIntelligenceController::class, 'createMatchmaking'])->name('matchmaking.create');
    Route::post('/matchmaking', [TradeIntelligenceController::class, 'storeMatchmaking'])->name('matchmaking.store');
    // Rute Edit untuk MODAL 1: Toko Digital Bahan
    Route::get('/admin/inventory/{id}/edit', [TradeIntelligenceController::class, 'editInventory'])->name('inventory.edit');
    
    // Rute Edit untuk MODAL 2: Pusat Data & Regulasi
    Route::get('/admin/regulations/{id}/edit', [TradeIntelligenceController::class, 'editRegulation'])->name('regulation.edit');
    
    // Rute Edit untuk MODAL 3: Matchmaking Kemitraan
    // Di dalam routes/web.php (grup middleware auth)
Route::get('/admin/partnerships/{id}/edit', [TradeIntelligenceController::class, 'editMatchmaking'])->name('partnership.edit');
 Route::put('/admin/inventory/{id}', [TradeIntelligenceController::class, 'updateInventory'])->name('inventory.update');
    Route::put('/admin/regulations/{id}', [TradeIntelligenceController::class, 'updateRegulation'])->name('regulation.update');
    Route::put('/admin/partnerships/{id}', [TradeIntelligenceController::class, 'updateMatchmaking'])->name('partnership.update');


    // Profil User
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    // Direktori Perusahaan & Join Us
     Route::get('/company/create', [CompanyController::class, 'create'])->name('companies.create');
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
// Logistic

Route::get('/admin/logistics', function () {
        return inertia('Logistics/Index', [
            'locale' => app()->getLocale()
        ]);
    })->name('logistics.index');

    // Rute halaman tracking live read-only JICT & NPCT1
    Route::get('/admin/logistics/tracking', function () {
        return inertia('Logistics/Tracking', [
            'locale' => app()->getLocale()
        ]);
    })->name('logistics.tracking');

// QUOTATION TABEL DIREKTORY
Route::get(
        '/rfqs',
        [RfqController::class, 'index']
    )->name('rfqs.index');

    Route::get(
        '/rfqs/create',
        [RfqController::class, 'create']
    )->name('rfqs.create');

    Route::post(
        '/rfqs',
        [RfqController::class, 'store']
    )->name('rfqs.store');

    Route::get(
        '/rfqs/{rfq}',
        [RfqController::class, 'show']
    )->name('rfqs.show');

    Route::delete(
        '/rfqs/{rfq}',
        [RfqController::class, 'destroy']
    )->name('rfqs.destroy');
Route::post(
    '/rfqs/{rfq}/quotations',
    [QuotationController::class, 'store']
)->name('quotations.store');

Route::get(
    '/quotations/{quotation}',
    [QuotationController::class, 'show']
)->name('quotations.show');

Route::delete(
    '/quotations/{quotation}',
    [QuotationController::class, 'destroy']
)->name('quotations.destroy');

Route::post(
    '/quotations/{quotation}/accept',
    [QuotationController::class, 'accept']
)->name('quotations.accept');

Route::post(
    '/quotations/{quotation}/reject',
    [QuotationController::class, 'reject']
)->name('quotations.reject');

Route::post(
    '/quotations/{quotation}/award',
    [QuotationController::class, 'award']
)->name('quotations.award');

Route::post(
    '/rfqs/{rfq}/close',
    [RfqController::class, 'close']
)->name('rfqs.close');
Route::get(
    '/my-quotations',
    [QuotationController::class, 'myQuotations']
)->name('quotations.my');

Route::get(
    '/my-quotations',
    [QuotationController::class, 'index']
)->name('quotations.index');
Route::get(
        '/purchase-orders',
        [PurchaseOrderController::class, 'index']
    )->name('purchase-orders.index');

    Route::get(
        '/purchase-orders/{purchaseOrder}',
        [PurchaseOrderController::class, 'show']
    )->name('purchase-orders.show');
    Route::post(
    '/purchase-orders/{purchaseOrder}/confirm',
    [PurchaseOrderController::class, 'confirm']
)->name('purchase-orders.confirm');

Route::post(
    '/purchase-orders/{purchaseOrder}/production',
    [PurchaseOrderController::class, 'production']
)->name('purchase-orders.production');
Route::post('/purchase-orders/{purchaseOrder}/start-production', [PurchaseOrderController::class, 'startProduction'])
        ->name('purchase-orders.start-production');

Route::post(
    '/purchase-orders/{purchaseOrder}/shipped',
    [PurchaseOrderController::class, 'shipped']
)->name('purchase-orders.shipped');

Route::post(
    '/purchase-orders/{purchaseOrder}/completed',
    [PurchaseOrderController::class, 'completed']
)->name('purchase-orders.completed');

 });

/*

|--------------------------------------------------------------------------
| LEVEL 4: ADMIN ONLY
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/pending-updates', [AdminDashboardController::class, 'pendingUpdates'])->name('pending-updates');
    Route::post('/approve-update/{id}', [AdminDashboardController::class, 'approveUpdate'])->name('approve-update');
    Route::post('/reject-update/{id}', [AdminDashboardController::class, 'rejectUpdate'])->name('reject-update');
    Route::resource('gallery', GalleryController::class);
    Route::post('/companies/{company}/verify', [CompanyController::class, 'verify'])->name('companies.verify');
    
});