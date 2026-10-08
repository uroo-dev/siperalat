<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Log extends Model
{
    use HasFactory;

    protected $table = 'logs';

    protected $fillable = [
        'user',
        'aksi',
        'deskripsi',
    ];

    /**
     * Helper untuk mencatat log aktivitas pengguna.
     */
    public static function record($aksi, $deskripsi, $username = null)
    {
        $user = $username ?? (Auth::check() ? Auth::user()->username : 'Sistem/Guest');

        return self::create([
            'user' => $user,
            'aksi' => $aksi,
            'deskripsi' => $deskripsi,
        ]);
    }
}
