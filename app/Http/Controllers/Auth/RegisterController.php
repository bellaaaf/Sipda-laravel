<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        if (Auth::check()) return redirect()->route('user.dashboard');
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'full_name'        => 'required|string|min:3|max:100|regex:/^[a-zA-Z\s]+$/',
            'email'            => 'required|email|unique:users,email',
            'no_telp'          => 'nullable|regex:/^[0-9]{10,13}$/',
            'password'         => 'required|min:6|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
        ], [
            'full_name.required'  => 'Nama lengkap harus diisi.',
            'full_name.min'       => 'Nama minimal 3 karakter.',
            'full_name.regex'     => 'Nama hanya boleh berisi huruf dan spasi.',
            'email.required'      => 'Email harus diisi.',
            'email.unique'        => 'Email sudah terdaftar. Gunakan email lain.',
            'no_telp.regex'       => 'Nomor telepon harus 10-13 digit angka.',
            'password.min'        => 'Password minimal 6 karakter.',
            'password.confirmed'  => 'Konfirmasi password tidak sama.',
            'password.regex'      => 'Password harus mengandung huruf besar, huruf kecil, dan angka.',
        ]);

        $user = User::create([
            'full_name' => $request->full_name,
            'email'     => $request->email,
            'no_telp'   => $request->no_telp,
            'password'  => Hash::make($request->password),
            'role'      => 'masyarakat',
            'is_active' => true,
        ]);

        Auth::login($user);

        return redirect()->route('user.dashboard')->with('success', 'Pendaftaran berhasil! Selamat datang di SIPDA Bandung.');
    }
}
