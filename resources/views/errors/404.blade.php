@extends('layouts.app')

@section('title', '404 - Halaman Tidak Ditemukan')

@section('content')
<div class="container py-5 text-center">
    <div class="py-5">
        <span class="material-symbols-outlined text-warning d-block mb-3" style="font-size:80px;">search_off</span>
        <h1 class="display-4 fw-bold">404</h1>
        <h3 class="text-muted mb-4">Halaman Tidak Ditemukan</h3>
        <p class="text-muted fs-5 mb-4">Maaf, halaman yang Anda cari tidak ada atau telah dipindahkan.</p>
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
