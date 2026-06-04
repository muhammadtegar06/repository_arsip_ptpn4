<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Box;
use App\Models\Divisi;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'divisi', 'boxes']);

        if (auth()->user()->isUserDivisi()) {
            $query->where('id_divisi', auth()->user()->id_divisi);
        } elseif ($request->filled('divisi')) {
            $query->where('id_divisi', $request->divisi);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $peminjaman = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $divisi = Divisi::orderBy('nama')->get();

        return view('peminjaman.index', compact('peminjaman', 'divisi'));
    }

    public function create()
    {
        if (!auth()->user()->isUserDivisi()) {
            return redirect()->route('peminjaman.index')->with('error', 'Hanya User Divisi yang dapat mengajukan peminjaman.');
        }

        // Get boxes that belong to the user's division, and are already in the warehouse (Pengajuan = Terkirim)
        // Also ensure the box is not currently borrowed
        $availableBoxes = Box::whereHas('pengajuan', function($q) {
            $q->where('id_divisi', auth()->user()->id_divisi)
              ->where('status', 'Terkirim');
        })->whereDoesntHave('peminjaman', function($q) {
            $q->whereIn('status', ['Menunggu Persetujuan', 'Disetujui', 'Dipinjam']);
        })->get();

        return view('peminjaman.create', compact('availableBoxes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'boxes' => 'required|array|min:1',
            'boxes.*' => 'exists:box,id',
            'tanggal_kembali' => 'required|date|after:today',
            'alasan' => 'required|string',
        ]);

        $no_peminjaman = 'PMJ-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        $peminjaman = Peminjaman::create([
            'no_peminjaman' => $no_peminjaman,
            'id_user' => auth()->id(),
            'id_divisi' => auth()->user()->id_divisi,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_kembali' => $request->tanggal_kembali,
            'alasan' => $request->alasan,
            'status' => 'Menunggu Persetujuan',
        ]);

        $peminjaman->boxes()->attach($request->boxes);

        return redirect()->route('peminjaman.index')->with('success', 'Pengajuan peminjaman berhasil dibuat dan menunggu persetujuan.');
    }

    public function show(Peminjaman $peminjaman)
    {
        // Check access
        if (auth()->user()->isUserDivisi() && $peminjaman->id_divisi !== auth()->user()->id_divisi) {
            abort(403, 'Akses ditolak.');
        }

        $peminjaman->load(['user', 'divisi', 'boxes.pengajuan']);
        return view('peminjaman.show', compact('peminjaman'));
    }

    public function approval(Request $request, Peminjaman $peminjaman)
    {
        if (auth()->user()->isUserDivisi()) {
            abort(403);
        }

        $request->validate([
            'aksi' => 'required|in:setujui,tolak',
            'alasan_tolak' => 'required_if:aksi,tolak',
        ]);

        if ($request->aksi == 'setujui') {
            $peminjaman->update([
                'status' => 'Dipinjam', // Langsung dipinjam atau Disetujui dulu? Kita buat langsung Dipinjam agar simpel
                'keterangan_status' => 'Disetujui oleh ' . auth()->user()->nama,
            ]);
            $msg = 'Peminjaman disetujui, box dapat diambil.';
        } else {
            $peminjaman->update([
                'status' => 'Ditolak',
                'keterangan_status' => $request->alasan_tolak,
            ]);
            $msg = 'Peminjaman ditolak.';
        }

        return back()->with('success', $msg);
    }

    public function kembalikan(Peminjaman $peminjaman)
    {
        if (auth()->user()->isUserDivisi()) {
            abort(403);
        }

        $peminjaman->update([
            'status' => 'Dikembalikan',
            'keterangan_status' => 'Box telah dikembalikan pada ' . now()->format('d/m/Y H:i'),
        ]);

        return back()->with('success', 'Peminjaman diselesaikan, box telah dikembalikan ke gudang.');
    }
}
