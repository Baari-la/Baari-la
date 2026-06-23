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
        'slug' => strlen($request->text) < 255 ? Str::slug($translatedText) : null
    ]);
}

// app/Http/Controllers/NewsController.php

public function suggestMeta(Request $request)
{
    // Bersihkan konten dari tag HTML agar tidak ikut terbaca oleh Google
    $cleanContent = strip_tags($request->content_en ?? $request->content_id);
    
    // 1. Meta Title (Max 60 karakter agar tidak terpotong di Google)
    // Format: Judul Berita | Brand Global
    $metaTitle = Str::limit($request->title_en ?? $request->title_id, 50) . " | DigestexGlobal";
    
    // 2. Meta Description (Max 160 karakter agar informatif di Google)
    $metaDescription = Str::limit($cleanContent, 155);
    
    // 3. Keywords Otomatis
    $keywords = "indonesia textile industry, garment export data, " . Str::slug($request->title_en ?? $request->title_id, ', ');

    return response()->json([
        'meta_title' => $metaTitle,
        'meta_description' => $metaDescription,
        'meta_keywords' => $keywords
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
   $relatedNews = News::where('id','!=',$news->id)
    ->latest()
    ->take(4)
    ->get();
   
    return inertia('News/Show', [
        'news' => $news,
        'relatedNews' => $relatedNews,
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
        'summary_id' => 'nullable|string|max:2000',
        'content_id' => 'required',

        'title_en' => 'nullable|string',
    'summary_en' => 'nullable|string|max:2000',
    'content_en' => 'nullable|string',

        'slug' => 'required|unique:news,slug', // Tambahkan validasi slug
        'category' => 'required|string|max:100',
         'partner_name' => 'nullable|string|max:255',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Maksimal 2MB
    ]);
$imagePath = null;
    if ($request->hasFile('image')) {
    $file = $request->file('image');
    // Ambil nama asli file (misal: "berita-ykk.jpg")
        // $originalName = $file->getClientOriginalName();
        $originalName = time() . '_' . $file->getClientOriginalName(); // Tambah timestamp agar tidak bentrok    
    // Simpan file dan ambil nama jalurnya
         $imagePath = $file->storeAs('news', $originalName, 'public');
    }
    
    \App\Models\News::create([
        'title_id' => $request->title_id,
    'summary_id' => $request->summary_id,
    'content_id' => $request->content_id,

    'title_en' => $request->title_en,
    'summary_en' => $request->summary_en,
    'content_en' => $request->content_en,

    'slug' => $request->slug,

    'author_id' => auth()->id(),

    'category' => $request->category,
    'partner_name' => $request->partner_name,

    'image' => $imagePath,

    'meta_title' => $request->meta_title,
    'meta_description' => $request->meta_description,
    'meta_keywords' => $request->meta_keywords,
    ]);

        // Lempar kembali ke Dashboard dengan pesan sukses
        return redirect()->route('admin.news.index')
    ->with('message', 'Global Intelligence News Published Successfully!');
    }
public function edit(News $news)
{
    return Inertia::render('News/Edit', [
        'news' => $news
    ]);
}

public function update(Request $request, News $news)
{
    //   dd($request->all());
    $request->validate([
        'title_id' => 'required',
        'summary_id' => 'nullable|string|max:2000',
        'title_en' => 'required',
          'summary_en' => 'nullable|string|max:2000',
        'content_id' => 'required',
        'content_en' => 'required',
    ]);

    $data = $request->all(); // Ambil semua data input

    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $originalName = time() . '_' . $file->getClientOriginalName();
        $data['image'] = $file->storeAs('news', $originalName, 'public');
    }

    // PERBAIKAN: Gunakan $data, BUKAN $request->all()
    $news->update($data); 

    return redirect()->route('admin.news.index')->with('message', 'Intelligence News Updated!');
}


}