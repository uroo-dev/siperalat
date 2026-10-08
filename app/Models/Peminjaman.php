<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Peminjaman extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'peminjaman';

    /**
     * Primary key tabel.
     *
     * @var string
     */
    protected $primaryKey = 'id_peminjaman';

    /**
     * Kolom yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_transaksi',
        'id_peminjam',
        'tanggal_pengajuan',
        'tanggal_pinjam',
        'tanggal_kembali_rencana',
        'lama_pinjam',
        'keperluan',
        'subtotal',
        'total_denda',
        'total_tagihan',
        'status',
        'alasan_penolakan',
        'disetujui_oleh',
        'disetujui_at',
    ];

    /**
     * Type casting atribut model.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_pengajuan' => 'datetime',
        'tanggal_pinjam' => 'date',
        'tanggal_kembali_rencana' => 'date',
        'lama_pinjam' => 'integer',
        'subtotal' => 'decimal:2',
        'total_denda' => 'decimal:2',
        'total_tagihan' => 'decimal:2',
        'disetujui_at' => 'datetime',
    ];

    /**
     * Relasi Many-to-One: Profil peminjam yang mengajukan transaksi.
     *
     * @return BelongsTo
     */
    public function peminjam(): BelongsTo
    {
        return $this->belongsTo(Peminjam::class, 'id_peminjam', 'id_peminjam');
    }

    /**
     * Relasi Many-to-One: Petugas/Admin yang menyetujui peminjaman ini.
     *
     * @return BelongsTo
     */
    public function disetujuiOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disetujui_oleh', 'id');
    }

    /**
     * Relasi One-to-Many: Detail item alat yang dipinjam dalam transaksi ini.
     *
     * @return HasMany
     */
    public function detailPeminjaman(): HasMany
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }

    /**
     * Relasi Many-to-Many: Daftar alat yang dipinjam melalui tabel pivot detail_peminjaman.
     *
     * @return BelongsToMany
     */
    public function alat(): BelongsToMany
    {
        return $this->belongsToMany(
            Alat::class,
            'detail_peminjaman',
            'id_peminjaman',
            'id_alat'
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

    /**
     * Relasi One-to-Many: Catatan pengembalian untuk transaksi ini (bisa bertahap/sebagian).
     *
     * @return HasMany
     */
    public function pengembalian(): HasMany
    {
        return $this->hasMany(Pengembalian::class, 'id_peminjaman', 'id_peminjaman');
    }
}
