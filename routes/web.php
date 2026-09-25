
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KategoriAsetController;


Route::get('/', function () {
    return view('auth.login');
});

Route::get('/login', [UserController::class, 'login'])->name('login');
Route::post('/login', [UserController::class, 'authenticate'])->name('login.process');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::resource('kategori', KategoriAsetController::class);
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard.index');
});


// jenis aset
Route::get('/jenis-aset', function () {
    return view('jenis-aset.index');
});
Route::get('/jenis-aset/tambah', function () {
    return view('jenis-aset.create');

});
