<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan', function (Blueprint $table) {
            $table->id();
            $table->string('no_pengajuan', 30)->unique();
            $table->foreignId('id_divisi')->constrained('divisi')->onDelete('restrict');
            $table->foreignId('id_user')->constrained('users')->onDelete('restrict');
            $table->date('tanggal_pengajuan');
            $table->integer('jumlah_box')->default(0);
            $table->enum('status', [
                'Menunggu Persetujuan',
                'Disetujui',
                'Ditolak',
                'Selesai Diinput',
                'Akan Di Kirim',
                'Terkirim',
                'Cancel'
            ])->default('Menunggu Persetujuan');
            $table->text('catatan')->nullable();
            $table->text('alasan_tolak')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan');
    }
};
