<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pengajuan extends Model
{
    protected $table = 'pengajuan';

    protected $fillable = [
        'no_pengajuan', 'id_divisi', 'id_user', 'tanggal_pengajuan',
        'jumlah_box', 'status', 'catatan', 'alasan_tolak'
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
    ];

    public function divisi(): BelongsTo
    {
        return $this->belongsTo(Divisi::class, 'id_divisi');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function box(): HasMany
    {
        return $this->hasMany(Box::class, 'id_pengajuan');
    }

    public function pengiriman(): HasOne
    {
        return $this->hasOne(Pengiriman::class, 'id_pengajuan');
    }

    // Generate nomor pengajuan otomatis
    public static function generateNoPengajuan(): string
    {
        $tahun = date('Y');
        $bulan = date('m');
        $last = self::whereYear('created_at', $tahun)->whereMonth('created_at', $bulan)->count() + 1;
        return 'PA-' . $tahun . $bulan . '-' . str_pad($last, 4, '0', STR_PAD_LEFT);
    }

    public function getBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'Menunggu Persetujuan' => 'badge-warning',
            'Disetujui'           => 'badge-info',
            'Ditolak'             => 'badge-danger',
            'Selesai Diinput'     => 'badge-secondary',
            'Akan Di Kirim'       => 'badge-primary',
            'Terkirim'            => 'badge-success',
            'Cancel'              => 'badge-dark',
            default               => 'badge-secondary',
        };
    }
}
