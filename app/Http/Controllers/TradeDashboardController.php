<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\DigitalDirectoryParticipant;
use App\Models\MarketHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Services\Trade\Analytics\TradeAnalyticsService;

class TradeDashboardController extends Controller
{
 public function __construct(

        protected TradeAnalyticsService $trade

    ) {
    }

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

            /*
|--------------------------------------------------------------------------
| National Trade Snapshot - 2025
|--------------------------------------------------------------------------
*/
            $export2025 = (float) (
                $annualTrend
                    ->firstWhere('tipe_arus', 'ekspor')
                    ?->{'2025'} ?? 0
            );

            $import2025 = (float) (
                $annualTrend
                    ->firstWhere('tipe_arus', 'impor')
                    ?->{'2025'} ?? 0
            );

            $tradeBalance2025 =
                $export2025 - $import2025;


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

      /*
|--------------------------------------------------------------------------
| User & Company Context
|--------------------------------------------------------------------------
*/

$user = auth()->user();

$company = null;

if ($user?->company_id) {
    $company = Company::query()
        ->select([
            'id',
            'nama_perusahaan',
            'membership_type',
            'status_verifikasi',
        ])
        ->find($user->company_id);
}

/*
|--------------------------------------------------------------------------
| Digital Directory Program
|--------------------------------------------------------------------------
*/

$digitalDirectoryProgram =
    DigitalDirectoryParticipant::query()
        ->where(
            'user_id',
            $user->id
        )
        ->latest()
        ->first();
        
$annualSummary = [];

foreach (range(2019, 2025) as $year) {

    $valueColumn = "val_{$year}";

    /*
    |--------------------------------------------------------------------------
    | Trade Values
    |--------------------------------------------------------------------------
    */

    $exportValue = (float) DB::table('trade_master_annual_hscode')
        ->where('tipe_arus', 'ekspor')
        ->sum($valueColumn);

    $importValue = (float) DB::table('trade_master_annual_hscode')
        ->where('tipe_arus', 'impor')
        ->sum($valueColumn);

    /*
    |--------------------------------------------------------------------------
    | ALL Trade Flow
    |--------------------------------------------------------------------------
    */

    $allCountries = DB::table('trade_master_annual_country')
        ->whereNotNull('nama_negara')
        ->whereRaw("TRIM(nama_negara) <> ''")
        ->where($valueColumn, '>', 0)
        ->distinct()
        ->count('nama_negara');

    $allHsCodes = DB::table('trade_master_annual_hscode')
        ->whereNotNull('hs_code')
        ->whereRaw("TRIM(hs_code) <> ''")
        ->where($valueColumn, '>', 0)
        ->distinct()
        ->count('hs_code');

    $allRecords = DB::table('trade_master_annual_hscode')
        ->where($valueColumn, '>', 0)
        ->count();

    /*
    |--------------------------------------------------------------------------
    | EXPORT Trade Flow
    |--------------------------------------------------------------------------
    */

    $exportCountries = DB::table('trade_master_annual_country')
        ->where('tipe_arus', 'ekspor')
        ->whereNotNull('nama_negara')
        ->whereRaw("TRIM(nama_negara) <> ''")
        ->where($valueColumn, '>', 0)
        ->distinct()
        ->count('nama_negara');

    $exportHsCodes = DB::table('trade_master_annual_hscode')
        ->where('tipe_arus', 'ekspor')
        ->whereNotNull('hs_code')
        ->whereRaw("TRIM(hs_code) <> ''")
        ->where($valueColumn, '>', 0)
        ->distinct()
        ->count('hs_code');

    $exportRecords = DB::table('trade_master_annual_hscode')
        ->where('tipe_arus', 'ekspor')
        ->where($valueColumn, '>', 0)
        ->count();

    /*
    |--------------------------------------------------------------------------
    | IMPORT Trade Flow
    |--------------------------------------------------------------------------
    */

    $importCountries = DB::table('trade_master_annual_country')
        ->where('tipe_arus', 'impor')
        ->whereNotNull('nama_negara')
        ->whereRaw("TRIM(nama_negara) <> ''")
        ->where($valueColumn, '>', 0)
        ->distinct()
        ->count('nama_negara');

    $importHsCodes = DB::table('trade_master_annual_hscode')
        ->where('tipe_arus', 'impor')
        ->whereNotNull('hs_code')
        ->whereRaw("TRIM(hs_code) <> ''")
        ->where($valueColumn, '>', 0)
        ->distinct()
        ->count('hs_code');

    $importRecords = DB::table('trade_master_annual_hscode')
        ->where('tipe_arus', 'impor')
        ->where($valueColumn, '>', 0)
        ->count();

    /*
    |--------------------------------------------------------------------------
    | Annual Analytical Summary
    |--------------------------------------------------------------------------
    */

    $annualSummary[(string) $year] = [

        'all' => [
            'exportValue' => $exportValue,
            'importValue' => $importValue,
            'tradeBalance' => $exportValue - $importValue,

            'countries' => $allCountries,
            'hsCodes' => $allHsCodes,
            'records' => $allRecords,
        ],

        'export' => [
            'exportValue' => $exportValue,
            'importValue' => null,
            'tradeBalance' => null,

            'countries' => $exportCountries,
            'hsCodes' => $exportHsCodes,
            'records' => $exportRecords,
        ],

        'import' => [
            'exportValue' => null,
            'importValue' => $importValue,
            'tradeBalance' => null,

            'countries' => $importCountries,
            'hsCodes' => $importHsCodes,
            'records' => $importRecords,
        ],
    ];
}

/*
|--------------------------------------------------------------------------
| Sector Trade Intelligence 2019-2025
|--------------------------------------------------------------------------
*/

$sectorSummary = [];

$sectors = DB::table('trade_master_annual_country')
    ->selectRaw('TRIM(produk) as sector')
    ->whereNotNull('produk')
    ->whereRaw("TRIM(produk) <> ''")
    ->distinct()
    ->orderBy('sector')
    ->pluck('sector')
    ->values();

foreach (range(2019, 2025) as $year) {

    $valueColumn = "val_{$year}";

    $sectorSummary[(string) $year] = [];

    foreach ($sectors as $sector) {

        /*
        |--------------------------------------------------------------------------
        | Sector Export
        |--------------------------------------------------------------------------
        */

        $sectorExport = (float) DB::table('trade_master_annual_country')
            ->whereRaw('TRIM(produk) = ?', [$sector])
            ->where('tipe_arus', 'ekspor')
            ->sum($valueColumn);

        /*
        |--------------------------------------------------------------------------
        | Sector Import
        |--------------------------------------------------------------------------
        */

        $sectorImport = (float) DB::table('trade_master_annual_country')
            ->whereRaw('TRIM(produk) = ?', [$sector])
            ->where('tipe_arus', 'impor')
            ->sum($valueColumn);

        /*
        |--------------------------------------------------------------------------
        | Active Countries
        |--------------------------------------------------------------------------
        */

        $allCountries = DB::table('trade_master_annual_country')
            ->whereRaw('TRIM(produk) = ?', [$sector])
            ->where($valueColumn, '>', 0)
            ->distinct()
            ->count('id_negara');

        $exportCountries = DB::table('trade_master_annual_country')
            ->whereRaw('TRIM(produk) = ?', [$sector])
            ->where('tipe_arus', 'ekspor')
            ->where($valueColumn, '>', 0)
            ->distinct()
            ->count('id_negara');

        $importCountries = DB::table('trade_master_annual_country')
            ->whereRaw('TRIM(produk) = ?', [$sector])
            ->where('tipe_arus', 'impor')
            ->where($valueColumn, '>', 0)
            ->distinct()
            ->count('id_negara');

        /*
        |--------------------------------------------------------------------------
        | Sector Summary by Trade Flow
        |--------------------------------------------------------------------------
        */

        $sectorSummary[(string) $year][$sector] = [

            'all' => [
                'exportValue' => $sectorExport,
                'importValue' => $sectorImport,
                'tradeBalance' => $sectorExport - $sectorImport,
                'countries' => $allCountries,
            ],

            'export' => [
                'exportValue' => $sectorExport,
                'importValue' => null,
                'tradeBalance' => null,
                'countries' => $exportCountries,
            ],

            'import' => [
                'exportValue' => null,
                'importValue' => $sectorImport,
                'tradeBalance' => null,
                'countries' => $importCountries,
            ],
        ];
    }
}

        
/*
|--------------------------------------------------------------------------
| Trade Intelligence Dashboard
|--------------------------------------------------------------------------
*/

$tradeDashboard = [

    /*
    |--------------------------------------------------------------------------
    | Dataset Summary
    |--------------------------------------------------------------------------
    */

    'annualSummary' => $annualSummary,

    /*
    |--------------------------------------------------------------------------
    | Sector Intelligence
    |--------------------------------------------------------------------------
    */

    'sectorSummary' => $sectorSummary,

    'sectors' => $sectors,

    'summary' => [

    /*
    |--------------------------------------------------------------------------
    | Trade Value — Latest Complete Annual Dataset
    |--------------------------------------------------------------------------
    */

    'exportValue' =>
        $export2025,

    'importValue' =>
        $import2025,

    'tradeBalance' =>
        $tradeBalance2025,

    /*
    |--------------------------------------------------------------------------
    | Dataset Coverage
    |--------------------------------------------------------------------------
    */

    'countries' =>
        DB::table('trade_master_annual_country')
            ->whereNotNull('nama_negara')
            ->whereRaw("TRIM(nama_negara) <> ''")
            ->distinct()
            ->count('nama_negara'),

    'hsCodes' =>
        DB::table('trade_master_annual_hscode')
            ->whereNotNull('hs_code')
            ->whereRaw("TRIM(hs_code) <> ''")
            ->distinct()
            ->count('hs_code'),

    'records' =>
        DB::table('trade_master_annual_hscode')
            ->count(),

    /*
    |--------------------------------------------------------------------------
    | Metadata
    |--------------------------------------------------------------------------
    */

    'lastUpdate' =>
        now()->format('d M Y'),

    'source' =>
        'Kemendag RI',

    'coverage' =>
        '2019–2025',
],

    /*
    |--------------------------------------------------------------------------
    | Annual Trade Trend
    |--------------------------------------------------------------------------
    */

    'trend' => $annualTrend,

    /*
    |--------------------------------------------------------------------------
    | Top Countries
    |--------------------------------------------------------------------------
    */

    'topCountries' =>
        DB::table('trade_master_annual_country')
            ->selectRaw("
                TRIM(nama_negara) as name,
                tipe_arus,
                SUM(val_2025) as value
            ")
            ->whereNotNull('nama_negara')
            ->groupBy(
                'nama_negara',
                'tipe_arus'
            )
            ->orderByDesc('value')
            ->limit(20)
            ->get(),

    /*
    |--------------------------------------------------------------------------
    | Top HS Codes
    |--------------------------------------------------------------------------
    */

    'topHsCodes' =>
    DB::table('trade_master_annual_hscode')
        ->selectRaw("
            hs_code as code,
            TRIM(produk) as product,
            tipe_arus,
            SUM(val_2025) as value
        ")
        ->whereNotNull('hs_code')
        ->groupBy(
            'hs_code',
            'produk',
            'tipe_arus'
        )
        ->orderByDesc('value')
        ->limit(20)
        ->get(),

/*
|--------------------------------------------------------------------------
| Filter Options
|--------------------------------------------------------------------------
*/

'filterOptions' => [

    'years' => [
        2019,
        2020,
        2021,
        2022,
        2023,
        2024,
        2025,
    ],

    'countries' =>
        DB::table('trade_master_annual_country')
            ->selectRaw("
                id_negara as code,
                TRIM(nama_negara) as name
            ")
            ->whereNotNull('nama_negara')
            ->whereRaw("TRIM(nama_negara) <> ''")
            ->groupBy(
                'id_negara',
                'nama_negara'
            )
            ->orderBy('nama_negara')
            ->get(),

    'hsCodes' =>
        DB::table('trade_master_annual_hscode')
            ->selectRaw("
                hs_code as code,
                TRIM(uraian_hs) as description
            ")
            ->whereNotNull('hs_code')
            ->whereRaw("TRIM(hs_code) <> ''")
            ->groupBy(
                'hs_code',
                'uraian_hs'
            )
            ->orderBy('hs_code')
            ->get(),
],

];
        // Kirim data ke React (Dashboard.jsx)
        return Inertia::render('Dashboard', [
            
        'tradeDashboard' => $tradeDashboard,
       
        'companyContext' => [
    'company' =>
        $company
            ? [
                'id' =>
                    $company->id,

                'name' =>
                    $company->nama_perusahaan,

                'membership_type' =>
                    $company->membership_type,

                'verification_status' =>
                    $company->status_verifikasi,
            ]
            : null,

    'program' =>
        $digitalDirectoryProgram
            ? [
                'id' =>
                    $digitalDirectoryProgram->id,

                'package' =>
                    $digitalDirectoryProgram->package,

                'payment_status' =>
                    $digitalDirectoryProgram->payment_status,

                'activation_status' =>
                    $digitalDirectoryProgram->activation_status,

                'company_passport_active' =>
                    (bool) $digitalDirectoryProgram
                        ->company_passport_active,

                'visibility_score_active' =>
                    (bool) $digitalDirectoryProgram
                        ->visibility_score_active,

                'executive_dashboard_active' =>
                    (bool) $digitalDirectoryProgram
                        ->executive_dashboard_active,

                'smart_matching_active' =>
                    (bool) $digitalDirectoryProgram
                        ->smart_matching_active,

                'build_supply_chain_active' =>
                    (bool) $digitalDirectoryProgram
                        ->build_supply_chain_active,
            ]
            : null,
],
        
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
              'tradeSummary' => $this->trade->summary(),
           'monthlyComparison' => $this->trade->monthlyComparison(),

'monthlyComparisonPieces' => $this->trade->monthlyComparisonPieces(),
              'memberStatus' => auth()->user()->is_premium ? 'Premium Member' : 'Regular Member',
            'exportValue' => $export2025,

            'importValue' => $import2025,

            'tradeBalance' => $tradeBalance2025,

            'tradeYear' => 2025, // Nanti bisa dihitung dinamis dari $annualTrend
            
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