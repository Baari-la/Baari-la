<?php
namespace App\Http\Controllers;

use App\Models\MarketHistory;
use App\Models\News;
use App\Models\Company;
use App\Models\CompanyProduct;
use App\Models\CompanyMarket;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Models\IndustryPartner;
use App\Services\Home\HomeIntelligenceService;
use App\Services\Home\HomeDirectoryService;


class HomeController extends Controller
{
    public function index(
 HomeDirectoryService $directory,
    HomeIntelligenceService $intelligence
    
    )
    {
        // 1. Ambil data bursa paling terbaru untuk Ticker (currentCotton & currentExchange)
        $latestMarket = \App\Models\MarketHistory::orderBy('date', 'desc')->first();

        // 2. Ambil data harga kapas dan nilai tukar 7 hari terakhir untuk grafik utama
        $marketHistory = \App\Models\MarketHistory::orderBy('date', 'desc')
            ->take(7)
            ->get()
            ->reverse()
            ->values()
            ->map(fn($item) => [
                'month'    => date('d M', strtotime($item->date)),
                'price'    => (float) str_replace(',', '', $item->cotton_price), 
                'exchange' => (float) $item->usd_idr,
            ]);

        // 3. Logika Analisis Garmen Nasional
        $garmentTradeData = DB::table('trade_master_annual_hscode')
            ->selectRaw("
                SUM(CASE WHEN tipe_arus = 'ekspor' THEN 
                    CASE 
                        WHEN TRIM(hs_code) LIKE '6109%' THEN vol_2025 * 5.5
                        WHEN TRIM(hs_code) LIKE '6110%' THEN vol_2025 * 2.5
                        WHEN TRIM(hs_code) LIKE '6203%' OR TRIM(hs_code) LIKE '6204%' THEN vol_2025 * 1.8
                        WHEN TRIM(hs_code) LIKE '6111%' OR TRIM(hs_code) LIKE '6209%' THEN vol_2025 * 8.0
                        ELSE vol_2025 * 4.0
                    END ELSE 0 END) as export_pcs,
                SUM(CASE WHEN tipe_arus = 'impor' THEN 
                    CASE 
                        WHEN TRIM(hs_code) LIKE '6109%' THEN vol_2025 * 5.5
                        ELSE vol_2025 * 4.0
                    END ELSE 0 END) as import_pcs
            ")
            ->whereRaw("(TRIM(hs_code) LIKE '61%' OR TRIM(hs_code) LIKE '62%')")
            ->first();

        // 4. Ambil 5 Komoditas Teratas
        $topProducts = DB::table('trade_master_annual_hscode')
            ->selectRaw("TRIM(hs_code) as hs_code, uraian_hs, vol_2025, val_2025")
            ->where('tipe_arus', 'ekspor')
            ->where(function($q) {
                $q->whereRaw("TRIM(hs_code) LIKE '61%'")
                  ->orWhereRaw("TRIM(hs_code) LIKE '62%'");
            })
            ->orderBy('val_2025', 'desc')
            ->take(5)
            ->get();

        // 5. Data Bursa Stok Komoditas Anggota
        $topStocks = DB::table('companies')
            ->where('stock_qty', '>', 0)
            ->selectRaw('
                id as company_id, 
                stock_ready_caption as product_name, 
                SUM(stock_qty) as total_qty, 
                stock_unit as unit
            ')
            ->groupBy('company_id', 'product_name', 'unit')
            ->orderBy('total_qty', 'desc')
            ->take(10)
            ->get();

        // 6. LOGIKA AMAN: PENGUNCI DATA SERAT STRATEGIS
        $fiberData = $this->getFiberIntelligence();
        
       // Jika pengunjung BELUM LOGIN, potong data setelah tahun 2022
if (!auth()->check()) {
    $fiberData = collect($fiberData)->map(function($item, $key) {
        // $key 0=2019, 1=2020, 2=2021, 3=2022. Kita potong jika indeks di atas 3 (2023, 2024, 2025)
        if ($key > 3) { 
            $item['cotton_vol'] = 0; $item['cotton_val'] = 0;
            $item['syn_vol'] = 0;    $item['syn_val'] = 0;
        }
        return $item;
    })->all();
        } /* PERBAIKAN 1: Menutup blok penyeleksian data publik */

        // Untuk data regulasi / presentasi Ambil 3 dokumen regulasi/materi terbaru untuk ditampilkan di halaman depan
$regulations = \DB::table('regulations')
    ->orderBy('event_date', 'desc')
    ->get();
$inventoryItems = \DB::table('inventories')->orderBy('created_at', 'desc')->get();

// Ambil seluruh daftar kemitraan B2B multi-sektor dari database
$partnershipItems = \DB::table('partnerships')->orderBy('match_percentage', 'desc')->get();

$directoryStats = [

    'companies' =>
        Company::count(),

    'products' =>
        CompanyProduct::count(),

    'markets' =>
        CompanyMarket::count(),

    'exportCompanies' =>
        Company::has('markets')->count(),
];

// Iklan/partner
$featuredPartner =
    IndustryPartner::where(
        'is_active',
        true
    )
    ->where(
        'partner_level',
        'gold'
    )
    ->first();

    $industrySolutions =
    IndustryPartner::where(
        'is_active',
        true
    )
    ->take(6)
    ->get();

// Berita Market Intellgence
    $latestIntelligence = News::latest()
    ->take(8)
    ->get();

$marketIntelligence = News::where(
        'category',
        'Market Intelligence'
    )
    ->latest()
    ->take(4)
    ->get();

$tradePolicy = News::where(
        'category',
        'Trade & Policy'
    )
    ->latest()
    ->take(4)
    ->get();

$sustainability = News::where(
        'category',
        'Sustainability'
    )
    ->latest()
    ->take(4)
    ->get();

$technology = News::where(
        'category',
        'Technology & Innovation'
    )
    ->latest()
    ->take(4)
    ->get();

$industryNews = News::where(
        'category',
        'Industry News'
    )
    ->latest()
    ->take(4)
    ->get();

$intelligenceStats = [
    'reports' => News::count(),
    'companies' => Company::count(),
    'markets' => CompanyMarket::count(),
    'desks' => 5,
];

        // 7. Render Seluruh Payload ke Halaman Depan
        return Inertia::render('Home', [
            'directoryStats' => ['companies' => Company::count(),
            'products' => CompanyProduct::count(),
            'markets' => CompanyMarket::count(),
            'exportCompanies' => Company::has('markets')->count(),
],
           'featuredPartner' => $featuredPartner,
           'industrySolutions' => $industrySolutions,
            'marketHistory'     => $marketHistory,
            'topStocks'         => $topStocks,
            'latestNews'        => News::latest()->take(3)->get(),
             'latestIntelligence' => $latestIntelligence,
    'marketIntelligence' => $marketIntelligence,
    'tradePolicy' => $tradePolicy,
    'sustainability' => $sustainability,
    'technology' => $technology,
    'industryNews' => $industryNews,
    'intelligenceStats' => $intelligenceStats,
            'topProducts'       => $topProducts,
            'fiberIntelligence' => $fiberData, /* PERBAIKAN 2: Menggunakan array yang sudah diproteksi, bukan fungsi mentah */
            'isLoggedIn'        => auth()->check(),
            'currentCotton'     => $latestMarket->cotton_price ?? 71.31,
            'currentExchange'   => $latestMarket->usd_idr ?? 16000,
            'garmentTrade'      => $garmentTradeData,
            'totalGarment'      => (float) ($garmentTradeData->export_pcs ?? 0), /* PERBAIKAN 3: Memperbaiki nama variabel bursa garmen */
            'pendingCount'      => auth()->check() ? Company::where('status_verifikasi', 'pending')->count() : 0,
            'regulations' => $regulations,
            'partnershipItems' => $partnershipItems,
             'inventoryItems' => $inventoryItems,
    'isLoggedIn'  => auth()->check(),
'auth' => [
        'user' => auth()->user() ? [
            'id'            => auth()->user()->id,
            'name'          => auth()->user()->name,
            'email'         => auth()->user()->email,
            'role'          => auth()->user()->role, 
            'company_id'    => auth()->user()->company_id ?? auth()->user()->company_id,
            'member_status' => auth()->user()->member_status ?? 'Free',
            'locale'        => auth()->user()->locale ?? 'id',
        ] : null,
    ],

    
        ]);
    }

    private function getIntelligenceData(): array
{
    return [
        'featuredIntelligence' => News::latest()->first(),
        'latestIntelligence' => News::latest()->skip(1)->take(8)->get(),
    ];
}

    // --- ASISTEN PRIVATE: DATA SERAT MURNI ANTI BENANG GANDA ---
    private function getFiberIntelligence() 
    {
        $years = ['2019', '2020', '2021', '2022', '2023', '2024', '2025'];
        $trendData = [];
        $prevCotton = 0;
        $prevSyn = 0;

        foreach ($years as $year) {
            // 1. DATA SERAT KAPAS ALAM MURNI (HS 5201, 5202, 5203)
            $cottonData = DB::table('trade_master_annual_hscode')
                ->selectRaw("SUM(vol_$year) as vol, SUM(val_$year) as val")
                ->where(function($q) {
                    $q->where('hs_code', 'LIKE', '%5201%')
                      ->orWhere('hs_code', 'LIKE', '%5202%')
                      ->orWhere('hs_code', 'LIKE', '%5203%');
                })
                ->where('tipe_arus', 'impor')
                ->first();

            // 2. DATA SERAT SINTETIS MURNI (HS 5501 sampai 5507)
            $syntheticData = DB::table('trade_master_annual_hscode')
                ->selectRaw("SUM(vol_$year) as vol, SUM(val_$year) as val")
                ->where(function($q) {
                    $q->where('hs_code', 'LIKE', '%5501%')
                      ->orWhere('hs_code', 'LIKE', '%5502%')
                      ->orWhere('hs_code', 'LIKE', '%5503%')
                      ->orWhere('hs_code', 'LIKE', '%5504%')
                      ->orWhere('hs_code', 'LIKE', '%5505%')
                      ->orWhere('hs_code', 'LIKE', '%5506%')
                      ->orWhere('hs_code', 'LIKE', '%5507%');
                })
                ->where('tipe_arus', 'impor')
                ->first();

            // Ekstraksi nilai angka aman dari objek database
            $c_vol = (float) ($cottonData->vol ?? 0);
            $s_vol = (float) ($syntheticData->vol ?? 0);

            $trendData[] = [
                'year'       => $year,
                'cotton_vol' => $c_vol,
                'cotton_val' => (float) ($cottonData->val ?? 0),
                'syn_vol'    => $s_vol,
                'syn_val'    => (float) ($syntheticData->val ?? 0),
                
                // Menghitung tren pertumbuhan tahunan
                'cotton_growth' => ($prevCotton > 0) ? round((($c_vol - $prevCotton) / $prevCotton) * 100, 1) : 0,
                'syn_growth'    => ($prevSyn > 0) ? round((($s_vol - $prevSyn) / $prevSyn) * 100, 1) : 0,
            ];

            $prevCotton = $c_vol;
            $prevSyn = $s_vol;
        }
        return $trendData;
    }

    public function about()
    {
        return Inertia::render('About/Index', [
            'galleries' => \App\Models\Gallery::latest()->get()
        ]);
    }
}