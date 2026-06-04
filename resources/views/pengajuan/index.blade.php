@extends('layouts.app')
@section('title', 'Pengajuan Box')
@section('page-title', 'Pengajuan Box')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <span class="breadcrumb-sep">›</span>
    <span>Pengajuan Box</span>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h2 class="page-title">Daftar Pengajuan</h2>
        <p class="page-subtitle">Kelola pengajuan box arsip dari setiap divisi PTPN4</p>
    </div>
    @if(in_array(auth()->user()->role, ['Administrator', 'Admin Gudang']))
    <a href="{{ route('pengajuan.create') }}" class="btn btn-primary">
        <svg style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 4v16m8-8H4"/></svg>
        Buat Pengajuan Baru
    </a>
    @endif
</div>

{{-- Filter --}}
<div class="card" style="margin-bottom: 1.25rem;">
    <div class="card-body" style="padding: 1.25rem;">
        <form method="GET" action="{{ route('pengajuan.index') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.875rem; align-items: end;">
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
                    @foreach(['Menunggu Persetujuan','Disetujui','Ditolak','Selesai Diinput','Akan Di Kirim','Terkirim','Cancel'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Tgl Awal</label>
                <input type="date" name="tgl_awal" class="form-input" value="{{ request('tgl_awal') }}">
            </div>
            <div>
                <label class="form-label">Tgl Akhir</label>
                <input type="date" name="tgl_akhir" class="form-input" value="{{ request('tgl_akhir') }}">
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn btn-primary btn-sm" style="flex: 1;">
                    <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    Filter
                </button>
                <a href="{{ route('pengajuan.index') }}" class="btn btn-outline btn-sm">Reset</a>
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
                    <th>No. Pengajuan</th>
                    <th>Divisi</th>
                    <th>Tanggal</th>
                    <th style="text-align: center;">Jumlah Box</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuan as $i => $p)
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
                <tr>
                    <td style="text-align: center; color: #94a3b8; font-size: 0.82rem;">{{ $pengajuan->firstItem() + $i }}</td>
                    <td>
                        <a href="{{ route('pengajuan.show', $p) }}" style="font-family: monospace; font-weight: 700; color: #16a34a; text-decoration: none; font-size: 0.85rem;">
                            {{ $p->no_pengajuan }}
                        </a>
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div class="divisi-avatar">{{ strtoupper(substr($p->divisi->singkatan ?? 'XX', 0, 3)) }}</div>
                            <div>
                                <div style="font-weight: 600; font-size: 0.85rem;">{{ $p->divisi->nama ?? '-' }}</div>
                                <div style="font-size: 0.75rem; color: #94a3b8;">Dibuat: {{ $p->user->nama ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size: 0.85rem; color: #64748b;">{{ $p->tanggal_pengajuan->translatedFormat('d M Y') }}</td>
                    <td style="text-align: center;">
                        <span style="font-weight: 700; font-size: 1.05rem; color: #0f172a;">{{ $p->jumlah_box }}</span>
                        <div style="font-size: 0.7rem; color: #94a3b8;">box</div>
                    </td>
                    <td style="text-align: center;">
                        <span class="badge {{ $badgeMap[$p->status] ?? 'badge-gray' }}" style="font-size: 0.68rem;">{{ $p->status }}</span>
                    </td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.375rem; justify-content: center;">
                            <a href="{{ route('pengajuan.show', $p) }}" class="btn btn-icon btn-info btn-xs" title="Detail">
                                <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>

                            @if(auth()->user()->role === 'Administrator' && in_array($p->status, ['Menunggu Persetujuan']))
                            <button onclick="openApproval({{ $p->id }}, '{{ $p->no_pengajuan }}')" class="btn btn-icon btn-warning btn-xs" title="Approval">
                                <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </button>
                            @endif

                            @if(in_array($p->status, ['Menunggu Persetujuan', 'Ditolak']))
                            <form action="{{ route('pengajuan.destroy', $p) }}" method="POST" onsubmit="return confirmDelete('{{ $p->no_pengajuan }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-danger btn-xs" title="Hapus">
                                    <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6m5 0V4h4v2"/></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0121 9.414V19a2 2 0 01-2 2z"/></svg>
                            <p>Belum ada data pengajuan. <a href="{{ route('pengajuan.create') }}" style="color: #16a34a;">Buat pengajuan pertama.</a></p>
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

@push('modals')
{{-- Modal Approval --}}
<div class="modal-overlay" id="modalApproval">
    <div class="modal-box" style="max-width: 450px;">
        <div class="modal-header">
            <div>
                <div style="font-weight: 700; color: #0f172a;">Approval Pengajuan</div>
                <div style="font-size: 0.8rem; color: #64748b;" id="approvalNoPengajuan">-</div>
            </div>
            <button onclick="document.getElementById('modalApproval').classList.remove('active')" style="border:none;background:none;cursor:pointer;color:#94a3b8;font-size:1.25rem;">&times;</button>
        </div>
        <form id="formApproval" method="POST">
            @csrf
            <div class="modal-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1rem;">
                    <label style="display: flex; flex-direction: column; align-items: center; padding: 1rem; border: 2px solid #e2e8f0; border-radius: 10px; cursor: pointer; transition: all 0.2s;" id="labelSetujui">
                        <input type="radio" name="aksi" value="setujui" style="display: none;" onchange="toggleAlasan(false)">
                        <svg style="width:28px;height:28px;color:#16a34a;margin-bottom:0.5rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span style="font-weight: 600; font-size: 0.85rem; color: #16a34a;">Setujui</span>
                    </label>
                    <label style="display: flex; flex-direction: column; align-items: center; padding: 1rem; border: 2px solid #e2e8f0; border-radius: 10px; cursor: pointer; transition: all 0.2s;" id="labelTolak">
                        <input type="radio" name="aksi" value="tolak" style="display: none;" onchange="toggleAlasan(true)">
                        <svg style="width:28px;height:28px;color:#dc2626;margin-bottom:0.5rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg>
                        <span style="font-weight: 600; font-size: 0.85rem; color: #dc2626;">Tolak</span>
                    </label>
                </div>
                <div id="alasanGroup" style="display: none;">
                    <label class="form-label">Alasan Penolakan <span style="color: #dc2626;">*</span></label>
                    <textarea name="alasan_tolak" class="form-textarea" rows="3" placeholder="Masukkan alasan penolakan..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="document.getElementById('modalApproval').classList.remove('active')" class="btn btn-outline btn-sm">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">Simpan Keputusan</button>
            </div>
        </form>
    </div>
</div>
@endpush

@endsection

@push('scripts')
<script>
function openApproval(id, noPengajuan) {
    document.getElementById('formApproval').action = `/pengajuan/${id}/approval`;
    document.getElementById('approvalNoPengajuan').textContent = noPengajuan;
    document.getElementById('modalApproval').classList.add('active');
}

function toggleAlasan(show) {
    document.getElementById('alasanGroup').style.display = show ? 'block' : 'none';
    const l1 = document.getElementById('labelSetujui');
    const l2 = document.getElementById('labelTolak');
    l1.style.borderColor = show ? '#e2e8f0' : '#16a34a';
    l2.style.borderColor = show ? '#dc2626' : '#e2e8f0';
}

function confirmDelete(no) {
    return confirm(`Hapus pengajuan ${no}? Data yang sudah terhapus tidak bisa dikembalikan.`);
}
</script>
@endpush
