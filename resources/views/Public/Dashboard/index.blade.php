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
                Cari alat yang kamu butuhkan, cek ketersediaan stok secara real-time, dan ajukan peminjaman secara langsung tanpa birokrasi rumit.
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

    <!-- NOTIFICATION ALERTS -->
    @if(session('succes_trx'))
        <div class="u-alert u-alert-success">
            <div class="u-alert-icon"><i data-lucide="check-circle" class="w-5 h-5"></i></div>
            <div class="flex-1 min-w-0">
                <h4 class="u-alert-title text-zinc-900 dark:text-zinc-100">Pengajuan Berhasil Dikirim!</h4>
                <p class="u-alert-desc font-semibold">{{ session('succes_trx') }}</p>
            </div>
            <button onclick="this.closest('.u-alert').remove()" class="text-zinc-400 hover:text-zinc-600 p-1"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
    @endif

    @if(session('error'))
        <div class="u-alert u-alert-danger">
            <div class="u-alert-icon"><i data-lucide="alert-octagon" class="w-5 h-5"></i></div>
            <div class="flex-1 min-w-0">
                <h4 class="u-alert-title text-zinc-900 dark:text-zinc-100">Gagal Mengajukan</h4>
                <p class="u-alert-desc">{{ session('error') }}</p>
            </div>
            <button onclick="this.closest('.u-alert').remove()" class="text-zinc-400 hover:text-zinc-600 p-1"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
    @endif

    @if($errors->any())
        <div class="u-alert u-alert-danger">
            <div class="u-alert-icon"><i data-lucide="alert-circle" class="w-5 h-5"></i></div>
            <div class="flex-1 min-w-0">
                <h4 class="u-alert-title text-zinc-900 dark:text-zinc-100">Terjadi Kesalahan Validasi</h4>
                <ul class="list-disc list-inside text-xs mt-1 space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            <button onclick="this.closest('.u-alert').remove()" class="text-zinc-400 hover:text-zinc-600 p-1"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
    @endif

    <!-- SEARCH & FILTER SECTION -->
    <section id="katalog" class="space-y-6 pt-4">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-zinc-400 dark:text-zinc-500">Katalog Peralatan</p>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-zinc-900 dark:text-white mt-1">
                    Daftar Alat Siap Pakai
                </h2>
                <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">
                    Pilih alat untuk keperluan praktikum, riset, proyek, atau kegiatan kampus.
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
                Semua Kategori ({{ $alats->count() }})
            </button>
            @foreach($kategoris as $kat)
                <button onclick="setCategoryFilter('{{ $kat->id_kategori }}', this)" class="category-chip px-4 py-2 rounded-xl text-xs font-bold transition-all bg-black/5 dark:bg-white/5 text-zinc-600 dark:text-zinc-400 hover:bg-black/10 dark:hover:bg-white/10">
                    {{ $kat->nama_kategori }}
                </button>
            @endforeach
        </div>

        <!-- TOOL CATALOG CARDS GRID -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="toolsGrid">
            @forelse($alats as $alt)
                <div class="tool-card glass-panel rounded-2xl p-5 flex flex-col justify-between hover:shadow-2xl transition-all duration-300 border border-black/5 dark:border-white/10"
                    data-name="{{ strtolower($alt->nama_alat) }} {{ strtolower($alt->kode_alat) }}" data-category="{{ $alt->id_kategori }}">
                    <div class="space-y-4">
                        <div class="relative h-44 rounded-xl bg-gradient-to-br from-zinc-100 to-zinc-200 dark:from-zinc-800 dark:to-zinc-900 grid place-items-center overflow-hidden">
                            @if($alt->foto)
                                <img src="{{ asset('storage/' . $alt->foto) }}" alt="{{ $alt->nama_alat }}" class="w-full h-full object-cover" />
                            @else
                                <div class="text-zinc-400 dark:text-zinc-500 flex flex-col items-center gap-2">
                                    <i data-lucide="wrench" class="w-12 h-12 stroke-[1.5]"></i>
                                    <span class="text-xs font-bold tracking-wide">{{ $alt->kode_alat }}</span>
                                </div>
                            @endif

                            @if($alt->stok_tersedia > 0)
                                <span class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                    Tersedia ({{ $alt->stok_tersedia }} Unit)
                                </span>
                            @else
                                <span class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-500/15 text-rose-600 dark:text-rose-400 border border-rose-500/20">
                                    Stok Habis
                                </span>
                            @endif

                            <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-black/40 text-white backdrop-blur-md">
                                {{ $alt->kategori ? $alt->kategori->nama_kategori : 'Umum' }}
                            </span>
                        </div>

                        <div>
                            <h3 class="font-extrabold text-base text-zinc-900 dark:text-white leading-snug">
                                {{ $alt->nama_alat }}
                            </h3>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 line-clamp-2">
                                {{ $alt->deskripsi ?? 'Peralatan standar siap pakai untuk kegiatan laboratorium dan praktikum.' }}
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-[11px] font-semibold text-zinc-500 dark:text-zinc-400 bg-black/5 dark:bg-white/5 p-2.5 rounded-xl">
                            <div>Tarif: <span class="text-zinc-900 dark:text-zinc-100 font-bold font-mono">Rp {{ number_format($alt->harga_sewa, 0, ',', '.') }}/hari</span></div>
                            <div>Total Stok: <span class="text-zinc-800 dark:text-zinc-200 font-mono">{{ $alt->stok_total }} Unit</span></div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 mt-5 pt-4 border-t border-black/5 dark:border-white/10">
                        <button onclick="showToolDetail('{{ addslashes($alt->nama_alat) }}', '{{ $alt->kode_alat }}', '{{ $alt->kategori ? addslashes($alt->kategori->nama_kategori) : 'Umum' }}', '{{ $alt->stok_tersedia }} Unit Tersedia', '{{ number_format($alt->harga_sewa, 0, ',', '.') }}', '{{ addslashes($alt->deskripsi ?? '-') }}', {{ $alt->id_alat }})" class="u-btn u-btn-ghost u-btn-sm flex-1">
                            <i data-lucide="info" class="w-3.5 h-3.5"></i> Detail
                        </button>

                        @if($alt->stok_tersedia > 0)
                            @auth
                                <button onclick="openBorrowModal('{{ addslashes($alt->nama_alat) }}', '{{ $alt->kode_alat }}', {{ $alt->stok_tersedia }}, {{ $alt->id_alat }}, {{ $alt->harga_sewa }})" class="u-btn u-btn-primary u-btn-sm flex-1">
                                    <i data-lucide="hand-metal" class="w-3.5 h-3.5"></i> Pinjam Langsung
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="u-btn u-btn-primary u-btn-sm flex-1" title="Harap login terlebih dahulu">
                                    <i data-lucide="log-in" class="w-3.5 h-3.5"></i> Login utk Pinjam
                                </a>
                            @endauth
                        @else
                            <button disabled class="u-btn u-btn-ghost u-btn-sm flex-1 opacity-50 cursor-not-allowed">
                                <i data-lucide="lock" class="w-3.5 h-3.5"></i> Habis
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-3 py-12 text-center text-zinc-400 text-sm">
                    Belum ada alat inventaris yang tersedia di katalog saat ini.
                </div>
            @endforelse
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
                <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed">Pilih alat dari katalog di atas dan pastikan stok unit tersedia.</p>
            </div>

            <div class="glass-panel rounded-2xl p-5 space-y-2 border border-black/5 dark:border-white/10">
                <div class="w-8 h-8 rounded-xl bg-black/5 dark:bg-white/10 font-black text-sm flex items-center justify-center">2</div>
                <h4 class="font-bold text-sm text-zinc-900 dark:text-white">Isi Formulir Mandiri</h4>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed">Lengkapi identitas, nomor kontak, jadwal tanggal, dan keperluan.</p>
            </div>

            <div class="glass-panel rounded-2xl p-5 space-y-2 border border-black/5 dark:border-white/10">
                <div class="w-8 h-8 rounded-xl bg-black/5 dark:bg-white/10 font-black text-sm flex items-center justify-center">3</div>
                <h4 class="font-bold text-sm text-zinc-900 dark:text-white">Verifikasi Admin</h4>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed">Admin memverifikasi permohonan melalui panel petugas.</p>
            </div>

            <div class="glass-panel rounded-2xl p-5 space-y-2 border border-black/5 dark:border-white/10">
                <div class="w-8 h-8 rounded-xl bg-black/5 dark:bg-white/10 font-black text-sm flex items-center justify-center">4</div>
                <h4 class="font-bold text-sm text-zinc-900 dark:text-white">Ambil & Kembalikan</h4>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed">Tunjukkan kode tiket transaksi di meja laboratorium saat pengambilan alat.</p>
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
        <div class="modal-head">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 grid place-items-center font-bold">
                    <i data-lucide="clipboard-pen" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white">Form Pengajuan Peminjaman</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400" id="modalToolSubtitle">Peralatan Terpilih</p>
                </div>
            </div>
            <button onclick="closeModal('modal-pinjam')" class="p-1.5 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 text-zinc-400">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form action="{{ route('public.pinjam.store') }}" method="POST">
            @csrf
            <input type="hidden" name="id_alat" id="inputAlatId" value="" />

            <div class="modal-body space-y-4">
                <!-- Alat Info Banner -->
                <div class="p-3 rounded-xl bg-black/5 dark:bg-white/5 flex items-center justify-between text-xs font-semibold">
                    <span class="text-zinc-500 dark:text-zinc-400">Alat Dipilih:</span>
                    <span class="font-bold text-zinc-900 dark:text-white" id="selectedToolDisplay">-</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="u-field-label">Nama Lengkap Peminjam <span class="u-req">*</span></label>
                        <input type="text" name="nama" value="{{ Auth::check() ? Auth::user()->username : old('nama') }}" required placeholder="Contoh: Budi Santoso" class="u-input" />
                    </div>
                    <div>
                        <label class="u-field-label">No. Identitas (NIM / NIP / KTP) <span class="u-req">*</span></label>
                        <input type="text" name="no_identitas" value="{{ old('no_identitas') }}" required placeholder="Contoh: 2205356088" class="u-input" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="u-field-label">Nomor WhatsApp / HP Aktif <span class="u-req">*</span></label>
                        <input type="tel" name="no_telepon" value="{{ old('no_telepon') }}" required placeholder="0812xxxxxxxx" class="u-input" />
                    </div>
                    <div>
                        <label class="u-field-label">Jumlah Unit <span class="u-req">*</span></label>
                        <input type="number" name="jumlah" id="inputJumlahUnit" min="1" max="1" value="1" required class="u-input font-mono" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="u-field-label">Tanggal Mulai Pinjam <span class="u-req">*</span></label>
                        <input type="date" name="tanggal_pinjam" value="{{ date('Y-m-d') }}" required class="u-input font-mono text-xs" />
                    </div>
                    <div>
                        <label class="u-field-label">Tanggal Rencana Kembali <span class="u-req">*</span></label>
                        <input type="date" name="tanggal_kembali_rencana" value="{{ date('Y-m-d', strtotime('+3 days')) }}" required class="u-input font-mono text-xs" />
                    </div>
                </div>

                <div>
                    <label class="u-field-label">Keperluan Peminjaman <span class="u-req">*</span></label>
                    <textarea name="keperluan" rows="2" required placeholder="Tuliskan tujuan peminjaman secara jelas (misal: Praktikum Sistem Tertanam Modul 4)..." class="u-textarea">{{ old('keperluan') }}</textarea>
                </div>

                <div>
                    <label class="u-field-label">Alamat / Instansi (Opsional)</label>
                    <input type="text" name="alamat" placeholder="Lab Komputer / Gedung B Lantai 2" class="u-input" />
                </div>
            </div>

            <div class="modal-foot">
                <button type="button" onclick="closeModal('modal-pinjam')" class="u-btn u-btn-ghost u-btn-sm">
                    Batal
                </button>
                <button type="submit" class="u-btn u-btn-primary u-btn-sm">
                    <i data-lucide="send" class="w-3.5 h-3.5"></i> Kirim Pengajuan Pinjam
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
                    <span class="text-zinc-400 block text-[11px]">Tarif Sewa</span>
                    <strong class="text-zinc-900 dark:text-white font-mono" id="detPrice">-</strong>
                </div>
                <div>
                    <span class="text-zinc-400 block text-[11px]">Kondisi Fisik</span>
                    <strong class="text-emerald-500">Baik / Siap Pakai</strong>
                </div>
            </div>

            <div>
                <h4 class="font-bold text-xs uppercase tracking-wider text-zinc-400 mb-1">Deskripsi & Spesifikasi</h4>
                <p class="text-zinc-600 dark:text-zinc-300 leading-relaxed text-xs sm:text-sm" id="detSpecs">
                    -
                </p>
            </div>
        </div>
        <div class="modal-foot">
            <button onclick="closeModal('modal-detail-alat')" class="u-btn u-btn-ghost u-btn-sm">
                Tutup
            </button>
            @auth
                <button id="detBtnBorrow" onclick="proceedBorrowFromDetail()" class="u-btn u-btn-primary u-btn-sm">
                    <i data-lucide="hand-metal" class="w-3.5 h-3.5"></i> Lanjut Pinjam
                </button>
            @else
                <a href="{{ route('login') }}" class="u-btn u-btn-primary u-btn-sm">
                    <i data-lucide="log-in" class="w-3.5 h-3.5"></i> Login untuk Pinjam
                </a>
            @endauth
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
                <p><strong>1. Identitas Sah:</strong> Peminjam wajib memiliki akun dan menyertakan identitas (KTM/KTP/NIP) yang masih berlaku.</p>
                <p><strong>2. Durasi Pinjam:</strong> Durasi peminjaman dihitung harian sesuai kesepakatan pada formulir peminjaman.</p>
                <p><strong>3. Tanggung Jawab:</strong> Kerusakan fisik atau kehilangan yang timbul akibat kelalaian pemakaian menjadi tanggung jawab penuh peminjam untuk mengganti unit yang setara.</p>
                <p><strong>4. Kebersihan:</strong> Kembalikan alat dalam kondisi bersih dan tersusun rapi seperti saat serah terima.</p>
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
    let currentDetailTool = null;

    // Filter Category Chips
    function setCategoryFilter(categoryId, btnElement) {
        document.querySelectorAll('.category-chip').forEach(btn => {
            btn.className = 'category-chip px-4 py-2 rounded-xl text-xs font-bold transition-all bg-black/5 dark:bg-white/5 text-zinc-600 dark:text-zinc-400 hover:bg-black/10 dark:hover:bg-white/10';
        });
        btnElement.className = 'category-chip px-4 py-2 rounded-xl text-xs font-bold transition-all bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 shadow-md';

        const cards = document.querySelectorAll('.tool-card');
        cards.forEach(card => {
            if (categoryId === 'all' || card.dataset.category === String(categoryId)) {
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

    // Open Borrow Modal with Real Tool Data
    function openBorrowModal(toolName, code, stock, idAlat, price) {
        const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
        if (!isLoggedIn) {
            window.location.href = "{{ route('login') }}";
            return;
        }

        document.getElementById('inputAlatId').value = idAlat;
        document.getElementById('modalToolSubtitle').innerText = `${toolName} (${code})`;
        document.getElementById('selectedToolDisplay').innerText = `${toolName} - Stok Tersedia: ${stock} Unit (Rp ${Number(price).toLocaleString('id-ID')}/hari)`;

        const inputJumlah = document.getElementById('inputJumlahUnit');
        inputJumlah.max = stock;
        inputJumlah.value = 1;

        openModal('modal-pinjam');
    }

    // Open Detail Modal
    function showToolDetail(name, code, category, stock, price, specs, idAlat) {
        currentDetailTool = { name, code, stock, idAlat, price };
        document.getElementById('detTitle').innerText = name;
        document.getElementById('detCode').innerText = `KODE ALAT: ${code}`;
        document.getElementById('detCategory').innerText = category;
        document.getElementById('detStock').innerText = stock;
        document.getElementById('detPrice').innerText = 'Rp ' + price + '/hari';
        document.getElementById('detSpecs').innerText = specs;
        openModal('modal-detail-alat');
    }

    function proceedBorrowFromDetail() {
        closeModal('modal-detail-alat');
        if (currentDetailTool) {
            openBorrowModal(
                currentDetailTool.name,
                currentDetailTool.code,
                currentDetailTool.stock,
                currentDetailTool.idAlat,
                currentDetailTool.price
            );
        }
    }
</script>
@endpush
