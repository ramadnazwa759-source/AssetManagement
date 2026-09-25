<?php

namespace App\Http\Controllers;

use App\Models\KategoriAset;
use App\Models\SubKategoriAset;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KategoriAsetController extends Controller
{
    // Menampilkan semua kategori
   public function index()
{
    $kategori = KategoriAset::all();
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

    // Menghapus kategori
    public function destroy($id)
    {
        $kategori = KategoriAset::findOrFail($id);

        $kategori->delete();

        return redirect()
            ->route('kategori.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}