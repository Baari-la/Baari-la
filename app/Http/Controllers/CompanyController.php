<?php
namespace App\Http\Controllers;
use App\Models\News; 
use App\Models\Company;
use App\Models\CompanyMoq;
use App\Models\CompanyLeadTime;
use App\Models\CompanyProduct;
use App\Models\CompanyMarket;
use App\Models\CompanyCertification;
use App\Models\CompanyCapacity;
use App\Models\CompanyContact;
use App\Models\CompanyLink;
use App\Models\CompanyImage;
use App\Models\CompanyMachine;
use Illuminate\Support\Facades\Storage;
use App\Services\CompanyRelationalSyncService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

class CompanyController extends Controller
{
public function index(Request $request)
{
    $search = $request->search;
    $category = $request->category;
    $location = $request->location;

    /*
    |--------------------------------------------------------------------------
    | Company Query
    |--------------------------------------------------------------------------
    */

    $query = Company::with([
        'products',
        'markets',
        'certifications',
        'contacts',
        'links',
        'images',
        'capacities',
        'machines',
        'moqs',
        'leadTimes'
    ]);

    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    if ($search) {

        $query->where(function ($q) use ($search) {

            /*
            |--------------------------------------------------------------------------
            | Legacy Fields
            |--------------------------------------------------------------------------
            */

            $q->where('nama_perusahaan', 'like', "%{$search}%")
              ->orWhere('produk', 'like', "%{$search}%")
              ->orWhere('sektor', 'like', "%{$search}%")
              ->orWhere('city', 'like', "%{$search}%")
              ->orWhere('pasar_ekspor', 'like', "%{$search}%");

            /*
            |--------------------------------------------------------------------------
            | Products
            |--------------------------------------------------------------------------
            */

            $q->orWhereHas('products', function ($productQuery) use ($search) {

                $productQuery
                    ->where('product_name', 'like', "%{$search}%")
                    ->orWhere('product_name_en', 'like', "%{$search}%")
                    ->orWhere('hs_code', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });

            /*
            |--------------------------------------------------------------------------
            | Markets
            |--------------------------------------------------------------------------
            */

            $q->orWhereHas('markets', function ($marketQuery) use ($search) {

                $marketQuery->where(
                    'country_name',
                    'like',
                    "%{$search}%"
                );

            });

            /*
            |--------------------------------------------------------------------------
            | Certifications
            |--------------------------------------------------------------------------
            */

            $q->orWhereHas('certifications', function ($certQuery) use ($search) {

                $certQuery
                    ->where('certification_name', 'like', "%{$search}%")
                    ->orWhere('issuer', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");

            });

            /*
            |--------------------------------------------------------------------------
            | Machines
            |--------------------------------------------------------------------------
            */

            $q->orWhereHas('machines', function ($machineQuery) use ($search) {

                $machineQuery
                    ->where('machine_brand', 'like', "%{$search}%")
                    ->orWhere('machine_model', 'like', "%{$search}%")
                    ->orWhere('machine_category', 'like', "%{$search}%");

            });

            /*
            |--------------------------------------------------------------------------
            | Capacities
            |--------------------------------------------------------------------------
            */

            $q->orWhereHas('capacities', function ($capacityQuery) use ($search) {

                $capacityQuery
                    ->where('item_name', 'like', "%{$search}%")
                    ->orWhere('capacity_type', 'like', "%{$search}%");

            });

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Category Filter
    |--------------------------------------------------------------------------
    */

    if ($category) {
        $query->where('category', $category);
    }

    /*
    |--------------------------------------------------------------------------
    | Location Filter
    |--------------------------------------------------------------------------
    */

    if ($location) {
        $query->where('city', 'like', "%{$location}%");
    }

    /*
    |--------------------------------------------------------------------------
    | Sorting Priority
    |--------------------------------------------------------------------------
    */

    $query->orderByRaw("
        CASE membership_type
            WHEN 'gold_member' THEN 1
            WHEN 'silver_member' THEN 2
            WHEN 'member' THEN 3
            ELSE 4
        END
    ");

    /*
    |--------------------------------------------------------------------------
    | Execute Query
    |--------------------------------------------------------------------------
    */

    $companies = $query
        ->paginate(9, ['*'], 'companies_page')
        ->withQueryString();

    /*
    |--------------------------------------------------------------------------
    | News Search
    |--------------------------------------------------------------------------
    */

    $news = News::query()
        ->when($search, function ($q, $search) {

            $q->where('title_id', 'like', "%{$search}%")
              ->orWhere('title_en', 'like', "%{$search}%");

        })
        ->latest()
        ->paginate(3, ['*'], 'news_page')
        ->withQueryString();

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    return Inertia::render('Company/Index', [

        'companies' => $companies,

        'newsResults' => $news,

        'filters' => $request->only([
            'search',
            'category',
            'location',
        ]),
    ]);
}

public function updateMachines(
    Request $request,
    Company $company
) {

    $validated = $request->validate([

        'machines' => 'nullable|array',
        'machines.*.id' =>
    'nullable|integer',

        'machines.*.machine_category' =>
            'nullable|string|max:255',

        'machines.*.machine_type' =>
            'nullable|string|max:255',

        'machines.*.machine_brand' =>
            'nullable|string|max:255',

        'machines.*.machine_model' =>
            'nullable|string|max:255',

        'machines.*.quantity' =>
            'nullable|integer',

        'machines.*.production_capacity' =>
            'nullable|numeric',

        'machines.*.capacity_unit' =>
            'nullable|string|max:100',

        'machines.*.working_width' =>
            'nullable|string|max:100',

        'machines.*.gauge_specification' =>
            'nullable|string|max:100',

        'machines.*.year_installed' =>
            'nullable',

        'machines.*.machine_condition' =>
            'nullable|string|max:100',

        'machines.*.automation_level' =>
            'nullable|string|max:100',

        'machines.*.country_origin' =>
            'nullable|string|max:255',

        'machines.*.is_active' =>
            'nullable|boolean',

        'machines.*.notes' =>
            'nullable|string',
    ]);

    CompanyRelationalSyncService::syncMachines(
        $company,
        $validated['machines'] ?? []
    );

    return back()->with(
        'success',
        'Machines updated successfully.'
    );
}
public function destroyMachine(
    Company $company,
    CompanyMachine $machine
)
{
    /*
    |--------------------------------------------------------------------------
    | Security Check
    |--------------------------------------------------------------------------
    */

    if ($machine->company_id != $company->id) {

        abort(
            403,
            'Machine does not belong to this company.'
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    $machine->delete();

    /*
    |--------------------------------------------------------------------------
    | Response
    |--------------------------------------------------------------------------
    */

    return back()->with(
        'success',
        'Machine deleted successfully.'
    );
}


public function updateMoqs(
    Request $request,
    Company $company
) {

    $validated = $request->validate([

        'moqs' => 'nullable|array',

        'moqs.*.id' =>
            'nullable|integer',

        'moqs.*.product_name' =>
            'nullable|string|max:255',

        'moqs.*.minimum_quantity' =>
            'nullable|numeric',

        'moqs.*.unit' =>
            'nullable|string|max:50',

        'moqs.*.notes' =>
            'nullable|string',
    ]);

    CompanyRelationalSyncService::syncMoqs(
        $company,
        $validated['moqs'] ?? []
    );

    return back()->with(
        'success',
        'MOQ updated successfully.'
    );
}

public function updateLeadTimes(
    Request $request,
    Company $company
) {
    $validated = $request->validate([
        'lead_times' => 'nullable|array',

        'lead_times.*.lead_time_type' =>
            'nullable|string|max:255',

        'lead_times.*.days' =>
            'nullable|integer',

        'lead_times.*.notes' =>
            'nullable|string',
    ]);

    foreach ($validated['lead_times'] ?? [] as $item) {

        if (
            empty($item['lead_time_type']) &&
            empty($item['days'])
        ) {
            continue;
        }

        CompanyLeadTime::updateOrCreate(
            [
                'company_id' => $company->id,
                'lead_time_type' =>
                    $item['lead_time_type'],
            ],
            [
                'days' =>
                    $item['days'] ?? 0,

                'notes' =>
                    $item['notes'] ?? null,
            ]
        );
    }

    return back();
}

public function updateCapacities(
    Request $request,
    Company $company
) {
    $validated = $request->validate([
        'capacities' => 'nullable|array',
    ]);

    foreach ($validated['capacities'] ?? [] as $item) {

        if (
            empty($item['capacity_type']) &&
            empty($item['item_name'])
        ) {
            continue;
        }

        CompanyCapacity::updateOrCreate(
            [
                'company_id' => $company->id,
                'capacity_type' =>
                    $item['capacity_type'] ?? '',
                'item_name' =>
                    $item['item_name'] ?? '',
            ],
            $item
        );
    }

    return back();
}

public function updateProducts(
    Request $request,
    Company $company
) {
    $validated = $request->validate([
        'products' => 'nullable|array',
    ]);

    foreach ($validated['products'] ?? [] as $product) {

        if (
            empty($product['product_name'])
        ) {
            continue;
        }

        CompanyProduct::updateOrCreate(
            [
                'company_id' => $company->id,
                'product_name' =>
                    $product['product_name'],
            ],
            $product
        );
    }

    return back();
}

public function updateMarkets(
    Request $request,
    Company $company
) {
    $validated = $request->validate([
        'markets' => 'nullable|array',
    ]);

    foreach ($validated['markets'] ?? [] as $market) {

        if (
            empty($market['country_name'])
        ) {
            continue;
        }

        CompanyMarket::updateOrCreate(
            [
                'company_id' => $company->id,
                'country_name' =>
                    $market['country_name'],
                'market_type' =>
                    $market['market_type'] ?? 'export',
            ],
            $market
        );
    }

    return back();
}


public function updateCertifications(
    Request $request,
    Company $company
) {
    $validated = $request->validate([
        'certifications' => 'nullable|array',
    ]);

    foreach (
        $validated['certifications'] ?? []
        as $cert
    ) {

        if (
            empty($cert['certification_name'])
        ) {
            continue;
        }

        CompanyCertification::updateOrCreate(
            [
                'company_id' => $company->id,
                'certification_name' =>
                    $cert['certification_name'],
            ],
            $cert
        );
    }

    return back();
}

public function updateContacts(
    Request $request,
    Company $company
) {
    $validated = $request->validate([
        'contacts' => 'nullable|array',
    ]);

    foreach ($validated['contacts'] ?? [] as $contact) {

        if (
            empty($contact['contact_name'])
        ) {
            continue;
        }

        CompanyContact::updateOrCreate(
            [
                'company_id' => $company->id,
                'contact_name' =>
                    $contact['contact_name'],
            ],
            $contact
        );
    }

    return back();
}

public function updateLinks(
    Request $request,
    Company $company
) {
    $validated = $request->validate([
        'links' => 'nullable|array',
    ]);

    foreach ($validated['links'] ?? [] as $link) {

        if (
            empty($link['url'])
        ) {
            continue;
        }

        CompanyLink::updateOrCreate(
            [
                'company_id' => $company->id,
                'url' =>
                    $link['url'],
            ],
            $link
        );
    }

    return back();
}

public function updateImages(
    Request $request,
    Company $company
) {
    $validated = $request->validate([
        'images' => 'nullable|array',
    ]);

    foreach ($request->images ?? [] as $image) {

        $imagePath = null;

        if (
            isset($image['image_file'])
        ) {
            $imagePath =
                $image['image_file']
                    ->store(
                        'company-images',
                        'public'
                    );
        }

        CompanyImage::updateOrCreate(
            [
                'company_id' => $company->id,
                'caption' =>
                    $image['caption'] ?? '',
            ],
            [
                'image_type' =>
                    $image['image_type'] ?? null,

                'image_url' =>
                    $imagePath
                        ? Storage::url($imagePath)
                        : ($image['image_url'] ?? null),

                'caption' =>
                    $image['caption'] ?? null,
            ]
        );
    }

    return back();
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



public function show(Company $company)
{
    $company->load([
        'products',
        'markets',
        'certifications',
        'contacts',
        'links',
        'images',
        'capacities',
        'machines',
        'moqs',
        'leadTimes'
    
    ]);

     return Inertia::render('Company/Show', [
        'company' => $company,
    ]);
}

public function edit(Company $company)
{
    $user = auth()->user();

    /*
    |--------------------------------------------------------------------------
    | Authorization
    |--------------------------------------------------------------------------
    */

    $isOwner = $user->company_id == $company->id;
    $isAdmin = $user->role === 'admin';

    if (!$isAdmin && !$isOwner) {

        abort(
            403,
            'Akses Ditolak: Anda bukan pemilik resmi entitas industri ini.'
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Load Relationships
    |--------------------------------------------------------------------------
    */

    $company->load([
         'products',
        'markets',
        'certifications',
        'contacts',
        'links',
        'images',
        'capacities',
        'machines',
        'moqs',
        'leadTimes'
    ]);

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    return Inertia::render('Company/Edit', [
        'company' => $company
    ]);
}

public function update(Request $request, Company $company)
{
    $user = auth()->user();

    /*
    |--------------------------------------------------------------------------
    | PROTECTION
    |--------------------------------------------------------------------------
    */
// dd($request->all());



    $isOwner = $user->company_id == $company->id;
    $isAdmin = $user->role === 'admin';

    if (!$isAdmin && !$isOwner) {
        abort(403, 'Unauthorized action.');
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    $validated = $request->validate([

        // MAIN TABLE
        'nama_perusahaan' => 'required|string|max:255',
        'sektor' => 'nullable|string',
        'wilayah' => 'nullable|string',
        'city' => 'nullable|string',
        'produk' => 'nullable|string',
        'alamat_lengkap' => 'nullable|string',
        'tahun_berdiri' => 'nullable|string',
        'telepon' => 'nullable|string',
        'email_web' => 'nullable|string',
        'pimpinan' => 'nullable|string',
        'tenaga_kerja' => 'nullable|string',
        'pasar_ekspor' => 'nullable|string',

        'stock_ready_caption' => 'nullable|string',
        'stock_qty' => 'nullable|numeric',
        'stock_unit' => 'nullable|string',
        'price' => 'nullable|numeric',

        /*
        |--------------------------------------------------------------------------
        | PRODUCTS
        |--------------------------------------------------------------------------
        */

        'products' => 'nullable|array',
        'products.*.product_name' => 'nullable|string',
        'products.*.product_name_en' => 'nullable|string',
        'products.*.hs_code' => 'nullable|string',
        'products.*.category' => 'nullable|string',
        'products.*.description' => 'nullable|string',
        'products.*.is_primary' => 'nullable|boolean',

        /*
        |--------------------------------------------------------------------------
        | MARKETS
        |--------------------------------------------------------------------------
        */

        'markets' => 'nullable|array',
        'markets.*.country_name' => 'nullable|string',
        'markets.*.market_type' => 'nullable|string',

        /*
        |--------------------------------------------------------------------------
        | CERTIFICATIONS
        |--------------------------------------------------------------------------
        */

            'certifications' => ['nullable', 'array'],
            'certifications.*.certification_name' =>
                'nullable|string|max:255',
            'certifications.*.category' =>
                'nullable|string|max:255',
            'certifications.*.certification_code' =>
                'nullable|string|max:255',
            'certifications.*.issuer' =>
                'nullable|string|max:255',
            'certifications.*.certificate_number' =>
                'nullable|string|max:255',
            'certifications.*.description' =>
                'nullable|string',
            'certifications.*.valid_until' =>
                'nullable|date',
            'certifications.*.issued_at' =>
                'nullable|date',
            'certifications.*.status' =>
                'nullable|string',
            'certifications.*.is_verified' =>
                'nullable|boolean',
            'certifications.*.is_featured' =>
                'nullable|boolean',
            'certifications.*.sort_order' =>
                'nullable|integer',
            'certifications.*.certificate_file' => [
                'nullable',
                'file',
                'mimes:pdf',
                'max:10240',
            ],

            'certifications.*.logo_file' => [
                'nullable',
                'image',
                'max:5120',
            ],
 /*
        |--------------------------------------------------------------------------
        | IMAGES
        |--------------------------------------------------------------------------
        */
        'images' => 'nullable|array',
        'images.*.image_type' => 'nullable|string',
        'images.*.image_url' => 'nullable|string',
        'images.*.caption' => 'nullable|string',
        'images.*.image_file' =>
        'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',

        /*
        |--------------------------------------------------------------------------
        | CONTACTS
        |--------------------------------------------------------------------------
        */

        'contacts' => 'nullable|array',
        'contacts.*.contact_name' => 'nullable|string',
        'contacts.*.position' => 'nullable|string',
        'contacts.*.phone' => 'nullable|string',
        'contacts.*.email' => 'nullable|string',

        /*
        |--------------------------------------------------------------------------
        | LINKS
        |--------------------------------------------------------------------------
        */

        'links' => 'nullable|array',
        'links.*.link_type' => 'nullable|string',
        'links.*.url' => 'nullable|string',

        /*
        |--------------------------------------------------------------------------
        | CAPACITIES
        |--------------------------------------------------------------------------
        */

        'capacities' => 'nullable|array',
        'capacities.*.capacity_type' => 'nullable|string',
        'capacities.*.item_name' => 'nullable|string',
        'capacities.*.capacity_value' => 'nullable|numeric',
        'capacities.*.capacity_unit' => 'nullable|string',
        'capacities.*.capacity_category' => 'nullable|string',
        'capacities.*.machine_count' => 'nullable|numeric',
        'capacities.*.shift_info' => 'nullable|string',
        'capacities.*.notes' => 'nullable|string',

         /*
|--------------------------------------------------------------------------
| MACHINES
|--------------------------------------------------------------------------
*/

'machines' => 'nullable|array',

'machines.*.machine_category' =>
    'nullable|string|max:255',

'machines.*.machine_type' =>
    'nullable|string|max:255',

'machines.*.machine_brand' =>
    'nullable|string|max:255',

'machines.*.machine_model' =>
    'nullable|string|max:255',

'machines.*.quantity' =>
    'nullable|integer',

'machines.*.production_capacity' =>
    'nullable|numeric',

'machines.*.capacity_unit' =>
    'nullable|string|max:100',

'machines.*.working_width' =>
    'nullable|string|max:100',

'machines.*.gauge_specification' =>
    'nullable|string|max:100',

'machines.*.year_installed' =>
    'nullable',

'machines.*.machine_condition' =>
    'nullable|string|max:100',

'machines.*.automation_level' =>
    'nullable|string|max:100',

'machines.*.country_origin' =>
    'nullable|string|max:255',

'machines.*.is_active' =>
    'nullable|boolean',

'machines.*.notes' =>
    'nullable|string',

/*
|--------------------------------------------------------------------------
| MOQS
|--------------------------------------------------------------------------
*/

'moqs' => 'nullable|array',

'moqs.*.product_name' => 'nullable|string|max:255',
'moqs.*.minimum_quantity' => 'nullable|numeric',

'moqs.*.unit' =>
    'nullable|string|max:50',

'moqs.*.notes' =>
    'nullable|string',

/*
|--------------------------------------------------------------------------
| LEAD TIMES
|--------------------------------------------------------------------------
*/

'lead_times' => 'nullable|array',

'lead_times.*.lead_time_type' =>
    'nullable|string|max:255',

'lead_times.*.days' =>
    'nullable|integer',

'lead_times.*.notes' =>
    'nullable|string',


    ]);

   

    /*
    |--------------------------------------------------------------------------
    | MAIN COMPANY DATA
    |--------------------------------------------------------------------------
    */

    $mainData = [
        'nama_perusahaan' => $validated['nama_perusahaan'] ?? null,
        'sektor' => $validated['sektor'] ?? null,
        'wilayah' => $validated['wilayah'] ?? null,
        'city' => $validated['city'] ?? null,
        'produk' => $validated['produk'] ?? null,
        'alamat_lengkap' => $validated['alamat_lengkap'] ?? null,
        'tahun_berdiri' => $validated['tahun_berdiri'] ?? null,
        'telepon' => $validated['telepon'] ?? null,
        'email_web' => $validated['email_web'] ?? null,
        'pimpinan' => $validated['pimpinan'] ?? null,
        'tenaga_kerja' => $validated['tenaga_kerja'] ?? null,
        'pasar_ekspor' => $validated['pasar_ekspor'] ?? null,

        'stock_ready_caption' =>
            $validated['stock_ready_caption'] ?? null,

        'stock_qty' =>
            $validated['stock_qty'] ?? 0,

        'stock_unit' =>
            $validated['stock_unit'] ?? 'Kg',

        'price' =>
            $validated['price'] ?? 0,
    ];

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */

    if ($isAdmin) {

        // UPDATE MAIN TABLE
        $company->update($mainData);

        // SYNC ALL RELATIONAL TABLES
        CompanyRelationalSyncService::sync($company, $validated);


        return redirect()
            ->route('companies.index')
            ->with(
                'success',
                'Data berhasil diperbarui secara instan oleh Admin.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | MEMBER → AUDIT QUEUE
    |--------------------------------------------------------------------------
    */

    \App\Models\CompanyUpdate::create([
        'company_id' => $company->id,

        'user_id' => auth()->id(),

        'proposed_data' => [
            ...$mainData,

            'products' =>
                $validated['products'] ?? [],
            'images' =>
                $validated['images'] ?? [],

            'markets' =>
                $validated['markets'] ?? [],

            'certifications' =>
                $validated['certifications'] ?? [],

            'contacts' =>
                $validated['contacts'] ?? [],

            'links' =>
                $validated['links'] ?? [],

            'capacities' =>
                $validated['capacities'] ?? [],
            'machines' =>
                $validated['machines'] ?? [],

            'moqs' =>
                $validated['moqs'] ?? [],

            'lead_times' =>
                $validated['lead_times'] ?? [],  
                    ],

        'status' => 'pending',
    ]);

    return redirect()
        ->route('intelligence.center')
        ->with(
            'message',
            'Update request submitted. Your data is now in the audit queue.'
        );
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