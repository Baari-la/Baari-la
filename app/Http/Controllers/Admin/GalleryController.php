<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery; // WAJIB: Agar sistem mengenali tabel Gallery
use Illuminate\Http\Request; // WAJIB: Untuk menangani input
use Inertia\Inertia; // WAJIB: Untuk merender halaman ke React

class GalleryController extends Controller
{
    // Fungsi untuk menampilkan halaman Gallery di Admin
    public function index()
    {
        return Inertia::render('Admin/Gallery', [
            'galleries' => Gallery::latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title_id' => 'required',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:5120', // Maksimal 5MB untuk foto resolusi tinggi
            'category' => 'required'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            // Tambahkan timestamp agar nama file tidak bentrok
            $originalName = time() . '_' . $file->getClientOriginalName();
            $imagePath = $file->storeAs('gallery', $originalName, 'public');
        }

        Gallery::create([
            'title_id' => $request->title_id,
            'title_en' => $request->title_en,
            'category' => $request->category,
            'image_path' => $imagePath,
        ]);

        return back()->with('message', 'Global Documentation Published Successfully!');
    }

    // Fungsi hapus untuk merapikan galeri jika ada salah upload
    public function destroy(Gallery $gallery)
    {
        $gallery->delete();
        return back()->with('message', 'Documentation Removed.');
    }
}