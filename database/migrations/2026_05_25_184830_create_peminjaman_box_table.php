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
        Schema::create('peminjaman_box', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_peminjaman')->constrained('peminjaman')->onDelete('cascade');
            $table->foreignId('id_box')->constrained('box')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman_box');
    }
};
