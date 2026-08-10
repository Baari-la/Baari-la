<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;  
use App\Models\Company;
use App\Models\DigitalDirectoryParticipant;
use App\Models\CompanyUpdate;
use Illuminate\Support\Facades\DB;
use App\Models\AuditLog;
use App\Models\CompanyClaim;
use App\Models\TradeAnalyticsVertical;
use Maatwebsite\Excel\Facades\Excel; // Sesuaikan dengan library excel yang Anda gunakan (misal: Maatwebsite/Laravel-Excel)
use App\Services\Trade\Analytics\TradeAnalyticsService;
use App\Models\CompanyIdentityMediaAsset;


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

$pendingMediaModeration = CompanyIdentityMediaAsset::query()
    ->where(
        'verification_status',
        'draft'
    )
    ->count();
    
        
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

    'pendingPayments' =>
    DigitalDirectoryParticipant::where(
        'payment_status',
        'pending_verification'
    )->count(),
/*
|--------------------------------------------------------------------------
| Digital Directory Ownership Verification
|--------------------------------------------------------------------------
|
| Ownership claims yang berasal dari user yang terhubung dengan
| Digital Directory & Visibility Program.
|
*/

'pendingProgramOwnerships' =>
    CompanyClaim::query()
        ->where(
            'status',
            'pending'
        )
        ->whereIn(
            'user_id',
            DigitalDirectoryParticipant::query()
                ->whereNotNull(
                    'user_id'
                )
                ->select(
                    'user_id'
                )
        )
        ->count(),

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

    'pendingMediaModeration' => $pendingMediaModeration,
    
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

// $companyFields['status_verifikasi'] = 'verified';
// $companyFields['last_verified_at'] = now();
$companyFields['last_updated_at'] = now();
$companyFields['data_source'] = 'verified_by_admin';

$company->update($companyFields);

/*
|--------------------------------------------------------------------------
| RELATIONAL DATA
|--------------------------------------------------------------------------
*/

\App\Services\CompanyRelationalSyncService::sync(
    $company,
    $newData
);


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
) {
    /*
    |--------------------------------------------------------------------------
    | Claim Must Be Pending
    |--------------------------------------------------------------------------
    */

    if ($claim->status !== 'pending') {
        return back()->with(
            'error',
            'This ownership claim is no longer pending.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Load Claim Relationships
    |--------------------------------------------------------------------------
    */

    $claim->loadMissing([
        'user',
        'company',
        'companyIdentity',
    ]);

    if (!$claim->user) {
        return back()->with(
            'error',
            'The user associated with this ownership claim could not be found.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Determine Claim Target
    |--------------------------------------------------------------------------
    */

    $hasCanonicalIdentity =
        !empty($claim->company_identity_id);

    $hasLegacyCompany =
        !empty($claim->company_id);

    /*
    |--------------------------------------------------------------------------
    | Invalid Claim Target
    |--------------------------------------------------------------------------
    |
    | A claim must never target canonical identity and legacy company
    | simultaneously.
    |
    */

    if (
        $hasCanonicalIdentity &&
        $hasLegacyCompany
    ) {
        return back()->with(
            'error',
            'This ownership claim has an invalid company target.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Manual / Unresolved Claim
    |--------------------------------------------------------------------------
    |
    | Claims without either company_identity_id or company_id must first
    | be resolved by an administrator before ownership can be approved.
    |
    */

    if (
        !$hasCanonicalIdentity &&
        !$hasLegacyCompany
    ) {
        return back()->with(
            'error',
            'This ownership claim must be linked to a canonical company identity or legacy company before approval.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Canonical Company Identity Claim
    |--------------------------------------------------------------------------
    */

    if ($hasCanonicalIdentity) {

        $companyIdentity =
            $claim->companyIdentity;

        if (!$companyIdentity) {
            return back()->with(
                'error',
                'The linked canonical company identity could not be found.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Only READY Identity Can Be Approved
        |--------------------------------------------------------------------------
        */

        if (
            $companyIdentity->identity_status
            !== 'READY'
        ) {
            return back()->with(
                'error',
                'This canonical company identity is not currently available for ownership approval.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Identity Already Managed By Another User
        |--------------------------------------------------------------------------
        */

        $identityAlreadyManaged =
            \App\Models\User::query()
                ->where(
                    'company_identity_id',
                    $companyIdentity->id
                )
                ->where(
                    'id',
                    '!=',
                    $claim->user_id
                )
                ->exists();

        if ($identityAlreadyManaged) {
            return back()->with(
                'error',
                'This canonical company identity is already managed by another user.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | User Already Manages Another Company
        |--------------------------------------------------------------------------
        */

        if (
            $claim->user->company_identity_id &&
            (int) $claim->user->company_identity_id
                !== (int) $companyIdentity->id
        ) {
            return back()->with(
                'error',
                'This user already manages another canonical company identity.'
            );
        }

        if ($claim->user->company_id) {
            return back()->with(
                'error',
                'This user already manages a legacy company.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Approve Canonical Claim
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $claim,
            $companyIdentity
        ) {
            /*
            |--------------------------------------------------------------------------
            | Connect User To Canonical Identity
            |--------------------------------------------------------------------------
            */

            $claim->user->update([
                'company_identity_id' =>
                    $companyIdentity->id,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Mark Canonical Identity Verified
            |--------------------------------------------------------------------------
            */

            $companyIdentity->update([
                'verification_status' =>
                    'verified',

                'verified_at' =>
                    now(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | Approve Current Claim
            |--------------------------------------------------------------------------
            */

            $claim->update([
                'status' =>
                    'approved',

                'reviewed_at' =>
                    now(),

                'reviewed_by' =>
                    auth()->id(),

                'rejection_reason' =>
                    null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Reject Other Pending Claims For Same Canonical Identity
            |--------------------------------------------------------------------------
            */

            CompanyClaim::query()
                ->where(
                    'company_identity_id',
                    $companyIdentity->id
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

                    'reviewed_by' =>
                        auth()->id(),

                    'rejection_reason' =>
                        'Another ownership claim for this canonical company identity was approved.',
                ]);

            /*
            |--------------------------------------------------------------------------
            | NOTE
            |--------------------------------------------------------------------------
            |
            | Legacy companies are intentionally NOT modified here.
            |
            | company_id remains NULL on the user.
            |
            | DigitalDirectoryParticipant and AuditLog canonical integration
            | will be migrated separately after canonical ownership is stable.
            |
            */
        });

        return back()->with(
            'message',
            'Canonical company ownership claim approved.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Legacy Company Claim
    |--------------------------------------------------------------------------
    |
    | Existing behavior is preserved for compatibility.
    |
    */

    $company =
        $claim->company;

    if (!$company) {
        return back()->with(
            'error',
            'The linked company could not be found.'
        );
    }

    if ($company->claimed_by_user_id) {
        return back()->with(
            'error',
            'Company already claimed.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | User Already Manages Another Company
    |--------------------------------------------------------------------------
    */

    if ($claim->user->company_identity_id) {
        return back()->with(
            'error',
            'This user already manages a canonical company identity.'
        );
    }

    if (
        $claim->user->company_id &&
        (int) $claim->user->company_id
            !== (int) $company->id
    ) {
        return back()->with(
            'error',
            'This user already manages another company.'
        );
    }

    DB::transaction(function () use (
        $claim,
        $company
    ) {
        /*
        |--------------------------------------------------------------------------
        | Update Legacy Company
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | Connect User To Legacy Company
        |--------------------------------------------------------------------------
        */

        $claim->user->update([
            'company_id' =>
                $company->id,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Link Digital Directory Participant
        |--------------------------------------------------------------------------
        */

        DigitalDirectoryParticipant::query()
            ->where(
                'user_id',
                $claim->user_id
            )
            ->whereNull(
                'company_id'
            )
            ->update([
                'company_id' =>
                    $company->id,
            ]);

        /*
        |--------------------------------------------------------------------------
        | Approve Current Claim
        |--------------------------------------------------------------------------
        */

        $claim->update([
            'status' =>
                'approved',

            'reviewed_at' =>
                now(),

            'reviewed_by' =>
                auth()->id(),

            'rejection_reason' =>
                null,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Reject Other Pending Claims For Same Legacy Company
        |--------------------------------------------------------------------------
        */

        CompanyClaim::query()
            ->where(
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

                'reviewed_by' =>
                    auth()->id(),

                'rejection_reason' =>
                    'Another ownership claim for this company was approved.',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Legacy Audit Log
        |--------------------------------------------------------------------------
        */

        AuditLog::create([
            'user_id' =>
                auth()->id(),

            'company_id' =>
                $company->id,

            'action' =>
                'approved',

            'details' =>
                'Company claim approved.',
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

    'reviewed_by' =>
        auth()->id(),
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

public function mediaModeration()
{
    $media = CompanyIdentityMediaAsset::query()
        ->where(
            'company_identity_media_assets.verification_status',
            'draft'
        )
        ->with('companyIdentity')
        ->latest('company_identity_media_assets.id')
        ->get()
        ->map(function ($asset) {

            $company = Company::query()
                ->where(
                    'company_identity_id',
                    $asset->company_identity_id
                )
                ->orderBy('id')
                ->first();

            return [
                'id' => $asset->id,

                'company_id' =>
                    $company?->id,

                'nama_perusahaan' =>
                    $company?->nama_perusahaan,

                'canonical_name' =>
                    $asset->companyIdentity?->canonical_name,

                'media_type' =>
                    $asset->media_type,

                'image_url' =>
                    $asset->file_path
                        ? url(
                            '/storage/' .
                            ltrim(
                                $asset->file_path,
                                '/'
                            )
                        )
                        : $asset->file_url,

                'image_path' =>
                    $asset->file_path,

                'title' =>
                    $asset->title,

                'caption' =>
                    $asset->caption,

                'is_featured' =>
                    (bool) $asset->is_featured,

                'sort_order' =>
                    $asset->sort_order,

                'verification_status' =>
                    $asset->verification_status,
            ];
        })
        ->values();

    return Inertia::render(
        'Admin/MediaModeration',
        [
            'media' => $media,
        ]
    );
}
}