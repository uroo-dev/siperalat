@extends('Admin.Layout.index')

@section('title', 'Log Aktivitas Sistem')

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
                <span class="text-zinc-700 dark:text-zinc-300">Sistem & Keamanan</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-zinc-900 dark:text-white mt-1">
                Log Aktivitas Sistem
            </h1>
            <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">
                Audit trail lengkap riwayat aktivitas autentikasi, transaksi peminjaman, pengembalian, dan perubahan data inventaris.
            </p>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="glass-panel rounded-2xl p-5 border border-black/5 dark:border-white/10 shadow-xl">
        <form action="{{ route('admin.log.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="u-field-label">Pencarian Kata Kunci</label>
                <div class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari user, aksi, atau keterangan..." class="u-input text-xs pl-8" />
                    <i data-lucide="search" class="w-3.5 h-3.5 absolute left-2.5 top-1/2 -translate-y-1/2 text-zinc-400"></i>
                </div>
            </div>

            <div>
                <label class="u-field-label">Filter Jenis Aksi</label>
                <select name="aksi" class="u-select text-xs">
                    <option value="">-- Semua Jenis Aksi --</option>
                    @foreach($listAksi as $itemAksi)
                        <option value="{{ $itemAksi }}" {{ request('aksi') === $itemAksi ? 'selected' : '' }}>
                            {{ $itemAksi }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="u-field-label">Filter Tanggal</label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="u-input font-mono text-xs" />
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="u-btn u-btn-secondary text-xs flex-1">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i> Terapkan Filter
                </button>
                <a href="{{ route('admin.log.index') }}" class="u-btn u-btn-ghost text-xs" title="Reset Filter">
                    <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Tabel Log Aktivitas -->
    <div class="glass-panel rounded-2xl overflow-hidden border border-black/5 dark:border-white/10 shadow-xl space-y-4 p-5">
        <div class="flex items-center justify-between pb-3 border-b border-black/5 dark:border-white/10">
            <div>
                <h3 class="text-base font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="activity" class="w-5 h-5 text-zinc-500"></i>
                    Catatan Rekam Jejak (Audit Trail)
                </h3>
            </div>
            <div class="text-xs text-zinc-400">
                Total: <span class="font-bold text-zinc-700 dark:text-zinc-200">{{ $logs->total() }}</span> entri tercatat
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-zinc-600 dark:text-zinc-300">
                <thead class="text-[11px] font-bold uppercase tracking-wider bg-black/5 dark:bg-white/5 text-zinc-500 dark:text-zinc-400 rounded-lg">
                    <tr>
                        <th class="py-3 px-4 rounded-l-xl w-16">No</th>
                        <th class="py-3 px-4 w-40">Waktu & Tanggal</th>
                        <th class="py-3 px-4 w-44">Pengguna (User)</th>
                        <th class="py-3 px-4 w-48">Aksi</th>
                        <th class="py-3 px-4 rounded-r-xl">Deskripsi Aktivitas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/5">
                    @forelse($logs as $index => $log)
                        <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition-colors">
                            <td class="py-3.5 px-4 font-mono text-zinc-400">
                                {{ $logs->firstItem() + $index }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="text-zinc-900 dark:text-zinc-200 font-semibold font-mono text-[11px]">
                                    {{ $log->created_at ? $log->created_at->format('d/m/Y H:i:s') : '-' }}
                                </div>
                                <div class="text-[10px] text-zinc-400">
                                    {{ $log->created_at ? $log->created_at->diffForHumans() : '' }}
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-zinc-800 text-white dark:bg-zinc-200 dark:text-zinc-900 grid place-items-center text-[10px] font-bold shrink-0">
                                        {{ strtoupper(substr($log->user ?? 'S', 0, 1)) }}
                                    </div>
                                    <span class="font-bold text-zinc-900 dark:text-white truncate">
                                        {{ $log->user ?? 'Sistem' }}
                                    </span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                @if(str_contains(strtolower($log->aksi), 'login'))
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-500/15 text-blue-600 dark:text-blue-400">
                                        {{ $log->aksi }}
                                    </span>
                                @elseif(str_contains(strtolower($log->aksi), 'logout'))
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-zinc-500/15 text-zinc-600 dark:text-zinc-400">
                                        {{ $log->aksi }}
                                    </span>
                                @elseif(str_contains(strtolower($log->aksi), 'tambah') || str_contains(strtolower($log->aksi), 'register'))
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-500/15 text-emerald-600 dark:text-emerald-400">
                                        {{ $log->aksi }}
                                    </span>
                                @elseif(str_contains(strtolower($log->aksi), 'update') || str_contains(strtolower($log->aksi), 'proses'))
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-500/15 text-amber-600 dark:text-amber-400">
                                        {{ $log->aksi }}
                                    </span>
                                @elseif(str_contains(strtolower($log->aksi), 'hapus'))
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-rose-500/15 text-rose-600 dark:text-rose-400">
                                        {{ $log->aksi }}
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-purple-500/15 text-purple-600 dark:text-purple-400">
                                        {{ $log->aksi }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-zinc-700 dark:text-zinc-300">
                                {{ $log->deskripsi }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-zinc-400 text-xs">
                                Tidak ada rekaman log aktivitas yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="pt-3">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
