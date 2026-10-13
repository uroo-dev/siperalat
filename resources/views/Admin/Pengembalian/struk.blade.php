<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pengembalian Alat - {{ $pengembalian->kode_pengembalian }}</title>
    <script src="{{ asset('template/assets/js/tailwind.js') }}"></script>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                color: black !important;
                padding: 0 !important;
            }
            .print-card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
                max-width: 100% !important;
                width: 100% !important;
            }
        }
    </style>
</head>
<body class="bg-zinc-100 text-zinc-800 antialiased py-8 px-4 font-sans">

    <!-- Action Bar (Hidden on print) -->
    <div class="max-w-3xl mx-auto mb-4 flex items-center justify-between no-print">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.peminjaman.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-zinc-200 text-sm font-medium hover:bg-zinc-50 shadow-sm text-zinc-700">
                &larr; Data Peminjaman
            </a>
            <a href="{{ route('admin.pengembalian.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-zinc-200 text-sm font-medium hover:bg-zinc-50 shadow-sm text-zinc-700">
                Riwayat Pengembalian
            </a>
        </div>
        <button onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-zinc-900 text-white text-sm font-medium hover:bg-zinc-800 shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Cetak Struk Pengembalian (PDF / Print)
        </button>
    </div>

    <!-- Printable Receipt Card -->
    <div class="max-w-3xl mx-auto bg-white rounded-2xl border border-zinc-200 shadow-lg p-8 print-card">

        <!-- Header -->
        <div class="flex justify-between items-start border-b border-zinc-200 pb-6 mb-6">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-8 h-8 rounded-lg bg-zinc-900 text-white flex items-center justify-center font-black text-sm">
                        S
                    </div>
                    <span class="text-xl font-black tracking-tight text-zinc-900">SIPERALAT</span>
                </div>
                <p class="text-xs text-zinc-500">Sistem Informasi Peminjaman Peralatan Laboratorium & Outdoor</p>
                <p class="text-xs text-zinc-400">Jl. Teknologi No. 128, Kampus Terpadu • Telp: (021) 789-0123</p>
            </div>
            <div class="text-right">
                <span class="text-xs uppercase font-bold tracking-widest text-emerald-600 block">BUKTI / STRUK PENGEMBALIAN ALAT</span>
                <span class="text-xl font-mono font-bold text-zinc-900 block mt-1">{{ $pengembalian->kode_pengembalian }}</span>
                <span class="inline-block mt-2 px-3 py-0.5 rounded-full text-xs font-semibold uppercase {{ $pengembalian->status_pengembalian === 'selesai' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-amber-100 text-amber-800 border border-amber-300' }}">
                    Status: {{ $pengembalian->status_pengembalian === 'selesai' ? 'Pengembalian Selesai Penuh' : 'Pengembalian Sebagian' }}
                </span>
            </div>
        </div>

        <!-- Info Grid -->
        <div class="grid grid-cols-2 gap-6 mb-6 text-xs">
            <div>
                <h4 class="font-bold text-zinc-400 uppercase tracking-wider mb-2">Peminjam / Pelanggan</h4>
                <div class="space-y-1 text-zinc-800">
                    <p class="font-bold text-sm text-zinc-900">
                        {{ $pengembalian->peminjaman && $pengembalian->peminjaman->peminjam ? ($pengembalian->peminjaman->peminjam->user->username ?? $pengembalian->peminjaman->peminjam->nama) : 'Anonim' }}
                    </p>
                    <p><span class="text-zinc-500">No. Identitas:</span> {{ $pengembalian->peminjaman && $pengembalian->peminjaman->peminjam ? ($pengembalian->peminjaman->peminjam->no_identitas ?? '-') : '-' }}</p>
                    <p><span class="text-zinc-500">No. Telepon:</span> {{ $pengembalian->peminjaman && $pengembalian->peminjaman->peminjam ? ($pengembalian->peminjaman->peminjam->no_telepon ?? '-') : '-' }}</p>
                    <p><span class="text-zinc-500">Petugas Pemeriksa:</span> <b>{{ $pengembalian->diprosesOleh ? $pengembalian->diprosesOleh->username : 'Admin' }}</b></p>
                </div>
            </div>
            <div>
                <h4 class="font-bold text-zinc-400 uppercase tracking-wider mb-2">Informasi Transaksi</h4>
                <div class="space-y-1 text-zinc-800">
                    <p><span class="text-zinc-500">No. Transaksi Peminjaman:</span> <b class="font-mono">{{ $pengembalian->peminjaman ? $pengembalian->peminjaman->kode_transaksi : '-' }}</b></p>
                    <p><span class="text-zinc-500">Tanggal Pinjam:</span> {{ $pengembalian->peminjaman && $pengembalian->peminjaman->tanggal_pinjam ? \Carbon\Carbon::parse($pengembalian->peminjaman->tanggal_pinjam)->format('d F Y') : '-' }}</p>
                    <p><span class="text-zinc-500">Batas Rencana Kembali:</span> <b>{{ $pengembalian->peminjaman ? \Carbon\Carbon::parse($pengembalian->peminjaman->tanggal_kembali_rencana)->format('d F Y') : '-' }}</b></p>
                    <p><span class="text-zinc-500">Tanggal Realisasi Kembali:</span> <b class="text-zinc-900">{{ \Carbon\Carbon::parse($pengembalian->tanggal_pengembalian)->format('d F Y') }}</b></p>
                </div>
            </div>
        </div>

        <!-- Tabel Hasil Pengecekan Fisik Alat -->
        <div class="mb-6">
            <h4 class="font-bold text-zinc-700 text-xs uppercase tracking-wider mb-2 flex items-center justify-between">
                <span>Daftar Alat & Hasil Pemeriksaan Kondisi Fisik</span>
                <span class="text-zinc-400 font-normal">Inspeksi saat pengembalian</span>
            </h4>
            <div class="border border-zinc-200 rounded-xl overflow-hidden">
                <table class="w-full text-left text-xs">
                    <thead class="bg-zinc-50 border-b border-zinc-200 text-zinc-500 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="py-3 px-4">No</th>
                            <th class="py-3 px-4">Nama Alat</th>
                            <th class="py-3 px-4 text-center">Jumlah Kembali</th>
                            <th class="py-3 px-4 text-center">Hasil Cek Kondisi</th>
                            <th class="py-3 px-4">Keterangan / Temuan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 text-zinc-700">
                        @forelse($pengembalian->detailPengembalian as $index => $dp)
                            <tr class="hover:bg-zinc-50/50">
                                <td class="py-3 px-4 font-mono text-zinc-400">{{ $index + 1 }}</td>
                                <td class="py-3 px-4 font-bold text-zinc-900">
                                    {{ $dp->detailPeminjaman && $dp->detailPeminjaman->alat ? $dp->detailPeminjaman->alat->nama_alat : 'Alat Inventaris' }}
                                    <span class="block text-[10px] font-normal text-zinc-400">Kode: {{ $dp->detailPeminjaman && $dp->detailPeminjaman->alat ? $dp->detailPeminjaman->alat->kode_alat : '-' }}</span>
                                </td>
                                <td class="py-3 px-4 text-center font-mono font-bold">{{ $dp->jumlah_kembali }} Unit</td>
                                <td class="py-3 px-4 text-center">
                                    <span class="px-2 py-0.5 rounded text-[11px] font-semibold {{ $dp->kondisiAlat && $dp->kondisiAlat->dapat_dipinjam ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                        {{ $dp->kondisiAlat ? $dp->kondisiAlat->nama_kondisi : 'Baik' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-zinc-600">
                                    {{ $dp->keterangan_kondisi ?: 'Kondisi barang sesuai dan tidak ada cacat baru.' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 text-center text-zinc-400">Tidak ada rincian item pengembalian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Rincian Biaya & Denda Tambahan -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-zinc-200 pt-6 mb-6">
            <div class="text-xs space-y-3">
                <div>
                    <h4 class="font-bold text-zinc-700 uppercase tracking-wider mb-1">Catatan Serah Terima</h4>
                    <p class="text-zinc-600 italic bg-zinc-50 p-3 rounded-xl border border-zinc-200">
                        "{{ $pengembalian->catatan ?: 'Alat telah diperiksa secara fisik dan dikembalikan sesuai ketentuan operasional SIPERALAT.' }}"
                    </p>
                </div>

                @if($pengembalian->denda->count() > 0)
                    <div>
                        <h4 class="font-bold text-rose-600 uppercase tracking-wider mb-1">Rincian Denda / Biaya Tambahan</h4>
                        <div class="space-y-1.5 bg-rose-50/50 p-3 rounded-xl border border-rose-200 text-zinc-700">
                            @foreach($pengembalian->denda as $d)
                                <div class="flex justify-between items-center text-xs">
                                    <span>
                                        <b class="text-rose-700">{{ $d->jenisDenda ? $d->jenisDenda->nama_denda : 'Denda' }}</b>
                                        @if($d->jumlah_hari_terlambat > 0)
                                            ({{ $d->jumlah_hari_terlambat }} hari terlambat)
                                        @endif
                                        <span class="block text-[11px] text-zinc-500">{{ $d->alasan }}</span>
                                    </span>
                                    <span class="font-mono font-bold text-rose-600">Rp {{ number_format($d->nominal, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Tidak ada denda atau biaya tambahan (Pengembalian tepat waktu & kondisi baik).</span>
                    </div>
                @endif
            </div>

            <!-- Rekapitulasi Tagihan -->
            <div class="space-y-2 text-xs">
                <div class="flex justify-between py-1.5 border-b border-zinc-100 text-zinc-600">
                    <span>Biaya Pokok Sewa:</span>
                    <span class="font-mono font-medium">Rp {{ number_format($pengembalian->peminjaman ? $pengembalian->peminjaman->subtotal : 0, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-zinc-100 text-zinc-600">
                    <span>Total Denda Pengembalian:</span>
                    <span class="font-mono font-semibold {{ $pengembalian->total_denda > 0 ? 'text-rose-600' : 'text-zinc-600' }}">
                        Rp {{ number_format($pengembalian->total_denda, 0, ',', '.') }}
                    </span>
                </div>
                <div class="flex justify-between py-2 text-sm font-bold text-zinc-900 border-t-2 border-zinc-300">
                    <span>Total Tagihan Akhir Transaksi:</span>
                    <span class="font-mono text-base text-zinc-900">
                        Rp {{ number_format($pengembalian->peminjaman ? $pengembalian->peminjaman->total_tagihan : 0, 0, ',', '.') }}
                    </span>
                </div>
                <div class="text-right">
                    <span class="inline-block px-3 py-1 rounded-lg bg-emerald-100 text-emerald-800 text-[11px] font-bold uppercase tracking-wider">
                        STATUS: SELESAI &amp; TERVALIDASI
                    </span>
                </div>
            </div>
        </div>

        <!-- Tanda Tangan -->
        <div class="grid grid-cols-2 gap-8 text-center text-xs mt-12 pt-6 border-t border-zinc-200">
            <div>
                <p class="text-zinc-400 mb-16">Peminjam / Yang Menyerahkan Alat,</p>
                <p class="font-bold text-zinc-900 border-b border-zinc-300 inline-block px-6 pb-1">
                    {{ $pengembalian->peminjaman && $pengembalian->peminjaman->peminjam ? ($pengembalian->peminjaman->peminjam->user->username ?? $pengembalian->peminjaman->peminjam->nama) : 'Peminjam' }}
                </p>
                <p class="text-[10px] text-zinc-400 mt-0.5">Tanda Tangan &amp; Nama Terang</p>
            </div>
            <div>
                <p class="text-zinc-400 mb-16">Petugas Pemeriksa Inventaris,</p>
                <p class="font-bold text-zinc-900 border-b border-zinc-300 inline-block px-6 pb-1">
                    {{ $pengembalian->diprosesOleh ? $pengembalian->diprosesOleh->username : 'Admin SIPERALAT' }}
                </p>
                <p class="text-[10px] text-zinc-400 mt-0.5">SIPERALAT Management System</p>
            </div>
        </div>

    </div>

</body>
</html>
