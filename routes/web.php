<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
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

