<?php

namespace App\Http\Controllers;

use App\Models\MarketHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class TradeDashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil Data Tren 5 Tahun (Annual)
        $annualTrend = DB::table('trade_master_annual_hscode')
            ->selectRaw("
                tipe_arus,
                SUM(val_2021) as '2021',
                SUM(val_2022) as '2022',
                SUM(val_2023) as '2023',
                SUM(val_2024) as '2024',
                SUM(val_2025) as '2025'
            ")
            ->groupBy('tipe_arus')
            ->get();

        // 2. Ambil Data Perbandingan Jan-Feb (2025 vs 2026)
        $monthlyCompare = DB::table('trade_analytics_monthly')
            ->selectRaw("
                tipe_arus,
                SUM(val_2025_01) as Jan_2025,
                SUM(val_2026_01) as Jan_2026,
                SUM(val_2025_02) as Feb_2025,
                SUM(val_2026_02) as Feb_2026
            ")
            ->groupBy('tipe_arus')
            ->get();

        // 3. Ambil Riwayat Market 30 Hari (Untuk Grafik Recharts)
        $history = MarketHistory::orderBy('date', 'desc')
            ->take(30)
            ->get()
            ->reverse()
            ->values();

        $latestMarket = $history->last();
        $previousMarket = $history->get($history->count() - 2);

        $fullTradeData = DB::table('trade_master_annual_hscode')
        ->where('tipe_arus', 'ekspor')
        ->orderBy('val_2025', 'desc')
        ->get();


        // 4. Hitung Cotton Trend (Persentase)
        $cottonChange = 0;
        if ($latestMarket && $previousMarket && $previousMarket->cotton_price > 0) {
            $cottonChange = (($latestMarket->cotton_price - $previousMarket->cotton_price) / $previousMarket->cotton_price) * 100;
        }

        // 5. Spesifikasi Garmen untuk Kalkulator
        $garmenSpecs = config('garmen_specs');

 // Ambil data harga kapas dan nilai Tukar 30 hari terakhir untuk member
$history = MarketHistory::orderBy('date', 'desc')->take(30)->get()->reverse()->values();
    $latest = $history->last();

 
    $history = MarketHistory::orderBy('date', 'desc')->take(30)->get()->reverse()->values();
    $latest = $history->last();

     $marketHistory = \App\Models\MarketHistory::orderBy('date', 'desc')
        ->take(7)->get()->reverse()->values()
        ->map(fn($item) => [
            'month'    => date('d M', strtotime($item->date)),
            'price'    => (float) $item->cotton_price,
            'exchange' => (float) $item->usd_idr,
        ]);
    
        
        // Kirim data ke React (Dashboard.jsx)
        return Inertia::render('Dashboard', [
            // Data Analitik Perdagangan
            'annualTrend' => $annualTrend,
            'marketHistory' => $marketHistory,
            'monthlyCompare' => $monthlyCompare,
            'garmenSpecs' => $garmenSpecs,
            'fullTradeData' => $fullTradeData,
            
            // Data Market Intelligence (Sesuai props Dashboard.jsx)
             'marketHistory' => $history->map(fn($item) => [
            'month' => date('d M', strtotime($item->date)),
            'price' => (float)$item->cotton_price,
            'rate' => (float)$item->usd_idr,
        ]),
            'cottonPrice' => $latestMarket->cotton_price ?? 71.31,
            'cottonTrend' => round($cottonChange, 2) . '%',
            'usd_idr' => $latestMarket->usd_idr ?? 16000,
            'cottonPrice' => $latest->cotton_price ?? 0,
        'usd_idr' => $latest->usd_idr ?? 0,
            // Data Pendukung
             'annualTrend' => $this->getAnnualTrendData(), 
            'memberStatus' => auth()->user()->is_premium ? 'Premium Member' : 'Regular Member',
            'exportValue' => '11.9', // Nanti bisa dihitung dinamis dari $annualTrend
            'lastUpdate' => now()->format('d M Y')
        ]);
    }

private function getAnnualTrendData()
{
    return DB::table('trade_master_annual_hscode')
        ->selectRaw("
            tipe_arus,
            SUM(val_2021) as '2021',
            SUM(val_2022) as '2022',
            SUM(val_2023) as '2023',
            SUM(val_2024) as '2024',
            SUM(val_2025) as '2025'
        ")
        ->groupBy('tipe_arus')
        ->get();
}

    public function calculate(Request $request)
    {
        $request->validate([
            'hs_code' => 'required',
            'pieces' => 'required|numeric|min:1'
        ]);

        $prefix = substr($request->hs_code, 0, 4);
        $specs = config("garmen_specs.$prefix") ?? [
            'weight_kg_pcs' => 0.4, 
            'fabric_cons_meter' => 1.4, 
            'cbm_per_pcs' => 0.005
        ];

        $result = [
            'total_weight' => $request->pieces * $specs['weight_kg_pcs'],
            'total_fabric' => $request->pieces * $specs['fabric_cons_meter'],
            'total_cbm' => $request->pieces * $specs['cbm_per_pcs'],
            'container_estimate' => ceil(($request->pieces * $specs['cbm_per_pcs']) / 28)
        ];

        return response()->json($result);
    }
}