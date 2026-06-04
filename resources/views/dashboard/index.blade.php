@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Stats Row --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.75rem;">

    <div class="stat-card">
        <div class="stat-icon" style="background: #dcfce7;">
            <svg style="width:24px;height:24px;color:#16a34a;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0121 9.414V19a2 2 0 01-2 2z"/></svg>
        </div>
        <div>
            <div class="stat-label">Total Pengajuan</div>
            <div class="stat-value" style="color: #16a34a;">{{ number_format($totalPengajuan) }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: #dbeafe;">
            <svg style="width:24px;height:24px;color:#2563eb;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
        </div>
        <div>
            <div class="stat-label">Total Box</div>
            <div class="stat-value" style="color: #2563eb;">{{ number_format($totalBox) }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: #fef9c3;">
            <svg style="width:24px;height:24px;color:#ca8a04;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
        </div>
        <div>
            <div class="stat-label">Total Bantex</div>
            <div class="stat-value" style="color: #ca8a04;">{{ number_format($totalBantex) }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: #ede9fe;">
            <svg style="width:24px;height:24px;color:#7c3aed;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
        </div>
        <div>
            <div class="stat-label">Total Dokumen</div>
            <div class="stat-value" style="color: #7c3aed;">{{ number_format($totalDokumen) }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: #ffedd5;">
            <svg style="width:24px;height:24px;color:#ea580c;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div>
            <div class="stat-label">Divisi Terdaftar</div>
            <div class="stat-value" style="color: #ea580c;">{{ number_format($totalDivisi) }}</div>
        </div>
    </div>

</div>

{{-- Status Overview + Recent Table --}}
<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1.25rem; margin-bottom: 1.25rem;">

    {{-- Status Overview --}}
    <div class="card">
        <div class="card-header">
            <h3 style="font-weight: 700; color: #0f172a; font-size: 0.95rem;">Status Pengajuan</h3>
        </div>
        <div class="card-body" style="padding: 1rem;">
            @php
            $statuses = [
                ['label' => 'Menunggu Persetujuan', 'count' => $menunggu,     'color' => '#f59e0b', 'bg' => '#fef9c3'],
                ['label' => 'Disetujui',             'count' => $disetujui,   'color' => '#2563eb', 'bg' => '#dbeafe'],
                ['label' => 'Selesai Diinput',       'count' => $selesaiInput,'color' => '#7c3aed', 'bg' => '#ede9fe'],
                ['label' => 'Akan Di Kirim',          'count' => $akanDikirim, 'color' => '#ea580c', 'bg' => '#ffedd5'],
                ['label' => 'Terkirim',              'count' => $terkirim,    'color' => '#16a34a', 'bg' => '#dcfce7'],
            ];
            @endphp
            @foreach($statuses as $s)
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.65rem 0; border-bottom: 1px solid #f1f5f9;">
                <div style="display: flex; align-items: center; gap: 0.6rem;">
                    <div style="width: 8px; height: 8px; border-radius: 50%; background: {{ $s['color'] }};"></div>
                    <span style="font-size: 0.82rem; color: #475569;">{{ $s['label'] }}</span>
                </div>
                <span style="font-size: 0.875rem; font-weight: 700; color: {{ $s['color'] }}; background: {{ $s['bg'] }}; padding: 0.2rem 0.6rem; border-radius: 20px;">
                    {{ $s['count'] }}
                </span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Recent Pengajuan --}}
    <div class="card">
        <div class="card-header">
            <h3 style="font-weight: 700; color: #0f172a; font-size: 0.95rem;">Pengajuan Terbaru</h3>
            <a href="{{ route('pengajuan.index') }}" class="btn btn-outline btn-xs">Lihat Semua</a>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No. Pengajuan</th>
                        <th>Divisi</th>
                        <th>Tanggal</th>
                        <th>Box</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuanTerbaru as $p)
                    <tr>
                        <td>
                            <a href="{{ route('pengajuan.show', $p) }}" style="font-family: monospace; font-weight: 600; color: #16a34a; text-decoration: none; font-size: 0.8rem;">
                                {{ $p->no_pengajuan }}
                            </a>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.6rem;">
                                <div class="divisi-avatar">{{ strtoupper(substr($p->divisi->singkatan ?? 'XX', 0, 3)) }}</div>
                                <span style="font-size: 0.82rem;">{{ $p->divisi->nama ?? '-' }}</span>
                            </div>
                        </td>
                        <td style="font-size: 0.82rem; color: #64748b;">{{ $p->tanggal_pengajuan->format('d M Y') }}</td>
                        <td style="text-align: center; font-weight: 600;">{{ $p->jumlah_box }}</td>
                        <td>
                            @php
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
                            <span class="badge {{ $badgeMap[$p->status] ?? 'badge-gray' }}" style="font-size: 0.68rem;">
                                {{ $p->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="empty-state" style="padding: 2rem;">
                            <p>Belum ada data pengajuan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Statistik per Divisi --}}
<div class="card">
    <div class="card-header">
        <h3 style="font-weight: 700; color: #0f172a; font-size: 0.95rem;">Statistik Pengajuan per Divisi</h3>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Divisi</th>
                    <th>Singkatan</th>
                    <th style="text-align: center;">Total Pengajuan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($perDivisi as $i => $d)
                <tr>
                    <td style="color: #94a3b8; font-size: 0.82rem;">{{ $i + 1 }}</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div class="divisi-avatar">{{ strtoupper(substr($d->singkatan, 0, 3)) }}</div>
                            <div>
                                <div style="font-weight: 600; font-size: 0.875rem;">{{ $d->nama }}</div>
                            </div>
                        </div>
                    </td>
                    <td><span style="font-family: monospace; font-size: 0.82rem; color: #64748b;">{{ $d->singkatan }}</span></td>
                    <td style="text-align: center;">
                        <span style="font-weight: 700; font-size: 1rem; color: #16a34a;">{{ $d->pengajuan_count }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="empty-state" style="padding: 2rem;"><p>Belum ada data divisi.</p></td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
