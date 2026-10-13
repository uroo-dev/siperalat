<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Peminjam;
use App\Models\Kategori;
use App\Models\KondisiAlat;
use App\Models\JenisDenda;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Pengembalian;
use App\Models\DetailPengembalian;
use App\Models\Log;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 0. AUTO-ENSURE STORAGE ALAT IMAGES
        $storageAlatPath = storage_path('app/public/alat');
        if (!file_exists($storageAlatPath)) {
            mkdir($storageAlatPath, 0755, true);
        }

        $imageMap = [
            'tenda-dome-4p.jpg' => 'unsplash-1506905925346-21bda4d32df4.jpg',
            'kompor-camping.jpg' => 'unsplash-1501785888041-af3ef285b470.jpg',
            'carrier-consina-60l.jpg' => 'unsplash-1470071459604-3b5ec3a7fe05.jpg',
            'sleeping-bag-polar.jpg' => 'unsplash-1472214103451-9374bd1c798e.jpg',
            'headlamp-waterproof.jpg' => 'unsplash-1441974231531-c6227db76b6e.jpg',
            'action-cam-4k.jpg' => 'uroo_ui.png',
        ];

        foreach ($imageMap as $target => $source) {
            $srcPath = public_path('template/assets/img/' . $source);
            $dstPath = $storageAlatPath . '/' . $target;
            if (file_exists($srcPath) && !file_exists($dstPath)) {
                copy($srcPath, $dstPath);
            }
        }

        // 1. SEED USERS
        $admin = User::firstOrCreate(
            ['email' => 'uroo@gmail.com'],
            [
                'username' => 'uroo',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'aktif',
            ]
        );

        $petugas = User::firstOrCreate(
            ['email' => 'petugas@gmail.com'],
            [
                'username' => 'petugas',
                'password' => Hash::make('password'),
                'role' => 'petugas',
                'status' => 'aktif',
            ]
        );

        $user1 = User::firstOrCreate(
            ['email' => 'peminjam@gmail.com'],
            [
                'username' => 'peminjam',
                'password' => Hash::make('password'),
                'role' => 'peminjam',
                'status' => 'aktif',
            ]
        );

        $user2 = User::firstOrCreate(
            ['email' => 'budi@gmail.com'],
            [
                'username' => 'budi_santoso',
                'password' => Hash::make('password'),
                'role' => 'peminjam',
                'status' => 'aktif',
            ]
        );

        $user3 = User::firstOrCreate(
            ['email' => 'siti@gmail.com'],
            [
                'username' => 'siti_nurhaliza',
                'password' => Hash::make('password'),
                'role' => 'peminjam',
                'status' => 'aktif',
            ]
        );

        // 2. SEED PROFIL PEMINJAM
        $pelanggan1 = Peminjam::firstOrCreate(
            ['user_id' => $user1->id],
            [
                'nama' => 'Dimas Peminjam',
                'no_identitas' => 'KTP-3578012903980001',
                'no_telepon' => '081234567890',
                'alamat' => 'Jl. Ketintang Baru No. 12, Surabaya',
                'status_peminjam' => 'aktif',
            ]
        );

        $pelanggan2 = Peminjam::firstOrCreate(
            ['user_id' => $user2->id],
            [
                'nama' => 'Budi Santoso',
                'no_identitas' => 'KTM-2205356041',
                'no_telepon' => '081298765432',
                'alamat' => 'Jl. Soekarno Hatta No. 45, Malang',
                'status_peminjam' => 'aktif',
            ]
        );

        $pelanggan3 = Peminjam::firstOrCreate(
            ['user_id' => $user3->id],
            [
                'nama' => 'Siti Nurhaliza',
                'no_identitas' => 'KTM-2105346012',
                'no_telepon' => '081377889900',
                'alamat' => 'Jl. Raya Darmo No. 88, Surabaya',
                'status_peminjam' => 'aktif',
            ]
        );

        // 3. SEED KATEGORI
        $katTenda = Kategori::firstOrCreate(
            ['nama_kategori' => 'Tenda & Shelter'],
            ['deskripsi' => 'Tenda dome, flysheet, pasak, dan perlengkapan pelindung cuaca.', 'status' => 'aktif']
        );

        $katMasak = Kategori::firstOrCreate(
            ['nama_kategori' => 'Peralatan Masak'],
            ['deskripsi' => 'Kompor portable, nesting, nesting set, pisau lipat, dan gas canister.', 'status' => 'aktif']
        );

        $katCarrier = Kategori::firstOrCreate(
            ['nama_kategori' => 'Tas & Carrier'],
            ['deskripsi' => 'Tas carrier 45L - 80L, rain cover, dan daypack outdoor.', 'status' => 'aktif']
        );

        $katTidur = Kategori::firstOrCreate(
            ['nama_kategori' => 'Perlengkapan Tidur'],
            ['deskripsi' => 'Sleeping bag polar/dacron, matras angin, dan bantal tiup.', 'status' => 'aktif']
        );

        $katNavigasi = Kategori::firstOrCreate(
            ['nama_kategori' => 'Navigasi & Lampu'],
            ['deskripsi' => 'Headlamp, senter jarak jauh, kompas bidik, dan lentera tenda.', 'status' => 'aktif']
        );

        $katElektronik = Kategori::firstOrCreate(
            ['nama_kategori' => 'Dokumentasi & Gadget'],
            ['deskripsi' => 'Action camera, drone outdoor, powerbank high capacity, dan walkie talkie.', 'status' => 'aktif']
        );

        // 4. SEED KONDISI ALAT
        $kondisiBaik = KondisiAlat::firstOrCreate(
            ['nama_kondisi' => 'Baik'],
            ['deskripsi' => 'Alat berfungsi 100% normal tanpa kerusakan fisik maupun fungsional.', 'dapat_dipinjam' => 1]
        );

        $kondisiRusakRingan = KondisiAlat::firstOrCreate(
            ['nama_kondisi' => 'Rusak Ringan'],
            ['deskripsi' => 'Kerusakan fisik minor namun unit masih bisa difungsikan sebagian.', 'dapat_dipinjam' => 0]
        );

        $kondisiRusakBerat = KondisiAlat::firstOrCreate(
            ['nama_kondisi' => 'Rusak Berat'],
            ['deskripsi' => 'Kerusakan fisik/fungsional parah dan unit tidak layak digunakan.', 'dapat_dipinjam' => 0]
        );

        // 5. SEED JENIS DENDA
        JenisDenda::firstOrCreate(
            ['nama_denda' => 'Denda Keterlambatan'],
            [
                'metode_hitung' => 'per_hari',
                'tarif' => 10000,
                'persentase' => 0,
                'deskripsi' => 'Denda per hari keterlambatan dari jadwal pengembalian.',
                'status' => 'aktif'
            ]
        );

        JenisDenda::firstOrCreate(
            ['nama_denda' => 'Denda Kerusakan Ringan'],
            [
                'metode_hitung' => 'tetap',
                'tarif' => 25000,
                'persentase' => 0,
                'deskripsi' => 'Biaya perbaikan kerusakan minor atau kotor parah.',
                'status' => 'aktif'
            ]
        );

        JenisDenda::firstOrCreate(
            ['nama_denda' => 'Ganti Rugi Kehilangan'],
            [
                'metode_hitung' => 'persentase',
                'tarif' => 0,
                'persentase' => 100,
                'deskripsi' => 'Ganti rugi 100% dari nilai penggantian alat yang hilang.',
                'status' => 'aktif'
            ]
        );

        // 6. SEED ALAT (DENGAN GAMBAR NYATA)
        $alat1 = Alat::updateOrCreate(
            ['kode_alat' => 'TND-001'],
            [
                'id_kategori' => $katTenda->id_kategori,
                'nama_alat' => 'Tenda Dome Kapasitas 4-5 Orang',
                'deskripsi' => 'Tenda double layer waterproof tahan angin kencang dan hujan lebat, kapasitas 4-5 orang dewasa.',
                'harga_sewa' => 50000,
                'stok_total' => 8,
                'stok_tersedia' => 6,
                'stok_dipinjam' => 2,
                'stok_rusak' => 0,
                'nilai_penggantian' => 650000,
                'foto' => 'alat/tenda-dome-4p.jpg',
                'status' => 'tersedia',
            ]
        );

        $alat2 = Alat::updateOrCreate(
            ['kode_alat' => 'KMP-001'],
            [
                'id_kategori' => $katMasak->id_kategori,
                'nama_alat' => 'Kompor Portable Camping Mini',
                'deskripsi' => 'Kompor gas mini windproof dengan pemantik otomatis, praktis dan hemat gas.',
                'harga_sewa' => 15000,
                'stok_total' => 12,
                'stok_tersedia' => 10,
                'stok_dipinjam' => 2,
                'stok_rusak' => 0,
                'nilai_penggantian' => 175000,
                'foto' => 'alat/kompor-camping.jpg',
                'status' => 'tersedia',
            ]
        );

        $alat3 = Alat::updateOrCreate(
            ['kode_alat' => 'CRR-001'],
            [
                'id_kategori' => $katCarrier->id_kategori,
                'nama_alat' => 'Tas Carrier Consina 60 Liter',
                'deskripsi' => 'Carrier ergonomis dengan backsystem empuk, sirkulasi udara baik, dan termasuk raincover.',
                'harga_sewa' => 35000,
                'stok_total' => 10,
                'stok_tersedia' => 8,
                'stok_dipinjam' => 2,
                'stok_rusak' => 0,
                'nilai_penggantian' => 750000,
                'foto' => 'alat/carrier-consina-60l.jpg',
                'status' => 'tersedia',
            ]
        );

        $alat4 = Alat::updateOrCreate(
            ['kode_alat' => 'SLP-001'],
            [
                'id_kategori' => $katTidur->id_kategori,
                'nama_alat' => 'Sleeping Bag Polar Hangat',
                'deskripsi' => 'Sleeping bag model tikar berlapis polar tebal, nyaman digunakan hingga suhu 10 derajat celcius.',
                'harga_sewa' => 15000,
                'stok_total' => 15,
                'stok_tersedia' => 13,
                'stok_dipinjam' => 2,
                'stok_rusak' => 0,
                'nilai_penggantian' => 180000,
                'foto' => 'alat/sleeping-bag-polar.jpg',
                'status' => 'tersedia',
            ]
        );

        $alat5 = Alat::updateOrCreate(
            ['kode_alat' => 'LMP-001'],
            [
                'id_kategori' => $katNavigasi->id_kategori,
                'nama_alat' => 'Headlamp LED Waterproof Rechargeable',
                'deskripsi' => 'Senter kepala LED super terang dengan sensor gerak dan baterai isi ulang USB type-C.',
                'harga_sewa' => 10000,
                'stok_total' => 14,
                'stok_tersedia' => 12,
                'stok_dipinjam' => 2,
                'stok_rusak' => 0,
                'nilai_penggantian' => 120000,
                'foto' => 'alat/headlamp-waterproof.jpg',
                'status' => 'tersedia',
            ]
        );

        $alat6 = Alat::updateOrCreate(
            ['kode_alat' => 'CAM-001'],
            [
                'id_kategori' => $katElektronik->id_kategori,
                'nama_alat' => 'Action Camera 4K Ultra HD',
                'deskripsi' => 'Action cam perekam video 4K 60fps dengan image stabilizer dan waterproof case hingga kedalaman 30m.',
                'harga_sewa' => 50000,
                'stok_total' => 5,
                'stok_tersedia' => 4,
                'stok_dipinjam' => 1,
                'stok_rusak' => 0,
                'nilai_penggantian' => 1250000,
                'foto' => 'alat/action-cam-4k.jpg',
                'status' => 'tersedia',
            ]
        );

        // 7. SEED TRANSAKSI PEMINJAMAN
        // Transaksi 1: Aktif Dipinjam
        $trx1 = Peminjaman::firstOrCreate(
            ['kode_transaksi' => 'TRX-CAMP01'],
            [
                'id_peminjam' => $pelanggan2->id_peminjam,
                'tanggal_pengajuan' => now()->subDays(2),
                'tanggal_pinjam' => now()->subDays(2)->toDateString(),
                'tanggal_kembali_rencana' => now()->addDays(2)->toDateString(),
                'lama_pinjam' => 4,
                'keperluan' => 'Kegiatan Pendakian Gunung Arjuno UKM Pecinta Alam',
                'subtotal' => 340000,
                'total_denda' => 0,
                'total_tagihan' => 340000,
                'status' => 'dipinjam',
                'disetujui_oleh' => $admin->id,
                'disetujui_at' => now()->subDays(2),
            ]
        );

        DetailPeminjaman::firstOrCreate(
            ['id_peminjaman' => $trx1->id_peminjaman, 'id_alat' => $alat1->id_alat],
            [
                'jumlah' => 1,
                'harga_per_hari' => 50000,
                'lama_pinjam' => 4,
                'subtotal' => 200000,
                'jumlah_dikembalikan' => 0,
            ]
        );

        DetailPeminjaman::firstOrCreate(
            ['id_peminjaman' => $trx1->id_peminjaman, 'id_alat' => $alat3->id_alat],
            [
                'jumlah' => 1,
                'harga_per_hari' => 35000,
                'lama_pinjam' => 4,
                'subtotal' => 140000,
                'jumlah_dikembalikan' => 0,
            ]
        );

        // Transaksi 2: Menunggu Verifikasi
        $trx2 = Peminjaman::firstOrCreate(
            ['kode_transaksi' => 'TRX-REQ002'],
            [
                'id_peminjam' => $pelanggan3->id_peminjam,
                'tanggal_pengajuan' => now()->subHours(5),
                'tanggal_pinjam' => now()->toDateString(),
                'tanggal_kembali_rencana' => now()->addDays(3)->toDateString(),
                'lama_pinjam' => 3,
                'keperluan' => 'Dokumentasi praktikum biologi lapangan dan observasi satwa',
                'subtotal' => 195000,
                'total_denda' => 0,
                'total_tagihan' => 195000,
                'status' => 'menunggu',
            ]
        );

        DetailPeminjaman::firstOrCreate(
            ['id_peminjaman' => $trx2->id_peminjaman, 'id_alat' => $alat6->id_alat],
            [
                'jumlah' => 1,
                'harga_per_hari' => 50000,
                'lama_pinjam' => 3,
                'subtotal' => 150000,
                'jumlah_dikembalikan' => 0,
            ]
        );

        DetailPeminjaman::firstOrCreate(
            ['id_peminjaman' => $trx2->id_peminjaman, 'id_alat' => $alat5->id_alat],
            [
                'jumlah' => 1,
                'harga_per_hari' => 10000,
                'lama_pinjam' => 3,
                'subtotal' => 30000,
                'jumlah_dikembalikan' => 0,
            ]
        );

        DetailPeminjaman::firstOrCreate(
            ['id_peminjaman' => $trx2->id_peminjaman, 'id_alat' => $alat2->id_alat],
            [
                'jumlah' => 1,
                'harga_per_hari' => 15000,
                'lama_pinjam' => 3,
                'subtotal' => 45000,
                'jumlah_dikembalikan' => 0,
            ]
        );

        // Transaksi 3: Selesai Dikembalikan
        $trx3 = Peminjaman::firstOrCreate(
            ['kode_transaksi' => 'TRX-RET003'],
            [
                'id_peminjam' => $pelanggan1->id_peminjam,
                'tanggal_pengajuan' => now()->subDays(6),
                'tanggal_pinjam' => now()->subDays(5)->toDateString(),
                'tanggal_kembali_rencana' => now()->subDays(2)->toDateString(),
                'lama_pinjam' => 3,
                'keperluan' => 'Family Camp dan Glamping Akhir Pekan',
                'subtotal' => 240000,
                'total_denda' => 0,
                'total_tagihan' => 240000,
                'status' => 'dikembalikan',
                'disetujui_oleh' => $admin->id,
                'disetujui_at' => now()->subDays(5),
            ]
        );

        $detail1 = DetailPeminjaman::firstOrCreate(
            ['id_peminjaman' => $trx3->id_peminjaman, 'id_alat' => $alat1->id_alat],
            [
                'jumlah' => 1,
                'harga_per_hari' => 50000,
                'lama_pinjam' => 3,
                'subtotal' => 150000,
                'jumlah_dikembalikan' => 1,
            ]
        );

        $detail2 = DetailPeminjaman::firstOrCreate(
            ['id_peminjaman' => $trx3->id_peminjaman, 'id_alat' => $alat4->id_alat],
            [
                'jumlah' => 2,
                'harga_per_hari' => 15000,
                'lama_pinjam' => 3,
                'subtotal' => 90000,
                'jumlah_dikembalikan' => 2,
            ]
        );

        // 8. SEED DATA PENGEMBALIAN
        $pengembalian = Pengembalian::firstOrCreate(
            ['kode_pengembalian' => 'RTN-998877'],
            [
                'id_peminjaman' => $trx3->id_peminjaman,
                'diproses_oleh' => $petugas->id,
                'tanggal_pengembalian' => now()->subDays(2)->toDateString(),
                'total_denda' => 0,
                'status_pengembalian' => 'selesai',
                'catatan' => 'Peralatan dikembalikan tepat waktu dalam kondisi sangat bersih dan lengkap.',
            ]
        );

        DetailPengembalian::firstOrCreate(
            [
                'id_pengembalian' => $pengembalian->id_pengembalian,
                'id_detail_peminjaman' => $detail1->id_detail,
            ],
            [
                'jumlah_kembali' => 1,
                'id_kondisi' => $kondisiBaik->id_kondisi,
                'keterangan_kondisi' => 'Tenda lengkap dengan pasak dan tas asli.',
            ]
        );

        DetailPengembalian::firstOrCreate(
            [
                'id_pengembalian' => $pengembalian->id_pengembalian,
                'id_detail_peminjaman' => $detail2->id_detail,
            ],
            [
                'jumlah_kembali' => 2,
                'id_kondisi' => $kondisiBaik->id_kondisi,
                'keterangan_kondisi' => 'Sleeping bag kering dan wangi.',
            ]
        );

        // 9. SEED LOGS AKTIVITAS
        Log::create([
            'user' => 'Sistem',
            'aksi' => 'Inisialisasi',
            'deskripsi' => 'Inisialisasi database master data inventaris peralatan berhasil.',
            'created_at' => now()->subDays(7),
        ]);

        Log::create([
            'user' => 'uroo',
            'aksi' => 'Login',
            'deskripsi' => 'Administrator masuk ke dalam panel admin.',
            'created_at' => now()->subDays(2),
        ]);

        Log::create([
            'user' => 'Budi Santoso',
            'aksi' => 'Pengajuan Publik',
            'deskripsi' => 'Mengajukan peminjaman TRX-CAMP01 untuk kegiatan pendakian gunung.',
            'created_at' => now()->subDays(2),
        ]);

        Log::create([
            'user' => 'uroo',
            'aksi' => 'Approval',
            'deskripsi' => 'Menyetujui transaksi peminjaman TRX-CAMP01.',
            'created_at' => now()->subDays(2),
        ]);

        Log::create([
            'user' => 'petugas',
            'aksi' => 'Pengembalian',
            'deskripsi' => 'Memproses pengembalian unit TRX-RET003 dan verifikasi fisik kondisi baik.',
            'created_at' => now()->subDays(2),
        ]);

        Log::create([
            'user' => 'Siti Nurhaliza',
            'aksi' => 'Pengajuan Publik',
            'deskripsi' => 'Mengajukan peminjaman TRX-REQ002 untuk dokumentasi lapangan.',
            'created_at' => now()->subHours(5),
        ]);
    }
}
