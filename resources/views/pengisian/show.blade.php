@extends('layouts.app')
@section('title', 'Input Data Box – ' . $pengajuan->no_pengajuan)
@section('page-title', 'Input Data Box')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <span class="breadcrumb-sep">›</span>
    <a href="{{ route('pengisian.index') }}">Pengisian Data</a>
    <span class="breadcrumb-sep">›</span>
    <span>{{ $pengajuan->no_pengajuan }}</span>
@endsection

@section('content')

@php
    $terisi = $pengajuan->box->count();
    $target = $pengajuan->jumlah_box;
    $percent = $target > 0 ? min(100, round(($terisi / $target) * 100)) : 0;
    $isSelesai = $terisi >= $target;
@endphp

<div class="card" style="margin-bottom: 1.25rem;">
    <div class="card-body">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div class="divisi-avatar" style="width: 48px; height: 48px;">
                    {{ strtoupper(substr($pengajuan->divisi->singkatan ?? 'XX', 0, 3)) }}
                </div>
                <div>
                    <div style="font-weight: 800; font-size: 1.1rem; color: #0f172a; font-family: monospace;">{{ $pengajuan->no_pengajuan }}</div>
                    <div style="font-size: 0.85rem; color: #475569;">{{ $pengajuan->divisi->nama ?? '-' }}</div>
                </div>
            </div>
            
            <div style="text-align: right;">
                <div style="font-size: 0.75rem; color: #64748b; text-transform: uppercase; margin-bottom: 0.25rem; font-weight: 600;">Progress Input Box</div>
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 150px; height: 8px; background: #e2e8f0; border-radius: 4px; overflow: hidden;">
                        <div style="height: 100%; width: {{ $percent }}%; background: {{ $isSelesai ? '#16a34a' : '#2563eb' }}; border-radius: 4px; transition: width 0.3s ease;"></div>
                    </div>
                    <div style="font-size: 0.9rem; font-weight: 700; color: {{ $isSelesai ? '#16a34a' : '#0f172a' }};">
                        {{ $terisi }} / {{ $target }}
                    </div>
                </div>
            </div>
        </div>

        @if($isSelesai && $pengajuan->status === 'Selesai Diinput')
        <div style="margin-top: 1rem; padding: 0.75rem 1rem; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; color: #166534; font-size: 0.85rem; display: flex; align-items: center; gap: 0.5rem;">
            <svg style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
            <strong>Target Terpenuhi!</strong> Pengajuan ini sudah selesai diinput dan masuk ke tahap selanjutnya.
        </div>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 style="font-weight: 700; color: #0f172a; font-size: 0.95rem;">Daftar Box</h3>
        @if(!$isSelesai)
        <button onclick="document.getElementById('modalTambahBox').classList.add('active')" class="btn btn-primary btn-sm">
            <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 4v16m8-8H4"/></svg>
            Tambah Box
        </button>
        @endif
    </div>
    
    <div class="card-body" style="padding: 1.25rem;">
        @if($pengajuan->box->isEmpty())
        <div class="empty-state">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
            <p>Belum ada box yang diinput. Mulai dengan klik tombol "Tambah Box".</p>
        </div>
        @else
        <div style="display: grid; grid-template-columns: 1fr; gap: 1rem;">
            @foreach($pengajuan->box as $box)
            <div style="border: 1.5px solid #e2e8f0; border-radius: 12px; overflow: hidden; background: white;">
                
                {{-- Box Header --}}
                <div style="background: #f8fafc; padding: 1rem 1.25rem; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #f1f5f9;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 40px; height: 40px; background: #dbeafe; color: #2563eb; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                            B{{ $loop->iteration }}
                        </div>
                        <div>
                            <div style="font-weight: 800; font-size: 1rem; color: #0f172a;">Box Nomor: <span style="font-family: monospace; color: #16a34a;">{{ $box->nomor_box }}</span></div>
                            <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.125rem;">
                                RFID: {{ $box->rfid_code ?? 'Kosong' }} | Ket: {{ $box->keterangan ?? '-' }}
                            </div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <button onclick="openModalBantex({{ $box->id }}, '{{ $box->nomor_box }}')" class="btn btn-outline btn-xs">
                            <svg style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 4v16m8-8H4"/></svg>
                            Tambah Bantex
                        </button>
                        <form action="{{ route('pengisian.box.destroy', $box) }}" method="POST" onsubmit="return confirm('Hapus Box ini beserta seluruh Bantex & Dokumen di dalamnya?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-icon btn-outline btn-xs" style="color: #dc2626; border-color: #fca5a5;">
                                <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Daftar Bantex --}}
                <div style="padding: 1.25rem;">
                    @if($box->bantex->isEmpty())
                    <div style="text-align: center; color: #94a3b8; font-size: 0.8rem; padding: 1rem;">Belum ada Bantex di dalam Box ini.</div>
                    @else
                    <div style="display: grid; gap: 1rem;">
                        @foreach($box->bantex as $bantex)
                        <div style="border: 1px solid #f1f5f9; border-radius: 8px; background: #fdfdfd;">
                            
                            {{-- Bantex Header --}}
                            <div style="padding: 0.875rem 1rem; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px dashed #e2e8f0;">
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <svg style="width:16px;height:16px;color:#7c3aed;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                                    <span style="font-weight: 700; font-size: 0.85rem; color: #334155;">{{ $bantex->nama_bantex }}</span>
                                    @if($bantex->tahun_awal)
                                    <span class="badge badge-gray" style="font-size: 0.65rem;">{{ $bantex->tahun_awal }}{{ $bantex->tahun_akhir ? ' - '.$bantex->tahun_akhir : '' }}</span>
                                    @endif
                                </div>
                                <div style="display: flex; gap: 0.375rem;">
                                    <button onclick="openModalDokumen({{ $bantex->id }}, '{{ $bantex->nama_bantex }}')" class="btn btn-outline btn-xs" style="padding: 0.2rem 0.5rem; font-size: 0.7rem;">
                                        + Dokumen
                                    </button>
                                    <form action="{{ route('pengisian.bantex.destroy', $bantex) }}" method="POST" onsubmit="return confirm('Hapus Bantex ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-icon btn-outline btn-xs" style="width: 24px; height: 24px; color: #dc2626;">
                                            &times;
                                        </button>
                                    </form>
                                </div>
                            </div>

                            {{-- Daftar Dokumen --}}
                            <div style="padding: 0.75rem 1rem;">
                                @if($bantex->dokumen->isEmpty())
                                <div style="font-size: 0.75rem; color: #94a3b8; font-style: italic;">Kosong</div>
                                @else
                                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                    @foreach($bantex->dokumen as $dokumen)
                                    <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.8rem; background: white; padding: 0.5rem 0.75rem; border: 1px solid #f1f5f9; border-radius: 6px;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <svg style="width:14px;height:14px;color:#cbd5e1;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                            <span style="color: #475569; font-weight: 500;">{{ $dokumen->nama_dokumen }}</span>
                                            @if($dokumen->nomor_dokumen) <span style="color: #94a3b8; font-size: 0.7rem;">(#{{ $dokumen->nomor_dokumen }})</span> @endif
                                        </div>
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            @if($dokumen->file_path)
                                            <a href="{{ Storage::url($dokumen->file_path) }}" target="_blank" class="btn btn-icon btn-info btn-xs" style="width:20px;height:20px;" title="Lihat File">
                                                <svg style="width:10px;height:10px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </a>
                                            @endif
                                            <form action="{{ route('pengisian.dokumen.destroy', $dokumen) }}" method="POST" onsubmit="return confirm('Hapus Dokumen ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-icon btn-danger btn-xs" style="width:20px;height:20px;">
                                                    <svg style="width:10px;height:10px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

{{-- Modal Tambah Box --}}
<div class="modal-overlay" id="modalTambahBox">
    <div class="modal-box" style="max-width: 450px;">
        <div class="modal-header">
            <div style="font-weight: 700; color: #0f172a;">Tambah Box Baru</div>
            <button onclick="document.getElementById('modalTambahBox').classList.remove('active')" style="border:none;background:none;cursor:pointer;color:#94a3b8;font-size:1.25rem;">&times;</button>
        </div>
        <form action="{{ route('pengisian.box.store', $pengajuan) }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nomor Box <span style="color: #dc2626;">*</span></label>
                    <input type="text" name="nomor_box" class="form-input" required placeholder="Contoh: BOX-001">
                </div>
                <div class="form-group">
                    <label class="form-label">Kode RFID</label>
                    <input type="text" name="rfid_code" class="form-input" placeholder="Opsional">
                </div>
                <div class="form-group">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-textarea" rows="2" placeholder="Opsional"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="document.getElementById('modalTambahBox').classList.remove('active')" class="btn btn-outline btn-sm">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">Simpan Box</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Tambah Bantex --}}
<div class="modal-overlay" id="modalTambahBantex">
    <div class="modal-box" style="max-width: 450px;">
        <div class="modal-header">
            <div>
                <div style="font-weight: 700; color: #0f172a;">Tambah Bantex</div>
                <div style="font-size: 0.8rem; color: #64748b;">Ke Box: <span id="labelBantexBox" style="font-family: monospace; color: #16a34a; font-weight: 700;"></span></div>
            </div>
            <button onclick="document.getElementById('modalTambahBantex').classList.remove('active')" style="border:none;background:none;cursor:pointer;color:#94a3b8;font-size:1.25rem;">&times;</button>
        </div>
        <form id="formTambahBantex" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nama Bantex <span style="color: #dc2626;">*</span></label>
                    <input type="text" name="nama_bantex" class="form-input" required placeholder="Contoh: Surat Keputusan Direksi">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Tahun Awal</label>
                        <input type="number" name="tahun_awal" class="form-input" placeholder="YYYY" min="1900" max="2100">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tahun Akhir</label>
                        <input type="number" name="tahun_akhir" class="form-input" placeholder="YYYY" min="1900" max="2100">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-textarea" rows="2" placeholder="Opsional"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="document.getElementById('modalTambahBantex').classList.remove('active')" class="btn btn-outline btn-sm">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">Simpan Bantex</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Tambah Dokumen --}}
<div class="modal-overlay" id="modalTambahDokumen">
    <div class="modal-box" style="max-width: 500px;">
        <div class="modal-header">
            <div>
                <div style="font-weight: 700; color: #0f172a;">Tambah Dokumen</div>
                <div style="font-size: 0.8rem; color: #64748b;">Ke Bantex: <span id="labelDokumenBantex" style="font-weight: 600; color: #16a34a;"></span></div>
            </div>
            <button onclick="document.getElementById('modalTambahDokumen').classList.remove('active')" style="border:none;background:none;cursor:pointer;color:#94a3b8;font-size:1.25rem;">&times;</button>
        </div>
        <form id="formTambahDokumen" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nama / Perihal Dokumen <span style="color: #dc2626;">*</span></label>
                    <input type="text" name="nama_dokumen" class="form-input" required placeholder="Contoh: SK Pengangkatan Pegawai Baru">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Nomor Dokumen</label>
                        <input type="text" name="nomor_dokumen" class="form-input" placeholder="Opsional">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Dokumen</label>
                        <input type="date" name="tgl_dokumen" class="form-input">
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Upload Softcopy</label>
                    <input type="file" name="file_dokumen" class="form-input" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    <div style="font-size: 0.7rem; color: #94a3b8; margin-top: 0.375rem;">Maks 10MB. Format: PDF, DOC, JPG, PNG</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="document.getElementById('modalTambahDokumen').classList.remove('active')" class="btn btn-outline btn-sm">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">Simpan Dokumen</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openModalBantex(boxId, boxNomor) {
    document.getElementById('formTambahBantex').action = `/pengisian/bantex/${boxId}`;
    document.getElementById('labelBantexBox').textContent = boxNomor;
    document.getElementById('modalTambahBantex').classList.add('active');
}

function openModalDokumen(bantexId, bantexNama) {
    document.getElementById('formTambahDokumen').action = `/pengisian/dokumen/${bantexId}`;
    document.getElementById('labelDokumenBantex').textContent = bantexNama;
    document.getElementById('modalTambahDokumen').classList.add('active');
}
</script>
@endpush
