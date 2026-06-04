<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bantex extends Model
{
    protected $table = 'bantex';
    protected $fillable = ['id_box', 'nama_bantex', 'tahun_awal', 'tahun_akhir', 'keterangan'];

    public function box(): BelongsTo
    {
        return $this->belongsTo(Box::class, 'id_box');
    }

    public function dokumen(): HasMany
    {
        return $this->hasMany(Dokumen::class, 'id_bantex');
    }
}
