@extends('layouts.app')
@section('title', 'Tambah User')
@section('page-title', 'Tambah Akun User Baru')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <span class="breadcrumb-sep">›</span>
    <a href="{{ route('users.index') }}">Manajemen User</a>
    <span class="breadcrumb-sep">›</span>
    <span>Tambah Baru</span>
@endsection

@section('content')

<div style="max-width: 500px; margin: 0 auto;">
    <div class="card">
        <div class="card-header">
            <h3 style="font-weight: 700; color: #0f172a; font-size: 0.95rem;">Form Data Pengguna</h3>
        </div>
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span style="color: #dc2626;">*</span></label>
                    <input type="text" name="nama" class="form-input {{ $errors->has('nama') ? 'error' : '' }}" value="{{ old('nama') }}" required placeholder="Masukkan nama lengkap">
                    @error('nama')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Username <span style="color: #dc2626;">*</span></label>
                    <input type="text" name="username" class="form-input {{ $errors->has('username') ? 'error' : '' }}" value="{{ old('username') }}" required placeholder="Pilih username tanpa spasi">
                    @error('username')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Role / Hak Akses <span style="color: #dc2626;">*</span></label>
                    <select name="role" id="roleSelect" class="form-select {{ $errors->has('role') ? 'error' : '' }}" required onchange="toggleDivisi()">
                        <option value="">-- Pilih Role --</option>
                        <option value="Super Admin" {{ old('role') == 'Super Admin' ? 'selected' : '' }}>Super Admin (Programmer)</option>
                        <option value="Administrator" {{ old('role') == 'Administrator' ? 'selected' : '' }}>Administrator (Divisi Umum)</option>
                        <option value="User Divisi" {{ old('role') == 'User Divisi' ? 'selected' : '' }}>User Divisi (Tiap Divisi)</option>
                    </select>
                    @error('role')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group" id="divisiGroup" style="display: {{ old('role') == 'User Divisi' ? 'block' : 'none' }};">
                    <label class="form-label">Pilih Divisi <span style="color: #dc2626;">*</span></label>
                    <select name="id_divisi" id="divisiSelect" class="form-select {{ $errors->has('id_divisi') ? 'error' : '' }}">
                        <option value="">-- Pilih Divisi --</option>
                        @foreach($divisi as $d)
                        <option value="{{ $d->id }}" {{ old('id_divisi') == $d->id ? 'selected' : '' }}>{{ $d->singkatan }} - {{ $d->nama }}</option>
                        @endforeach
                    </select>
                    <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem;">Wajib diisi jika Role adalah User Divisi.</div>
                    @error('id_divisi')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Password <span style="color: #dc2626;">*</span></label>
                    <input type="password" name="password" class="form-input {{ $errors->has('password') ? 'error' : '' }}" required placeholder="Minimal 6 karakter">
                    @error('password')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Password <span style="color: #dc2626;">*</span></label>
                    <input type="password" name="password_confirmation" class="form-input" required placeholder="Ulangi password">
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ route('users.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan User</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function toggleDivisi() {
    const role = document.getElementById('roleSelect').value;
    const divisiGroup = document.getElementById('divisiGroup');
    const divisiSelect = document.getElementById('divisiSelect');
    if (role === 'User Divisi') {
        divisiGroup.style.display = 'block';
        divisiSelect.required = true;
    } else {
        divisiGroup.style.display = 'none';
        divisiSelect.required = false;
        divisiSelect.value = '';
    }
}
// Run once on load to handle old() data
toggleDivisi();
</script>
@endpush
