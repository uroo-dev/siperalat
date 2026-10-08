<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('Public.Dashboard.index');
})->name('public.home');


Route::middleware(['guest'])->group(function () {
    Route::controller(AuthController::class)->group(function () {
        // Login Routes
        Route::get('/login', 'login')->name('login');
        Route::get('/auth/login', 'login');
        Route::post('/login', 'login_proses')->name('login.proses');
        Route::post('/auth/login', 'login_proses');

        // Register Routes
        Route::get('/register', 'register')->name('register');
        Route::get('/auth/register', 'register');
        Route::post('/register', 'register_proses')->name('register.proses');
        Route::post('/auth/register', 'register_proses');
    });
});


Route::middleware(['auth'])->group(function () {

    // Logout Route
    Route::match(['GET', 'POST'], '/logout', [AuthController::class, 'logout_proses'])->name('logout');
    Route::match(['GET', 'POST'], '/auth/logout', [AuthController::class, 'logout_proses']);

    // Admin Prefix & Panel
    Route::prefix('admin')->group(function () {

        // Admin Dashboard
        Route::get('/', function () {
            return view('Admin.Dashboard.index');
        })->name('admin.dashboard');

        // User Management
        Route::controller(AuthController::class)->prefix('users')->name('admin.users.')->group(function () {
            Route::get('/', 'user_index')->name('index');
            Route::get('/create', 'user_create')->name('create');
            Route::post('/', 'user_store')->name('store');
            Route::get('/{id}/edit', 'user_edit')->name('edit');
            Route::put('/{id}', 'user_update')->name('update');
            Route::delete('/{id}', 'user_destroy')->name('destroy');
        });
    });
});
