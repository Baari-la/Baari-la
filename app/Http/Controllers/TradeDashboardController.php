<?php

namespace App\Http\Controllers;

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

             // 3. Ambil data spesifikasi garmen untuk fitur kalkulator
        $garmenSpecs = config('garmen_specs');

 $latestMarket = \App\Models\MarketHistory::orderBy('date', 'desc')->first();
 
        // Kirim data ke React (Halaman: resources/js/Pages/Dashboard/Trade.jsx)
        return Inertia::render('Dashboard/Trade', [
            'annualTrend' => $annualTrend,
            'monthlyCompare' => $monthlyCompare,
            'garmenSpecs' => $garmenSpecs,
              'currentCotton' => $latestMarket->cotton_price ?? 71.31,
        'currentExchange' => $latestMarket->usd_idr ?? 16000,
            'lastUpdate' => now()->format('d M Y')
        ]);
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