<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Denda extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'denda';

    /**
     * Primary key tabel.
     *
     * @var string
     */
    protected $primaryKey = 'id_denda';

    /**
     * Kolom yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_pengembalian',
        'id_detail_pengembalian',
        'id_jenis_denda',
        'jumlah_hari_terlambat',
        'jumlah_alat',
        'tarif',
        'nominal',
        'alasan',
        'status',
        'dibebaskan_oleh',
        'alasan_pembebasan',
    ];

    /**
     * Type casting atribut model.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'jumlah_hari_terlambat' => 'integer',
        'jumlah_alat' => 'integer',
        'tarif' => 'decimal:2',
        'nominal' => 'decimal:2',
    ];

    /**
     * Relasi Many-to-One: Transaksi pengembalian yang menghasilkan denda ini.
     *
     * @return BelongsTo
     */
    public function pengembalian(): BelongsTo
    {
        return $this->belongsTo(Pengembalian::class, 'id_pengembalian', 'id_pengembalian');
    }

    /**
     * Relasi Many-to-One: Item detail pengembalian yang terkena denda (jika terkait alat spesifik).
     *
     * @return BelongsTo
     */
    public function detailPengembalian(): BelongsTo
    {
        return $this->belongsTo(DetailPengembalian::class, 'id_detail_pengembalian', 'id_detail_pengembalian');
    }

    /**
     * Relasi Many-to-One: Jenis aturan denda yang diterapkan.
     *
     * @return BelongsTo
     */
    public function jenisDenda(): BelongsTo
    {
        return $this->belongsTo(JenisDenda::class, 'id_jenis_denda', 'id_jenis_denda');
    }

    /**
     * Relasi Many-to-One: Admin/Petugas yang membebaskan denda ini jika status 'dibebaskan'.
     *
     * @return BelongsTo
     */
    public function dibebaskanOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibebaskan_oleh', 'id');
    }
}
