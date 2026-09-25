<?php

namespace App\Http\Controllers;

use App\Models\SubKategoriAset;
use App\Models\KategoriAset;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class SubKategoriAsetController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_kategori' => 'required|exists:kategori_aset,id_kategori',
            'nama_sub_kategori' => 'required|string|max:100',
            'Gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'Deskripsi' => 'nullable|string',
        ]);

        $gambar = null;

        if ($request->hasFile('Gambar')) {
            $gambar = $request->file('Gambar')->store('sub-kategori', 'public');
        }

        SubKategoriAset::create([
            'id_sub_kategori' => 'SUB-' . strtoupper(Str::random(6)),
            'id_kategori' => $request->id_kategori,
            'nama_sub_kategori' => $request->nama_sub_kategori,
            'Gambar' => $gambar,
            'Deskripsi' => $request->Deskripsi,
        ]);

        return redirect()
            ->route('kategori.index')
            ->with('success', 'Sub kategori berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $subKategori = SubKategoriAset::findOrFail($id);

        $request->validate([
            'id_kategori' => 'required|exists:kategori_aset,id_kategori',
            'nama_sub_kategori' => 'required|string|max:100',
            'Gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'Deskripsi' => 'nullable|string',
        ]);

        $data = [
            'id_kategori' => $request->id_kategori,
            'nama_sub_kategori' => $request->nama_sub_kategori,
            'Deskripsi' => $request->Deskripsi,
        ];

        if ($request->hasFile('Gambar')) {
            $data['Gambar'] = $request->file('Gambar')->store('sub-kategori', 'public');
        }

        $subKategori->update($data);

        return redirect()
            ->route('kategori.index')
            ->with('success', 'Sub kategori berhasil diubah.');
    }

    public function destroy($id)
    {
        $subKategori = SubKategoriAset::findOrFail($id);

        $subKategori->delete();

        return redirect()
            ->route('kategori.index')
            ->with('success', 'Sub kategori berhasil dihapus.');
    }
}