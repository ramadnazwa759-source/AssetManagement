<?php

namespace App\Http\Controllers;

use App\Models\KategoriAset;
use App\Models\SubKategoriAset;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KategoriAsetController extends Controller
{
    // Menampilkan semua kategori dan pencarian kategori
    public function index(Request $request)
    {
        $query = KategoriAset::query();

        // Pencarian kategori berdasarkan nama kategori
        if ($request->filled('search')) {
            $query->where('nama_kategori', 'like', '%' . $request->search . '%');
        }

        $kategori = $query->get();
        $subKategori = SubKategoriAset::all();

        return view('kategori.index', compact('kategori', 'subKategori'));
    }

    // Menampilkan form tambah kategori
    public function create()
    {
        return view('kategori.create');
    }

    // Menyimpan kategori baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_aset,nama_kategori',
            'Deskripsi' => 'nullable|string',
        ]);

        KategoriAset::create([
            'id_kategori' => 'KAT-' . strtoupper(Str::random(6)),
            'nama_kategori' => $request->nama_kategori,
            'Deskripsi' => $request->Deskripsi,
        ]);

        return redirect()
            ->route('kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    // Menampilkan detail kategori
    public function show($id)
    {
        $kategori = KategoriAset::findOrFail($id);

        return view('kategori.show', compact('kategori'));
    }

    // Menampilkan form edit kategori
    public function edit($id)
    {
        $kategori = KategoriAset::findOrFail($id);

        return view('kategori.edit', compact('kategori'));
    }

    // Mengubah kategori
    public function update(Request $request, $id)
    {
        $kategori = KategoriAset::findOrFail($id);

        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_aset,nama_kategori,' . $id . ',id_kategori',
            'Deskripsi' => 'nullable|string',
        ]);

        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'Deskripsi' => $request->Deskripsi,
        ]);

        return redirect()
            ->route('kategori.index')
            ->with('success', 'Kategori berhasil diubah.');
    }
}