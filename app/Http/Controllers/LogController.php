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
        $logs = Log::latest()->paginate(20)->withQueryString();
        $listAksi = Log::select('aksi')->distinct()->pluck('aksi');

        return view('Admin.Log.index', compact('logs', 'listAksi'));
    }
}
