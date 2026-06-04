@extends('layouts.app')
@section('title', 'Pengisian Data Box')
@section('page-title', 'Pengisian Data Box & Bantex')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <span class="breadcrumb-sep">›</span>
    <span>Pengisian Data Box</span>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h2 class="page-title">Daftar Pengajuan Disetujui</h2>
        <p class="page-subtitle">Pilih pengajuan untuk mulai menginput data Box, Bantex, dan Dokumen.</p>
    </div>
</div>

<div class="card" style="margin-bottom: 1.25rem;">
    <div class="card-body" style="padding: 1.25rem;">
        <form method="GET" action="{{ route('pengisian.index') }}" style="display: flex; gap: 0.875rem; align-items: end; max-width: 500px;">
            @if(!auth()->user()->isUserDivisi())
            <div style="flex: 1;">
                <label class="form-label">Filter Divisi</label>
                <select name="divisi" class="form-select">
                    <option value="">Semua Divisi</option>
                    @foreach($divisi as $d)
                    <option value="{{ $d->id }}" {{ request('divisi') == $d->id ? 'selected' : '' }}>{{ $d->singkatan }} – {{ $d->nama }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div style="display: flex; gap: 0.5rem; flex: 1; align-items: flex-end;">
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                <a href="{{ route('pengisian.index') }}" class="btn btn-outline btn-sm">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">No</th>
                    <th>No. Pengajuan</th>
                    <th>Divisi</th>
                    <th style="text-align: center;">Target Box</th>
                    <th style="text-align: center;">Box Terisi</th>
                    <th style="text-align: center;">Progress</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuan as $i => $p)
                @php
                    $terisi = $p->box->count();
                    $target = $p->jumlah_box;
                    $percent = $target > 0 ? min(100, round(($terisi / $target) * 100)) : 0;
                    $isSelesai = $p->status === 'Selesai Diinput';
                @endphp
                <tr>
                    <td style="text-align: center; color: #94a3b8; font-size: 0.82rem;">{{ $pengajuan->firstItem() + $i }}</td>
                    <td>
                        <div style="font-family: monospace; font-weight: 700; color: #0f172a; font-size: 0.85rem;">
                            {{ $p->no_pengajuan }}
                        </div>
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div class="divisi-avatar">{{ strtoupper(substr($p->divisi->singkatan ?? 'XX', 0, 3)) }}</div>
                            <div style="font-weight: 600; font-size: 0.85rem;">{{ $p->divisi->nama ?? '-' }}</div>
                        </div>
                    </td>
                    <td style="text-align: center; font-weight: 700; color: #64748b;">{{ $target }}</td>
                    <td style="text-align: center; font-weight: 700; color: {{ $terisi == $target ? '#16a34a' : '#2563eb' }};">{{ $terisi }}</td>
                    <td style="width: 150px;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div style="flex: 1; height: 6px; background: #e2e8f0; border-radius: 3px; overflow: hidden;">
                                <div style="height: 100%; width: {{ $percent }}%; background: {{ $percent == 100 ? '#16a34a' : '#2563eb' }}; border-radius: 3px;"></div>
                            </div>
                            <span style="font-size: 0.7rem; font-weight: 700; color: #64748b;">{{ $percent }}%</span>
                        </div>
                    </td>
                    <td style="text-align: center;">
                        @if($isSelesai)
                        <span class="badge badge-purple" style="font-size: 0.68rem;">Selesai Diinput</span>
                        @else
                        <span class="badge badge-blue" style="font-size: 0.68rem;">Disetujui</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <a href="{{ route('pengisian.show', $p) }}" class="btn {{ $isSelesai ? 'btn-outline' : 'btn-primary' }} btn-xs">
                            @if($isSelesai)
                            <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Lihat Data
                            @else
                            <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Input Data
                            @endif
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0121 9.414V19a2 2 0 01-2 2z"/></svg>
                            <p>Tidak ada pengajuan dengan status Disetujui atau Selesai Diinput.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pengajuan->hasPages())
    <div style="padding: 1rem 1.5rem; border-top: 1px solid #f1f5f9;">
        {{ $pengajuan->links('vendor.pagination.custom') }}
    </div>
    @endif
</div>

@endsection
