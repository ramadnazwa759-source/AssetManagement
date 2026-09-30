<?php

namespace App\Http\Controllers;

use App\Models\KategoriAset;
use Illuminate\Http\Request;

class KategoriAsetController extends Controller
{
    // =====================================================
    // MENAMPILKAN SEMUA KATEGORI
    // =====================================================

    public function index()
    {
        $kategori = KategoriAset::all();

        return view('kategori.index', compact('kategori'));
    }


    // =====================================================
    // FORM TAMBAH KATEGORI
    // =====================================================

    public function create()
    {
        // Mencari nomor ID kategori terakhir
        $nomorTerakhir = KategoriAset::all()
            ->map(function ($item) {
                return (int) str_replace('KAT-', '', $item->id_kategori);
            })
            ->max() ?? 0;

        // Membuat ID kategori baru
        $idKategoriBaru = 'KAT-' . str_pad(
            $nomorTerakhir + 1,
            2,
            '0',
            STR_PAD_LEFT
        );

        return view('kategori.create', compact('idKategoriBaru'));
    }


    // =====================================================
    // MENYIMPAN KATEGORI BARU
    // =====================================================

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_aset,nama_kategori',
            'Deskripsi' => 'nullable|string',
        ]);

        // Mencari nomor ID kategori terakhir
        $nomorTerakhir = KategoriAset::all()
            ->map(function ($item) {
                return (int) str_replace('KAT-', '', $item->id_kategori);
            })
            ->max() ?? 0;

        // Membuat ID kategori
        $idKategori = 'KAT-' . str_pad(
            $nomorTerakhir + 1,
            2,
            '0',
            STR_PAD_LEFT
        );

        // Simpan data
        KategoriAset::create([
            'id_kategori' => $idKategori,
            'nama_kategori' => $request->nama_kategori,
            'Deskripsi' => $request->Deskripsi,
        ]);

        return redirect()
            ->route('kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }


    // =====================================================
    // FORM UBAH KATEGORI
    // =====================================================

    public function edit($id)
    {
        // Cari kategori berdasarkan ID
        $kategori = KategoriAset::findOrFail($id);

        // Karena file bernama update.blade.php,
        // maka view yang dipanggil adalah kategori.update
        return view('kategori.update', compact('kategori'));
    }


    // =====================================================
    // UPDATE / MENGUBAH KATEGORI
    // =====================================================

    public function update(Request $request, $id)
    {
        // Cari kategori berdasarkan ID
        $kategori = KategoriAset::findOrFail($id);

        // Validasi
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_aset,nama_kategori,' . $id . ',id_kategori',
            'Deskripsi' => 'nullable|string',
        ]);

        // Update data
        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'Deskripsi' => $request->Deskripsi,
        ]);

        // Kembali ke halaman kategori
        return redirect()
            ->route('kategori.index')
            ->with('success', 'Kategori berhasil diubah.');
    }


    // =====================================================
    // HAPUS KATEGORI
    // =====================================================

    public function destroy($id)
    {
        // Cari kategori
        $kategori = KategoriAset::findOrFail($id);

        // Hapus kategori
        $kategori->delete();

        return redirect()
            ->route('kategori.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}