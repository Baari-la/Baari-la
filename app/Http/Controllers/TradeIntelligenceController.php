<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class TradeIntelligenceController extends Controller
{
  public function index(Request $request)
{
//   Marchmaking 
$category = $request->input('category');
    $region = $request->input('region');

    $query = \DB::table('partnerships');

    // Filter Kategori Multi-Sektor
    if ($category) {
        $query->where('category', $category);
    }

    // Filter Geografis Wilayah
    if ($region) {
        $query->where('region', $region);
    }

    $partners = $query->orderBy('match_percentage', 'desc')->get();


        // 1. Ambil Data Jenis Produk dari tabel trade_master_annual_hscode
        // Kita petakan sesuai sektor: Serat, Benang, Kain, Garmen, Home Textile
        $topTrade = DB::table('trade_master_annual_hscode')
            ->selectRaw('
                CASE 
                    WHEN hscode LIKE "52%" THEN "Serat Kapas"
                    WHEN hscode LIKE "54%" OR hscode LIKE "55%" THEN "Serat Sintetis"
                    WHEN hscode BETWEEN "5204" AND "5207" OR hscode BETWEEN "5401" AND "5406" THEN "Benang"
                    WHEN hscode LIKE "5208%" OR hscode LIKE "5212%" OR hscode LIKE "5407%" THEN "Kain"
                    WHEN hscode LIKE "61%" OR hscode LIKE "62%" THEN "Garmen"
                    WHEN hscode LIKE "63%" THEN "Home Textile"
                    ELSE "Produk Tekstil Lainnya"
                END as name,
                SUM(nilai_ekspor_2025) as value
            ')
            ->groupBy('name')
            ->orderBy('value', 'desc')
            ->get()
            ->map(function($item) {
                // Standar Industri: Garmen pakai Pcs, lainnya Kg
                $item->unit = ($item->name === 'Garmen') ? 'Pcs' : 'Kg';
                return $item;
            });

        // 2. Ambil Data Negara Tujuan dari tabel trade_master_annual_country
        $topCountries = DB::table('trade_master_annual_country')
            ->selectRaw('negara_tujuan as name, SUM(nilai_ekspor_2025) as value')
            ->groupBy('negara_tujuan')
            ->orderBy('value', 'desc')
            ->take(5)
            ->get();

        // 3. Ambil Tren Tahunan (2021-2025) untuk grafik utama
        $yearlyTrends = DB::table('trade_master_annual_hscode')
            ->selectRaw('
                SUM(nilai_ekspor_2021) as "2021", 
                SUM(nilai_ekspor_2022) as "2022", 
                SUM(nilai_ekspor_2023) as "2023", 
                SUM(nilai_ekspor_2024) as "2024", 
                SUM(nilai_ekspor_2025) as "2025"
            ')->first();

        return Inertia::render('Trade/Radar', [
            'topTrade' => $topTrade,
            'topCountries' => $topCountries,
            'yearlyTrends' => $yearlyTrends,
            'hscodes' => \App\Models\HsCode::take(10)->get(),
            'partners' => $partners,
        'filters' => $request->only(['category', 'region'])
        ]);
    }

    /* PERBAIKAN 1: Menyelaraskan kueri index bursa bahan agar mendukung filter pencarian */
    public function indexInventory(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');

        $query = DB::table('inventories');

        if ($search) {
            $query->where('name', 'LIKE', "%$search%");
        }

        if ($category) {
            $query->where('category', $category);
        }

        $items = $query->orderBy('created_at', 'desc')->get();

        return Inertia::render('Inventory/Index', [
            'inventories' => $items, // Mengirim data bursa bahan baku dinamis
            'filters' => $request->only(['search', 'category'])
        ]);
    }

    public function create()
    {
        return Inertia::render('Inventory/Create'); // Membuka form tambah bahan
    }

    /* PERBAIKAN 2: Menyelaraskan logika penyimpanan data pengisi material baru */
    public function storeInventory(Request $request)
    {
        // 1. Validasi Input sesuai format kolom database asli Bapak
        $request->validate([
            'name' => 'required|min:3',
            'category' => 'required',
            'stock' => 'required|numeric',
            'unit' => 'required',
            'warehouse_location' => 'required',
            'whatsapp_contact' => 'required|numeric',
        ]);

        // 2. Ambil data profil user untuk mendapatkan company_id pengunggah
        $user = auth()->user();
 $company = \DB::table('companies')->where('id', $user->company_id)->first();

    if (!$company) {
        return redirect()->back()->with('error', 'Profil perusahaan Anda tidak ditemukan.');
    }

    // 3. ATURAN BISNIS: Cek Hak Akses Unggah Toko Digital
    $isApiMember = (bool) $company->is_api_member;
    $hasActiveRental = $company->rental_expires_at && \Carbon\Carbon::parse($company->rental_expires_at)->isFuture();

    // Jika BUKAN Anggota API DAN masa sewa bulanan sudah habis/tidak ada
    if (!$isApiMember && !$hasActiveRental) {
        return redirect()->back()->with('error', '⚠️ Akses Ditolak: Anda harus menjadi Anggota API atau memperpanjang sewa bulanan Toko Digital.');
    }

    // 4. Jalankan Penyimpanan jika Lolos Validasi Aturan Bisnis
    
        DB::table('inventories')->insert([
            'name' => $request->name,
            'category' => $request->category,
            'stock' => $request->stock,
            'unit' => $request->unit,
            'warehouse_location' => $request->warehouse_location,
            'whatsapp_contact' => $request->whatsapp_contact,
            'description' => $request->description,
            'price' => $request->price ?? 0.00,
             'nama_perusahaan' => $company->company_name, 
            'company_id' => $user->company_id ?? 1, // Otomatis mencatat ID perusahaan pemilik barang
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('inventory.index')->with('message', 'Produk berhasil dipajang di Toko Digital!');
    }
}