<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        $redirectUrl = config('services.google.redirect');
        dd('Redirect URI yang dikirim ke Google: ' . $redirectUrl);
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($user) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar'    => $googleUser->getAvatar(),
                ]);
            } else {
                $user = User::create([
                    'full_name' => $googleUser->getName(),
                    'email'     => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar'    => $googleUser->getAvatar(),
                    'role'      => 'masyarakat',
                    'is_active' => true,
                ]);
            }

            if (!$user->is_active) {
                return redirect()->route('login')->with('error', 'Akun Anda telah dinonaktifkan.');
            }

            Auth::login($user);

            return match($user->role) {
                'admin'   => redirect()->route('admin.dashboard'),
                'petugas' => redirect()->route('petugas.dashboard'),
                default   => redirect()->route('user.dashboard'),
            };
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Login Google gagal. Silakan coba lagi.');
        }
    }
}
