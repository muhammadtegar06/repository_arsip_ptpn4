<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\Divisi;
use App\Models\Box;
use App\Models\Bantex;
use App\Models\Dokumen;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengajuan::with(['divisi', 'box.bantex.dokumen']);

        if (auth()->user()->isUserDivisi()) {
            $query->where('id_divisi', auth()->user()->id_divisi);
        }

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

        $pengajuanIds = (clone $query)->pluck('id');
        $boxIds = Box::whereIn('id_pengajuan', $pengajuanIds)->pluck('id');
        $bantexIds = Bantex::whereIn('id_box', $boxIds)->pluck('id');

        $laporan    = $query->orderByDesc('tanggal_pengajuan')->paginate(20)->withQueryString();
        $divisi     = Divisi::orderBy('nama')->get();
        $totalBox   = $boxIds->count();
        $totalDok   = Dokumen::whereIn('id_bantex', $bantexIds)->count();

        return view('laporan.index', compact('laporan', 'divisi', 'totalBox', 'totalDok'));
    }

    public function export(Request $request)
    {
        $params = $request->only(['divisi', 'status', 'tgl_awal', 'tgl_akhir']);
        $filename = 'Laporan-Arsip-PTPN4-' . date('YmdHis') . '.xlsx';
        return Excel::download(new LaporanExport($params), $filename);
    }
}
