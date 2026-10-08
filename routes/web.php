<?php

use App\Http\Controllers\AdminDashboardContrller;
use App\Http\Controllers\AlatController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KategoriController;

Route::get('/', function () {
    return view('Public.Dashboard.index');
})->name('public.home');


Route::middleware(['guest'])->group(function () {
    // Login Routes
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'login_proses'])->name('login.proses');

    // Register Routes
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'register_proses'])->name('register.proses');
});


Route::middleware(['auth'])->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [AdminDashboardContrller::class, 'index'])->name('admin.dashboard');

    // Users Management
    Route::get('admin/users', [AuthController::class, 'user_index'])->name('admin.user.index');
    Route::post('admin/users', [AuthController::class, 'user_store'])->name('admin.user.store');
    Route::put('admin/users/{id}', [AuthController::class, 'user_update'])->name('admin.user.update');
    Route::delete('admin/users/{id}', [AuthController::class, 'user_destroy'])->name('admin.user.destroy');

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

    // Logout Process
    Route::post('/logout', [AuthController::class, 'logout_proses'])->name('logout');
});
