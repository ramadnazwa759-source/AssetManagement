<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KategoriAsetController;
use App\Http\Controllers\SubKategoriAsetController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [UserController::class, 'login'])->name('login');
Route::post('/login', [UserController::class, 'authenticate'])->name('login.process');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// Halaman Kategori tanpa login
Route::get('/kategori', [KategoriAsetController::class, 'index'])
    ->name('kategori.index');

// Route yang membutuhkan login
Route::middleware('auth')->group(function () {

    Route::post('/kategori', [KategoriAsetController::class, 'store'])
        ->name('kategori.store');

    Route::get('/kategori/create', [KategoriAsetController::class, 'create'])
        ->name('kategori.create');

    Route::get('/kategori/{kategori}/edit', [KategoriAsetController::class, 'edit'])
        ->name('kategori.edit');

    Route::put('/kategori/{kategori}', [KategoriAsetController::class, 'update'])
        ->name('kategori.update');

    Route::delete('/kategori/{kategori}', [KategoriAsetController::class, 'destroy'])
        ->name('kategori.destroy');

    Route::post('/sub-kategori', [SubKategoriAsetController::class, 'store'])
        ->name('sub-kategori.store');

    Route::put('/sub-kategori/{id}', [SubKategoriAsetController::class, 'update'])
        ->name('sub-kategori.update');

    Route::delete('/sub-kategori/{id}', [SubKategoriAsetController::class, 'destroy'])
        ->name('sub-kategori.destroy');
});