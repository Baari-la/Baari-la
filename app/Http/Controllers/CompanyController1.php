<?php

namespace App\Http\Controllers;
use App\Models\Company;
use Illuminate\Http\Request; // WAJIB ADA
use Inertia\Inertia;

class CompanyController extends Controller
{
public function index(Request $request)
{
$query = $request->input('search');

$companies = \App\Models\Company::when($query, function ($q, $search) {
$q->where('nama_perusahaan', 'like', "%{$search}%")
->orWhere('sektor', 'like', "%{$search}%")
->orWhere('wilayah', 'like', "%{$search}%")
->orWhere('produk', 'like', "%{$search}%");
})
->latest()
->paginate(12)
->withQueryString();

return inertia('Company/Index', [
'companies' => $companies,
'filters' => $request->only(['search'])
]);
}
public function show(Company $company) {
    return inertia('Company/Show', ['company' => $company]);
}

public function edit(\App\Models\Company $company)
{
    // Untuk sementara kita return ke halaman draf yang kita buat kemarin
    return inertia('Member/EditProfile', [
        'company' => $company
    ]);
}


public function update(Request $request, Company $company)
{
    // HANYA BOLEH EDIT JIKA ID USER YANG LOGIN SAMA DENGAN PEMILIK DATA
    if ($company->claimed_by_user_id !== auth()->id()) {
        abort(403, 'Akses Ditolak: Anda tidak memiliki izin mengedit data ini.');
    }

    // Proses simpan data...
    $company->update($request->all());
    
    // Kembalikan status ke 'pending' agar diverifikasi admin lagi
    $company->update(['status_verifikasi' => 'pending']); 
}

}