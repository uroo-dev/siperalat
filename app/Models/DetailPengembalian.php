<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DetailPengembalian extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'detail_pengembalian';

    /**
     * Primary key tabel.
     *
     * @var string
     */
    protected $primaryKey = 'id_detail_pengembalian';

    /**
     * Kolom yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_pengembalian',
        'id_detail_peminjaman',
        'jumlah_kembali',
        'id_kondisi',
        'keterangan_kondisi',
        'foto_kondisi',
    ];

    /**
     * Type casting atribut model.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'jumlah_kembali' => 'integer',
    ];

    /**
     * Relasi Many-to-One: Header transaksi pengembalian.
     *
     * @return BelongsTo
     */
    public function pengembalian(): BelongsTo
    {
        return $this->belongsTo(Pengembalian::class, 'id_pengembalian', 'id_pengembalian');
    }

    /**
     * Relasi Many-to-One: Baris detail peminjaman yang dikembalikan.
     *
     * @return BelongsTo
     */
    public function detailPeminjaman(): BelongsTo
    {
        return $this->belongsTo(DetailPeminjaman::class, 'id_detail_peminjaman', 'id_detail');
    }

    /**
     * Relasi Many-to-One: Kondisi alat saat dikembalikan.
     *
     * @return BelongsTo
     */
    public function kondisiAlat(): BelongsTo
    {
        return $this->belongsTo(KondisiAlat::class, 'id_kondisi', 'id_kondisi');
    }

    /**
     * Relasi One-to-Many: Denda terkait item pengembalian ini jika ada.
     *
     * @return HasMany
     */
    public function denda(): HasMany
    {
        return $this->hasMany(Denda::class, 'id_detail_pengembalian', 'id_detail_pengembalian');
    }
}
