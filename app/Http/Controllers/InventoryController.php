<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');

        $query = DB::table('inventories');

        // Saring berdasarkan nama kain / benang sisa produksi
        if ($search) {
            $query->where('name', 'LIKE', "%$search%");
        }

        // Saring berdasarkan kategori (Fabric, Yarn, etc.)
        if ($category) {
            $query->where('category', $category);
        }

        $items = $query->orderBy('created_at', 'desc')->get();

        return Inertia::render('Home', [
            // Kirim ke halaman depan agar bisa ditangkap oleh Modal Bursa Bahan
            'inventoryItems' => $items,
        ]);
    }
}