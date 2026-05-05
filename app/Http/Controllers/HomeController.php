<?php

namespace App\Http\Controllers;

// HAPUS baris use App\Http\Controllers\HomeController; (Ini penyebab error)
use App\Models\MarketHistory;
use App\Models\News;
use App\Models\Company; // Tambahkan ini untuk Dashboard Admin
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Query Data grafik
        $analytics = DB::table('trade_analytics_annual')
            ->select(
                DB::raw("ROUND(SUM(CASE WHEN id_hs = 1 THEN val_2021 ELSE 0 END) / 1000000000, 2) as hulu_2021"),
                DB::raw("ROUND(SUM(CASE WHEN id_hs IN (2, 3) THEN val_2021 ELSE 0 END) / 1000000000, 2) as antara_2021"),
                DB::raw("ROUND(SUM(CASE WHEN id_hs = 4 THEN val_2021 ELSE 0 END) / 1000000000, 2) as hilir_2021"),
                DB::raw("ROUND(SUM(CASE WHEN id_hs = 1 THEN val_2022 ELSE 0 END) / 1000000000, 2) as hulu_2022"),
                DB::raw("ROUND(SUM(CASE WHEN id_hs IN (2, 3) THEN val_2022 ELSE 0 END) / 1000000000, 2) as antara_2022"),
                DB::raw("ROUND(SUM(CASE WHEN id_hs = 4 THEN val_2022 ELSE 0 END) / 1000000000, 2) as hilir_2022"),
                DB::raw("ROUND(SUM(CASE WHEN id_hs = 1 THEN val_2023 ELSE 0 END) / 1000000000, 2) as hulu_2023"),
                DB::raw("ROUND(SUM(CASE WHEN id_hs IN (2, 3) THEN val_2023 ELSE 0 END) / 1000000000, 2) as antara_2023"),
                DB::raw("ROUND(SUM(CASE WHEN id_hs = 4 THEN val_2023 ELSE 0 END) / 1000000000, 2) as hilir_2023"),
                DB::raw("ROUND(SUM(CASE WHEN id_hs = 1 THEN val_2024 ELSE 0 END) / 1000000000, 2) as hulu_2024"),
                DB::raw("ROUND(SUM(CASE WHEN id_hs IN (2, 3) THEN val_2024 ELSE 0 END) / 1000000000, 2) as antara_2024"),
                DB::raw("ROUND(SUM(CASE WHEN id_hs = 4 THEN val_2024 ELSE 0 END) / 1000000000, 2) as hilir_2024"),
                DB::raw("ROUND(SUM(CASE WHEN id_hs = 1 THEN val_jandes_2025 ELSE 0 END) / 1000000000, 2) AS hulu_2025"),
                DB::raw("ROUND(SUM(CASE WHEN id_hs IN (2, 3) THEN val_jandes_2025 ELSE 0 END) / 1000000000, 2) AS antara_2025"),
                DB::raw("ROUND(SUM(CASE WHEN id_hs = 4 THEN val_jandes_2025 ELSE 0 END) / 1000000000, 2) AS hilir_2025")
            )
            ->first();

        // Cek jika null agar tidak error
        $a = $analytics; 
        
        $industrialData = [
            ['year' => '2021', 'hulu' => $a->hulu_2021 ?? 0, 'antara' => $a->antara_2021 ?? 0, 'hilir' => $a->hilir_2021 ?? 0, 'total' => ($a->hulu_2021 ?? 0) + ($a->antara_2021 ?? 0) + ($a->hilir_2021 ?? 0)],
            ['year' => '2022', 'hulu' => $a->hulu_2022 ?? 0, 'antara' => $a->antara_2022 ?? 0, 'hilir' => $a->hilir_2022 ?? 0, 'total' => ($a->hulu_2022 ?? 0) + ($a->antara_2022 ?? 0) + ($a->hilir_2022 ?? 0)],
            ['year' => '2023', 'hulu' => $a->hulu_2023 ?? 0, 'antara' => $a->antara_2023 ?? 0, 'hilir' => $a->hilir_2023 ?? 0, 'total' => ($a->hulu_2023 ?? 0) + ($a->antara_2023 ?? 0) + ($a->hilir_2023 ?? 0)],
            ['year' => '2024', 'hulu' => $a->hulu_2024 ?? 0, 'antara' => $a->antara_2024 ?? 0, 'hilir' => $a->hilir_2024 ?? 0, 'total' => ($a->hulu_2024 ?? 0) + ($a->antara_2024 ?? 0) + ($a->hilir_2024 ?? 0)],
            ['year' => '2025', 'hulu' => $a->hulu_2025 ?? 0, 'antara' => $a->antara_2025 ?? 0, 'hilir' => $a->hilir_2025 ?? 0, 'total' => ($a->hulu_2025 ?? 0) + ($a->antara_2025 ?? 0) + ($a->hilir_2025 ?? 0)],
        ];

        // 2. Query Data Garmen (Tabel)
        $topProducts = DB::table('trade_master_annual_hscode')
            ->selectRaw("
                TRIM(hs_code) as hs_code_clean, 
                uraian_hs, 
                vol_2024, 
                vol_2025, 
                val_2024, 
                val_2025, 
                (vol_2025 - vol_2024) as selisih_kg, 
                ((vol_2025 - vol_2024) / NULLIF(vol_2024, 0) * 100) as growth
            ")
            ->where('tipe_arus', 'ekspor')
            ->whereRaw("(TRIM(hs_code) LIKE '61%' OR TRIM(hs_code) LIKE '62%')")
            ->orderBy('vol_2025', 'desc')
            ->get();

        // 3. Algoritma Pieces (Pcs) & Thread Demand
        $garmentTrade = DB::table('trade_master_annual_hscode')
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
                        WHEN TRIM(hs_code) LIKE '6110%' THEN vol_2025 * 2.5
                        WHEN TRIM(hs_code) LIKE '6203%' OR TRIM(hs_code) LIKE '6204%' THEN vol_2025 * 1.8
                        WHEN TRIM(hs_code) LIKE '6111%' OR TRIM(hs_code) LIKE '6209%' THEN vol_2025 * 8.0
                        ELSE vol_2025 * 4.0
                    END ELSE 0 END) as import_pcs
            ")
            ->whereRaw("(TRIM(hs_code) LIKE '61%' OR TRIM(hs_code) LIKE '62%')")
            ->first();

 // 1. Data Bursa Stok (Dari LandingPageController sebelumnya)
    $topStocks = DB::table('companies')
    ->where('stock_qty', '>', 0)
    ->selectRaw('
        id as company_id, 
        stock_ready_caption as product_name, 
        SUM(stock_qty) as total_qty, 
        stock_unit as unit
    ')
    ->groupBy('company_id', 'product_name', 'unit') // Kelompokkan berdasarkan perusahaan
    ->orderBy('total_qty', 'desc')
    ->take(10)
    ->get();
            
    
        return Inertia::render('Home', [
            'marketHistory' => MarketHistory::orderBy('date', 'asc')->take(30)->get(),
            'industrialData' => $industrialData,
              'topStocks' => $topStocks,
            'latestNews' => News::latest()->take(3)->get(),
            'topProducts' => $topProducts,
            'garmentTrade' => $garmentTrade,
            // Tambahkan pendingCount untuk badge admin
            'pendingCount' => Company::where('status_verifikasi', 'pending')->count(),
        ]);
    }

    public function adminDashboard()
    {
        $total = Company::count();
        $active = Company::where('last_verified_at', '>', now()->subMonths(11))->count();
        $expiring = Company::where('last_verified_at', '<=', now()->subMonths(11))
                    ->where('last_verified_at', '>', now()->subMonths(12))
                    ->count();
        $expired = $total - ($active + $expiring);

        return Inertia::render('Admin/Dashboard', [
            'healthStats' => [
                'active' => $active,
                'expiring' => $expiring,
                'expired' => $expired,
                'total' => $total
            ]
        ]);
    }

// app/Http/Controllers/HomeController.php atau PageController.php

public function about()
{
     $galleries = \App\Models\Gallery::latest()->get();
    return Inertia::render('About/Index', [
        'galleries' => $galleries
    ]);

    }

}