<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KategoriAsetController;


// =========================
// LOGIN
// =========================

Route::get('/', function () {
    return view('login');
});

Route::get('/login', [UserController::class, 'login'])->name('login');

Route::post('/login', [UserController::class, 'authenticate'])->name('login.process');


// =========================
// LOGOUT
// =========================

Route::post('/logout', [UserController::class, 'logout'])->name('logout');


// =========================
// HALAMAN YANG SUDAH LOGIN
// =========================

Route::middleware('auth')->group(function () {

    // Kategori Aset
    Route::resource('kategori', KategoriAsetController::class);

});


// =========================
// DASHBOARD
// =========================

Route::get('/dashboard', function () {
    return view('dashboard.index');
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