<!DOCTYPE html>
<html lang="id" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - SIPERALAT</title>

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

        #sidebar {
            width: 16rem;
            transition: transform .28s cubic-bezier(.16, 1, .3, 1), width .28s cubic-bezier(.16, 1, .3, 1)
        }

        #sidebar.collapsed {
            width: 4.75rem
        }

        #sidebar .nav-label {
            transition: opacity .15s ease, width .15s ease
        }

        #sidebar.collapsed .nav-label {
            opacity: 0;
            width: 0;
            overflow: hidden;
            white-space: nowrap
        }

        #sidebar.collapsed .nav-link {
            justify-content: center;
            padding-left: 0;
            padding-right: 0
        }

        #sidebar.collapsed .group-title {
            justify-content: center;
            padding-left: 0;
            padding-right: 0
        }

        #sidebar.collapsed .logo-text {
            display: none
        }

        #sidebar.collapsed .user-info {
            display: none
        }

        @media (max-width: 767px) {
            #sidebar {
                position: fixed;
                top: 1rem;
                bottom: 1rem;
                left: 1rem;
                z-index: 50;
                transform: translateX(-130%);
            }

            #sidebar.open {
                transform: translateX(0);
            }
        }

        /* Buttons & Inputs */
        .u-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            font-size: 13px;
            font-weight: 600;
            padding: 9px 16px;
            border-radius: 11px;
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
            color: #fff
        }

        .dark .u-btn-primary {
            background: #fafafa;
            color: #18181b
        }

        .u-btn-primary:hover {
            opacity: .88
        }

        .u-btn-ghost {
            background: rgba(0, 0, 0, .05);
            color: #3f3f46
        }

        .dark .u-btn-ghost {
            background: rgba(255, 255, 255, .08);
            color: #e4e4e7
        }

        .u-btn-ghost:hover {
            background: rgba(0, 0, 0, .09);
            color: #18181b
        }

        .dark .u-btn-ghost:hover {
            background: rgba(255, 255, 255, .14);
            color: #fafafa
        }

        .u-btn-danger {
            background: #dc2626;
            color: #fff
        }

        .u-btn-danger:hover {
            background: #b91c1c
        }

        .u-btn-success {
            background: #059669;
            color: #fff
        }

        .u-btn-success:hover {
            background: #047857
        }

        .u-btn-sm {
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 9px
        }

        .u-field-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #52525b
        }

        .dark .u-field-label {
            color: #a1a1aa
        }

        .u-field-label .u-req {
            color: #dc2626
        }

        .dark .u-field-label .u-req {
            color: #f87171
        }

        .u-input,
        .u-select,
        .u-textarea {
            width: 100%;
            padding: 9px 12px;
            border-radius: 11px;
            font-size: 13.5px;
            color: #18181b;
            background: rgba(255, 255, 255, .6);
            border: 1px solid rgba(0, 0, 0, .14);
            transition: border-color .15s ease, box-shadow .15s ease;
            outline: none;
        }

        .dark .u-input,
        .dark .u-select,
        .dark .u-textarea {
            color: #fafafa;
            background: rgba(255, 255, 255, .05);
            border-color: rgba(255, 255, 255, .14);
        }

        .dark .u-select option {
            background: #18181b;
            color: #fafafa;
        }

        .u-input:focus,
        .u-select:focus,
        .u-textarea:focus {
            border-color: rgba(0, 0, 0, .55);
            box-shadow: 0 0 0 3px rgba(0, 0, 0, .05);
        }

        .dark .u-input:focus,
        .dark .u-select:focus,
        .dark .u-textarea:focus {
            border-color: rgba(255, 255, 255, .55);
            box-shadow: 0 0 0 3px rgba(255, 255, 255, .05);
        }

        /* Modal classes */
        .modal-container {
            position: fixed;
            inset: 0;
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            opacity: 0;
            pointer-events: none;
            transition: opacity .25s ease;
        }

        .modal-container.active {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .45);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
        }

        .modal-panel {
            position: relative;
            z-index: 1000;
            width: 100%;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            border-radius: 20px;
            transform: translateY(12px) scale(.96);
            transition: transform .28s cubic-bezier(.16, 1, .3, 1);
            overflow: hidden;
        }

        .modal-container.active .modal-panel {
            transform: translateY(0) scale(1);
        }

        .modal-sm {
            max-width: 380px
        }

        .modal-md {
            max-width: 520px
        }

        .modal-lg {
            max-width: 720px
        }

        .modal-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            border-bottom: 1px solid rgba(0, 0, 0, .06);
        }

        .dark .modal-head {
            border-bottom-color: rgba(255, 255, 255, .08);
        }

        .modal-body {
            padding: 20px 24px;
            overflow-y: auto;
            max-height: calc(85vh - 140px);
        }

        .modal-foot {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            padding: 16px 24px;
            border-top: 1px solid rgba(0, 0, 0, .06);
        }

        .dark .modal-foot {
            border-top-color: rgba(255, 255, 255, .08);
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

        .u-alert-success .u-alert-icon {
            background: rgba(16, 185, 129, .15);
            color: #059669;
        }

        .dark .u-alert-success .u-alert-icon {
            background: rgba(16, 185, 129, .2);
            color: #6ee7b7;
        }

        .u-alert-warning .u-alert-icon {
            background: rgba(245, 158, 11, .15);
            color: #d97706;
        }

        .dark .u-alert-warning .u-alert-icon {
            background: rgba(245, 158, 11, .2);
            color: #fcd34d;
        }

        .u-alert-danger .u-alert-icon {
            background: rgba(239, 68, 68, .15);
            color: #dc2626;
        }

        .dark .u-alert-danger .u-alert-icon {
            background: rgba(239, 68, 68, .2);
            color: #fca5a5;
        }
    </style>
    @stack('styles')
</head>

<body class="min-h-screen font-sans antialiased text-zinc-800 dark:text-zinc-100">
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

    <!-- Mobile Drawer Overlay -->
    <div id="overlay"
        class="fixed inset-0 z-40 bg-black/40 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 md:hidden"
        onclick="closeDrawer()"></div>

    <!-- MAIN ADMIN LAYOUT (Sidebar + Main Content) -->
    <div class="md:flex md:gap-4 md:p-4 min-h-screen">
        @include('Admin.Layout.sidebar')

        <!-- MAIN CONTENT AREA -->
        <main class="flex-1 p-4 md:p-6 min-w-0">
            @yield('content')
        </main>
    </div>

    <!-- Floating Theme Toggle Button -->
    <button onclick="toggleTheme()"
        class="fixed top-5 right-5 z-[80] p-2.5 rounded-full glass-panel shadow-2xl text-zinc-600 dark:text-zinc-300 md:top-[1.6rem] md:right-[1.6rem] hover:scale-105 transition-transform"
        title="Ganti Tema">
        <i data-lucide="moon" class="w-4 h-4 hidden dark:block"></i>
        <i data-lucide="sun" class="w-4 h-4 block dark:hidden"></i>
    </button>

    <!-- GLOBAL TOAST NOTIFICATION CONTAINER -->
    <div id="toast-container" class="fixed bottom-5 right-5 z-[100] flex flex-col gap-2 pointer-events-none"></div>

    @stack('modals')

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

        const sidebar = document.getElementById('sidebar');

        function toggleSidebar() {
            if (window.innerWidth >= 768) {
                sidebar.classList.toggle('collapsed');
            } else {
                sidebar.classList.toggle('open');
                document.getElementById('overlay').classList.toggle('opacity-0', !sidebar.classList.contains('open'));
                document.getElementById('overlay').classList.toggle('pointer-events-none', !sidebar.classList.contains('open'));
            }
        }

        function openDrawer() {
            sidebar.classList.add('open');
            document.getElementById('overlay').classList.remove('opacity-0', 'pointer-events-none');
        }

        function closeDrawer() {
            sidebar.classList.remove('open');
            document.getElementById('overlay').classList.add('opacity-0', 'pointer-events-none');
        }

        // Global Modal Helper
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
                lucide.createIcons();
            }
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
        }

        // Global Toast Notification Helper
        function toast(msg, icon = 'check-circle') {
            const container = document.getElementById('toast-container');
            const t = document.createElement('div');
            t.className =
                'glass-panel rounded-xl px-4 py-3 text-sm font-semibold shadow-2xl flex items-center gap-3 pointer-events-auto max-w-sm transition-all duration-300 opacity-0 translate-y-2';
            t.innerHTML = `<i data-lucide="${icon}" class="w-4 h-4 shrink-0 text-zinc-900 dark:text-zinc-50"></i><span>${msg}</span>`;
            container.appendChild(t);
            lucide.createIcons();

            setTimeout(() => {
                t.classList.remove('opacity-0', 'translate-y-2');
            }, 10);

            setTimeout(() => {
                t.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => t.remove(), 300);
            }, 3000);
        }
    </script>
    @stack('script')
</body>

</html>
