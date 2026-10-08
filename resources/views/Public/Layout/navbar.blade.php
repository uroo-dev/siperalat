<header class="sticky top-0 z-40 glass-panel rounded-none border-x-0 border-t-0 shadow-sm">
    <div class="max-w-7xl mx-auto h-16 px-4 sm:px-6 lg:px-8 flex items-center justify-between gap-4">
        <!-- Brand / Logo -->
        <a href="{{ url('/') }}" class="inline-flex items-center gap-3 shrink-0">
            <div class="w-10 h-10 rounded-2xl bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-900 grid place-items-center shadow-lg font-black text-lg">
                <i data-lucide="wrench" class="w-5 h-5"></i>
            </div>
            <div>
                <span class="text-base font-extrabold tracking-tight text-zinc-900 dark:text-white block leading-none">SIPERALAT</span>
                <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">Peminjaman Alat</span>
            </div>
        </a>

        <!-- Navigation Links -->
        <nav class="hidden md:flex items-center gap-6 text-sm font-semibold text-zinc-600 dark:text-zinc-300">
            <a href="#katalog" class="hover:text-zinc-950 dark:hover:text-white transition-colors flex items-center gap-1.5">
                <i data-lucide="package" class="w-4 h-4"></i> Katalog Alat
            </a>
            <a href="#cek-status" class="hover:text-zinc-950 dark:hover:text-white transition-colors flex items-center gap-1.5">
                <i data-lucide="search-check" class="w-4 h-4"></i> Cek Status Pinjam
            </a>
            <a href="#prosedur" class="hover:text-zinc-950 dark:hover:text-white transition-colors flex items-center gap-1.5">
                <i data-lucide="file-text" class="w-4 h-4"></i> Tata Tertib
            </a>
        </nav>

        <!-- Actions -->
        <div class="flex items-center gap-3">
            @guest
                <a href="{{ route('login') }}" class="u-btn u-btn-ghost u-btn-sm text-xs border border-black/10 dark:border-white/10">
                    <i data-lucide="log-in" class="w-3.5 h-3.5"></i> Masuk
                </a>
                <a href="{{ route('register') }}" class="u-btn u-btn-primary u-btn-sm text-xs hidden sm:inline-flex">
                    <i data-lucide="user-plus" class="w-3.5 h-3.5"></i> Daftar
                </a>
            @endguest

            @auth
                @if(in_array(Auth::user()->role, ['admin', 'petugas']))
                    <a href="{{ route('admin.dashboard') }}" class="u-btn u-btn-primary u-btn-sm text-xs">
                        <i data-lucide="shield" class="w-3.5 h-3.5"></i> Panel Admin
                    </a>
                @else
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-zinc-100 dark:bg-zinc-800 text-xs font-bold text-zinc-700 dark:text-zinc-200 border border-black/5 dark:border-white/10">
                        <i data-lucide="user" class="w-3.5 h-3.5 text-zinc-500"></i>
                        <span>{{ Auth::user()->username }}</span>
                    </div>
                @endif

                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="u-btn u-btn-ghost u-btn-sm text-xs text-rose-600 hover:bg-rose-500/10 dark:hover:bg-rose-500/20 flex items-center gap-1.5" title="Keluar">
                        <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                        <span>Keluar</span>
                    </button>
                </form>
            @endauth

            <!-- Theme Toggle -->
            <button onclick="toggleTheme()" class="p-2.5 rounded-xl glass-panel text-zinc-600 dark:text-zinc-300 hover:bg-black/5 dark:hover:bg-white/10 transition-colors" title="Toggle Tema">
                <i data-lucide="moon" class="w-4 h-4 hidden dark:block"></i>
                <i data-lucide="sun" class="w-4 h-4 block dark:hidden"></i>
            </button>
        </div>
    </div>
</header>
