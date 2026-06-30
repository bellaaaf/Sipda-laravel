<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\Petugas;
use App\Http\Controllers\PublicBencanaController;
use App\Http\Controllers\PublicBeritaController;
use App\Http\Controllers\User;
use Illuminate\Support\Facades\Route;

// ═══════════════════════════════════════════════
// PUBLIC ROUTES
// ═══════════════════════════════════════════════
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/layanan', [HomeController::class, 'layanan'])->name('layanan');
Route::get('/bencana', [PublicBencanaController::class, 'index'])->name('bencana.index');
Route::get('/bencana/{bencana}', [PublicBencanaController::class, 'show'])->name('bencana.show');
Route::get('/berita', [PublicBeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{berita}', [PublicBeritaController::class, 'show'])->name('berita.show');

// ═══════════════════════════════════════════════
// AUTH ROUTES
// ═══════════════════════════════════════════════
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Google OAuth
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback');

// ═══════════════════════════════════════════════
// NOTIFIKASI (admin + petugas)
// ═══════════════════════════════════════════════
Route::middleware(['auth', 'role:admin,petugas'])->prefix('notifikasi')->name('notifikasi.')->group(function () {
    Route::get('/unread',             [NotifikasiController::class, 'unread'])->name('unread');
    Route::post('/{notifikasi}/baca', [NotifikasiController::class, 'baca'])->name('baca');
    Route::post('/baca-semua',        [NotifikasiController::class, 'bacaSemua'])->name('baca-semua');
});

// ═══════════════════════════════════════════════
// USER (MASYARAKAT) ROUTES
// ═══════════════════════════════════════════════
Route::middleware(['auth', 'role:masyarakat,admin,petugas'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [User\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profil', [User\ProfilController::class, 'edit'])->name('profil');
    Route::put('/profil', [User\ProfilController::class, 'update'])->name('profil.update');

    Route::middleware('role:masyarakat')->group(function () {
        Route::get('/laporan', [User\LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/buat', [User\LaporanController::class, 'create'])->name('laporan.create');
        Route::post('/laporan', [User\LaporanController::class, 'store'])->name('laporan.store');
        Route::get('/laporan/{laporan}', [User\LaporanController::class, 'show'])->name('laporan.show');
    });
});

// ═══════════════════════════════════════════════
// PETUGAS ROUTES
// ═══════════════════════════════════════════════
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', [Petugas\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/laporan', [Petugas\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/{laporan}', [Petugas\LaporanController::class, 'show'])->name('laporan.show');
    Route::post('/laporan/{laporan}/tinjau', [Petugas\LaporanController::class, 'tinjau'])->name('laporan.tinjau');
    Route::get('/laporan/export/excel', [Petugas\LaporanController::class, 'exportExcel'])->name('laporan.export');
});

// ═══════════════════════════════════════════════
// ADMIN ROUTES
// ═══════════════════════════════════════════════
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Bencana
    Route::get('/bencana', [Admin\BencanaController::class, 'index'])->name('bencana.index');
    Route::post('/bencana', [Admin\BencanaController::class, 'store'])->name('bencana.store');
    Route::put('/bencana/{bencana}', [Admin\BencanaController::class, 'update'])->name('bencana.update');
    Route::delete('/bencana/{bencana}', [Admin\BencanaController::class, 'destroy'])->name('bencana.destroy');
    Route::post('/bencana/{bencana}/update-perkembangan', [Admin\BencanaController::class, 'updatePerkembangan'])->name('bencana.update-perkembangan');

    // Berita
    Route::get('/berita', [Admin\BeritaController::class, 'index'])->name('berita.index');
    Route::post('/berita', [Admin\BeritaController::class, 'store'])->name('berita.store');
    Route::put('/berita/{berita}', [Admin\BeritaController::class, 'update'])->name('berita.update');
    Route::delete('/berita/{berita}', [Admin\BeritaController::class, 'destroy'])->name('berita.destroy');

    // Laporan
    Route::get('/laporan', [Admin\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/{laporan}', [Admin\LaporanController::class, 'show'])->name('laporan.show');
    Route::post('/laporan/{laporan}/status', [Admin\LaporanController::class, 'updateStatus'])->name('laporan.status');
    Route::delete('/laporan/{laporan}', [Admin\LaporanController::class, 'destroy'])->name('laporan.destroy');

    // User management
    Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::post('/users', [Admin\UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [Admin\UserController::class, 'destroy'])->name('users.destroy');
});
