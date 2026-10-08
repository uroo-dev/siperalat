<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Alat extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'alat';

    /**
     * Primary key tabel.
     *
     * @var string
     */
    protected $primaryKey = 'id_alat';

    /**
     * Kolom yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_kategori',
        'kode_alat',
        'nama_alat',
        'deskripsi',
        'harga_sewa',
        'stok_total',
        'stok_tersedia',
        'stok_dipinjam',
        'stok_rusak',
        'nilai_penggantian',
        'foto',
        'status',
    ];

    /**
     * Type casting atribut model.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'harga_sewa' => 'decimal:2',
        'nilai_penggantian' => 'decimal:2',
        'stok_total' => 'integer',
        'stok_tersedia' => 'integer',
        'stok_dipinjam' => 'integer',
        'stok_rusak' => 'integer',
    ];

    /**
     * Relasi Many-to-One: Kategori dari alat ini.
     *
     * @return BelongsTo
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    /**
     * Relasi One-to-Many: Detail transaksi peminjaman untuk alat ini.
     *
     * @return HasMany
     */
    public function detailPeminjaman(): HasMany
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_alat', 'id_alat');
    }

    /**
     * Relasi Many-to-Many: Transaksi peminjaman yang meminjam alat ini.
     *
     * @return BelongsToMany
     */
    public function peminjaman(): BelongsToMany
    {
        return $this->belongsToMany(
            Peminjaman::class,
            'detail_peminjaman',
            'id_alat',
            'id_peminjaman'
        )->withPivot([
            'id_detail',
            'jumlah',
            'harga_per_hari',
            'lama_pinjam',
            'subtotal',
            'jumlah_dikembalikan',
            'jumlah_rusak',
            'jumlah_hilang',
            'catatan'
        ])->withTimestamps();
    }
}
