<?php

namespace App\Exports;

use App\Models\Pengajuan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class LaporanExport implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    protected array $params;

    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function collection()
    {
        $query = Pengajuan::with(['divisi', 'user', 'box'])
            ->withCount(['box as total_box']);

        if (auth()->user()->isUserDivisi()) {
            $query->where('id_divisi', auth()->user()->id_divisi);
        }

        if (!empty($this->params['divisi'])) {
            $query->where('id_divisi', $this->params['divisi']);
        }
        if (!empty($this->params['status'])) {
            $query->where('status', $this->params['status']);
        }
        if (!empty($this->params['tgl_awal'])) {
            $query->whereDate('tanggal_pengajuan', '>=', $this->params['tgl_awal']);
        }
        if (!empty($this->params['tgl_akhir'])) {
            $query->whereDate('tanggal_pengajuan', '<=', $this->params['tgl_akhir']);
        }

        $data = $query->orderByDesc('tanggal_pengajuan')->get();

        return $data->map(function ($row, $i) {
            return [
                'No'                => $i + 1,
                'No Pengajuan'      => $row->no_pengajuan,
                'Divisi'            => $row->divisi->nama ?? '-',
                'Tanggal Pengajuan' => $row->tanggal_pengajuan->format('d/m/Y'),
                'Jumlah Box'        => $row->jumlah_box,
                'Box Terisi'        => $row->total_box,
                'Status'            => $row->status,
                'Dibuat Oleh'       => $row->user->nama ?? '-',
                'Catatan'           => $row->catatan ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No', 'No. Pengajuan', 'Divisi', 'Tanggal Pengajuan',
            'Jumlah Box (Diajukan)', 'Box Terisi', 'Status', 'Dibuat Oleh', 'Catatan',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF16A34A']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function title(): string
    {
        return 'Laporan Arsip PTPN4';
    }
}
