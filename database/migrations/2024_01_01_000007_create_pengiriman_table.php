<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengiriman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengajuan')->constrained('pengajuan')->onDelete('cascade');
            $table->date('tanggal_kirim')->nullable();
            $table->string('petugas', 100)->nullable();
            $table->string('no_surat_jalan', 100)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengiriman');
    }
};
