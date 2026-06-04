<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\Pengiriman;
use App\Models\HistoryPengiriman;
use App\Models\Divisi;
use Illuminate\Http\Request;

class PengirimanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengajuan::with(['divisi', 'box', 'pengiriman.history'])
            ->whereIn('status', ['Selesai Diinput', 'Akan Di Kirim', 'Terkirim', 'Cancel']);

        if ($request->filled('divisi')) {
            $query->where('id_divisi', $request->divisi);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('tgl_awal')) {
            $query->whereDate('tanggal_pengajuan', '>=', $request->tgl_awal);
        }
        if ($request->filled('tgl_akhir')) {
            $query->whereDate('tanggal_pengajuan', '<=', $request->tgl_akhir);
        }

        $pengajuan = $query->orderByDesc('id')->paginate(15)->withQueryString();
        $divisi    = Divisi::orderBy('nama')->get();

        return view('pengiriman.index', compact('pengajuan', 'divisi'));
    }

    public function updateStatus(Request $request, Pengajuan $pengajuan)
    {
        $request->validate([
            'status'     => 'required|in:Akan Di Kirim,Terkirim,Cancel',
            'keterangan' => 'nullable|string|max:500',
        ]);

        // Buat atau update record pengiriman
        $pengiriman = $pengajuan->pengiriman ?? Pengiriman::create([
            'id_pengajuan'  => $pengajuan->id,
            'tanggal_kirim' => now()->toDateString(),
            'petugas'       => auth()->user()->nama,
        ]);

        // Simpan history
        HistoryPengiriman::create([
            'id_pengiriman' => $pengiriman->id,
            'status'        => $request->status,
            'keterangan'    => $request->keterangan,
            'petugas'       => auth()->user()->nama,
            'waktu'         => now(),
        ]);

        // Update status pengajuan
        $pengajuan->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => "Status berhasil diubah menjadi {$request->status}.",
        ]);
    }

    public function getTracking(Pengajuan $pengajuan)
    {
        $pengajuan->load(['divisi', 'box', 'pengiriman.history']);
        $boxCount    = $pengajuan->box->count();
        $bantexCount = $pengajuan->box->sum(fn($b) => $b->bantex->count());

        $timeline = [];

        // Entry pertama: pengajuan dibuat
        $timeline[] = [
            'status'  => 'Pengajuan Dibuat',
            'waktu'   => $pengajuan->created_at->format('d M Y H:i'),
            'petugas' => $pengajuan->user->nama ?? 'Sistem',
            'warna'   => 'blue',
            'icon'    => 'document',
        ];

        // Riwayat pengiriman
        if ($pengajuan->pengiriman) {
            foreach ($pengajuan->pengiriman->history as $h) {
                $warna = match(true) {
                    str_contains(strtolower($h->status), 'terkirim') => 'green',
                    str_contains(strtolower($h->status), 'cancel')   => 'red',
                    str_contains(strtolower($h->status), 'kirim')    => 'yellow',
                    default                                            => 'gray',
                };
                $timeline[] = [
                    'status'  => strtoupper($h->status),
                    'waktu'   => $h->waktu->format('d M Y H:i'),
                    'petugas' => $h->petugas ?? 'Petugas Logistik',
                    'warna'   => $warna,
                    'icon'    => 'truck',
                    'ket'     => $h->keterangan,
                ];
            }
        }

        return response()->json([
            'no_pengajuan' => $pengajuan->no_pengajuan,
            'divisi'       => $pengajuan->divisi->nama,
            'singkatan'    => $pengajuan->divisi->singkatan,
            'status'       => $pengajuan->status,
            'jumlah_box'   => $boxCount,
            'jumlah_bantex'=> $bantexCount,
            'timeline'     => $timeline,
        ]);
    }
}
