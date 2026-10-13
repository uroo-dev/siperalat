<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use App\Models\Log;
use Illuminate\Http\Request;

class AdminDashboardContrller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalAlat = Alat::count();
        $stokTersedia = Alat::sum('stok_tersedia');
        $totalDipinjam = Peminjaman::whereIn('status', ['dipinjam', 'sebagian_dikembalikan'])->count();
        $menungguApproval = Peminjaman::where('status', 'menunggu')->count();
        $totalPengembalian = Pengembalian::count();
        $overdueCount = Peminjaman::whereIn('status', ['dipinjam', 'disetujui'])->where('tanggal_kembali_rencana', '<', now()->toDateString())->count();

        $kategoris = \App\Models\Kategori::where('status', 'aktif')->get();
        $recentLogs = Log::latest()->take(8)->get();
        $recentPeminjamans = Peminjaman::with(['peminjam.user', 'detailPeminjaman.alat'])->latest()->take(5)->get();
        $recentAlats = Alat::with('kategori')->latest()->take(6)->get();

        return view('Admin.Dashboard.index', compact(
            'totalAlat',
            'stokTersedia',
            'totalDipinjam',
            'menungguApproval',
            'totalPengembalian',
            'overdueCount',
            'kategoris',
            'recentLogs',
            'recentPeminjamans',
            'recentAlats'
        ));
    }
}
