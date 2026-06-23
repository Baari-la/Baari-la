<?php

namespace App\Http\Controllers;
use App\Models\Company;
use App\Models\MarketHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class TradeDashboardController extends Controller
{
    public function index()
    {

// Mapping kategori sesuai dengan isi kolom 'produk' di tabel Anda
    $categories = [
        'Garment'  => 'Pakaian Jadi (Garmen)',
        'Yarn'     => 'Benang',
        'Fabric'   => 'Kain',
        'Fiber'    => 'Serat Kapas dan serat alam lainnya',
        'Synthetic'=> 'Serat sintetik',
        'Various'  => 'Berbagai produk teksti' // Sesuaikan typo 'teksti' jika memang begitu di DB
    ];

    
$topDestinations = [];

foreach ($categories as $key => $productName) {
    // Kita bungkus ekspor dan impor dalam satu array per kategori
    $topDestinations[$key] = [
        'export' => DB::table('trade_master_annual_country')
            ->selectRaw("TRIM(nama_negara) as name, SUM(val_2025) as value")
            ->where('produk', 'LIKE', '%' . $productName . '%')
            ->where('tipe_arus', 'ekspor')
            ->groupBy('nama_negara')
            ->orderBy('value', 'desc')
            ->take(5)
            ->get(),

        'import' => DB::table('trade_master_annual_country')
            ->selectRaw("TRIM(nama_negara) as name, SUM(val_2025) as value")
            ->where('produk', 'LIKE', '%' . $productName . '%')
            ->where('tipe_arus', 'impor')
            ->groupBy('nama_negara')
            ->orderBy('value', 'desc')
            ->take(5)
            ->get(),
    ];
}



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
        
  // 🚢 KOREKSI KUERI: Menarik seluruh data log kontainer pelabuhan terbaru dari database tanpa batasan filter waktu lampau
        $portLogs = \Illuminate\Support\Facades\DB::table('port_container_logs')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

         $portLogs = \Illuminate\Support\Facades\DB::connection('mysql')
            ->table('port_container_logs')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // 🧠 MEMANGGIL FUNGSI REKAPITULASI DATA EWS INTERNAL
        $ewsController = new \App\Http\Controllers\Api\PortTrackerController();
        $ewsData = $ewsController->getLiveEwsStatus()->getData();

    // Jumlah perusahaan di Directory

      $totalCompanies = Company::count();
        
        // Kirim data ke React (Dashboard.jsx)
        return Inertia::render('Dashboard', [
        // Jumlah perusahaan di direktory
         'totalCompanies' => $totalCompanies,   
        // Data Analitik Perdagangan
            'annualTrend' => $annualTrend,
            // 'marketHistory' => $marketHistory,
            'monthlyCompare' => $monthlyCompare,
            'garmenSpecs' => $garmenSpecs,
            'fullTradeData' => $fullTradeData,
             'topDestinations' => $topDestinations,
              'fiberIntelligence' => $this->getFiberIntelligence(), // Panggil fungsi baru di sini
            
            // Data Market Intelligence (Sesuai props Dashboard.jsx)
             'marketHistory' => $history->map(fn($item) => [
            'month' => date('d M', strtotime($item->date)),
            'price' => (float)$item->cotton_price,
            'rate' => (float)$item->usd_idr,
        ]),
            'cottonPrice' => $latestMarket->cotton_price ?? 71.31,
            'cottonTrend' => round($cottonChange, 2) . '%',
            'usd_idr' => $latestMarket->usd_idr ?? 16000,
             'containerLogs' => $portLogs, // 🌟 DATA TERBARU DIALIRKAN KE DASHBOARD.JSX
            'ewsLiveAlerts' => $ewsData, 
             // 'cottonPrice' => $latest->cotton_price ?? 0,
        // 'usd_idr' => $latest->usd_idr ?? 0,
            // Data Pendukung
            //  'annualTrend' => $this->getAnnualTrendData(), 
            'memberStatus' => auth()->user()->is_premium ? 'Premium Member' : 'Regular Member',
            'exportValue' => '11.9', // Nanti bisa dihitung dinamis dari $annualTrend
            'lastUpdate' => now()->format('d M Y')
        ]);
    }
private function getFiberIntelligence() 
{
    $years = ['2019', '2020', '2021', '2022', '2023', '2024', '2025'];
    $trendData = [];
    $prevCotton = 0;
    $prevSyn = 0;

    foreach ($years as $year) {
        $cotton = (float) DB::table('trade_master_annual_hscode')
            ->where('hs_code', 'LIKE', '5201%')
            ->where('tipe_arus', 'impor')
            ->sum("vol_$year");

        $synthetic = (float) DB::table('trade_master_annual_hscode')
            ->where(function($q) {
                $q->where('hs_code', 'LIKE', '5402%')->orWhere('hs_code', 'LIKE', '5503%');
            })->where('tipe_arus', 'impor')
            ->sum("vol_$year");

        // Hitung selisih persen dibanding tahun sebelumnya
        $cottonGrowth = ($prevCotton > 0) ? (($cotton - $prevCotton) / $prevCotton) * 100 : 0;
        $synGrowth = ($prevSyn > 0) ? (($synthetic - $prevSyn) / $prevSyn) * 100 : 0;

        $trendData[] = [
            'year' => $year,
            'cotton' => $cotton,
            'synthetic' => $synthetic,
            'cotton_growth' => round($cottonGrowth, 1),
            'syn_growth' => round($synGrowth, 1),
        ];

        $prevCotton = $cotton;
        $prevSyn = $synthetic;
    }

    return $trendData;
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