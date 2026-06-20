<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';
    public $timestamps = false;

    protected $fillable = [
        'nama', 'email', 'password', 'no_hp',
        'lokasi', 'foto_ktm', 'catatan_admin', 'status_akun', 'created_at',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'password'   => 'hashed',
        'created_at' => 'datetime',
    ];

    // ── RELATIONS ─────────────────────────────────────────────
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'id_user', 'id_role');
    }

    public function produk()
    {
        return $this->hasMany(Produk::class, 'id_user', 'id_user');
    }

    public function transaksiSebagaiPembeli()
    {
        return $this->hasMany(Transaksi::class, 'id_pembeli', 'id_user');
    }

    public function transaksiSebagaiPenjual()
    {
        return $this->hasMany(Transaksi::class, 'id_penjual', 'id_user');
    }

    public function requestKategori()
    {
        return $this->hasMany(RequestKategori::class, 'id_user', 'id_user');
    }

    // ── HELPERS ───────────────────────────────────────────────
    public function hasRole(string $role): bool
    {
        return $this->roles->pluck('nama_role')->contains($role);
    }

    public function isAdmin(): bool    { return $this->hasRole('admin'); }
    public function isPenjual(): bool  { return $this->hasRole('penjual'); }
    public function isPembeli(): bool  { return $this->hasRole('pembeli'); }

    public function getPrimaryRole(): string
    {
        $roles = $this->roles->pluck('nama_role');
        if ($roles->contains('admin'))   return 'admin';
        if ($roles->contains('penjual')) return 'penjual';
        return 'pembeli';
    }

    public function fotoKtmUrl(): ?string
    {
        return $this->foto_ktm
            ? asset('storage/ktm/' . $this->foto_ktm)
            : null;
    }

    public function waLink(string $pesan = ''): string
    {
        $no = preg_replace('/[^0-9]/', '', $this->no_hp ?? '');
        if (str_starts_with($no, '0')) $no = '62' . substr($no, 1);
        $url = "https://wa.me/{$no}";
        if ($pesan) $url .= '?text=' . urlencode($pesan);
        return $url;
    }
}
