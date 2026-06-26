@extends('layouts.app')

@section('title', 'Daftar Akun')

@section('content')
<div class="auth-wrapper py-4" style="min-height:auto;">
    <div class="auth-card" style="max-width:500px;">
        <div class="auth-header">
            <span class="material-symbols-outlined msf ms-xxl d-block mb-2">person_add</span>
            <h4 class="fw-bold mb-1">Buat Akun Baru</h4>
            <p class="mb-0 opacity-70 small">Bergabung dengan SIPDA Bandung</p>
        </div>

        <div class="auth-body">
            @if($errors->any())
                <div class="alert alert-danger d-flex align-items-center gap-2 mb-3">
                    <span class="material-symbols-outlined msf ms-sm flex-shrink-0">error</span>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="full_name"
                        class="form-control @error('full_name') is-invalid @enderror"
                        placeholder="Nama sesuai KTP"
                        value="{{ old('full_name') }}" required>
                    @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="contoh@email.com"
                        value="{{ old('email') }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">
                        Nomor Telepon <span class="text-muted">(opsional)</span>
                    </label>
                    <input type="tel" name="no_telp"
                        class="form-control @error('no_telp') is-invalid @enderror"
                        placeholder="081234567890"
                        value="{{ old('no_telp') }}">
                    @error('no_telp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Min. 6 karakter (besar, kecil, angka)" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text d-flex align-items-center gap-1 mt-1">
                        <span class="material-symbols-outlined ms-sm">info</span>
                        Harus mengandung huruf besar, huruf kecil, dan angka
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold small">Konfirmasi Password <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation"
                        class="form-control" placeholder="Ulangi password" required>
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-bold py-2 d-flex align-items-center justify-content-center gap-2">
                    <span class="material-symbols-outlined ms-sm">how_to_reg</span>Daftar Sekarang
                </button>
            </form>

            <div class="text-center mt-3 small">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">Login di sini</a>
            </div>
            <div class="text-center mt-2">
                <a href="{{ route('home') }}" class="text-muted small text-decoration-none d-inline-flex align-items-center gap-1">
                    <span class="material-symbols-outlined ms-sm">arrow_back</span>Kembali ke beranda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
