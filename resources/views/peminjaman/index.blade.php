@extends('layouts.app')
@section('title', 'Peminjaman Box')
@section('page-title', 'Peminjaman Box Arsip')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <span class="breadcrumb-sep">›</span>
    <span>Peminjaman Box</span>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h2 class="page-title">Daftar Peminjaman Box</h2>
        <p class="page-subtitle">Kelola riwayat peminjaman box arsip</p>
    </div>
    @if(auth()->user()->isUserDivisi())
    <a href="{{ route('peminjaman.create') }}" class="btn btn-primary">
        <svg style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 4v16m8-8H4"/></svg>
        Ajukan Peminjaman
    </a>
    @endif
</div>

{{-- Filter --}}
<div class="card" style="margin-bottom: 1.25rem;">
    <div class="card-body" style="padding: 1.25rem;">
        <form method="GET" action="{{ route('peminjaman.index') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.875rem; align-items: end;">
            @if(!auth()->user()->isUserDivisi())
            <div>
                <label class="form-label">Divisi</label>
                <select name="divisi" class="form-select">
                    <option value="">Semua Divisi</option>
                    @foreach($divisi as $d)
                    <option value="{{ $d->id }}" {{ request('divisi') == $d->id ? 'selected' : '' }}>{{ $d->singkatan }} – {{ $d->nama }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div>
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    @foreach(['Menunggu Persetujuan','Disetujui','Ditolak','Dipinjam','Dikembalikan','Batal'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn btn-primary btn-sm" style="flex: 1;">
                    <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    Filter
                </button>
                <a href="{{ route('peminjaman.index') }}" class="btn btn-outline btn-sm">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">No</th>
                    <th>No. Peminjaman</th>
                    <th>Divisi</th>
                    <th>Tgl Pinjam / Kembali</th>
                    <th style="text-align: center;">Jumlah Box</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjaman as $i => $p)
                @php
                $badgeMap = [
                    'Menunggu Persetujuan' => 'badge-yellow',
                    'Disetujui'           => 'badge-blue',
                    'Ditolak'             => 'badge-red',
                    'Dipinjam'            => 'badge-orange',
                    'Dikembalikan'        => 'badge-green',
                    'Batal'               => 'badge-gray',
                ];
                @endphp
                <tr>
                    <td style="text-align: center; color: #94a3b8; font-size: 0.82rem;">{{ $peminjaman->firstItem() + $i }}</td>
                    <td>
                        <a href="{{ route('peminjaman.show', $p) }}" style="font-family: monospace; font-weight: 700; color: #16a34a; text-decoration: none; font-size: 0.85rem;">
                            {{ $p->no_peminjaman }}
                        </a>
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div class="divisi-avatar">{{ strtoupper(substr($p->divisi->singkatan ?? 'XX', 0, 3)) }}</div>
                            <div>
                                <div style="font-weight: 600; font-size: 0.85rem;">{{ $p->divisi->nama ?? '-' }}</div>
                                <div style="font-size: 0.75rem; color: #94a3b8;">Oleh: {{ $p->user->nama ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-size: 0.85rem; color: #0f172a; font-weight: 500;">Pinjam: {{ $p->tanggal_pinjam->format('d/m/Y') }}</div>
                        <div style="font-size: 0.75rem; color: #64748b;">Kembali: {{ $p->tanggal_kembali->format('d/m/Y') }}</div>
                    </td>
                    <td style="text-align: center;">
                        <span style="font-weight: 700; font-size: 1.05rem; color: #0f172a;">{{ $p->boxes->count() }}</span>
                        <div style="font-size: 0.7rem; color: #94a3b8;">box</div>
                    </td>
                    <td style="text-align: center;">
                        <span class="badge {{ $badgeMap[$p->status] ?? 'badge-gray' }}" style="font-size: 0.68rem;">{{ $p->status }}</span>
                    </td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.375rem; justify-content: center;">
                            <a href="{{ route('peminjaman.show', $p) }}" class="btn btn-icon btn-info btn-xs" title="Detail">
                                <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                            <p>Belum ada data peminjaman box. 
                                @if(auth()->user()->isUserDivisi())
                                <a href="{{ route('peminjaman.create') }}" style="color: #16a34a;">Buat pengajuan peminjaman pertama.</a>
                                @endif
                            </p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($peminjaman->hasPages())
    <div style="padding: 1rem 1.5rem; border-top: 1px solid #f1f5f9;">
        {{ $peminjaman->links('vendor.pagination.custom') }}
    </div>
    @endif
</div>

@endsection
