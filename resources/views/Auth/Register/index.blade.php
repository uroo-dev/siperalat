@extends('Auth.Layout.index')

@section('title', 'Registrasi Peminjam Baru')

@section('content')
<div class="glass-panel rounded-3xl p-8 sm:p-10 shadow-2xl border border-black/5 dark:border-white/10">
    <!-- Brand -->
    <a href="{{ url('/') }}" class="inline-flex items-center gap-3">
        <span class="inline-flex items-center justify-center w-11 h-11 rounded-2xl bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-900 shadow-lg font-black">
            <i data-lucide="user-plus" class="w-5 h-5"></i>
        </span>
        <div>
            <span class="text-xl font-extrabold tracking-tight text-zinc-900 dark:text-white block leading-none">SIPERALAT</span>
            <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">Registrasi Peminjam</span>
        </div>
    </a>

    <!-- Header Text -->
    <h1 class="mt-7 text-2xl font-extrabold tracking-tight text-zinc-900 dark:text-white">
        Daftar Akun Peminjam
    </h1>
    <p class="mt-1 text-xs sm:text-sm text-zinc-500 dark:text-zinc-400">
        Buat akun peminjam untuk melakukan permohonan peminjaman alat laboratorium.
    </p>

    <!-- Error Message Alert -->
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

    <!-- Register Form -->
    <form action="{{ route('register.proses') }}" method="POST" class="mt-6 space-y-4">
        @csrf

        <!-- Hidden Role: Peminjam -->
        <input type="hidden" name="role" value="peminjam">

        <!-- Username -->
        <div>
            <label for="username" class="block text-xs font-bold mb-1.5 text-zinc-700 dark:text-zinc-300">
                Username Akun <span class="text-rose-500">*</span>
            </label>
            <div class="u-form-wrap">
                <i data-lucide="at-sign" class="u-ic-l w-4 h-4"></i>
                <input id="username" name="username" type="text" required class="u-field" placeholder="Contoh: peminjam_lab" value="{{ old('username') }}" autofocus autocomplete="username" />
            </div>
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-xs font-bold mb-1.5 text-zinc-700 dark:text-zinc-300">
                Alamat Email <span class="text-rose-500">*</span>
            </label>
            <div class="u-form-wrap">
                <i data-lucide="mail" class="u-ic-l w-4 h-4"></i>
                <input id="email" name="email" type="email" required class="u-field" placeholder="Contoh: user@gmail.com" value="{{ old('email') }}" autocomplete="email" />
            </div>
        </div>

        <!-- Password -->
        <div>
            <label for="passInput" class="block text-xs font-bold mb-1.5 text-zinc-700 dark:text-zinc-300">
                Kata Sandi <span class="text-rose-500">*</span>
            </label>
            <div class="u-form-wrap">
                <i data-lucide="lock" class="u-ic-l w-4 h-4"></i>
                <input id="passInput" name="password" type="password" required class="u-field pr-12" placeholder="••••••••" autocomplete="new-password" />
                <button type="button" onclick="togglePasswordVisibility('passInput', 'eyeIcon1')" class="u-ic-r p-2 text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200 transition-colors" aria-label="Tampilkan kata sandi">
                    <i id="eyeIcon1" data-lucide="eye" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="u-btn u-btn-primary w-full py-3 mt-4 text-sm font-bold shadow-xl">
            <i data-lucide="user-plus" class="w-4 h-4"></i> Buat Akun Peminjam
        </button>
    </form>

    <!-- Login Link -->
    <div class="mt-6 pt-5 border-t border-black/5 dark:border-white/10 text-center text-xs text-zinc-500 dark:text-zinc-400 space-y-1">
        <div>
            Sudah memiliki akun?
            <a href="{{ route('login') }}" class="font-bold text-zinc-900 dark:text-white hover:underline">
                Masuk di Sini
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
