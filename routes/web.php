<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\PengisianController;
use App\Http\Controllers\PengirimanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ============================================================
// AUTH ROUTES
// ============================================================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ============================================================
// AUTHENTICATED ROUTES
// ============================================================
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Profile (semua role)
    Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('profile.password');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Tentang
    Route::get('/tentang', fn() => view('tentang.index'))->name('tentang');

    // ============================================================
    // ADMINISTRATOR + USER DIVISI
    // ============================================================
    Route::middleware(['role:Administrator,User Divisi'])->group(function () {

        // Pengajuan Box
        Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan/buat', [PengajuanController::class, 'create'])->name('pengajuan.create');
        Route::post('/pengajuan', [PengajuanController::class, 'store'])->name('pengajuan.store');
        Route::get('/pengajuan/{pengajuan}', [PengajuanController::class, 'show'])->name('pengajuan.show');
        Route::delete('/pengajuan/{pengajuan}', [PengajuanController::class, 'destroy'])->name('pengajuan.destroy');

        // Pengisian Box
        Route::get('/pengisian', [PengisianController::class, 'index'])->name('pengisian.index');
        Route::get('/pengisian/{pengajuan}', [PengisianController::class, 'show'])->name('pengisian.show');
        Route::post('/pengisian/{pengajuan}/box', [PengisianController::class, 'storeBox'])->name('pengisian.box.store');
        Route::delete('/pengisian/box/{box}', [PengisianController::class, 'destroyBox'])->name('pengisian.box.destroy');
        Route::post('/pengisian/bantex/{box}', [PengisianController::class, 'storeBantex'])->name('pengisian.bantex.store');
        Route::delete('/pengisian/bantex/{bantex}', [PengisianController::class, 'destroyBantex'])->name('pengisian.bantex.destroy');
        Route::post('/pengisian/dokumen/{bantex}', [PengisianController::class, 'storeDokumen'])->name('pengisian.dokumen.store');
        Route::delete('/pengisian/dokumen/{dokumen}', [PengisianController::class, 'destroyDokumen'])->name('pengisian.dokumen.destroy');

        // Peminjaman Box
        Route::get('/peminjaman', [\App\Http\Controllers\PeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::get('/peminjaman/buat', [\App\Http\Controllers\PeminjamanController::class, 'create'])->name('peminjaman.create');
        Route::post('/peminjaman', [\App\Http\Controllers\PeminjamanController::class, 'store'])->name('peminjaman.store');
        Route::get('/peminjaman/{peminjaman}', [\App\Http\Controllers\PeminjamanController::class, 'show'])->name('peminjaman.show');
    });

    // ============================================================
    // ADMINISTRATOR ONLY (Divisi Umum)
    // ============================================================
    Route::middleware(['role:Administrator'])->group(function () {

        // Approval Pengajuan & Peminjaman
        Route::post('/pengajuan/{pengajuan}/approval', [PengajuanController::class, 'approval'])->name('pengajuan.approval');
        Route::post('/peminjaman/{peminjaman}/approval', [\App\Http\Controllers\PeminjamanController::class, 'approval'])->name('peminjaman.approval');
        Route::post('/peminjaman/{peminjaman}/kembalikan', [\App\Http\Controllers\PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');

        // Manajemen Pengiriman Box
        Route::get('/pengiriman', [PengirimanController::class, 'index'])->name('pengiriman.index');
        Route::post('/pengiriman/{pengajuan}/status', [PengirimanController::class, 'updateStatus'])->name('pengiriman.status');
        Route::get('/pengiriman/{pengajuan}/tracking', [PengirimanController::class, 'getTracking'])->name('pengiriman.tracking');

        // Manajemen Divisi
        Route::resource('/divisi', \App\Http\Controllers\DivisiController::class)->except(['create', 'show', 'edit']);
    });

    // ============================================================
    // SUPER ADMIN ONLY (Programmer)
    // ============================================================
    Route::middleware(['role:Super Admin'])->group(function () {
        // Manajemen User
        Route::resource('/users', UserController::class);
    });

    // ============================================================
    // SEMUA ROLE (Laporan)
    // ============================================================
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export');
});
