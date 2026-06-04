@extends('layouts.app')
@section('title', 'Detail Peminjaman Box')
@section('page-title', 'Detail Peminjaman Box')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <span class="breadcrumb-sep">›</span>
    <a href="{{ route('peminjaman.index') }}">Peminjaman Box</a>
    <span class="breadcrumb-sep">›</span>
    <span>{{ $peminjaman->no_peminjaman }}</span>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h2 class="page-title">Detail Peminjaman #{{ $peminjaman->no_peminjaman }}</h2>
        <p class="page-subtitle">Rincian peminjaman dan daftar box yang diajukan</p>
    </div>
    <a href="{{ route('peminjaman.index') }}" class="btn btn-outline">Kembali</a>
</div>

<div style="display: grid; grid-template-columns: 1fr 300px; gap: 1.5rem; align-items: start;">
    
    {{-- Left Content: Boxes --}}
    <div>
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <div style="font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 0.5rem;">
                    <svg style="width:18px;height:18px;color:#64748b;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                    Daftar Box Dipinjam ({{ $peminjaman->boxes->count() }})
                </div>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">No</th>
                            <th>No. Box</th>
                            <th>Asal Pengajuan</th>
                            <th>Keterangan Box</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($peminjaman->boxes as $i => $box)
                        <tr>
                            <td style="text-align: center; color: #94a3b8; font-size: 0.82rem;">{{ $i + 1 }}</td>
                            <td style="font-family: monospace; font-weight: 600; color: #0f172a;">{{ $box->nomor_box }}</td>
                            <td>
                                <a href="{{ route('pengajuan.show', $box->pengajuan_id ?? $box->id_pengajuan) }}" style="color: #16a34a; text-decoration: none; font-size: 0.85rem;">
                                    {{ $box->pengajuan->no_pengajuan ?? '-' }}
                                </a>
                            </td>
                            <td style="font-size: 0.85rem; color: #64748b;">{{ $box->keterangan ?: '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div style="font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 0.5rem;">
                    <svg style="width:18px;height:18px;color:#64748b;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0121 9.414V19a2 2 0 01-2 2z"/></svg>
                    Alasan Peminjaman
                </div>
            </div>
            <div class="card-body">
                <p style="color: #334155; line-height: 1.6; margin: 0; font-size: 0.9rem;">
                    {{ $peminjaman->alasan }}
                </p>
            </div>
        </div>
    </div>

    {{-- Right Content: Sidebar Info --}}
    <div>
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-body">
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
                <div style="text-align: center; margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid #f1f5f9;">
                    <div style="font-size: 0.75rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; margin-bottom: 0.5rem;">Status Saat Ini</div>
                    <span class="badge {{ $badgeMap[$peminjaman->status] ?? 'badge-gray' }}" style="font-size: 0.85rem; padding: 0.4rem 1rem;">
                        {{ $peminjaman->status }}
                    </span>
                    @if($peminjaman->keterangan_status)
                    <div style="margin-top: 0.75rem; font-size: 0.8rem; color: #475569; background: #f8fafc; padding: 0.5rem; border-radius: 6px;">
                        {{ $peminjaman->keterangan_status }}
                    </div>
                    @endif
                </div>

                <div style="margin-bottom: 1.25rem;">
                    <div style="font-size: 0.7rem; color: #94a3b8; font-weight: 600; text-transform: uppercase; margin-bottom: 0.25rem;">Divisi Peminjam</div>
                    <div style="font-weight: 600; color: #0f172a; font-size: 0.9rem;">{{ $peminjaman->divisi->nama }}</div>
                    <div style="font-size: 0.8rem; color: #64748b;">Oleh: {{ $peminjaman->user->nama }}</div>
                </div>

                <div style="margin-bottom: 1.25rem;">
                    <div style="font-size: 0.7rem; color: #94a3b8; font-weight: 600; text-transform: uppercase; margin-bottom: 0.25rem;">Tanggal Pinjam</div>
                    <div style="font-weight: 600; color: #0f172a; font-size: 0.9rem;">{{ $peminjaman->tanggal_pinjam->format('d M Y') }}</div>
                </div>

                <div>
                    <div style="font-size: 0.7rem; color: #94a3b8; font-weight: 600; text-transform: uppercase; margin-bottom: 0.25rem;">Rencana Kembali</div>
                    <div style="font-weight: 600; color: #dc2626; font-size: 0.9rem;">{{ $peminjaman->tanggal_kembali->format('d M Y') }}</div>
                </div>
            </div>
            
            {{-- Aksi Admin --}}
            @if(in_array(auth()->user()->role, ['Administrator', 'Super Admin']))
            <div class="card-header" style="background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; flex-direction: column; gap: 0.5rem; padding: 1rem;">
                
                @if($peminjaman->status === 'Menunggu Persetujuan')
                <button onclick="document.getElementById('modalApproval').classList.add('active')" class="btn btn-primary" style="width: 100%; justify-content: center;">
                    <svg style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Tindak Lanjuti
                </button>
                @endif

                @if($peminjaman->status === 'Dipinjam')
                <form action="{{ route('peminjaman.kembalikan', $peminjaman) }}" method="POST" onsubmit="return confirm('Apakah box ini sudah dikembalikan dan masuk kembali ke Gudang?')">
                    @csrf
                    <button type="submit" class="btn btn-success" style="width: 100%; justify-content: center; background: #16a34a; color: white; border: none;">
                        <svg style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                        Tandai Selesai & Dikembalikan
                    </button>
                </form>
                @endif

            </div>
            @endif
        </div>
    </div>
</div>

@push('modals')
@if(in_array(auth()->user()->role, ['Administrator', 'Super Admin']) && $peminjaman->status === 'Menunggu Persetujuan')
<div class="modal-overlay" id="modalApproval">
    <div class="modal-box" style="max-width: 450px;">
        <div class="modal-header">
            <div>
                <div style="font-weight: 700; color: #0f172a;">Approval Peminjaman</div>
                <div style="font-size: 0.8rem; color: #64748b;">{{ $peminjaman->no_peminjaman }}</div>
            </div>
            <button onclick="document.getElementById('modalApproval').classList.remove('active')" style="border:none;background:none;cursor:pointer;color:#94a3b8;font-size:1.25rem;">&times;</button>
        </div>
        <form action="{{ route('peminjaman.approval', $peminjaman) }}" method="POST">
            @csrf
            <div class="modal-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1rem;">
                    <label style="display: flex; flex-direction: column; align-items: center; padding: 1rem; border: 2px solid #e2e8f0; border-radius: 10px; cursor: pointer; transition: all 0.2s;" id="labelSetujui">
                        <input type="radio" name="aksi" value="setujui" style="display: none;" onchange="toggleAlasan(false)">
                        <svg style="width:28px;height:28px;color:#16a34a;margin-bottom:0.5rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span style="font-weight: 600; font-size: 0.85rem; color: #16a34a;">Setujui Peminjaman</span>
                    </label>
                    <label style="display: flex; flex-direction: column; align-items: center; padding: 1rem; border: 2px solid #e2e8f0; border-radius: 10px; cursor: pointer; transition: all 0.2s;" id="labelTolak">
                        <input type="radio" name="aksi" value="tolak" style="display: none;" onchange="toggleAlasan(true)">
                        <svg style="width:28px;height:28px;color:#dc2626;margin-bottom:0.5rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg>
                        <span style="font-weight: 600; font-size: 0.85rem; color: #dc2626;">Tolak Peminjaman</span>
                    </label>
                </div>
                <div id="alasanGroup" style="display: none;">
                    <label class="form-label">Alasan Penolakan <span style="color: #dc2626;">*</span></label>
                    <textarea name="alasan_tolak" class="form-textarea" rows="3" placeholder="Masukkan alasan mengapa ditolak..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="document.getElementById('modalApproval').classList.remove('active')" class="btn btn-outline btn-sm">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">Simpan Keputusan</button>
            </div>
        </form>
    </div>
</div>
@endif
@endpush

@push('scripts')
<script>
function toggleAlasan(show) {
    document.getElementById('alasanGroup').style.display = show ? 'block' : 'none';
    const l1 = document.getElementById('labelSetujui');
    const l2 = document.getElementById('labelTolak');
    l1.style.borderColor = show ? '#e2e8f0' : '#16a34a';
    l2.style.borderColor = show ? '#dc2626' : '#e2e8f0';
}
</script>
@endpush

@endsection
