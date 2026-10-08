<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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
        
});
