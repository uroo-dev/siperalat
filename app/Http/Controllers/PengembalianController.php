<?php

namespace App\Http\Controllers;

use App\Models\Pengembalian;
use App\Models\DetailPengembalian;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\KondisiAlat;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PengembalianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Pastikan kondisi alat default tersedia
        KondisiAlat::firstOrCreate(['nama_kondisi' => 'Baik'], ['deskripsi' => 'Alat berfungsi normal dan dalam kondisi baik', 'dapat_dipinjam' => 1]);
        KondisiAlat::firstOrCreate(['nama_kondisi' => 'Rusak Ringan'], ['deskripsi' => 'Alat mengalami kerusakan minor', 'dapat_dipinjam' => 0]);
        KondisiAlat::firstOrCreate(['nama_kondisi' => 'Rusak Berat'], ['deskripsi' => 'Alat rusak parah dan tidak dapat digunakan', 'dapat_dipinjam' => 0]);

        $pengembalians = Pengembalian::with([
            'peminjaman.peminjam.user',
            'diprosesOleh',
            'detailPengembalian.detailPeminjaman.alat',
            'detailPengembalian.kondisiAlat'
        ])->latest()->paginate(10);

        $peminjamanAktif = Peminjaman::with(['peminjam.user', 'detailPeminjaman.alat'])
            ->whereIn('status', ['dipinjam', 'disetujui', 'sebagian_dikembalikan'])
            ->get();

        $kondisis = KondisiAlat::all();

        return view('Admin.Pengembalian.index', compact('pengembalians', 'peminjamanAktif', 'kondisis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_peminjaman' => 'required|exists:peminjaman,id_peminjaman',
            'tanggal_pengembalian' => 'required|date',
            'id_detail' => 'required|array',
            'jumlah_kembali' => 'required|array',
            'id_kondisi' => 'required|array',
        ]);

        $pengembalian = Pengembalian::create([
            'kode_pengembalian' => 'RTN-' . strtoupper(Str::random(6)),
            'id_peminjaman' => $request->id_peminjaman,
            'diproses_oleh' => Auth::id(),
            'tanggal_pengembalian' => $request->tanggal_pengembalian,
            'total_denda' => 0, // Tanpa denda dulu sesuai instruksi
            'status_pengembalian' => 'selesai',
            'catatan' => $request->catatan,
        ]);

        foreach ($request->id_detail as $index => $idDetail) {
            $detailPeminjaman = DetailPeminjaman::with('alat')->find($idDetail);
            $qtyKembali = (int) ($request->jumlah_kembali[$index] ?? 0);
            $kondisiId = $request->id_kondisi[$index] ?? 1;

            if ($detailPeminjaman && $qtyKembali > 0) {
                DetailPengembalian::create([
                    'id_pengembalian' => $pengembalian->id_pengembalian,
                    'id_detail_peminjaman' => $detailPeminjaman->id_detail,
                    'jumlah_kembali' => $qtyKembali,
                    'id_kondisi' => $kondisiId,
                    'keterangan_kondisi' => $request->keterangan_kondisi[$index] ?? null,
                ]);

                // Update jumlah yang sudah dikembalikan di baris peminjaman
                $detailPeminjaman->increment('jumlah_dikembalikan', $qtyKembali);

                // Update stok inventaris
                if ($detailPeminjaman->alat) {
                    $kondisi = KondisiAlat::find($kondisiId);

                    if ($kondisi && $kondisi->dapat_dipinjam) {
                        $detailPeminjaman->alat->increment('stok_tersedia', $qtyKembali);
                    } else {
                        $detailPeminjaman->alat->increment('stok_rusak', $qtyKembali);
                        $detailPeminjaman->increment('jumlah_rusak', $qtyKembali);
                    }

                    $detailPeminjaman->alat->decrement('stok_dipinjam', $qtyKembali);

                    if ($detailPeminjaman->alat->status === 'habis' && $detailPeminjaman->alat->stok_tersedia > 0) {
                        $detailPeminjaman->alat->update(['status' => 'tersedia']);
                    }
                }
            }
        }

        // Cek status keseluruhan peminjaman
        $peminjaman = Peminjaman::with('detailPeminjaman')->find($request->id_peminjaman);
        $sisaBelumKembali = $peminjaman->detailPeminjaman->sum('jumlah') - $peminjaman->detailPeminjaman->sum('jumlah_dikembalikan');

        if ($sisaBelumKembali <= 0) {
            $peminjaman->update(['status' => 'dikembalikan']);
            $pengembalian->update(['status_pengembalian' => 'selesai']);
        } else {
            $peminjaman->update(['status' => 'sebagian_dikembalikan']);
            $pengembalian->update(['status_pengembalian' => 'sebagian']);
        }

        Log::record('Proses Pengembalian', "Memproses pengembalian alat {$pengembalian->kode_pengembalian} untuk transaksi {$peminjaman->kode_transaksi}");

        return redirect()->back()->with('success', 'Data Pengembalian Berhasil Diproses');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pengembalian = Pengembalian::findOrFail($id);
        $kodePengembalian = $pengembalian->kode_pengembalian;
        $pengembalian->delete();

        Log::record('Hapus Pengembalian', "Menghapus data riwayat pengembalian: {$kodePengembalian}");

        return redirect()->back()->with('success', 'Data Pengembalian Berhasil Dihapus');
    }
}
