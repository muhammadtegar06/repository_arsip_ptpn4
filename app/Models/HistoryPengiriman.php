<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoryPengiriman extends Model
{
    protected $table = 'history_pengiriman';
    protected $fillable = ['id_pengiriman', 'status', 'keterangan', 'petugas', 'waktu'];

    protected $casts = ['waktu' => 'datetime'];

    public function pengiriman(): BelongsTo
    {
        return $this->belongsTo(Pengiriman::class, 'id_pengiriman');
    }
}
