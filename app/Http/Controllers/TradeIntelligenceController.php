<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TradeIntelligenceController extends Controller
{
public function index()
{
    // 1. Ambil Data Jenis Produk dari tabel trade_master_annual_hscode
    // Kita petakan sesuai sektor: Serat, Benang, Kain, Garmen, Home Textile
    $topTrade = \DB::table('trade_master_annual_hscode')
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
    $topCountries = \DB::table('trade_master_annual_country')
        ->selectRaw('negara_tujuan as name, SUM(nilai_ekspor_2025) as value')
        ->groupBy('negara_tujuan')
        ->orderBy('value', 'desc')
        ->take(5)
        ->get();

    // 3. Ambil Tren Tahunan (2021-2025) untuk grafik utama
    $yearlyTrends = \DB::table('trade_master_annual_hscode')
        ->selectRaw('
            SUM(nilai_ekspor_2021) as "2021", 
            SUM(nilai_ekspor_2022) as "2022", 
            SUM(nilai_ekspor_2023) as "2023", 
            SUM(nilai_ekspor_2024) as "2024", 
            SUM(nilai_ekspor_2025) as "2025"
        ')->first();

    return inertia('Trade/Radar', [
        'topTrade' => $topTrade,
        'topCountries' => $topCountries,
        'yearlyTrends' => $yearlyTrends,
        'hscodes' => \App\Models\HsCode::take(10)->get()
    ]);
}

public function indexInventory()
{
    return inertia('Inventory/Index', [
        'inventories' => \App\Models\Inventory::all() // Mengambil data bursa
    ]);
}

public function create()
{
    return inertia('Inventory/Create'); // Membuka form tambah bahan
}
public function storeInventory(Request $request)
{
    // 1. Validasi Input
    $request->validate([
        'name' => 'required|min:3',
        'category' => 'required',
        'stock' => 'required|numeric',
        'unit' => 'required',
        'warehouse_location' => 'required',
        'whatsapp_contact' => 'required|numeric',
    ]);

    // 2. Simpan ke Database
    \App\Models\Inventory::create([
        'name' => $request->name,
        'category' => $request->category,
        'stock' => $request->stock,
        'unit' => $request->unit,
        'warehouse_location' => $request->warehouse_location,
        'whatsapp_contact' => $request->whatsapp_contact,
        'description' => $request->description,
        'price' => $request->price,
        'user_id' => auth()->id(), // Mencatat siapa yang punya barang
    ]);

    // 3. Lempar balik ke halaman bursa dengan pesan sukses
    return redirect()->route('inventory.index')->with('message', 'Material listed successfully!');
}

}