<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dokumen extends Model
{
    protected $table = 'dokumen';
    protected $fillable = ['id_bantex', 'nama_dokumen', 'nomor_dokumen', 'file_path', 'tgl_dokumen'];

    protected $casts = ['tgl_dokumen' => 'date'];

    public function bantex(): BelongsTo
    {
        return $this->belongsTo(Bantex::class, 'id_bantex');
    }
}
