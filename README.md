# SIPERALAT (Sistem Informasi Peminjaman Peralatan)

> Dokumentasi teknis dan panduan penggunaan aplikasi Sistem Informasi Peminjaman dan Manajemen Peralatan Laboratorium & Inventaris Outdoor.

**Live Demo:** https://siperalat.uroo.my.id  
**Repository:** https://github.com/uroo-dev/siperalat  
**Status:** Production Ready  
**Versi:** 1.0.0  
**Terakhir diperbarui:** 13 Oktober 2026  

---

## 1. Ringkasan Aplikasi

### 1.1 Deskripsi

**SIPERALAT** adalah aplikasi berbasis web yang dibangun dengan Laravel 11 untuk mendigitalisasi proses peminjaman peralatan laboratorium dan perlengkapan outdoor. Aplikasi ini memungkinkan pengguna publik melihat katalog peralatan dengan gambar beresolusi tinggi, mengecek ketersediaan stok aktual, dan mengajukan peminjaman secara mandiri. Administrator dan petugas operasional dapat mengelola master kategori dan alat, memvalidasi permohonan, memproses serah terima dan pengembalian unit, mencetak nota transaksi, serta memantau log audit sistem secara menyeluruh.

### 1.2 Masalah yang Diselesaikan

- **Pencatatan Manual yang Rentan Hilang:** Menggantikan buku log fisik atau spreadsheet yang rawan inkonsistensi data, duplikasi, dan sulit diaudit.
- **Kesulitan Mengetahui Stok Nyata:** Mencegah peminjaman ganda (*double booking*) dengan sinkronisasi stok otomatis (*tersedia*, *dipinjam*, dan *rusak*).
- **Kurangnya Visibilitas Pelanggan:** Peminjam sering kali tidak mengetahui apakah unit alat tersedia atau apakah permohonan mereka telah disetujui.
- **Ketiadaan Bukti Transaksi Resmi:** Mempermudah pembuatan bukti nota peminjaman dan berita acara serah terima alat siap cetak.

### 1.3 Pengguna Aplikasi

| Role | Deskripsi | Hak Akses Utama |
|---|---|---|
| **Admin** | Administrator sistem utama | Mengelola semua master data alat, kategori, staf, pelanggan, menyetujui transaksi, cetak nota/laporan, dan monitoring log. |
| **Petugas** | Staf operasional laboratorium / gudang | Memverifikasi fisik alat, menyetujui/menolak pengajuan, memproses pengembalian, mencatat kondisi alat dan denda. |
| **Peminjam** | Pengguna akhir (Mahasiswa / Pelanggan) | Menjelajahi katalog alat, mengajukan peminjaman online, memantau status tiket transaksi mandiri. |

### 1.4 Tujuan Aplikasi

- Mengotomatiskan alur pengajuan, verifikasi, hingga pengembalian unit inventaris secara transparan.
- Menyediakan katalog interaktif lengkap dengan galeri foto alat dan informasi spesifikasi teknis.
- Menyajikan rekapitulasi laporan transaksi dan pencatatan audit log untuk akuntabilitas operasional.

---

## 2. Tech Stack

| Kategori | Teknologi |
|---|---|
| **Backend** | PHP 8.3+, Laravel 11 |
| **Database** | MariaDB 11 / MySQL 8.0 |
| **Frontend** | Blade Templates, Tailwind CSS, Lucide Icons, Vanilla JavaScript |
| **Authentication** | Laravel Session & Custom Role-Based Middleware |
| **Upload File** | Laravel Storage (`public` disk symlink) |
| **Print / Export** | Native CSS `@media print` Responsive Document Views |
| **Testing** | PHPUnit 11 |
| **Deployment** | Nginx, PHP-FPM, Linux VPS, Git |
| **Tools** | Composer, npm, Git, Artisan CLI |

---

## 3. Fitur Aplikasi

### 3.1 Autentikasi & Otorisasi
- Registrasi akun khusus peminjam baru dengan enkripsi kata sandi Bcrypt.
- Login multi-role dengan pengalihan otomatis: Admin/Petugas diarahkan ke `/dashboard`, Peminjam diarahkan ke katalog `/`.
- Pembaruan stempel waktu `last_login_at` dan pencatatan log aktivitas masuk/keluar sistem.
- Proteksi route menggunakan middleware `auth`, `guest`, dan middleware otorisasi `role.admin`.
- Fitur blokir akun: pengguna dengan status `nonaktif` dicegah masuk ke dalam sistem.

### 3.2 Katalog Publik & Peminjaman Mandiri
- Katalog alat modern dengan filter kategori instan (*category chips*) dan pencarian nama/kode alat secara realtime.
- Tampilan foto produk dengan kartu responsif, badge status stok (*Tersedia*, *Stok Habis*), dan tarif sewa per hari.
- Modal Detail Alat yang menampilkan foto resolusi tinggi, spesifikasi lengkap, dan kondisi unit.
- Formulir pengajuan peminjaman langsung dengan validasi tanggal pinjam, batas maksimum stok unit, dan kalkulasi biaya.
- Pelacakan status tiket transaksi (`TRX-XXXXXX`) secara mandiri pada section Cek Status.

### 3.3 Dashboard Administrasi & Monitoring
- Statistik ringkasan: Total Jenis Alat, Unit Siap Pakai, Transaksi Sedang Dipinjam, Menunggu Approval, dan Riwayat Pengembalian.
- Banner alert dinamis untuk mendeteksi transaksi jatuh tempo (*overdue*) dan permohonan baru yang memerlukan tindakan.
- Feed realtime Log Aktivitas Pengguna & Sistem.
- Tabel peminjaman terbaru dan inventaris alat dengan tautan cepat ke modul transaksi.

### 3.4 Manajemen Master Data (Alat & Kategori)
- CRUD Kategori Alat dilengkapi status aktif dan deskripsi.
- CRUD Master Alat lengkap dengan kode alat unik, nama, tarif sewa harian, nilai ganti rugi, dan alokasi stok.
- Dukungan unggah file foto alat (JPG, PNG, WEBP hingga 4MB) dengan penghapusan otomatis file lama saat diganti atau dihapus.
- Thumbnail foto alat ditampilkan langsung pada tabel manajemen dan modal pengeditan.

### 3.5 Transaksi Peminjaman & Pengembalian
- Alur persetujuan permohonan: status `menunggu`, `disetujui`, `dipinjam`, `ditolak`, atau `dibatalkan`.
- Penyesuaian stok unit inventaris secara otomatis saat transaksi disetujui atau dibatalkan.
- Pembuatan dan pencetakan Bukti Nota Peminjaman resmi berformat faktur standar cetak.
- Formulir pengembalian alat dengan inspeksi kondisi unit (*Baik*, *Rusak Ringan*, *Rusak Berat*).
- Penambahan stok rusak dan pemulihan stok siap pakai secara cerdas berdasarkan kondisi fisik saat dikembalikan.

### 3.6 Laporan Rekapitulasi & Log Audit
- Rekapitulasi data transaksi berdasarkan filter tanggal, jenis (peminjaman / pengembalian), dan status transaksi.
- Halaman cetak laporan resmi berformat tabel siap ekspor ke PDF via browser print.
- Pencatatan jejak audit sistem (*system audit trail*) pada setiap aktivitas penting dalam aplikasi.

---

## 4. Alur Aplikasi

### 4.1 Alur Request Laravel

```text
Browser / Pengguna
  ↓
Route (routes/web.php)
  ↓
Middleware (auth / role.admin / guest)
  ↓
Controller (Request Validation)
  ↓
Model / Eloquent ORM & Storage Disk
  ↓
Database (MariaDB)
  ↓
Controller (Data Collection / Compact)
  ↓
Blade View Engine
  ↓
Browser Rendering (HTML / Tailwind CSS / Lucide)
```

### 4.2 Alur Transaksi Peminjaman Publik

```text
Peminjam memilih alat dari katalog publik (/)
  ↓
Peminjam menekan tombol "Pinjam Langsung"
  ↓
Sistem memvalidasi login (jika belum login, dialihkan ke /login)
  ↓
Peminjam melengkapi formulir (identitas, jadwal pinjam, keperluan)
  ↓
Sistem memverifikasi ketersediaan stok aktual
  ↓
Sistem membuat transaksi status "menunggu" & menerbitkan kode tiket TRX-XXXXXX
  ↓
Admin / Petugas memeriksa pengajuan di panel admin (/admin/peminjaman)
  ↓
Admin menyetujui transaksi → Stok unit dipotong otomatis menjadi "dipinjam"
  ↓
Peminjam mengambil alat fisik dengan menunjukkan kode tiket & bukti nota
```

### 4.3 Alur Pengembalian Alat

```text
Peminjam mengembalikan alat ke laboratorium
  ↓
Petugas membuka menu Pengembalian (/admin/pengembalian)
  ↓
Petugas memilih nomor transaksi peminjaman aktif
  ↓
Petugas memeriksa kondisi fisik alat (Baik / Rusak Ringan / Rusak Berat)
  ↓
Sistem mencatat data pengembalian & memperbarui stok (tersedia / rusak)
  ↓
Status peminjaman beralih menjadi "dikembalikan" (selesai)
```

---

## 5. Arsitektur Aplikasi

### 5.1 Struktur MVC

| Layer | Lokasi | Tanggung Jawab |
|---|---|---|
| **Route** | `routes/web.php` | Mendefinisikan seluruh endpoint publik, autentikasi, dan panel admin. |
| **Middleware** | `app/Http/Middleware/RoleAdminPetugas.php` | Memproteksi akses khusus untuk pengguna dengan hak akses admin dan petugas. |
| **Controller** | `app/Http/Controllers/` | Menangani logika bisnis, alur data, validasi, dan transaksi database. |
| **Model** | `app/Models/` | Mendefinisikan entitas database, fillable, casting, dan relasi Eloquent. |
| **View** | `resources/views/` | Merender antarmuka pengguna berbasis Blade template responsif. |
| **Migration** | `database/migrations/` | Mendefinisikan skema tabel database dan integritas relasi foreign key. |
| **Seeder** | `database/seeders/DatabaseSeeder.php` | Memuat master data awal, akun demo, produk sampel, dan riwayat transaksi. |
| **Test** | `tests/Feature/` | Menguji fungsionalitas katalog, autentikasi, hak akses, dan transaksi. |

### 5.2 Keputusan Teknis

| Keputusan | Alasan |
|---|---|
| **Penyimpanan Gambar di `storage/app/public`** | Mengikuti standar keamanan Laravel; file dapat diakses publik via symlink tanpa mengekspos direktori aplikasi internal. |
| **Relasi Eloquent dengan Cascade Deletes / Constraints** | Memastikan integritas referensial data tetap konsisten antara transaksi induk dan rincian item. |
| **CSS `@media print` untuk Nota & Laporan** | Menghasilkan dokumen cetak tajam tanpa dependensi library eksternal yang berat seperti headless browser. |
| **Pencatatan Audit Trail Log Terpusat** | Setiap aksi mutasi data penting (tambah, ubah, hapus, status transaksi) otomatis tercatat di tabel `logs`. |
| **Penggunaan Tailwind Lokal & Lucide Lokal** | Menjamin aplikasi dapat berjalan sempurna di lingkungan intranet maupun offline tanpa dependensi CDN luar. |

---

## 6. Desain Database

### 6.1 Ringkasan Relasi

```text
users
 ├── peminjam (1 to 1)
 │    └── peminjaman (1 to Many)
 │         ├── detail_peminjaman (1 to Many)
 │         │    └── alat (Many to 1)
 │         └── pengembalian (1 to 1)
 │              └── detail_pengembalian (1 to Many)
 │                   └── kondisi_alat (Many to 1)

kategori
 └── alat (1 to Many)

jenis_denda
 └── denda (1 to Many)
```

### 6.2 Daftar Tabel

| Tabel | Fungsi |
|---|---|
| `users` | Menyimpan data akun pengguna dan role (`admin`, `petugas`, `peminjam`). |
| `peminjam` | Menyimpan profil identitas peminjam (NIK/NIM, telepon, alamat). |
| `kategori` | Menyimpan data kategori peralatan. |
| `kondisi_alat` | Menyimpan master parameter kelayakan kondisi unit alat fisik. |
| `alat` | Menyimpan data katalog peralatan, stok, tarif sewa, dan path foto. |
| `peminjaman` | Menyimpan header data transaksi peminjaman dan kode tiket. |
| `detail_peminjaman` | Menyimpan rincian item alat yang dipinjam dalam transaksi. |
| `pengembalian` | Menyimpan data transaksi serah terima pengembalian alat. |
| `detail_pengembalian` | Menyimpan rincian status fisik dan jumlah alat yang dikembalikan. |
| `jenis_denda` | Menyimpan konfigurasi skema perhitungan denda. |
| `denda` | Menyimpan catatan penagihan denda keterlambatan/kerusakan. |
| `logs` | Menyimpan catatan aktivitas sistem dan riwayat tindakan pengguna. |

### 6.3 Struktur Tabel Utama

#### `users`
| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | BIGINT UNSIGNED | Primary Key |
| `username` | VARCHAR(255) | Nama pengguna unik |
| `email` | VARCHAR(255) | Alamat email unik |
| `password` | VARCHAR(255) | Password terenkripsi Bcrypt |
| `role` | ENUM | `admin`, `petugas`, `peminjam` |
| `status` | ENUM | `aktif`, `nonaktif` |
| `last_login_at`| TIMESTAMP | Waktu terakhir pengguna masuk |

#### `alat`
| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id_alat` | BIGINT UNSIGNED | Primary Key |
| `id_kategori` | BIGINT UNSIGNED | Foreign Key ke `kategori` |
| `kode_alat` | VARCHAR(50) | Kode inventaris unik |
| `nama_alat` | VARCHAR(150) | Nama peralatan |
| `deskripsi` | TEXT | Keterangan spesifikasi alat |
| `harga_sewa` | DECIMAL(12,2) | Tarif sewa per hari |
| `stok_total` | INT | Jumlah total unit alat |
| `stok_tersedia` | INT | Jumlah unit siap dipinjam |
| `stok_dipinjam` | INT | Jumlah unit sedang dipinjam |
| `stok_rusak` | INT | Jumlah unit dalam perbaikan |
| `nilai_penggantian` | DECIMAL(12,2) | Biaya penggantian jika unit hilang |
| `foto` | VARCHAR(255) | Path penyimpanan file foto di storage |
| `status` | ENUM | `tersedia`, `habis`, `maintenance`, `nonaktif` |

#### `peminjaman`
| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id_peminjaman` | BIGINT UNSIGNED | Primary Key |
| `kode_transaksi` | VARCHAR(50) | Kode transaksi tiket unik (`TRX-XXXXXX`) |
| `id_peminjam` | BIGINT UNSIGNED | Foreign Key ke `peminjam` |
| `tanggal_pinjam` | DATE | Tanggal mulai pinjam |
| `tanggal_kembali_rencana` | DATE | Batas tanggal rencana kembali |
| `lama_pinjam` | INT | Durasi pinjam dalam satuan hari |
| `total_tagihan` | DECIMAL(12,2) | Total biaya sewa |
| `status` | ENUM | `menunggu`, `disetujui`, `dipinjam`, `ditolak`, `dikembalikan`, `dibatalkan` |
| `disetujui_oleh` | BIGINT UNSIGNED | ID petugas yang menyetujui transaksi |

---

## 7. Keamanan

### 7.1 Autentikasi dan Otorisasi
- Kata sandi disimpan dengan algoritma hashing satu arah Bcrypt.
- Sesi pengguna diregenerasi setiap kali login (`$request->session()->regenerate()`) untuk mencegah serangan *Session Fixation*.
- Proteksi route berbasis middleware memisahkan hak akses panel petugas dari pengguna publik.
- Akun nonaktif langsung dibatalkan sesinya dan dilarang masuk ke sistem.

### 7.2 Validasi dan Integritas Data
- Seluruh input form divalidasi ketat di sisi server (tipe data, format tanggal, rentang stok, ukuran file).
- Perlindungan bawaan CSRF (*Cross-Site Request Forgery*) aktif pada seluruh metode request `POST`, `PUT`, `PATCH`, dan `DELETE`.
- Penggunaan Eloquent ORM dengan *parameterized queries* mencegah kerentanan SQL Injection.
- Filter unggah berkas memvalidasi tipe MIME gambar (`jpeg`, `png`, `webp`) dengan batas maksimal 4MB.

### 7.3 Pengamanan Konfigurasi
- Kredensial database dan kunci enkripsi aplikasi (`APP_KEY`) terisolasi di dalam berkas `.env` dan diabaikan oleh Git.
- Tersedia template `.env.example` yang aman untuk replikasi lingkungan pengembangan.

---

## 8. Instalasi Lokal

### 8.1 Prasyarat Sistem
- PHP >= 8.2 (dengan ekstensi `pdo_mysql`, `mbstring`, `fileinfo`, `gd`, `openssl`)
- Composer >= 2.0
- MariaDB >= 10.4 atau MySQL >= 8.0
- Node.js >= 18.x dan npm
- Git

### 8.2 Langkah Instalasi

1. **Clone repository:**
   ```bash
   git clone https://github.com/uroo-dev/siperalat.git
   cd siperalat
   ```

2. **Pasang dependensi PHP:**
   ```bash
   composer install
   ```

3. **Konfigurasi Environment:**
   Salin berkas `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Konfigurasi Database:**
   Sesuaikan pengaturan koneksi database pada berkas `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=siperalat_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Hubungkan Symlink Storage:**
   ```bash
   php artisan storage:link
   ```

6. **Jalankan Migrasi dan Seeder:**
   ```bash
   php artisan migrate:fresh --seed
   ```

7. **Jalankan Server Aplikasi:**
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses melalui peramban di: `http://127.0.0.1:8000`

### 8.3 Akun Pengujian (Demo Accounts)

| Role | Username | Email | Password | Akses URL |
|---|---|---|---|---|
| **Admin** | `uroo` | `uroo@gmail.com` | `password` | `/dashboard` |
| **Petugas** | `petugas` | `petugas@gmail.com` | `password` | `/dashboard` |
| **Peminjam** | `peminjam` | `peminjam@gmail.com` | `password` | `/` (Katalog Publik) |
| **Peminjam 2** | `budi_santoso` | `budi@gmail.com` | `password` | `/` (Katalog Publik) |

> **Catatan:** Akun demo di atas diinisialisasi otomatis oleh `DatabaseSeeder`. Harap ubah kata sandi saat menggunakan aplikasi di lingkungan produksi.

---

## 9. Pengujian (Testing)

Aplikasi telah dilengkapi dengan serangkaian pengujian fitur (*Feature Tests*) menggunakan PHPUnit untuk memastikan stabilitas fungsionalitas inti.

### 9.1 Menjalankan Test Suite

```bash
php artisan test
```

### 9.2 Cakupan Pengujian

| Pengujian | Deskripsi Skenario |
|---|---|
| `test_public_home_page_can_be_rendered` | Memverifikasi katalog alat publik dapat diakses dan menampilkan nama peralatan dengan benar. |
| `test_user_can_login_as_admin` | Memverifikasi login administrator berhasil dan dialihkan ke panel dashboard. |
| `test_guest_cannot_access_admin_dashboard` | Memverifikasi tamu tanpa autentikasi dicegah mengakses panel dashboard admin. |
| `test_admin_can_access_admin_dashboard` | Memverifikasi pengguna role admin memiliki izin penuh mengakses dashboard. |
| `test_authenticated_user_can_submit_borrow_request` | Memverifikasi alur pengajuan peminjaman mandiri tersimpan ke database berstatus `menunggu`. |

---

## 10. Panduan Deployment Production

### 10.1 Persiapan Server Production
- Server Linux (Ubuntu 22.04 LTS / 24.04 LTS)
- Web Server Nginx terkonfigurasi dengan PHP 8.3-FPM
- MariaDB Server aktif
- Sertifikat SSL/TLS gratis melalui Let's Encrypt Certbot

### 10.2 Langkah Deployment

```bash
# 1. Masuk ke direktori web server
cd /var/www/siperalat

# 2. Ambil pembaruan kode terbaru
git pull origin main

# 3. Pasang dependensi production tanpa dev package
composer install --no-dev --optimize-autoloader

# 4. Jalankan migrasi database
php artisan migrate --force

# 5. Hubungkan symlink storage
php artisan storage:link

# 6. Bersihkan dan buat cache konfigurasi untuk kecepatan optimal
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Sesuaikan kepemilikan permission direktori storage dan bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### 10.3 Aturan Keselamatan Production
- Jangan pernah menjalankan `php artisan migrate:fresh` atau `db:wipe` di server produksi.
- Selalu lakukan *database backup* berkala sebelum melakukan *update* versi aplikasi.
- Pastikan opsi `APP_DEBUG=false` pada berkas `.env` server produksi.

---

## 11. Struktur Direktori Proyek

```text
siperalat/
 ├── app/
 │   ├── Http/
 │   │   ├── Controllers/        # AlatController, PeminjamanController, PublicController, dll.
 │   │   └── Middleware/         # RoleAdminPetugas.php
 │   └── Models/                 # Alat.php, Peminjaman.php, User.php, Kategori.php, dll.
 ├── config/                     # Konfigurasi aplikasi, auth, database, filesystems
 ├── database/
 │   ├── migrations/             # Skema tabel database (14 file migrasi)
 │   └── seeders/                # DatabaseSeeder.php (Master data & demo)
 ├── public/
 │   ├── index.php               # Front controller aplikasi
 │   ├── storage/                # Symlink ke storage/app/public/
 │   └── template/               # Aset UI lokal (CSS, JS, Font, Lucide Icons)
 ├── resources/
 │   └── views/
 │       ├── Admin/              # View panel admin (Alat, Kategori, Transaksi, Laporan, Log)
 │       ├── Auth/               # View login dan registrasi
 │       └── Public/             # View katalog utama dan modal peminjaman publik
 ├── routes/
 │   └── web.php                 # Definisi routing aplikasi
 ├── storage/
 │   └── app/
 │       └── public/
 │           └── alat/           # Direktori penyimpanan unggahan foto alat
 └── tests/
     └── Feature/                # Unit & Feature Test Suite
```

---

## 12. Keterbatasan Saat Ini (Known Limitations)

- Verifikasi transaksi saat ini dilakukan secara manual oleh petugas di laboratorium sebelum serah terima alat.
- Sistem notifikasi saat ini masih mengandalkan pembaruan status di web aplikasi dan belum terintegrasi dengan gateway WhatsApp API atau email SMTP pihak ketiga.
- Pembayaran sewa dilakukan secara tunai / transfer manual saat serah terima unit di tempat.

---

## 13. Rencana Pengembangan (Future Improvements)

- Integrasi Payment Gateway (Midtrans / Xendit) untuk pembayaran deposit dan sewa instan otomatis.
- Notifikasi status transaksi via WhatsApp Gateway atau Notifikasi Email real-time.
- Fitur pemindaian QR Code / Barcode pada unit alat untuk mempermudah inventarisasi fisik di laboratorium.
- Ekspor laporan rekapitulasi ke format spreadsheet Excel (`.xlsx`).

---

## 14. Riwayat Perubahan (Changelog)

### [1.0.0] - 2026-10-13

#### Added
- Rilis awal aplikasi sistem informasi peminjaman alat SIPERALAT.
- Modul Katalog Publik responsif lengkap dengan pencarian, filter kategori, dan modal detail gambar.
- Fitur unggah, simpan, perbarui, dan hapus foto alat inventaris terintegrasi dengan Laravel Storage.
- Modul transaksi peminjaman mandiri dengan kalkulasi tarif harian dan batas stok unit.
- Modul persetujuan peminjaman petugas dengan pemotongan stok dan pemulihan otomatis saat pembatalan.
- Modul cetak Bukti Nota Peminjaman dan Laporan Rekapitulasi transaksi.
- Manajemen Akun Admin, Petugas, Pelanggan, dan Kategori Alat.
- Log audit aktivitas pengguna dan sistem.
- Database seeder lengkap dengan akun demo dan peralatan contoh bergambar nyata.
- Rangkaian pengujian otomatis PHPUnit Feature Tests.

#### Fixed
- Perbaikan koneksi database MariaDB socket pada lingkungan Linux.
- Perbaikan sinkronisasi symlink storage untuk rendering gambar produk.
- Pembersihan dependensi eksternal CDN untuk keandalan pencetakan nota dan laporan.

---

## 15. Penulis & Pengembang

**Dimas Euro Dwi Prasetyo**  
Laravel Developer | UROO.DEV  

- **GitHub:** https://github.com/uroo-dev  
- **Portfolio:** https://uroo.my.id  
- **Email:** contact@uroo.my.id  
- **LinkedIn:** https://linkedin.com/in/dimas-euro  

---

*Hak Cipta © 2026 SIPERALAT - UROO.DEV. Dikembangkan untuk efisiensi pengelolaan inventaris dan peminjaman alat.*
