<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Peminjam extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'peminjam';

    /**
     * Primary key tabel.
     *
     * @var string
     */
    protected $primaryKey = 'id_peminjam';

    /**
     * Kolom yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'nama',
        'no_identitas',
        'no_telepon',
        'alamat',
        'foto',
        'status_peminjam',
    ];

    /**
     * Relasi Many-to-One / Inverse One-to-One: Akun login pengguna terkait.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Relasi One-to-Many: Transaksi peminjaman yang diajukan oleh peminjam ini.
     *
     * @return HasMany
     */
    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'id_peminjam', 'id_peminjam');
    }
}
