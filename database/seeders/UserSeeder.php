<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin (Programmer)
        User::create([
            'nama'     => 'Super Admin (Programmer)',
            'username' => 'superadmin',
            'password' => Hash::make('admin123'),
            'role'     => 'Super Admin',
            'id_divisi'=> null,
        ]);

        // Administrator (Divisi Umum)
        User::create([
            'nama'     => 'Admin Divisi Umum',
            'username' => 'adminumum',
            'password' => Hash::make('admin123'),
            'role'     => 'Administrator',
            'id_divisi'=> 1, // Asumsi id 1 adalah Bagian Umum & SDM
        ]);

        // User Divisi (Contoh: Divisi Keuangan)
        User::create([
            'nama'     => 'User Keuangan',
            'username' => 'userkeuangan',
            'password' => Hash::make('admin123'),
            'role'     => 'User Divisi',
            'id_divisi'=> 2, // Asumsi id 2 adalah Bagian Keuangan & Akuntansi
        ]);
    }
}
