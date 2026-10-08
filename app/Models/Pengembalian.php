<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengembalian extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'pengembalian';

    /**
     * Primary key tabel.
     *
     * @var string
     */
    protected $primaryKey = 'id_pengembalian';

    /**
     * Kolom yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_pengembalian',
        'id_peminjaman',
        'diproses_oleh',
        'tanggal_pengembalian',
        'total_denda',
        'status_pengembalian',
        'catatan',
    ];

    /**
     * Type casting atribut model.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_pengembalian' => 'date',
        'total_denda' => 'decimal:2',
    ];

    /**
     * Relasi Many-to-One: Transaksi peminjaman terkait.
     *
     * @return BelongsTo
     */
    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }

    /**
     * Relasi Many-to-One: Petugas yang memproses pengembalian.
     *
     * @return BelongsTo
     */
    public function diprosesOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diproses_oleh', 'id');
    }

    /**
     * Relasi One-to-Many: Detail item alat yang dikembalikan dalam transaksi ini.
     *
     * @return HasMany
     */
    public function detailPengembalian(): HasMany
    {
        return $this->hasMany(DetailPengembalian::class, 'id_pengembalian', 'id_pengembalian');
    }

    /**
     * Relasi One-to-Many: Denda yang ditimbulkan dari pengembalian ini.
     *
     * @return HasMany
     */
    public function denda(): HasMany
    {
        return $this->hasMany(Denda::class, 'id_pengembalian', 'id_pengembalian');
    }
}
