<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KondisiAlat extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'kondisi_alat';

    /**
     * Primary key tabel.
     *
     * @var string
     */
    protected $primaryKey = 'id_kondisi';

    /**
     * Kolom yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_kondisi',
        'deskripsi',
        'dapat_dipinjam',
    ];

    /**
     * Type casting atribut model.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'dapat_dipinjam' => 'boolean',
    ];

    /**
     * Relasi One-to-Many: Detail pengembalian yang mencatat kondisi ini.
     *
     * @return HasMany
     */
    public function detailPengembalian(): HasMany
    {
        return $this->hasMany(DetailPengembalian::class, 'id_kondisi', 'id_kondisi');
    }
}
