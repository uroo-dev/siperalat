@extends('Admin.Layout.index')

@section('title', 'Transaksi Pengembalian')

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
                <span class="text-zinc-700 dark:text-zinc-300">Transaksi Pengembalian</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-zinc-900 dark:text-white mt-1">
                Data Pengembalian Alat
            </h1>
            <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">
                Proses pengembalian alat inventaris, pengecekan kondisi fisik, dan pemulihan stok.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <button onclick="openModal('modal-proses-pengembalian')" class="u-btn u-btn-primary text-xs sm:text-sm shadow-lg">
                <i data-lucide="corner-down-left" class="w-4 h-4"></i> Proses Pengembalian Baru
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

    <!-- Tabel Riwayat Pengembalian -->
    <div class="glass-panel rounded-2xl overflow-hidden border border-black/5 dark:border-white/10 shadow-xl space-y-4 p-5">
        <div class="flex items-center justify-between pb-3 border-b border-black/5 dark:border-white/10">
            <div>
                <h3 class="text-base font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="history" class="w-5 h-5 text-zinc-500"></i>
                    Riwayat Pengembalian Alat
                </h3>
            </div>
            <div class="text-xs text-zinc-400">
                Total: <span class="font-bold text-zinc-700 dark:text-zinc-200">{{ $pengembalians->total() }}</span> pengembalian
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-zinc-600 dark:text-zinc-300">
                <thead class="text-[11px] font-bold uppercase tracking-wider bg-black/5 dark:bg-white/5 text-zinc-500 dark:text-zinc-400 rounded-lg">
                    <tr>
                        <th class="py-3 px-4 rounded-l-xl">Kode & Peminjam</th>
                        <th class="py-3 px-4">Tgl Kembali</th>
                        <th class="py-3 px-4">Item & Kondisi</th>
                        <th class="py-3 px-4">Diproses Oleh</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right rounded-r-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/5">
                    @forelse($pengembalians as $pg)
                        <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition-colors">
                            <td class="py-3.5 px-4">
                                <div>
                                    <span class="font-mono font-bold text-zinc-900 dark:text-white">{{ $pg->kode_pengembalian }}</span>
                                    <div class="text-zinc-400 text-[11px] mt-0.5 flex items-center gap-1">
                                        <i data-lucide="receipt" class="w-3 h-3"></i>
                                        <span>Pinjam: {{ $pg->peminjaman ? $pg->peminjaman->kode_transaksi : '-' }}</span>
                                    </div>
                                    <div class="text-zinc-500 dark:text-zinc-400 text-[11px] flex items-center gap-1 mt-0.5">
                                        <i data-lucide="user" class="w-3 h-3"></i>
                                        <span>{{ $pg->peminjaman && $pg->peminjaman->peminjam ? ($pg->peminjaman->peminjam->user->username ?? $pg->peminjaman->peminjam->nama) : 'Anonim' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="text-zinc-900 dark:text-zinc-200 font-semibold">
                                    {{ $pg->tanggal_pengembalian ? \Carbon\Carbon::parse($pg->tanggal_pengembalian)->format('d M Y') : '-' }}
                                </div>
                                @if($pg->catatan)
                                    <div class="text-zinc-400 text-[11px] mt-0.5 italic max-w-xs truncate">"{{ $pg->catatan }}"</div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="space-y-1">
                                    @foreach($pg->detailPengembalian as $dp)
                                        <div class="text-[11px] flex items-center gap-1.5">
                                            <span class="font-bold text-zinc-900 dark:text-white">
                                                {{ $dp->detailPeminjaman && $dp->detailPeminjaman->alat ? $dp->detailPeminjaman->alat->nama_alat : 'Alat Dihapus' }}
                                            </span>
                                            <span class="px-1.5 py-0.5 rounded bg-black/5 dark:bg-white/10 font-mono text-[10px]">{{ $dp->jumlah_kembali }} Unit</span>
                                            <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold {{ $dp->kondisiAlat && $dp->kondisiAlat->dapat_dipinjam ? 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400' : 'bg-rose-500/15 text-rose-600 dark:text-rose-400' }}">
                                                {{ $dp->kondisiAlat ? $dp->kondisiAlat->nama_kondisi : 'N/A' }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="text-zinc-800 dark:text-zinc-200 font-medium">
                                    {{ $pg->diprosesOleh ? $pg->diprosesOleh->username : 'Admin/Petugas' }}
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($pg->status_pengembalian === 'selesai')
                                    <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-emerald-500/15 text-emerald-600 dark:text-emerald-400">
                                        Selesai Penuh
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-amber-500/15 text-amber-600 dark:text-amber-400">
                                        Sebagian
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <form action="{{ route('admin.pengembalian.destroy', $pg->id_pengembalian) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengembalian {{ $pg->kode_pengembalian }}?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg hover:bg-rose-500/10 text-rose-500" title="Hapus Riwayat">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-zinc-400 text-xs">
                                Belum ada riwayat pengembalian alat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="pt-3">
            {{ $pengembalians->links() }}
        </div>
    </div>
</div>
@endsection

@push('modals')
<!-- Modal Proses Pengembalian -->
<div id="modal-proses-pengembalian" class="modal-container" role="dialog" aria-modal="true">
    <div class="modal-backdrop" onclick="closeModal('modal-proses-pengembalian')"></div>
    <div class="modal-panel modal-lg glass-panel">
        <div class="modal-head">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 grid place-items-center font-bold">
                    <i data-lucide="corner-down-left" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white">Proses Pengembalian Alat</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Pilih transaksi aktif untuk memproses pengembalian barang.</p>
                </div>
            </div>
            <button onclick="closeModal('modal-proses-pengembalian')" class="p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 text-zinc-400">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form action="{{ route('admin.pengembalian.store') }}" method="POST">
            @csrf
            <div class="modal-body space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="u-field-label">Pilih Transaksi Peminjaman <span class="u-req">*</span></label>
                        <select name="id_peminjaman" id="selectPeminjaman" required class="u-select" onchange="loadPeminjamanDetails(this.value)">
                            <option value="">-- Pilih Transaksi Aktif --</option>
                            @foreach($peminjamanAktif as $pa)
                                <option value="{{ $pa->id_peminjaman }}" data-details="{{ json_encode($pa->detailPeminjaman) }}">
                                    {{ $pa->kode_transaksi }} - {{ $pa->peminjam ? ($pa->peminjam->user->username ?? $pa->peminjam->nama) : 'Peminjam' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="u-field-label">Tanggal Pengembalian <span class="u-req">*</span></label>
                        <input type="date" name="tanggal_pengembalian" value="{{ date('Y-m-d') }}" required class="u-input font-mono" />
                    </div>
                </div>

                <div>
                    <label class="u-field-label">Item Alat yang Dikembalikan <span class="u-req">*</span></label>
                    <div id="items-return-container" class="space-y-3 p-3 rounded-xl bg-black/5 dark:bg-white/5 border border-black/5 dark:border-white/10">
                        <p class="text-xs text-zinc-400 text-center py-4">Silakan pilih transaksi peminjaman di atas terlebih dahulu.</p>
                    </div>
                </div>

                <div>
                    <label class="u-field-label">Catatan Pengembalian (Opsional)</label>
                    <textarea name="catatan" rows="2" placeholder="Catatan kondisi fisik atau serah terima barang..." class="u-textarea"></textarea>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" onclick="closeModal('modal-proses-pengembalian')" class="u-btn u-btn-ghost u-btn-sm">Batal</button>
                <button type="submit" class="u-btn u-btn-primary u-btn-sm">
                    <i data-lucide="check" class="w-3.5 h-3.5"></i> Simpan Pengembalian
                </button>
            </div>
        </form>
    </div>
</div>
@endpush

@push('script')
<script>
    const kondisisData = @json($kondisis);

    function loadPeminjamanDetails(peminjamanId) {
        const container = document.getElementById('items-return-container');
        if (!peminjamanId) {
            container.innerHTML = '<p class="text-xs text-zinc-400 text-center py-4">Silakan pilih transaksi peminjaman di atas terlebih dahulu.</p>';
            return;
        }

        const select = document.getElementById('selectPeminjaman');
        const selectedOption = select.options[select.selectedIndex];
        const details = JSON.parse(selectedOption.getAttribute('data-details') || '[]');

        if (details.length === 0) {
            container.innerHTML = '<p class="text-xs text-rose-500 text-center py-4">Tidak ada item alat pada transaksi ini.</p>';
            return;
        }

        let html = '';
        let validItemCount = 0;

        details.forEach((d, idx) => {
            const sisa = d.jumlah - (d.jumlah_dikembalikan || 0);
            if (sisa > 0) {
                validItemCount++;
                const namaAlat = d.alat ? d.alat.nama_alat : 'Alat #' + d.id_alat;

                let optionsKondisi = '';
                kondisisData.forEach(k => {
                    optionsKondisi += `<option value="${k.id_kondisi}">${k.nama_kondisi} (${k.dapat_dipinjam ? 'Masuk Stok Tersedia' : 'Masuk Stok Rusak'})</option>`;
                });

                html += `
                    <div class="p-3 rounded-lg bg-white/50 dark:bg-black/20 border border-black/5 dark:border-white/10 space-y-2">
                        <input type="hidden" name="id_detail[]" value="${d.id_detail}">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-xs text-zinc-900 dark:text-white">${namaAlat}</span>
                            <span class="text-[11px] text-zinc-400">Total Pinjam: ${d.jumlah} | Belum Kembali: <b class="text-zinc-700 dark:text-zinc-200">${sisa}</b></span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <div>
                                <label class="text-[11px] text-zinc-400">Jumlah Dikembalikan</label>
                                <input type="number" name="jumlah_kembali[]" value="${sisa}" min="1" max="${sisa}" required class="u-input font-mono text-xs" />
                            </div>
                            <div>
                                <label class="text-[11px] text-zinc-400">Kondisi Alat</label>
                                <select name="id_kondisi[]" required class="u-select text-xs">
                                    ${optionsKondisi}
                                </select>
                            </div>
                        </div>
                        <div>
                            <input type="text" name="keterangan_kondisi[]" placeholder="Keterangan kondisi tambahan (opsional)" class="u-input text-xs" />
                        </div>
                    </div>
                `;
            }
        });

        if (validItemCount === 0) {
            container.innerHTML = '<p class="text-xs text-emerald-500 text-center py-4">Semua item pada transaksi ini sudah dikembalikan sepenuhnya.</p>';
        } else {
            container.innerHTML = html;
        }

        if (window.lucide) {
            lucide.createIcons();
        }
    }
</script>
@endpush
