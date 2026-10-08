<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Kategori;
use App\Models\Peminjam;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\User;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PublicController extends Controller
{
    /**
     * Menampilkan katalog publik dan formulir peminjaman.
     */
    public function index(Request $request)
    {
        $kategoris = Kategori::where('status', 'aktif')->get();
        $alats = Alat::with('kategori')->where('status', 'tersedia')->latest()->get();

        $cekTransaksi = null;
        if ($request->filled('keyword')) {
            $keyword = trim($request->keyword);
            $cekTransaksi = Peminjaman::with(['peminjam.user', 'detailPeminjaman.alat', 'disetujuiOleh'])
                ->where('kode_transaksi', $keyword)
                ->orWhereHas('peminjam', function ($q) use ($keyword) {
                    $q->where('no_identitas', $keyword)
                      ->orWhere('nama', 'like', "%{$keyword}%");
                })
                ->latest()
                ->first();
        }

        return view('Public.Dashboard.index', compact('kategoris', 'alats', 'cekTransaksi'));
    }

    /**
     * Memproses pengajuan peminjaman langsung dari halaman publik.
     */
    public function store_peminjaman(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk mengajukan peminjaman alat.');
        }

        $request->validate([
            'nama' => 'required|string|max:100',
            'no_identitas' => 'required|string|max:50',
            'no_telepon' => 'required|string|max:25',
            'alamat' => 'nullable|string',
            'id_alat' => 'required|exists:alat,id_alat',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_pinjam',
            'keperluan' => 'required|string|max:500',
        ]);

        $alat = Alat::findOrFail($request->id_alat);

        if ($request->jumlah > $alat->stok_tersedia) {
            return back()->with('error', "Stok alat {$alat->nama_alat} tidak mencukupi (Tersedia: {$alat->stok_tersedia} unit).");
        }

        $user = Auth::user();
        $peminjam = Peminjam::firstOrCreate(
            ['user_id' => $user->id],
            [
                'nama' => $request->nama,
                'no_identitas' => $request->no_identitas,
                'no_telepon' => $request->no_telepon,
                'alamat' => $request->alamat ?? '-',
                'status_peminjam' => 'aktif',
            ]
        );

        // Update profil jika ada data baru
        $peminjam->update([
            'nama' => $request->nama,
            'no_identitas' => $request->no_identitas,
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat ?? $peminjam->alamat,
        ]);

        // Hitung durasi dan subtotal
        $tglPinjam = \Carbon\Carbon::parse($request->tanggal_pinjam);
        $tglKembali = \Carbon\Carbon::parse($request->tanggal_kembali_rencana);
        $lamaPinjam = max(1, $tglPinjam->diffInDays($tglKembali));
        $subtotal = $alat->harga_sewa * $request->jumlah * $lamaPinjam;

        $kodeTransaksi = 'TRX-' . strtoupper(Str::random(6));

        $peminjaman = Peminjaman::create([
            'kode_transaksi' => $kodeTransaksi,
            'id_peminjam' => $peminjam->id_peminjam,
            'tanggal_pengajuan' => now(),
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
            'lama_pinjam' => $lamaPinjam,
            'keperluan' => $request->keperluan,
            'subtotal' => $subtotal,
            'total_denda' => 0,
            'total_tagihan' => $subtotal,
            'status' => 'menunggu',
        ]);

        DetailPeminjaman::create([
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'id_alat' => $alat->id_alat,
            'jumlah' => $request->jumlah,
            'harga_per_hari' => $alat->harga_sewa,
            'lama_pinjam' => $lamaPinjam,
            'subtotal' => $subtotal,
        ]);

        Log::record(
            'Pengajuan Publik',
            "Pengajuan peminjaman baru oleh {$peminjam->nama} (Kode: {$kodeTransaksi}) untuk alat {$alat->nama_alat}",
            $peminjam->nama
        );

        return redirect()->route('public.home', ['keyword' => $kodeTransaksi . '#cek-status'])
            ->with('succes_trx', "Pengajuan peminjaman berhasil dikirim! Simpan Kode Tiket Anda: {$kodeTransaksi}");
    }
}
