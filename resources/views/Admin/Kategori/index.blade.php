@extends('Admin.Layout.index')

@section('title', 'Kategori Alat')

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
                <span class="text-zinc-700 dark:text-zinc-300">Kategori Alat</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-zinc-900 dark:text-white mt-1">
                Data Kategori Alat
            </h1>
            <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">
                Kelola kelompok dan klasifikasi alat inventaris laboratorium.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <button onclick="openModal('modal-tambah-kategori')" class="u-btn u-btn-primary text-xs sm:text-sm shadow-lg">
                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Kategori Baru
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

    <!-- Tabel Kategori -->
    <div class="glass-panel rounded-2xl overflow-hidden border border-black/5 dark:border-white/10 shadow-xl space-y-4 p-5">
        <!-- Filter & Search -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-black/5 dark:border-white/10">
            <div>
                <h3 class="text-base font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="tag" class="w-5 h-5 text-zinc-500"></i>
                    Daftar Kategori
                </h3>
            </div>
            <form action="{{ route('admin.kategori.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                <select name="status" onchange="this.form.submit()" class="u-select py-1.5 px-3 text-xs w-auto">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>

                <div class="flex items-center gap-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / deskripsi..." class="u-input py-1.5 px-3 text-xs w-44 sm:w-56" />
                    <button type="submit" class="u-btn u-btn-ghost u-btn-sm text-xs" title="Cari">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    </button>
                    @if(request('search') || request('status'))
                        <a href="{{ route('admin.kategori.index') }}" class="u-btn u-btn-ghost u-btn-sm text-xs text-rose-500" title="Reset Filter">
                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-zinc-600 dark:text-zinc-300">
                <thead class="text-[11px] font-bold uppercase tracking-wider bg-black/5 dark:bg-white/5 text-zinc-500 dark:text-zinc-400 rounded-lg">
                    <tr>
                        <th class="py-3 px-4 rounded-l-xl">ID</th>
                        <th class="py-3 px-4">Nama Kategori</th>
                        <th class="py-3 px-4">Deskripsi</th>
                        <th class="py-3 px-4">Jumlah Alat</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Dibuat Pada</th>
                        <th class="py-3 px-4 text-right rounded-r-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/5">
                    @forelse($kategoris as $k)
                        <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition-colors">
                            <td class="py-3.5 px-4 font-mono font-bold text-zinc-900 dark:text-white">#{{ $k->id_kategori }}</td>
                            <td class="py-3.5 px-4 font-bold text-zinc-900 dark:text-white">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-lg bg-zinc-800 text-white dark:bg-zinc-200 dark:text-zinc-900 grid place-items-center text-[10px] font-black shrink-0">
                                        <i data-lucide="folder" class="w-3.5 h-3.5"></i>
                                    </span>
                                    <span>{{ $k->nama_kategori }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-zinc-500 dark:text-zinc-400 max-w-xs truncate">
                                {{ $k->deskripsi ?: '-' }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-sky-500/15 text-sky-600 dark:text-sky-400">
                                    {{ $k->alat_count ?? 0 }} Alat
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($k->status === 'aktif')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-semibold bg-emerald-500/15 text-emerald-600 dark:text-emerald-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-semibold bg-rose-500/15 text-rose-600 dark:text-rose-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-zinc-400">
                                {{ $k->created_at ? $k->created_at->format('d M Y, H:i') : '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <button onclick="openEditKategoriModal({{ $k->id_kategori }}, '{{ addslashes($k->nama_kategori) }}', '{{ addslashes($k->deskripsi ?? '') }}', '{{ $k->status }}')" class="p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 text-zinc-500" title="Edit Kategori">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </button>

                                    <form action="{{ route('admin.kategori.destroy', $k->id_kategori) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori {{ $k->nama_kategori }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg hover:bg-rose-500/10 text-rose-500" title="Hapus Kategori">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-zinc-400 text-xs">
                                Tidak ada data kategori ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="pt-3">
            {{ $kategoris->links() }}
        </div>
    </div>
</div>
@endsection

@push('modals')
<!-- Modal Tambah Kategori -->
<div id="modal-tambah-kategori" class="modal-container" role="dialog" aria-modal="true">
    <div class="modal-backdrop" onclick="closeModal('modal-tambah-kategori')"></div>
    <div class="modal-panel modal-md glass-panel">
        <div class="modal-head">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 grid place-items-center font-bold">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white">Tambah Kategori Baru</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Buat klasifikasi kategori alat laboratorium baru.</p>
                </div>
            </div>
            <button onclick="closeModal('modal-tambah-kategori')" class="p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 text-zinc-400">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form action="{{ route('admin.kategori.store') }}" method="POST">
            @csrf
            <div class="modal-body space-y-4">
                <div>
                    <label class="u-field-label">Nama Kategori <span class="u-req">*</span></label>
                    <input type="text" name="nama_kategori" value="{{ old('nama_kategori') }}" required placeholder="Contoh: Alat Jaringan / Perkakas" class="u-input" />
                </div>
                <div>
                    <label class="u-field-label">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" placeholder="Keterangan opsional mengenai kategori alat..." class="u-textarea">{{ old('deskripsi') }}</textarea>
                </div>
                <div>
                    <label class="u-field-label">Status <span class="u-req">*</span></label>
                    <select name="status" required class="u-select">
                        <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" onclick="closeModal('modal-tambah-kategori')" class="u-btn u-btn-ghost u-btn-sm">Batal</button>
                <button type="submit" class="u-btn u-btn-primary u-btn-sm">
                    <i data-lucide="save" class="w-3.5 h-3.5"></i> Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Kategori -->
<div id="modal-edit-kategori" class="modal-container" role="dialog" aria-modal="true">
    <div class="modal-backdrop" onclick="closeModal('modal-edit-kategori')"></div>
    <div class="modal-panel modal-md glass-panel">
        <div class="modal-head">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 grid place-items-center font-bold">
                    <i data-lucide="edit-3" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white">Edit Kategori Alat</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Perbarui informasi nama, deskripsi, atau status kategori.</p>
                </div>
            </div>
            <button onclick="closeModal('modal-edit-kategori')" class="p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 text-zinc-400">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form id="editKategoriForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body space-y-4">
                <div>
                    <label class="u-field-label">Nama Kategori <span class="u-req">*</span></label>
                    <input type="text" id="editNamaKategori" name="nama_kategori" required class="u-input" />
                </div>
                <div>
                    <label class="u-field-label">Deskripsi</label>
                    <textarea id="editDeskripsi" name="deskripsi" rows="3" class="u-textarea"></textarea>
                </div>
                <div>
                    <label class="u-field-label">Status <span class="u-req">*</span></label>
                    <select id="editStatusKategori" name="status" required class="u-select">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" onclick="closeModal('modal-edit-kategori')" class="u-btn u-btn-ghost u-btn-sm">Batal</button>
                <button type="submit" class="u-btn u-btn-primary u-btn-sm">
                    <i data-lucide="check" class="w-3.5 h-3.5"></i> Perbarui Kategori
                </button>
            </div>
        </form>
    </div>
</div>
@endpush

@push('script')
<script>
    function openEditKategoriModal(id, namaKategori, deskripsi, status) {
        document.getElementById('editNamaKategori').value = namaKategori;
        document.getElementById('editDeskripsi').value = deskripsi || '';
        document.getElementById('editStatusKategori').value = status;
        document.getElementById('editKategoriForm').action = "{{ url('/admin/kategori') }}/" + id;
        openModal('modal-edit-kategori');
    }
</script>
@endpush
