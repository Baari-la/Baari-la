<?php

namespace App\Http\Controllers;
use App\Models\News;
use Illuminate\Http\Request;
use Inertia\Inertia; 
use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Str;
class NewsController extends Controller
{

public function translate(Request $request)
{
    $tr = new \Stichoza\GoogleTranslate\GoogleTranslate('en');
    
    // Deteksi apakah yang dikirim adalah judul atau konten
    $translatedText = $tr->translate($request->text);
    
    return response()->json([
        'translated' => $translatedText,
        // Jika teks pendek (asumsi judul), kirim juga slug-nya
        'slug' => strlen($request->text) < 255 ? \Illuminate\Support\Str::slug($request->text) : null
    ]);
}

public function index()
{
    
    // Mengambil semua berita, diurutkan dari yang terbaru
    $news = \App\Models\News::latest()->get(); 
    
    return Inertia::render('News/Index', [
        'news' => $news
    ]);
}
public function show(\App\Models\News $news) // Gunakan Type-Hinting Model News
{
    // Tidak perlu lagi findOrFail, Laravel sudah mencarikannya untuk Anda
    return inertia('News/Show', [
        'news' => $news
    ]);
}




public function destroy(News $news)
{
    $news->delete();
    return redirect()->back()->with('message', 'Intelligence News Deleted Successfully');
}



// Menampilkan Form Tulis
    public function create()
    {
        return Inertia::render('News/Create');
    }

    // Menyimpan Berita ke Database
public function store(Request $request)
{
    // Validasi data yang masuk
    $request->validate([
        'title_id' => 'required|min:5',
        'content_id' => 'required',
        'slug' => 'required', // Tambahkan validasi slug
         'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Maksimal 2MB
    ]);
$imagePath = null;
    if ($request->hasFile('image')) {
    $file = $request->file('image');
    // Ambil nama asli file (misal: "berita-ykk.jpg")
        $originalName = $file->getClientOriginalName();    
    // Simpan file dan ambil nama jalurnya
         $imagePath = $file->storeAs('news', $originalName, 'public');
    }
    
    \App\Models\News::create([
        'title_id' => $request->title_id,
        'title_en' => $request->title_en,
        'content_id' => $request->content_id,
        'content_en' => $request->content_en,
        'slug' => $request->slug, // MASUKKAN INI
        'author_id' => auth()->id(),
         'image' => $imagePath, // Simpan path gambar
    ]);

        // Lempar kembali ke Dashboard dengan pesan sukses
        return redirect()->route('dashboard')->with('message', 'Intelligence News Published Successfully!');
    }
public function edit(News $news)
{
    return Inertia::render('News/Edit', [
        'news' => $news
    ]);
}

public function update(Request $request, News $news)
{
    $request->validate([
        'title_id' => 'required',
        'title_en' => 'required',
        'content_id' => 'required',
        'content_en' => 'required',
    ]);

    $news->update($request->all());

    return redirect()->route('news.index')->with('message', 'Intelligence News Updated!');
}


}