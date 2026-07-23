<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AnalyticsController extends Controller
{
 

public function deepAnalysis(Request $request)
{

    // 1. Ambil Total Ekspor Nasional
    $totalNasional = DB::table('trade_master_annual_country')
        ->where('tipe_arus', 'ekspor')
        ->sum('val_2025');

    // 2. Query data Negara (Bukan Uni Eropa)
    $countryData = DB::table('trade_master_annual_country as t')
        ->join('mst_negara as n', 't.id_negara', '=', 'n.id_negara')
        ->where('t.tipe_arus', 'ekspor')
        ->where('n.kawasan', '!=', 'Uni Eropa')
        ->select('n.nama_negara as name', DB::raw('SUM(t.val_2025) as val'))
        ->groupBy('n.nama_negara');

    // 3. Query data Uni Eropa (Sebagai Blok)
    $euData = DB::table('trade_master_annual_country as t')
        ->join('mst_negara as n', 't.id_negara', '=', 'n.id_negara')
        ->where('t.tipe_arus', 'ekspor')
        ->where('n.kawasan', '=', 'Uni Eropa')
        ->select(DB::raw("'UNI EROPA (BLOK)' as name"), DB::raw('SUM(t.val_2025) as val'))
        ->groupBy(DB::raw("'UNI EROPA (BLOK)'"));

    // 4. Gabungkan dan Eksekusi
    $topMarkets = $countryData->union($euData)
        ->orderBy('val', 'desc')
        ->limit(5)
        ->get()
        ->map(function ($item) use ($totalNasional) {
            $valM = round($item->val / 1000000, 2);
            $share = $totalNasional > 0 ? round(($item->val / $totalNasional) * 100, 1) : 0;
            
            // Bersihkan nama negara
            $displayName = str_replace(
                ['AMERIKASERIKAT', 'KOREASELATAN', 'REP.RAKYATCINA'], 
                ['AMERIKA SERIKAT', 'KOREA SELATAN', 'R.R. CINA'], 
                trim($item->name)
            );

            return [
                'name'  => $displayName,
                'value' => $valM,
                'label' => "$" . number_format($valM, 2) . "M (" . $share . "%)"
            ];
        });   

    // 2. DATA BULANAN (JAN-FEB 2025 VS 2026)
    $monthlyData = DB::table('trade_analytics_monthly')
        ->where('tipe_arus', 'ekspor')
        ->where('dimensi', 'country')
        ->select(
            DB::raw("SUM(val_2025_01 + val_2025_02) as v25"),
            DB::raw("SUM(vol_2025_01 + vol_2025_02) as q25"),
            DB::raw("SUM(val_2026_01 + val_2026_02) as v26"),
            DB::raw("SUM(vol_2026_01 + vol_2026_02) as q26")
        )->first();

    $comparisonData = [
        ['period' => 'Jan-Feb 2025', 'value' => round($monthlyData->v25/1000000, 2), 'volume' => round($monthlyData->q25/1000000, 2)],
        ['period' => 'Jan-Feb 2026', 'value' => round($monthlyData->v26/1000000, 2), 'volume' => round($monthlyData->q26/1000000, 2)]
    ];


// To 5 countries
$topCountries = DB::table('trade_master_annual_country')
        ->where('tipe_arus', 'ekspor')
        ->select(
            'nama_negara', 
            DB::raw('SUM(val_2025) as total_val'),
            DB::raw('SUM(vol_2025) as total_vol')
        )
        ->groupBy('nama_negara', 'id_negara')
        ->orderBy('total_val', 'desc')
        ->limit(5)
        ->get()
        ->map(function ($item) {
            return [
                'name' => trim($item->nama_negara),
                'value' => round($item->total_val / 1000000, 2), // Miliar/Juta USD
                'volume' => round($item->total_vol / 1000000, 2), // Juta KG
                // Menghitung harga rata-rata per KG untuk lobi
                'avg_price' => $item->total_vol > 0 ? round($item->total_val / $item->total_vol, 2) : 0
            ];
        });



// 1. Ambil data Kawasan untuk Filter
    $regions = DB::table('mst_negara')->select('kawasan')->distinct()->whereNotNull('kawasan')->get();

    // 2. QUERY MURNI: Mengunci Ekspor dan Dimensi HSCode agar tidak duplikasi
    $analytics = DB::table('trade_analytics_annual')
        ->where('tipe_arus', 'ekspor')
        ->where('dimensi', 'hscode')
        ->select(
            // 2021
            DB::raw("SUM(CASE WHEN id_hs = 1 THEN val_2021 ELSE 0 END) as h_21"),
            DB::raw("SUM(CASE WHEN id_hs IN (2, 3) THEN val_2021 ELSE 0 END) as a_21"),
            DB::raw("SUM(CASE WHEN id_hs = 4 THEN val_2021 ELSE 0 END) as l_21"),
            DB::raw("SUM(CASE WHEN id_hs = 5 THEN val_2021 ELSE 0 END) as lain_21"),
            
            // 2022
            DB::raw("SUM(CASE WHEN id_hs = 1 THEN val_2022 ELSE 0 END) as h_22"),
            DB::raw("SUM(CASE WHEN id_hs IN (2, 3) THEN val_2022 ELSE 0 END) as a_22"),
            DB::raw("SUM(CASE WHEN id_hs = 4 THEN val_2022 ELSE 0 END) as l_22"),
DB::raw("SUM(CASE WHEN id_hs = 5 THEN val_2022 ELSE 0 END) as lain_22"),

            // 2023
            DB::raw("SUM(CASE WHEN id_hs = 1 THEN val_2023 ELSE 0 END) as h_23"),
            DB::raw("SUM(CASE WHEN id_hs IN (2, 3) THEN val_2023 ELSE 0 END) as a_23"),
            DB::raw("SUM(CASE WHEN id_hs = 4 THEN val_2023 ELSE 0 END) as l_23"),
DB::raw("SUM(CASE WHEN id_hs = 5 THEN val_2023 ELSE 0 END) as lain_23"),
            
// 2024
            DB::raw("SUM(CASE WHEN id_hs = 1 THEN val_2024 ELSE 0 END) as h_24"),
            DB::raw("SUM(CASE WHEN id_hs IN (2, 3) THEN val_2024 ELSE 0 END) as a_24"),
            DB::raw("SUM(CASE WHEN id_hs = 4 THEN val_2024 ELSE 0 END) as l_24"),
DB::raw("SUM(CASE WHEN id_hs = 5 THEN val_2024 ELSE 0 END) as lain_24"),
            
// 2025
            DB::raw("SUM(CASE WHEN id_hs = 1 THEN val_jandes_2025 ELSE 0 END) as h_25"),
            DB::raw("SUM(CASE WHEN id_hs IN (2, 3) THEN val_jandes_2025 ELSE 0 END) as a_25"),
            DB::raw("SUM(CASE WHEN id_hs = 4 THEN val_jandes_2025 ELSE 0 END) as l_25"),
            DB::raw("SUM(CASE WHEN id_hs = 5 THEN val_jandes_2025 ELSE 0 END) as lain_25"),

            // Tambahan Volume
            // 2021
        DB::raw("SUM(CASE WHEN id_hs = 1 THEN vol_2021 ELSE 0 END) / 1000000 as v_h_21"),
        DB::raw("SUM(CASE WHEN id_hs IN (2, 3) THEN vol_2021 ELSE 0 END) / 1000000 as v_a_21"),
        DB::raw("SUM(CASE WHEN id_hs = 4 THEN vol_2021 ELSE 0 END) / 1000000 as v_l_21"),
        DB::raw("SUM(CASE WHEN id_hs = 5 THEN vol_2021 ELSE 0 END) / 1000000 as v_lain_21"),
 
            // 2022
        DB::raw("SUM(CASE WHEN id_hs = 1 THEN vol_2022 ELSE 0 END) / 1000000 as v_h_22"),
        DB::raw("SUM(CASE WHEN id_hs IN (2, 3) THEN vol_2022 ELSE 0 END) / 1000000 as v_a_22"),
        DB::raw("SUM(CASE WHEN id_hs = 4 THEN vol_2022 ELSE 0 END) / 1000000 as v_l_22"),
        DB::raw("SUM(CASE WHEN id_hs = 5 THEN vol_2022 ELSE 0 END) / 1000000 as v_lain_22"),
    // 2023
        DB::raw("SUM(CASE WHEN id_hs = 1 THEN vol_2023 ELSE 0 END) / 1000000 as v_h_23"),
        DB::raw("SUM(CASE WHEN id_hs IN (2, 3) THEN vol_2023 ELSE 0 END) / 1000000 as v_a_23"),
        DB::raw("SUM(CASE WHEN id_hs = 4 THEN vol_2023 ELSE 0 END) / 1000000 as v_l_23"),
        DB::raw("SUM(CASE WHEN id_hs = 5 THEN vol_2023 ELSE 0 END) / 1000000 as v_lain_23"),

    // 2024
        DB::raw("SUM(CASE WHEN id_hs = 1 THEN vol_2024 ELSE 0 END) / 1000000 as v_h_24"),
        DB::raw("SUM(CASE WHEN id_hs IN (2, 3) THEN vol_2024 ELSE 0 END) / 1000000 as v_a_24"),
        DB::raw("SUM(CASE WHEN id_hs = 4 THEN vol_2024 ELSE 0 END) / 1000000 as v_l_24"),
        DB::raw("SUM(CASE WHEN id_hs = 5 THEN vol_2024 ELSE 0 END) / 1000000 as v_lain_24"),

        // TAMBAHKAN QUERY VOLUME 2025
        DB::raw("SUM(CASE WHEN id_hs = 1 THEN vol_jandes_2025 ELSE 0 END) / 1000000 as v_h_25"),
        DB::raw("SUM(CASE WHEN id_hs IN (2, 3) THEN vol_jandes_2025 ELSE 0 END) / 1000000 as v_a_25"),
        DB::raw("SUM(CASE WHEN id_hs = 4 THEN vol_jandes_2025 ELSE 0 END) / 1000000 as v_l_25"),
        DB::raw("SUM(CASE WHEN id_hs = 5 THEN vol_jandes_2025 ELSE 0 END) / 1000000 as v_lain_25")
        // Batas tambhan volume
        )
        ->first();

// 3. Susun Data ke Format Grafik (Normalisasi ke Billion USD)
// 3. Susun Data ke Format Grafik
$industrialData = [
    [
        'year' => '2021', 
        'hulu' => round($analytics->h_21 / 1000000000, 2), 
        'antara' => round($analytics->a_21 / 1000000000, 2), 
        'hilir' => round($analytics->l_21 / 1000000000, 2),
        'lain' => round($analytics->lain_21 / 1000000000, 2),
        'total' => round(($analytics->h_21 + $analytics->a_21 + $analytics->l_21 + $analytics->lain_21) / 1000000000, 2),
        // TAMBAHKAN DATA VOLUME DI SINI:
        'vol_hulu' => round($analytics->v_h_21, 2),
        'vol_antara' => round($analytics->v_a_21, 2),
        'vol_hilir' => round($analytics->v_l_21, 2),
        'vol_lain' => round($analytics->v_lain_21, 2),
        // Di dalam loop $industrialData Bapak:
'vol_total' => round($analytics->v_h_21 + $analytics->v_a_21 + $analytics->v_l_21 + $analytics->v_lain_21, 2),

    ],
    [
        'year' => '2022', 
        'hulu' => round($analytics->h_22 / 1000000000, 2), 
        'antara' => round($analytics->a_22 / 1000000000, 2), 
        'hilir' => round($analytics->l_22 / 1000000000, 2),
        'lain' => round($analytics->lain_22 / 1000000000, 2),
        'total' => round(($analytics->h_22 + $analytics->a_22 + $analytics->l_22 + $analytics->lain_22) / 1000000000, 2),
        'vol_hulu' => round($analytics->v_h_22, 2),
        'vol_antara' => round($analytics->v_a_22, 2),
        'vol_hilir' => round($analytics->v_l_22, 2),
        'vol_lain' => round($analytics->v_lain_22, 2)
    ],
    [
        'year' => '2023', 
        'hulu' => round($analytics->h_23 / 1000000000, 2), 
        'antara' => round($analytics->a_23 / 1000000000, 2), 
        'hilir' => round($analytics->l_23 / 1000000000, 2),
        'lain' => round($analytics->lain_23 / 1000000000, 2),
        'total' => round(($analytics->h_23 + $analytics->a_23 + $analytics->l_23 + $analytics->lain_23) / 1000000000, 2),
        'vol_hulu' => round($analytics->v_h_23, 2),
        'vol_antara' => round($analytics->v_a_23, 2),
        'vol_hilir' => round($analytics->v_l_23, 2),
        'vol_lain' => round($analytics->v_lain_23, 2)
    ],
    [
        'year' => '2024', 
        'hulu' => round($analytics->h_24 / 1000000000, 2), 
        'antara' => round($analytics->a_24 / 1000000000, 2), 
        'hilir' => round($analytics->l_24 / 1000000000, 2),
        'lain' => round($analytics->lain_24 / 1000000000, 2),
        'total' => round(($analytics->h_24 + $analytics->a_24 + $analytics->l_24 + $analytics->lain_24) / 1000000000, 2),
        'vol_hulu' => round($analytics->v_h_24, 2),
        'vol_antara' => round($analytics->v_a_24, 2),
        'vol_hilir' => round($analytics->v_l_24, 2),
        'vol_lain' => round($analytics->v_lain_24, 2)
    ],
    [
        'year' => '2025', 
        'hulu' => round($analytics->h_25 / 1000000000, 2), 
        'antara' => round($analytics->a_25 / 1000000000, 2), 
        'hilir' => round($analytics->l_25 / 1000000000, 2),
        'lain' => round($analytics->lain_25 / 1000000000, 2),
        'total' => round(($analytics->h_25 + $analytics->a_25 + $analytics->l_25 + $analytics->lain_25) / 1000000000, 2),
        'vol_hulu' => round($analytics->v_h_25, 2),
        'vol_antara' => round($analytics->v_a_25, 2),
        'vol_hilir' => round($analytics->v_l_25, 2),
        'vol_lain' => round($analytics->v_lain_25, 2)
    ],
];
        
// AMBIL TOP 5 NEGARA EKSPOR TAHUN 2025
    $topCountries = DB::table('trade_master_annual_country')
        ->where('tipe_arus', 'ekspor')
        ->select('nama_negara', DB::raw('SUM(val_2025) as total_val'))
        ->groupBy('nama_negara')
        ->orderBy('total_val', 'desc')
        ->limit(5)
        ->get()
        ->map(function ($item) {
            return [
                'name' => trim($item->nama_negara),
                'value' => round($item->total_val / 1000000, 2) // Kita pakai Juta USD agar grafik rapi
            ];
        });

// Menentukan Status Risiko per Wilayah untuk Heatmap
// Menentukan Status Risiko per Wilayah untuk Heatmap
$riskHeatmap = $topMarkets->map(function ($item) {
    $status = 'STABLE';
    $color = 'bg-emerald-500'; // Default Hijau (Aman)

    // Logika ERM: Jika nilai pasar di bawah $500M, kita beri tanda Waspada (Contoh)
    if ($item['value'] < 500) { 
        $status = 'AT RISK';
        $color = 'bg-red-500'; // Merah (Bahaya)
    } elseif ($item['value'] < 1000) {
        $status = 'WATCHLIST';
        $color = 'bg-yellow-500'; // Kuning (Peringatan)
    }

    return [
        'name' => $item['name'],
        'status' => $status,
        'color' => $color,
        'val' => $item['value']
    ];
});


        

    return Inertia::render('Analytics/DeepIntelligence', [
      'riskHeatmap'    => $riskHeatmap, 
        'topCountries' => $topMarkets,
        'industrialData' => $industrialData,
        'comparisonData' => $comparisonData,
        'regions' => $regions
    ]);
}
  
}