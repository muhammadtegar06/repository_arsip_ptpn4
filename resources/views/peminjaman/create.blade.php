@extends('layouts.app')
@section('title', 'Ajukan Peminjaman Box')
@section('page-title', 'Ajukan Peminjaman Box')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <span class="breadcrumb-sep">›</span>
    <a href="{{ route('peminjaman.index') }}">Peminjaman Box</a>
    <span class="breadcrumb-sep">›</span>
    <span>Buat Baru</span>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h2 class="page-title">Formulir Peminjaman Box Arsip</h2>
        <p class="page-subtitle">Pilih box yang ingin dipinjam dari Repository Arsip</p>
    </div>
    <a href="{{ route('peminjaman.index') }}" class="btn btn-outline">
        Kembali
    </a>
</div>

<div class="card" style="max-width: 800px;">
    <form action="{{ route('peminjaman.store') }}" method="POST">
        @csrf
        <div class="card-body">
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                <div class="form-group">
                    <label class="form-label">Tanggal Pinjam</label>
                    <input type="text" class="form-input" value="{{ now()->format('d/m/Y') }}" disabled style="background: #f8fafc; color: #94a3b8;">
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Rencana Kembali <span style="color: #dc2626;">*</span></label>
                    <input type="date" name="tanggal_kembali" class="form-input @error('tanggal_kembali') error @enderror" required min="{{ now()->addDay()->toDateString() }}" value="{{ old('tanggal_kembali', now()->addDays(7)->toDateString()) }}">
                    @error('tanggal_kembali') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Pilih Box yang Dipinjam <span style="color: #dc2626;">*</span></label>
                <p style="font-size: 0.75rem; color: #64748b; margin-bottom: 0.5rem;">Hanya menampilkan box divisi Anda yang sudah berada di Repository Arsip (Status: Terkirim) dan tidak sedang dipinjam.</p>
                
                @if($availableBoxes->count() > 0)
                <div style="max-height: 250px; overflow-y: auto; border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 0.5rem;">
                    @foreach($availableBoxes as $box)
                    <label style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; border-bottom: 1px solid #f1f5f9; cursor: pointer; transition: background 0.2s;">
                        <input type="checkbox" name="boxes[]" value="{{ $box->id }}" style="width: 16px; height: 16px; cursor: pointer;">
                        <div>
                            <div style="font-weight: 600; color: #0f172a; font-family: monospace;">{{ $box->nomor_box }}</div>
                            <div style="font-size: 0.75rem; color: #64748b;">Pengajuan: {{ $box->pengajuan->no_pengajuan }}</div>
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('boxes') <div class="form-error">{{ $message }}</div> @enderror
                @else
                <div class="alert alert-warning" style="margin-bottom: 0;">
                    Tidak ada box yang tersedia untuk dipinjam saat ini. Pastikan Anda memiliki box yang sudah disetujui dan terkirim ke Repository Arsip.
                </div>
                @endif
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Alasan Keperluan Peminjaman <span style="color: #dc2626;">*</span></label>
                <textarea name="alasan" class="form-textarea @error('alasan') error @enderror" rows="4" required placeholder="Jelaskan alasan divisi Anda perlu meminjam box arsip ini (misal: Audit internal, peninjauan kontrak, dll).">{{ old('alasan') }}</textarea>
                @error('alasan') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            
        </div>
        <div class="card-header" style="background: #f8fafc; border-top: 1px solid #e2e8f0; border-bottom: none;">
            <button type="submit" class="btn btn-primary" {{ $availableBoxes->count() == 0 ? 'disabled' : '' }}>
                Simpan & Ajukan Peminjaman
            </button>
        </div>
    </form>
</div>

@endsection
