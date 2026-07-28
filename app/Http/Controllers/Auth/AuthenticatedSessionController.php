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
    $request->validate([
        'login_identity' => 'required|string',
        'password'       => 'required|string',
    ]);

    $loginValue = trim(
        $request->login_identity
    );

    $fieldType =
        filter_var(
            $loginValue,
            FILTER_VALIDATE_EMAIL
        )
            ? 'email'
            : 'member_number';

    if (
        ! Auth::attempt(
            [
                $fieldType => $loginValue,
                'password' => $request->password,
            ],
            $request->remember
        )
    ) {
        return back()->withErrors([
            'login_identity' =>
                'Kredensial yang Anda masukkan tidak terdaftar di sistem Digestex.',
        ]);
    }

    $request->session()->regenerate();

    $user = auth()->user();

    /*
    |--------------------------------------------------------------------------
    | Admin
    |--------------------------------------------------------------------------
    */

    if (
        in_array(
            $user->role,
            [
                'admin',
                'super_admin',
            ]
        )
    ) {
        return redirect()->intended(
            route(
                'admin.dashboard'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Resume Onboarding™
    |--------------------------------------------------------------------------
    */

    if (
    ! $user->isOnboardingCompleted()
        ) {
            return redirect()->to(
                $user->getOnboardingRoute()
            );
        }

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    return redirect()->intended(
        route('dashboard')
    );
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