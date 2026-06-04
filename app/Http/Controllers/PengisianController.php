<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\Box;
use App\Models\Bantex;
use App\Models\Dokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengisianController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengajuan::with(['divisi', 'box'])
            ->whereIn('status', ['Disetujui', 'Selesai Diinput']);

        if (auth()->user()->isUserDivisi()) {
            $query->where('id_divisi', auth()->user()->id_divisi);
        }

        if ($request->filled('divisi')) {
            $query->where('id_divisi', $request->divisi);
        }

        $pengajuan = $query->orderByDesc('id')->paginate(15)->withQueryString();
        $divisi    = \App\Models\Divisi::orderBy('nama')->get();

        return view('pengisian.index', compact('pengajuan', 'divisi'));
    }

    public function show(Pengajuan $pengajuan)
    {
        if (auth()->user()->isUserDivisi() && $pengajuan->id_divisi !== auth()->user()->id_divisi) {
            abort(403, 'Akses ditolak.');
        }
        $pengajuan->load(['divisi', 'box.bantex.dokumen']);
        return view('pengisian.show', compact('pengajuan'));
    }

    public function storeBox(Request $request, Pengajuan $pengajuan)
    {
        $request->validate([
            'nomor_box'  => 'required|string|max:20',
            'rfid_code'  => 'nullable|string|max:100',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $pengajuan->box()->create($request->only('nomor_box', 'rfid_code', 'keterangan'));

        // Cek apakah semua box sudah terisi — update status
        $this->cekUpdateStatus($pengajuan);

        return back()->with('success', 'Box berhasil ditambahkan.');
    }

    public function destroyBox(Box $box)
    {
        $pengajuan = $box->pengajuan;
        $box->delete();
        $this->cekUpdateStatus($pengajuan);
        return back()->with('success', 'Box berhasil dihapus.');
    }

    public function storeBantex(Request $request, Box $box)
    {
        $request->validate([
            'nama_bantex' => 'required|string|max:200',
            'tahun_awal'  => 'nullable|digits:4',
            'tahun_akhir' => 'nullable|digits:4',
            'keterangan'  => 'nullable|string',
        ]);

        $box->bantex()->create($request->only('nama_bantex', 'tahun_awal', 'tahun_akhir', 'keterangan'));

        return back()->with('success', 'Bantex berhasil ditambahkan.');
    }

    public function destroyBantex(Bantex $bantex)
    {
        $bantex->delete();
        return back()->with('success', 'Bantex berhasil dihapus.');
    }

    public function storeDokumen(Request $request, Bantex $bantex)
    {
        $request->validate([
            'nama_dokumen'   => 'required|string|max:200',
            'nomor_dokumen'  => 'nullable|string|max:100',
            'tgl_dokumen'    => 'nullable|date',
            'file_dokumen'   => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ]);

        $filePath = null;
        if ($request->hasFile('file_dokumen')) {
            $filePath = $request->file('file_dokumen')->store('dokumen', 'public');
        }

        $bantex->dokumen()->create([
            'nama_dokumen'  => $request->nama_dokumen,
            'nomor_dokumen' => $request->nomor_dokumen,
            'tgl_dokumen'   => $request->tgl_dokumen,
            'file_path'     => $filePath,
        ]);

        return back()->with('success', 'Dokumen berhasil ditambahkan.');
    }

    public function destroyDokumen(Dokumen $dokumen)
    {
        if ($dokumen->file_path) {
            Storage::disk('public')->delete($dokumen->file_path);
        }
        $dokumen->delete();
        return back()->with('success', 'Dokumen berhasil dihapus.');
    }

    private function cekUpdateStatus(Pengajuan $pengajuan): void
    {
        $jumlahBoxTerisi = $pengajuan->box()->count();
        if ($jumlahBoxTerisi >= $pengajuan->jumlah_box && $pengajuan->status === 'Disetujui') {
            $pengajuan->update(['status' => 'Selesai Diinput']);
        } elseif ($jumlahBoxTerisi < $pengajuan->jumlah_box && $pengajuan->status === 'Selesai Diinput') {
            $pengajuan->update(['status' => 'Disetujui']);
        }
    }
}
