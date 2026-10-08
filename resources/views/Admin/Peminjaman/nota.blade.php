<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Peminjaman - {{ $peminjaman->kode_transaksi }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
        <a href="{{ route('admin.peminjaman.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-zinc-200 text-sm font-medium hover:bg-zinc-50 shadow-sm text-zinc-700">
            &larr; Kembali ke Data Peminjaman
        </a>
        <button onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-zinc-900 text-white text-sm font-medium hover:bg-zinc-800 shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
            Cetak Nota (Print / PDF)
        </button>
    </div>

    <!-- Printable Invoice Card -->
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
                <p class="text-xs text-zinc-500">Sistem Informasi Peminjaman Peralatan Laboratorium & Inventaris</p>
                <p class="text-xs text-zinc-400">Jl. Teknologi No. 128, Kampus Terpadu</p>
            </div>
            <div class="text-right">
                <span class="text-xs uppercase font-bold tracking-widest text-zinc-400 block">BUKTI / NOTA PEMINJAMAN</span>
                <span class="text-xl font-mono font-bold text-zinc-900 block mt-1">{{ $peminjaman->kode_transaksi }}</span>
                <span class="inline-block mt-2 px-3 py-0.5 rounded-full text-xs font-semibold uppercase bg-zinc-100 text-zinc-700 border border-zinc-200">
                    Status: {{ $peminjaman->status }}
                </span>
            </div>
        </div>

        <!-- Info Grid -->
        <div class="grid grid-cols-2 gap-6 mb-6 text-xs">
            <div>
                <h4 class="font-bold text-zinc-400 uppercase tracking-wider mb-2">Informasi Peminjam</h4>
                <div class="space-y-1 text-zinc-800">
                    <p class="font-bold text-sm text-zinc-900">{{ $peminjaman->peminjam ? ($peminjaman->peminjam->user->username ?? $peminjaman->peminjam->nama) : 'Anonim' }}</p>
                    <p><span class="text-zinc-500">No. Identitas:</span> {{ $peminjaman->peminjam->no_identitas ?? '-' }}</p>
                    <p><span class="text-zinc-500">No. Telepon:</span> {{ $peminjaman->peminjam->no_telepon ?? '-' }}</p>
                    <p><span class="text-zinc-500">Alamat:</span> {{ $peminjaman->peminjam->alamat ?? '-' }}</p>
                </div>
            </div>
            <div>
                <h4 class="font-bold text-zinc-400 uppercase tracking-wider mb-2">Detail Waktu & Keperluan</h4>
                <div class="space-y-1 text-zinc-800">
                    <p><span class="text-zinc-500">Tgl Pinjam:</span> <b>{{ $peminjaman->tanggal_pinjam ? \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d F Y') : '-' }}</b></p>
                    <p><span class="text-zinc-500">Tgl Rencana Kembali:</span> <b>{{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali_rencana)->format('d F Y') }}</b></p>
                    <p><span class="text-zinc-500">Durasi:</span> {{ $peminjaman->lama_pinjam }} Hari</p>
                    <p><span class="text-zinc-500">Keperluan:</span> {{ $peminjaman->keperluan }}</p>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="border border-zinc-200 rounded-xl overflow-hidden mb-6">
            <table class="w-full text-left text-xs">
                <thead class="bg-zinc-50 border-b border-zinc-200 text-zinc-500 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4">No</th>
                        <th class="py-3 px-4">Nama Alat</th>
                        <th class="py-3 px-4 text-center">Jumlah</th>
                        <th class="py-3 px-4 text-right">Tarif / Hari</th>
                        <th class="py-3 px-4 text-right">Durasi</th>
                        <th class="py-3 px-4 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 text-zinc-700">
                    @foreach($peminjaman->detailPeminjaman as $index => $detail)
                        <tr>
                            <td class="py-3 px-4 text-zinc-400">{{ $index + 1 }}</td>
                            <td class="py-3 px-4 font-semibold text-zinc-900">
                                {{ $detail->alat ? $detail->alat->nama_alat : 'Alat Telah Dihapus' }}
                            </td>
                            <td class="py-3 px-4 text-center font-mono">{{ $detail->jumlah }} Unit</td>
                            <td class="py-3 px-4 text-right font-mono">Rp {{ number_format($detail->harga_per_hari, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-right font-mono">{{ $detail->lama_pinjam }} Hari</td>
                            <td class="py-3 px-4 text-right font-mono font-bold text-zinc-900">
                                Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Total Tagihan -->
        <div class="flex justify-end mb-8">
            <div class="w-72 space-y-2 text-xs">
                <div class="flex justify-between text-zinc-600">
                    <span>Subtotal Biaya Sewa:</span>
                    <span class="font-mono font-semibold">Rp {{ number_format($peminjaman->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-zinc-600">
                    <span>Denda Keterlambatan/Kerusakan:</span>
                    <span class="font-mono font-semibold">Rp {{ number_format($peminjaman->total_denda, 0, ',', '.') }}</span>
                </div>
                <div class="border-t border-zinc-300 pt-2 flex justify-between font-bold text-sm text-zinc-900">
                    <span>TOTAL TAGIHAN:</span>
                    <span class="font-mono">Rp {{ number_format($peminjaman->total_tagihan, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Signatures -->
        <div class="grid grid-cols-2 gap-8 text-center text-xs text-zinc-700 pt-6 border-t border-zinc-200">
            <div>
                <p class="text-zinc-500 mb-16">Peminjam / Pelanggan,</p>
                <p class="font-bold border-b border-zinc-400 inline-block px-8 pb-1">
                    {{ $peminjaman->peminjam ? ($peminjaman->peminjam->user->username ?? $peminjaman->peminjam->nama) : 'Peminjam' }}
                </p>
                <p class="text-[10px] text-zinc-400 mt-1">Tanda Tangan & Nama Terang</p>
            </div>
            <div>
                <p class="text-zinc-500 mb-16">Petugas / Disetujui Oleh,</p>
                <p class="font-bold border-b border-zinc-400 inline-block px-8 pb-1">
                    {{ $peminjaman->disetujuiOleh ? $peminjaman->disetujuiOleh->username : 'Admin Petugas' }}
                </p>
                <p class="text-[10px] text-zinc-400 mt-1">
                    {{ $peminjaman->disetujui_at ? \Carbon\Carbon::parse($peminjaman->disetujui_at)->format('d M Y H:i') : 'Petugas Lab' }}
                </p>
            </div>
        </div>

    </div>

</body>
</html>
