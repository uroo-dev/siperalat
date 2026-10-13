@extends('Admin.Layout.index')

@section('title', 'Data Peminjaman Alat')

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
                <span class="text-zinc-700 dark:text-zinc-300">Transaksi Peminjaman</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-zinc-900 dark:text-white mt-1">
                Data Peminjaman Alat
            </h1>
            <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">
                Kelola pemesanan, verifikasi serah terima alat, hingga proses pengembalian dan denda.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <button onclick="openModal('modal-tambah-peminjaman')" class="u-btn u-btn-primary text-xs sm:text-sm shadow-lg">
                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Peminjaman
            </button>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('succes') || session('success'))
        <div class="u-alert u-alert-success">
            <div class="u-alert-icon"><i data-lucide="check-circle" class="w-5 h-5"></i></div>
            <div class="flex-1 min-w-0">
                <h4 class="u-alert-title text-zinc-900 dark:text-zinc-100">Berhasil</h4>
                <p class="u-alert-desc">{{ session('succes') ?? session('success') }}</p>
            </div>
            <button onclick="this.closest('.u-alert').remove()" class="text-zinc-400 hover:text-zinc-600 p-1"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
    @endif

    @if(session('error'))
        <div class="u-alert u-alert-danger">
            <div class="u-alert-icon"><i data-lucide="alert-octagon" class="w-5 h-5"></i></div>
            <div class="flex-1 min-w-0">
                <h4 class="u-alert-title text-zinc-900 dark:text-zinc-100">Peringatan</h4>
                <p class="u-alert-desc">{{ session('error') }}</p>
            </div>
            <button onclick="this.closest('.u-alert').remove()" class="text-zinc-400 hover:text-zinc-600 p-1"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
    @endif

    @if($errors->any())
        <div class="u-alert u-alert-danger">
            <div class="u-alert-icon"><i data-lucide="alert-circle" class="w-5 h-5"></i></div>
            <div class="flex-1 min-w-0">
                <h4 class="u-alert-title text-zinc-900 dark:text-zinc-100">Terjadi Kesalahan Validasi</h4>
                <ul class="list-disc list-inside text-xs mt-1 space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            <button onclick="this.closest('.u-alert').remove()" class="text-zinc-400 hover:text-zinc-600 p-1"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
    @endif

    <!-- Tabel Peminjaman -->
    <div class="glass-panel rounded-2xl overflow-hidden border border-black/5 dark:border-white/10 shadow-xl space-y-4 p-5">
        <div class="flex items-center justify-between pb-3 border-b border-black/5 dark:border-white/10">
            <div>
                <h3 class="text-base font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="calendar" class="w-5 h-5 text-zinc-500"></i>
                    Daftar Transaksi Peminjaman
                </h3>
            </div>
            <div class="text-xs text-zinc-400">
                Total: <span class="font-bold text-zinc-700 dark:text-zinc-200">{{ $peminjamans->total() }}</span> transaksi
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-zinc-600 dark:text-zinc-300">
                <thead class="text-[11px] font-bold uppercase tracking-wider bg-black/5 dark:bg-white/5 text-zinc-500 dark:text-zinc-400 rounded-lg">
                    <tr>
                        <th class="py-3 px-4 rounded-l-xl">Transaksi & Peminjam</th>
                        <th class="py-3 px-4">Jadwal Pinjam</th>
                        <th class="py-3 px-4">Item Alat</th>
                        <th class="py-3 px-4">Biaya & Tagihan</th>
                        <th class="py-3 px-4">Status Transaksi</th>
                        <th class="py-3 px-4 text-right rounded-r-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/5">
                    @forelse($peminjamans as $p)
                        <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition-colors">
                            <td class="py-3.5 px-4">
                                <div>
                                    <span class="font-mono font-bold text-zinc-900 dark:text-white">{{ $p->kode_transaksi }}</span>
                                    <div class="text-zinc-500 dark:text-zinc-400 font-semibold text-xs mt-0.5">
                                        {{ $p->peminjam ? ($p->peminjam->user->username ?? $p->peminjam->nama) : 'Anonim' }}
                                    </div>
                                    <div class="text-zinc-400 text-[11px]">
                                        ID: {{ $p->peminjam ? $p->peminjam->no_identitas : '-' }}
                                    </div>
                                    <div class="text-zinc-400 text-[10px] mt-0.5 truncate max-w-xs" title="{{ $p->keperluan }}">
                                        Keperluan: {{ $p->keperluan }}
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="space-y-0.5">
                                    <div class="flex items-center gap-1.5 text-zinc-700 dark:text-zinc-300">
                                        <i data-lucide="play-circle" class="w-3.5 h-3.5 text-emerald-500"></i>
                                        <span>Pinjam: <b>{{ $p->tanggal_pinjam ? \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') : '-' }}</b></span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-zinc-700 dark:text-zinc-300">
                                        <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-500"></i>
                                        <span>Kembali: <b>{{ \Carbon\Carbon::parse($p->tanggal_kembali_rencana)->format('d M Y') }}</b></span>
                                    </div>
                                    <span class="inline-block text-[10px] px-1.5 py-0.5 rounded bg-black/5 dark:bg-white/10 text-zinc-500">
                                        Durasi: {{ $p->lama_pinjam }} Hari
                                    </span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="space-y-1">
                                    @foreach($p->detailPeminjaman as $d)
                                        <div class="text-[11px] flex items-center justify-between gap-2 max-w-xs">
                                            <span class="truncate font-medium text-zinc-800 dark:text-zinc-200" title="{{ $d->alat ? $d->alat->nama_alat : 'Alat Dihapus' }}">
                                                {{ $d->alat ? $d->alat->nama_alat : 'Alat Dihapus' }}
                                            </span>
                                            <span class="font-mono text-zinc-500 whitespace-nowrap">{{ $d->jumlah }} unit</span>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-mono">
                                <div class="text-zinc-900 dark:text-white font-bold">
                                    Rp {{ number_format($p->total_tagihan, 0, ',', '.') }}
                                </div>
                                @if($p->total_denda > 0)
                                    <div class="text-rose-500 text-[11px] font-semibold">
                                        + Denda: Rp {{ number_format($p->total_denda, 0, ',', '.') }}
                                    </div>
                                @endif
                                <div class="text-zinc-400 text-[10px]">
                                    Sewa: Rp {{ number_format($p->subtotal, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($p->status === 'menunggu')
                                    <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-amber-500/15 text-amber-600 dark:text-amber-400 flex items-center gap-1.5 w-fit">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        1. Menunggu Konfirmasi
                                    </span>
                                @elseif($p->status === 'disetujui')
                                    <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-sky-500/15 text-sky-600 dark:text-sky-400 flex items-center gap-1.5 w-fit">
                                        <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                                        2. Disetujui (Siap Ambil)
                                    </span>
                                @elseif($p->status === 'dipinjam')
                                    <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-purple-500/15 text-purple-600 dark:text-purple-400 flex items-center gap-1.5 w-fit">
                                        <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                        3. Sedang Dipinjam
                                    </span>
                                @elseif($p->status === 'sebagian_dikembalikan')
                                    <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-indigo-500/15 text-indigo-600 dark:text-indigo-400 flex items-center gap-1.5 w-fit">
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                        Sebagian Dikembalikan
                                    </span>
                                @elseif($p->status === 'dikembalikan')
                                    <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5 w-fit">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        4. Selesai Dikembalikan
                                    </span>
                                @elseif($p->status === 'ditolak')
                                    <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-rose-500/15 text-rose-600 dark:text-rose-400 flex items-center gap-1.5 w-fit">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Ditolak
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-zinc-500/15 text-zinc-600 dark:text-zinc-400 flex items-center gap-1.5 w-fit capitalize">
                                        <span class="w-1.5 h-1.5 rounded-full bg-zinc-500"></span>
                                        {{ $p->status }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="inline-flex items-center gap-1.5">

                                    {{-- Tahap 1: Menunggu Persetujuan --}}
                                    @if($p->status === 'menunggu')
                                        <form action="{{ route('admin.peminjaman.status', $p->id_peminjaman) }}" method="POST" class="inline" onsubmit="return confirm('Setujui transaksi {{ $p->kode_transaksi }}? Status akan berubah menjadi Disetujui (Siap Diambil).');">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="disetujui">
                                            <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-semibold shadow-sm transition" title="Setujui Peminjaman">
                                                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                                <span>Setujui</span>
                                            </button>
                                        </form>

                                        <button onclick="openRejectModal({{ $p->id_peminjaman }}, '{{ addslashes($p->kode_transaksi) }}')" class="inline-flex items-center gap-1 px-2 py-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-600 dark:text-rose-400 text-[11px] font-semibold transition" title="Tolak Pengajuan">
                                            <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                            <span>Tolak</span>
                                        </button>

                                    {{-- Tahap 2: Sudah Disetujui -> Serahkan Alat --}}
                                    @elseif($p->status === 'disetujui')
                                        <form action="{{ route('admin.peminjaman.status', $p->id_peminjaman) }}" method="POST" class="inline" onsubmit="return confirm('Serahkan alat untuk transaksi {{ $p->kode_transaksi }} ke peminjam sekarang?');">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="dipinjam">
                                            <button type="submit" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-sky-600 hover:bg-sky-700 text-white text-[11px] font-semibold shadow-sm transition" title="Serahkan Alat ke Peminjam">
                                                <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                                                <span>Serahkan Alat</span>
                                            </button>
                                        </form>

                                    {{-- Tahap 3: Sedang Dipinjam / Sebagian -> Proses Pengembalian (Cek Fisik & Denda) --}}
                                    @elseif(in_array($p->status, ['dipinjam', 'sebagian_dikembalikan']))
                                        <button onclick="openPengembalianModal({{ $p->id_peminjaman }}, '{{ addslashes($p->kode_transaksi) }}', '{{ addslashes($p->peminjam ? ($p->peminjam->user->username ?? $p->peminjam->nama) : 'Peminjam') }}', '{{ $p->tanggal_kembali_rencana ? \Carbon\Carbon::parse($p->tanggal_kembali_rencana)->format('Y-m-d') : '' }}', {{ $p->subtotal }}, {{ json_encode($p->detailPeminjaman) }})" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-amber-500 hover:bg-amber-600 text-white text-[11px] font-semibold shadow-sm transition" title="Inspeksi Kondisi & Pengembalian Alat">
                                            <i data-lucide="corner-down-left" class="w-3.5 h-3.5"></i>
                                            <span>Pengembalian</span>
                                        </button>

                                    {{-- Tahap 4: Selesai Dikembalikan -> Struk Pengembalian --}}
                                    @elseif($p->status === 'dikembalikan')
                                        <a href="{{ route('admin.peminjaman.struk', $p->id_peminjaman) }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-emerald-500/15 hover:bg-emerald-500/25 text-emerald-600 dark:text-emerald-400 text-[11px] font-semibold transition" title="Cetak / Lihat Struk Pengembalian">
                                            <i data-lucide="receipt" class="w-3.5 h-3.5"></i>
                                            <span>Struk Pengembalian</span>
                                        </a>
                                    @endif

                                    {{-- Tombol Nota Peminjaman --}}
                                    <a href="{{ route('admin.peminjaman.nota', $p->id_peminjaman) }}" target="_blank" class="p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 text-zinc-500" title="Cetak Nota Peminjaman">
                                        <i data-lucide="printer" class="w-4 h-4"></i>
                                    </a>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('admin.peminjaman.destroy', $p->id_peminjaman) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi {{ $p->kode_transaksi }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg hover:bg-rose-500/10 text-rose-500" title="Hapus Transaksi">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-zinc-400 text-xs">
                                Belum ada transaksi peminjaman alat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="pt-3">
            {{ $peminjamans->links() }}
        </div>
    </div>
</div>
@endsection

@push('modals')
<!-- Modal Tambah Peminjaman -->
<div id="modal-tambah-peminjaman" class="modal-container" role="dialog" aria-modal="true">
    <div class="modal-backdrop" onclick="closeModal('modal-tambah-peminjaman')"></div>
    <div class="modal-panel modal-lg glass-panel">
        <div class="modal-head">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 grid place-items-center font-bold">
                    <i data-lucide="clipboard-plus" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white">Tambah Transaksi Peminjaman</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Catat pemesanan / peminjaman alat inventaris baru.</p>
                </div>
            </div>
            <button onclick="closeModal('modal-tambah-peminjaman')" class="p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 text-zinc-400">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form action="{{ route('admin.peminjaman.store') }}" method="POST">
            @csrf
            <div class="modal-body space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="u-field-label">Peminjam / Pelanggan <span class="u-req">*</span></label>
                        <select name="id_peminjam" required class="u-select">
                            <option value="">-- Pilih Peminjam --</option>
                            @foreach($peminjams as $pj)
                                <option value="{{ $pj->id_peminjam }}">
                                    {{ $pj->user ? $pj->user->username : $pj->nama }} ({{ $pj->no_identitas }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="u-field-label">Tahap Status Awal <span class="u-req">*</span></label>
                        <select name="status" required class="u-select">
                            <option value="menunggu" selected>1. Dipesan (Menunggu Konfirmasi)</option>
                            <option value="disetujui">2. Disetujui (Siap Diambil)</option>
                            <option value="dipinjam">3. Langsung Dipinjamkan (Stok Terpotong)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="u-field-label">Tanggal Pinjam <span class="u-req">*</span></label>
                        <input type="date" name="tanggal_pinjam" value="{{ date('Y-m-d') }}" required class="u-input font-mono" />
                    </div>
                    <div>
                        <label class="u-field-label">Tanggal Rencana Kembali <span class="u-req">*</span></label>
                        <input type="date" name="tanggal_kembali_rencana" value="{{ date('Y-m-d', strtotime('+3 days')) }}" required class="u-input font-mono" />
                    </div>
                </div>

                <div>
                    <label class="u-field-label">Keperluan Peminjaman <span class="u-req">*</span></label>
                    <input type="text" name="keperluan" required placeholder="Contoh: Praktikum / Ekspedisi Alam" class="u-input" />
                </div>

                <!-- Item Alat yang Dipinjam -->
                <div>
                    <label class="u-field-label">Pilih Alat yang Dipinjam <span class="u-req">*</span></label>
                    <div class="space-y-3 p-3 rounded-xl bg-black/5 dark:bg-white/5 border border-black/5 dark:border-white/10" id="alat-container">
                        <div class="grid grid-cols-12 gap-3 items-center alat-row">
                            <div class="col-span-8">
                                <select name="id_alat[]" required class="u-select">
                                    <option value="">-- Pilih Alat Inventaris --</option>
                                    @foreach($alats as $alt)
                                        <option value="{{ $alt->id_alat }}">
                                            {{ $alt->nama_alat }} (Tersedia: {{ $alt->stok_tersedia }}) - Rp {{ number_format($alt->harga_sewa, 0, ',', '.') }}/hari
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-4">
                                <input type="number" name="jumlah[]" min="1" value="1" placeholder="Jumlah" required class="u-input font-mono" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" onclick="closeModal('modal-tambah-peminjaman')" class="u-btn u-btn-ghost u-btn-sm">Batal</button>
                <button type="submit" class="u-btn u-btn-primary u-btn-sm">
                    <i data-lucide="save" class="w-3.5 h-3.5"></i> Simpan Peminjaman
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tolak Peminjaman -->
<div id="modal-tolak-peminjaman" class="modal-container" role="dialog" aria-modal="true">
    <div class="modal-backdrop" onclick="closeModal('modal-tolak-peminjaman')"></div>
    <div class="modal-panel modal-md glass-panel">
        <div class="modal-head">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-rose-500 text-white grid place-items-center font-bold">
                    <i data-lucide="x-circle" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white">Tolak Pengajuan Peminjaman</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400" id="tolakModalSubtitle">Transaksi</p>
                </div>
            </div>
            <button onclick="closeModal('modal-tolak-peminjaman')" class="p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 text-zinc-400">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form id="tolakForm" method="POST">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="ditolak">
            <div class="modal-body space-y-4">
                <div>
                    <label class="u-field-label">Alasan Penolakan <span class="u-req">*</span></label>
                    <textarea name="alasan_penolakan" rows="3" required placeholder="Contoh: Stok alat sedang dalam perbaikan rutin / jadwal tabrakan..." class="u-textarea"></textarea>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" onclick="closeModal('modal-tolak-peminjaman')" class="u-btn u-btn-ghost u-btn-sm">Batal</button>
                <button type="submit" class="u-btn bg-rose-600 hover:bg-rose-700 text-white u-btn-sm">
                    <i data-lucide="x" class="w-3.5 h-3.5"></i> Tolak Transaksi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Proses Pengembalian Lengkap (Cek Fisik & Denda) -->
<div id="modal-proses-pengembalian" class="modal-container" role="dialog" aria-modal="true">
    <div class="modal-backdrop" onclick="closeModal('modal-proses-pengembalian')"></div>
    <div class="modal-panel modal-lg glass-panel max-h-[90vh] overflow-y-auto">
        <div class="modal-head sticky top-0 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md z-10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500 text-white grid place-items-center font-bold">
                    <i data-lucide="corner-down-left" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white">Pemeriksaan & Pengembalian Alat</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400" id="pgModalSubtitle">Transaksi</p>
                </div>
            </div>
            <button onclick="closeModal('modal-proses-pengembalian')" class="p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 text-zinc-400">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form action="{{ route('admin.pengembalian.store') }}" method="POST" id="pengembalianForm">
            @csrf
            <input type="hidden" name="id_peminjaman" id="pg_id_peminjaman">
            <input type="hidden" id="pg_rencana_kembali">
            <input type="hidden" id="pg_subtotal_pokok">

            <div class="modal-body space-y-5">

                <!-- Ringkasan Transaksi & Tanggal Kembali -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-3.5 rounded-xl bg-black/5 dark:bg-white/5 border border-black/5 dark:border-white/10 text-xs">
                    <div>
                        <span class="text-zinc-400 block text-[11px]">Peminjam / Pelanggan:</span>
                        <span class="font-bold text-zinc-900 dark:text-white" id="pg_peminjam_nama">-</span>
                        <div class="text-zinc-400 mt-1 text-[11px]">
                            Batas Rencana Kembali: <b class="text-zinc-700 dark:text-zinc-200" id="pg_rencana_kembali_text">-</b>
                        </div>
                    </div>
                    <div>
                        <label class="u-field-label">Tanggal Realisasi Kembali <span class="u-req">*</span></label>
                        <input type="date" name="tanggal_pengembalian" id="pg_tanggal_pengembalian" value="{{ date('Y-m-d') }}" required class="u-input font-mono text-xs" onchange="calculateLateFee()" />
                    </div>
                </div>

                <!-- Peringatan Keterlambatan jika ada -->
                <div id="pg_late_alert" class="hidden p-3 rounded-xl bg-amber-500/15 border border-amber-500/30 text-amber-800 dark:text-amber-300 text-xs flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i data-lucide="alert-triangle" class="w-4 h-4 text-amber-500"></i>
                        <span>Terlambat: <b id="pg_late_days">0</b> hari dari jadwal. Denda keterlambatan dihitung otomatis.</span>
                    </div>
                </div>

                <!-- Bagian 1: Cek Kondisi Fisik Tiap Alat -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="u-field-label font-bold text-zinc-900 dark:text-white flex items-center gap-1.5">
                            <i data-lucide="check-square" class="w-4 h-4 text-emerald-500"></i>
                            1. Pemeriksaan Fisik Item Alat <span class="u-req">*</span>
                        </label>
                        <span class="text-[11px] text-zinc-400">Pastikan jumlah & kelayakan fisik alat</span>
                    </div>
                    <div id="pg_items_container" class="space-y-3">
                        <!-- Populated by JS -->
                    </div>
                </div>

                <!-- Bagian 2: Cek Denda & Kerusakan -->
                <div class="p-4 rounded-xl bg-rose-500/5 border border-rose-500/20 space-y-3 text-xs">
                    <div class="flex items-center gap-1.5 font-bold text-zinc-900 dark:text-white text-xs">
                        <i data-lucide="alert-circle" class="w-4 h-4 text-rose-500"></i>
                        2. Cek Denda Tambahan (Keterlambatan / Kerusakan)
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="text-[11px] font-semibold text-zinc-700 dark:text-zinc-300">Denda Keterlambatan (Rp)</label>
                            <input type="number" name="denda_terlambat" id="pg_denda_terlambat" value="0" min="0" class="u-input font-mono text-xs" oninput="updateBillingSummary()" />
                            <input type="hidden" name="hari_terlambat" id="pg_hari_terlambat_input" value="0">
                            <span class="text-[10px] text-zinc-400 block mt-0.5">Dihitung otomatis (Rp 10.000/hari) atau disesuaikan</span>
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-zinc-700 dark:text-zinc-300">Denda Kerusakan / Ganti Rugi (Rp)</label>
                            <input type="number" name="denda_kerusakan" id="pg_denda_kerusakan" value="0" min="0" class="u-input font-mono text-xs" oninput="updateBillingSummary()" />
                            <span class="text-[10px] text-zinc-400 block mt-0.5">Biaya perbaikan jika ada alat yang rusak</span>
                        </div>
                    </div>

                    <div>
                        <label class="text-[11px] font-semibold text-zinc-700 dark:text-zinc-300">Alasan / Rincian Denda Kerusakan (Opsional)</label>
                        <input type="text" name="alasan_denda_kerusakan" placeholder="Misal: Pasak tenda hilang 2 buah / kabel adaptor terkelupas..." class="u-input text-xs" />
                    </div>
                </div>

                <!-- Bagian 3: Ringkasan Tagihan Baru -->
                <div class="p-3.5 rounded-xl bg-black/5 dark:bg-white/5 border border-black/5 dark:border-white/10 text-xs space-y-1.5">
                    <span class="font-bold text-zinc-700 dark:text-zinc-300 block mb-1">Rincian Tagihan Akhir Transaksi:</span>
                    <div class="flex justify-between text-zinc-500">
                        <span>Biaya Sewa Pokok:</span>
                        <span class="font-mono" id="summary_subtotal">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-rose-500 font-semibold">
                        <span>Total Denda Pengembalian:</span>
                        <span class="font-mono" id="summary_denda">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-sm font-bold text-zinc-900 dark:text-white pt-1.5 border-t border-black/5 dark:border-white/10">
                        <span>Total Tagihan Baru:</span>
                        <span class="font-mono text-base" id="summary_total">Rp 0</span>
                    </div>
                </div>

                <!-- Catatan Serah Terima -->
                <div>
                    <label class="u-field-label">Catatan Serah Terima (Opsional)</label>
                    <textarea name="catatan" rows="2" placeholder="Catatan kondisi serah terima barang..." class="u-textarea text-xs"></textarea>
                </div>

            </div>
            <div class="modal-foot">
                <button type="button" onclick="closeModal('modal-proses-pengembalian')" class="u-btn u-btn-ghost u-btn-sm">Batal</button>
                <button type="submit" class="u-btn bg-emerald-600 hover:bg-emerald-700 text-white u-btn-sm">
                    <i data-lucide="check" class="w-3.5 h-3.5"></i> Selesaikan &amp; Terbitkan Struk
                </button>
            </div>
        </form>
    </div>
</div>
@endpush

@push('script')
<script>
    const kondisisData = @json($kondisis);

    function openRejectModal(id, kodeTransaksi) {
        document.getElementById('tolakModalSubtitle').innerText = "Transaksi: " + kodeTransaksi;
        document.getElementById('tolakForm').action = "{{ url('/admin/peminjaman') }}/" + id + "/status";
        openModal('modal-tolak-peminjaman');
    }

    function openPengembalianModal(id, kodeTransaksi, peminjamNama, rencanaKembali, subtotal, details) {
        document.getElementById('pgModalSubtitle').innerText = "Transaksi: " + kodeTransaksi;
        document.getElementById('pg_id_peminjaman').value = id;
        document.getElementById('pg_peminjam_nama').innerText = peminjamNama;
        document.getElementById('pg_rencana_kembali').value = rencanaKembali;
        document.getElementById('pg_rencana_kembali_text').innerText = rencanaKembali ? new Date(rencanaKembali).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'}) : '-';
        document.getElementById('pg_subtotal_pokok').value = subtotal;

        // Reset denda
        document.getElementById('pg_denda_terlambat').value = 0;
        document.getElementById('pg_denda_kerusakan').value = 0;

        // Render item list
        const container = document.getElementById('pg_items_container');
        let html = '';

        details.forEach((d) => {
            const sisa = d.jumlah - (d.jumlah_dikembalikan || 0);
            if (sisa > 0) {
                const namaAlat = d.alat ? d.alat.nama_alat : 'Alat #' + d.id_alat;

                let optionsKondisi = '';
                kondisisData.forEach(k => {
                    optionsKondisi += `<option value="${k.id_kondisi}">${k.nama_kondisi} (${k.dapat_dipinjam ? 'Masuk Stok Tersedia' : 'Masuk Stok Rusak'})</option>`;
                });

                html += `
                    <div class="p-3 rounded-xl bg-white/70 dark:bg-black/30 border border-black/5 dark:border-white/10 space-y-2">
                        <input type="hidden" name="id_detail[]" value="${d.id_detail}">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-zinc-900 dark:text-white">${namaAlat}</span>
                            <span class="text-[11px] text-zinc-400">Total Pinjam: ${d.jumlah} | Belum Kembali: <b class="text-zinc-700 dark:text-zinc-200">${sisa} Unit</b></span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <div>
                                <label class="text-[11px] text-zinc-500">Jumlah Kembali</label>
                                <input type="number" name="jumlah_kembali[]" value="${sisa}" min="1" max="${sisa}" required class="u-input font-mono text-xs" />
                            </div>
                            <div>
                                <label class="text-[11px] text-zinc-500">Kondisi Alat Saat Ini</label>
                                <select name="id_kondisi[]" required class="u-select text-xs">
                                    ${optionsKondisi}
                                </select>
                            </div>
                        </div>
                        <div>
                            <input type="text" name="keterangan_kondisi[]" placeholder="Keterangan fisik jika ada goresan/cacat (opsional)" class="u-input text-xs" />
                        </div>
                    </div>
                `;
            }
        });

        container.innerHTML = html;

        // Hitung denda keterlambatan
        calculateLateFee();

        openModal('modal-proses-pengembalian');

        if (window.lucide) {
            lucide.createIcons();
        }
    }

    function calculateLateFee() {
        const rencanaStr = document.getElementById('pg_rencana_kembali').value;
        const kembaliStr = document.getElementById('pg_tanggal_pengembalian').value;
        const alertBox = document.getElementById('pg_late_alert');
        const lateDaysEl = document.getElementById('pg_late_days');
        const dendaInput = document.getElementById('pg_denda_terlambat');
        const hariInput = document.getElementById('pg_hari_terlambat_input');

        if (!rencanaStr || !kembaliStr) return;

        const rencana = new Date(rencanaStr);
        const kembali = new Date(kembaliStr);

        // Reset waktu ke 00:00:00 untuk perbandingan hari akurat
        rencana.setHours(0,0,0,0);
        kembali.setHours(0,0,0,0);

        const diffTime = kembali - rencana;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

        if (diffDays > 0) {
            alertBox.classList.remove('hidden');
            lateDaysEl.innerText = diffDays;
            hariInput.value = diffDays;
            // Tarif denda default Rp 10.000 per hari keterlambatan
            dendaInput.value = diffDays * 10000;
        } else {
            alertBox.classList.add('hidden');
            hariInput.value = 0;
            dendaInput.value = 0;
        }

        updateBillingSummary();
    }

    function updateBillingSummary() {
        const subtotal = parseFloat(document.getElementById('pg_subtotal_pokok').value || 0);
        const dendaTerlambat = parseFloat(document.getElementById('pg_denda_terlambat').value || 0);
        const dendaKerusakan = parseFloat(document.getElementById('pg_denda_kerusakan').value || 0);
        const totalDenda = dendaTerlambat + dendaKerusakan;
        const totalTagihan = subtotal + totalDenda;

        document.getElementById('summary_subtotal').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
        document.getElementById('summary_denda').innerText = 'Rp ' + totalDenda.toLocaleString('id-ID');
        document.getElementById('summary_total').innerText = 'Rp ' + totalTagihan.toLocaleString('id-ID');
    }
</script>
@endpush
