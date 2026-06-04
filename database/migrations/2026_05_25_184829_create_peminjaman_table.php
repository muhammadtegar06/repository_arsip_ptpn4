<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('no_peminjaman', 50)->unique();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_divisi')->constrained('divisi')->onDelete('cascade');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali');
            $table->text('alasan')->nullable();
            $table->enum('status', ['Menunggu Persetujuan', 'Disetujui', 'Ditolak', 'Dipinjam', 'Dikembalikan', 'Batal'])->default('Menunggu Persetujuan');
            $table->text('keterangan_status')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
