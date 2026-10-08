<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Alat;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Tampilkan halaman filter dan rekap laporan.
     */
    public function index(Request $request)
    {
        $jenisLaporan = $request->get('jenis', 'peminjaman');
        $tglMulai = $request->get('tgl_mulai', date('Y-m-01'));
        $tglSelesai = $request->get('tgl_selesai', date('Y-m-d'));
        $status = $request->get('status', 'semua');

        $peminjamans = collect();
        $pengembalians = collect();

        if ($jenisLaporan === 'peminjaman' || $jenisLaporan === 'semua') {
            $queryPeminjaman = Peminjaman::with(['peminjam.user', 'detailPeminjaman.alat', 'disetujuiOleh'])
                ->whereDate('tanggal_pinjam', '>=', $tglMulai)
                ->whereDate('tanggal_pinjam', '<=', $tglSelesai);

            if ($status !== 'semua') {
                $queryPeminjaman->where('status', $status);
            }

            $peminjamans = $queryPeminjaman->latest('tanggal_pinjam')->get();
        }

        if ($jenisLaporan === 'pengembalian' || $jenisLaporan === 'semua') {
            $pengembalians = Pengembalian::with([
                'peminjaman.peminjam.user',
                'diprosesOleh',
                'detailPengembalian.detailPeminjaman.alat',
                'detailPengembalian.kondisiAlat'
            ])
                ->whereDate('tanggal_pengembalian', '>=', $tglMulai)
                ->whereDate('tanggal_pengembalian', '<=', $tglSelesai)
                ->latest('tanggal_pengembalian')
                ->get();
        }

        $totalPendapatan = $peminjamans->sum('total_tagihan');
        $totalPeminjaman = $peminjamans->count();
        $totalPengembalian = $pengembalians->count();

        return view('Admin.Laporan.index', compact(
            'jenisLaporan',
            'tglMulai',
            'tglSelesai',
            'status',
            'peminjamans',
            'pengembalians',
            'totalPendapatan',
            'totalPeminjaman',
            'totalPengembalian'
        ));
    }

    /**
     * Tampilkan halaman cetak / print view laporan.
     */
    public function cetak(Request $request)
    {
        $jenisLaporan = $request->get('jenis', 'peminjaman');
        $tglMulai = $request->get('tgl_mulai', date('Y-m-01'));
        $tglSelesai = $request->get('tgl_selesai', date('Y-m-d'));
        $status = $request->get('status', 'semua');

        $peminjamans = collect();
        $pengembalians = collect();

        if ($jenisLaporan === 'peminjaman' || $jenisLaporan === 'semua') {
            $queryPeminjaman = Peminjaman::with(['peminjam.user', 'detailPeminjaman.alat', 'disetujuiOleh'])
                ->whereDate('tanggal_pinjam', '>=', $tglMulai)
                ->whereDate('tanggal_pinjam', '<=', $tglSelesai);

            if ($status !== 'semua') {
                $queryPeminjaman->where('status', $status);
            }

            $peminjamans = $queryPeminjaman->latest('tanggal_pinjam')->get();
        }

        if ($jenisLaporan === 'pengembalian' || $jenisLaporan === 'semua') {
            $pengembalians = Pengembalian::with([
                'peminjaman.peminjam.user',
                'diprosesOleh',
                'detailPengembalian.detailPeminjaman.alat',
                'detailPengembalian.kondisiAlat'
            ])
                ->whereDate('tanggal_pengembalian', '>=', $tglMulai)
                ->whereDate('tanggal_pengembalian', '<=', $tglSelesai)
                ->latest('tanggal_pengembalian')
                ->get();
        }

        $totalPendapatan = $peminjamans->sum('total_tagihan');

        return view('Admin.Laporan.cetak', compact(
            'jenisLaporan',
            'tglMulai',
            'tglSelesai',
            'status',
            'peminjamans',
            'pengembalians',
            'totalPendapatan'
        ));
    }
}
