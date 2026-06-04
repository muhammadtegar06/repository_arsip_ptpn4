<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\Box;
use App\Models\Bantex;
use App\Models\Dokumen;
use App\Models\Divisi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $qPengajuan = Pengajuan::query();
        if ($user->isUserDivisi()) {
            $qPengajuan->where('id_divisi', $user->id_divisi);
        }

        $totalPengajuan    = (clone $qPengajuan)->count();
        
        $pengajuanIds      = (clone $qPengajuan)->pluck('id');
        $boxIds            = Box::whereIn('id_pengajuan', $pengajuanIds)->pluck('id');
        $bantexIds         = Bantex::whereIn('id_box', $boxIds)->pluck('id');
        
        $totalBox          = $boxIds->count();
        $totalBantex       = $bantexIds->count();
        $totalDokumen      = Dokumen::whereIn('id_bantex', $bantexIds)->count();
        $totalDivisi       = Divisi::count();

        $menunggu          = (clone $qPengajuan)->where('status', 'Menunggu Persetujuan')->count();
        $disetujui         = (clone $qPengajuan)->where('status', 'Disetujui')->count();
        $selesaiInput      = (clone $qPengajuan)->where('status', 'Selesai Diinput')->count();
        $akanDikirim       = (clone $qPengajuan)->where('status', 'Akan Di Kirim')->count();
        $terkirim          = (clone $qPengajuan)->where('status', 'Terkirim')->count();

        // Pengajuan terbaru (5 data)
        $pengajuanTerbaru  = (clone $qPengajuan)->with(['divisi', 'user'])
            ->latest()
            ->take(5)
            ->get();

        // Statistik per divisi (tidak diubah, biar bisa membandingkan, atau filter juga)
        // Kita biarkan saja semua divisi terlihat di tabel statistik per divisi agar tahu posisi
        $perDivisi = Divisi::withCount('pengajuan')->orderByDesc('pengajuan_count')->get();

        return view('dashboard.index', compact(
            'totalPengajuan', 'totalBox', 'totalBantex', 'totalDokumen', 'totalDivisi',
            'menunggu', 'disetujui', 'selesaiInput', 'akanDikirim', 'terkirim',
            'pengajuanTerbaru', 'perDivisi'
        ));
    }
}
