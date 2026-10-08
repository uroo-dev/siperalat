<aside id="sidebar" class="glass-panel rounded-2xl flex flex-col overflow-hidden shrink-0 md:sticky md:top-4 md:h-[calc(100vh-2rem)]">
    <!-- Header / Brand -->
    <div class="flex items-center gap-3 px-5 h-16 border-b border-black/5 dark:border-white/10 shrink-0">
        <div class="w-9 h-9 rounded-xl bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-900 grid place-items-center shadow-lg font-black shrink-0">
            <i data-lucide="wrench" class="w-4 h-4"></i>
        </div>
        <div class="logo-text min-w-0">
            <p class="font-extrabold text-[15px] leading-none tracking-tight text-zinc-900 dark:text-white">SIPERALAT</p>
            <p class="mt-0.5 text-[10px] font-semibold uppercase tracking-[0.18em] text-zinc-400 dark:text-zinc-500">Panel Admin</p>
        </div>
        <!-- Mobile close button -->
        <button onclick="closeDrawer()" class="ml-auto md:hidden p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 text-zinc-500">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
        <!-- Desktop collapse button -->
        <button onclick="toggleSidebar()" class="hidden md:block ml-auto p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 text-zinc-400" title="Collapse Sidebar">
            <i data-lucide="panel-left-close" class="w-4 h-4"></i>
        </button>
    </div>

    <!-- Navigation List -->
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-6">
        <!-- Group Utama -->
        <div>
            <p class="group-title flex items-center nav-label px-3 mb-2 text-[10px] font-bold uppercase tracking-[0.18em] text-zinc-400 dark:text-zinc-500">
                Utama
            </p>
            <div class="space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.dashboard') ? 'bg-zinc-900 text-zinc-50 dark:bg-zinc-50 dark:text-zinc-900 shadow-md' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 hover:bg-black/5 dark:hover:bg-white/10' }}">
                    <i data-lucide="layout-dashboard" class="w-[18px] h-[18px] shrink-0"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
                <a href="{{ route('admin.dashboard') }}#tabel-peminjaman" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 hover:bg-black/5 dark:hover:bg-white/10">
                    <i data-lucide="clipboard-list" class="w-[18px] h-[18px] shrink-0"></i>
                    <span class="nav-label flex-1">Peminjaman</span>
                    <span class="nav-label inline-flex items-center justify-center px-2 py-0.5 text-[10px] font-bold rounded-full bg-amber-500/15 text-amber-600 dark:text-amber-400">7 Baru</span>
                </a>
                <a href="{{ route('admin.dashboard') }}#tabel-alat" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 hover:bg-black/5 dark:hover:bg-white/10">
                    <i data-lucide="box" class="w-[18px] h-[18px] shrink-0"></i>
                    <span class="nav-label">Data Inventaris</span>
                </a>
            </div>
        </div>

        <!-- Group Manajemen -->
        <div>
            <p class="group-title flex items-center nav-label px-3 mb-2 text-[10px] font-bold uppercase tracking-[0.18em] text-zinc-400 dark:text-zinc-500">
                Manajemen
            </p>
            <div class="space-y-1">
                <a href="{{ route('admin.user.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.user.*') ? 'bg-zinc-900 text-zinc-50 dark:bg-zinc-50 dark:text-zinc-900 shadow-md' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 hover:bg-black/5 dark:hover:bg-white/10' }}">
                    <i data-lucide="users" class="w-[18px] h-[18px] shrink-0"></i>
                    <span class="nav-label">Kelola User Admin</span>
                </a>
                <a href="{{ route('admin.kategori.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.kategori.*') ? 'bg-zinc-900 text-zinc-50 dark:bg-zinc-50 dark:text-zinc-900 shadow-md' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 hover:bg-black/5 dark:hover:bg-white/10' }}">
                    <i data-lucide="tag" class="w-[18px] h-[18px] shrink-0"></i>
                    <span class="nav-label">Kategori Alat</span>
                </a>
            </div>
        </div>

        <!-- Group Laporan & Navigasi -->
        <div>
            <p class="group-title flex items-center nav-label px-3 mb-2 text-[10px] font-bold uppercase tracking-[0.18em] text-zinc-400 dark:text-zinc-500">
                Laporan & Sistem
            </p>
            <div class="space-y-1">
                <a href="javascript:void(0)" onclick="toast('Laporan peminjaman siap diekspor ke PDF/Excel', 'file-spreadsheet')" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 hover:bg-black/5 dark:hover:bg-white/10">
                    <i data-lucide="file-text" class="w-[18px] h-[18px] shrink-0"></i>
                    <span class="nav-label">Laporan Rekap</span>
                </a>
                <a href="{{ url('/') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 hover:bg-black/5 dark:hover:bg-white/10">
                    <i data-lucide="external-link" class="w-[18px] h-[18px] shrink-0"></i>
                    <span class="nav-label">Lihat Web Publik</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Bottom User Profile Card -->
    <div class="p-3 border-t border-black/5 dark:border-white/10 shrink-0">
        <div class="w-full flex items-center gap-3 px-3 py-2 rounded-xl bg-black/5 dark:bg-white/5">
            <span class="grid place-items-center w-8 h-8 rounded-full bg-gradient-to-br from-zinc-700 to-zinc-900 text-white text-xs font-bold shrink-0">
                {{ Auth::check() ? strtoupper(substr(Auth::user()->username, 0, 2)) : 'AD' }}
            </span>
            <div class="user-info text-left min-w-0 flex-1">
                <span class="block font-bold text-[13px] leading-tight truncate">
                    {{ Auth::check() ? Auth::user()->username : 'Admin Petugas' }}
                </span>
                <span class="block text-[11px] font-normal text-zinc-400 leading-tight truncate capitalize">
                    {{ Auth::check() ? Auth::user()->role : 'Petugas Lab' }}
                </span>
            </div>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-zinc-400 hover:text-rose-500 transition-colors p-1" title="Keluar / Logout">
                <i data-lucide="log-out" class="w-4 h-4"></i>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>
    </div>
</aside>
