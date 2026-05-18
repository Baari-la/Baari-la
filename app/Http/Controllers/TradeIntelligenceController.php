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
    // Membuka form tambah bahan baku di folder resources/js/Pages/Inventory/Create.jsx
    return inertia('Inventory/Create', [
        'isLoggedIn' => auth()->check(),
        'company'    => auth()->user() ? \DB::table('companies')->where('id', auth()->user()->company_id)->first() : null
    ]);
}

// Tambahkan fungsi ini di dalam class TradeIntelligenceController

public function editInventory($id)
{
   $item = \DB::table('inventories')->where('id', $id)->first();
    if (!$item) abort(404);

    // PROTEKSI: Tolak jika bukan admin DAN company_id tidak cocok
    if (auth()->user()->role !== 'admin' && auth()->user()->company_id !== $item->company_id) {
        abort(403, 'Anda tidak memiliki hak akses untuk mengubah produk ini.');
    }
    // Lempar data ke file view resources/js/Pages/Inventory/Edit.jsx
    return Inertia::render('Inventory/Edit', [
        'item' => $item,
        'isLoggedIn' => auth()->check()
    ]);
}

// 1. FUNGSI EDIT UNTUK MODAL 2: PUSAT DATA & REGULASI
public function editRegulation($id)
{
    $regulation = \DB::table('regulations')->where('id', $id)->first();

    if (!$regulation) {
        abort(404, 'Data regulasi/materi tidak ditemukan.');
    }

    return Inertia::render('Regulation/Edit', [
        'regulation' => $regulation,
        'isLoggedIn' => auth()->check()
    ]);
}

// 2. FUNGSI EDIT UNTUK MODAL 3: MATCHMAKING KEMITRAAN B2B
public function editMatchmaking($id)
{
    $partnership = \DB::table('partnerships')->where('id', $id)->first();

    if (!$partnership) {
        abort(404, 'Data kemitraan/vendor tidak ditemukan.');
   
    // PROTEKSI: Tolak jika bukan admin DAN company_id tidak cocok
    if (auth()->user()->role !== 'admin' && auth()->user()->company_id !== $partnership->company_id) {
        abort(403, 'Anda tidak memiliki hak akses untuk mengubah profil kemitraan ini.');
    }

   
        }

    return Inertia::render('Matchmaking/Edit', [
        'partnership' => $partnership,
        'isLoggedIn' => auth()->check()
    ]);
}

  
   
   public function createMatchmaking()
{
    return Inertia::render('Matchmaking/Create', [
        'isLoggedIn' => auth()->check(),
        'company'    => auth()->user() ? \DB::table('companies')->where('id', auth()->user()->company_id)->first() : null
    ]);
}

public function storeMatchmaking(Request $request)
{
    // 1. Validasi Input Spesifikasi Lini Mesin
    $request->validate([
        'jenis_mesin' => 'required|min:3',
        'kategori_proses' => 'required',
        'kapasitas_bulanan' => 'required|numeric',
        'satuan' => 'required',
        'lokasi_pabrik' => 'required',
        'whatsapp_contact' => 'required|numeric',
    ]);

    $user = auth()->user();
    $company = \DB::table('companies')->where('id', $user->company_id)->first();

    // 2. Suntik Data Langsung ke Tabel matchmakings Kontrol Riil Anda
    \DB::table('partnerships')->insert([
        'nama_perusahaan' => $company->company_name ?? 'PT. Vendor Tekstil',
        'jenis_mesin' => $request->jenis_mesin,
        'kategori_proses' => $request->kategori_proses,
        'kapasitas_bulanan' => $request->kapasitas_bulanan,
        'satuan' => $request->satuan,
        'sertifikasi' => $request->sertifikasi,
        'lokasi_pabrik' => $request->lokasi_pabrik,
        'whatsapp_contact' => $request->whatsapp_contact,
        'spesifikasi_mesin' => $request->spesifikasi_mesin,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    return redirect()->route('home')->with('message', 'Kapasitas lini mesin pabrik berhasil didaftarkan!');
}


    
    public function storeInventory(Request $request)
{
    // 1. Validasi Input Produk + Validasi Gambar (Maksimal 2MB)
    $request->validate([
        'name' => 'required|min:3',
        'name_en' => 'nullable|min:3',
        'category' => 'required',
        'stock' => 'required|numeric',
        'unit' => 'required',
        'warehouse_location' => 'required',
        'whatsapp_contact' => 'required|numeric',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Batasan file gambar
         'brochure' => 'nullable|mimes:pdf|max:5120',
        ]);

        // Logika pemrosesan file PDF brosur toko digital
$brochurePath = null;
if ($request->hasFile('brochure')) {
    $file = $request->file('brochure');
    $filename = time() . '_brochure_' . $file->getClientOriginalName();
    $file->storeAs('public/brochures', $filename);
    $brochurePath = 'brochures/' . $filename;
}

    $user = auth()->user();
    $company = \DB::table('companies')->where('id', $user->company_id)->first();

    // 2. LOGIKA UPLOAD GAMBAR FISIK
    $imagePath = null;
    if ($request->hasFile('image')) {
        // Simpan file ke dalam folder storage/app/public/news
        $file = $request->file('image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/news', $filename);
        $imagePath = 'news/' . $filename; // Jalur yang akan dicatat di database
    }

    // 3. Simpan Seluruh Data ke Database
    \DB::table('inventories')->insert([
        'name' => $request->name,
        'name_en' => $request->name_en ?? $request->name,
        'category' => $request->category,
        'stock' => $request->stock,
        'unit' => $request->unit,
        'warehouse_location' => $request->warehouse_location,
        'whatsapp_contact' => $request->whatsapp_contact,
        'description' => $request->description,
        'description_en' => $request->description_en,
        'price' => $request->price ?? 0.00,
        'nama_perusahaan' => $company->company_name ?? 'PT. Vendor Utama',
        'company_id' => $company->id ?? 1,
        'image' => $imagePath, // Mengamankan lokasi gambar baru
        'created_at' => now(),
        'updated_at' => now()
    ]);

    return redirect()->route('home')->with('message', 'Material listed successfully with image!');
}

// 1. UPDATE DATA MODAL 1: TOKO DIGITAL BAHAN
public function updateInventory(Request $request, $id)
{
    // 1. Validasi Input Produk + Validasi Gambar (Maksimal 2MB)
    $request->validate([
        'name' => 'required|min:3',
                'category' => 'required',
        'stock' => 'required|numeric',
        'unit' => 'required',
        'warehouse_location' => 'required',
        'whatsapp_contact' => 'required|numeric',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // Ambil data komoditas lama dari database
    $oldItem = \DB::table('inventories')->where('id', $id)->first();
    $imagePath = $oldItem->image; // Gunakan jalur foto lama sebagai nilai bawaan

    // 2. LOGIKA SUBSTITUSI GAMBAR BARU
    if ($request->hasFile('image')) {
        // Hapus foto lama di storage jika file fisiknya terdeteksi
        if ($oldItem->image && \Storage::exists('public/' . $oldItem->image)) {
            \Storage::delete('public/' . $oldItem->image);
        }

        // Simpan file foto baru ke folder storage/app/public/news
        $file = $request->file('image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/news', $filename);
        $imagePath = 'news/' . $filename;
    }

    // 3. Eksekusi Perintah SQL UPDATE ke Database Kantor/Rumah
    \DB::table('inventories')->where('id', $id)->update([
        'name' => $request->name,
        'name_en' => $request->name_en ?? $request->name,
        'category' => $request->category,
        'stock' => $request->stock,
        'unit' => $request->unit,
        'warehouse_location' => $request->warehouse_location,
        'whatsapp_contact' => $request->whatsapp_contact,
        'description' => $request->description,
        'description_en' => $request->description_en,
        'price' => $request->price ?? 0.00,
        'nama_perusahaan' => $request->nama_perusahaan ?? 'PT. Vendor Utama',
        'image' => $imagePath, // Mengunci jalur gambar mutakhir
        'updated_at' => now()
    ]);

    return redirect()->route('home')->with('message', 'Material updated successfully with new image!');
}

// 2. UPDATE DATA MODAL 2: PUSAT DATA & REGULASI
public function updateRegulation(Request $request, $id)
{
    $request->validate([
        'title' => 'required|min:5',
        'speaker' => 'required',
        'category' => 'required',
        'event_date' => 'required|date',
        'file' => 'nullable|mimes:pdf|max:10240',
    ]);

    $oldReg = \DB::table('regulations')->where('id', $id)->first();
    $filePath = $oldReg->file_path;

    if ($request->hasFile('file')) {
        // Hapus dokumen PDF lama di server jika ada
        if ($oldReg->file_path && \Storage::exists('public/' . $oldReg->file_path)) {
            \Storage::delete('public/' . $oldReg->file_path);
        }

        $file = $request->file('file');
        
        /* PERBAIKAN UTAMA: Mengambil nama asli file murni tanpa tambahan angka */
        $filename = $file->getClientOriginalName(); 
        
        // Simpan ke folder storage/app/public/regulations/
        $file->storeAs('public/regulations', $filename);
        $filePath = 'regulations/' . $filename;
    }

    \DB::table('regulations')->where('id', $id)->update([
        'title' => $request->title,
        'speaker' => $request->speaker,
        'category' => $request->category,
        'access_tier' => $request->access_tier,
        'event_date' => $request->event_date,
        'file_path' => $filePath, // Mengunci jalur nama murni asli
        'updated_at' => now()
    ]);

    return redirect()->route('home')->with('message', 'Dokumen regulasi berhasil diperbarui dengan nama asli!');
}


// 1. FUNGSI UNTUK MENYIMPAN REGULASI BARU
public function storeRegulation(Request $request)
{
    $request->validate([
        'title' => 'required|min:5',
        'speaker' => 'required',
        'category' => 'required',
        'event_date' => 'required|date',
        'file' => 'required|mimes:pdf|max:10240', // Wajib PDF, Maksimal 10MB
    ]);

    $filePath = null;
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $filename = time() . '_doc_' . $file->getClientOriginalName();
        $file->storeAs('public/regulations', $filename);
        $filePath = 'regulations/' . $filename;
    }

    \DB::table('regulations')->insert([
        'title' => $request->title,
        'speaker' => $request->speaker,
        'category' => $request->category,
        'access_tier' => $request->access_tier ?? 'Member',
        'event_date' => $request->event_date,
        'file_path' => $filePath,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    return redirect()->route('home')->with('message', 'Dokumen regulasi baru berhasil diunggah!');
}

// 3. UPDATE DATA MODAL 3: MATCHMAKING KEMITRAAN
public function updateMatchmaking(Request $request, $id)
{
    $request->validate([
        'name' => 'required|min:3',
        'tagline' => 'required',
        'category' => 'required',
        'region' => 'required',
        'description' => 'required',
        'whatsapp_contact' => 'required|numeric',
    ]);

    \DB::table('partnerships')->where('id', $id)->update([
        'name' => $request->name,
        'tagline' => $request->tagline,
        'category' => $request->category,
        'region' => $request->region,
        'description' => $request->description,
        'moq_info' => $request->moq_info,
        'after_sales_sla' => $request->after_sales_sla,
        'whatsapp_contact' => $request->whatsapp_contact,
        'updated_at' => now()
    ]);

    return redirect()->route('home')->with('message', 'Profil kemitraan B2B berhasil diperbarui!');
}


    
}