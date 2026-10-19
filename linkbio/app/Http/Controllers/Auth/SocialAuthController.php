<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class SocialAuthController extends Controller
{
    public function redirectToGoogle(): mixed
    {
        if (! config('services.google.client_id') || ! config('services.google.client_secret')) {
            return redirect()->route('login')->withErrors([
                'oauth' => 'Login Google belum dikonfigurasi. Tambahkan GOOGLE_CLIENT_ID dan GOOGLE_CLIENT_SECRET di file .env.',
            ]);
        }

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $exception) {
            Log::warning('Google OAuth callback failed', ['message' => $exception->getMessage()]);

            return redirect()->route('login')->withErrors([
                'oauth' => 'Login Google dibatalkan atau gagal. Silakan coba lagi.',
            ]);
        }

        if (! $googleUser->getEmail()) {
            return redirect()->route('login')->withErrors([
                'oauth' => 'Google tidak mengirim alamat email yang bisa digunakan.',
            ]);
        }

        $user = User::where('google_id', $googleUser->getId())->first();

        if (! $user) {
            $user = User::where('email', strtolower($googleUser->getEmail()))->first();
        }

        if (! $user) {
            $user = User::create([
                'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: 'TreeLink User',
                'username' => $this->uniqueUsername($googleUser->getNickname() ?: Str::before($googleUser->getEmail(), '@')),
                'email' => strtolower($googleUser->getEmail()),
                'google_id' => $googleUser->getId(),
                'password' => Hash::make(Str::random(40)),
                'email_verified_at' => now(),
            ]);

            $user->getOrCreateProfile();
        } elseif (! $user->google_id) {
            $user->forceFill([
                'google_id' => $googleUser->getId(),
                'email_verified_at' => $user->email_verified_at ?: now(),
            ])->save();
        }

        if (! $user->is_active) {
            return redirect()->route('login')->withErrors([
                'oauth' => 'Akun Anda telah dinonaktifkan. Silakan hubungi admin.',
            ]);
        }

        Auth::login($user, true);
        request()->session()->regenerate();

        return redirect()->intended(route('dashboard.index'));
    }

    private function uniqueUsername(string $source): string
    {
        $username = Str::lower(Str::of($source)->replaceMatches('/[^a-zA-Z0-9_.]+/', '_')->trim('_')->substr(0, 24));
        $username = strlen($username) >= 3 ? $username : 'user_'.Str::lower(Str::random(6));

        $base = $username;
        $counter = 1;

        while (User::where('username', $username)->exists()
            || in_array($username, \App\Http\Controllers\PublicProfileController::RESERVED_USERNAMES, true)) {
            $suffix = '_'.$counter++;
            $username = substr($base, 0, 30 - strlen($suffix)).$suffix;
        }

        return $username;
    }
}
