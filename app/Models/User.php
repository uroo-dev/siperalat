<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Nama tabel database.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Primary key tabel.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Kolom yang dapat diisi secara massal (mass assignable).
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'status',
        'last_login_at',
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Type casting atribut model.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Relasi One-to-One: Profil peminjam jika user memiliki role 'peminjam'.
     *
     * @return HasOne
     */
    public function peminjam(): HasOne
    {
        return $this->hasOne(Peminjam::class, 'user_id', 'id');
    }

    /**
     * Relasi One-to-Many: Peminjaman yang disetujui oleh admin/petugas ini.
     *
     * @return HasMany
     */
    public function peminjamanDisetujui(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'disetujui_oleh', 'id');
    }

    /**
     * Relasi One-to-Many: Transaksi pengembalian yang diproses oleh petugas ini.
     *
     * @return HasMany
     */
    public function pengembalianDiproses(): HasMany
    {
        return $this->hasMany(Pengembalian::class, 'diproses_oleh', 'id');
    }

    /**
     * Relasi One-to-Many: Denda yang dibebaskan oleh admin/petugas ini.
     *
     * @return HasMany
     */
    public function dendaDibebaskan(): HasMany
    {
        return $this->hasMany(Denda::class, 'dibebaskan_oleh', 'id');
    }
}
