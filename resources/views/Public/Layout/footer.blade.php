<footer class="mt-auto border-t border-black/5 dark:border-white/10 glass-panel rounded-none border-x-0 border-b-0 py-6 text-center text-xs text-zinc-500 dark:text-zinc-400">
    <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-3">
        <p>© {{ date('Y') }} SIPERALAT — Sistem Informasi Peminjaman Alat & Inventaris.</p>
        <div class="flex items-center gap-4 font-semibold">
            <a href="#prosedur" class="hover:underline">Tata Tertib</a>
            <span>•</span>
            <a href="{{ url('/auth/login') }}" class="hover:underline">Login Petugas</a>
            <span>•</span>
            <a href="{{ url('/admin') }}" class="hover:underline">Dashboard Admin</a>
        </div>
    </div>
</footer>
