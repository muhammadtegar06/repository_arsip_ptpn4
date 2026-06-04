@extends('layouts.app')
@section('title', 'Buat Pengajuan Box')
@section('page-title', 'Buat Pengajuan Baru')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <span class="breadcrumb-sep">›</span>
    <a href="{{ route('pengajuan.index') }}">Pengajuan Box</a>
    <span class="breadcrumb-sep">›</span>
    <span>Buat Baru</span>
@endsection

@section('content')

<div style="max-width: 640px; margin: 0 auto;">
    <div class="card">
        <div class="card-header" style="background: linear-gradient(135deg, #0f172a, #1e293b);">
            <div style="display: flex; align-items: center; gap: 0.875rem;">
                <div style="width: 42px; height: 42px; background: rgba(22,163,74,0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <svg style="width:22px;height:22px;color:#4ade80;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0121 9.414V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <div style="color: white; font-weight: 700; font-size: 1rem;">Form Pengajuan Box</div>
                    <div style="color: #94a3b8; font-size: 0.8rem;">Isi data pengajuan dengan lengkap dan benar</div>
                </div>
            </div>
        </div>

        <form action="{{ route('pengajuan.store') }}" method="POST">
            @csrf
            <div class="card-body">

                @if(auth()->user()->isUserDivisi())
                <div class="form-group">
                    <label class="form-label">Divisi Pengaju</label>
                    <input type="text" class="form-input" value="{{ auth()->user()->divisi->nama ?? '-' }}" disabled>
                    <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem;">Divisi terisi otomatis sesuai dengan akun Anda.</div>
                </div>
                @else
                <div class="form-group">
                    <label class="form-label">Divisi Pengaju <span style="color: #dc2626;">*</span></label>
                    <select name="id_divisi" class="form-select {{ $errors->has('id_divisi') ? 'error' : '' }}" required>
                        <option value="">-- Pilih Divisi --</option>
                        @foreach($divisi as $d)
                        <option value="{{ $d->id }}" {{ old('id_divisi') == $d->id ? 'selected' : '' }}>
                            {{ $d->singkatan }} &mdash; {{ $d->nama }}
                        </option>
                        @endforeach
                    </select>
                    @error('id_divisi')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                @endif

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Tanggal Pengajuan <span style="color: #dc2626;">*</span></label>
                        <input type="date" name="tanggal_pengajuan" class="form-input {{ $errors->has('tanggal_pengajuan') ? 'error' : '' }}"
                            value="{{ old('tanggal_pengajuan', date('Y-m-d')) }}" required>
                        @error('tanggal_pengajuan')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jumlah Box <span style="color: #dc2626;">*</span></label>
                        <input type="number" name="jumlah_box" class="form-input {{ $errors->has('jumlah_box') ? 'error' : '' }}"
                            min="1" max="1000" value="{{ old('jumlah_box', 1) }}" required placeholder="Jumlah box yang diajukan">
                        @error('jumlah_box')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan / Keterangan</label>
                    <textarea name="catatan" class="form-textarea" rows="4"
                        placeholder="Tambahkan catatan atau keterangan tambahan (opsional)...">{{ old('catatan') }}</textarea>
                    @error('catatan')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 1rem; font-size: 0.85rem; color: #166534;">
                    <strong>ℹ️ Informasi:</strong> Pengajuan akan masuk dengan status <em>Menunggu Persetujuan</em> dan perlu disetujui oleh Administrator sebelum dapat diproses lebih lanjut.
                </div>

            </div>

            <div class="modal-footer">
                <a href="{{ route('pengajuan.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <svg style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                    Kirim Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
