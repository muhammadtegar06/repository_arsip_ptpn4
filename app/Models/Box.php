<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Box extends Model
{
    protected $table = 'box';
    protected $fillable = ['id_pengajuan', 'nomor_box', 'rfid_code', 'keterangan'];

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class, 'id_pengajuan');
    }

    public function bantex(): HasMany
    {
        return $this->hasMany(Bantex::class, 'id_box');
    }

    public function peminjaman()
    {
        return $this->belongsToMany(Peminjaman::class, 'peminjaman_box', 'id_box', 'id_peminjaman')->withTimestamps();
    }
}
