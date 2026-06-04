@extends('layouts.app')
@section('title', 'Manajemen Divisi')
@section('page-title', 'Data Divisi PTPN4')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <span class="breadcrumb-sep">›</span>
    <span>Manajemen Divisi</span>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h2 class="page-title">Manajemen Data Divisi</h2>
        <p class="page-subtitle">Kelola daftar divisi yang berhak mengajukan box arsip</p>
    </div>
    <button onclick="openModalCreate()" class="btn btn-primary">
        <svg style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 4v16m8-8H4"/></svg>
        Tambah Divisi Baru
    </button>
</div>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">No</th>
                    <th style="width: 120px;">Singkatan</th>
                    <th>Nama Divisi</th>
                    <th>Keterangan</th>
                    <th style="text-align: center; width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($divisi as $i => $d)
                <tr>
                    <td style="text-align: center; color: #94a3b8; font-size: 0.82rem;">{{ $i + 1 }}</td>
                    <td><div class="badge badge-gray" style="font-size: 0.75rem; font-family: monospace;">{{ $d->singkatan }}</div></td>
                    <td style="font-weight: 600; color: #0f172a;">{{ $d->nama }}</td>
                    <td style="color: #64748b; font-size: 0.85rem;">{{ $d->keterangan ?: '-' }}</td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.375rem; justify-content: center;">
                            <button onclick="openModalEdit({{ $d->id }}, '{{ $d->nama }}', '{{ $d->singkatan }}', '{{ $d->keterangan }}')" class="btn btn-icon btn-warning btn-xs" title="Edit">
                                <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <form action="{{ route('divisi.destroy', $d) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus divisi ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-danger btn-xs" title="Hapus">
                                    <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6m5 0V4h4v2"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <p>Belum ada data divisi. Silakan tambah data divisi baru.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('modals')
{{-- Modal Create/Edit --}}
<div class="modal-overlay" id="modalDivisi">
    <div class="modal-box" style="max-width: 500px;">
        <div class="modal-header">
            <div>
                <div style="font-weight: 700; color: #0f172a;" id="modalTitle">Tambah Divisi</div>
                <div style="font-size: 0.8rem; color: #64748b;">Isi data divisi dengan benar</div>
            </div>
            <button onclick="document.getElementById('modalDivisi').classList.remove('active')" style="border:none;background:none;cursor:pointer;color:#94a3b8;font-size:1.25rem;">&times;</button>
        </div>
        <form id="formDivisi" method="POST" action="{{ route('divisi.store') }}">
            @csrf
            <input type="hidden" name="_method" id="methodDivisi" value="POST">
            <div class="modal-body">
                <div style="display: grid; grid-template-columns: 100px 1fr; gap: 1rem;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Singkatan <span style="color: #dc2626;">*</span></label>
                        <input type="text" name="singkatan" id="inputSingkatan" class="form-input" required placeholder="Ex: DSPN" maxlength="20">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Nama Divisi Lengkap <span style="color: #dc2626;">*</span></label>
                        <input type="text" name="nama" id="inputNama" class="form-input" required placeholder="Contoh: Divisi Sekretariat Perusahaan">
                    </div>
                </div>
                <div class="form-group" style="margin-top: 1.25rem;">
                    <label class="form-label">Keterangan Tambahan</label>
                    <textarea name="keterangan" id="inputKeterangan" class="form-textarea" rows="3" placeholder="Opsional..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="document.getElementById('modalDivisi').classList.remove('active')" class="btn btn-outline btn-sm">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">Simpan Divisi</button>
            </div>
        </form>
    </div>
</div>
@endpush

@endsection

@push('scripts')
<script>
function openModalCreate() {
    document.getElementById('modalTitle').textContent = 'Tambah Divisi Baru';
    document.getElementById('formDivisi').action = '{{ route("divisi.store") }}';
    document.getElementById('methodDivisi').value = 'POST';
    document.getElementById('inputSingkatan').value = '';
    document.getElementById('inputNama').value = '';
    document.getElementById('inputKeterangan').value = '';
    document.getElementById('modalDivisi').classList.add('active');
}

function openModalEdit(id, nama, singkatan, keterangan) {
    document.getElementById('modalTitle').textContent = 'Edit Divisi';
    document.getElementById('formDivisi').action = `/divisi/${id}`;
    document.getElementById('methodDivisi').value = 'PUT';
    document.getElementById('inputSingkatan').value = singkatan;
    document.getElementById('inputNama').value = nama;
    document.getElementById('inputKeterangan').value = keterangan !== '-' ? keterangan : '';
    document.getElementById('modalDivisi').classList.add('active');
}
</script>
@endpush
