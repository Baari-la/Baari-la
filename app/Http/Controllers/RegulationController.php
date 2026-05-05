<?php

namespace App\Http\Controllers;

use App\Models\Regulation; // 1. TAMBAHKAN INI
use Illuminate\Http\Request;
use Inertia\Inertia; // 2. TAMBAHKAN INI jika ingin pakai return Inertia::render

class RegulationController extends Controller
{
    public function index()
    {
        return Inertia::render('Regulation/Index', [
            'regulations' => Regulation::orderBy('year', 'desc')->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Regulation/Create');
    }

    
    public function store(Request $request)
{
    $request->validate([
        'title_id' => 'required|string|max:255',
        'year' => 'required|numeric',
        'file' => 'required|mimes:pdf|max:20480', // Maksimal 20MB (biasanya regulasi pemerintah cukup besar)
    ]);

    // Simpan file dengan nama asli dari kementerian agar mudah dikenali
    $fileName = $request->file('file')->getClientOriginalName();
    $filePath = $request->file('file')->storeAs('regulations', $fileName, 'public');

    \App\Models\Regulation::create([
        'title_id' => $request->title_id,
        'title_en' => $request->title_en, // Tetap diisi untuk kebutuhan mitra global (Centric)
        'year' => $request->year,
        'file_path' => $filePath,
    ]);

    return redirect()->route('regulation.index')->with('success', 'Regulasi berhasil diarsipkan.');
}
}
