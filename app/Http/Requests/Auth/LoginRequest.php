<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
  public function rules(): array
{
    return [
        // Gunakan 'login_identity' agar sinkron dengan file Login.jsx Bapak
        'login_identity' => ['required', 'string'], 
        'password'       => ['required', 'string'],
    ];
}

public function authenticate(): void
{
    $this->ensureIsNotRateLimited();

    // Ambil input dan bersihkan spasi (Sangat Penting untuk Nomor Anggota)
    $loginValue = trim($this->input('login_identity'));
    
    // Cek apakah input berupa email atau nomor anggota
    $fieldType = filter_var($loginValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'member_number';

    // Eksekusi Login
    if (! Auth::attempt([$fieldType => $loginValue, 'password' => $this->password], $this->boolean('remember'))) {
        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'login_identity' => trans('auth.failed'),
        ]);
    }

    RateLimiter::clear($this->throttleKey());
}

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));
        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
{
    // Sesuaikan kunci limit dengan variabel baru
    return Str::transliterate(Str::lower($this->string('login_identity')).'|'.$this->ip());
}
}