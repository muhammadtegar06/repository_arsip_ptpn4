<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Divisi extends Model
{
    protected $table = 'divisi';
    protected $fillable = ['nama', 'singkatan', 'keterangan'];

    public function pengajuan(): HasMany
    {
        return $this->hasMany(Pengajuan::class, 'id_divisi');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'id_divisi');
    }
}
