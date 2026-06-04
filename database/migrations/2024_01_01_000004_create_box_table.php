<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('box', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengajuan')->constrained('pengajuan')->onDelete('cascade');
            $table->string('nomor_box', 20);
            $table->string('rfid_code', 100)->nullable();
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('box');
    }
};
