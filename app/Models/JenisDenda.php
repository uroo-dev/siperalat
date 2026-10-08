<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisDenda extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'jenis_denda';

    /**
     * Primary key tabel.
     *
     * @var string
     */
    protected $primaryKey = 'id_jenis_denda';

    /**
     * Kolom yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_denda',
        'metode_hitung',
        'tarif',
        'persentase',
        'deskripsi',
        'status',
    ];

    /**
     * Type casting atribut model.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tarif' => 'decimal:2',
        'persentase' => 'decimal:2',
    ];

    /**
     * Relasi One-to-Many: Catatan denda transaksi yang menggunakan jenis denda ini.
     *
     * @return HasMany
     */
    public function denda(): HasMany
    {
        return $this->hasMany(Denda::class, 'id_jenis_denda', 'id_jenis_denda');
    }
}
