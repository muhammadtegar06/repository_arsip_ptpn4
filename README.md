# Sistem Informasi Repository Arsip - PTPN4

Aplikasi ini adalah Sistem Informasi Repository Arsip untuk PTPN4 yang dibangun menggunakan Laravel 12. Sistem ini digunakan untuk memfasilitasi pengajuan, pengisian data box arsip, persetujuan (approval), hingga manajemen pengiriman arsip box ke Repository Arsip terpusat.

## 🚀 Panduan Instalasi (Untuk Cloning)

Jika Anda ingin menjalankan proyek ini di komputer atau laptop lain, ikuti langkah-langkah di bawah ini:

### 1. Persyaratan Sistem
Pastikan laptop/komputer Anda sudah terinstal:
- **PHP** (minimal versi 8.2 atau 8.3)
- **Composer** (untuk instalasi dependency PHP)
- **Node.js & NPM** (untuk compile file CSS/JS)
- **MySQL/MariaDB** (melalui XAMPP, Laragon, dsb)
- **Git**

### 2. Clone Repositori
Buka terminal (Command Prompt/PowerShell/Git Bash) di folder tempat Anda ingin menyimpan proyek (misalnya `C:\laragon\www`), lalu jalankan:
```bash
git clone https://github.com/muhammadtegar06/repository_arsip_ptpn4.git
cd arsip_ptpn4
```

### 3. Install Dependency PHP & Node.js
Jalankan kedua perintah ini untuk mengunduh paket-paket yang dibutuhkan:
```bash
composer install
npm install
```

### 4. Konfigurasi Environment (.env)
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
*(Atau jika di Windows CMD/PowerShell, Anda bisa meng-copy paste manual file `.env.example` dan mengubah namanya menjadi `.env`)*

Buka file `.env` di teks editor (VS Code dll), lalu sesuaikan koneksi database Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_arsip_ptpn4    # (Pastikan database ini sudah dibuat di MySQL/phpMyAdmin)
DB_USERNAME=root           # Sesuaikan dengan username MySQL Anda
DB_PASSWORD=               # Sesuaikan dengan password MySQL Anda (kosongkan jika default)
```

### 5. Generate Application Key
Jalankan perintah berikut untuk mengamankan aplikasi:
```bash
php artisan key:generate
```

### 6. Migrasi Database dan Seeding
Perintah ini akan membuat semua tabel beserta data awal (termasuk daftar 24 Divisi dan akun *User* bawaan):
```bash
php artisan migrate:fresh --seed
```

### 7. Jalankan Server Lokal
Anda butuh menjalankan dua perintah ini (bisa di dua terminal yang berbeda):

Terminal 1 (Menjalankan server Laravel):
```bash
php artisan serve
```

Terminal 2 (Menjalankan *asset bundler* agar CSS dan tampilan berfungsi):
```bash
npm run dev
```

### 8. Selesai! 🎉
Akses aplikasi melalui browser di alamat:
**http://127.0.0.1:8000**

---

## Akun Login (Bawaan)
Gunakan salah satu dari akun berikut untuk mencoba aplikasi:
- **Super Admin** (Programmer/Akses Penuh):
  Username: `superadmin` | Password: `admin123`
- **Administrator** (Divisi Umum/Pengelola Gudang):
  Username: `adminumum` | Password: `admin123`
- **User Divisi** (Contoh: Bagian Keuangan):
  Username: `userkeuangan` | Password: `admin123`
