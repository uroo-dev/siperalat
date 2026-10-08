@extends('Public.Layout.index')

@section('title', 'Katalog & Pengajuan Peminjaman Alat')

@section('content')
<div class="space-y-10">

    <!-- HERO SECTION -->
    <section class="glass-panel rounded-3xl p-6 sm:p-10 relative overflow-hidden shadow-2xl border border-black/5 dark:border-white/10">
        <div class="max-w-3xl relative z-10 space-y-4">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-zinc-900/5 dark:bg-white/10 border border-black/5 dark:border-white/10 text-xs font-bold text-zinc-700 dark:text-zinc-300">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Sistem Peminjaman Alat Terpadu & Digital
            </div>

            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight text-zinc-900 dark:text-white leading-[1.15]">
                Peminjaman Alat Laboratorium & Bengkel Jadi Lebih Cepat.
            </h1>

            <p class="text-sm sm:text-base text-zinc-600 dark:text-zinc-300 leading-relaxed max-w-2xl">
                Cari alat yang kamu butuhkan, cek ketersediaan stok secara real-time, dan ajukan peminjaman secara mandiri tanpa birokrasi rumit.
            </p>

            <div class="flex flex-wrap items-center gap-3 pt-2">
                <a href="#katalog" class="u-btn u-btn-primary py-3 px-6 text-sm">
                    <i data-lucide="package-search" class="w-4 h-4"></i> Jelajahi Katalog
                </a>
                <a href="#cek-status" class="u-btn u-btn-ghost py-3 px-6 text-sm">
                    <i data-lucide="search-check" class="w-4 h-4"></i> Lacak Status Pinjam
                </a>
                <button onclick="openModal('modal-terms')" class="u-btn u-btn-ghost py-3 px-4 text-xs text-zinc-500">
                    <i data-lucide="help-circle" class="w-3.5 h-3.5"></i> Syarat & Ketentuan
                </button>
            </div>
        </div>
    </section>

    <!-- ALERT COMPONENT EXAMPLES -->
    <section class="space-y-4">
        <!-- Info Alert -->
        <div class="u-alert u-alert-info">
            <div class="u-alert-icon">
                <i data-lucide="info" class="w-5 h-5"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="u-alert-title text-zinc-900 dark:text-zinc-100">Informasi Jam Layanan Pengambilan & Pengembalian</h4>
                <p class="u-alert-desc">
                    Layanan operasional lab dibuka Senin - Jumat pukul <strong>08:00 - 16:00 WIB</strong>. Peminjaman di luar jam operasional wajib melampirkan surat izin khusus dari koordinator.
                </p>
            </div>
            <button onclick="this.closest('.u-alert').remove()" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 p-1">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <!-- Warning Alert -->
        <div class="u-alert u-alert-warning">
            <div class="u-alert-icon">
                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="u-alert-title text-zinc-900 dark:text-zinc-100">Pemberitahuan Kalibrasi Rutin</h4>
                <p class="u-alert-desc">
                    Alat kategori <strong>Oscilloscope</strong> dan <strong>Total Station</strong> akan menjalani kalibrasi berkala pada akhir pekan ini. Pastikan pengembalian tepat waktu.
                </p>
            </div>
            <button onclick="this.closest('.u-alert').remove()" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 p-1">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
    </section>

    <!-- SEARCH & FILTER SECTION -->
    <section id="katalog" class="space-y-6 pt-4">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-zinc-400 dark:text-zinc-500">Katalog Peralatan</p>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-zinc-900 dark:text-white mt-1">
                    Daftar Alat Siap Pakai
                </h2>
                <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">
                    Pilih alat untuk keperluan praktikum, penelitian, workshop, atau event.
                </p>
            </div>

            <!-- Search box -->
            <div class="w-full md:w-80 relative">
                <i data-lucide="search" class="w-4 h-4 text-zinc-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                <input type="text" id="searchInput" placeholder="Cari nama alat / kode..." onkeyup="filterTools()" class="u-input pl-10 pr-4 text-sm" />
            </div>
        </div>

        <!-- Category Chips -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none" id="categoryChips">
            <button onclick="setCategoryFilter('all', this)" class="category-chip px-4 py-2 rounded-xl text-xs font-bold transition-all bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 shadow-md">
                Semua Kategori (6)
            </button>
            <button onclick="setCategoryFilter('elektronika', this)" class="category-chip px-4 py-2 rounded-xl text-xs font-bold transition-all bg-black/5 dark:bg-white/5 text-zinc-600 dark:text-zinc-400 hover:bg-black/10 dark:hover:bg-white/10">
                Elektronika & IoT
            </button>
            <button onclick="setCategoryFilter('alat-ukur', this)" class="category-chip px-4 py-2 rounded-xl text-xs font-bold transition-all bg-black/5 dark:bg-white/5 text-zinc-600 dark:text-zinc-400 hover:bg-black/10 dark:hover:bg-white/10">
                Alat Ukur & Uji
            </button>
            <button onclick="setCategoryFilter('multimedia', this)" class="category-chip px-4 py-2 rounded-xl text-xs font-bold transition-all bg-black/5 dark:bg-white/5 text-zinc-600 dark:text-zinc-400 hover:bg-black/10 dark:hover:bg-white/10">
                Multimedia & Dokumentasi
            </button>
            <button onclick="setCategoryFilter('fabrikasi', this)" class="category-chip px-4 py-2 rounded-xl text-xs font-bold transition-all bg-black/5 dark:bg-white/5 text-zinc-600 dark:text-zinc-400 hover:bg-black/10 dark:hover:bg-white/10">
                Fabrikasi & 3D Print
            </button>
        </div>

        <!-- TOOL CATALOG CARDS GRID -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="toolsGrid">

            <!-- Tool Item 1 -->
            <div class="tool-card glass-panel rounded-2xl p-5 flex flex-col justify-between hover:shadow-2xl transition-all duration-300 border border-black/5 dark:border-white/10"
                data-name="Digital Storage Oscilloscope Rigol DS1054Z" data-category="alat-ukur">
                <div class="space-y-4">
                    <div class="relative h-44 rounded-xl bg-gradient-to-br from-zinc-100 to-zinc-200 dark:from-zinc-800 dark:to-zinc-900 grid place-items-center overflow-hidden">
                        <div class="text-zinc-400 dark:text-zinc-500 flex flex-col items-center gap-2">
                            <i data-lucide="activity" class="w-12 h-12 stroke-[1.5]"></i>
                            <span class="text-xs font-bold tracking-wide">OSC-0102</span>
                        </div>
                        <span class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                            Tersedia (3 Unit)
                        </span>
                        <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-black/20 text-white backdrop-blur-md">
                            Alat Ukur
                        </span>
                    </div>

                    <div>
                        <h3 class="font-extrabold text-base text-zinc-900 dark:text-white leading-snug">
                            Oscilloscope Rigol DS1054Z 50MHz
                        </h3>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 line-clamp-2">
                            4-Channel Digital Oscilloscope dengan bandwidth 50MHz, 1 GSa/s real-time sample rate, dan layar 7 inch WVGA.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-[11px] font-semibold text-zinc-500 dark:text-zinc-400 bg-black/5 dark:bg-white/5 p-2.5 rounded-xl">
                        <div>Lokasi: <span class="text-zinc-800 dark:text-zinc-200">Rak Lab E-02</span></div>
                        <div>Kondisi: <span class="text-emerald-600 dark:text-emerald-400">Sangat Baik</span></div>
                    </div>
                </div>

                <div class="flex items-center gap-2 mt-5 pt-4 border-t border-black/5 dark:border-white/10">
                    <button onclick="showToolDetail('Oscilloscope Rigol DS1054Z', 'OSC-0102', 'Alat Ukur', '3 Unit Tersedia', 'Rak Lab E-02', '4 Channel, 50MHz Bandwidth, 1GSa/s, UltraVision Technology.')" class="u-btn u-btn-ghost u-btn-sm flex-1">
                        <i data-lucide="info" class="w-3.5 h-3.5"></i> Detail
                    </button>
                    <button onclick="openBorrowModal('Oscilloscope Rigol DS1054Z', 'OSC-0102', 3)" class="u-btn u-btn-primary u-btn-sm flex-1">
                        <i data-lucide="hand-metal" class="w-3.5 h-3.5"></i> Pinjam
                    </button>
                </div>
            </div>

            <!-- Tool Item 2 -->
            <div class="tool-card glass-panel rounded-2xl p-5 flex flex-col justify-between hover:shadow-2xl transition-all duration-300 border border-black/5 dark:border-white/10"
                data-name="3D Printer Creality Ender 3 V2" data-category="fabrikasi">
                <div class="space-y-4">
                    <div class="relative h-44 rounded-xl bg-gradient-to-br from-zinc-100 to-zinc-200 dark:from-zinc-800 dark:to-zinc-900 grid place-items-center overflow-hidden">
                        <div class="text-zinc-400 dark:text-zinc-500 flex flex-col items-center gap-2">
                            <i data-lucide="printer" class="w-12 h-12 stroke-[1.5]"></i>
                            <span class="text-xs font-bold tracking-wide">3DP-0021</span>
                        </div>
                        <span class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                            Tersedia (2 Unit)
                        </span>
                        <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-black/20 text-white backdrop-blur-md">
                            Fabrikasi
                        </span>
                    </div>

                    <div>
                        <h3 class="font-extrabold text-base text-zinc-900 dark:text-white leading-snug">
                            3D Printer Creality Ender 3 V2
                        </h3>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 line-clamp-2">
                            Mesin cetak 3D FDM dengan silent motherboard 32-bit, carborundum glass platform, volume cetak 220x220x250mm.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-[11px] font-semibold text-zinc-500 dark:text-zinc-400 bg-black/5 dark:bg-white/5 p-2.5 rounded-xl">
                        <div>Lokasi: <span class="text-zinc-800 dark:text-zinc-200">Ruang Fablab F-01</span></div>
                        <div>Kondisi: <span class="text-emerald-600 dark:text-emerald-400">Siap Pakai</span></div>
                    </div>
                </div>

                <div class="flex items-center gap-2 mt-5 pt-4 border-t border-black/5 dark:border-white/10">
                    <button onclick="showToolDetail('3D Printer Creality Ender 3 V2', '3DP-0021', 'Fabrikasi', '2 Unit Tersedia', 'Ruang Fablab F-01', 'Bed 220x220x250, Nozzle 0.4mm, Filament PLA/ABS support.')" class="u-btn u-btn-ghost u-btn-sm flex-1">
                        <i data-lucide="info" class="w-3.5 h-3.5"></i> Detail
                    </button>
                    <button onclick="openBorrowModal('3D Printer Creality Ender 3 V2', '3DP-0021', 2)" class="u-btn u-btn-primary u-btn-sm flex-1">
                        <i data-lucide="hand-metal" class="w-3.5 h-3.5"></i> Pinjam
                    </button>
                </div>
            </div>

            <!-- Tool Item 3 -->
            <div class="tool-card glass-panel rounded-2xl p-5 flex flex-col justify-between hover:shadow-2xl transition-all duration-300 border border-black/5 dark:border-white/10"
                data-name="Kamera DSLR Canon EOS 90D + Kit Lens" data-category="multimedia">
                <div class="space-y-4">
                    <div class="relative h-44 rounded-xl bg-gradient-to-br from-zinc-100 to-zinc-200 dark:from-zinc-800 dark:to-zinc-900 grid place-items-center overflow-hidden">
                        <div class="text-zinc-400 dark:text-zinc-500 flex flex-col items-center gap-2">
                            <i data-lucide="camera" class="w-12 h-12 stroke-[1.5]"></i>
                            <span class="text-xs font-bold tracking-wide">CAM-0044</span>
                        </div>
                        <span class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-500/15 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                            Sisa 1 Unit
                        </span>
                        <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-black/20 text-white backdrop-blur-md">
                            Multimedia
                        </span>
                    </div>

                    <div>
                        <h3 class="font-extrabold text-base text-zinc-900 dark:text-white leading-snug">
                            Kamera Canon EOS 90D + 18-135mm
                        </h3>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 line-clamp-2">
                            Kamera DSLR 32.5MP APS-C, perekaman video 4K tanpa crop, Dual Pixel CMOS AF untuk kebutuhan dokumentasi acara.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-[11px] font-semibold text-zinc-500 dark:text-zinc-400 bg-black/5 dark:bg-white/5 p-2.5 rounded-xl">
                        <div>Lokasi: <span class="text-zinc-800 dark:text-zinc-200">Lemari AV-01</span></div>
                        <div>Kondisi: <span class="text-emerald-600 dark:text-emerald-400">Normal 100%</span></div>
                    </div>
                </div>

                <div class="flex items-center gap-2 mt-5 pt-4 border-t border-black/5 dark:border-white/10">
                    <button onclick="showToolDetail('Kamera Canon EOS 90D', 'CAM-0044', 'Multimedia', '1 Unit Tersedia', 'Lemari AV-01', 'Kelengkapan: Tas kamera, Charger baterai LP-E6N, SD Card 64GB Extreme Pro, Strap.')" class="u-btn u-btn-ghost u-btn-sm flex-1">
                        <i data-lucide="info" class="w-3.5 h-3.5"></i> Detail
                    </button>
                    <button onclick="openBorrowModal('Kamera Canon EOS 90D', 'CAM-0044', 1)" class="u-btn u-btn-primary u-btn-sm flex-1">
                        <i data-lucide="hand-metal" class="w-3.5 h-3.5"></i> Pinjam
                    </button>
                </div>
            </div>

            <!-- Tool Item 4 -->
            <div class="tool-card glass-panel rounded-2xl p-5 flex flex-col justify-between hover:shadow-2xl transition-all duration-300 border border-black/5 dark:border-white/10"
                data-name="Fusion Splicer Fiber Optik Fujikura 88S" data-category="elektronika">
                <div class="space-y-4">
                    <div class="relative h-44 rounded-xl bg-gradient-to-br from-zinc-100 to-zinc-200 dark:from-zinc-800 dark:to-zinc-900 grid place-items-center overflow-hidden">
                        <div class="text-zinc-400 dark:text-zinc-500 flex flex-col items-center gap-2">
                            <i data-lucide="cable" class="w-12 h-12 stroke-[1.5]"></i>
                            <span class="text-xs font-bold tracking-wide">FIB-0012</span>
                        </div>
                        <span class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                            Tersedia (2 Unit)
                        </span>
                        <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-black/20 text-white backdrop-blur-md">
                            Elektronika
                        </span>
                    </div>

                    <div>
                        <h3 class="font-extrabold text-base text-zinc-900 dark:text-white leading-snug">
                            Fusion Splicer Fujikura 88S Core Alignment
                        </h3>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 line-clamp-2">
                            Penyambung kabel fiber optik presisi tinggi dengan waktu splicing 7 detik dan waktu pemanasan pelindung 13 detik.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-[11px] font-semibold text-zinc-500 dark:text-zinc-400 bg-black/5 dark:bg-white/5 p-2.5 rounded-xl">
                        <div>Lokasi: <span class="text-zinc-800 dark:text-zinc-200">Lab Jaringan J-03</span></div>
                        <div>Kondisi: <span class="text-emerald-600 dark:text-emerald-400">Terkalibrasi</span></div>
                    </div>
                </div>

                <div class="flex items-center gap-2 mt-5 pt-4 border-t border-black/5 dark:border-white/10">
                    <button onclick="showToolDetail('Fusion Splicer Fujikura 88S', 'FIB-0012', 'Elektronika', '2 Unit Tersedia', 'Lab Jaringan J-03', 'Termasuk Cleaver CT50, battery pack, AC adaptor, hard carrying case.')" class="u-btn u-btn-ghost u-btn-sm flex-1">
                        <i data-lucide="info" class="w-3.5 h-3.5"></i> Detail
                    </button>
                    <button onclick="openBorrowModal('Fusion Splicer Fujikura 88S', 'FIB-0012', 2)" class="u-btn u-btn-primary u-btn-sm flex-1">
                        <i data-lucide="hand-metal" class="w-3.5 h-3.5"></i> Pinjam
                    </button>
                </div>
            </div>

            <!-- Tool Item 5 -->
            <div class="tool-card glass-panel rounded-2xl p-5 flex flex-col justify-between hover:shadow-2xl transition-all duration-300 border border-black/5 dark:border-white/10"
                data-name="Drone DJI Mavic 3 Enterprise Thermal" data-category="multimedia">
                <div class="space-y-4">
                    <div class="relative h-44 rounded-xl bg-gradient-to-br from-zinc-100 to-zinc-200 dark:from-zinc-800 dark:to-zinc-900 grid place-items-center overflow-hidden">
                        <div class="text-zinc-400 dark:text-zinc-500 flex flex-col items-center gap-2">
                            <i data-lucide="navigation" class="w-12 h-12 stroke-[1.5]"></i>
                            <span class="text-xs font-bold tracking-wide">DRN-0005</span>
                        </div>
                        <span class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-500/15 text-rose-600 dark:text-rose-400 border border-rose-500/20">
                            Sedang Dipinjam
                        </span>
                        <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-black/20 text-white backdrop-blur-md">
                            Multimedia
                        </span>
                    </div>

                    <div>
                        <h3 class="font-extrabold text-base text-zinc-900 dark:text-white leading-snug">
                            Drone DJI Mavic 3 Enterprise
                        </h3>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 line-clamp-2">
                            Drone pemetaan udara dengan kamera mekanik shutter 56x hybrid zoom dan modul RTK untuk akurasi level sentimeter.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-[11px] font-semibold text-zinc-500 dark:text-zinc-400 bg-black/5 dark:bg-white/5 p-2.5 rounded-xl">
                        <div>Lokasi: <span class="text-zinc-800 dark:text-zinc-200">Gudang Inventaris</span></div>
                        <div>Status: <span class="text-amber-500">Kembali tgl 12 Okt</span></div>
                    </div>
                </div>

                <div class="flex items-center gap-2 mt-5 pt-4 border-t border-black/5 dark:border-white/10">
                    <button onclick="showToolDetail('Drone DJI Mavic 3 Enterprise', 'DRN-0005', 'Multimedia', '0 Unit (Dipinjam)', 'Gudang Inventaris', 'Sedang dalam peminjaman oleh Tim Robotika s/d 12 Oktober.')" class="u-btn u-btn-ghost u-btn-sm flex-1">
                        <i data-lucide="info" class="w-3.5 h-3.5"></i> Detail
                    </button>
                    <button disabled class="u-btn u-btn-ghost u-btn-sm flex-1 opacity-50 cursor-not-allowed">
                        <i data-lucide="lock" class="w-3.5 h-3.5"></i> Dipinjam
                    </button>
                </div>
            </div>

            <!-- Tool Item 6 -->
            <div class="tool-card glass-panel rounded-2xl p-5 flex flex-col justify-between hover:shadow-2xl transition-all duration-300 border border-black/5 dark:border-white/10"
                data-name="Digital Multimeter Fluke 87V Industrial" data-category="alat-ukur">
                <div class="space-y-4">
                    <div class="relative h-44 rounded-xl bg-gradient-to-br from-zinc-100 to-zinc-200 dark:from-zinc-800 dark:to-zinc-900 grid place-items-center overflow-hidden">
                        <div class="text-zinc-400 dark:text-zinc-500 flex flex-col items-center gap-2">
                            <i data-lucide="zap" class="w-12 h-12 stroke-[1.5]"></i>
                            <span class="text-xs font-bold tracking-wide">DMM-0019</span>
                        </div>
                        <span class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                            Tersedia (5 Unit)
                        </span>
                        <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-black/20 text-white backdrop-blur-md">
                            Alat Ukur
                        </span>
                    </div>

                    <div>
                        <h3 class="font-extrabold text-base text-zinc-900 dark:text-white leading-snug">
                            Digital Multimeter Fluke 87V
                        </h3>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 line-clamp-2">
                            True-RMS industrial multimeter untuk pengukuran tegangan, arus, resistansi, frekuensi, dan termometer bawaan.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-[11px] font-semibold text-zinc-500 dark:text-zinc-400 bg-black/5 dark:bg-white/5 p-2.5 rounded-xl">
                        <div>Lokasi: <span class="text-zinc-800 dark:text-zinc-200">Rak Lab E-01</span></div>
                        <div>Kondisi: <span class="text-emerald-600 dark:text-emerald-400">Sangat Baik</span></div>
                    </div>
                </div>

                <div class="flex items-center gap-2 mt-5 pt-4 border-t border-black/5 dark:border-white/10">
                    <button onclick="showToolDetail('Multimeter Fluke 87V', 'DMM-0019', 'Alat Ukur', '5 Unit Tersedia', 'Rak Lab E-01', 'True-RMS accuracy, CAT III 1000V / CAT IV 600V safety rated.')" class="u-btn u-btn-ghost u-btn-sm flex-1">
                        <i data-lucide="info" class="w-3.5 h-3.5"></i> Detail
                    </button>
                    <button onclick="openBorrowModal('Multimeter Fluke 87V', 'DMM-0019', 5)" class="u-btn u-btn-primary u-btn-sm flex-1">
                        <i data-lucide="hand-metal" class="w-3.5 h-3.5"></i> Pinjam
                    </button>
                </div>
            </div>

        </div>
    </section>

    <!-- SECTION CEK STATUS PEMINJAMAN MANDIRI -->
    <section id="cek-status" class="glass-panel rounded-3xl p-6 sm:p-8 border border-black/5 dark:border-white/10 space-y-6">
        <div class="max-w-2xl">
            <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-zinc-400 dark:text-zinc-500">Layanan Mandiri</p>
            <h2 class="text-2xl font-extrabold tracking-tight text-zinc-900 dark:text-white mt-1">
                Lacak Status Pengajuan Peminjaman
            </h2>
            <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                Masukkan Kode Tiket Pengajuan atau NIM/NIP Anda untuk melihat status persetujuan petugas.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 max-w-xl">
            <input type="text" id="statusTicketInput" placeholder="Contoh: PINJAM-2026-0089 atau NIM 2205356..." class="u-input flex-1" />
            <button onclick="checkBorrowStatus()" class="u-btn u-btn-primary py-2.5 px-6 shrink-0">
                <i data-lucide="search" class="w-4 h-4"></i> Cek Status
            </button>
        </div>

        <!-- Result Card (Demo Interactive) -->
        <div id="statusResultBox" class="hidden glass-panel rounded-2xl p-5 border border-emerald-500/30 bg-emerald-500/5 space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                    <span class="font-bold text-sm text-zinc-900 dark:text-white" id="resCode">PINJAM-2026-0089</span>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/15 text-emerald-600 dark:text-emerald-400" id="resStatus">
                    Disetujui - Siap Diambil
                </span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs text-zinc-600 dark:text-zinc-300 pt-2 border-t border-black/5 dark:border-white/10">
                <div>Peminjam: <strong class="text-zinc-900 dark:text-white block" id="resName">Ahmad Fauzi (2205356012)</strong></div>
                <div>Alat: <strong class="text-zinc-900 dark:text-white block" id="resTool">Oscilloscope Rigol DS1054Z (1 Unit)</strong></div>
                <div>Batas Kembali: <strong class="text-zinc-900 dark:text-white block text-amber-600 dark:text-amber-400" id="resDue">12 Okt 2026, 15:00 WIB</strong></div>
            </div>
        </div>
    </section>

    <!-- SECTION PROSEDUR & TATA TERTIB -->
    <section id="prosedur" class="space-y-4">
        <div>
            <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-zinc-400 dark:text-zinc-500">Petunjuk Peminjaman</p>
            <h2 class="text-2xl font-extrabold tracking-tight text-zinc-900 dark:text-white mt-1">
                4 Langkah Mudah Meminjam Alat
            </h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="glass-panel rounded-2xl p-5 space-y-2 border border-black/5 dark:border-white/10">
                <div class="w-8 h-8 rounded-xl bg-black/5 dark:bg-white/10 font-black text-sm flex items-center justify-center">1</div>
                <h4 class="font-bold text-sm text-zinc-900 dark:text-white">Pilih Alat</h4>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed">Pilih alat yang dibutuhkan dari katalog dan pastikan stok tersedia.</p>
            </div>

            <div class="glass-panel rounded-2xl p-5 space-y-2 border border-black/5 dark:border-white/10">
                <div class="w-8 h-8 rounded-xl bg-black/5 dark:bg-white/10 font-black text-sm flex items-center justify-center">2</div>
                <h4 class="font-bold text-sm text-zinc-900 dark:text-white">Isi Formulir</h4>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed">Lengkapi identitas, durasi pinjam, dan keperluan praktikum/kegiatan.</p>
            </div>

            <div class="glass-panel rounded-2xl p-5 space-y-2 border border-black/5 dark:border-white/10">
                <div class="w-8 h-8 rounded-xl bg-black/5 dark:bg-white/10 font-black text-sm flex items-center justify-center">3</div>
                <h4 class="font-bold text-sm text-zinc-900 dark:text-white">Verifikasi Petugas</h4>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed">Petugas memvalidasi pengajuan dalam kurun waktu maks 1x24 jam.</p>
            </div>

            <div class="glass-panel rounded-2xl p-5 space-y-2 border border-black/5 dark:border-white/10">
                <div class="w-8 h-8 rounded-xl bg-black/5 dark:bg-white/10 font-black text-sm flex items-center justify-center">4</div>
                <h4 class="font-bold text-sm text-zinc-900 dark:text-white">Ambil & Kembalikan</h4>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed">Tunjukkan bukti tiket pinjam di meja lab saat serah terima alat.</p>
            </div>
        </div>
    </section>

</div>
@endsection

@push('modals')
<!-- MODAL 1: FORM PENGAJUAN PINJAM ALAT (Medium Modal) -->
<div id="modal-pinjam" class="modal-container" role="dialog" aria-modal="true">
    <div class="modal-backdrop" onclick="closeModal('modal-pinjam')"></div>
    <div class="modal-panel modal-md glass-panel">
        <!-- Modal Header -->
        <div class="modal-head">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 grid place-items-center font-bold">
                    <i data-lucide="clipboard-pen" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white">Form Pengajuan Peminjaman</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400" id="modalToolSubtitle">Alat: Oscilloscope Rigol</p>
                </div>
            </div>
            <button onclick="closeModal('modal-pinjam')" class="p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 text-zinc-400">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <!-- Modal Body Form -->
        <form id="borrowForm" onsubmit="submitBorrowForm(event)">
            <div class="modal-body space-y-4">
                <input type="hidden" id="borrowToolName" value="" />

                <!-- Alat Info Banner -->
                <div class="p-3 rounded-xl bg-black/5 dark:bg-white/5 flex items-center justify-between text-xs font-semibold">
                    <span class="text-zinc-500 dark:text-zinc-400">Alat Dipilih:</span>
                    <span class="font-bold text-zinc-900 dark:text-white" id="selectedToolDisplay">-</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="u-field-label">Nama Lengkap <span class="u-req">*</span></label>
                        <input type="text" required placeholder="Contoh: Budi Santoso" class="u-input" />
                    </div>
                    <div>
                        <label class="u-field-label">NIM / NIP / ID <span class="u-req">*</span></label>
                        <input type="text" required placeholder="Contoh: 2205356088" class="u-input" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="u-field-label">Jurusan / Unit Kerja <span class="u-req">*</span></label>
                        <select class="u-select" required>
                            <option value="">-- Pilih Jurusan / Unit --</option>
                            <option value="Teknik Elektro">Teknik Elektro</option>
                            <option value="Teknik Informatika">Teknik Informatika</option>
                            <option value="Teknik Mesin">Teknik Mesin</option>
                            <option value="Teknik Sipil">Teknik Sipil</option>
                            <option value="Organisasi / UKM">Organisasi / UKM</option>
                        </select>
                    </div>
                    <div>
                        <label class="u-field-label">No. WhatsApp / HP <span class="u-req">*</span></label>
                        <input type="tel" required placeholder="0812xxxxxxxx" class="u-input" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="u-field-label">Jumlah Unit <span class="u-req">*</span></label>
                        <input type="number" min="1" max="5" value="1" required class="u-input" />
                    </div>
                    <div>
                        <label class="u-field-label">Tgl Mulai <span class="u-req">*</span></label>
                        <input type="date" required value="{{ date('Y-m-d') }}" class="u-input text-xs" />
                    </div>
                    <div>
                        <label class="u-field-label">Tgl Selesai <span class="u-req">*</span></label>
                        <input type="date" required value="{{ date('Y-m-d', strtotime('+2 days')) }}" class="u-input text-xs" />
                    </div>
                </div>

                <div>
                    <label class="u-field-label">Keperluan Peminjaman <span class="u-req">*</span></label>
                    <textarea rows="2" required placeholder="Tuliskan tujuan peminjaman secara jelas (misal: Praktikum Sistem Tertanam Modul 4)..." class="u-textarea"></textarea>
                </div>

                <div class="p-3 rounded-xl border border-amber-500/20 bg-amber-500/5 text-[11px] text-amber-700 dark:text-amber-400 flex items-start gap-2">
                    <i data-lucide="shield-alert" class="w-4 h-4 shrink-0 mt-0.5"></i>
                    <span>Peminjam bertanggung jawab penuh atas keutuhan dan keselamatan alat yang dipinjam.</span>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-foot">
                <button type="button" onclick="closeModal('modal-pinjam')" class="u-btn u-btn-ghost u-btn-sm">
                    Batal
                </button>
                <button type="submit" class="u-btn u-btn-primary u-btn-sm">
                    <i data-lucide="send" class="w-3.5 h-3.5"></i> Kirim Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL 2: DETAIL SPESIFIKASI ALAT (Large Modal) -->
<div id="modal-detail-alat" class="modal-container" role="dialog" aria-modal="true">
    <div class="modal-backdrop" onclick="closeModal('modal-detail-alat')"></div>
    <div class="modal-panel modal-lg glass-panel">
        <div class="modal-head">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-black/5 dark:bg-white/10 grid place-items-center">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white" id="detTitle">Detail Alat</h3>
                    <p class="text-xs text-zinc-400" id="detCode">KODE: -</p>
                </div>
            </div>
            <button onclick="closeModal('modal-detail-alat')" class="p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 text-zinc-400">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <div class="modal-body space-y-4 text-xs sm:text-sm">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 bg-black/5 dark:bg-white/5 p-4 rounded-xl">
                <div>
                    <span class="text-zinc-400 block text-[11px]">Kategori</span>
                    <strong class="text-zinc-900 dark:text-white" id="detCategory">-</strong>
                </div>
                <div>
                    <span class="text-zinc-400 block text-[11px]">Status Stok</span>
                    <strong class="text-emerald-500" id="detStock">-</strong>
                </div>
                <div>
                    <span class="text-zinc-400 block text-[11px]">Lokasi Rak</span>
                    <strong class="text-zinc-900 dark:text-white" id="detLocation">-</strong>
                </div>
                <div>
                    <span class="text-zinc-400 block text-[11px]">Kondisi Fisik</span>
                    <strong class="text-emerald-500">Baik / Terawat</strong>
                </div>
            </div>

            <div>
                <h4 class="font-bold text-xs uppercase tracking-wider text-zinc-400 mb-1">Deskripsi & Spesifikasi</h4>
                <p class="text-zinc-600 dark:text-zinc-300 leading-relaxed text-xs sm:text-sm" id="detSpecs">
                    -
                </p>
            </div>

            <div class="p-3.5 rounded-xl border border-black/5 dark:border-white/10 bg-black/5 dark:bg-white/5 space-y-2">
                <h4 class="font-bold text-xs text-zinc-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-500"></i> Kelengkapan Unit Dalam Box:
                </h4>
                <ul class="list-disc list-inside text-xs text-zinc-500 dark:text-zinc-400 space-y-1">
                    <li>1 Unit Alat Utama</li>
                    <li>Kabel Power / Adaptor Original</li>
                    <li>Probe / Aksesoris Standar Bawaan</li>
                    <li>Hardcase / Box Pelindung</li>
                </ul>
            </div>
        </div>
        <div class="modal-foot">
            <button onclick="closeModal('modal-detail-alat')" class="u-btn u-btn-ghost u-btn-sm">
                Tutup
            </button>
            <button id="detBtnBorrow" onclick="closeModal('modal-detail-alat'); openBorrowModal(document.getElementById('detTitle').innerText, document.getElementById('detCode').innerText, 1)" class="u-btn u-btn-primary u-btn-sm">
                <i data-lucide="hand-metal" class="w-3.5 h-3.5"></i> Lanjut Pinjam
            </button>
        </div>
    </div>
</div>

<!-- MODAL 3: SYARAT & KETENTUAN (Small/Medium Modal) -->
<div id="modal-terms" class="modal-container" role="dialog" aria-modal="true">
    <div class="modal-backdrop" onclick="closeModal('modal-terms')"></div>
    <div class="modal-panel modal-md glass-panel">
        <div class="modal-head">
            <h3 class="text-base font-bold text-zinc-900 dark:text-white">Tata Tertib & Syarat Peminjaman</h3>
            <button onclick="closeModal('modal-terms')" class="p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 text-zinc-400">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <div class="modal-body space-y-3 text-xs leading-relaxed text-zinc-600 dark:text-zinc-300">
            <div class="space-y-2">
                <p><strong>1. Identitas Sah:</strong> Peminjam wajib civitas akademika aktif dengan kartu identitas (KTM/KTP/NIP) yang masih berlaku.</p>
                <p><strong>2. Batas Waktu:</strong> Durasi peminjaman standar maksimal 3 hari kerja, perpanjangan wajib melapor 1 hari sebelum jatuh tempo.</p>
                <p><strong>3. Kerusakan & Kehilangan:</strong> Kerusakan yang disebabkan oleh kelalaian pemakaian menjadi tanggung jawab penuh peminjam untuk memperbaiki atau mengganti unit yang setara.</p>
                <p><strong>4. Kebersihan:</strong> Kembalikan alat dalam kondisi bersih dan tersusun rapi seperti saat serah terima pertama kali.</p>
            </div>
        </div>
        <div class="modal-foot">
            <button onclick="closeModal('modal-terms')" class="u-btn u-btn-primary u-btn-sm">
                Saya Mengerti
            </button>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    // Filter Category Chips
    function setCategoryFilter(category, btnElement) {
        document.querySelectorAll('.category-chip').forEach(btn => {
            btn.className = 'category-chip px-4 py-2 rounded-xl text-xs font-bold transition-all bg-black/5 dark:bg-white/5 text-zinc-600 dark:text-zinc-400 hover:bg-black/10 dark:hover:bg-white/10';
        });
        btnElement.className = 'category-chip px-4 py-2 rounded-xl text-xs font-bold transition-all bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 shadow-md';

        const cards = document.querySelectorAll('.tool-card');
        cards.forEach(card => {
            if (category === 'all' || card.dataset.category === category) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Filter Tool Search Input
    function filterTools() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        const cards = document.querySelectorAll('.tool-card');
        cards.forEach(card => {
            const name = card.dataset.name.toLowerCase();
            if (name.includes(query)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Open Borrow Modal with Tool Data
    function openBorrowModal(toolName, code, stock) {
        document.getElementById('borrowToolName').value = toolName;
        document.getElementById('modalToolSubtitle').innerText = `${toolName} (${code})`;
        document.getElementById('selectedToolDisplay').innerText = `${toolName} - Stok Sisa: ${stock} Unit`;
        openModal('modal-pinjam');
    }

    // Open Detail Modal
    function showToolDetail(name, code, category, stock, loc, specs) {
        document.getElementById('detTitle').innerText = name;
        document.getElementById('detCode').innerText = `KODE ALAT: ${code}`;
        document.getElementById('detCategory').innerText = category;
        document.getElementById('detStock').innerText = stock;
        document.getElementById('detLocation').innerText = loc;
        document.getElementById('detSpecs').innerText = specs;
        openModal('modal-detail-alat');
    }

    // Submit Borrow Form Dummy
    function submitBorrowForm(e) {
        e.preventDefault();
        closeModal('modal-pinjam');
        const randomCode = 'PINJAM-2026-' + Math.floor(1000 + Math.random() * 9000);
        toast(`Pengajuan Berhasil Dikirim! Kode Tiket: ${randomCode}`, 'check-circle');

        // Auto show status in check section
        document.getElementById('statusResultBox').classList.remove('hidden');
        document.getElementById('resCode').innerText = randomCode;
        document.getElementById('resStatus').innerText = 'Menunggu Persetujuan Petugas';
        document.getElementById('resStatus').className = 'px-3 py-1 rounded-full text-xs font-bold bg-amber-500/15 text-amber-600 dark:text-amber-400';
    }

    // Check Borrow Status Interactive
    function checkBorrowStatus() {
        const val = document.getElementById('statusTicketInput').value.trim();
        if (!val) {
            toast('Silakan masukkan Kode Tiket atau NIM terlebih dahulu', 'alert-circle');
            return;
        }

        const box = document.getElementById('statusResultBox');
        box.classList.remove('hidden');
        toast('Data pengajuan ditemukan', 'search');
    }
</script>
@endpush
