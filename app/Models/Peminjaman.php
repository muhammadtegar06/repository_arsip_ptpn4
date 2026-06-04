<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    protected $fillable = [
        'no_peminjaman', 'id_user', 'id_divisi', 'tanggal_pinjam',
        'tanggal_kembali', 'alasan', 'status', 'keterangan_status'
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'id_divisi');
    }

    public function boxes()
    {
        return $this->belongsToMany(Box::class, 'peminjaman_box', 'id_peminjaman', 'id_box')->withTimestamps();
    }
}
