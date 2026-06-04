<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus kolom email (tidak digunakan), tambah kolom custom
            $table->dropColumn(['email', 'email_verified_at', 'remember_token']);
            $table->string('username', 50)->unique()->after('name');
            $table->enum('role', ['Super Admin', 'Administrator', 'User Divisi'])->default('User Divisi')->after('username');
            $table->foreignId('id_divisi')->nullable()->after('role')->constrained('divisi')->onDelete('set null');
        });

        // Rename kolom 'name' menjadi 'nama' via rawStatement tidak bisa pakai alterTable biasa,
        // jadi kita rename dengan cara yang kompatibel
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('name', 'nama');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('nama', 'name');
            $table->string('email')->unique()->after('name');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->dropColumn(['username', 'role']);
        });
    }
};
