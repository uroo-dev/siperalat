@extends('Admin.Layout.index')

@section('title', 'Laporan & Rekap Transaksi')

@section('content')
<div class="space-y-6">

    <!-- Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">
                <button onclick="openDrawer()" class="md:hidden p-1 -ml-1 rounded hover:bg-black/5 dark:hover:bg-white/10 text-zinc-600 dark:text-zinc-300">
                    <i data-lucide="menu" class="w-4 h-4"></i>
                </button>
                <span>Panel Admin</span>
                <span>/</span>
                <span class="text-zinc-700 dark:text-zinc-300">Laporan Rekap</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-zinc-900 dark:text-white mt-1">
                Laporan & Rekap Transaksi
            </h1>
            <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">
                Filter, pantau pendapatan sewa, dan cetak laporan peminjaman serta pengembalian.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.laporan.cetak', request()->query()) }}" target="_blank" class="u-btn u-btn-primary text-xs sm:text-sm shadow-lg">
                <i data-lucide="printer" class="w-4 h-4"></i> Cetak Laporan (PDF / Print)
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="glass-panel rounded-2xl p-5 border border-black/5 dark:border-white/10 shadow-xl">
        <form action="{{ route('admin.laporan.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="u-field-label">Jenis Laporan</label>
                <select name="jenis" class="u-select text-xs">
                    <option value="peminjaman" {{ $jenisLaporan === 'peminjaman' ? 'selected' : '' }}>Transaksi Peminjaman</option>
                    <option value="pengembalian" {{ $jenisLaporan === 'pengembalian' ? 'selected' : '' }}>Transaksi Pengembalian</option>
                    <option value="semua" {{ $jenisLaporan === 'semua' ? 'selected' : '' }}>Semua Transaksi</option>
                </select>
            </div>

            <div>
                <label class="u-field-label">Dari Tanggal</label>
                <input type="date" name="tgl_mulai" value="{{ $tglMulai }}" class="u-input font-mono text-xs" />
            </div>

            <div>
                <label class="u-field-label">Sampai Tanggal</label>
                <input type="date" name="tgl_selesai" value="{{ $tglSelesai }}" class="u-input font-mono text-xs" />
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="u-btn u-btn-secondary text-xs flex-1">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i> Terapkan Filter
                </button>
                <a href="{{ route('admin.laporan.index') }}" class="u-btn u-btn-ghost text-xs" title="Reset Filter">
                    <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="glass-panel rounded-2xl p-4 border border-black/5 dark:border-white/10 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 grid place-items-center font-bold">
                <i data-lucide="clipboard-list" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs text-zinc-400 font-medium uppercase tracking-wider">Total Peminjaman</p>
                <p class="text-xl font-extrabold text-zinc-900 dark:text-white mt-0.5">{{ $totalPeminjaman }} <span class="text-xs font-normal text-zinc-400">transaksi</span></p>
            </div>
        </div>

        <div class="glass-panel rounded-2xl p-4 border border-black/5 dark:border-white/10 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-500/10 text-purple-600 dark:text-purple-400 grid place-items-center font-bold">
                <i data-lucide="rotate-ccw" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs text-zinc-400 font-medium uppercase tracking-wider">Total Pengembalian</p>
                <p class="text-xl font-extrabold text-zinc-900 dark:text-white mt-0.5">{{ $totalPengembalian }} <span class="text-xs font-normal text-zinc-400">transaksi</span></p>
            </div>
        </div>

        <div class="glass-panel rounded-2xl p-4 border border-black/5 dark:border-white/10 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 grid place-items-center font-bold">
                <i data-lucide="banknote" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs text-zinc-400 font-medium uppercase tracking-wider">Total Tagihan Sewa</p>
                <p class="text-xl font-extrabold text-zinc-900 dark:text-white font-mono mt-0.5">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    @if($jenisLaporan === 'peminjaman' || $jenisLaporan === 'semua')
    <!-- Tabel Hasil Peminjaman -->
    <div class="glass-panel rounded-2xl overflow-hidden border border-black/5 dark:border-white/10 shadow-xl space-y-4 p-5">
        <div class="flex items-center justify-between pb-3 border-b border-black/5 dark:border-white/10">
            <h3 class="text-base font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                <i data-lucide="clipboard-list" class="w-5 h-5 text-zinc-500"></i>
                Rekap Data Peminjaman ({{ $peminjamans->count() }} Data)
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-zinc-600 dark:text-zinc-300">
                <thead class="text-[11px] font-bold uppercase tracking-wider bg-black/5 dark:bg-white/5 text-zinc-500 dark:text-zinc-400 rounded-lg">
                    <tr>
                        <th class="py-3 px-4 rounded-l-xl">Kode & Tgl</th>
                        <th class="py-3 px-4">Peminjam</th>
                        <th class="py-3 px-4">Item Alat</th>
                        <th class="py-3 px-4">Lama</th>
                        <th class="py-3 px-4">Total Tagihan</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right rounded-r-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/5">
                    @forelse($peminjamans as $p)
                        <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition-colors">
                            <td class="py-3.5 px-4 font-mono font-bold text-zinc-900 dark:text-white">
                                {{ $p->kode_transaksi }}
                                <div class="text-[10px] text-zinc-400 font-normal font-sans">
                                    {{ $p->tanggal_pinjam ? \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') : '-' }}
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-medium text-zinc-800 dark:text-zinc-200">
                                {{ $p->peminjam ? ($p->peminjam->user->username ?? $p->peminjam->nama) : 'Anonim' }}
                            </td>
                            <td class="py-3.5 px-4">
                                @foreach($p->detailPeminjaman as $d)
                                    <div class="text-[11px]">{{ $d->alat ? $d->alat->nama_alat : 'Alat Dihapus' }} ({{ $d->jumlah }} unit)</div>
                                @endforeach
                            </td>
                            <td class="py-3.5 px-4 font-mono">{{ $p->lama_pinjam }} Hari</td>
                            <td class="py-3.5 px-4 font-mono font-bold text-zinc-900 dark:text-white">Rp {{ number_format($p->total_tagihan, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold uppercase bg-black/5 dark:bg-white/10 text-zinc-700 dark:text-zinc-300">
                                    {{ $p->status }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('admin.peminjaman.nota', $p->id_peminjaman) }}" target="_blank" class="p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 text-zinc-500 inline-block" title="Cetak Nota">
                                    <i data-lucide="printer" class="w-4 h-4"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-zinc-400">Tidak ada data peminjaman pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @if($jenisLaporan === 'pengembalian' || $jenisLaporan === 'semua')
    <!-- Tabel Hasil Pengembalian -->
    <div class="glass-panel rounded-2xl overflow-hidden border border-black/5 dark:border-white/10 shadow-xl space-y-4 p-5">
        <div class="flex items-center justify-between pb-3 border-b border-black/5 dark:border-white/10">
            <h3 class="text-base font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                <i data-lucide="rotate-ccw" class="w-5 h-5 text-zinc-500"></i>
                Rekap Data Pengembalian ({{ $pengembalians->count() }} Data)
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-zinc-600 dark:text-zinc-300">
                <thead class="text-[11px] font-bold uppercase tracking-wider bg-black/5 dark:bg-white/5 text-zinc-500 dark:text-zinc-400 rounded-lg">
                    <tr>
                        <th class="py-3 px-4 rounded-l-xl">Kode Pengembalian</th>
                        <th class="py-3 px-4">Tgl Kembali</th>
                        <th class="py-3 px-4">Ref Peminjaman</th>
                        <th class="py-3 px-4">Item & Kondisi</th>
                        <th class="py-3 px-4">Petugas</th>
                        <th class="py-3 px-4 text-right rounded-r-xl">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/5">
                    @forelse($pengembalians as $pg)
                        <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition-colors">
                            <td class="py-3.5 px-4 font-mono font-bold text-zinc-900 dark:text-white">
                                {{ $pg->kode_pengembalian }}
                            </td>
                            <td class="py-3.5 px-4">
                                {{ $pg->tanggal_pengembalian ? \Carbon\Carbon::parse($pg->tanggal_pengembalian)->format('d M Y') : '-' }}
                            </td>
                            <td class="py-3.5 px-4 font-medium text-zinc-800 dark:text-zinc-200">
                                {{ $pg->peminjaman ? $pg->peminjaman->kode_transaksi : '-' }}
                            </td>
                            <td class="py-3.5 px-4">
                                @foreach($pg->detailPengembalian as $dp)
                                    <div class="text-[11px]">
                                        {{ $dp->detailPeminjaman && $dp->detailPeminjaman->alat ? $dp->detailPeminjaman->alat->nama_alat : 'Alat' }}
                                        ({{ $dp->jumlah_kembali }} Unit - {{ $dp->kondisiAlat ? $dp->kondisiAlat->nama_kondisi : 'N/A' }})
                                    </div>
                                @endforeach
                            </td>
                            <td class="py-3.5 px-4">
                                {{ $pg->diprosesOleh ? $pg->diprosesOleh->username : '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold uppercase bg-emerald-500/15 text-emerald-600 dark:text-emerald-400">
                                    {{ $pg->status_pengembalian }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-zinc-400">Tidak ada data pengembalian pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
@endsection
