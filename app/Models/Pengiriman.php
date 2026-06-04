<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengiriman extends Model
{
    protected $table = 'pengiriman';
    protected $fillable = ['id_pengajuan', 'tanggal_kirim', 'petugas', 'no_surat_jalan', 'keterangan'];

    protected $casts = ['tanggal_kirim' => 'date'];

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class, 'id_pengajuan');
    }

    public function history(): HasMany
    {
        return $this->hasMany(HistoryPengiriman::class, 'id_pengiriman')->orderBy('waktu', 'desc');
    }
}
