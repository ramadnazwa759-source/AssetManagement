<?php

namespace App\Http\Controllers;

use App\Models\JenisAset;
use App\Models\Aset;
use App\Models\SubKategoriAset;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JenisAsetController extends Controller
{
    // Menampilkan semua data jenis aset
    public function index()
    {
        $jenis = JenisAset::with('subKategori')
            ->latest()
            ->get();

        return view(
            'asset-management.jenis.index',
            compact('jenis')
        );
    }

    // Menampilkan form tambah jenis aset
    public function create()
    {
        $subKategori = SubKategoriAset::all();

        return view(
            'asset-management.jenis.create',
            compact('subKategori')
        );
    }

    // Menyimpan jenis aset baru dan membuat aset sesuai stok
    public function store(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'id_jenis' => 'required|string|max:25|unique:jenis_aset,id_jenis',

            'id_sub_kategori_aset' =>
                'required|exists:sub_kategori_aset,id_sub_kategori',

            'nama_jenis' =>
                'required|string|max:100|unique:jenis_aset,nama_jenis',

            'stok' =>
                'required|integer|min:0',

            'deskripsi' =>
                'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {

            // Menyimpan jenis aset
            $jenis = JenisAset::create([
                'id_jenis' => $validated['id_jenis'],
                'id_sub_kategori_aset' => $validated['id_sub_kategori_aset'],
                'nama_jenis' => $validated['nama_jenis'],
                'stok' => $validated['stok'],
                'status_jenis' => 'Aktif',
                'deskripsi' => $validated['deskripsi'] ?? null,
            ]);

            // Membuat data aset sesuai jumlah stok
            for ($i = 1; $i <= $validated['stok']; $i++) {

                Aset::create([
                    'kode_aset' => $this->generateKodeAset(
                        $jenis->nama_jenis,
                        $i
                    ),

                    'id_jenis' => $jenis->id_jenis,
                    'id_lokasi' => null,
                    'nama_aset' => $jenis->nama_jenis,
                    'tanggal_beli' => null,
                    'kondisi_aset' => 'Baik',
                    'status_aset' => 'Tersedia',
                    'gambar' => null,
                    'keterangan' => null,
                ]);
            }
        });

        return redirect()
            ->route('jenis-aset.index')
            ->with(
                'success',
                'Jenis aset dan stok berhasil ditambahkan.'
            );
    }

    // Menampilkan detail jenis aset
    public function show(string $id)
    {
        $jenis = JenisAset::with([
            'subKategori',
            'aset'
        ])->findOrFail($id);

        return view(
            'asset-management.jenis.show',
            compact('jenis')
        );
    }

    // Menampilkan form ubah jenis aset
    public function edit(string $id)
    {
        $jenis = JenisAset::findOrFail($id);

        $subKategori = SubKategoriAset::all();

        return view(
            'asset-management.jenis.edit',
            compact('jenis', 'subKategori')
        );
    }

    // Mengubah data jenis aset dan menambah stok
    public function update(Request $request, string $id)
    {
        $jenis = JenisAset::findOrFail($id);

        // Validasi data
        $validated = $request->validate([
            'id_sub_kategori_aset' =>
                'required|exists:sub_kategori_aset,id_sub_kategori',

            'nama_jenis' =>
                'required|string|max:100|unique:jenis_aset,nama_jenis,' . $id . ',id_jenis',

            'deskripsi' =>
                'nullable|string',

            'tambah_stok' =>
                'nullable|integer|min:0',
        ]);

        DB::transaction(function () use (
            $jenis,
            $validated
        ) {

            // Mengubah data jenis aset
            $jenis->update([
                'id_sub_kategori_aset' =>
                    $validated['id_sub_kategori_aset'],

                'nama_jenis' =>
                    $validated['nama_jenis'],

                'deskripsi' =>
                    $validated['deskripsi'] ?? null,
            ]);

            $tambahStok = $validated['tambah_stok'] ?? 0;

            // Jika ada tambahan stok
            if ($tambahStok > 0) {

                $stokLama = $jenis->stok;

                $stokBaru = $stokLama + $tambahStok;

                // Mengubah jumlah stok
                $jenis->update([
                    'stok' => $stokBaru
                ]);

                // Membuat aset baru dari tambahan stok
                for (
                    $i = $stokLama + 1;
                    $i <= $stokBaru;
                    $i++
                ) {

                    Aset::create([
                        'kode_aset' => $this->generateKodeAset(
                            $jenis->nama_jenis,
                            $i
                        ),

                        'id_jenis' => $jenis->id_jenis,
                        'id_lokasi' => null,
                        'nama_aset' => $jenis->nama_jenis,
                        'tanggal_beli' => null,
                        'kondisi_aset' => 'Baik',
                        'status_aset' => 'Tersedia',
                        'gambar' => null,
                        'keterangan' => null,
                    ]);
                }
            }
        });

        return redirect()
            ->route('jenis-aset.index')
            ->with(
                'success',
                'Jenis aset berhasil diperbarui.'
            );
    }

    // Mengubah status jenis aset
    public function ubahStatus(string $id)
    {
        $jenis = JenisAset::findOrFail($id);

        if ($jenis->status_jenis === 'Aktif') {

            $jenis->update([
                'status_jenis' => 'Nonaktif'
            ]);

            $pesan = 'Jenis aset berhasil dinonaktifkan.';

        } else {

            $jenis->update([
                'status_jenis' => 'Aktif'
            ]);

            $pesan = 'Jenis aset berhasil diaktifkan.';
        }

        return redirect()
            ->route('jenis-aset.index')
            ->with('success', $pesan);
    }

    // Membuat kode aset berdasarkan nama jenis dan nomor
    private function generateKodeAset(
        string $namaJenis,
        int $nomor
    ) {
        $prefix = $this->getPrefix($namaJenis);

        return $prefix . str_pad(
            $nomor,
            2,
            '0',
            STR_PAD_LEFT
        );
    }

    // Mengambil 3 huruf pertama dari nama jenis
    private function getPrefix(string $namaJenis)
    {
        $nama = preg_replace(
            '/[^a-zA-Z]/',
            '',
            trim($namaJenis)
        );

        return strtoupper(substr($nama, 0, 3));
    }
}
