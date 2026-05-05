<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
   public function store(Request $request)
{
    // 1. Validasi input: Nama variabel HARUS 'login_identity' agar sinkron dengan React
    $request->validate([
        'login_identity' => 'required|string', 
        'password' => 'required|string',
    ]);

    // 2. Ambil nilai dan bersihkan spasi (Penting untuk Nomor Anggota)
    $loginValue = trim($request->login_identity);

    // 3. Deteksi Email atau Nomor Anggota
    $fieldType = filter_var($loginValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'member_number';

    // 4. Proses Login
    if (!Auth::attempt([$fieldType => $loginValue, 'password' => $request->password], $request->remember)) {
        return back()->withErrors([
            'login_identity' => 'Kredensial yang Anda masukkan tidak terdaftar di sistem Digestex.',
        ]);
    }

    $request->session()->regenerate();

    // Arahkan ke Intelligence Center sesuai keinginan Bapak
    return redirect()->intended('/intelligence-center'); 
}


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}