<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;  
use App\Models\Company;
use App\Models\CompanyUpdate;
use Illuminate\Support\Facades\DB;
use App\Models\AuditLog;
use App\Models\CompanyClaim;
use App\Models\TradeAnalyticsVertical;
use Maatwebsite\Excel\Facades\Excel; // Sesuaikan dengan library excel yang Anda gunakan (misal: Maatwebsite/Laravel-Excel)
use App\Services\Trade\Analytics\TradeAnalyticsService;


class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Query Big Data Aggregation (Hulu, Antara, Hilir)
        $analytics = DB::table('trade_analytics_annual as t')
            ->join('mst_hscode as h', 't.id_hs', '=', 'h.id_hs')
            ->select(
                // Data 2024 (Value & Volume)
                DB::raw("SUM(CASE WHEN h.id_hs = 1 THEN t.val_2024 ELSE 0 END) / 1000000000 as hulu_24"),
                DB::raw("SUM(CASE WHEN h.id_hs IN (2, 3) THEN t.val_2024 ELSE 0 END) / 1000000000 as antara_24"),
                DB::raw("SUM(CASE WHEN h.id_hs = 4 THEN t.val_2024 ELSE 0 END) / 1000000000 as hilir_24"),
                DB::raw("SUM(CASE WHEN h.id_hs = 1 THEN t.vol_2024 ELSE 0 END) / 1000000 as vol_hulu_24"),
                DB::raw("SUM(CASE WHEN h.id_hs IN (2, 3) THEN t.vol_2024 ELSE 0 END) / 1000000 as vol_antara_24"),
                DB::raw("SUM(CASE WHEN h.id_hs = 4 THEN t.vol_2024 ELSE 0 END) / 1000000 as vol_hilir_24"),

                // Data 2025 (Menggunakan kolom jandes_2025)
                DB::raw("SUM(CASE WHEN h.id_hs = 1 THEN t.val_jandes_2025 ELSE 0 END) / 1000000000 as hulu_25"),
                DB::raw("SUM(CASE WHEN h.id_hs IN (2, 3) THEN t.val_jandes_2025 ELSE 0 END) / 1000000000 as antara_25"),
                DB::raw("SUM(CASE WHEN h.id_hs = 4 THEN t.val_jandes_2025 ELSE 0 END) / 1000000000 as hilir_25"),
                DB::raw("SUM(CASE WHEN h.id_hs = 1 THEN t.vol_jandes_2025 ELSE 0 END) / 1000000 as vol_hulu_25"),
                DB::raw("SUM(CASE WHEN h.id_hs IN (2, 3) THEN t.vol_jandes_2025 ELSE 0 END) / 1000000 as vol_antara_25"),
                DB::raw("SUM(CASE WHEN h.id_hs = 4 THEN t.vol_jandes_2025 ELSE 0 END) / 1000000 as vol_hilir_25")
            )
            ->first();

        // 2. Susun Data untuk Grafik React
        $industrialData = [
            [
                'year' => '2024', 
                'hulu' => round($analytics->hulu_24 ?? 0, 2), 
                'antara' => round($analytics->antara_24 ?? 0, 2), 
                'hilir' => round($analytics->hilir_24 ?? 0, 2),
                'vol_hulu' => round($analytics->vol_hulu_24 ?? 0, 2),
                'vol_antara' => round($analytics->vol_antara_24 ?? 0, 2),
                'vol_hilir' => round($analytics->vol_hilir_24 ?? 0, 2)
            ],
            [
                'year' => '2025', 
                'hulu' => round($analytics->hulu_25 ?? 0, 2), 
                'antara' => round($analytics->antara_25 ?? 0, 2), 
                'hilir' => round($analytics->hilir_25 ?? 0, 2),
                'vol_hulu' => round($analytics->vol_hulu_25 ?? 0, 2),
                'vol_antara' => round($analytics->vol_antara_25 ?? 0, 2),
                'vol_hilir' => round($analytics->vol_hilir_25 ?? 0, 2)
            ],
        ];

        // 3. Hitung Health Stats (Radar Kesegaran Data)
        $totalCompanies = Company::count();
        $healthStats = [
            'active'   => Company::where('last_verified_at', '>', now()->subMonths(11))->count(),
            'expiring' => Company::where('last_verified_at', '<=', now()->subMonths(11))
                          ->where('last_verified_at', '>', now()->subMonths(12))->count(),
            'total'    => $totalCompanies ?: 1,
        ];
        
        $expired = $totalCompanies - ($healthStats['active'] + $healthStats['expiring']);
        $healthStats['expired'] = $expired; // Tambahkan untuk kelengkapan data dashboard

 
   $stockOverview = \DB::table('companies')
        ->where('stock_qty', '>', 0)
        ->selectRaw('
            stock_ready_caption as product_name, 
            SUM(stock_qty) as total_qty, 
            AVG(price) as avg_price, 
            stock_unit as unit,
            COUNT(id) as total_suppliers
        ')
        ->groupBy('product_name', 'unit')
        ->orderBy('total_qty', 'desc')
        ->get();

        
        // 4. Render ke Inertia
   return Inertia::render('Admin/Dashboard', [

    /*
    |--------------------------------------------------------------------------
    | Stats
    |--------------------------------------------------------------------------
    */

    'stats' => [

        'total_companies' =>
            $totalCompanies,

        'gold_members' =>
            Company::where(
                'membership_type',
                'gold_member'
            )->count(),

        'premium_requests' =>
            DB::table(
                'premium_requests'
            )
            ->where(
                'status',
                'pending'
            )
            ->count(),

        'pending_updates_count' =>
            CompanyUpdate::where(
                'status',
                'pending'
            )->count(),

        'pending_claims_count' =>
            CompanyClaim::where(
                'status',
                'pending'
            )->count(),
    ],

    /*
    |--------------------------------------------------------------------------
    | Dashboard Counts
    |--------------------------------------------------------------------------
    */

    'pendingPayments' => 0,

    'pendingVerifications' =>
        Company::where(
            'status_verifikasi',
            'pending'
        )->count(),

    'pendingUpdatesCount' =>
        CompanyUpdate::where(
            'status',
            'pending'
        )->count(),

    'pendingClaimsCount' =>
        CompanyClaim::where(
            'status',
            'pending'
        )->count(),

    'supplyChainRequests' => 0,

    /*
    |--------------------------------------------------------------------------
    | Lists
    |--------------------------------------------------------------------------
    */

    'pendingUpdates' =>
        CompanyUpdate::with([
            'company',
            'user'
        ])
        ->where(
            'status',
            'pending'
        )
        ->latest()
        ->get(),

    'pendingClaims' =>
        CompanyClaim::with([
            'company',
            'user'
        ])
        ->where(
            'status',
            'pending'
        )
        ->latest()
        ->get(),

    /*
    |--------------------------------------------------------------------------
    | Dashboard Data
    |--------------------------------------------------------------------------
    */

    'recentCompanies' =>
        Company::latest()
            ->take(10)
            ->get(),

    'stockOverview' =>
        $stockOverview,

    'healthStats' =>
        $healthStats,

    'industrialData' =>
        $industrialData,

    'pendingCount' =>
        Company::where(
            'status_verifikasi',
            'pending'
        )->count(),

    'tradeDashboard' =>
        $this->trade->dashboard(),

]);


    }

// Menyetujui dan memindahkan data ke tabel utama
public function approveUpdate($id)
{
    $update = CompanyUpdate::findOrFail($id);
// dd([
//     'raw_proposed_data' => $update->proposed_data,
// ]);
    DB::transaction(function () use ($update) {

        $company = Company::findOrFail(
            $update->company_id
        );

        $newData = $update->proposed_data;

        /*
        |--------------------------------------------------------------------------
        | JSON STRING → ARRAY
        |--------------------------------------------------------------------------
        */

        if (is_string($newData)) {

            $newData = json_decode(
                $newData,
                true
            );
        }

        /*
        |--------------------------------------------------------------------------
        | RELATIONAL DATA
        |--------------------------------------------------------------------------
        */

        /*
|--------------------------------------------------------------------------
| UPDATE COMPANY MASTER DATA
|--------------------------------------------------------------------------
*/

$companyFields = collect($newData)
    ->except([
        'products',
        'images',
        'markets',
        'certifications',
        'contacts',
        'links',
        'capacities',
        'machines',
        'moqs',
        'locations',
        'lead_times',
    ])
    ->toArray();

$companyFields['status_verifikasi'] = 'verified';
$companyFields['last_verified_at'] = now();
$companyFields['last_updated_at'] = now();
$companyFields['data_source'] = 'verified_by_admin';

$company->update($companyFields);
// dd($newData['capacities'] ?? 'NO CAPACITIES');
/*
|--------------------------------------------------------------------------
| RELATIONAL DATA
|--------------------------------------------------------------------------
*/

\App\Services\CompanyRelationalSyncService::sync(
    $company,
    $newData
);

logger('RELATIONAL SYNC EXECUTED', [
    'company_id' => $company->id,
    'machines_count' => count($newData['machines'] ?? []),
]);

        $update->update([

            'status' => 'approved',

            'approved_by' => auth()->id(),

            'approved_at' => now(),
        ]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'company_id' => $company->id,
            'action' => 'approved',
            'details' => 'Company update approved.',
        ]);
    });

    return back()->with(
        'message',
        'Audit Berhasil: Data perusahaan telah diperbarui secara live.'
    );
}

protected TradeAnalyticsService $trade;

public function __construct(
    TradeAnalyticsService $trade
) {
    $this->trade = $trade;
}

public function approveClaim(
    CompanyClaim $claim
)
{
    $company = $claim->company;

    if (
        $company->claimed_by_user_id
    ) {

        return back()->with(
            'error',
            'Company already claimed.'
        );
    }

    DB::transaction(function () use (
        $claim,
        $company
    ) {

        $company->update([

            'claimed_by_user_id' =>
                $claim->user_id,

            'data_source' =>
                'company_updated',

            'status_verifikasi' =>
                'verified',

            'last_updated_at' =>
                now(),

            'last_verified_at' =>
                now(),
        ]);

        $claim->user->update([

            'company_id' =>
                $company->id,
        ]);

        $claim->update([

            'status' =>
                'approved',

            'reviewed_at' =>
                now(),
        ]);

        CompanyClaim::where(
            'company_id',
            $company->id
        )
        ->where(
            'id',
            '!=',
            $claim->id
        )
        ->where(
            'status',
            'pending'
        )
        ->update([

            'status' =>
                'rejected',

            'reviewed_at' =>
                now(),
        ]);

        AuditLog::create([
    'user_id' => auth()->id(),
    'company_id' => $company->id,
    'action' => 'approved',
    'details' => 'Company claim approved.'
]);
    });

    return back()->with(
        'message',
        'Company claim approved.'
    );
}

// Menolak usulan perubahan
public function rejectUpdate(
    CompanyUpdate $update
)
{
    DB::transaction(function () use (
        $update
    ) {

        $update->update([

            'status' =>
                'rejected',

            'approved_by' =>
                auth()->id(),

            'approved_at' =>
                now(),
        ]);

        AuditLog::create([
    'user_id' => auth()->id(),
    'company_id' => $update->company_id,
    'action' => 'rejected',
    'details' => 'Company update rejected.'
]);
    });

    return back()->with(
        'message',
        'Usulan perubahan data telah ditolak.'
    );
}
public function rejectClaim(
    CompanyClaim $claim
)
{
    DB::transaction(function () use (
        $claim
    ) {

        $claim->update([

            'status' =>
                'rejected',

            'reviewed_at' =>
                now(),
        ]);

        AuditLog::create([
    'user_id' => auth()->id(),
    'company_id' => $claim->company_id,
    'action' => 'rejected',
    'details' => 'Company claim rejected.'
]);
    });

    return back()->with(
        'message',
        'Company claim rejected.'
    );
}

public function importDataKemendag(Request $request)
{
    $file = $request->file('file_excel');
    $tahunTarget = $request->input('tahun'); // Misal admin input: 2026 lewat form

    // Membaca data excel menjadi array
    $dataArray = Excel::toArray([], $file)[0];
    $header = array_shift($dataArray); // Mengambil baris pertama sebagai susunan header

    foreach ($dataArray as $row) {
    // Abaikan jika baris kosong atau jumlah kolom tidak sinkron dengan header
    if (count($header) !== count($row)) {
        continue; 
    }
        $rowData = array_combine($header, $row);

        // Loop untuk 12 Bulan (1 = Jan, 12 = Des)
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            // Membuat nama kolom dinamis sesuai format header excel admin
            // Contoh: val_2026_01, vol_2026_01
            $padBulan = str_pad($bulan, 2, '0', STR_PAD_LEFT); 
            $kolomNilai = "val_{$tahunTarget}_{$padBulan}";
            $kolomVolume = "vol_{$tahunTarget}_{$padBulan}";

            // Ambil nilainya dari excel
            $nilaiUsd = $rowData[$kolomNilai] ?? 0;
            $volumeKg = $rowData[$kolomVolume] ?? 0;

            // Masukkan ke database vertikal hanya jika ada transaksi (> 0)
            if ($nilaiUsd > 0 || $volumeKg > 0) {
                TradeAnalyticsVertical::create([
                    'tipe_arus'   => $rowData['tipe_arus'] ?? 'ekspor',
                    'dimensi'     => 'country',
                    'produk'      => $rowData['produk'],
                    'hs'          => $rowData['hs'],
                    'uraian_hs'   => $rowData['uraian_hs'],
                    'kode_negara' => $rowData['kode_negara'],
                    'nama_negara' => $rowData['nama_negara'],
                    'tahun'       => $tahunTarget,
                    'bulan'       => $bulan,
                    'value_usd'   => $nilaiUsd,
                    'weight_kg'   => $volumeKg,
                ]);
            }
        }
    }

   return Inertia::render('Admin/ImportKemendag');
}
}