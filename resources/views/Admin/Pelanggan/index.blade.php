@extends('Admin.Layout.index')

@section('title', 'Data Pelanggan / Peminjam')

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
                <span class="text-zinc-700 dark:text-zinc-300">Data Pelanggan</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-zinc-900 dark:text-white mt-1">
                Data Pelanggan / Peminjam
            </h1>
            <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">
                Kelola akun pelanggan dan peminjam alat laboratorium.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <button onclick="openModal('modal-tambah-pelanggan')" class="u-btn u-btn-primary text-xs sm:text-sm shadow-lg">
                <i data-lucide="user-plus" class="w-4 h-4"></i> Tambah Pelanggan Baru
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

    <!-- Tabel Pelanggan -->
    <div class="glass-panel rounded-2xl overflow-hidden border border-black/5 dark:border-white/10 shadow-xl space-y-4 p-5">
        <div class="flex items-center justify-between pb-3 border-b border-black/5 dark:border-white/10">
            <div>
                <h3 class="text-base font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="user-check" class="w-5 h-5 text-zinc-500"></i>
                    Daftar Akun Pelanggan
                </h3>
            </div>
            <div class="text-xs text-zinc-400">
                Total: <span class="font-bold text-zinc-700 dark:text-zinc-200">{{ $pelanggans->total() }}</span> pelanggan
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-zinc-600 dark:text-zinc-300">
                <thead class="text-[11px] font-bold uppercase tracking-wider bg-black/5 dark:bg-white/5 text-zinc-500 dark:text-zinc-400 rounded-lg">
                    <tr>
                        <th class="py-3 px-4 rounded-l-xl">ID</th>
                        <th class="py-3 px-4">Username</th>
                        <th class="py-3 px-4">Email</th>
                        <th class="py-3 px-4">Role</th>
                        <th class="py-3 px-4">Status Akun</th>
                        <th class="py-3 px-4">Terdaftar Pada</th>
                        <th class="py-3 px-4 text-right rounded-r-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/5">
                    @forelse($pelanggans as $p)
                        <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition-colors">
                            <td class="py-3.5 px-4 font-mono font-bold text-zinc-900 dark:text-white">#{{ $p->id }}</td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-7 h-7 rounded-lg bg-zinc-800 text-white dark:bg-zinc-200 dark:text-zinc-900 grid place-items-center text-xs font-black shrink-0 uppercase">
                                        {{ substr($p->username, 0, 2) }}
                                    </span>
                                    <span class="font-bold text-zinc-900 dark:text-white">{{ $p->username }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-zinc-500 dark:text-zinc-400">{{ $p->email }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-emerald-500/15 text-emerald-600 dark:text-emerald-400">
                                    Peminjam
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($p->status === 'aktif')
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
                                {{ $p->created_at ? $p->created_at->format('d M Y, H:i') : '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <button onclick="openEditPelangganModal({{ $p->id }}, '{{ addslashes($p->username) }}', '{{ addslashes($p->email) }}', '{{ $p->status }}')" class="p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 text-zinc-500" title="Edit Pelanggan">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </button>

                                    <form action="{{ route('admin.pelanggan.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelanggan {{ $p->username }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg hover:bg-rose-500/10 text-rose-500" title="Hapus Pelanggan">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-zinc-400 text-xs">
                                Belum ada data pelanggan / peminjam terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="pt-3">
            {{ $pelanggans->links() }}
        </div>
    </div>
</div>
@endsection

@push('modals')
<!-- Modal Tambah Pelanggan -->
<div id="modal-tambah-pelanggan" class="modal-container" role="dialog" aria-modal="true">
    <div class="modal-backdrop" onclick="closeModal('modal-tambah-pelanggan')"></div>
    <div class="modal-panel modal-md glass-panel">
        <div class="modal-head">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 grid place-items-center font-bold">
                    <i data-lucide="user-plus" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white">Tambah Pelanggan Baru</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Buat akun untuk pelanggan / peminjam.</p>
                </div>
            </div>
            <button onclick="closeModal('modal-tambah-pelanggan')" class="p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 text-zinc-400">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form action="{{ route('admin.pelanggan.store') }}" method="POST">
            @csrf
            <div class="modal-body space-y-4">
                <div>
                    <label class="u-field-label">Username <span class="u-req">*</span></label>
                    <input type="text" name="username" value="{{ old('username') }}" required placeholder="Contoh: budi_peminjam" class="u-input" />
                </div>
                <div>
                    <label class="u-field-label">Email <span class="u-req">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="Contoh: budi@gmail.com" class="u-input" />
                </div>
                <div>
                    <label class="u-field-label">Kata Sandi (Min. 6 Karakter) <span class="u-req">*</span></label>
                    <input type="password" name="password" required placeholder="••••••••" class="u-input" />
                </div>
                <div>
                    <label class="u-field-label">Status Akun <span class="u-req">*</span></label>
                    <select name="status" required class="u-select">
                        <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" onclick="closeModal('modal-tambah-pelanggan')" class="u-btn u-btn-ghost u-btn-sm">Batal</button>
                <button type="submit" class="u-btn u-btn-primary u-btn-sm">
                    <i data-lucide="save" class="w-3.5 h-3.5"></i> Simpan Pelanggan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Pelanggan -->
<div id="modal-edit-pelanggan" class="modal-container" role="dialog" aria-modal="true">
    <div class="modal-backdrop" onclick="closeModal('modal-edit-pelanggan')"></div>
    <div class="modal-panel modal-md glass-panel">
        <div class="modal-head">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 grid place-items-center font-bold">
                    <i data-lucide="edit-3" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white">Edit Data Pelanggan</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Perbarui akun pelanggan atau ganti kata sandi.</p>
                </div>
            </div>
            <button onclick="closeModal('modal-edit-pelanggan')" class="p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 text-zinc-400">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form id="editPelangganForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body space-y-4">
                <div>
                    <label class="u-field-label">Username <span class="u-req">*</span></label>
                    <input type="text" id="editUsernamePelanggan" name="username" required class="u-input" />
                </div>
                <div>
                    <label class="u-field-label">Email <span class="u-req">*</span></label>
                    <input type="email" id="editEmailPelanggan" name="email" required class="u-input" />
                </div>
                <div>
                    <label class="u-field-label">Password Baru (Kosongkan jika tidak ingin diubah)</label>
                    <input type="password" name="password" placeholder="••••••••" class="u-input" />
                </div>
                <div>
                    <label class="u-field-label">Status Akun <span class="u-req">*</span></label>
                    <select id="editStatusPelanggan" name="status" required class="u-select">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" onclick="closeModal('modal-edit-pelanggan')" class="u-btn u-btn-ghost u-btn-sm">Batal</button>
                <button type="submit" class="u-btn u-btn-primary u-btn-sm">
                    <i data-lucide="check" class="w-3.5 h-3.5"></i> Perbarui Data
                </button>
            </div>
        </form>
    </div>
</div>
@endpush

@push('script')
<script>
    function openEditPelangganModal(id, username, email, status) {
        document.getElementById('editUsernamePelanggan').value = username;
        document.getElementById('editEmailPelanggan').value = email;
        document.getElementById('editStatusPelanggan').value = status;
        document.getElementById('editPelangganForm').action = "{{ url('/admin/pelanggan') }}/" + id;
        openModal('modal-edit-pelanggan');
    }
</script>
@endpush
