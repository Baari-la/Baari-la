<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Find user by google_id first, then fallback to email
            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            if (!$user) {
                // New user: Create account
                $user = User::create([
                    'name'      => $googleUser->name,
                    'email'     => $googleUser->email,
                    'google_id' => $googleUser->id,
                    // Use a random password string
                    'password'  => bcrypt(Str::random(16)), 
                ]);
            } else {
                // Existing user: Ensure google_id is saved
                $user->update(['google_id' => $googleUser->id]);
            }

            Auth::login($user, true);

            // intended() will go to the previous URL if Laravel caught it, 
            // otherwise it goes to '/'
            return redirect()->intended('/');

        } catch (\Exception $e) {
            // It's good to log the error for debugging
            \Log::error('Google Auth Error: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Gagal login dengan Google');
        }
    }
}
