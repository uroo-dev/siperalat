<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Pengembalian;
use App\Models\DetailPengembalian;
use App\Models\KondisiAlat;
use App\Models\JenisDenda;
use App\Models\Peminjam;
use App\Models\Alat;
use App\Models\User;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Pastikan setiap user peminjam memiliki profil peminjam
        $usersPeminjam = User::where('role', 'peminjam')->get();
        foreach ($usersPeminjam as $u) {
            Peminjam::firstOrCreate(
                ['user_id' => $u->id],
                [
                    'nama' => $u->username,
                    'no_identitas' => 'ID-' . str_pad($u->id, 4, '0', STR_PAD_LEFT),
                    'no_telepon' => '-',
                    'alamat' => '-',
                    'status_peminjam' => 'aktif',
                ]
            );
        }

        $peminjamans = Peminjaman::with(['peminjam.user', 'detailPeminjaman.alat', 'disetujuiOleh', 'pengembalian'])->latest()->paginate(10);
        $peminjams = Peminjam::with('user')->where('status_peminjam', 'aktif')->get();
        $alats = Alat::where('status', 'tersedia')->where('stok_tersedia', '>', 0)->get();
        $kondisis = KondisiAlat::all();
        $jenisDendas = JenisDenda::where('status', 'aktif')->get();

        return view('Admin.Peminjaman.index', compact('peminjamans', 'peminjams', 'alats', 'kondisis', 'jenisDendas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_peminjam' => 'required',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_pinjam',
            'keperluan' => 'required',
            'id_alat' => 'required|array',
            'id_alat.*' => 'required|exists:alat,id_alat',
            'jumlah' => 'required|array',
            'jumlah.*' => 'required|integer|min:1',
        ]);

        $tglPinjam = \Carbon\Carbon::parse($request->tanggal_pinjam);
        $tglKembali = \Carbon\Carbon::parse($request->tanggal_kembali_rencana);
        $lamaPinjam = max(1, $tglPinjam->diffInDays($tglKembali));

        $status = $request->status ?? 'menunggu';
        $disetujuiOleh = in_array($status, ['disetujui', 'dipinjam']) ? Auth::id() : null;
        $disetujuiAt = in_array($status, ['disetujui', 'dipinjam']) ? now() : null;

        $peminjaman = Peminjaman::create([
            'kode_transaksi' => 'TRX-' . strtoupper(Str::random(6)),
            'id_peminjam' => $request->id_peminjam,
            'tanggal_pengajuan' => now(),
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
            'lama_pinjam' => $lamaPinjam,
            'keperluan' => $request->keperluan,
            'subtotal' => 0,
            'total_denda' => 0,
            'total_tagihan' => 0,
            'status' => $status,
            'disetujui_oleh' => $disetujuiOleh,
            'disetujui_at' => $disetujuiAt,
        ]);

        $totalSubtotal = 0;
        foreach ($request->id_alat as $index => $idAlat) {
            $alat = Alat::find($idAlat);
            $qty = (int) ($request->jumlah[$index] ?? 1);

            if ($alat) {
                $subtotal = $alat->harga_sewa * $qty * $lamaPinjam;
                $totalSubtotal += $subtotal;

                DetailPeminjaman::create([
                    'id_peminjaman' => $peminjaman->id_peminjaman,
                    'id_alat' => $alat->id_alat,
                    'jumlah' => $qty,
                    'harga_per_hari' => $alat->harga_sewa,
                    'lama_pinjam' => $lamaPinjam,
                    'subtotal' => $subtotal,
                ]);

                // Update stok alat jika langsung disetujui / dipinjam
                if (in_array($status, ['disetujui', 'dipinjam'])) {
                    $alat->decrement('stok_tersedia', $qty);
                    $alat->increment('stok_dipinjam', $qty);
                    if ($alat->stok_tersedia <= 0) {
                        $alat->update(['status' => 'habis']);
                    }
                }
            }
        }

        $peminjaman->update([
            'subtotal' => $totalSubtotal,
            'total_tagihan' => $totalSubtotal,
        ]);

        Log::record('Tambah Peminjaman', "Membuat transaksi peminjaman baru: {$peminjaman->kode_transaksi}");

        return redirect()->back()->with('success', 'Data Peminjaman Berhasil Ditambahkan');
    }

    /**
     * Update status peminjaman (approve, reject, pinjamkan, batalkan).
     */
    public function update_status(Request $request, $id)
    {
        $peminjaman = Peminjaman::with('detailPeminjaman.alat')->findOrFail($id);
        $statusLama = $peminjaman->status;
        $statusBaru = $request->status;

        $peminjaman->status = $statusBaru;

        if (in_array($statusBaru, ['disetujui', 'dipinjam'])) {
            $peminjaman->disetujui_oleh = Auth::id();
            $peminjaman->disetujui_at = now();

            // Potong stok alat jika sebelumnya berstatus menunggu/ditolak/dibatalkan
            if (!in_array($statusLama, ['disetujui', 'dipinjam', 'sebagian_dikembalikan', 'dikembalikan'])) {
                foreach ($peminjaman->detailPeminjaman as $detail) {
                    if ($detail->alat) {
                        $detail->alat->decrement('stok_tersedia', $detail->jumlah);
                        $detail->alat->increment('stok_dipinjam', $detail->jumlah);
                        if ($detail->alat->stok_tersedia <= 0) {
                            $detail->alat->update(['status' => 'habis']);
                        }
                    }
                }
            }
        } elseif (in_array($statusBaru, ['ditolak', 'dibatalkan'])) {
            if ($request->filled('alasan_penolakan')) {
                $peminjaman->alasan_penolakan = $request->alasan_penolakan;
            }

            // Kembalikan stok jika sebelumnya sempat dipotong
            if (in_array($statusLama, ['disetujui', 'dipinjam'])) {
                foreach ($peminjaman->detailPeminjaman as $detail) {
                    if ($detail->alat) {
                        $detail->alat->increment('stok_tersedia', $detail->jumlah);
                        $detail->alat->decrement('stok_dipinjam', $detail->jumlah);
                        if ($detail->alat->status === 'habis' && $detail->alat->stok_tersedia > 0) {
                            $detail->alat->update(['status' => 'tersedia']);
                        }
                    }
                }
            }
        } elseif ($statusBaru === 'dikembalikan') {
            // Pulihkan sisa stok alat yang masih dipinjam & catat ke riwayat pengembalian
            if (in_array($statusLama, ['dipinjam', 'sebagian_dikembalikan', 'disetujui'])) {
                $kondisiBaik = KondisiAlat::firstOrCreate(
                    ['nama_kondisi' => 'Baik'],
                    ['deskripsi' => 'Alat berfungsi normal dan dalam kondisi baik', 'dapat_dipinjam' => 1]
                );

                $pengembalian = Pengembalian::create([
                    'kode_pengembalian' => 'RTN-' . strtoupper(Str::random(6)),
                    'id_peminjaman' => $peminjaman->id_peminjaman,
                    'diproses_oleh' => Auth::id(),
                    'tanggal_pengembalian' => now()->toDateString(),
                    'total_denda' => 0,
                    'status_pengembalian' => 'selesai',
                    'catatan' => 'Pengembalian selesai dicatat melalui perubahan status.',
                ]);

                foreach ($peminjaman->detailPeminjaman as $detail) {
                    $sisa = $detail->jumlah - $detail->jumlah_dikembalikan;
                    if ($sisa > 0) {
                        DetailPengembalian::create([
                            'id_pengembalian' => $pengembalian->id_pengembalian,
                            'id_detail_peminjaman' => $detail->id_detail,
                            'jumlah_kembali' => $sisa,
                            'id_kondisi' => $kondisiBaik->id_kondisi,
                            'keterangan_kondisi' => 'Kondisi Baik',
                        ]);

                        $detail->increment('jumlah_dikembalikan', $sisa);

                        if ($detail->alat) {
                            $detail->alat->increment('stok_tersedia', $sisa);
                            $detail->alat->decrement('stok_dipinjam', $sisa);
                            if ($detail->alat->status === 'habis' && $detail->alat->stok_tersedia > 0) {
                                $detail->alat->update(['status' => 'tersedia']);
                            }
                        }
                    }
                }
            }
        }

        $peminjaman->save();

        Log::record('Update Status Peminjaman', "Mengubah status transaksi {$peminjaman->kode_transaksi} dari {$statusLama} menjadi {$statusBaru}");

        $pesan = 'Status Peminjaman Berhasil Diperbarui';
        if ($statusBaru === 'disetujui') {
            $pesan = "Transaksi {$peminjaman->kode_transaksi} berhasil disetujui (Siap diambil).";
        } elseif ($statusBaru === 'dipinjam') {
            $pesan = "Alat untuk transaksi {$peminjaman->kode_transaksi} berhasil diserahkan ke peminjam (Status: Sedang Dipinjam).";
        } elseif ($statusBaru === 'ditolak') {
            $pesan = "Pengajuan peminjaman {$peminjaman->kode_transaksi} telah ditolak.";
        }

        return redirect()->back()->with('success', $pesan);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $peminjaman = Peminjaman::with('detailPeminjaman.alat')->findOrFail($id);
        $kodeTransaksi = $peminjaman->kode_transaksi;

        if (in_array($peminjaman->status, ['disetujui', 'dipinjam'])) {
            foreach ($peminjaman->detailPeminjaman as $detail) {
                if ($detail->alat) {
                    $detail->alat->increment('stok_tersedia', $detail->jumlah);
                    $detail->alat->decrement('stok_dipinjam', $detail->jumlah);
                    if ($detail->alat->status === 'habis' && $detail->alat->stok_tersedia > 0) {
                        $detail->alat->update(['status' => 'tersedia']);
                    }
                }
            }
        }

        $peminjaman->delete();

        Log::record('Hapus Peminjaman', "Menghapus transaksi peminjaman: {$kodeTransaksi}");

        return redirect()->back()->with('success', 'Data Peminjaman Berhasil Dihapus');
    }

    /**
     * Tampilkan cetak nota / invoice peminjaman.
     */
    public function nota($id)
    {
        $peminjaman = Peminjaman::with(['peminjam.user', 'detailPeminjaman.alat', 'disetujuiOleh'])->findOrFail($id);
        return view('Admin.Peminjaman.nota', compact('peminjaman'));
    }

    /**
     * Tampilkan struk pengembalian untuk transaksi peminjaman ini.
     */
    public function struk_pengembalian($id)
    {
        $peminjaman = Peminjaman::with('pengembalian')->findOrFail($id);
        $lastPengembalian = $peminjaman->pengembalian()->latest()->first();

        if (!$lastPengembalian) {
            return redirect()->back()->with('error', 'Belum ada data riwayat pengembalian untuk transaksi ini.');
        }

        return redirect()->route('admin.pengembalian.struk', $lastPengembalian->id_pengembalian);
    }
}

