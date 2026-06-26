@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <h3 class="fw-bold mb-4 d-flex align-items-center gap-2">
                <span class="material-symbols-outlined text-primary">manage_accounts</span>Edit Profil
            </h3>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    @if(session('success'))
                    <div class="alert alert-success d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined msf ms-sm">check_circle</span>{{ session('success') }}
                    </div>
                    @endif

                    <div class="text-center mb-4">
                        @if($user->avatar)
                            <img src="{{ $user->avatar }}" class="rounded-circle border border-3 border-primary" width="80" height="80" alt="Avatar">
                        @else
                            <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width:80px;height:80px;font-size:32px;">
                                {{ strtoupper(substr($user->full_name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="mt-2">
                            <span class="badge rounded-pill bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'petugas' ? 'primary' : 'success') }} px-3 py-2">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                    </div>

                    <form action="{{ route('user.profil.update') }}" method="POST">
                        @csrf @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Nama Lengkap *</label>
                            <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror"
                                value="{{ old('full_name', $user->full_name) }}" required>
                            @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Email</label>
                            <input type="email" class="form-control bg-light" value="{{ $user->email }}" disabled>
                            <div class="form-text">Email tidak dapat diubah.</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold small">Nomor Telepon</label>
                            <input type="tel" name="no_telp" class="form-control @error('no_telp') is-invalid @enderror"
                                value="{{ old('no_telp', $user->no_telp) }}" placeholder="081234567890">
                            @error('no_telp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <hr>

                        <h6 class="fw-bold mb-3">Ganti Password <span class="text-muted small">(opsional)</span></h6>

                        @if($errors->has('current_password'))
                        <div class="alert alert-danger small">{{ $errors->first('current_password') }}</div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Password Saat Ini</label>
                            <input type="password" name="current_password" class="form-control" placeholder="Masukkan password lama">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Password Baru</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Min. 6 karakter">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold small">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                        </div>

                        <div class="d-flex gap-3 flex-wrap">
                            <button type="submit" class="btn btn-primary px-4 d-flex align-items-center gap-2">
                                <span class="material-symbols-outlined ms-sm">save</span>Simpan Perubahan
                            </button>
                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
