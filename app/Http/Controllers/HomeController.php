<?php
namespace App\Http\Controllers;

use App\Models\MarketHistory;
use App\Models\News;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
 // Ambil data harga kapas dan nilai tukar 7 hari terakhir saja
    // Ambil data paling terbaru untuk Ticker (currentCotton & currentExchange)
    $latestMarket = \App\Models\MarketHistory::orderBy('date', 'desc')->first();
// app/Http/Controllers/HomeController.php
$marketHistory = \App\Models\MarketHistory::orderBy('date', 'desc')
    ->take(7)
    ->get()
    ->reverse()
    ->values()
    ->map(fn($item) => [
        'month' => date('d M', strtotime($item->date)),
        // Tambahkan (float) dan pastikan tidak ada karakter non-angka
        'price' => (float) str_replace(',', '', $item->cotton_price), 
    'exchange' => (float) $item->usd_idr,
        ]);

     // Gunakan kueri normal Anda yang sudah terbukti jalan
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

// 3. Ambil 5 Komoditas Teratas
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

     // 2. Data Bursa Stok (Pindahan dari HomeController)
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


  return Inertia::render('Home', [
    // 'marketHistory' => MarketHistory::orderBy('date', 'asc')->take(30)->get(),
     'marketHistory' => $marketHistory,
    'topStocks'     => $topStocks,
    'latestNews'    => News::latest()->take(3)->get(),
    'topProducts'   => $topProducts,
      'currentCotton'   => $latestMarket->cotton_price ?? 71.31,
    'currentExchange' => $latestMarket->usd_idr ?? 16000,
    'garmentTrade'    => $garmentTradeData,
     // TAMBAHKAN BARIS INI:
    'totalGarment'  => (float) ($garmentTrade->export_pcs ?? 0),
    'pendingCount'  => auth()->check() ? Company::where('status_verifikasi', 'pending')->count() : 0,
]);
    }





    public function about()
    {
        return Inertia::render('About/Index', [
            'galleries' => \App\Models\Gallery::latest()->get()
        ]);
    }
}