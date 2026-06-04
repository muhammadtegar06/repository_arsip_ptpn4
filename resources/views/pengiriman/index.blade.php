@extends('layouts.app')
@section('title', 'Pengiriman Box')
@section('page-title', 'Pengiriman Box & Tracking')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <span class="breadcrumb-sep">›</span>
    <span>Pengiriman Box</span>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h2 class="page-title">Monitoring Pengiriman</h2>
        <p class="page-subtitle">Kelola status pengiriman box arsip fisik ke Repository Arsip PTPN4</p>
    </div>
</div>

<div class="card" style="margin-bottom: 1.25rem;">
    <div class="card-body" style="padding: 1.25rem;">
        <form method="GET" action="{{ route('pengiriman.index') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.875rem; align-items: end;">
            <div>
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    @foreach(['Selesai Diinput', 'Akan Di Kirim', 'Terkirim', 'Cancel'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Divisi</label>
                <select name="divisi" class="form-select">
                    <option value="">Semua Divisi</option>
                    @foreach($divisi as $d)
                    <option value="{{ $d->id }}" {{ request('divisi') == $d->id ? 'selected' : '' }}>{{ $d->singkatan }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn btn-primary btn-sm" style="flex: 1;">Filter</button>
                <a href="{{ route('pengiriman.index') }}" class="btn btn-outline btn-sm">Reset</a>
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
                    <th style="text-align: center;">Jumlah Box</th>
                    <th style="text-align: center;">Status Pengiriman</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuan as $i => $p)
                @php
                $badgeMap = [
                    'Selesai Diinput' => 'badge-purple',
                    'Akan Di Kirim'   => 'badge-orange',
                    'Terkirim'        => 'badge-green',
                    'Cancel'          => 'badge-gray',
                ];
                @endphp
                <tr>
                    <td style="text-align: center; color: #94a3b8; font-size: 0.82rem;">{{ $pengajuan->firstItem() + $i }}</td>
                    <td>
                        <div style="font-family: monospace; font-weight: 700; color: #0f172a; font-size: 0.85rem;">{{ $p->no_pengajuan }}</div>
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div class="divisi-avatar">{{ strtoupper(substr($p->divisi->singkatan ?? 'XX', 0, 3)) }}</div>
                            <div style="font-weight: 600; font-size: 0.85rem;">{{ $p->divisi->nama ?? '-' }}</div>
                        </div>
                    </td>
                    <td style="text-align: center; font-weight: 700; color: #475569;">{{ $p->box->count() }}</td>
                    <td style="text-align: center;">
                        <span class="badge {{ $badgeMap[$p->status] ?? 'badge-gray' }}" style="font-size: 0.68rem;" id="badgeStatus_{{ $p->id }}">{{ $p->status }}</span>
                    </td>
                    <td style="text-align: center;">
                        <div style="display: flex; justify-content: center; gap: 0.375rem;">
                            <button onclick="viewTracking({{ $p->id }})" class="btn btn-icon btn-info btn-xs" title="Lihat Timeline Tracking">
                                <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </button>
                            <button onclick="openUpdateStatus({{ $p->id }}, '{{ $p->no_pengajuan }}')" class="btn btn-icon btn-primary btn-xs" title="Update Status Pengiriman">
                                <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <p>Tidak ada pengajuan yang siap dikirim.</p>
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

{{-- Modal Update Status --}}
<div class="modal-overlay" id="modalUpdateStatus">
    <div class="modal-box" style="max-width: 450px;">
        <div class="modal-header">
            <div>
                <div style="font-weight: 700; color: #0f172a;">Update Status Pengiriman</div>
                <div style="font-size: 0.8rem; color: #64748b;" id="updateNoPengajuan">-</div>
            </div>
            <button onclick="document.getElementById('modalUpdateStatus').classList.remove('active')" style="border:none;background:none;cursor:pointer;color:#94a3b8;font-size:1.25rem;">&times;</button>
        </div>
        <form id="formUpdateStatus" onsubmit="submitUpdateStatus(event)">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Status Baru</label>
                    <select name="status" class="form-select" required>
                        <option value="Akan Di Kirim">Akan Di Kirim</option>
                        <option value="Terkirim">Terkirim</option>
                        <option value="Cancel">Cancel</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Keterangan / Posisi / Nama Penerima</label>
                    <textarea name="keterangan" class="form-textarea" rows="3" placeholder="Opsional"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="document.getElementById('modalUpdateStatus').classList.remove('active')" class="btn btn-outline btn-sm">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm" id="btnSubmitStatus">Simpan Status</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Tracking Timeline --}}
<div class="modal-overlay" id="modalTracking">
    <div class="modal-box" style="max-width: 550px;">
        <div class="modal-header">
            <div>
                <div style="font-weight: 700; color: #0f172a; font-size: 1.1rem; font-family: monospace;" id="trackNo">-</div>
                <div style="font-size: 0.8rem; color: #64748b; margin-top: 0.25rem;" id="trackDivisi">-</div>
            </div>
            <button onclick="document.getElementById('modalTracking').classList.remove('active')" style="border:none;background:none;cursor:pointer;color:#94a3b8;font-size:1.25rem;">&times;</button>
        </div>
        <div class="modal-body" style="background: #f8fafc; padding: 2rem;">
            
            <div style="display: flex; justify-content: space-around; padding: 1rem; background: white; border-radius: 12px; margin-bottom: 2rem; border: 1px solid #e2e8f0; text-align: center;">
                <div>
                    <div style="font-size: 1.25rem; font-weight: 800; color: #0f172a;" id="trackBox">0</div>
                    <div style="font-size: 0.7rem; color: #64748b; text-transform: uppercase;">Total Box</div>
                </div>
                <div>
                    <div style="font-size: 1.25rem; font-weight: 800; color: #0f172a;" id="trackBantex">0</div>
                    <div style="font-size: 0.7rem; color: #64748b; text-transform: uppercase;">Total Bantex</div>
                </div>
                <div>
                    <div style="font-size: 1.1rem; font-weight: 700; color: #16a34a; margin-top: 0.15rem;" id="trackStatus">-</div>
                    <div style="font-size: 0.7rem; color: #64748b; text-transform: uppercase;">Status Saat Ini</div>
                </div>
            </div>

            <div id="timelineContainer" style="position: relative; padding-left: 0.5rem;">
                {{-- Timeline items will be injected here via AJAX --}}
                <div style="text-align: center; color: #94a3b8; font-size: 0.85rem;">Memuat data timeline...</div>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let currentPengajuanId = null;

function openUpdateStatus(id, noPengajuan) {
    currentPengajuanId = id;
    document.getElementById('updateNoPengajuan').textContent = noPengajuan;
    document.getElementById('formUpdateStatus').reset();
    document.getElementById('modalUpdateStatus').classList.add('active');
}

async function submitUpdateStatus(e) {
    e.preventDefault();
    const btn = document.getElementById('btnSubmitStatus');
    btn.disabled = true;
    btn.innerHTML = 'Menyimpan...';

    const formData = new FormData(e.target);
    const data = {
        _token: document.querySelector('meta[name="csrf-token"]').content,
        status: formData.get('status'),
        keterangan: formData.get('keterangan')
    };

    try {
        const response = await fetch(`/pengiriman/${currentPengajuanId}/status`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(data)
        });

        const result = await response.json();
        
        if (response.ok) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: result.message,
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire('Error', result.message || 'Terjadi kesalahan sistem', 'error');
            btn.disabled = false;
            btn.innerHTML = 'Simpan Status';
        }
    } catch (err) {
        console.error(err);
        Swal.fire('Error', 'Gagal terhubung ke server', 'error');
        btn.disabled = false;
        btn.innerHTML = 'Simpan Status';
    }
}

async function viewTracking(id) {
    document.getElementById('timelineContainer').innerHTML = '<div style="text-align:center; color:#94a3b8; font-size:0.85rem; padding: 2rem;">Memuat data timeline...</div>';
    document.getElementById('modalTracking').classList.add('active');

    try {
        const response = await fetch(`/pengiriman/${id}/tracking`);
        const data = await response.json();
        
        document.getElementById('trackNo').textContent = data.no_pengajuan;
        document.getElementById('trackDivisi').textContent = `${data.singkatan} - ${data.divisi}`;
        document.getElementById('trackBox').textContent = data.jumlah_box;
        document.getElementById('trackBantex').textContent = data.jumlah_bantex;
        document.getElementById('trackStatus').textContent = data.status;

        let html = '';
        data.timeline.forEach(item => {
            let colorHex = '#e2e8f0';
            if (item.warna === 'green') colorHex = '#16a34a';
            else if (item.warna === 'blue') colorHex = '#3b82f6';
            else if (item.warna === 'yellow') colorHex = '#eab308';
            else if (item.warna === 'red') colorHex = '#ef4444';

            html += `
            <div class="timeline-item">
                <div class="timeline-dot" style="background: ${colorHex}; color: white; box-shadow: 0 0 0 2px ${colorHex};">
                    <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        ${item.icon === 'truck' ? '<path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zm10 0a2 2 0 11-4 0 2 2 0 014 0zM13 17V7H1v10m12 0V9l4 4m0-4v4m-4-4h-1"/>' : '<path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0121 9.414V19a2 2 0 01-2 2z"/>'}
                    </svg>
                </div>
                <div>
                    <div style="font-weight: 700; color: #0f172a; font-size: 0.95rem;">${item.status}</div>
                    <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.25rem;">
                        <span>${item.waktu}</span> &bull; <span>Oleh: ${item.petugas}</span>
                    </div>
                    ${item.ket ? `<div style="margin-top: 0.5rem; font-size: 0.85rem; color: #475569; padding: 0.5rem 0.75rem; background: white; border: 1px solid #e2e8f0; border-radius: 6px;">${item.ket}</div>` : ''}
                </div>
            </div>
            `;
        });
        document.getElementById('timelineContainer').innerHTML = html;

    } catch (err) {
        console.error(err);
        document.getElementById('timelineContainer').innerHTML = '<div style="text-align:center; color:#ef4444; font-size:0.85rem; padding: 2rem;">Gagal memuat timeline tracking</div>';
    }
}
</script>
@endpush
