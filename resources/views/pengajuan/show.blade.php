@extends('layouts.app')
@section('title', 'Detail Pengajuan – ' . $pengajuan->no_pengajuan)
@section('page-title', 'Detail Pengajuan')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <span class="breadcrumb-sep">›</span>
    <a href="{{ route('pengajuan.index') }}">Pengajuan Box</a>
    <span class="breadcrumb-sep">›</span>
    <span>{{ $pengajuan->no_pengajuan }}</span>
@endsection

@section('content')

@php
$badgeMap = ['Menunggu Persetujuan'=>'badge-yellow','Disetujui'=>'badge-blue','Ditolak'=>'badge-red','Selesai Diinput'=>'badge-purple','Akan Di Kirim'=>'badge-orange','Terkirim'=>'badge-green','Cancel'=>'badge-gray'];
@endphp

{{-- Header Info --}}
<div class="card" style="margin-bottom: 1.25rem;">
    <div class="card-body">
        <div style="display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div class="divisi-avatar" style="width: 56px; height: 56px; font-size: 0.9rem;">
                    {{ strtoupper(substr($pengajuan->divisi->singkatan ?? 'XX', 0, 3)) }}
                </div>
                <div>
                    <div style="font-weight: 800; font-size: 1.25rem; color: #0f172a; font-family: monospace;">{{ $pengajuan->no_pengajuan }}</div>
                    <div style="font-size: 0.9rem; color: #475569;">{{ $pengajuan->divisi->nama ?? '-' }}</div>
                    <div style="font-size: 0.8rem; color: #94a3b8; margin-top: 0.125rem;">Dibuat oleh: {{ $pengajuan->user->nama ?? '-' }} &bull; {{ $pengajuan->tanggal_pengajuan->format('d M Y') }}</div>
                </div>
            </div>
            <span class="badge {{ $badgeMap[$pengajuan->status] ?? 'badge-gray' }}" style="font-size: 0.8rem; padding: 0.45rem 1rem;">
                {{ $pengajuan->status }}
            </span>
        </div>

        @if($pengajuan->catatan)
        <div style="margin-top: 1rem; padding: 0.875rem; background: #f8fafc; border-radius: 8px; border-left: 3px solid #94a3b8;">
            <div style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: #94a3b8; margin-bottom: 0.25rem;">Catatan</div>
            <div style="font-size: 0.875rem; color: #475569;">{{ $pengajuan->catatan }}</div>
        </div>
        @endif

        @if($pengajuan->alasan_tolak)
        <div style="margin-top: 0.75rem; padding: 0.875rem; background: #fef2f2; border-radius: 8px; border-left: 3px solid #dc2626;">
            <div style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: #dc2626; margin-bottom: 0.25rem;">Alasan Penolakan</div>
            <div style="font-size: 0.875rem; color: #7f1d1d;">{{ $pengajuan->alasan_tolak }}</div>
        </div>
        @endif

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-top: 1.25rem;">
            <div style="text-align: center; padding: 0.875rem; background: #f0fdf4; border-radius: 10px;">
                <div style="font-size: 1.75rem; font-weight: 800; color: #16a34a;">{{ $pengajuan->jumlah_box }}</div>
                <div style="font-size: 0.75rem; color: #64748b; text-transform: uppercase;">Box Diajukan</div>
            </div>
            <div style="text-align: center; padding: 0.875rem; background: #eff6ff; border-radius: 10px;">
                <div style="font-size: 1.75rem; font-weight: 800; color: #2563eb;">{{ $pengajuan->box->count() }}</div>
                <div style="font-size: 0.75rem; color: #64748b; text-transform: uppercase;">Box Terisi</div>
            </div>
            <div style="text-align: center; padding: 0.875rem; background: #faf5ff; border-radius: 10px;">
                <div style="font-size: 1.75rem; font-weight: 800; color: #7c3aed;">{{ $pengajuan->box->sum(fn($b) => $b->bantex->count()) }}</div>
                <div style="font-size: 0.75rem; color: #64748b; text-transform: uppercase;">Total Bantex</div>
            </div>
        </div>
    </div>
</div>

{{-- Daftar Box --}}
<div class="card">
    <div class="card-header">
        <h3 style="font-weight: 700; color: #0f172a; font-size: 0.95rem;">Daftar Box & Bantex</h3>
        @if(in_array(auth()->user()->role, ['Administrator', 'Admin Gudang']) && in_array($pengajuan->status, ['Disetujui', 'Selesai Diinput']))
        <a href="{{ route('pengisian.show', $pengajuan) }}" class="btn btn-primary btn-sm">
            <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Input Data Box
        </a>
        @endif
    </div>

    @if($pengajuan->box->isEmpty())
    <div class="empty-state" style="padding: 2.5rem;">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
        <p>Belum ada data box yang diinput.</p>
    </div>
    @else
    <div class="card-body" style="padding: 1.25rem;">
        @foreach($pengajuan->box as $box)
        <div style="border: 1.5px solid #e2e8f0; border-radius: 12px; margin-bottom: 1rem; overflow: hidden;">
            <div style="background: #f8fafc; padding: 0.875rem 1.25rem; display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 36px; height: 36px; background: #dbeafe; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <svg style="width:18px;height:18px;color:#2563eb;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
                    </div>
                    <div>
                        <div style="font-weight: 700; font-size: 0.9rem;">Box #{{ $box->nomor_box }}</div>
                        <div style="font-size: 0.75rem; color: #64748b;">RFID: {{ $box->rfid_code ?? 'Belum discan' }}</div>
                    </div>
                </div>
                <span style="font-size: 0.75rem; background: #dcfce7; color: #15803d; padding: 0.25rem 0.75rem; border-radius: 20px; font-weight: 600;">
                    {{ $box->bantex->count() }} Bantex
                </span>
            </div>
            @if($box->bantex->isNotEmpty())
            <div style="padding: 0.75rem 1.25rem;">
                @foreach($box->bantex as $b)
                <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem 0; border-bottom: 1px solid #f1f5f9;">
                    <svg style="width:14px;height:14px;color:#94a3b8;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                    <div style="flex: 1;">
                        <span style="font-size: 0.82rem; font-weight: 600;">{{ $b->nama_bantex }}</span>
                        @if($b->tahun_awal)
                        <span style="font-size: 0.75rem; color: #94a3b8; margin-left: 0.5rem;">({{ $b->tahun_awal }}{{ $b->tahun_akhir ? ' – '.$b->tahun_akhir : '' }})</span>
                        @endif
                    </div>
                    <span style="font-size: 0.72rem; color: #7c3aed;">{{ $b->dokumen->count() }} dok</span>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @endif
</div>

@endsection
