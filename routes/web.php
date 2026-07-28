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
use App\Http\Controllers\IntelligenceController;
use App\Http\Controllers\ExecutiveController;
use App\Http\Controllers\BuildMySupplyChainController;
use App\Http\Controllers\DigitalDirectoryProgramController;
use App\Http\Controllers\Admin\DigitalDirectoryParticipantController;
use App\Http\Controllers\Admin\AdminCompanyController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\DirectoryVisibilityProgramController;
use App\Http\Controllers\ProgramPortalController;




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

Route::get('/register', function () {
    return Inertia::render('Auth/Register');
})->name('register');

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

// Kapas


Route::get(
    '/future-of-digestex',
    function () {
        return Inertia::render(
            'FutureOfDigestex'
        );
    }
)->name('future-of-digestex');

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


// Claim compnaies
/*
|--------------------------------------------------------------------------
| Ownership Verification
|--------------------------------------------------------------------------
*/

// Manual company
Route::get(
    '/onboarding/ownership-verification',
    [CompanyClaimController::class, 'createManual']
)->name('companies.claim.create-manual');


// Submit ownership verification
Route::post(
    '/onboarding/ownership-verification',
    [CompanyClaimController::class, 'store']
)->name('companies.claim.store');


// Success / pending page
// HARUS sebelum /{company}
Route::get(
    '/onboarding/ownership-verification/submitted',
    [CompanyClaimController::class, 'submitted']
)->name('companies.claim.submitted');


// Existing company
// Letakkan PALING BAWAH
Route::get(
    '/onboarding/ownership-verification/{company}',
    [CompanyClaimController::class, 'create']
)->name('companies.claim.create');

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

Route::get('verify-email', EmailVerificationPromptController::class)
    ->middleware('auth')
    ->name('verification.notice');

Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');



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

Route::prefix('intelligence')
    ->name('intelligence.')
    ->group(function () {

        Route::get(
            '/executive',
            [IntelligenceController::class, 'executive']
        )->name('executive.index');

        Route::get(
            '/company',
            [IntelligenceController::class, 'company']
        )->name('company.index');

        Route::get(
            '/knowledge-graph',
            [IntelligenceController::class, 'knowledgeGraph']
        )->name('knowledge-graph.index');

        Route::get(
            '/master-data',
            [IntelligenceController::class, 'masterData']
        )->name('master-data.index');

        Route::get(
            '/visualization',
            [IntelligenceController::class, 'visualization']
        )->name('visualization.index');
    });

// Ekspor impor intelligence /Executive Dashboard
Route::get(
    '/executive-dashboard',
    [ExecutiveController::class, 'index']
);

Route::prefix('executive-dashboard')
    ->name('executive.')
    ->group(function () {
        Route::get(
            '/{sector?}',
            [ExecutiveController::class, 'index']
        )
        ->name('dashboard');
    });

Route::get(
    '/build-my-supply-chain',
    [BuildMySupplyChainController::class, 'index']
);

Route::get(
    '/build-my-supply-chain/{sector}',
    [BuildMySupplyChainController::class, 'sector']
);

Route::get(
    '/build-my-supply-chain/product/{product}',
    [BuildMySupplyChainController::class, 'product']
); 


// Navbar
Route::prefix('intelligence')
    ->name('intelligence.')
    ->group(function () {

        Route::get(
            '/weekly',
            fn () => Inertia::render(
                'ComingSoon'
            )
        )->name('weekly');

        Route::get(
            '/news',
            fn () => Inertia::render(
                'ComingSoon'
            )
        )->name('news');

        Route::get(
            '/market',
            fn () => Inertia::render(
                'ComingSoon'
            )
        )->name('market');

        Route::get(
            '/trade',
            fn () => Inertia::render(
                'ComingSoon'
            )
        )->name('trade');

        Route::get(
            '/policy',
            fn () => Inertia::render(
                'ComingSoon'
            )
        )->name('policy');

        Route::get(
            '/country',
            fn () => Inertia::render(
                'ComingSoon'
            )
        )->name('country');
    });
// Coming soon
Route::get('/coming-soon/{module?}', function ($module = null) {
    return Inertia::render('ComingSoon', [
        'module' => $module,
    ]);
})->name('coming-soon');

Route::get('/cotton-intelligence', function () {
    return Inertia::render('Intelligence/Cotton/Index');
})->name('cotton-intelligence');
    
// Login program digital directory

Route::get(
    '/program/digital-directory/portal',
    [
        ProgramPortalController::class,
        'index',
    ]
)
    ->middleware('auth')
    ->name(
        'program.digital-directory.portal'
    );



// Digital Directory
Route::middleware([
    'auth',
    'verified',
])->group(function () {

    Route::get(
        '/onboarding/company-information',
        function () {
            return Inertia::render(
                'Onboarding/CompanyInformation'
            );
        }
    )->name('onboarding.company-information');

    Route::get(
    '/onboarding/company-lookup',
    [OnboardingController::class, 'companyLookup']
)->name('onboarding.company-lookup');

Route::get(
    '/claims',
    [AdminCompanyController::class, 'claims']
)->name('claims');

Route::post(
    '/claims/{claim}/approve',
    [AdminDashboardController::class, 'approveClaim']
)->name('claims.approve');

Route::post(
    '/claims/{claim}/reject',
    [AdminDashboardController::class, 'rejectClaim']
)->name('claims.reject');

Route::post(
    '/onboarding/company-information',
    [OnboardingController::class,
    'storeCompanyInformation']
)->name(
    'onboarding.company-information.store'
);

Route::post(
    '/onboarding/business-information',
    [OnboardingController::class,
    'storeBusinessInformation']
)->name(
    'onboarding.business-information.store'
);

Route::post(
    '/onboarding/capabilities',
    [OnboardingController::class,
    'storeCapabilities']
)->name(
    'onboarding.capabilities.store'
);

Route::post(
    '/onboarding/manufacturing',
    [OnboardingController::class,
    'storeManufacturing']
)->name(
    'onboarding.manufacturing.store'
);

Route::post(
    '/onboarding/media-catalog',
    [OnboardingController::class,
    'storeMediaCatalog']
)->name(
    'onboarding.media-catalog.store'
);

Route::post(
    '/onboarding/review-submit',
    [OnboardingController::class,
    'submitReview']
)->name(
    'onboarding.review-submit.store'
);
     
 Route::get(
        '/onboarding/business-information',
        [OnboardingController::class, 'businessInformation']
    )->name('onboarding.business-information');

    Route::get(
        '/onboarding/capabilities',
        [OnboardingController::class, 'capabilities']
    )->name('onboarding.capabilities');

    Route::get(
        '/onboarding/manufacturing',
        [OnboardingController::class, 'manufacturing']
    )->name('onboarding.manufacturing');

    Route::get(
        '/onboarding/media-catalog',
        [OnboardingController::class, 'mediaCatalog']
    )->name('onboarding.media-catalog');

    Route::get(
        '/onboarding/review-submit',
        [OnboardingController::class, 'reviewSubmit']
    )->name('onboarding.review-submit');
});

// Direktory digital

Route::middleware(['auth', 'verified'])
    ->get('/welcome', function () {
        return Inertia::render(
            'Programs/DigitalDirectory/Step1Welcome'
        );
    })
    ->name('welcome');
    
/*
|--------------------------------------------------------------------------
| Landing Page
|--------------------------------------------------------------------------
*/

Route::get(
    '/program/digital-directory',
    [
        DirectoryVisibilityProgramController::class,
        'index',
    ]
)->name('program.digital-directory');

/*
|--------------------------------------------------------------------------
| Registration Wizard
|--------------------------------------------------------------------------
*/

Route::prefix('program/digital-directory/register')->group(function () {

    Route::get(
        '/',
        [
            DigitalDirectoryProgramController::class,
            'step1',
        ]
    )->name('program.digital-directory.register');

    Route::get(
        '/package',
        [
            DigitalDirectoryProgramController::class,
            'step2',
        ]
    )->name('program.digital-directory.package');

    Route::get(
        '/company-information',
        [
            DigitalDirectoryProgramController::class,
            'step3',
        ]
    )->name('program.digital-directory.company-information');

    Route::post(
        '/company-information',
        [
            DigitalDirectoryProgramController::class,
            'storeCompanyInformation',
        ]
    )->name('program.digital-directory.company-information.store');

    Route::get(
        '/review',
        [
            DigitalDirectoryProgramController::class,
            'step4',
        ]
    )->name('program.digital-directory.review');

    Route::get(
        '/payment',
        [
            DigitalDirectoryProgramController::class,
            'step5',
        ]
    )->name('program.digital-directory.payment');

    Route::post(
        '/payment/confirm',
        [
            DigitalDirectoryProgramController::class,
            'confirmPayment',
        ]
    )->name('program.digital-directory.payment.confirm');

    Route::get(
        '/welcome',
        [
            DigitalDirectoryProgramController::class,
            'step6',
        ]
    )->name('program.digital-directory.welcome');

});


// Route::post(
//     '/payment/confirm',
//     [
//         DigitalDirectoryProgramController::class,
//         'confirmPayment',
//     ]
// )->name(
//     'program.digital-directory.payment.confirm'
// );
// Route::get(
//     '/program/digital-directory/review',
//     [
//         DigitalDirectoryProgramController::class,
//         'review',
//     ]
// )->name(
//     'program.digital-directory.review'
// );
/*
/*
|--------------------------------------------------------------------------
| LEVEL 4: ADMIN ONLY
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // --- Dashboard & Pending Actions ---
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/pending-updates', [AdminDashboardController::class, 'pendingUpdates'])->name('pending-updates');
        Route::post('/approve-update/{id}', [AdminDashboardController::class, 'approveUpdate'])->name('approve-update');
        Route::post('/reject-update/{id}', [AdminDashboardController::class, 'rejectUpdate'])->name('reject-update');

        Route::post('/company-claims/{claim}/approve', [AdminDashboardController::class, 'approveClaim'])->name('company-claims.approve');
        Route::post('/company-claims/{claim}/reject', [AdminDashboardController::class, 'rejectClaim'])->name('company-claims.reject');

        // --- Gallery ---
        Route::resource('gallery', GalleryController::class);

        // --- Impor Data Kemendag ---
        Route::get('/import-kemendag', [ImportKemendagController::class, 'index'])->name('import-kemendag');
        Route::post('/import-kemendag', [ImportKemendagController::class, 'store'])->name('import-kemendag.store');

        // --- News Management ---
        Route::get('/news', [NewsController::class, 'index'])->name('news.index');
        Route::get('/news/create', [NewsController::class, 'create'])->name('news.create');
        Route::post('/news', [NewsController::class, 'store'])->name('news.store');
        Route::post('/news/translate', [NewsController::class, 'translate'])->name('news.translate');
        Route::post('/news/suggest-meta', [NewsController::class, 'suggestMeta'])->name('news.suggest-meta');
        
        // Dynamic / Wildcard News Routes (Wajib ditaruh di bawah)
        Route::get('/news/{news:slug}/edit', [NewsController::class, 'edit'])->name('news.edit');
        Route::put('/news/{news:slug}', [NewsController::class, 'update'])->name('news.update');
        Route::delete('/news/{news:slug}', [NewsController::class, 'destroy'])->name('news.destroy');

        // --- Build My Supply Chain & General Views ---
        Route::get('/build-my-supply-chain', function () {
            return Inertia::render('BuildMySupplyChain/Index');
        })->name('build-my-supply-chain.index');

        // --- Companies ---
        Route::prefix('companies')->name('companies.')->group(function () {
            Route::get('/', [AdminCompanyController::class, 'index'])->name('index');
            Route::get('/pending', [AdminCompanyController::class, 'pending'])->name('pending');
            Route::get('/updates', [AdminCompanyController::class, 'updates'])->name('updates');
            Route::post('/updates/{id}/approve', [AdminDashboardController::class, 'approveUpdate'])->name('updates.approve');
            Route::post('/updates/{update}/reject', [AdminDashboardController::class, 'rejectUpdate'])->name('updates.reject');
            
            Route::get('/claims', [AdminCompanyController::class, 'claims'])->name('claims');
            Route::post('/claims/{claim}/approve', [AdminDashboardController::class, 'approveClaim'])->name('claims.approve');
            Route::post('/claims/{claim}/reject', [AdminDashboardController::class, 'rejectClaim'])->name('claims.reject');
            Route::post('/{company}/verify', [CompanyController::class, 'verify'])->name('verify');
            
            // Route wildcard '{company}' diletakkan paling bawah dalam grup ini
            Route::get('/{company}', [AdminCompanyController::class, 'show'])->name('show');
        });

        // --- Digital Directory ---
        Route::prefix('digital-directory')->name('digital-directory.')->group(function () {
            Route::get('/', [DigitalDirectoryParticipantController::class, 'index'])->name('index');
            Route::get('/pending-payments', [DigitalDirectoryParticipantController::class, 'pendingPayments'])->name('pending-payments');
            Route::get('/verified', [DigitalDirectoryParticipantController::class, 'verified'])->name('verified');
            Route::get('/revenue', [DigitalDirectoryParticipantController::class, 'revenue'])->name('revenue');
            Route::get('/package-analytics', [DigitalDirectoryParticipantController::class, 'packageAnalytics'])->name('package-analytics');
/*
|--------------------------------------------------------------------------
| Ownership Verification
|--------------------------------------------------------------------------
*/

Route::get(
    '/ownership-verification',
    [
        DigitalDirectoryParticipantController::class,
        'ownershipVerification',
    ]
)->name('ownership-verification');


            Route::post('/{participant}/verify', [DigitalDirectoryParticipantController::class, 'verify'])->name('verify');
            Route::post('/{participant}/reject', [DigitalDirectoryParticipantController::class, 'reject'])->name('reject');
            Route::post('/{participant}/activate', [DigitalDirectoryParticipantController::class, 'activate'])->name('activate');
            Route::post('/{participant}/deactivate', [DigitalDirectoryParticipantController::class, 'deactivate'])->name('deactivate');

            // Must always be the last route in this group
            Route::get('/{participant}', [DigitalDirectoryParticipantController::class, 'show'])->name('show');
        });

        /*
|--------------------------------------------------------------------------
| PAYMENTS
|--------------------------------------------------------------------------
*/

Route::prefix('payments')
    ->name('payments.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Payment Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/',
            [PaymentController::class,'index',])->name('index');

        /*
        |--------------------------------------------------------------------------
        | Transactions
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/transactions',
            [
                PaymentController::class,
                'transactions',
            ]
        )->name('transactions');

        /*
        |--------------------------------------------------------------------------
        | QRIS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/qris',
            [
                PaymentController::class,
                'qris',
            ]
        )->name('qris');

        /*
        |--------------------------------------------------------------------------
        | Manual Transfer
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/manual-transfer',
            [
                PaymentController::class,
                'manualTransfer',
            ]
        )->name('manual-transfer');
        
        Route::get(
            '/manual-transfer/{participant}/receipt',
            [
                PaymentController::class,
                'viewManualTransferReceipt',
            ]
        )->name('manual-transfer.receipt');

        /*
        |--------------------------------------------------------------------------
        | Revenue Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/revenue',
            [
                PaymentController::class,
                'revenue',
            ]
        )->name('revenue');

        /*
        |--------------------------------------------------------------------------
        | Invoice Management
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/invoice-management',
            [
                PaymentController::class,
                'invoiceManagement',
            ]
        )->name('invoice-management');

        /*
        |--------------------------------------------------------------------------
        | Invoice Actions
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/invoice/{participant}/mark-paid',
            [
                PaymentController::class,
                'markPaid',
            ]
        )->name('mark-paid');

        Route::post(
            '/invoice/{participant}/void',
            [
                PaymentController::class,
                'voidInvoice',
            ]
        )->name('void-invoice');

        Route::post(
            '/invoice/{participant}/resend',
            [
                PaymentController::class,
                'resendInvoice',
            ]
        )->name('resend-invoice');

        Route::get(
            '/invoice/{participant}/download',
            [
                PaymentController::class,
                'downloadInvoice',
            ]
        )->name('download-invoice');

        /*
        |--------------------------------------------------------------------------
        | Manual Transfer Actions
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/manual-transfer/{participant}/approve',
            [
                PaymentController::class,
                'approveManualTransfer',
            ]
        )->name('manual-transfer.approve');

        Route::post(
            '/manual-transfer/{participant}/reject',
            [
                PaymentController::class,
                'rejectManualTransfer',
            ]
        )->name('manual-transfer.reject');

        /*
        |--------------------------------------------------------------------------
        | QRIS Actions
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/qris/{participant}/refresh',
            [
                PaymentController::class,
                'refreshQrisStatus',
            ]
        )->name('qris.refresh');

        Route::post(
            '/qris/{participant}/expire',
            [
                PaymentController::class,
                'expireQris',
            ]
        )->name('qris.expire');
    });

       /*
|--------------------------------------------------------------------------
| USERS
|--------------------------------------------------------------------------
*/

Route::prefix('users')
    ->name('users.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard Users
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/',
            [
                UserController::class,
                'index',
            ]
        )->name('index');

        /*
        |--------------------------------------------------------------------------
        | Administrators
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/admins',
            [
                UserController::class,
                'admins',
            ]
        )->name('admins');

        /*
        |--------------------------------------------------------------------------
        | Premium Users
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/premium',
            [
                UserController::class,
                'premium',
            ]
        )->name('premium');

        /*
        |--------------------------------------------------------------------------
        | Company Owners
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/company-owners',
            [
                UserController::class,
                'companyOwners',
            ]
        )->name('company-owners');

        /*
        |--------------------------------------------------------------------------
        | Pending Verification
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/pending-verification',
            [
                UserController::class,
                'pendingVerification',
            ]
        )->name('pending-verification');

        /*
        |--------------------------------------------------------------------------
        | Activity Logs
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/activity-logs',
            [
                UserController::class,
                'activityLogs',
            ]
        )->name('activity-logs');
    });
// Settings
Route::prefix('settings')
    ->name('settings.')
    ->group(function () {

        Route::get('/', [SettingsController::class, 'index'])
            ->name('index');

        Route::get('/general', [SettingsController::class, 'general'])
            ->name('general');

        Route::get('/membership', [SettingsController::class, 'membership'])
            ->name('membership');

        Route::get('/payment-gateway', [SettingsController::class, 'paymentGateway'])
            ->name('payment-gateway');

        Route::get('/email', [SettingsController::class, 'email'])
            ->name('email');

        Route::get('/localization', [SettingsController::class, 'localization'])
            ->name('localization');

        Route::get('/security', [SettingsController::class, 'security'])
            ->name('security');

        Route::get('/storage', [SettingsController::class, 'storage'])
            ->name('storage');

        Route::get('/queue', [SettingsController::class, 'queue'])
            ->name('queue');

        Route::get('/system-health', [SettingsController::class, 'systemHealth'])
            ->name('system-health');
    });

    });

    require __DIR__.'/auth.php';