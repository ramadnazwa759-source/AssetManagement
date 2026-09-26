<?php

namespace App\Http\Controllers;

use App\Models\LokasiAset;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LokasiAsetController extends Controller
{
    // Menampilkan daftar lokasi
    public function index()
    {
        $lokasi = LokasiAset::all();

        return view('lokasi.index', compact('lokasi'));
    }

    // Menampilkan form tambah lokasi
    public function create()
    {
        return view('lokasi.create');
    }

    // Menyimpan lokasi baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_lokasi' => 'required|string|max:100|unique:lokasi_aset,nama_lokasi',
            'deskripsi' => 'nullable|string',
        ]);

        LokasiAset::create([
            'id_lokasi' => 'LOK-' . strtoupper(Str::random(6)),
            'nama_lokasi' => $request->nama_lokasi,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()
            ->route('lokasi.index')
            ->with('success', 'Lokasi berhasil ditambahkan.');
    }

    // Menampilkan detail lokasi
    public function show($id)
    {
        $lokasi = LokasiAset::findOrFail($id);

        return view('lokasi.show', compact('lokasi'));
    }

    // Menampilkan form edit lokasi
    public function edit($id)
    {
        $lokasi = LokasiAset::findOrFail($id);

        return view('lokasi.edit', compact('lokasi'));
    }

    // Mengubah data lokasi
    public function update(Request $request, $id)
    {
        $lokasi = LokasiAset::findOrFail($id);

        $request->validate([
            'nama_lokasi' => 'required|string|max:100|unique:lokasi_aset,nama_lokasi,' . $id . ',id_lokasi',
            'deskripsi' => 'nullable|string',
        ]);

        $lokasi->update([
            'nama_lokasi' => $request->nama_lokasi,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()
            ->route('lokasi.index')
            ->with('success', 'Lokasi berhasil diubah.');
    }

    // Menghapus lokasi
    public function destroy($id)
    {
        $lokasi = LokasiAset::findOrFail($id);

        $lokasi->delete();

        return redirect()
            ->route('lokasi.index')
            ->with('success', 'Lokasi berhasil dihapus.');
    }
}