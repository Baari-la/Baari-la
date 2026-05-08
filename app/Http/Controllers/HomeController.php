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
    ]);

    // Data 5 Produk Garmen Teratas (Sudah benar)
    $topProducts = DB::table('trade_master_annual_hscode')
        ->selectRaw("TRIM(hs_code) as hs_code, uraian_hs, vol_2025, val_2025")
        ->where('tipe_arus', 'ekspor')
        ->whereRaw("(TRIM(hs_code) LIKE '61%' OR TRIM(hs_code) LIKE '62%')")
        ->orderBy('val_2025', 'desc')
        ->take(5)
        ->get();


        return Inertia::render('Home', [
            'currentCotton' => $latestMarket->cotton_price ?? 71.31, // Gunakan variabel yang baru dibuat
        'marketHistory' => $marketHistory,
        'currentExchange' => $latestMarket->usd_idr ?? 16000, // Gunakan variabel yang baru dibuat
        'topStocks' => DB::table('companies')->where('stock_qty', '>', 0)->take(10)->get(),
        'latestNews' => News::latest()->take(3)->get(),
        'topProducts' => $topProducts,
        'isLoggedIn' => auth()->check(),
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