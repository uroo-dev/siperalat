<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Kategori;
use App\Models\Alat;
use App\Models\Peminjam;
use App\Models\Peminjaman;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SiperalatTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_home_page_can_be_rendered(): void
    {
        $kategori = Kategori::create([
            'nama_kategori' => 'Tenda & Shelter',
            'deskripsi' => 'Kategori tenda',
            'status' => 'aktif',
        ]);

        $alat = Alat::create([
            'id_kategori' => $kategori->id_kategori,
            'kode_alat' => 'TND-001',
            'nama_alat' => 'Tenda Dome 4P',
            'harga_sewa' => 50000,
            'stok_total' => 5,
            'stok_tersedia' => 5,
            'nilai_penggantian' => 500000,
            'status' => 'tersedia',
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Tenda Dome 4P');
    }

    public function test_user_can_login_as_admin(): void
    {
        $admin = User::create([
            'username' => 'uroo',
            'email' => 'uroo@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        $response = $this->post('/login', [
            'username' => 'uroo',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::create([
            'username' => 'uroo',
            'email' => 'uroo@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Dashboard');
    }

    public function test_authenticated_user_can_submit_borrow_request(): void
    {
        $user = User::create([
            'username' => 'peminjam',
            'email' => 'peminjam@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'peminjam',
            'status' => 'aktif',
        ]);

        $kategori = Kategori::create([
            'nama_kategori' => 'Tenda',
            'status' => 'aktif',
        ]);

        $alat = Alat::create([
            'id_kategori' => $kategori->id_kategori,
            'kode_alat' => 'TND-001',
            'nama_alat' => 'Tenda Dome',
            'harga_sewa' => 50000,
            'stok_total' => 5,
            'stok_tersedia' => 5,
            'nilai_penggantian' => 500000,
            'status' => 'tersedia',
        ]);

        $response = $this->actingAs($user)->post('/pinjam', [
            'nama' => 'Dimas Peminjam',
            'no_identitas' => 'KTP-12345678',
            'no_telepon' => '081234567890',
            'alamat' => 'Surabaya',
            'id_alat' => $alat->id_alat,
            'jumlah' => 1,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_kembali_rencana' => now()->addDays(2)->toDateString(),
            'keperluan' => 'Camping akhir pekan',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('peminjaman', [
            'status' => 'menunggu',
            'keperluan' => 'Camping akhir pekan',
        ]);
    }

    public function test_admin_can_approve_loan_and_handover_tool(): void
    {
        $admin = User::create([
            'username' => 'admin_test',
            'email' => 'admin_test@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        $user = User::create([
            'username' => 'user_borrower',
            'email' => 'borrower@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'peminjam',
            'status' => 'aktif',
        ]);

        $peminjam = Peminjam::create([
            'user_id' => $user->id,
            'nama' => 'User Borrower',
            'no_identitas' => 'ID-0001',
            'no_telepon' => '0811111111',
            'alamat' => 'Alamat Borrower',
            'status_peminjam' => 'aktif',
        ]);

        $kategori = Kategori::create(['nama_kategori' => 'Tenda', 'status' => 'aktif']);
        $alat = Alat::create([
            'id_kategori' => $kategori->id_kategori,
            'kode_alat' => 'TND-999',
            'nama_alat' => 'Tenda Dome Pro',
            'harga_sewa' => 50000,
            'stok_total' => 5,
            'stok_tersedia' => 5,
            'stok_dipinjam' => 0,
            'nilai_penggantian' => 500000,
            'status' => 'tersedia',
        ]);

        $peminjaman = Peminjaman::create([
            'kode_transaksi' => 'TRX-TEST01',
            'id_peminjam' => $peminjam->id_peminjam,
            'tanggal_pengajuan' => now(),
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_kembali_rencana' => now()->addDays(2)->toDateString(),
            'lama_pinjam' => 2,
            'keperluan' => 'Ekspedisi Uji',
            'subtotal' => 100000,
            'total_denda' => 0,
            'total_tagihan' => 100000,
            'status' => 'menunggu',
        ]);

        \App\Models\DetailPeminjaman::create([
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'id_alat' => $alat->id_alat,
            'jumlah' => 1,
            'harga_per_hari' => 50000,
            'lama_pinjam' => 2,
            'subtotal' => 100000,
        ]);

        // 1. One-click approve
        $response = $this->actingAs($admin)->patch("/admin/peminjaman/{$peminjaman->id_peminjaman}/status", [
            'status' => 'disetujui',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('peminjaman', [
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'status' => 'disetujui',
        ]);

        // 2. One-click handover (dipinjam)
        $response2 = $this->actingAs($admin)->patch("/admin/peminjaman/{$peminjaman->id_peminjaman}/status", [
            'status' => 'dipinjam',
        ]);
        $response2->assertRedirect();
        $this->assertDatabaseHas('peminjaman', [
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'status' => 'dipinjam',
        ]);

        // 3. Process return with physical inspection and late fee
        $kondisi = \App\Models\KondisiAlat::firstOrCreate(['nama_kondisi' => 'Baik'], ['dapat_dipinjam' => 1]);
        $detailPeminjaman = $peminjaman->detailPeminjaman()->first();

        $returnResponse = $this->actingAs($admin)->post('/admin/pengembalian', [
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'tanggal_pengembalian' => now()->toDateString(),
            'id_detail' => [$detailPeminjaman->id_detail],
            'jumlah_kembali' => [1],
            'id_kondisi' => [$kondisi->id_kondisi],
            'keterangan_kondisi' => ['Barang mulus dan bersih'],
            'denda_terlambat' => 20000,
            'hari_terlambat' => 2,
            'denda_kerusakan' => 10000,
            'alasan_denda_kerusakan' => 'Biaya cuci tenda kotor',
            'catatan' => 'Pemeriksaan selesai tanpa kendala',
        ]);

        $returnResponse->assertRedirect();

        $peminjaman->refresh();
        $this->assertEquals('dikembalikan', $peminjaman->status);
        $this->assertEquals(30000, $peminjaman->total_denda);
        $this->assertEquals(130000, $peminjaman->total_tagihan);

        // 4. Verify return receipt route
        $pengembalian = $peminjaman->pengembalian()->latest()->first();
        $this->assertNotNull($pengembalian);

        $receiptResponse = $this->actingAs($admin)->get("/admin/pengembalian/{$pengembalian->id_pengembalian}/struk");
        $receiptResponse->assertStatus(200);
        $receiptResponse->assertSee($pengembalian->kode_pengembalian);
        $receiptResponse->assertSee('STRUK PENGEMBALIAN ALAT');
    }
}
