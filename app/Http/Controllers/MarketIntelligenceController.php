<?php
namespace App\Http\Controllers;

use App\Models\MarketHistory;
use App\Models\News;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class MarketIntelligenceController extends Controller
{
    public function getHomeData()
    {
        // 1. Ambil Data Harga Kapas Terbaru (untuk Ticker)
        $latest = MarketHistory::orderBy('date', 'desc')->first();

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

        // 3. Return Semua Data ke Home.jsx
        return Inertia::render('Home', [
            // Data untuk Ticker
            'currentCotton' => $latest->cotton_price ?? 71.31,
            'currentExchange' => $latest->usd_idr ?? 16000,
            
            // Data untuk Komponen Lainnya (Pindahan)
            'marketHistory' => MarketHistory::orderBy('date', 'asc')->take(30)->get(),
            'topStocks' => $topStocks,
            'latestNews' => News::latest()->take(3)->get(),
            'pendingCount' => Company::where('status_verifikasi', 'pending')->count(),
            
            // Data opsional jika masih dibutuhkan
            'industrialData' => [], // Isi sesuai variabel $industrialData Anda sebelumnya
            'topProducts' => [],
            'garmentTrade' => [],
        ]);
    }
    
    public function getDashboardData()
{
    // Ambil data 30 hari terakhir
    $history = \App\Models\MarketHistory::orderBy('date', 'desc')->take(30)->get()->reverse()->values();
    $latest = $history->last();
    $previous = $history->get($history->count() - 2);

    $cottonChange = 0;
    if ($latest && $previous && $previous->cotton_price > 0) {
        $cottonChange = (($latest->cotton_price - $previous->cotton_price) / $previous->cotton_price) * 100;
    }

    return \Inertia\Inertia::render('Dashboard', [
        'marketHistory' => $history->map(fn($item) => [
            'month' => date('d M', strtotime($item->date)),
            'price' => (float)$item->cotton_price,
            'rate' => (float)$item->usd_idr,
        ]),
        'cottonPrice' => $latest->cotton_price ?? 0,
        'cottonTrend' => round($cottonChange, 2) . '%',
        'usd_idr' => $latest->usd_idr ?? 0,
        'memberStatus' => auth()->user()->is_premium ? 'Premium Member' : 'Regular Member',
        'exportValue' => '11.9', 
    ]);
}

}