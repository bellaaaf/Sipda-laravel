@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <span class="material-symbols-outlined msf ms-xxl d-block mb-2">lock</span>
            <h4 class="fw-bold mb-1">Masuk ke SIPDA</h4>
            <p class="mb-0 opacity-70 small">Sistem Informasi Peringatan Dini Bencana Bandung</p>
        </div>

        <div class="auth-body">
            @if($errors->any())
                <div class="alert alert-danger d-flex align-items-center gap-2 mb-3">
                    <span class="material-symbols-outlined msf ms-sm flex-shrink-0">error</span>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Email</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <span class="material-symbols-outlined ms-sm">mail</span>
                        </span>
                        <input type="email" name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="contoh@email.com"
                            value="{{ old('email') }}" required autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <span class="material-symbols-outlined ms-sm">lock</span>
                        </span>
                        <input type="password" name="password" id="passwordInput"
                            class="form-control" placeholder="Password" required>
                        <button type="button" class="btn btn-outline-secondary" onclick="togglePwd()">
                            <span class="material-symbols-outlined ms-sm" id="eyeIcon">visibility_off</span>
                        </button>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label small" for="remember">Ingat saya</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-bold py-2 d-flex align-items-center justify-content-center gap-2">
                    <span class="material-symbols-outlined ms-sm">login</span>Masuk
                </button>
            </form>

            <div class="my-3 text-center text-muted small">— atau masuk dengan —</div>

            <a href="{{ route('auth.google') }}" class="btn btn-outline-danger w-100 mb-3 d-flex align-items-center justify-content-center gap-2">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Masuk dengan Google
            </a>

            <div class="text-center small">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">Daftar sekarang</a>
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

@push('scripts')
<script>
function togglePwd() {
    const input = document.getElementById('passwordInput');
    const icon  = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.textContent = 'visibility';
    } else {
        input.type = 'password';
        icon.textContent = 'visibility_off';
    }
}
</script>
@endpush
