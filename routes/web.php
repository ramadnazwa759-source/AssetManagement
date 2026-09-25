<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KategoriAsetController;
use App\Http\Controllers\DashboardController;


// =========================
// LOGIN
// =========================

Route::get('/', function () {
    return view('auth.login');
});

Route::post('/login', [UserController::class, 'authenticate'])
    ->name('login.process');


// =========================
// LOGOUT
// =========================

Route::post('/logout', [UserController::class, 'logout'])
    ->name('logout');


// =========================
// HALAMAN YANG SUDAH LOGIN
// =========================

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Kategori Aset
    //Route::resource('kategori', KategoriAsetController::class);

});


// =========================
// JENIS ASET
// =========================

Route::get('/jenis-aset', function () {
    return view('jenis-aset.index');
});

Route::get('/jenis-aset/tambah', function () {
    return view('jenis-aset.create');
});