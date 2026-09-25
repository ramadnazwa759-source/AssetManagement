<?php

namespace App\Http\Controllers;

use App\Models\SubKategoriAset;

class DashboardController extends Controller
{
    public function index()
    {
        $subKategori = SubKategoriAset::all();

        return view('index', compact('subKategori'));
    }
}
