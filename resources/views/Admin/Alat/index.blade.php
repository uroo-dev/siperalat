@extends('Admin.Layout.index')

@section('title', 'Data Alat & Inventaris')

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
                <span class="text-zinc-700 dark:text-zinc-300">Data Alat & Inventaris</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-zinc-900 dark:text-white mt-1">
                Data Alat & Inventaris
            </h1>
            <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">
                Kelola daftar peralatan laboratorium, ketersediaan stok, biaya sewa, dan status alat.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <button onclick="openModal('modal-tambah-alat')" class="u-btn u-btn-primary text-xs sm:text-sm shadow-lg">
                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Alat Baru
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

    <!-- Tabel Alat -->
    <div class="glass-panel rounded-2xl overflow-hidden border border-black/5 dark:border-white/10 shadow-xl space-y-4 p-5">
        <div class="flex items-center justify-between pb-3 border-b border-black/5 dark:border-white/10">
            <div>
                <h3 class="text-base font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="box" class="w-5 h-5 text-zinc-500"></i>
                    Daftar Alat Laboratorium
                </h3>
            </div>
            <div class="text-xs text-zinc-400">
                Total: <span class="font-bold text-zinc-700 dark:text-zinc-200">{{ $alats->total() }}</span> alat
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-zinc-600 dark:text-zinc-300">
                <thead class="text-[11px] font-bold uppercase tracking-wider bg-black/5 dark:bg-white/5 text-zinc-500 dark:text-zinc-400 rounded-lg">
                    <tr>
                        <th class="py-3 px-4 rounded-l-xl">Kode & Nama Alat</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Harga Sewa / Hari</th>
                        <th class="py-3 px-4">Ketersediaan Stok</th>
                        <th class="py-3 px-4">Nilai Ganti</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right rounded-r-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/5">
                    @forelse($alats as $a)
                        <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-zinc-800 text-white dark:bg-zinc-200 dark:text-zinc-900 grid place-items-center font-bold text-xs shrink-0">
                                        <i data-lucide="wrench" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-zinc-900 dark:text-white">{{ $a->nama_alat }}</div>
                                        <div class="font-mono text-[11px] text-zinc-400">{{ $a->kode_alat }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-zinc-500/15 text-zinc-700 dark:text-zinc-300">
                                    {{ $a->kategori ? $a->kategori->nama_kategori : '-' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-mono font-bold text-zinc-900 dark:text-white">
                                Rp {{ number_format($a->harga_sewa, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex flex-wrap items-center gap-1.5 text-[11px]">
                                    <span class="px-2 py-0.5 rounded font-bold bg-emerald-500/15 text-emerald-600 dark:text-emerald-400" title="Stok Tersedia">
                                        {{ $a->stok_tersedia }} Siap
                                    </span>
                                    <span class="px-2 py-0.5 rounded font-medium bg-amber-500/15 text-amber-600 dark:text-amber-400" title="Stok Dipinjam">
                                        {{ $a->stok_dipinjam }} Pinjam
                                    </span>
                                    @if($a->stok_rusak > 0)
                                        <span class="px-2 py-0.5 rounded font-medium bg-rose-500/15 text-rose-600 dark:text-rose-400" title="Stok Rusak">
                                            {{ $a->stok_rusak }} Rusak
                                        </span>
                                    @endif
                                    <span class="text-zinc-400 text-[10px]">/ {{ $a->stok_total }} Total</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-zinc-500 dark:text-zinc-400">
                                Rp {{ number_format($a->nilai_penggantian, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4">
                                @if($a->status === 'tersedia')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-semibold bg-emerald-500/15 text-emerald-600 dark:text-emerald-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Tersedia
                                    </span>
                                @elseif($a->status === 'habis')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-semibold bg-rose-500/15 text-rose-600 dark:text-rose-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Habis
                                    </span>
                                @elseif($a->status === 'maintenance')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-semibold bg-amber-500/15 text-amber-600 dark:text-amber-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Perbaikan
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-semibold bg-zinc-500/15 text-zinc-600 dark:text-zinc-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-zinc-500"></span> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <button onclick="openEditAlatModal({{ $a->id_alat }}, {{ $a->id_kategori }}, '{{ addslashes($a->kode_alat) }}', '{{ addslashes($a->nama_alat) }}', '{{ addslashes($a->deskripsi ?? '') }}', {{ $a->harga_sewa }}, {{ $a->stok_total }}, {{ $a->stok_tersedia }}, {{ $a->stok_dipinjam }}, {{ $a->stok_rusak }}, {{ $a->nilai_penggantian }}, '{{ $a->status }}')" class="p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 text-zinc-500" title="Edit Alat">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </button>

                                    <form action="{{ route('admin.alat.destroy', $a->id_alat) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus alat {{ $a->nama_alat }} ({{ $a->kode_alat }})?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg hover:bg-rose-500/10 text-rose-500" title="Hapus Alat">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-zinc-400 text-xs">
                                Tidak ada data alat inventaris ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="pt-3">
            {{ $alats->links() }}
        </div>
    </div>
</div>
@endsection

@push('modals')
<!-- Modal Tambah Alat -->
<div id="modal-tambah-alat" class="modal-container" role="dialog" aria-modal="true">
    <div class="modal-backdrop" onclick="closeModal('modal-tambah-alat')"></div>
    <div class="modal-panel modal-lg glass-panel">
        <div class="modal-head">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 grid place-items-center font-bold">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white">Tambah Alat Baru</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Daftarkan peralatan baru ke dalam inventaris laboratorium.</p>
                </div>
            </div>
            <button onclick="closeModal('modal-tambah-alat')" class="p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 text-zinc-400">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form action="{{ route('admin.alat.store') }}" method="POST">
            @csrf
            <div class="modal-body space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="u-field-label">Kategori Alat <span class="u-req">*</span></label>
                        <select name="id_kategori" required class="u-select">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $k)
                                <option value="{{ $k->id_kategori }}" {{ old('id_kategori') == $k->id_kategori ? 'selected' : '' }}>
                                    {{ $k->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="u-field-label">Kode Alat <span class="u-req">*</span></label>
                        <input type="text" name="kode_alat" value="{{ old('kode_alat') }}" required placeholder="Contoh: ALT-NET-001" class="u-input font-mono" />
                    </div>
                </div>

                <div>
                    <label class="u-field-label">Nama Alat <span class="u-req">*</span></label>
                    <input type="text" name="nama_alat" value="{{ old('nama_alat') }}" required placeholder="Contoh: Tang Crimping RJ45" class="u-input" />
                </div>

                <div>
                    <label class="u-field-label">Deskripsi & Spesifikasi</label>
                    <textarea name="deskripsi" rows="2" placeholder="Keterangan singkat kondisi dan kegunaan alat..." class="u-textarea">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="u-field-label">Harga Sewa / Hari (Rp) <span class="u-req">*</span></label>
                        <input type="number" step="0.01" min="0" name="harga_sewa" value="{{ old('harga_sewa', 0) }}" required placeholder="0" class="u-input font-mono" />
                    </div>
                    <div>
                        <label class="u-field-label">Nilai Penggantian / Ganti Rugi (Rp) <span class="u-req">*</span></label>
                        <input type="number" step="0.01" min="0" name="nilai_penggantian" value="{{ old('nilai_penggantian', 0) }}" required placeholder="0" class="u-input font-mono" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="u-field-label">Jumlah Stok Total <span class="u-req">*</span></label>
                        <input type="number" min="0" name="stok_total" value="{{ old('stok_total', 1) }}" required placeholder="Contoh: 10" class="u-input font-mono" />
                    </div>
                    <div>
                        <label class="u-field-label">Status Alat <span class="u-req">*</span></label>
                        <select name="status" required class="u-select">
                            <option value="tersedia" {{ old('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="habis" {{ old('status') == 'habis' ? 'selected' : '' }}>Habis</option>
                            <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Perbaikan / Maintenance</option>
                            <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" onclick="closeModal('modal-tambah-alat')" class="u-btn u-btn-ghost u-btn-sm">Batal</button>
                <button type="submit" class="u-btn u-btn-primary u-btn-sm">
                    <i data-lucide="save" class="w-3.5 h-3.5"></i> Simpan Data Alat
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Alat -->
<div id="modal-edit-alat" class="modal-container" role="dialog" aria-modal="true">
    <div class="modal-backdrop" onclick="closeModal('modal-edit-alat')"></div>
    <div class="modal-panel modal-lg glass-panel">
        <div class="modal-head">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 grid place-items-center font-bold">
                    <i data-lucide="edit-3" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white">Edit Data Alat</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Perbarui spesifikasi, ketersediaan unit, biaya, atau status alat.</p>
                </div>
            </div>
            <button onclick="closeModal('modal-edit-alat')" class="p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 text-zinc-400">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form id="editAlatForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="u-field-label">Kategori Alat <span class="u-req">*</span></label>
                        <select id="editIdKategori" name="id_kategori" required class="u-select">
                            @foreach($kategoris as $k)
                                <option value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="u-field-label">Kode Alat <span class="u-req">*</span></label>
                        <input type="text" id="editKodeAlat" name="kode_alat" required class="u-input font-mono" />
                    </div>
                </div>

                <div>
                    <label class="u-field-label">Nama Alat <span class="u-req">*</span></label>
                    <input type="text" id="editNamaAlat" name="nama_alat" required class="u-input" />
                </div>

                <div>
                    <label class="u-field-label">Deskripsi & Spesifikasi</label>
                    <textarea id="editDeskripsiAlat" name="deskripsi" rows="2" class="u-textarea"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="u-field-label">Harga Sewa / Hari (Rp) <span class="u-req">*</span></label>
                        <input type="number" step="0.01" min="0" id="editHargaSewa" name="harga_sewa" required class="u-input font-mono" />
                    </div>
                    <div>
                        <label class="u-field-label">Nilai Penggantian / Ganti Rugi (Rp) <span class="u-req">*</span></label>
                        <input type="number" step="0.01" min="0" id="editNilaiPenggantian" name="nilai_penggantian" required class="u-input font-mono" />
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div>
                        <label class="u-field-label">Stok Total <span class="u-req">*</span></label>
                        <input type="number" min="0" id="editStokTotal" name="stok_total" required class="u-input font-mono" />
                    </div>
                    <div>
                        <label class="u-field-label">Stok Tersedia <span class="u-req">*</span></label>
                        <input type="number" min="0" id="editStokTersedia" name="stok_tersedia" required class="u-input font-mono" />
                    </div>
                    <div>
                        <label class="u-field-label">Dipinjam</label>
                        <input type="number" min="0" id="editStokDipinjam" name="stok_dipinjam" class="u-input font-mono" />
                    </div>
                    <div>
                        <label class="u-field-label">Rusak</label>
                        <input type="number" min="0" id="editStokRusak" name="stok_rusak" class="u-input font-mono" />
                    </div>
                </div>

                <div>
                    <label class="u-field-label">Status Alat <span class="u-req">*</span></label>
                    <select id="editStatusAlat" name="status" required class="u-select">
                        <option value="tersedia">Tersedia</option>
                        <option value="habis">Habis</option>
                        <option value="maintenance">Perbaikan / Maintenance</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" onclick="closeModal('modal-edit-alat')" class="u-btn u-btn-ghost u-btn-sm">Batal</button>
                <button type="submit" class="u-btn u-btn-primary u-btn-sm">
                    <i data-lucide="check" class="w-3.5 h-3.5"></i> Perbarui Data Alat
                </button>
            </div>
        </form>
    </div>
</div>
@endpush

@push('script')
<script>
    function openEditAlatModal(id, idKategori, kode, nama, deskripsi, hargaSewa, stokTotal, stokTersedia, stokDipinjam, stokRusak, nilaiGanti, status) {
        document.getElementById('editIdKategori').value = idKategori;
        document.getElementById('editKodeAlat').value = kode;
        document.getElementById('editNamaAlat').value = nama;
        document.getElementById('editDeskripsiAlat').value = deskripsi || '';
        document.getElementById('editHargaSewa').value = hargaSewa;
        document.getElementById('editNilaiPenggantian').value = nilaiGanti;
        document.getElementById('editStokTotal').value = stokTotal;
        document.getElementById('editStokTersedia').value = stokTersedia;
        document.getElementById('editStokDipinjam').value = stokDipinjam;
        document.getElementById('editStokRusak').value = stokRusak;
        document.getElementById('editStatusAlat').value = status;
        document.getElementById('editAlatForm').action = "{{ url('/admin/alat') }}/" + id;
        openModal('modal-edit-alat');
    }
</script>
@endpush
