@extends('Auth.Layout.index')

@section('title', 'Masuk Petugas / Admin')

@section('content')
<div class="glass-panel rounded-3xl p-8 sm:p-10 shadow-2xl border border-black/5 dark:border-white/10">
    <!-- Brand -->
    <a href="{{ url('/') }}" class="inline-flex items-center gap-3">
        <span class="inline-flex items-center justify-center w-11 h-11 rounded-2xl bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-900 shadow-lg font-black">
            <i data-lucide="wrench" class="w-5 h-5"></i>
        </span>
        <div>
            <span class="text-xl font-extrabold tracking-tight text-zinc-900 dark:text-white block leading-none">SIPERALAT</span>
            <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">Panel Autentikasi</span>
        </div>
    </a>

    <!-- Header Text -->
    <h1 class="mt-7 text-2xl font-extrabold tracking-tight text-zinc-900 dark:text-white">
        Selamat Datang Petugas
    </h1>
    <p class="mt-1 text-xs sm:text-sm text-zinc-500 dark:text-zinc-400">
        Masuk untuk mengelola permohonan pinjam dan inventaris lab.
    </p>

    <!-- Success Message Alert -->
    @if(session('succes'))
        <div class="mt-4 p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-xs text-emerald-700 dark:text-emerald-400 flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>
            <span>{{ session('succes') }}</span>
        </div>
    @endif

    <!-- Error Message Alert -->
    @if(session('error'))
        <div class="mt-4 p-3 rounded-xl bg-rose-500/10 border border-rose-500/20 text-xs text-rose-700 dark:text-rose-400 flex items-center gap-2">
            <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="mt-4 p-3 rounded-xl bg-rose-500/10 border border-rose-500/20 text-xs text-rose-700 dark:text-rose-400 space-y-1">
            @foreach($errors->all() as $error)
                <div class="flex items-center gap-1.5">
                    <i data-lucide="x" class="w-3.5 h-3.5 shrink-0"></i>
                    <span>{{ $error }}</span>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Login Form -->
    <form action="{{ route('login.proses') }}" method="POST" class="mt-6 space-y-4">
        @csrf

        <!-- Username -->
        <div>
            <label for="username" class="block text-xs font-bold mb-1.5 text-zinc-700 dark:text-zinc-300">
                Username Petugas
            </label>
            <div class="u-form-wrap">
                <i data-lucide="user" class="u-ic-l w-4 h-4"></i>
                <input id="username" name="username" type="text" required class="u-field" placeholder="Masukkan username" value="{{ old('username') }}" autocomplete="username" autofocus />
            </div>
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="passInput" class="block text-xs font-bold text-zinc-700 dark:text-zinc-300">
                    Kata Sandi
                </label>
                <a href="javascript:void(0)" onclick="alert('Silakan hubungi koordinator laboratorium untuk reset password.')" class="text-[11px] font-semibold text-zinc-500 hover:text-zinc-900 dark:hover:text-white">
                    Lupa sandi?
                </a>
            </div>
            <div class="u-form-wrap">
                <i data-lucide="lock" class="u-ic-l w-4 h-4"></i>
                <input id="passInput" name="password" type="password" required class="u-field pr-12" placeholder="••••••••" autocomplete="current-password" />
                <button type="button" onclick="togglePasswordVisibility('passInput', 'eyeIcon')" class="u-ic-r p-2 text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200 transition-colors" aria-label="Tampilkan kata sandi">
                    <i id="eyeIcon" data-lucide="eye" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1">
            <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-zinc-600 dark:text-zinc-400">
                <input type="checkbox" name="remember" class="rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900" />
                <span>Ingat saya di perangkat ini</span>
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="u-btn u-btn-primary w-full py-3 mt-4 text-sm font-bold shadow-xl">
            <i data-lucide="log-in" class="w-4 h-4"></i> Masuk ke Dashboard
        </button>
    </form>

    <!-- Register Link -->
    <div class="mt-6 pt-5 border-t border-black/5 dark:border-white/10 text-center text-xs text-zinc-500 dark:text-zinc-400 space-y-1">
        <div>
            Belum punya akun admin?
            <a href="{{ route('register') }}" class="font-bold text-zinc-900 dark:text-white hover:underline">
                Daftar Petugas Baru
            </a>
        </div>
        <div>
            <a href="{{ url('/') }}" class="text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200 text-[11px] block mt-2">
                ← Kembali ke Halaman Publik
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const eye = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            eye.setAttribute('data-lucide', 'eye-off');
        } else {
            input.type = 'password';
            eye.setAttribute('data-lucide', 'eye');
        }
        lucide.createIcons();
    }
</script>
@endpush
