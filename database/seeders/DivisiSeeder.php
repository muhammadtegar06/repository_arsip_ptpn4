<?php

namespace Database\Seeders;

use App\Models\Divisi;
use Illuminate\Database\Seeder;

class DivisiSeeder extends Seeder
{
    public function run(): void
    {
        $divisions = [
            ['singkatan' => 'DSPN', 'nama' => 'Divisi Sekretariat Perusahaan'],
            ['singkatan' => 'DTPI', 'nama' => 'Divisi Satuan Pengawasan Intern'],
            ['singkatan' => 'DTAN', 'nama' => 'Divisi Tanaman'],
            ['singkatan' => 'DTPL', 'nama' => 'Divisi Teknik & Pengolahan'],
            ['singkatan' => 'DINF', 'nama' => 'Divisi Infrastruktur'],
            ['singkatan' => 'DITN', 'nama' => 'Divisi Investasi Tanaman'],
            ['singkatan' => 'DPSN', 'nama' => 'Divisi Pemasaran'],
            ['singkatan' => 'DRPL', 'nama' => 'Divisi Rantai Pasok & Logistik'],
            ['singkatan' => 'DPEN', 'nama' => 'Divisi Pengadaan'],
            ['singkatan' => 'DSKP', 'nama' => 'Divisi Strategi Perusahaan & Pengendalian Kinerja Anak Perusahaan'],
            ['singkatan' => 'DSMS', 'nama' => 'Divisi Sistem Manajemen & Sustainability'],
            ['singkatan' => 'DRPH', 'nama' => 'Divisi Riset, Pengembangan Bisnis & Hilirisasi'],
            ['singkatan' => 'DKSH', 'nama' => 'Divisi Keuangan Strategis dan Hubungan Investor'],
            ['singkatan' => 'DPBA', 'nama' => 'Divisi Perbendaharaan & Anggaran'],
            ['singkatan' => 'DAPN', 'nama' => 'Divisi Akuntansi & Perpajakan'],
            ['singkatan' => 'DMRS', 'nama' => 'Divisi Manajemen Risiko'],
            ['singkatan' => 'DPSB', 'nama' => 'Divisi Pengembangan SDM dan Budaya'],
            ['singkatan' => 'DSDM', 'nama' => 'Divisi Operasional SDM'],
            ['singkatan' => 'DHPU', 'nama' => 'Divisi HPS & Umum'],
            ['singkatan' => 'DTIS', 'nama' => 'Divisi Teknologi Informasi'],
            ['singkatan' => 'DHKT', 'nama' => 'Divisi Hubungan Kelembagaan dan TJSL'],
            ['singkatan' => 'DHKM', 'nama' => 'Divisi Hukum'],
            ['singkatan' => 'DPSR', 'nama' => 'Divisi PSR dan Plasma'],
            ['singkatan' => 'DPMO', 'nama' => 'Project Management Office'],
        ];

        foreach ($divisions as $division) {
            Divisi::create($division);
        }
    }
}
