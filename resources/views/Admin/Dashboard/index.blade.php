@extends('Admin.Layout.index')

@section('title', 'Dashboard Manajemen Peminjaman Alat')

@section('content')
<div class="space-y-6">

    <!-- TOP HEADER / BREADCRUMB & ACTIONS -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">
                <button onclick="openDrawer()" class="md:hidden p-1 -ml-1 rounded hover:bg-black/5 dark:hover:bg-white/10 text-zinc-600 dark:text-zinc-300">
                    <i data-lucide="menu" class="w-4 h-4"></i>
                </button>
                <span>Panel Admin</span>
                <span>/</span>
                <span class="text-zinc-700 dark:text-zinc-300">Sistem Peminjaman Alat</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-zinc-900 dark:text-white mt-1">
                Dashboard & Manajemen Alat
            </h1>
            <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">
                Monitoring ketersediaan stok alat, verifikasi pengajuan, dan log pengembalian laboratorium.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <button onclick="openModal('modal-tambah-alat')" class="u-btn u-btn-primary text-xs sm:text-sm shadow-lg">
                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Alat Baru
            </button>
            <button onclick="toast('Laporan bulanan berhasil diekspor ke PDF', 'file-down')" class="u-btn u-btn-ghost text-xs sm:text-sm border border-black/10 dark:border-white/10">
                <i data-lucide="download" class="w-4 h-4"></i> Ekspor Rekap
            </button>
        </div>
    </div>

    <!-- ALERT COMPONENT NOTIFICATIONS -->
    @if(($overdueCount ?? 0) > 0 || ($menungguApproval ?? 0) > 0)
    <div class="space-y-3">
        @if(($overdueCount ?? 0) > 0)
        <!-- Alert Peringatan Terlambat -->
        <div class="u-alert u-alert-danger">
            <div class="u-alert-icon">
                <i data-lucide="alert-octagon" class="w-5 h-5"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="u-alert-title text-zinc-900 dark:text-zinc-100">{{ $overdueCount }} Peminjaman Melewati Batas Waktu</h4>
                <p class="u-alert-desc">
                    Terdapat transaksi peminjaman yang telah melewati batas tanggal rencana pengembalian. Harap tindak lanjuti ke peminjam.
                </p>
            </div>
            <a href="{{ route('admin.peminjaman.index') }}" class="u-btn u-btn-danger u-btn-sm text-xs shrink-0">
                Lihat Data
            </a>
            <button onclick="this.closest('.u-alert').remove()" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 p-1">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        @endif

        @if(($menungguApproval ?? 0) > 0)
        <!-- Alert Info Pengajuan Baru -->
        <div class="u-alert u-alert-info">
            <div class="u-alert-icon">
                <i data-lucide="bell" class="w-5 h-5"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="u-alert-title text-zinc-900 dark:text-zinc-100">{{ $menungguApproval }} Pengajuan Peminjaman Baru Menunggu Verifikasi</h4>
                <p class="u-alert-desc">
                    Permohonan pinjam alat telah masuk dan memerlukan persetujuan petugas.
                </p>
            </div>
            <a href="{{ route('admin.peminjaman.index') }}" class="u-btn u-btn-primary u-btn-sm text-xs shrink-0">
                Periksa Sekarang
            </a>
            <button onclick="this.closest('.u-alert').remove()" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 p-1">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        @endif
    </div>
    @endif

    <!-- STAT CARDS (4 COLUMNS) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Stat 1 -->
        <div class="glass-panel rounded-2xl p-5 border border-black/5 dark:border-white/10">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-zinc-500 dark:text-zinc-400">Total Jenis Alat</span>
                <span class="grid place-items-center w-9 h-9 rounded-xl bg-black/5 dark:bg-white/10 text-zinc-600 dark:text-zinc-300">
                    <i data-lucide="boxes" class="w-4 h-4"></i>
                </span>
            </div>
            <p class="mt-3 text-3xl font-extrabold tracking-tight text-zinc-900 dark:text-white">{{ $totalAlat ?? 0 }}</p>
            <p class="mt-1 text-xs font-semibold text-emerald-600 dark:text-emerald-400 inline-flex items-center gap-1">
                <i data-lucide="check" class="w-3.5 h-3.5"></i> {{ $stokTersedia ?? 0 }} Unit Stok Siap Pakai
            </p>
        </div>

        <!-- Stat 2 -->
        <div class="glass-panel rounded-2xl p-5 border border-black/5 dark:border-white/10">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-zinc-500 dark:text-zinc-400">Sedang Dipinjam</span>
                <span class="grid place-items-center w-9 h-9 rounded-xl bg-sky-500/15 text-sky-600 dark:text-sky-400">
                    <i data-lucide="hand-metal" class="w-4 h-4"></i>
                </span>
            </div>
            <p class="mt-3 text-3xl font-extrabold tracking-tight text-zinc-900 dark:text-white">{{ $totalDipinjam ?? 0 }}</p>
            <p class="mt-1 text-xs font-semibold text-sky-600 dark:text-sky-400 inline-flex items-center gap-1">
                <i data-lucide="clock" class="w-3.5 h-3.5"></i> Transaksi Aktif
            </p>
        </div>

        <!-- Stat 3 -->
        <div class="glass-panel rounded-2xl p-5 border border-black/5 dark:border-white/10">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-zinc-500 dark:text-zinc-400">Menunggu Approval</span>
                <span class="grid place-items-center w-9 h-9 rounded-xl bg-amber-500/15 text-amber-600 dark:text-amber-400">
                    <i data-lucide="user-check" class="w-4 h-4"></i>
                </span>
            </div>
            <p class="mt-3 text-3xl font-extrabold tracking-tight text-zinc-900 dark:text-white">{{ $menungguApproval ?? 0 }}</p>
            <p class="mt-1 text-xs font-semibold text-amber-600 dark:text-amber-400 inline-flex items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> Perlu Tindakan
            </p>
        </div>

        <!-- Stat 4 -->
        <div class="glass-panel rounded-2xl p-5 border border-black/5 dark:border-white/10">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-zinc-500 dark:text-zinc-400">Total Pengembalian</span>
                <span class="grid place-items-center w-9 h-9 rounded-xl bg-emerald-500/15 text-emerald-600 dark:text-emerald-400">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                </span>
            </div>
            <p class="mt-3 text-3xl font-extrabold tracking-tight text-zinc-900 dark:text-white">{{ $totalPengembalian ?? 0 }}</p>
            <p class="mt-1 text-xs font-semibold text-emerald-600 dark:text-emerald-400 inline-flex items-center gap-1">
                <i data-lucide="check-check" class="w-3.5 h-3.5"></i> Riwayat Selesai
            </p>
        </div>
    </div>

    <!-- LOG AKTIVITAS SISTEM REALTIME -->
    <div class="glass-panel rounded-2xl p-5 border border-black/5 dark:border-white/10 shadow-xl space-y-3">
        <div class="flex items-center justify-between border-b border-black/5 dark:border-white/10 pb-3">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-xl bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 grid place-items-center font-bold">
                    <i data-lucide="activity" class="w-4 h-4"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-zinc-900 dark:text-white">Log Aktivitas Pengguna & Sistem</h3>
                    <p class="text-[11px] text-zinc-400">Rekaman jejak aksi login, perubahan status peminjaman, pengembalian, dan data inventaris.</p>
                </div>
            </div>
        </div>

        <div class="space-y-2 max-h-72 overflow-y-auto pr-1 divide-y divide-black/5 dark:divide-white/5">
            @forelse($recentLogs ?? [] as $log)
                <div class="pt-2 first:pt-0 flex items-start justify-between gap-3 text-xs">
                    <div class="flex items-start gap-2.5">
                        <span class="w-2 h-2 rounded-full bg-zinc-400 dark:bg-zinc-500 mt-1.5 shrink-0"></span>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-zinc-900 dark:text-white">{{ $log->user ?? 'Sistem' }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-black/5 dark:bg-white/10 text-zinc-600 dark:text-zinc-300">{{ $log->aksi }}</span>
                            </div>
                            <p class="text-zinc-500 dark:text-zinc-400 text-[11px] mt-0.5">{{ $log->deskripsi }}</p>
                        </div>
                    </div>
                    <span class="text-[10px] font-mono text-zinc-400 shrink-0">{{ $log->created_at ? $log->created_at->diffForHumans() : '-' }}</span>
                </div>
            @empty
                <p class="text-xs text-zinc-400 py-3 text-center">Belum ada aktivitas yang tercatat.</p>
            @endforelse
        </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="glass-panel rounded-2xl p-4 border border-black/5 dark:border-white/10">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2 text-xs font-bold text-zinc-700 dark:text-zinc-300">
                <i data-lucide="zap" class="w-4 h-4 text-amber-500"></i> Aksi Cepat:
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <button onclick="openModal('modal-tambah-alat')" class="u-btn u-btn-ghost u-btn-sm text-xs">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i> Tambah Alat
                </button>
                <button onclick="toast('Kamera pemindai barcode / QR alat aktif', 'scan')" class="u-btn u-btn-ghost u-btn-sm text-xs">
                    <i data-lucide="scan" class="w-3.5 h-3.5"></i> Scan Barcode Alat
                </button>
                <button onclick="toast('Laporan harian berhasil diunduh', 'printer')" class="u-btn u-btn-ghost u-btn-sm text-xs">
                    <i data-lucide="printer" class="w-3.5 h-3.5"></i> Cetak Berita Acara
                </button>
            </div>
        </div>
    </div>

    <!-- TABEL 1: DAFTAR PENGAJUAN & STATUS PEMINJAMAN ALAT -->
    <section id="tabel-peminjaman" class="glass-panel rounded-2xl overflow-hidden border border-black/5 dark:border-white/10 shadow-xl space-y-4 p-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-black/5 dark:border-white/10">
            <div>
                <h3 class="text-base font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="clipboard-list" class="w-5 h-5 text-zinc-500"></i>
                    Manajemen Pengajuan Peminjaman Alat
                </h3>
                <p class="text-xs text-zinc-500 dark:text-zinc-400">Daftar transaksi peminjaman aktif, permohonan persetujuan, dan pengembalian.</p>
            </div>
            <div class="flex items-center gap-2">
                <select id="filterStatusPinjam" onchange="filterPeminjaman(this.value)" class="u-select py-1.5 px-3 text-xs w-auto">
                    <option value="all">Semua Status</option>
                    <option value="Menunggu">Menunggu Approval</option>
                    <option value="Dipinjam">Sedang Dipinjam</option>
                    <option value="Terlambat">Terlambat</option>
                    <option value="Selesai">Selesai</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-zinc-600 dark:text-zinc-300">
                <thead class="text-[11px] font-bold uppercase tracking-wider bg-black/5 dark:bg-white/5 text-zinc-500 dark:text-zinc-400 rounded-lg">
                    <tr>
                        <th class="py-3 px-4 rounded-l-xl">No. Tiket</th>
                        <th class="py-3 px-4">Peminjam</th>
                        <th class="py-3 px-4">Alat & Jumlah</th>
                        <th class="py-3 px-4">Tgl Pinjam - Kembali</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right rounded-r-xl">Aksi Petugas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/5" id="peminjamanTableBody">

                    @forelse($recentPeminjamans ?? [] as $rp)
                    <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition-colors" data-status="{{ ucfirst($rp->status) }}">
                        <td class="py-3.5 px-4 font-mono font-bold text-zinc-900 dark:text-white">
                            {{ $rp->kode_transaksi }}
                        </td>
                        <td class="py-3.5 px-4">
                            <div class="font-bold text-zinc-900 dark:text-white">{{ $rp->peminjam ? $rp->peminjam->nama : '-' }}</div>
                            <div class="text-[11px] text-zinc-400">ID: {{ $rp->peminjam ? $rp->peminjam->no_identitas : '-' }} • Telp: {{ $rp->peminjam ? $rp->peminjam->no_telepon : '-' }}</div>
                        </td>
                        <td class="py-3.5 px-4">
                            @if($rp->detailPeminjaman && $rp->detailPeminjaman->isNotEmpty())
                                @php $d = $rp->detailPeminjaman->first(); @endphp
                                <div class="font-semibold text-zinc-900 dark:text-white">{{ $d->alat ? $d->alat->nama_alat : '-' }}</div>
                                <div class="text-[11px] text-zinc-400">{{ $d->jumlah }} Unit @if($rp->detailPeminjaman->count() > 1) (+{{ $rp->detailPeminjaman->count() - 1 }} lainnya) @endif</div>
                            @else
                                <span class="text-zinc-400">-</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4">
                            <div>{{ \Carbon\Carbon::parse($rp->tanggal_pinjam)->translatedFormat('d M Y') }}</div>
                            <div class="text-[11px] text-zinc-400">s/d {{ \Carbon\Carbon::parse($rp->tanggal_kembali_rencana)->translatedFormat('d M Y') }}</div>
                        </td>
                        <td class="py-3.5 px-4">
                            @if($rp->status === 'menunggu')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-500/15 text-amber-600 dark:text-amber-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Menunggu Approval
                                </span>
                            @elseif($rp->status === 'disetujui' || $rp->status === 'dipinjam')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-sky-500/15 text-sky-600 dark:text-sky-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span> Dipinjam
                                </span>
                            @elseif($rp->status === 'dikembalikan')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500/15 text-emerald-600 dark:text-emerald-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Selesai
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-500/15 text-rose-600 dark:text-rose-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> {{ ucfirst($rp->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            <div class="inline-flex items-center gap-1.5">
                                <a href="{{ route('admin.peminjaman.nota', $rp->id_peminjaman) }}" class="p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 text-zinc-500" title="Cetak Nota">
                                    <i data-lucide="printer" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('admin.peminjaman.index') }}" class="p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 text-zinc-500" title="Kelola Transaksi">
                                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-zinc-400 text-xs">
                            Belum ada riwayat transaksi peminjaman terbaru.
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </section>

    <!-- TABEL 2: DATA INVENTARIS ALAT & KONDISI -->
    <section id="tabel-alat" class="glass-panel rounded-2xl overflow-hidden border border-black/5 dark:border-white/10 shadow-xl space-y-4 p-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-black/5 dark:border-white/10">
            <div>
                <h3 class="text-base font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="boxes" class="w-5 h-5 text-zinc-500"></i>
                    Inventaris & Stok Fisik Alat
                </h3>
                <p class="text-xs text-zinc-500 dark:text-zinc-400">Daftar master alat, ketersediaan unit, biaya sewa, dan status alat.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.alat.index') }}" class="u-btn u-btn-primary u-btn-sm text-xs">
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i> Lihat Semua Alat
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-zinc-600 dark:text-zinc-300">
                <thead class="text-[11px] font-bold uppercase tracking-wider bg-black/5 dark:bg-white/5 text-zinc-500 dark:text-zinc-400 rounded-lg">
                    <tr>
                        <th class="py-3 px-4 rounded-l-xl">Kode</th>
                        <th class="py-3 px-4">Nama Alat</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Stok (Tersedia / Total)</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Tarif Sewa</th>
                        <th class="py-3 px-4 text-right rounded-r-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/5" id="inventoryTableBody">

                    @forelse($recentAlats ?? [] as $ra)
                    <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition-colors">
                        <td class="py-3 px-4 font-mono font-bold text-zinc-900 dark:text-white">{{ $ra->kode_alat }}</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg overflow-hidden bg-zinc-800 text-white dark:bg-zinc-200 dark:text-zinc-900 grid place-items-center shrink-0">
                                    @if($ra->foto)
                                        <img src="{{ asset('storage/' . $ra->foto) }}" alt="{{ $ra->nama_alat }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.parentElement.innerHTML='<i data-lucide=\'wrench\' class=\'w-3.5 h-3.5\'></i>';" />
                                    @else
                                        <i data-lucide="wrench" class="w-3.5 h-3.5"></i>
                                    @endif
                                </div>
                                <span class="font-bold text-zinc-900 dark:text-white">{{ $ra->nama_alat }}</span>
                            </div>
                        </td>
                        <td class="py-3 px-4">{{ $ra->kategori ? $ra->kategori->nama_kategori : '-' }}</td>
                        <td class="py-3 px-4 font-semibold text-emerald-600 dark:text-emerald-400">{{ $ra->stok_tersedia }} / {{ $ra->stok_total }} Unit</td>
                        <td class="py-3 px-4">
                            @if($ra->status === 'tersedia')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/15 text-emerald-600">Tersedia</span>
                            @elseif($ra->status === 'habis')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/15 text-rose-600">Habis</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/15 text-amber-600">{{ ucfirst($ra->status) }}</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-zinc-600 dark:text-zinc-300 font-mono">Rp {{ number_format($ra->harga_sewa, 0, ',', '.') }}/hari</td>
                        <td class="py-3 px-4 text-right">
                            <a href="{{ route('admin.alat.index') }}" class="u-btn u-btn-ghost u-btn-sm py-1 px-2.5 text-[11px]" title="Kelola di Menu Alat">
                                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i> Kelola
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-zinc-400 text-xs">
                            Belum ada inventaris alat terdaftar.
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </section>

                </tbody>
            </table>
        </div>
    </section>

</div>
@endsection

@push('modals')
<!-- MODAL 1: TAMBAH / EDIT ALAT (Medium/Large Modal) -->
<div id="modal-tambah-alat" class="modal-container" role="dialog" aria-modal="true">
    <div class="modal-backdrop" onclick="closeModal('modal-tambah-alat')"></div>
    <div class="modal-panel modal-md glass-panel">
        <div class="modal-head">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 grid place-items-center font-bold">
                    <i data-lucide="box" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white" id="modalToolTitle">Tambah Alat Inventaris Baru</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Masukkan detail spesifikasi dan stok alat lab.</p>
                </div>
            </div>
            <button onclick="closeModal('modal-tambah-alat')" class="p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 text-zinc-400">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form onsubmit="saveToolForm(event)">
            <div class="modal-body space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="u-field-label">Kode Alat / Barcode <span class="u-req">*</span></label>
                        <input type="text" id="inpToolCode" required placeholder="Contoh: OSC-0109" class="u-input" />
                    </div>
                    <div>
                        <label class="u-field-label">Kategori Alat <span class="u-req">*</span></label>
                        <select id="inpToolCat" class="u-select" required>
                            <option value="Alat Ukur">Alat Ukur & Uji</option>
                            <option value="Elektronika">Elektronika & IoT</option>
                            <option value="Fabrikasi">Fabrikasi & 3D Print</option>
                            <option value="Multimedia">Multimedia & AV</option>
                            <option value="Mesin Perkakas">Mesin Perkakas</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="u-field-label">Nama Peralatan <span class="u-req">*</span></label>
                    <input type="text" id="inpToolName" required placeholder="Contoh: Signal Generator Rigol DG1022" class="u-input" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="u-field-label">Stok Total <span class="u-req">*</span></label>
                        <input type="number" id="inpToolStock" min="1" value="1" required class="u-input" />
                    </div>
                    <div>
                        <label class="u-field-label">Kondisi Alat <span class="u-req">*</span></label>
                        <select class="u-select" required>
                            <option value="Sangat Baik">Sangat Baik</option>
                            <option value="Baik">Baik</option>
                            <option value="Perlu Kalibrasi">Perlu Kalibrasi</option>
                            <option value="Rusak Ringan">Rusak Ringan</option>
                        </select>
                    </div>
                    <div>
                        <label class="u-field-label">Lokasi Rak / Lab <span class="u-req">*</span></label>
                        <input type="text" id="inpToolLoc" required placeholder="Rak E-03" class="u-input" />
                    </div>
                </div>

                <div>
                    <label class="u-field-label">Deskripsi Spesifikasi & Kelengkapan</label>
                    <textarea rows="2" placeholder="Tuliskan spesifikasi teknis penting..." class="u-textarea"></textarea>
                </div>
            </div>

            <div class="modal-foot">
                <button type="button" onclick="closeModal('modal-tambah-alat')" class="u-btn u-btn-ghost u-btn-sm">
                    Batal
                </button>
                <button type="submit" class="u-btn u-btn-primary u-btn-sm">
                    <i data-lucide="save" class="w-3.5 h-3.5"></i> Simpan Data Alat
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL 2: APPROVAL / PERSETUJUAN PEMINJAMAN (Medium Modal) -->
<div id="modal-verifikasi" class="modal-container" role="dialog" aria-modal="true">
    <div class="modal-backdrop" onclick="closeModal('modal-verifikasi')"></div>
    <div class="modal-panel modal-md glass-panel">
        <div class="modal-head">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 grid place-items-center font-bold">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white">Verifikasi & Setujui Peminjaman</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400" id="appTicketCode">PINJAM-2026-0091</p>
                </div>
            </div>
            <button onclick="closeModal('modal-verifikasi')" class="p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 text-zinc-400">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <div class="modal-body space-y-4 text-xs sm:text-sm">
            <div class="p-4 rounded-xl bg-black/5 dark:bg-white/5 space-y-2">
                <div class="flex justify-between">
                    <span class="text-zinc-400">Nama Peminjam:</span>
                    <strong class="text-zinc-900 dark:text-white" id="appName">-</strong>
                </div>
                <div class="flex justify-between">
                    <span class="text-zinc-400">Item Alat:</span>
                    <strong class="text-zinc-900 dark:text-white" id="appTool">-</strong>
                </div>
                <div class="flex justify-between">
                    <span class="text-zinc-400">Pemeriksaan Fisik:</span>
                    <span class="text-emerald-500 font-bold">Stok Ready di Rak E-02</span>
                </div>
            </div>

            <div>
                <label class="u-field-label">Catatan Tambahan Petugas (Opsional)</label>
                <input type="text" placeholder="Misal: Sudah dicek kabel probe lengkap..." class="u-input" />
            </div>
        </div>
        <div class="modal-foot">
            <button onclick="closeModal('modal-verifikasi')" class="u-btn u-btn-ghost u-btn-sm">
                Batal
            </button>
            <button onclick="confirmApproval()" class="u-btn u-btn-success u-btn-sm">
                <i data-lucide="check" class="w-3.5 h-3.5"></i> Konfirmasi Setujui
            </button>
        </div>
    </div>
</div>

<!-- MODAL 3: PENGEMBALIAN ALAT & CEK KONDISI (Medium Modal) -->
<div id="modal-pengembalian" class="modal-container" role="dialog" aria-modal="true">
    <div class="modal-backdrop" onclick="closeModal('modal-pengembalian')"></div>
    <div class="modal-panel modal-md glass-panel">
        <div class="modal-head">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-sky-500/15 text-sky-600 dark:text-sky-400 grid place-items-center font-bold">
                    <i data-lucide="corner-down-left" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white">Proses Pengembalian Alat</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400" id="retTicketCode">-</p>
                </div>
            </div>
            <button onclick="closeModal('modal-pengembalian')" class="p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 text-zinc-400">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <div class="modal-body space-y-4 text-xs sm:text-sm">
            <div class="p-3.5 rounded-xl bg-black/5 dark:bg-white/5 space-y-1.5 text-xs">
                <div>Peminjam: <strong class="text-zinc-900 dark:text-white" id="retName">-</strong></div>
                <div>Alat: <strong class="text-zinc-900 dark:text-white" id="retTool">-</strong></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="u-field-label">Kondisi Pengembalian <span class="u-req">*</span></label>
                    <select class="u-select">
                        <option value="Lengkap & Normal">Lengkap & Normal</option>
                        <option value="Ada Kerusakan">Ada Kerusakan</option>
                        <option value="Aksesoris Hilang">Aksesoris Hilang</option>
                    </select>
                </div>
                <div>
                    <label class="u-field-label">Denda Keterlambatan (Rp)</label>
                    <input type="number" id="retDenda" value="0" class="u-input" />
                </div>
            </div>

            <div>
                <label class="u-field-label">Catatan Serah Terima</label>
                <textarea rows="2" placeholder="Catatan kondisi saat pengembalian..." class="u-textarea"></textarea>
            </div>
        </div>
        <div class="modal-foot">
            <button onclick="closeModal('modal-pengembalian')" class="u-btn u-btn-ghost u-btn-sm">
                Batal
            </button>
            <button onclick="confirmReturnProcess()" class="u-btn u-btn-primary u-btn-sm">
                <i data-lucide="check-check" class="w-3.5 h-3.5"></i> Selesaikan Pengembalian
            </button>
        </div>
    </div>
</div>

<!-- MODAL 4: KONFIRMASI HAPUS DATA (Small Modal) -->
<div id="modal-hapus" class="modal-container" role="dialog" aria-modal="true">
    <div class="modal-backdrop" onclick="closeModal('modal-hapus')"></div>
    <div class="modal-panel modal-sm glass-panel">
        <div class="modal-head">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-rose-500/15 text-rose-500 grid place-items-center font-bold">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                </div>
                <h3 class="text-sm font-bold text-zinc-900 dark:text-white">Konfirmasi Hapus</h3>
            </div>
            <button onclick="closeModal('modal-hapus')" class="p-1 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 text-zinc-400">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <div class="modal-body text-xs text-zinc-600 dark:text-zinc-300">
            Apakah Anda yakin ingin menghapus data <strong class="text-zinc-900 dark:text-white" id="delItemName">Alat</strong> dari inventaris sistem? Tindakan ini tidak dapat dibatalkan.
        </div>
        <div class="modal-foot">
            <button onclick="closeModal('modal-hapus')" class="u-btn u-btn-ghost u-btn-sm">
                Batal
            </button>
            <button onclick="executeDelete()" class="u-btn u-btn-danger u-btn-sm">
                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus Permanen
            </button>
        </div>
    </div>
</div>
@endpush

@push('script')
<script>
    // Filter Table Peminjaman by Status
    function filterPeminjaman(status) {
        const rows = document.querySelectorAll('#peminjamanTableBody tr');
        rows.forEach(row => {
            if (status === 'all' || row.dataset.status === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Filter Table Inventory by Search
    function filterInventoryTable() {
        const query = document.getElementById('searchToolTable').value.toLowerCase();
        const rows = document.querySelectorAll('#inventoryTableBody tr');
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            if (text.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Modal Approval Helpers
    function openApprovalModal(ticket, name, tool) {
        document.getElementById('appTicketCode').innerText = ticket;
        document.getElementById('appName').innerText = name;
        document.getElementById('appTool').innerText = tool;
        openModal('modal-verifikasi');
    }

    function confirmApproval() {
        closeModal('modal-verifikasi');
        toast('Pengajuan peminjaman berhasil disetujui!', 'check-circle');
    }

    function rejectLoan(ticket) {
        toast(`Pengajuan ${ticket} telah ditolak.`, 'x-circle');
    }

    // Modal Return Process Helpers
    function openReturnModal(ticket, name, tool, lateDays = 0) {
        document.getElementById('retTicketCode').innerText = ticket;
        document.getElementById('retName').innerText = name;
        document.getElementById('retTool').innerText = tool;
        document.getElementById('retDenda').value = lateDays * 15000;
        openModal('modal-pengembalian');
    }

    function confirmReturnProcess() {
        closeModal('modal-pengembalian');
        toast('Alat berhasil dicatat telah dikembalikan ke rak penyimpanan.', 'check-check');
    }

    // Modal Edit Tool
    function editToolModal(code, name, cat, stock, loc) {
        document.getElementById('modalToolTitle').innerText = 'Edit Data Alat Inventaris';
        document.getElementById('inpToolCode').value = code;
        document.getElementById('inpToolName').value = name;
        document.getElementById('inpToolCat').value = cat;
        document.getElementById('inpToolStock').value = stock;
        document.getElementById('inpToolLoc').value = loc;
        openModal('modal-tambah-alat');
    }

    // Save Tool Form Dummy
    function saveToolForm(e) {
        e.preventDefault();
        closeModal('modal-tambah-alat');
        toast('Data alat inventaris berhasil disimpan!', 'save');
    }

    // Modal Delete Confirm
    function confirmDeleteModal(name) {
        document.getElementById('delItemName').innerText = name;
        openModal('modal-hapus');
    }

    function executeDelete() {
        closeModal('modal-hapus');
        toast('Data alat berhasil dihapus dari inventaris.', 'trash-2');
    }
</script>
@endpush
