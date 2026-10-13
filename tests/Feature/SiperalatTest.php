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
}
