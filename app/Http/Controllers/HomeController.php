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
        // Data Ticker & Bursa (Ringan)
        $latestMarket = MarketHistory::orderBy('date', 'desc')->first();
        
        $topStocks = DB::table('companies')
            ->where('stock_qty', '>', 0)
            ->selectRaw('id as company_id, stock_ready_caption as product_name, SUM(stock_qty) as total_qty, stock_unit as unit')
            ->groupBy('company_id', 'product_name', 'unit')
            ->orderBy('total_qty', 'desc')
            ->take(10)->get();


    // Ambil hanya 5 Produk Garmen Teratas untuk Home (Teaser)
    $topProducts = DB::table('trade_master_annual_hscode')
        ->selectRaw("TRIM(hs_code) as hs_code, uraian_hs, vol_2025, val_2025")
        ->where('tipe_arus', 'ekspor')
        ->whereRaw("(TRIM(hs_code) LIKE '61%' OR TRIM(hs_code) LIKE '62%')")
        ->orderBy('val_2025', 'desc')
        ->take(5) // Cukup tampilkan 5 saja di Home
        ->get();


        return Inertia::render('Home', [
            'currentCotton' => $latestMarket->cotton_price ?? 0,
            'currentExchange' => $latestMarket->usd_idr ?? 0,
            'topStocks' => $topStocks,
            'latestNews' => News::latest()->take(3)->get(),
            'topProducts' => $topProducts,
        'isLoggedIn' => auth()->check(), // Untuk cek apakah user perlu diarahkan login
            // Badge admin tetap dikirim jika user login adalah admin
            'pendingCount' => auth()->check() ? Company::where('status_verifikasi', 'pending')->count() : 0,
        ]);
    }

    public function about()
    {
        return Inertia::render('About/Index', [
            'galleries' => \App\Models\Gallery::latest()->get()
        ]);
    }
}
