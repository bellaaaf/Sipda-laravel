<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) return $this->redirectBasedOnRole();
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Email harus diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password harus diisi.',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.']);
            }

            $request->session()->regenerate();

            LogAktivitas::create([
                'user_id'    => $user->id,
                'aksi'       => 'Login',
                'deskripsi'  => "User {$user->full_name} login ke sistem",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return $this->redirectBasedOnRole();
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->withInput($request->except('password'));
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            LogAktivitas::create([
                'user_id'    => Auth::id(),
                'aksi'       => 'Logout',
                'deskripsi'  => 'User logout dari sistem',
                'ip_address' => $request->ip(),
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda berhasil logout.');
    }

    protected function redirectBasedOnRole()
    {
        return match(Auth::user()->role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'petugas' => redirect()->route('petugas.dashboard'),
            default   => redirect()->route('home')->with('success', 'Selamat datang kembali, ' . Auth::user()->full_name . '!'),
        };
    }
}
