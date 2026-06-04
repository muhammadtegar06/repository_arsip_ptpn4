<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengajuan::with(['divisi', 'user']);

        if (auth()->user()->isUserDivisi()) {
            $query->where('id_divisi', auth()->user()->id_divisi);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('divisi')) {
            $query->where('id_divisi', $request->divisi);
        }
        if ($request->filled('tgl_awal')) {
            $query->whereDate('tanggal_pengajuan', '>=', $request->tgl_awal);
        }
        if ($request->filled('tgl_akhir')) {
            $query->whereDate('tanggal_pengajuan', '<=', $request->tgl_akhir);
        }

        $pengajuan = $query->orderByDesc('id')->paginate(15)->withQueryString();
        $divisi    = Divisi::orderBy('nama')->get();

        return view('pengajuan.index', compact('pengajuan', 'divisi'));
    }

    public function create()
    {
        $divisi = Divisi::orderBy('nama')->get();
        return view('pengajuan.create', compact('divisi'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->isUserDivisi()) {
            $request->merge(['id_divisi' => $user->id_divisi]);
        }

        $request->validate([
            'id_divisi'         => 'required|exists:divisi,id',
            'tanggal_pengajuan' => 'required|date',
            'jumlah_box'        => 'required|integer|min:1|max:1000',
            'catatan'           => 'nullable|string|max:500',
        ], [
            'id_divisi.required'         => 'Divisi wajib dipilih.',
            'tanggal_pengajuan.required' => 'Tanggal pengajuan wajib diisi.',
            'jumlah_box.required'        => 'Jumlah box wajib diisi.',
            'jumlah_box.min'             => 'Jumlah box minimal 1.',
        ]);

        $pengajuan = Pengajuan::create([
            'no_pengajuan'      => Pengajuan::generateNoPengajuan(),
            'id_divisi'         => $request->id_divisi,
            'id_user'           => $user->id,
            'tanggal_pengajuan' => $request->tanggal_pengajuan,
            'jumlah_box'        => $request->jumlah_box,
            'status'            => 'Menunggu Persetujuan',
            'catatan'           => $request->catatan,
        ]);

        return redirect()->route('pengajuan.index')
            ->with('success', "Pengajuan {$pengajuan->no_pengajuan} berhasil dibuat.");
    }

    public function show(Pengajuan $pengajuan)
    {
        $pengajuan->load(['divisi', 'user', 'box.bantex.dokumen', 'pengiriman.history']);
        return view('pengajuan.show', compact('pengajuan'));
    }

    public function approval(Request $request, Pengajuan $pengajuan)
    {
        $request->validate([
            'aksi'         => 'required|in:setujui,tolak',
            'alasan_tolak' => 'required_if:aksi,tolak|nullable|string|max:500',
        ]);

        if ($request->aksi === 'setujui') {
            $pengajuan->update(['status' => 'Disetujui', 'alasan_tolak' => null]);
            $msg = "Pengajuan {$pengajuan->no_pengajuan} telah disetujui.";
        } else {
            $pengajuan->update(['status' => 'Ditolak', 'alasan_tolak' => $request->alasan_tolak]);
            $msg = "Pengajuan {$pengajuan->no_pengajuan} ditolak.";
        }

        return back()->with('success', $msg);
    }

    public function destroy(Pengajuan $pengajuan)
    {
        if (!in_array($pengajuan->status, ['Menunggu Persetujuan', 'Ditolak'])) {
            return back()->with('error', 'Hanya pengajuan dengan status Menunggu atau Ditolak yang dapat dihapus.');
        }

        $no = $pengajuan->no_pengajuan;
        $pengajuan->delete();

        return redirect()->route('pengajuan.index')
            ->with('success', "Pengajuan {$no} berhasil dihapus.");
    }
}
