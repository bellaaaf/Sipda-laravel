@extends('layouts.app')

@section('title', 'Buat Laporan Bencana')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-danger text-white rounded-top-4 py-3 px-4">
                    <h4 class="fw-bold mb-1 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined msf">campaign</span>Laporkan Bencana
                    </h4>
                    <p class="mb-0 opacity-75 small">Informasi yang Anda berikan akan membantu petugas BPBD merespons lebih cepat</p>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <strong>Terjadi kesalahan:</strong>
                        <ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                    @endif

                    <form action="{{ route('user.laporan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Jenis Bencana <span class="text-danger">*</span></label>
                                <select name="jenis_bencana" class="form-select @error('jenis_bencana') is-invalid @enderror" required>
                                    <option value="">-- Pilih Jenis Bencana --</option>
                                    @foreach($jenis as $j)
                                    <option value="{{ $j->nama_bencana }}" {{ old('jenis_bencana') === $j->nama_bencana ? 'selected' : '' }}>{{ $j->nama_bencana }}</option>
                                    @endforeach
                                </select>
                                @error('jenis_bencana')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Tingkat Keparahan <span class="text-danger">*</span></label>
                                <select name="tingkat_keparahan" class="form-select" required>
                                    @foreach(['Ringan', 'Sedang', 'Berat', 'Sangat Berat'] as $t)
                                    <option value="{{ $t }}" {{ old('tingkat_keparahan', 'Sedang') === $t ? 'selected' : '' }}>{{ $t }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small">Lokasi Kejadian <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <span class="material-symbols-outlined msf ms-sm">location_on</span>
                                    </span>
                                    <input type="text" name="lokasi_kejadian"
                                        class="form-control @error('lokasi_kejadian') is-invalid @enderror"
                                        placeholder="Alamat lengkap lokasi bencana"
                                        value="{{ old('lokasi_kejadian') }}" required id="lokasiInput">
                                    <button type="button" class="btn btn-outline-secondary d-flex align-items-center gap-1" onclick="getLocation()">
                                        <span class="material-symbols-outlined ms-sm">my_location</span>GPS
                                    </button>
                                </div>
                                @error('lokasi_kejadian')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <input type="hidden" name="latitude" id="latInput" value="{{ old('latitude') }}">
                            <input type="hidden" name="longitude" id="lngInput" value="{{ old('longitude') }}">

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Tanggal Kejadian <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_kejadian"
                                    class="form-control @error('tanggal_kejadian') is-invalid @enderror"
                                    value="{{ old('tanggal_kejadian', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                                @error('tanggal_kejadian')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Telepon <span class="text-muted">(opsional)</span></label>
                                <input type="tel" name="telepon" class="form-control"
                                    placeholder="081234567890"
                                    value="{{ old('telepon', auth()->user()->no_telp) }}">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small">Deskripsi Kejadian <span class="text-danger">*</span></label>
                                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
                                    rows="5" placeholder="Ceritakan kejadian bencana secara detail (min. 20 karakter)..." required>{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Korban Jiwa</label>
                                <input type="number" name="korban_jiwa" class="form-control" min="0" value="{{ old('korban_jiwa', 0) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Korban Luka</label>
                                <input type="number" name="korban_luka" class="form-control" min="0" value="{{ old('korban_luka', 0) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Rumah Rusak</label>
                                <input type="number" name="rumah_rusak" class="form-control" min="0" value="{{ old('rumah_rusak', 0) }}">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small">Foto Kejadian <span class="text-muted">(opsional, maks 2MB)</span></label>
                                <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror"
                                    accept="image/jpeg,image/png,image/webp" id="fotoInput">
                                @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div id="fotoPreview" class="mt-2" style="display:none;">
                                    <img id="previewImg" src="" class="rounded-3" style="max-height:200px;object-fit:cover;" alt="Preview">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="alert alert-info d-flex align-items-start gap-2 small">
                                    <span class="material-symbols-outlined msf ms-sm flex-shrink-0 mt-1">info</span>
                                    Laporan Anda akan segera ditinjau oleh petugas BPBD. Mohon isi informasi dengan benar dan jujur. Laporan palsu dapat dikenakan sanksi hukum.
                                </div>
                            </div>

                            <div class="col-12 d-flex gap-3 flex-wrap">
                                <button type="submit" class="btn btn-danger px-5 fw-bold d-flex align-items-center gap-2">
                                    <span class="material-symbols-outlined msf ms-sm">send</span>Kirim Laporan
                                </button>
                                <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary">Batal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(pos => {
            document.getElementById('latInput').value = pos.coords.latitude.toFixed(8);
            document.getElementById('lngInput').value = pos.coords.longitude.toFixed(8);

            fetch(`https://nominatim.openstreetmap.org/reverse?lat=${pos.coords.latitude}&lon=${pos.coords.longitude}&format=json`)
                .then(r => r.json())
                .then(d => {
                    if (d.display_name) document.getElementById('lokasiInput').value = d.display_name;
                });
        }, () => alert('GPS tidak tersedia atau ditolak.'));
    }
}

document.getElementById('fotoInput').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('fotoPreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
