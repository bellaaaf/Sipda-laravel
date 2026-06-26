@extends('layouts.app')

@section('title', '403 - Akses Ditolak')

@section('content')
<div class="container py-5 text-center">
    <div class="py-5">
        <span class="material-symbols-outlined msf text-danger d-block mb-3" style="font-size:80px;">gpp_bad</span>
        <h1 class="display-4 fw-bold">403</h1>
        <h3 class="text-muted mb-4">Akses Ditolak</h3>
        <p class="text-muted fs-5 mb-4">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-lg d-flex align-items-center gap-2">
                <span class="material-symbols-outlined">arrow_back</span>Kembali
            </a>
            <a href="{{ route('home') }}" class="btn btn-primary btn-lg d-flex align-items-center gap-2">
                <span class="material-symbols-outlined">home</span>Beranda
            </a>
        </div>
    </div>
</div>
@endsection
