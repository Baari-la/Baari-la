<?php

namespace App\Http\Controllers;
use App\Models\Company;
use App\Models\News; 
use Illuminate\Http\Request; // WAJIB ADA
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

class CompanyController extends Controller
{
public function index(Request $request)
{
    $search = $request->search;
    $category = $request->category; // Ambil input kategori
    $location = $request->location; // Ambil input lokasi

    // 1. Cari di Direktori Perusahaan & Produk
      $query = Company::query();
        // 1. Logika Pencarian (Nama & Produk)
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('nama_perusahaan', 'like', "%{$search}%")
              ->orWhere('produk', 'like', "%{$search}%");
        });
    }

    // 2. Logika Filter Kategori (BARU)
    if ($category) {
        $query->where('category', $category);
    }

    // 3. Logika Filter Lokasi (BARU)
    if ($location) {
        $query->where('city', 'like', "%{$location}%");
    }

      // Eksekusi Query Perusahaan
    $companies = $query->orderByRaw("membership_type = 'gold_member' DESC")
                       ->paginate(9, ['*'], 'companies_page')
                       ->withQueryString();

    // 4. Logika Pencarian Berita (Jika ada search)
    $news = News::query()
        ->when($search, function($q, $search) {
            $q->where('title_id', 'like', "%{$search}%")
              ->orWhere('title_en', 'like', "%{$search}%");
        })
        ->latest()
        ->paginate(3, ['*'], 'news_page')
        ->withQueryString();

    return Inertia::render('Company/Index', [
        'companies' => $companies,
        'newsResults' => $news,
        'filters' => $request->only(['search', 'category', 'location'])
    ]);
}

public function create()
{
    return Inertia::render('Company/Create'); // Menuju file React baru

    }

public function store(Request $request)
{
    $validated = $request->validate([
        'nama_perusahaan'   => 'required|string|max:255',
        'sektor'            => 'nullable|string',
        'wilayah'           => 'nullable|string',
        'alamat_lengkap'    => 'nullable|string',
        'city'              => 'nullable|string',
        'telepon'           => 'nullable|string',
        'email_web'         => 'nullable|string',
        'pimpinan'          => 'nullable|string',
        'tenaga_kerja'      => 'nullable|string',
        'pasar_ekspor'      => 'nullable|string',
        'produk'            => 'nullable|string',
        'category'          => 'nullable|string',
        // 'membership_type'   => 'nullable|string',
        'tahun_berdiri'     => 'nullable|string',
        'status_verifikasi' => 'nullable|string',
    ]);

    Company::create($validated);

    return redirect()->route('companies.index')
        ->with('success', 'Data Industri Berhasil Ditambahkan.');
}



public function show(Company $company) {
    // return inertia('Company/Show', ['company' => $company]);
     return Inertia::render('Company/Show', [
        'company' => $company,
        // Pastikan middleware HandleInertiaRequests sudah mengirim 'auth'
    ]);
}

public function edit(Company $company)
{
    $user = auth()->user();

    // Logika: Izinkan jika dia Admin ATAU jika company_id di profil user cocok dengan ID perusahaan ini
    $isOwner = $user->company_id == $company->id;
    $isAdmin = $user->role === 'admin';

    if (!$isAdmin && !$isOwner) {
        abort(403, 'Akses Ditolak: Anda bukan pemilik resmi entitas industri ini.');
    }
    return Inertia::render('Company/Edit', [
        'company' => $company
    ]);
}

public function update(Request $request, Company $company)
{
     $user = auth()->user();

    // 1. Logika Proteksi: Izinkan jika Admin ATAU Pemilik (Gunakan == untuk fleksibilitas tipe data)
    $isOwner = $user->company_id == $company->id;
    $isAdmin = $user->role === 'admin';

    if (!$isAdmin && !$isOwner) {
        abort(403, 'Unauthorized action.');
    }

    // 2. Validasi (Sudah Lengkap)
    $validated = $request->validate([
        'nama_perusahaan' => 'required|string|max:255',
        'sektor' => 'nullable',
        'wilayah' => 'nullable',
        'city' => 'nullable',
        'produk' => 'nullable',
        'alamat_lengkap' => 'nullable',
        'tahun_berdiri'     => 'nullable',
        'telepon'           => 'nullable',
        'email_web'         => 'nullable',
        'pimpinan'          => 'nullable',
        'tenaga_kerja'      => 'nullable',
        'pasar_ekspor'      => 'nullable',
        'stock_ready_caption' => 'nullable|string',
        'stock_qty' => 'nullable|numeric',
        'stock_unit' => 'nullable|string',
        'price' => 'nullable|numeric',
        
    ]);

    // 3. LOGIKA PEMISAHAN (Kunci Keamanan Data)
    if (auth()->user()->role === 'admin') {
        // Jika Bapak (Admin) yang edit, langsung simpan ke database
        $company->update($validated);
        return redirect()->route('companies.index')
              ->with('success', 'Data berhasil diperbarui secara instan oleh Admin.');
    } else {
        // Jika MEMBER yang edit, simpan ke "Ruang Tunggu" (company_updates)
        \App\Models\CompanyUpdate::create([
            'company_id' => $company->id,
            'user_id' => auth()->id(),
            'proposed_data' => json_encode($validated), // Simpan perubahan dalam format JSON
            'status' => 'pending'
        ]);

       // Jika Member yang update
return redirect()->route('intelligence.center')
    ->with('message', 'Update request submitted. Your data is now in the audit queue.');


    }
}

public function destroy(Company $company)
{
    // Cek lagi: Hanya admin yang boleh menghapus
    if (auth()->user()->role !== 'admin') {
        abort(403, 'Hanya Admin yang dapat menghapus entitas industri.');
    }

    $company->delete();
    return back()->with('message', 'Perusahaan telah dihapus dari Big Data.');
}


public function requestPremium(Request $request)
{
    $user = auth()->user();
    
    // Cek apakah sudah pernah minta sebelumnya
    $exists = DB::table('premium_requests')
                ->where('user_id', $user->id)
                ->where('status', 'pending')
                ->exists();

    if (!$exists) {
        DB::table('premium_requests')->insert([
            'user_id' => $user->id,
            'company_name' => $request->company_name ?? 'Unknown',
            'created_at' => now(),
        ]);
    }
    
    return back()->with('success', 'Permintaan akses premium telah dikirim ke Admin.');
}
public function verify(Company $company)
{
    // Hanya Admin yang boleh akses pintu ini
    if (auth()->user()->role !== 'admin') {
        abort(403);
    }

    $company->update([
        'status_verifikasi' => 'verified',
        'last_verified_at' => now(), // Di sini baru kita catat waktu verifikasinya
    ]);

    return back()->with('success', "Perusahaan {$company->nama_perusahaan} resmi terverifikasi.");
}

public function publicVerify($nomor_anggota)
{
    // Cari perusahaan berdasarkan nomor anggota
    $company = \App\Models\Company::where('nomor_anggota', $nomor_anggota)->firstOrFail();

    return Inertia::render('Company/PublicVerify', [
        'company' => [
            'id' => $company->id,
            'nama_perusahaan' => $company->nama_perusahaan,
            'nomor_anggota' => $company->nomor_anggota,
            // Format tanggal agar rapi di tampilan global
            'last_verified_at' => $company->last_verified_at ? $company->last_verified_at->format('M d, Y') : null,
        ]
    ]);
}



public function downloadQrCode(Company $company)
{
    // Alamat tujuan scan: https://digestexmedia.com
    $url = url('/v/' . $company->nomor_anggota);
    
    // Generate QR Code dengan Logo di tengah (opsional) atau warna Navy khas Digestex
    $qr = QrCode::format('png')
        ->size(500)
        ->margin(2)
        ->errorCorrection('H')
        ->color(10, 25, 47) // Warna Navy #0a192f
        ->generate($url);

    return response($qr)->header('Content-Type', 'image/png')
        ->header('Content-Disposition', 'attachment; filename="QR-'.$company->nama_perusahaan.'.png"');
}

public function downloadCertificate(Company $company)
{
    // Pastikan hanya yang sudah verified bisa download
    if ($company->status_verifikasi !== 'verified') abort(403);

    $data = [
        'company' => $company,
        'date' => now()->format('M d, Y'),
        'qrCode' => base64_encode(QrCode::format('png')->size(150)->generate(url('/v/' . $company->nomor_anggota))),
    ];

    $pdf = Pdf::loadView('pdf.certificate', $data)
              ->setPaper('a4', 'landscape'); // Format Landscape agar mewah

    return $pdf->download("Certificate-{$company->nama_perusahaan}.pdf");
}

public function downloadMyCertificate()
{
    $user = auth()->user();
    
    // Cari perusahaan yang sudah diklaim oleh user ini dan sudah verified
    $company = \App\Models\Company::where('claimed_by_user_id', $user->id)
                ->where('status_verifikasi', 'verified')
                ->first();

    if (!$company) {
        return back()->with('error', 'Sertifikat belum tersedia atau profil belum diverifikasi.');
    }

    return $this->downloadCertificate($company); // Panggil fungsi downloadCertificate yang kita buat tadi
}

public function publicRegister(Request $request)

{
$cleanName = trim($request->nama_perusahaan);
$existingCompany = \App\Models\Company::where('nama_perusahaan', 'LIKE', '%' . $cleanName . '%')->first();

    if ($existingCompany) {
        // Jika sudah ada, jangan izinkan daftar lagi
        return back()->with('error', "Perusahaan '{$cleanName}' sudah terdaftar dalam sistem. Silakan login atau hubungi Admin API untuk akses akun.");
    }

    $request->validate([
        'nama_perusahaan' => 'required|string|max:255',
        'email'           => 'required|email|unique:users,email',
        'password'        => 'required|min:8',
    ]);

    // 1. Buat 'Cangkang' Perusahaan di tabel Utama (Status: Pending)
    $company = \App\Models\Company::create([
        'nama_perusahaan'   => $request->nama_perusahaan,
        'status_verifikasi' => 'pending',
        'membership_type'   => 'free', // Status untuk umum
    ]);

    // 2. Buat User & Hubungkan ke Company tersebut
    $user = \App\Models\User::create([
        'name'       => $request->nama_perusahaan,
        'email'      => $request->email,
        'password'   => bcrypt($request->password),
        'role'       => 'member',
        'company_id' => $company->id,
    ]);

    // 3. Masukkan ke Antrean Audit (CompanyUpdate) agar Admin bisa periksa
    \App\Models\CompanyUpdate::create([
        'company_id'    => $company->id,
        'user_id'       => $user->id,
        'proposed_data' => json_encode($request->only('nama_perusahaan')),
        'status'        => 'pending'
    ]);

    return redirect()->route('login')->with('success', 'Registrasi berhasil! Data Anda sedang diaudit oleh Admin.');
}



}