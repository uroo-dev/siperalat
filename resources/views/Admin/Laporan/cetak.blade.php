<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekapitulasi Transaksi - SIPERALAT</title>
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
            .print-container {
                box-shadow: none !important;
                border: none !important;
                max-width: 100% !important;
                width: 100% !important;
            }
        }
    </style>
</head>
<body class="bg-zinc-100 text-zinc-800 antialiased py-8 px-4 font-sans text-xs">

    <!-- Print / Close Controls -->
    <div class="max-w-5xl mx-auto mb-4 flex items-center justify-between no-print">
        <button onclick="window.close()" class="px-4 py-2 rounded-xl bg-white border border-zinc-200 text-sm font-medium hover:bg-zinc-50 shadow-sm text-zinc-700">
            Tutup Halaman
        </button>
        <button onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-zinc-900 text-white text-sm font-medium hover:bg-zinc-800 shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
            Cetak / Download PDF
        </button>
    </div>

    <!-- Printable Report Document -->
    <div class="max-w-5xl mx-auto bg-white rounded-2xl border border-zinc-200 shadow-lg p-8 print-container">

        <!-- Kop Surat -->
        <div class="border-b-2 border-zinc-900 pb-4 mb-6 text-center">
            <h2 class="text-xl font-black uppercase tracking-wider text-zinc-900">SISTEM INFORMASI PEMINJAMAN PERALATAN (SIPERALAT)</h2>
            <p class="text-xs text-zinc-600 mt-0.5">Laboratorium Terpadu & Inventaris Alat | Jl. Teknologi No. 128, Kampus Terpadu</p>
            <h3 class="text-sm font-bold uppercase tracking-wide text-zinc-800 mt-4 border-t border-zinc-200 pt-3">
                LAPORAN REKAPITULASI TRANSAKSI {{ strtoupper($jenisLaporan) }}
            </h3>
            <p class="text-[11px] text-zinc-500 mt-0.5">
                Periode: {{ \Carbon\Carbon::parse($tglMulai)->format('d F Y') }} s/d {{ \Carbon\Carbon::parse($tglSelesai)->format('d F Y') }}
            </p>
        </div>

        @if($jenisLaporan === 'peminjaman' || $jenisLaporan === 'semua')
        <!-- Section Peminjaman -->
        <div class="mb-8">
            <h4 class="font-bold text-zinc-900 uppercase tracking-wider text-xs mb-2">A. Rekap Data Peminjaman</h4>
            <table class="w-full text-left border border-zinc-300">
                <thead class="bg-zinc-100 text-zinc-700 font-bold uppercase text-[10px]">
                    <tr class="border-b border-zinc-300">
                        <th class="py-2 px-3 border-r border-zinc-300">No</th>
                        <th class="py-2 px-3 border-r border-zinc-300">Kode & Tgl</th>
                        <th class="py-2 px-3 border-r border-zinc-300">Peminjam</th>
                        <th class="py-2 px-3 border-r border-zinc-300">Rincian Item Alat</th>
                        <th class="py-2 px-3 border-r border-zinc-300">Durasi</th>
                        <th class="py-2 px-3 border-r border-zinc-300 text-right">Total Tagihan</th>
                        <th class="py-2 px-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 text-[11px]">
                    @forelse($peminjamans as $idx => $p)
                        <tr>
                            <td class="py-2 px-3 border-r border-zinc-200 text-center">{{ $idx + 1 }}</td>
                            <td class="py-2 px-3 border-r border-zinc-200 font-mono font-bold">
                                {{ $p->kode_transaksi }}
                                <div class="text-[10px] text-zinc-500 font-normal font-sans">
                                    {{ $p->tanggal_pinjam ? \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y') : '-' }}
                                </div>
                            </td>
                            <td class="py-2 px-3 border-r border-zinc-200">
                                {{ $p->peminjam ? ($p->peminjam->user->username ?? $p->peminjam->nama) : 'Anonim' }}
                            </td>
                            <td class="py-2 px-3 border-r border-zinc-200">
                                @foreach($p->detailPeminjaman as $d)
                                    <div>- {{ $d->alat ? $d->alat->nama_alat : 'Alat' }} ({{ $d->jumlah }} unit)</div>
                                @endforeach
                            </td>
                            <td class="py-2 px-3 border-r border-zinc-200 text-center">{{ $p->lama_pinjam }} Hari</td>
                            <td class="py-2 px-3 border-r border-zinc-200 text-right font-mono font-semibold">
                                Rp {{ number_format($p->total_tagihan, 0, ',', '.') }}
                            </td>
                            <td class="py-2 px-3 text-center uppercase font-bold text-[10px]">
                                {{ $p->status }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-4 text-center text-zinc-400">Tidak ada data peminjaman.</td>
                        </tr>
                    @endforelse
                </tbody>
                @if($peminjamans->count() > 0)
                <tfoot class="bg-zinc-50 border-t-2 border-zinc-300 font-bold">
                    <tr>
                        <td colspan="5" class="py-2 px-3 text-right">TOTAL PENDAPATAN SEWA:</td>
                        <td class="py-2 px-3 text-right font-mono">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
        @endif

        @if($jenisLaporan === 'pengembalian' || $jenisLaporan === 'semua')
        <!-- Section Pengembalian -->
        <div class="mb-8">
            <h4 class="font-bold text-zinc-900 uppercase tracking-wider text-xs mb-2">B. Rekap Data Pengembalian</h4>
            <table class="w-full text-left border border-zinc-300">
                <thead class="bg-zinc-100 text-zinc-700 font-bold uppercase text-[10px]">
                    <tr class="border-b border-zinc-300">
                        <th class="py-2 px-3 border-r border-zinc-300">No</th>
                        <th class="py-2 px-3 border-r border-zinc-300">Kode Pengembalian</th>
                        <th class="py-2 px-3 border-r border-zinc-300">Tgl Kembali</th>
                        <th class="py-2 px-3 border-r border-zinc-300">Ref Peminjaman</th>
                        <th class="py-2 px-3 border-r border-zinc-300">Item & Kondisi</th>
                        <th class="py-2 px-3 border-r border-zinc-300">Petugas</th>
                        <th class="py-2 px-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 text-[11px]">
                    @forelse($pengembalians as $idx => $pg)
                        <tr>
                            <td class="py-2 px-3 border-r border-zinc-200 text-center">{{ $idx + 1 }}</td>
                            <td class="py-2 px-3 border-r border-zinc-200 font-mono font-bold">{{ $pg->kode_pengembalian }}</td>
                            <td class="py-2 px-3 border-r border-zinc-200">
                                {{ $pg->tanggal_pengembalian ? \Carbon\Carbon::parse($pg->tanggal_pengembalian)->format('d/m/Y') : '-' }}
                            </td>
                            <td class="py-2 px-3 border-r border-zinc-200">
                                {{ $pg->peminjaman ? $pg->peminjaman->kode_transaksi : '-' }}
                            </td>
                            <td class="py-2 px-3 border-r border-zinc-200">
                                @foreach($pg->detailPengembalian as $dp)
                                    <div>
                                        - {{ $dp->detailPeminjaman && $dp->detailPeminjaman->alat ? $dp->detailPeminjaman->alat->nama_alat : 'Alat' }}
                                        ({{ $dp->jumlah_kembali }} unit - {{ $dp->kondisiAlat ? $dp->kondisiAlat->nama_kondisi : 'N/A' }})
                                    </div>
                                @endforeach
                            </td>
                            <td class="py-2 px-3 border-r border-zinc-200">
                                {{ $pg->diprosesOleh ? $pg->diprosesOleh->username : '-' }}
                            </td>
                            <td class="py-2 px-3 text-center uppercase font-bold text-[10px]">
                                {{ $pg->status_pengembalian }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-4 text-center text-zinc-400">Tidak ada data pengembalian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @endif

        <!-- Signatures -->
        <div class="grid grid-cols-2 gap-8 text-center text-xs text-zinc-700 pt-8 mt-8 border-t border-zinc-200">
            <div>
                <p class="text-zinc-500 mb-16">Mengetahui,<br>Kepala Laboratorium</p>
                <p class="font-bold border-b border-zinc-400 inline-block px-10 pb-1">
                    ( .................................................. )
                </p>
                <p class="text-[10px] text-zinc-400 mt-1">NIP. .........................................</p>
            </div>
            <div>
                <p class="text-zinc-500 mb-16">
                    Dicetak Pada: {{ date('d F Y') }}<br>
                    Petugas Administrator
                </p>
                <p class="font-bold border-b border-zinc-400 inline-block px-10 pb-1">
                    {{ Auth::check() ? Auth::user()->username : 'Administrator' }}
                </p>
                <p class="text-[10px] text-zinc-400 mt-1">SIPERALAT System</p>
            </div>
        </div>

    </div>

</body>
</html>
