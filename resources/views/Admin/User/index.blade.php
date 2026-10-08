@extends('Admin.Layout.index')

@section('title', 'Manajemen Pengguna Admin')

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
                <span class="text-zinc-700 dark:text-zinc-300">Manajemen Pengguna</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-zinc-900 dark:text-white mt-1">
                Data Pengguna & Petugas
            </h1>
            <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">
                Kelola akun admin dan petugas laboratorium yang memiliki hak akses sistem.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <button onclick="openModal('modal-tambah-user')" class="u-btn u-btn-primary text-xs sm:text-sm shadow-lg">
                <i data-lucide="user-plus" class="w-4 h-4"></i> Tambah Pengguna Baru
            </button>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('succes'))
        <div class="u-alert u-alert-success">
            <div class="u-alert-icon"><i data-lucide="check-circle" class="w-5 h-5"></i></div>
            <div class="flex-1 min-w-0">
                <h4 class="u-alert-title text-zinc-900 dark:text-zinc-100">Berhasil</h4>
                <p class="u-alert-desc">{{ session('succes') }}</p>
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

    <!-- Tabel Pengguna -->
    <div class="glass-panel rounded-2xl overflow-hidden border border-black/5 dark:border-white/10 shadow-xl space-y-4 p-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-black/5 dark:border-white/10">
            <div>
                <h3 class="text-base font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="users" class="w-5 h-5 text-zinc-500"></i>
                    Daftar Akun Pengguna
                </h3>
            </div>
            <form action="{{ route('admin.users.index') }}" method="GET" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / username..." class="u-input py-1.5 px-3 text-xs w-48 sm:w-60" />
                <button type="submit" class="u-btn u-btn-ghost u-btn-sm text-xs"><i data-lucide="search" class="w-3.5 h-3.5"></i></button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-zinc-600 dark:text-zinc-300">
                <thead class="text-[11px] font-bold uppercase tracking-wider bg-black/5 dark:bg-white/5 text-zinc-500 dark:text-zinc-400 rounded-lg">
                    <tr>
                        <th class="py-3 px-4 rounded-l-xl">ID</th>
                        <th class="py-3 px-4">Nama Lengkap</th>
                        <th class="py-3 px-4">Username</th>
                        <th class="py-3 px-4">Tanggal Dibuat</th>
                        <th class="py-3 px-4 text-right rounded-r-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/5">
                    @forelse($users as $u)
                        <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition-colors">
                            <td class="py-3.5 px-4 font-mono font-bold text-zinc-900 dark:text-white">#{{ $u->id }}</td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                                    <span>{{ $u->nama }}</span>
                                    @if(Auth::id() == $u->id)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/15 text-emerald-600">Anda</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-zinc-500 dark:text-zinc-400">{{ $u->username }}</td>
                            <td class="py-3.5 px-4 text-zinc-400">{{ $u->created_at ? $u->created_at->format('d M Y, H:i') : '-' }}</td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <button onclick="openEditUserModal({{ $u->id }}, '{{ addslashes($u->nama) }}', '{{ addslashes($u->username) }}')" class="p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 text-zinc-500" title="Edit Pengguna">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </button>

                                    @if(Auth::id() != $u->id)
                                        <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $u->nama }}?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg hover:bg-rose-500/10 text-rose-500" title="Hapus Pengguna">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-zinc-400 text-xs">
                                Tidak ada data pengguna ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="pt-3">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection

@push('modals')
<!-- Modal Tambah User -->
<div id="modal-tambah-user" class="modal-container" role="dialog" aria-modal="true">
    <div class="modal-backdrop" onclick="closeModal('modal-tambah-user')"></div>
    <div class="modal-panel modal-md glass-panel">
        <div class="modal-head">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 grid place-items-center font-bold">
                    <i data-lucide="user-plus" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white">Tambah Pengguna Baru</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Buat akun untuk petugas inventaris baru.</p>
                </div>
            </div>
            <button onclick="closeModal('modal-tambah-user')" class="p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 text-zinc-400">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="modal-body space-y-4">
                <div>
                    <label class="u-field-label">Nama Lengkap <span class="u-req">*</span></label>
                    <input type="text" name="nama" required placeholder="Contoh: Rahmat Hidayat" class="u-input" />
                </div>
                <div>
                    <label class="u-field-label">Username <span class="u-req">*</span></label>
                    <input type="text" name="username" required placeholder="Contoh: rahmat_petugas" class="u-input" />
                </div>
                <div>
                    <label class="u-field-label">Kata Sandi (Min. 6 Karakter) <span class="u-req">*</span></label>
                    <input type="password" name="password" required placeholder="••••••••" class="u-input" />
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" onclick="closeModal('modal-tambah-user')" class="u-btn u-btn-ghost u-btn-sm">Batal</button>
                <button type="submit" class="u-btn u-btn-primary u-btn-sm"><i data-lucide="save" class="w-3.5 h-3.5"></i> Simpan User</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit User -->
<div id="modal-edit-user" class="modal-container" role="dialog" aria-modal="true">
    <div class="modal-backdrop" onclick="closeModal('modal-edit-user')"></div>
    <div class="modal-panel modal-md glass-panel">
        <div class="modal-head">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 grid place-items-center font-bold">
                    <i data-lucide="edit-3" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white">Edit Data Pengguna</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Perbarui profil atau reset password akun.</p>
                </div>
            </div>
            <button onclick="closeModal('modal-edit-user')" class="p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 text-zinc-400">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form id="editUserForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body space-y-4">
                <div>
                    <label class="u-field-label">Nama Lengkap <span class="u-req">*</span></label>
                    <input type="text" id="editNama" name="nama" required class="u-input" />
                </div>
                <div>
                    <label class="u-field-label">Username <span class="u-req">*</span></label>
                    <input type="text" id="editUsername" name="username" required class="u-input" />
                </div>
                <div>
                    <label class="u-field-label">Password Baru (Kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tetap" class="u-input" />
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" onclick="closeModal('modal-edit-user')" class="u-btn u-btn-ghost u-btn-sm">Batal</button>
                <button type="submit" class="u-btn u-btn-primary u-btn-sm"><i data-lucide="check" class="w-3.5 h-3.5"></i> Perbarui Data</button>
            </div>
        </form>
    </div>
</div>
@endpush

@push('script')
<script>
    function openEditUserModal(id, nama, username) {
        document.getElementById('editNama').value = nama;
        document.getElementById('editUsername').value = username;
        document.getElementById('editUserForm').action = "{{ url('/admin/users') }}/" + id;
        openModal('modal-edit-user');
    }
</script>
@endpush
