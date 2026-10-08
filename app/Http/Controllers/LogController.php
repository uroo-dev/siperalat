<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Menampilkan daftar semua log aktivitas sistem.
     */
    public function index(Request $request)
    {
        $query = Log::query();

        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('user', 'like', "%{$keyword}%")
                  ->orWhere('aksi', 'like', "%{$keyword}%")
                  ->orWhere('deskripsi', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('aksi')) {
            $query->where('aksi', $request->aksi);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        $logs = $query->latest()->paginate(20)->withQueryString();
        $listAksi = Log::select('aksi')->distinct()->pluck('aksi');

        return view('Admin.Log.index', compact('logs', 'listAksi'));
    }
}
