@extends('layouts.app')
@section('title', 'Tentang Aplikasi')
@section('page-title', 'Informasi Sistem')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <span class="breadcrumb-sep">›</span>
    <span>Tentang</span>
@endsection

@section('content')

<div style="max-width: 600px; margin: 0 auto; text-align: center;">
    
    <div style="margin-bottom: 2rem;">
        <div style="width: 100px; height: 100px; background: linear-gradient(135deg, #16a34a, #22c55e); border-radius: 20px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1rem; box-shadow: 0 10px 25px rgba(22,163,74,0.3);">
            <svg style="width: 50px; height: 50px; color: white;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/>
                <path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
            </svg>
        </div>
        <h2 style="font-weight: 800; color: #0f172a; font-size: 1.75rem; margin-bottom: 0.25rem;">Sistem Arsip Digital</h2>
        <h3 style="color: #16a34a; font-size: 1rem; font-weight: 600;">PT Perkebunan Nusantara IV</h3>
    </div>

    <div class="card" style="text-align: left; margin-bottom: 1.5rem;">
        <div class="card-body">
            <p style="color: #475569; line-height: 1.6; font-size: 0.95rem; margin-bottom: 1rem;">
                Aplikasi Sistem Arsip Digital PTPN4 dirancang khusus untuk mendata, memonitoring, dan memanajemen perpindahan data box arsip dari masing-masing divisi (Unit Kerja) yang akan dikirimkan dan disimpan pada Gudang Arsip pusat.
            </p>
            <p style="color: #475569; line-height: 1.6; font-size: 0.95rem;">
                Dibangun dengan antarmuka yang modern, sistem ini memberikan kemudahan tracking pengiriman, struktur penyimpanan box-bantex-dokumen yang jelas, dan fitur pelaporan terpusat.
            </p>

            <hr style="border: 0; border-top: 1px dashed #cbd5e1; margin: 1.5rem 0;">

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <div style="font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; font-weight: 600;">Versi Sistem</div>
                    <div style="font-size: 0.95rem; color: #0f172a; font-weight: 600;">v2.0 (Laravel 12)</div>
                </div>
                <div>
                    <div style="font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; font-weight: 600;">Stack Teknologi</div>
                    <div style="font-size: 0.95rem; color: #0f172a; font-weight: 600;">PHP 8.3 &bull; Tailwind CSS</div>
                </div>
                <div>
                    <div style="font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; font-weight: 600;">Dikembangkan Oleh</div>
                    <div style="font-size: 0.95rem; color: #0f172a; font-weight: 600;">Tim IT PTPN4</div>
                </div>
                <div>
                    <div style="font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; font-weight: 600;">Tahun Rilis</div>
                    <div style="font-size: 0.95rem; color: #0f172a; font-weight: 600;">2026</div>
                </div>
            </div>
        </div>
    </div>
    
    <div style="font-size: 0.75rem; color: #94a3b8;">
        Jika Anda mengalami kendala teknis atau masalah pada sistem, silakan hubungi Administrator PTPN4.
    </div>

</div>

@endsection
