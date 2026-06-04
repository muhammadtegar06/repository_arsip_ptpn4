@extends('layouts.app')
@section('title', 'Laporan & Export')
@section('page-title', 'Laporan & Export Data')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <span class="breadcrumb-sep">›</span>
    <span>Laporan</span>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h2 class="page-title">Laporan Data Arsip PTPN4</h2>
        <p class="page-subtitle">Filter dan export laporan data pengajuan dan box arsip ke Excel</p>
    </div>
</div>

{{-- Summary Cards --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
    <div style="background: linear-gradient(135deg, #16a34a, #22c55e); border-radius: 12px; padding: 1.25rem; color: white; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <div style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; opacity: 0.9;">Total Pengajuan (Filter)</div>
            <div style="font-size: 1.75rem; font-weight: 800; margin-top: 0.25rem;">{{ $laporan->total() }}</div>
        </div>
        <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
            <svg style="width:24px;height:24px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0121 9.414V19a2 2 0 01-2 2z"/></svg>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #2563eb, #3b82f6); border-radius: 12px; padding: 1.25rem; color: white; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <div style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; opacity: 0.9;">Total Keseluruhan Box</div>
            <div style="font-size: 1.75rem; font-weight: 800; margin-top: 0.25rem;">{{ number_format($totalBox) }}</div>
        </div>
        <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
            <svg style="width:24px;height:24px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
        </div>
    </div>

    <div style="background: linear-gradient(135deg, #7c3aed, #8b5cf6); border-radius: 12px; padding: 1.25rem; color: white; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <div style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; opacity: 0.9;">Total Keseluruhan Dokumen</div>
            <div style="font-size: 1.75rem; font-weight: 800; margin-top: 0.25rem;">{{ number_format($totalDok) }}</div>
        </div>
        <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
            <svg style="width:24px;height:24px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
        </div>
    </div>
</div>

<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-header" style="background: #f8fafc;">
        <h3 style="font-weight: 700; color: #0f172a; font-size: 0.95rem;">Filter Data Laporan</h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('laporan.index') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
            @if(!auth()->user()->isUserDivisi())
            <div>
                <label class="form-label">Filter Divisi</label>
                <select name="divisi" class="form-select">
                    <option value="">Semua Divisi</option>
                    @foreach($divisi as $d)
                    <option value="{{ $d->id }}" {{ request('divisi') == $d->id ? 'selected' : '' }}>{{ $d->singkatan }} - {{ $d->nama }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div>
                <label class="form-label">Filter Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    @foreach(['Menunggu Persetujuan','Disetujui','Ditolak','Selesai Diinput','Akan Di Kirim','Terkirim','Cancel'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Tanggal Awal</label>
                <input type="date" name="tgl_awal" class="form-input" value="{{ request('tgl_awal') }}">
            </div>
            <div>
                <label class="form-label">Tanggal Akhir</label>
                <input type="date" name="tgl_akhir" class="form-input" value="{{ request('tgl_akhir') }}">
            </div>
            <div style="display: flex; gap: 0.5rem; grid-column: 1 / -1; justify-content: flex-end; margin-top: 0.5rem;">
                <a href="{{ route('laporan.index') }}" class="btn btn-outline">Reset Filter</a>
                <button type="submit" class="btn btn-primary">
                    <svg style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    Terapkan Filter
                </button>
                <a href="{{ route('laporan.export', request()->all()) }}" class="btn btn-success" style="background: #10b981; color: white;">
                    <svg style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Export Excel
                </a>
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
                    <th>Tanggal</th>
                    <th style="text-align: center;">Target Box</th>
                    <th style="text-align: center;">Box Terisi</th>
                    <th style="text-align: center;">Total Dokumen</th>
                    <th style="text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporan as $i => $p)
                @php
                    $terisi = $p->box->count();
                    $target = $p->jumlah_box;
                    $dokCount = $p->box->sum(fn($b) => $b->bantex->sum(fn($bx) => $bx->dokumen->count()));
                    
                    $badgeMap = [
                        'Menunggu Persetujuan' => 'badge-yellow',
                        'Disetujui'           => 'badge-blue',
                        'Ditolak'             => 'badge-red',
                        'Selesai Diinput'     => 'badge-purple',
                        'Akan Di Kirim'       => 'badge-orange',
                        'Terkirim'            => 'badge-green',
                        'Cancel'              => 'badge-gray',
                    ];
                @endphp
                <tr>
                    <td style="text-align: center; color: #94a3b8; font-size: 0.82rem;">{{ $laporan->firstItem() + $i }}</td>
                    <td>
                        <a href="{{ route('pengajuan.show', $p) }}" style="font-family: monospace; font-weight: 700; color: #16a34a; text-decoration: none; font-size: 0.85rem;">
                            {{ $p->no_pengajuan }}
                        </a>
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div class="divisi-avatar" style="width: 32px; height: 32px; font-size: 0.7rem;">{{ strtoupper(substr($p->divisi->singkatan ?? 'XX', 0, 3)) }}</div>
                            <div style="font-weight: 600; font-size: 0.85rem;">{{ $p->divisi->nama ?? '-' }}</div>
                        </div>
                    </td>
                    <td style="font-size: 0.85rem; color: #64748b;">{{ $p->tanggal_pengajuan->format('d/m/Y') }}</td>
                    <td style="text-align: center; font-weight: 700; color: #64748b;">{{ $target }}</td>
                    <td style="text-align: center; font-weight: 700; color: {{ $terisi == $target ? '#16a34a' : '#2563eb' }};">{{ $terisi }}</td>
                    <td style="text-align: center; font-weight: 700; color: #7c3aed;">{{ $dokCount }}</td>
                    <td style="text-align: center;">
                        <span class="badge {{ $badgeMap[$p->status] ?? 'badge-gray' }}" style="font-size: 0.68rem;">{{ $p->status }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <p>Tidak ada data laporan yang sesuai filter.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($laporan->hasPages())
    <div style="padding: 1rem 1.5rem; border-top: 1px solid #f1f5f9;">
        {{ $laporan->links('vendor.pagination.custom') }}
    </div>
    @endif
</div>

@endsection
