@extends('Admin.Layout.index')

@section('title', 'Transaksi Peminjaman')

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
                Kelola permohonan peminjaman, persetujuan admin/petugas, dan status transaksi.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <button onclick="openModal('modal-tambah-peminjaman')" class="u-btn u-btn-primary text-xs sm:text-sm shadow-lg">
                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Peminjaman Baru
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

    <!-- Tabel Transaksi Peminjaman -->
    <div class="glass-panel rounded-2xl overflow-hidden border border-black/5 dark:border-white/10 shadow-xl space-y-4 p-5">
        <div class="flex items-center justify-between pb-3 border-b border-black/5 dark:border-white/10">
            <div>
                <h3 class="text-base font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="clipboard-list" class="w-5 h-5 text-zinc-500"></i>
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
                        <th class="py-3 px-4 rounded-l-xl">Kode & Peminjam</th>
                        <th class="py-3 px-4">Jadwal Pinjam</th>
                        <th class="py-3 px-4">Item Alat</th>
                        <th class="py-3 px-4">Total Biaya</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right rounded-r-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/5">
                    @forelse($peminjamans as $p)
                        <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition-colors">
                            <td class="py-3.5 px-4">
                                <div>
                                    <span class="font-mono font-bold text-zinc-900 dark:text-white">{{ $p->kode_transaksi }}</span>
                                    <div class="text-zinc-400 text-[11px] mt-0.5 flex items-center gap-1">
                                        <i data-lucide="user" class="w-3 h-3"></i>
                                        <span>{{ $p->peminjam ? ($p->peminjam->user->username ?? $p->peminjam->nama) : 'Anonim' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="text-zinc-900 dark:text-zinc-200 font-semibold">
                                    {{ $p->tanggal_pinjam ? \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') : '-' }} s/d {{ \Carbon\Carbon::parse($p->tanggal_kembali_rencana)->format('d M Y') }}
                                </div>
                                <div class="text-zinc-400 text-[11px]">{{ $p->lama_pinjam }} Hari ({{ $p->keperluan }})</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="space-y-1">
                                    @foreach($p->detailPeminjaman as $d)
                                        <div class="text-[11px] flex items-center gap-1.5">
                                            <span class="font-bold text-zinc-900 dark:text-white">{{ $d->alat ? $d->alat->nama_alat : 'Alat Dihapus' }}</span>
                                            <span class="px-1.5     py-0.5 rounded bg-black/5 dark:bg-white/10 font-mono text-[10px]">{{ $d->jumlah }} Unit</span>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-mono font-bold text-zinc-900 dark:text-white">
                                Rp {{ number_format($p->total_tagihan, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4">
                                @if($p->status === 'menunggu')
                                    <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-amber-500/15 text-amber-600 dark:text-amber-400 flex items-center gap-1.5 w-fit">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        1. Dipesan / Menunggu
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
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('admin.peminjaman.nota', $p->id_peminjaman) }}" target="_blank" class="p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 text-zinc-500" title="Cetak Nota Peminjaman">
                                        <i data-lucide="printer" class="w-4 h-4"></i>
                                    </a>

                                    <button onclick="openStatusModal({{ $p->id_peminjaman }}, '{{ $p->status }}', '{{ addslashes($p->kode_transaksi) }}')" class="p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 text-zinc-500" title="Ubah Tahapan Status">
                                        <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                                    </button>

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
                    <input type="text" name="keperluan" required placeholder="Contoh: Praktikum Jaringan Komputer" class="u-input" />
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

<!-- Modal Update Status Peminjaman -->
<div id="modal-status-peminjaman" class="modal-container" role="dialog" aria-modal="true">
    <div class="modal-backdrop" onclick="closeModal('modal-status-peminjaman')"></div>
    <div class="modal-panel modal-md glass-panel">
        <div class="modal-head">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 grid place-items-center font-bold">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white">Alur Status Peminjaman</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400" id="statusModalSubtitle">Pembaruan tahapan transaksi.</p>
                </div>
            </div>
            <button onclick="closeModal('modal-status-peminjaman')" class="p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 text-zinc-400">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form id="statusForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="modal-body space-y-4">
                <div class="p-3 rounded-xl bg-black/5 dark:bg-white/5 text-xs text-zinc-600 dark:text-zinc-300">
                    <span class="text-zinc-400 block mb-1">Tahapan Status Berjalan:</span>
                    <div class="font-bold flex items-center gap-1.5" id="currentStatusBadge">
                        <!-- Populated by JS -->
                    </div>
                </div>

                <div>
                    <label class="u-field-label">Pilih Aksi Tahap Selanjutnya <span class="u-req">*</span></label>
                    <select id="selectStatus" name="status" required class="u-select" onchange="toggleAlasanPenolakan(this.value)">
                        <!-- Populated dynamically by JS -->
                    </select>
                </div>

                <div id="alasanPenolakanGroup" class="hidden">
                    <label class="u-field-label">Alasan Penolakan / Pembatalan <span class="u-req">*</span></label>
                    <textarea name="alasan_penolakan" rows="2" placeholder="Tulis alasan jika ditolak atau dibatalkan..." class="u-textarea"></textarea>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" onclick="closeModal('modal-status-peminjaman')" class="u-btn u-btn-ghost u-btn-sm">Batal</button>
                <button type="submit" class="u-btn u-btn-primary u-btn-sm">
                    <i data-lucide="check" class="w-3.5 h-3.5"></i> Perbarui Status
                </button>
            </div>
        </form>
    </div>
</div>
@endpush

@push('script')
<script>
    function openStatusModal(id, currentStatus, kodeTransaksi) {
        document.getElementById('statusModalSubtitle').innerText = "Transaksi: " + kodeTransaksi;
        document.getElementById('statusForm').action = "{{ url('/admin/peminjaman') }}/" + id + "/status";

        const badge = document.getElementById('currentStatusBadge');
        const select = document.getElementById('selectStatus');
        select.innerHTML = '';

        if (currentStatus === 'menunggu') {
            badge.innerHTML = '<span class="text-amber-500 font-bold">1. Dipesan (Menunggu Konfirmasi)</span>';
            select.innerHTML = `
                <option value="disetujui">⏩ 2. Setujui Peminjaman (Siap Diambil)</option>
                <option value="dipinjam">⏩ 3. Langsung Serahkan Alat (Sedang Dipinjam)</option>
                <option value="ditolak">❌ Tolak Pengajuan</option>
            `;
        } else if (currentStatus === 'disetujui') {
            badge.innerHTML = '<span class="text-sky-500 font-bold">2. Disetujui (Siap Diambil)</span>';
            select.innerHTML = `
                <option value="dipinjam">⏩ 3. Serahkan Alat ke Peminjam (Sedang Dipinjam)</option>
                <option value="dibatalkan">❌ Batalkan Peminjaman</option>
                <option value="menunggu">⏪ Kembalikan ke Status Menunggu</option>
            `;
        } else if (currentStatus === 'dipinjam') {
            badge.innerHTML = '<span class="text-purple-500 font-bold">3. Sedang Dipinjam</span>';
            select.innerHTML = `
                <option value="dikembalikan">✅ 4. Alat Dikembalikan Penuh (Selesai)</option>
                <option value="disetujui">⏪ Kembalikan ke Status Disetujui</option>
            `;
        } else if (currentStatus === 'sebagian_dikembalikan') {
            badge.innerHTML = '<span class="text-indigo-500 font-bold">3. Sebagian Dikembalikan</span>';
            select.innerHTML = `
                <option value="dikembalikan">✅ 4. Selesaikan Seluruh Pengembalian (Selesai)</option>
            `;
        } else if (currentStatus === 'dikembalikan') {
            badge.innerHTML = '<span class="text-emerald-500 font-bold">4. Selesai Dikembalikan</span>';
            select.innerHTML = `
                <option value="dikembalikan" selected>✅ Transaksi Selesai</option>
                <option value="dipinjam">⏪ Kembalikan ke Status Sedang Dipinjam</option>
            `;
        } else if (currentStatus === 'ditolak') {
            badge.innerHTML = '<span class="text-rose-500 font-bold">Ditolak</span>';
            select.innerHTML = `
                <option value="menunggu">🔄 Buka Kembali (Menunggu Persetujuan)</option>
                <option value="disetujui">⏩ Langsung Setujui</option>
            `;
        } else {
            badge.innerHTML = '<span class="text-zinc-500 font-bold">' + currentStatus + '</span>';
            select.innerHTML = `
                <option value="menunggu">🔄 Kembalikan ke Status Menunggu</option>
            `;
        }

        toggleAlasanPenolakan(select.value);
        openModal('modal-status-peminjaman');
    }

    function toggleAlasanPenolakan(status) {
        const group = document.getElementById('alasanPenolakanGroup');
        if (status === 'ditolak' || status === 'dibatalkan') {
            group.classList.remove('hidden');
        } else {
            group.classList.add('hidden');
        }
    }
</script>
@endpush
