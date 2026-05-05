<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Member;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Logika Login Manual (Karena kita menggunakan tabel kustom)
        $member = Member::where('email', $credentials['email'])->first();

        if ($member && Hash::check($credentials['password'], $member->password)) {
            Auth::login($member);
            $request->session()->regenerate();

            // Update last_login secara otomatis
            $member->update(['last_login' => now()]);

            return redirect()->intended('/members');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
