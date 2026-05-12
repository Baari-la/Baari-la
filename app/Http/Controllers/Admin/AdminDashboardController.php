<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;  
use App\Models\Company;
use App\Models\CompanyUpdate;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\AuditLog;


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
    'stats' => [
        'total_companies' => $totalCompanies,
        'gold_members'    => Company::where('membership_type', 'gold_member')->count(),
        'premium_requests' => DB::table('premium_requests')->where('status', 'pending')->count(),
        // Tambahkan hitungan angka untuk badge notifikasi
        'pending_updates_count' => \App\Models\CompanyUpdate::where('status', 'pending')->count(),
    ],
    
    // Data list untuk tabel audit admin
    'pendingUpdates'  => \App\Models\CompanyUpdate::with(['company', 'user'])
                        ->where('status', 'pending')
                        ->latest()
                        ->get(),

    'recentCompanies' => Company::latest()->take(10)->get(),
    'stockOverview'   => $stockOverview,
    'healthStats'     => $healthStats,
    'industrialData'  => $industrialData, // Data hulu-hilir
    'pendingCount'    => Company::where('status_verifikasi', 'pending')->count(), // Registrasi baru
]);


    }

// Menyetujui dan memindahkan data ke tabel utama
public function approveUpdate($id)
{
     $update = \App\Models\CompanyUpdate::findOrFail($id);
    $company = \App\Models\Company::findOrFail($update->company_id);

    // 1. Ambil data usulan dari JSON
    $newData = json_decode($update->proposed_data, true);

    // 2. TAMBAHKAN BARIS INI: Paksa status di tabel utama menjadi verified
    $company->status_verifikasi = 'verified'; 

    // 3. Timpa data lainnya
    $company->update($newData);

    // 4. Ubah status antrean agar hilang dari daftar "Pending"
    $update->update(['status' => 'approved']);

    
    // 4. Catat ke Audit Log untuk akuntabilitas
    \App\Models\AuditLog::create([
        'user_id' => auth()->id(),
        'company_id' => $company->id,
        'action' => 'approved',
        'details' => 'Admin menyetujui pemutakhiran data mandiri perusahaan.'
    ]);

    return back()->with('message', 'Audit Berhasil: Data perusahaan telah diperbarui secara live.');
}


// Menolak usulan perubahan
public function rejectUpdate(\App\Models\CompanyUpdate $update)
{
    $update->update(['status' => 'rejected']);
    return back()->with('message', 'Usulan perubahan data telah ditolak.');
}


}