<?php

namespace App\Http\Controllers;

use App\Models\Pengembalian;
use App\Models\DetailPengembalian;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\KondisiAlat;
use App\Models\JenisDenda;
use App\Models\Denda;
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

        // Pastikan master jenis denda default tersedia
        JenisDenda::firstOrCreate(
            ['nama_denda' => 'Denda Keterlambatan'],
            ['metode_hitung' => 'per_hari', 'tarif' => 10000, 'persentase' => 0, 'deskripsi' => 'Denda per hari keterlambatan dari jadwal pengembalian.', 'status' => 'aktif']
        );
        JenisDenda::firstOrCreate(
            ['nama_denda' => 'Denda Kerusakan / Penggantian'],
            ['metode_hitung' => 'tetap', 'tarif' => 25000, 'persentase' => 0, 'deskripsi' => 'Biaya perbaikan kerusakan alat atau penggantian suku cadang.', 'status' => 'aktif']
        );

        $pengembalians = Pengembalian::with([
            'peminjaman.peminjam.user',
            'diprosesOleh',
            'detailPengembalian.detailPeminjaman.alat',
            'detailPengembalian.kondisiAlat',
            'denda.jenisDenda'
        ])->latest()->paginate(10);

        $peminjamanAktif = Peminjaman::with(['peminjam.user', 'detailPeminjaman.alat'])
            ->whereIn('status', ['dipinjam', 'disetujui', 'sebagian_dikembalikan'])
            ->get();

        $kondisis = KondisiAlat::all();
        $jenisDendas = JenisDenda::where('status', 'aktif')->get();

        return view('Admin.Pengembalian.index', compact('pengembalians', 'peminjamanAktif', 'kondisis', 'jenisDendas'));
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
            'keterangan_kondisi' => 'nullable|array',
            'denda_terlambat' => 'nullable|numeric|min:0',
            'hari_terlambat' => 'nullable|integer|min:0',
            'denda_kerusakan' => 'nullable|numeric|min:0',
            'alasan_denda_kerusakan' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $peminjaman = Peminjaman::with('detailPeminjaman.alat')->findOrFail($request->id_peminjaman);

        $dendaTerlambat = max(0, floatval($request->denda_terlambat ?? 0));
        $dendaKerusakan = max(0, floatval($request->denda_kerusakan ?? 0));
        $hariTerlambat = max(0, intval($request->hari_terlambat ?? 0));
        $totalDendaPengembalian = $dendaTerlambat + $dendaKerusakan;

        $pengembalian = Pengembalian::create([
            'kode_pengembalian' => 'RTN-' . strtoupper(Str::random(6)),
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'diproses_oleh' => Auth::id(),
            'tanggal_pengembalian' => $request->tanggal_pengembalian,
            'total_denda' => $totalDendaPengembalian,
            'status_pengembalian' => 'selesai',
            'catatan' => $request->catatan,
        ]);

        $firstDetailPengembalianId = null;

        foreach ($request->id_detail as $index => $idDetail) {
            $detailPeminjaman = DetailPeminjaman::with('alat')->find($idDetail);
            $qtyKembali = (int) ($request->jumlah_kembali[$index] ?? 0);
            $kondisiId = $request->id_kondisi[$index] ?? 1;

            if ($detailPeminjaman && $qtyKembali > 0) {
                $detailPengembalian = DetailPengembalian::create([
                    'id_pengembalian' => $pengembalian->id_pengembalian,
                    'id_detail_peminjaman' => $detailPeminjaman->id_detail,
                    'jumlah_kembali' => $qtyKembali,
                    'id_kondisi' => $kondisiId,
                    'keterangan_kondisi' => $request->keterangan_kondisi[$index] ?? null,
                ]);

                if (!$firstDetailPengembalianId) {
                    $firstDetailPengembalianId = $detailPengembalian->id_detail_pengembalian;
                }

                // Update jumlah yang sudah dikembalikan di baris peminjaman
                $detailPeminjaman->increment('jumlah_dikembalikan', $qtyKembali);

                // Update stok inventaris berdasarkan kelayakan kondisi alat
                if ($detailPeminjaman->alat) {
                    $kondisi = KondisiAlat::find($kondisiId);

                    if ($kondisi && $kondisi->dapat_dipinjam) {
                        $detailPeminjaman->alat->increment('stok_tersedia', $qtyKembali);
                    } else {
                        // Masuk ke stok rusak
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

        // Catat entri Denda Keterlambatan jika ada
        if ($dendaTerlambat > 0) {
            $jenisDendaTerlambat = JenisDenda::firstOrCreate(
                ['nama_denda' => 'Denda Keterlambatan'],
                ['metode_hitung' => 'per_hari', 'tarif' => 10000, 'persentase' => 0, 'deskripsi' => 'Denda keterlambatan per hari', 'status' => 'aktif']
            );

            Denda::create([
                'id_pengembalian' => $pengembalian->id_pengembalian,
                'id_detail_pengembalian' => $firstDetailPengembalianId,
                'id_jenis_denda' => $jenisDendaTerlambat->id_jenis_denda,
                'jumlah_hari_terlambat' => $hariTerlambat,
                'jumlah_alat' => $peminjaman->detailPeminjaman->sum('jumlah'),
                'tarif' => $hariTerlambat > 0 ? ($dendaTerlambat / $hariTerlambat) : $dendaTerlambat,
                'nominal' => $dendaTerlambat,
                'alasan' => "Keterlambatan pengembalian selama {$hariTerlambat} hari dari batas rencana.",
                'status' => 'lunas',
            ]);
        }

        // Catat entri Denda Kerusakan jika ada
        if ($dendaKerusakan > 0) {
            $jenisDendaKerusakan = JenisDenda::firstOrCreate(
                ['nama_denda' => 'Denda Kerusakan / Penggantian'],
                ['metode_hitung' => 'tetap', 'tarif' => 25000, 'persentase' => 0, 'deskripsi' => 'Biaya perbaikan kerusakan alat', 'status' => 'aktif']
            );

            Denda::create([
                'id_pengembalian' => $pengembalian->id_pengembalian,
                'id_detail_pengembalian' => $firstDetailPengembalianId,
                'id_jenis_denda' => $jenisDendaKerusakan->id_jenis_denda,
                'jumlah_hari_terlambat' => 0,
                'jumlah_alat' => 1,
                'tarif' => $dendaKerusakan,
                'nominal' => $dendaKerusakan,
                'alasan' => $request->alasan_denda_kerusakan ?: 'Denda atas kerusakan fisik atau pembersihan alat.',
                'status' => 'lunas',
            ]);
        }

        // Refresh & update status transaksi peminjaman serta akumulasi tagihan
        $peminjaman->refresh();
        $sisaBelumKembali = $peminjaman->detailPeminjaman->sum('jumlah') - $peminjaman->detailPeminjaman->sum('jumlah_dikembalikan');

        $peminjaman->total_denda = ($peminjaman->total_denda ?? 0) + $totalDendaPengembalian;
        $peminjaman->total_tagihan = $peminjaman->subtotal + $peminjaman->total_denda;

        if ($sisaBelumKembali <= 0) {
            $peminjaman->status = 'dikembalikan';
            $pengembalian->update(['status_pengembalian' => 'selesai']);
        } else {
            $peminjaman->status = 'sebagian_dikembalikan';
            $pengembalian->update(['status_pengembalian' => 'sebagian']);
        }

        $peminjaman->save();

        Log::record('Proses Pengembalian', "Memproses pengembalian alat {$pengembalian->kode_pengembalian} untuk transaksi {$peminjaman->kode_transaksi}. Denda: Rp " . number_format($totalDendaPengembalian, 0, ',', '.'));

        return redirect()->route('admin.pengembalian.struk', $pengembalian->id_pengembalian)
            ->with('success', 'Pengembalian alat berhasil diproses. Struk pengembalian siap dicetak.');
    }

    /**
     * Tampilkan struk / bukti pengembalian alat.
     */
    public function struk($id)
    {
        $pengembalian = Pengembalian::with([
            'peminjaman.peminjam.user',
            'diprosesOleh',
            'detailPengembalian.detailPeminjaman.alat',
            'detailPengembalian.kondisiAlat',
            'denda.jenisDenda'
        ])->findOrFail($id);

        return view('Admin.Pengembalian.struk', compact('pengembalian'));
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
