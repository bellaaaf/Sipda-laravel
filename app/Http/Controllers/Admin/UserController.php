<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderByRaw("FIELD(role,'admin','petugas','masyarakat')")->orderByDesc('id')->get();
        return view('admin.user.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|min:3|max:100',
            'email'     => 'required|email|unique:users,email',
            'role'      => 'required|in:masyarakat,petugas,admin',
            'password'  => 'required|min:6|confirmed',
            'no_telp'   => 'nullable|regex:/^[0-9]{10,13}$/',
        ]);

        $user = User::create([
            'full_name' => $request->full_name,
            'email'     => $request->email,
            'role'      => $request->role,
            'no_telp'   => $request->no_telp,
            'password'  => Hash::make($request->password),
            'is_active' => true,
        ]);

        LogAktivitas::create([
            'user_id'   => Auth::id(),
            'aksi'      => 'Tambah User',
            'deskripsi' => "User {$user->full_name} ({$user->role}) ditambahkan",
            'ip_address'=> request()->ip(),
        ]);

        return back()->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        if ($user->role === 'admin' && Auth::id() !== $user->id) {
            return back()->with('error', 'Tidak dapat mengedit akun admin lain.');
        }

        $request->validate([
            'full_name' => 'required|string|min:3|max:100',
            'email'     => "required|email|unique:users,email,{$user->id}",
            'role'      => 'required|in:masyarakat,petugas,admin',
            'is_active' => 'required|boolean',
            'no_telp'   => 'nullable|regex:/^[0-9]{10,13}$/',
        ]);

        $data = $request->only(['full_name', 'email', 'role', 'is_active', 'no_telp']);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Akun admin tidak dapat dihapus.');
        }

        $user->delete();

        LogAktivitas::create([
            'user_id'   => Auth::id(),
            'aksi'      => 'Hapus User',
            'deskripsi' => "User {$user->full_name} dihapus",
            'ip_address'=> request()->ip(),
        ]);

        return back()->with('success', 'User berhasil dihapus.');
    }
}
