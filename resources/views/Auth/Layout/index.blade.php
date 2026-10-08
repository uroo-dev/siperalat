<!DOCTYPE html>
<html lang="id" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Autentikasi') - SIPERALAT</title>

    <!-- Tailwind & Lucide from template assets -->
    <script src="{{ asset('template/assets/js/tailwind.js') }}"></script>
    <script src="{{ asset('template/assets/js/lucide.js') }}"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        glass: {
                            light: 'rgba(255,255,255,0.65)',
                            'light-border': 'rgba(255,255,255,0.8)',
                            dark: 'rgba(18,18,20,0.65)',
                            'dark-border': 'rgba(255,255,255,0.1)'
                        }
                    }
                }
            }
        }
    </script>

    <link rel="stylesheet" href="{{ asset('template/assets/css/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/css/uroo.css') }}">

    <style>
        .u-bg {
            position: fixed;
            inset: 0;
            z-index: -10;
            transition: opacity .35s ease
        }

        .u-bg-light {
            background: linear-gradient(135deg, #dfe3ea, #f4f4f5)
        }

        .u-bg-dark {
            background: linear-gradient(135deg, #0a0a0b, #1a1a1e);
            opacity: 0
        }

        body.dark .u-bg-light {
            opacity: 0
        }

        body.dark .u-bg-dark {
            opacity: 1
        }

        .u-glow {
            position: fixed;
            border-radius: 9999px;
            filter: blur(90px);
            z-index: -9;
            pointer-events: none;
            background: rgba(0, 0, 0, 0.06);
            transition: background-color .35s ease
        }

        body.dark .u-glow {
            background: rgba(255, 255, 255, 0.05)
        }

        .u-glow-1 {
            width: 420px;
            height: 420px;
            top: -140px;
            left: -140px
        }

        .u-glow-2 {
            width: 520px;
            height: 520px;
            bottom: -200px;
            right: -140px
        }

        .u-glow-3 {
            width: 300px;
            height: 300px;
            top: 42%;
            left: 58%
        }

        .u-field {
            width: 100%;
            height: 48px;
            border-radius: 14px;
            background: rgba(255, 255, 255, .6);
            border: 1px solid rgba(24, 24, 27, .10);
            color: #18181b;
            font-size: 14px;
            padding: 0 46px 0 44px;
            outline: none;
            transition: border-color .2s ease, box-shadow .2s ease, background-color .2s ease;
        }

        body.dark .u-field {
            background: rgba(255, 255, 255, .05);
            border-color: rgba(255, 255, 255, .12);
            color: #f4f4f5;
        }

        .u-field::placeholder {
            color: #a1a1aa;
        }

        .u-field:focus {
            border-color: rgba(24, 24, 27, .5);
            box-shadow: 0 0 0 4px rgba(24, 24, 27, .08);
        }

        body.dark .u-field:focus {
            border-color: rgba(255, 255, 255, .55);
            box-shadow: 0 0 0 4px rgba(255, 255, 255, .08);
        }

        .u-form-wrap {
            position: relative;
        }

        .u-ic-l {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #a1a1aa;
            pointer-events: none;
        }

        .u-ic-r {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }

        .u-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            font-size: 13px;
            font-weight: 600;
            padding: 10px 18px;
            border-radius: 12px;
            transition: all .15s ease;
            cursor: pointer;
            border: 0;
            text-decoration: none;
        }

        .u-btn:hover {
            transform: translateY(-1px);
        }

        .u-btn:active {
            transform: scale(.97);
        }

        .u-btn-primary {
            background: #18181b;
            color: #fff;
        }

        .dark .u-btn-primary {
            background: #fafafa;
            color: #18181b;
        }

        .u-btn-primary:hover {
            opacity: .88;
        }

        /* Alert styling */
        .u-alert {
            display: flex;
            gap: .9rem;
            align-items: flex-start;
            padding: 1rem 1.1rem;
            border-radius: 1.1rem;
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            transition: all .25s ease;
        }

        body:not(.dark) .u-alert {
            background: rgba(255, 255, 255, .65);
            border: 1px solid rgba(255, 255, 255, .8);
            box-shadow: 0 16px 32px -16px rgba(0, 0, 0, .08);
        }

        .dark .u-alert {
            background: rgba(24, 24, 27, .65);
            border: 1px solid rgba(255, 255, 255, .08);
            box-shadow: 0 16px 32px -16px rgba(0, 0, 0, .5);
        }

        .u-alert-icon {
            flex: none;
            width: 2.25rem;
            height: 2.25rem;
            border-radius: .8rem;
            display: grid;
            place-items: center;
        }

        .u-alert-info .u-alert-icon {
            background: rgba(14, 165, 233, .15);
            color: #0284c7;
        }

        .dark .u-alert-info .u-alert-icon {
            background: rgba(14, 165, 233, .2);
            color: #38bdf8;
        }
    </style>
    @stack('styles')
</head>

<body class="min-h-screen font-sans antialiased text-zinc-800 dark:text-zinc-100 flex flex-col justify-center items-center px-4 py-8">
    <script>
        (function initTheme() {
            var saved = null;
            try {
                saved = localStorage.getItem('uroo-theme')
            } catch (e) {};
            var dark = (saved === 'dark') || (!saved && window.matchMedia && window.matchMedia(
                '(prefers-color-scheme: dark)').matches);
            var de = document.documentElement;
            if (dark) {
                de.classList.add('dark');
                de.classList.remove('light');
                if (document.body) {
                    document.body.classList.add('dark');
                    document.body.classList.remove('light');
                }
            } else {
                de.classList.remove('dark');
                de.classList.add('light');
                if (document.body) {
                    document.body.classList.remove('dark');
                    document.body.classList.add('light');
                }
            }
        })();
    </script>

    <!-- Background Lighting -->
    <div class="u-bg u-bg-light" aria-hidden="true"></div>
    <div class="u-bg u-bg-dark" aria-hidden="true"></div>
    <div class="u-glow u-glow-1" aria-hidden="true"></div>
    <div class="u-glow u-glow-2" aria-hidden="true"></div>
    <div class="u-glow u-glow-3" aria-hidden="true"></div>

    <!-- Back to Home Button -->
    <div class="fixed top-6 left-6 z-50">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 glass-panel rounded-full px-4 py-2.5 text-xs font-bold text-zinc-700 dark:text-zinc-200 hover:scale-105 transition-transform" title="Kembali ke Beranda">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Beranda Publik</span>
        </a>
    </div>

    <!-- Theme Toggle Floating -->
    <button onclick="toggleTheme()" class="fixed top-6 right-6 z-50 glass-panel rounded-full p-2.5 text-zinc-600 dark:text-zinc-200 hover:scale-110 transition-transform" title="Ganti Tema">
        <i data-lucide="moon" class="w-4 h-4 hidden dark:block"></i>
        <i data-lucide="sun" class="w-4 h-4 block dark:hidden"></i>
    </button>

    <!-- AUTH MAIN CONTENT -->
    <main class="w-full max-w-md mx-auto">
        @yield('content')
    </main>

    <!-- Base Scripts -->
    <script>
        lucide.createIcons();

        function toggleTheme() {
            document.documentElement.classList.toggle('dark');
            document.body.classList.toggle('dark');
            try {
                localStorage.setItem('uroo-theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
            } catch (e) {};
            lucide.createIcons();
        }
    </script>
    @stack('scripts')
</body>

</html>
