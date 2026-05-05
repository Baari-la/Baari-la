<?php

namespace App\Http\Controllers;

use App\Models\Member; // Memanggil Model Member yang tadi sudah berhasil
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        // Menarik semua data perusahaan dari database
        $members = Member::all();
        
        // Mengirim data tersebut ke halaman resources/views/members/index.blade.php
        return view('members.index', compact('members'));
    }

    public function home()
{
    // Kita bisa memanggil data ringkasan di sini nanti
    $data = [
        'locale' => app()->getLocale(),
        'total_export' => '11.9',
        'cotton_price' => '71.31'
    ];
    return view('home', $data);
}

}
