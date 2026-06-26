@extends('layouts.admin')

@section('title', 'Kelola User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0 d-flex align-items-center gap-2">
        <span class="material-symbols-outlined text-primary">group</span>Kelola User
    </h3>
    <button class="btn btn-success d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
        <span class="material-symbols-outlined ms-sm">person_add</span>Tambah User
    </button>
</div>

<div class="content-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>#</th><th>Nama</th><th>Email</th><th>Telepon</th><th>Role</th><th>Status</th><th>Terdaftar</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($users as $i => $u)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td class="fw-semibold">{{ $u->full_name }}</td>
                    <td class="text-muted small">{{ $u->email }}</td>
                    <td class="text-muted small">{{ $u->no_telp ?? '-' }}</td>
                    <td>
                        @if($u->role === 'admin')
                            <span class="badge rounded-pill bg-danger d-inline-flex align-items-center gap-1">
                                <span class="material-symbols-outlined msf" style="font-size:11px;">workspace_premium</span>Admin
                            </span>
                        @elseif($u->role === 'petugas')
                            <span class="badge rounded-pill bg-primary d-inline-flex align-items-center gap-1">
                                <span class="material-symbols-outlined msf" style="font-size:11px;">shield</span>Petugas
                            </span>
                        @else
                            <span class="badge rounded-pill bg-success d-inline-flex align-items-center gap-1">
                                <span class="material-symbols-outlined msf" style="font-size:11px;">group</span>Masyarakat
                            </span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-{{ $u->is_active ? 'success' : 'secondary' }}">
                            {{ $u->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="text-muted small">{{ $u->created_at->format('d/m/Y') }}</td>
                    <td>
                        @if($u->role !== 'admin')
                        <button class="btn btn-sm btn-outline-warning me-1 d-inline-flex align-items-center gap-1" onclick="editUser({{ $u->id }})">
                            <span class="material-symbols-outlined ms-sm">edit</span>
                        </button>
                        <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1"
                                onclick="confirmDelete(this.closest('form'), 'Hapus User', 'Akun {{ addslashes($u->full_name) }} akan dihapus permanen.')">
                                <span class="material-symbols-outlined ms-sm">delete</span>
                            </button>
                        </form>
                        @else
                        <span class="text-muted small d-flex align-items-center gap-1">
                            <span class="material-symbols-outlined msf ms-sm">lock</span>Protected
                        </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada data user.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Tambah User --}}
<div class="modal fade" id="modalTambahUser" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <div class="modal-icon-wrap me-3">
                    <span class="material-symbols-outlined msf ms-sm">person_add</span>
                </div>
                <div class="flex-grow-1">
                    <h5 class="modal-title mb-0">Tambah User Baru</h5>
                    <div class="modal-subtitle">Buat akun pengguna baru untuk sistem SIPDA</div>
                </div>
                <button type="button" class="btn-close btn-close-white ms-3" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="modal-section-label">
                        <span class="material-symbols-outlined ms-sm">badge</span>Informasi Akun
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="full_name" class="form-control" placeholder="Nama lengkap pengguna" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="email@example.com" required>
                    </div>
                    <div class="row g-3 mb-0">
                        <div class="col-md-6">
                            <label class="form-label">No. Telepon</label>
                            <input type="tel" name="no_telp" class="form-control" placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select name="role" class="form-select" required>
                                <option value="masyarakat">Masyarakat</option>
                                <option value="petugas">Petugas BPBD</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-section">
                        <div class="modal-section-label">
                            <span class="material-symbols-outlined ms-sm">lock</span>Keamanan
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary d-flex align-items-center gap-1" data-bs-dismiss="modal">
                        <span class="material-symbols-outlined ms-sm">close</span>Batal
                    </button>
                    <button type="submit" class="btn btn-success d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined msf ms-sm">person_add</span>Buat Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit User --}}
<div class="modal fade" id="modalEditUser" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <div class="modal-icon-wrap me-3">
                    <span class="material-symbols-outlined msf ms-sm">manage_accounts</span>
                </div>
                <div class="flex-grow-1">
                    <h5 class="modal-title mb-0">Edit User</h5>
                    <div class="modal-subtitle">Perbarui informasi akun pengguna</div>
                </div>
                <button type="button" class="btn-close btn-close-white ms-3" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="formEditUser">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="modal-section-label">
                        <span class="material-symbols-outlined ms-sm">badge</span>Informasi Akun
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="full_name" id="edit_full_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="edit_email" class="form-control" required>
                    </div>
                    <div class="row g-3 mb-0">
                        <div class="col-md-6">
                            <label class="form-label">No. Telepon</label>
                            <input type="tel" name="no_telp" id="edit_no_telp" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select name="role" id="edit_role" class="form-select" required>
                                <option value="masyarakat">Masyarakat</option>
                                <option value="petugas">Petugas BPBD</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Status Akun</label>
                            <select name="is_active" id="edit_is_active" class="form-select">
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-section">
                        <div class="modal-section-label">
                            <span class="material-symbols-outlined ms-sm">lock_reset</span>Ubah Password
                        </div>
                        <p class="text-muted small mb-3" style="text-transform:none;letter-spacing:0;">Kosongkan jika tidak ingin mengubah password.</p>
                        <div class="mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password" class="form-control" placeholder="Password baru (opsional)">
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi password baru">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary d-flex align-items-center gap-1" data-bs-dismiss="modal">
                        <span class="material-symbols-outlined ms-sm">close</span>Batal
                    </button>
                    <button type="submit" class="btn btn-warning text-white d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined msf ms-sm">save</span>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
const usersData = @json($users->keyBy('id'));

function editUser(id) {
    const u = usersData[id];
    if (!u) return;
    document.getElementById('formEditUser').action = `/admin/users/${id}`;
    document.getElementById('edit_full_name').value = u.full_name;
    document.getElementById('edit_email').value = u.email;
    document.getElementById('edit_no_telp').value = u.no_telp || '';
    document.getElementById('edit_role').value = u.role;
    document.getElementById('edit_is_active').value = u.is_active ? '1' : '0';
    new bootstrap.Modal(document.getElementById('modalEditUser')).show();
}

function confirmDelete(form, title, text) {
    Swal.fire({
        title: title || 'Hapus data ini?',
        text: text || 'Tindakan ini tidak dapat dibatalkan.',
        icon: 'warning',
        iconColor: '#ef4444',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then(r => { if (r.isConfirmed) form.submit(); });
}
</script>
@endpush
@endsection
