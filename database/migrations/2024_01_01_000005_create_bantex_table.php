<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bantex', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_box')->constrained('box')->onDelete('cascade');
            $table->string('nama_bantex', 200);
            $table->year('tahun_awal')->nullable();
            $table->year('tahun_akhir')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bantex');
    }
};
