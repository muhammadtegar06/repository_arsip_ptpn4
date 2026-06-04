<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    protected $fillable = ['nama', 'username', 'password', 'role', 'id_divisi'];

    protected $hidden = ['password'];

    protected $casts = ['password' => 'hashed'];

    public function pengajuan(): HasMany
    {
        return $this->hasMany(Pengajuan::class, 'id_user');
    }

    public function divisi(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Divisi::class, 'id_divisi');
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'Super Admin';
    }

    public function isAdministrator(): bool
    {
        return $this->role === 'Administrator';
    }

    public function isUserDivisi(): bool
    {
        return $this->role === 'User Divisi';
    }
}
