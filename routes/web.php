<?php

use App\Http\Controllers\AdminDashboardContrller;
use App\Http\Controllers\AlatController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\PeminjamController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\PublicController;

Route::get('/', [PublicController::class, 'index'])->name('public.home');
Route::post('/pinjam', [PublicController::class, 'store_peminjaman'])->name('public.pinjam.store');


Route::middleware(['guest'])->group(function () {
    // Login Routes
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'login_proses'])->name('login.proses');

    // Register Routes
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'register_proses'])->name('register.proses');
});


Route::middleware(['auth'])->group(function () {
    // Logout Process (Untuk Semua Pengguna yang Login)
    Route::post('/logout', [AuthController::class, 'logout_proses'])->name('logout');

    // Admin & Petugas Only Panel
    Route::middleware(['role.admin'])->group(function () {
        // Admin Dashboard
        Route::get('/dashboard', [AdminDashboardContrller::class, 'index'])->name('admin.dashboard');

        // Transaksi Peminjaman & Nota
        Route::get('admin/peminjaman', [PeminjamanController::class, 'index'])->name('admin.peminjaman.index');
        Route::post('admin/peminjaman', [PeminjamanController::class, 'store'])->name('admin.peminjaman.store');
        Route::get('admin/peminjaman/{id}/nota', [PeminjamanController::class, 'nota'])->name('admin.peminjaman.nota');
        Route::patch('admin/peminjaman/{id}/status', [PeminjamanController::class, 'update_status'])->name('admin.peminjaman.status');
        Route::delete('admin/peminjaman/{id}', [PeminjamanController::class, 'destroy'])->name('admin.peminjaman.destroy');

        // Transaksi Pengembalian
        Route::get('admin/pengembalian', [PengembalianController::class, 'index'])->name('admin.pengembalian.index');
        Route::post('admin/pengembalian', [PengembalianController::class, 'store'])->name('admin.pengembalian.store');
        Route::get('admin/pengembalian/{id}/struk', [PengembalianController::class, 'struk'])->name('admin.pengembalian.struk');
        Route::get('admin/peminjaman/{id}/struk-pengembalian', [PeminjamanController::class, 'struk_pengembalian'])->name('admin.peminjaman.struk');
        Route::delete('admin/pengembalian/{id}', [PengembalianController::class, 'destroy'])->name('admin.pengembalian.destroy');

        // Laporan Rekap
        Route::get('admin/laporan', [LaporanController::class, 'index'])->name('admin.laporan.index');
        Route::get('admin/laporan/cetak', [LaporanController::class, 'cetak'])->name('admin.laporan.cetak');

        // Log Aktivitas Sistem
        Route::get('admin/logs', [LogController::class, 'index'])->name('admin.log.index');

        // Admin & Petugas Users Management
        Route::get('admin/users', [AuthController::class, 'user_index'])->name('admin.user.index');
        Route::post('admin/users', [AuthController::class, 'user_store'])->name('admin.user.store');
        Route::put('admin/users/{id}', [AuthController::class, 'user_update'])->name('admin.user.update');
        Route::delete('admin/users/{id}', [AuthController::class, 'user_destroy'])->name('admin.user.destroy');

        // Pelanggan / Peminjam Management
        Route::get('admin/pelanggan', [PeminjamController::class, 'index'])->name('admin.pelanggan.index');
        Route::post('admin/pelanggan', [PeminjamController::class, 'store'])->name('admin.pelanggan.store');
        Route::put('admin/pelanggan/{id}', [PeminjamController::class, 'update'])->name('admin.pelanggan.update');
        Route::delete('admin/pelanggan/{id}', [PeminjamController::class, 'destroy'])->name('admin.pelanggan.destroy');

        // Kategori Management
        Route::get('admin/kategori', [KategoriController::class, 'index'])->name('admin.kategori.index');
        Route::post('admin/kategori', [KategoriController::class, 'store'])->name('admin.kategori.store');
        Route::put('admin/kategori/{kategori}', [KategoriController::class, 'update'])->name('admin.kategori.update');
        Route::delete('admin/kategori/{kategori}', [KategoriController::class, 'destroy'])->name('admin.kategori.destroy');

        // Alat Management
        Route::get('admin/alat', [AlatController::class, 'index'])->name('admin.alat.index');
        Route::post('admin/alat', [AlatController::class, 'store'])->name('admin.alat.store');
        Route::put('admin/alat/{alat}', [AlatController::class, 'update'])->name('admin.alat.update');
        Route::delete('admin/alat/{alat}', [AlatController::class, 'destroy'])->name('admin.alat.destroy');
    });
});
