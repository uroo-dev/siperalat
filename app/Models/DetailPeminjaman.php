<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DetailPeminjaman extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'detail_peminjaman';

    /**
     * Primary key tabel.
     *
     * @var string
     */
    protected $primaryKey = 'id_detail';

    /**
     * Kolom yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_peminjaman',
        'id_alat',
        'jumlah',
        'harga_per_hari',
        'lama_pinjam',
        'subtotal',
        'jumlah_dikembalikan',
        'jumlah_rusak',
        'jumlah_hilang',
        'catatan',
    ];

    /**
     * Type casting atribut model.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'jumlah' => 'integer',
        'harga_per_hari' => 'decimal:2',
        'lama_pinjam' => 'integer',
        'subtotal' => 'decimal:2',
        'jumlah_dikembalikan' => 'integer',
        'jumlah_rusak' => 'integer',
        'jumlah_hilang' => 'integer',
    ];

    /**
     * Relasi Many-to-One: Header transaksi peminjaman.
     *
     * @return BelongsTo
     */
    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }

    /**
     * Relasi Many-to-One: Master alat yang dipinjam.
     *
     * @return BelongsTo
     */
    public function alat(): BelongsTo
    {
        return $this->belongsTo(Alat::class, 'id_alat', 'id_alat');
    }

    /**
     * Relasi One-to-Many: Detail pengembalian alat ini.
     *
     * @return HasMany
     */
    public function detailPengembalian(): HasMany
    {
        return $this->hasMany(DetailPengembalian::class, 'id_detail_peminjaman', 'id_detail');
    }
}
