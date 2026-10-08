<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'kategori';

    /**
     * Primary key tabel.
     *
     * @var string
     */
    protected $primaryKey = 'id_kategori';

    /**
     * Kolom yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'status',
    ];

    /**
     * Relasi One-to-Many: Daftar alat yang tergolong dalam kategori ini.
     *
     * @return HasMany
     */
    public function alat(): HasMany
    {
        return $this->hasMany(Alat::class, 'id_kategori', 'id_kategori');
    }
}
