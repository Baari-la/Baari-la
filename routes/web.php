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
use App\Http\Controllers\PurchaseOrderDocumentController;
use App\Http\Controllers\CollectiveSourcingController;
use App\Http\Controllers\PurchaseOrderPaymentController;
use App\Http\Controllers\PurchaseOrderShipmentController;
use App\Http\Controllers\PurchaseOrderShipmentTrackController;
use App\Http\Controllers\PurchaseOrderDisputeController;
use App\Http\Controllers\SupplierReviewController;
use App\Http\Controllers\CompanyClaimController;
use App\Http\Controllers\IndustrySolutionController;
use App\Http\Controllers\EcosystemPartnerController;
use App\Http\Controllers\CompanyLocationController;
use App\Http\Controllers\PartnerInsightController;
use App\Http\Controllers\Admin\ImportKemendagController;
use App\Http\Controllers\CompanyPassportController;


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

Route::delete(
    '/company-locations/{location}',
    [CompanyController::class, 'destroyLocation']
)->name('company-locations.destroy');



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
    Route::post('/companies/{company}/locations',[CompanyLocationController::class, 'update'])->name('companies.locations.update');

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
    [PurchaseOrderController::class, 'startProduction']
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
Route::post(
    '/purchase-orders/{purchaseOrder}/documents',
    [PurchaseOrderDocumentController::class, 'store']
)->name(
    'purchase-orders.documents.store'
);
// IKM Sourcing
 Route::get(
            '/collective-sourcing',
            [CollectiveSourcingController::class, 'index']
        )->name('collective-sourcing.index');

        Route::get(
            '/collective-sourcing/create',
            [CollectiveSourcingController::class, 'create']
        )->name('collective-sourcing.create');

        Route::post(
            '/collective-sourcing',
            [CollectiveSourcingController::class, 'store']
        )->name('collective-sourcing.store');

        Route::get(
            '/my-requests',
            [CollectiveSourcingController::class, 'myRequests']
        )->name('collective-sourcing.my-requests');

        Route::get(
    '/my-groups',
    [CollectiveSourcingController::class, 'myGroups']
)->name('collective-sourcing.my-groups');

Route::post(
    '/collective-sourcing/groups/{group}/generate-rfq',
    [CollectiveSourcingController::class, 'generateRfq']
)->name(
    'collective-sourcing.groups.generate-rfq'
);
Route::get(
    '/collective-sourcing/groups/{group}',
    [CollectiveSourcingController::class, 'showGroup']
)->name('collective-sourcing.show-group');

Route::post(
    '/quotations/{quotation}/generate-po',
    [QuotationController::class, 'generatePurchaseOrder']
)->name('quotations.generate-po');

 
Route::post(
   '/purchase-orders/{purchaseOrder}/documents',
   [PurchaseOrderDocumentController::class, 'store']
)->name('purchase-orders.documents.store');

Route::post(
   '/purchase-orders/{purchaseOrder}/payments',
   [PurchaseOrderPaymentController::class, 'store']
)->name(
   'purchase-orders.payments.store'
);
Route::post(
   '/purchase-orders/{purchaseOrder}/shipment',
   [PurchaseOrderShipmentController::class, 'store']
)->name(
   'purchase-orders.shipment.store'
);
Route::post(
   '/purchase-orders/{purchaseOrder}/shipment-tracks',
   [PurchaseOrderShipmentTrackController::class, 'store']
)->name('purchase-orders.shipment-tracks.store');
Route::post(
   '/purchase-orders/{purchaseOrder}/confirm-received',
   [PurchaseOrderController::class, 'confirmReceived']
)->name('purchase-orders.confirm-received');

Route::post(
    '/purchase-orders/{purchaseOrder}/disputes',
    [PurchaseOrderDisputeController::class, 'store']
)->name('purchase-orders.disputes.store');
Route::post(
    '/purchase-order-disputes/{dispute}/respond',
    [PurchaseOrderDisputeController::class, 'respond']
)->name(
    'purchase-order-disputes.respond'
);
Route::post(
    '/purchase-order-disputes/{dispute}/resolve',
    [PurchaseOrderDisputeController::class, 'resolve']
)->name('purchase-order-disputes.resolve');
Route::post(
    '/purchase-order-disputes/{dispute}/close',
    [PurchaseOrderDisputeController::class, 'close']
)->name(
    'purchase-order-disputes.close'
);
Route::post(
    '/purchase-orders/{purchaseOrder}/review',
    [SupplierReviewController::class, 'store']
)->name('purchase-orders.review.store');

Route::post(
            '/companies/{company}/claim',
            [CompanyClaimController::class, 'store']
        )
        ->name(
            'companies.claim'
        );

// Digital Passport
Route::get(
    '/companies/{company}/passport',
    [CompanyPassportController::class, 'show']
)->name('companies.passport');

Route::get(
    '/companies/{company}/passport/data',
    [CompanyPassportController::class, 'data']
)->name('companies.passport.data');
 });



Route::get('/industry-solutions',[IndustrySolutionController::class, 'index'])->name('industry-solutions.index');
Route::get('/industry-solutions/{category}',[IndustrySolutionController::class, 'show']
)->name('industry-solutions.show');
Route::get('/ecosystem-partner',[EcosystemPartnerController::class, 'index']
)->name('ecosystem-partner.index');

// Partners
Route::get(
    '/partner-insights',
    [PartnerInsightController::class, 'index']
)->name('partner-insights.index');

Route::get(
    '/partner-insights/{partner}',
    [PartnerInsightController::class, 'show']
)->name('partner-insights.show');

// Menu
Route::get('/sourcing-hub', function () {
        return Inertia::render('SourcingHub/Index');
    })->name('sourcing-hub');

Route::get('/market-intelligence', function () {
        return Inertia::render('MarketIntelligence/Index');
    })->name(    'market-intelligence');

    Route::prefix('intelligence')->name('intelligence.')->group(function () {
    Route::get('/news', [IntelligenceController::class, 'news'])->name('news');
    Route::get('/market', [IntelligenceController::class, 'market'])->name('market');
    Route::get('/trade', [IntelligenceController::class, 'trade'])->name('trade');
    Route::get('/policy', [IntelligenceController::class, 'policy'])->name('policy');
    Route::get('/country', [IntelligenceController::class, 'country'])->name('country');
});


/*

|--------------------------------------------------------------------------
| LEVEL 4: ADMIN ONLY
|--------------------------------------------------------------------------
*/
Route::middleware([
    'auth',
    'verified',
    'admin'
])
->prefix('admin')
->name('admin.')
->group(function () {

    Route::get('dashboard', [AdminDashboardController::class, 'index'])
    ->name('dashboard');

    Route::get(
        '/pending-updates',
        [AdminDashboardController::class, 'pendingUpdates']
    )->name('pending-updates');

    Route::post(
        '/approve-update/{id}',
        [AdminDashboardController::class, 'approveUpdate']
    )->name('approve-update');

    Route::post(
        '/reject-update/{id}',
        [AdminDashboardController::class, 'rejectUpdate']
    )->name('reject-update');

    Route::post(
        '/company-claims/{claim}/approve',
        [AdminDashboardController::class, 'approveClaim']
    )->name('company-claims.approve');

    Route::post(
        '/company-claims/{claim}/reject',
        [AdminDashboardController::class, 'rejectClaim']
    )->name('company-claims.reject');

    Route::resource(
        'gallery',
        GalleryController::class
    );

    Route::post(
        '/companies/{company}/verify',
        [CompanyController::class, 'verify']
    )->name('companies.verify');

// Impor Data
Route::get(
    '/import-kemendag',
    [ImportKemendagController::class, 'index']
)->name('import-kemendag');

Route::post(
    '/import-kemendag',
    [ImportKemendagController::class, 'store']
)->name('import-kemendag.store');


// News
Route::get('/news', [NewsController::class, 'index'])
    ->name('news.index');
Route::post(
    '/news/translate',
    [NewsController::class, 'translate']
)->name('news.translate');
Route::post(
    '/admin/news/suggest-meta',
    [NewsController::class, 'suggestMeta']
)->name('admin.news.suggest-meta');

Route::get('/news/create', [NewsController::class, 'create'])
    ->name('news.create');

Route::post('/news', [NewsController::class, 'store'])
    ->name('news.store');

Route::get('/news/{news:slug}/edit', [NewsController::class, 'edit'])
    ->name('news.edit');

Route::put('/news/{news:slug}', [NewsController::class, 'update'])
    ->name('news.update');

Route::delete('/news/{news:slug}', [NewsController::class, 'destroy'])
    ->name('news.destroy');

Route::get('/admin/import-kemendag', [AdminDashboardController::class, 'showImportForm'])->name('admin.import.show');

    // 2. Route untuk memproses upload excel (Tipe POST)
    Route::post('/admin/import-kemendag', [AdminDashboardController::class, 'importDataKemendag'])->name('admin.import.kemendag');

});