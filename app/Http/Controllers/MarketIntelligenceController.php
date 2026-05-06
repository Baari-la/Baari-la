<?php
namespace App\Http\Controllers;

use App\Models\MarketHistory;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class MarketIntelligenceController extends Controller
{
    public function getDashboardData()
    {
        // 1. Ambil 30 data terbaru, lalu urutkan dari lama ke baru (asc) untuk grafik
        $history = MarketHistory::orderBy('date', 'desc')->take(30)->get()->reverse()->values();

        // 2. Ambil data hari ini dan kemarin untuk menghitung persentase
        $latest = $history->last();
        $previous = $history->get($history->count() - 2);

        // 3. Logika perhitungan persentase kenaikan/penurunan harga kapas
        $cottonChange = 0;
        if ($latest && $previous && $previous->cotton_price > 0) {
            $cottonChange = (($latest->cotton_price - $previous->cotton_price) / $previous->cotton_price) * 100;
        }

        return Inertia::render('Dashboard', [
            // Data untuk Grafik Recharts
            'marketHistory' => $history->map(fn($item) => [
                'month' => date('d M', strtotime($item->date)),
                'price' => (float)$item->cotton_price,
                'rate' => (float)$item->usd_idr,
            ]),
            
            // Data untuk Kartu Statistik
            'cottonPrice' => $latest->cotton_price ?? 0,
            'cottonTrend' => round($cottonChange, 2) . '%',
            'usd_idr' => $latest->usd_idr ?? 0,
            
            // Data tambahan
            'memberStatus' => auth()->user()->is_premium ? 'Premium Member' : 'Regular Member',
            'exportValue' => '11.9', // Nanti bisa diambil dari tabel trade_analytics
        ]);
    }
}