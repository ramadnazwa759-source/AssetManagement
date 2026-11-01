<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\JenisAset;
use App\Models\KategoriAset;
use App\Models\LokasiAset;
use App\Models\SubKategoriAset;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class AsetController extends Controller
{
    /**
     * Menampilkan daftar Unit Aset, pencarian,
     * dan filter berdasarkan data yang tersimpan.
     */
    public function index(Request $request)
    {
        // VR-AM-AST-007
        // Spasi di awal dan akhir kata kunci dihapus.
        $search = trim((string) $request->input('search', ''));

        $request->merge([
            'search' => $search,
        ]);

        // VR-AM-AST-007, VR-AM-AST-008
        // Filter harus menggunakan nilai yang valid.
        $request->validate([
            'search' => [
                'nullable',
                'string',
                'max:100',
            ],

            'id_jenis' => [
                'nullable',
                'string',
                'exists:jenis_aset,id_jenis',
            ],

            'id_kategori' => [
                'nullable',
                'string',
                'exists:kategori_aset,id_kategori',
            ],

            'id_sub_kategori' => [
                'nullable',
                'string',
                'exists:sub_kategori_aset,id_sub_kategori',
            ],

            'id_lokasi' => [
                'nullable',
                'string',
                'exists:lokasi_aset,id_lokasi',
            ],

            'kondisi_aset' => [
                'nullable',
                Rule::in([
                    'Baik',
                    'Rusak Ringan',
                    'Rusak Berat',
                ]),
            ],

            'status_aset' => [
                'nullable',
                Rule::in([
                    'Tersedia',
                    'Dipinjam',
                    'Perlu Perbaikan',
                    'Nonaktif',
                ]),
            ],
        ]);

        // BR-AM-AST-001, BR-AM-AST-002
        // Kategori dan Subkategori didapatkan melalui relasi.
        $query = Aset::with([
            'jenis.subKategori.kategori',
            'lokasi',
        ]);

        // VR-AM-AST-007
        // Pencarian dilakukan menggunakan query builder.
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('kode_aset', 'like', '%' . $search . '%')
                    ->orWhere('nama_aset', 'like', '%' . $search . '%')

                    ->orWhereHas('jenis', function ($jenis) use ($search) {
                        $jenis->where(
                            'nama_jenis',
                            'like',
                            '%' . $search . '%'
                        );
                    })

                    ->orWhereHas(
                        'jenis.subKategori',
                        function ($subKategori) use ($search) {
                            $subKategori->where(
                                'nama_sub_kategori',
                                'like',
                                '%' . $search . '%'
                            );
                        }
                    )

                    ->orWhereHas(
                        'jenis.subKategori.kategori',
                        function ($kategori) use ($search) {
                            $kategori->where(
                                'nama_kategori',
                                'like',
                                '%' . $search . '%'
                            );
                        }
                    )

                    ->orWhereHas('lokasi', function ($lokasi) use ($search) {
                        $lokasi->where(
                            'nama_lokasi',
                            'like',
                            '%' . $search . '%'
                        );
                    })

                    ->orWhere('kondisi_aset', 'like', '%' . $search . '%')
                    ->orWhere('status_aset', 'like', '%' . $search . '%');
            });
        }

        // VR-AM-AST-008
        if ($request->filled('id_jenis')) {
            $query->where(
                'id_jenis',
                $request->input('id_jenis')
            );
        }

        if ($request->filled('id_kategori')) {
            $idKategori = $request->input('id_kategori');

            $query->whereHas(
                'jenis.subKategori.kategori',
                function ($kategori) use ($idKategori) {
                    $kategori->where(
                        'id_kategori',
                        $idKategori
                    );
                }
            );
        }

        if ($request->filled('id_sub_kategori')) {
            $idSubKategori = $request->input('id_sub_kategori');

            $query->whereHas(
                'jenis.subKategori',
                function ($subKategori) use ($idSubKategori) {
                    $subKategori->where(
                        'id_sub_kategori',
                        $idSubKategori
                    );
                }
            );
        }

        if ($request->filled('id_lokasi')) {
            $query->where(
                'id_lokasi',
                $request->input('id_lokasi')
            );
        }

        if ($request->filled('kondisi_aset')) {
            $query->where(
                'kondisi_aset',
                $request->input('kondisi_aset')
            );
        }

        if ($request->filled('status_aset')) {
            $query->where(
                'status_aset',
                $request->input('status_aset')
            );
        }

        // BR-AM-AST-001, BR-AM-AST-006
        $aset = $query
            ->orderBy('kode_aset')
            ->get();

        // Data referensi untuk pilihan filter.
        $kategori = KategoriAset::orderBy('nama_kategori')
            ->get();

        $subKategori = SubKategoriAset::orderBy(
            'nama_sub_kategori'
        )->get();

        $jenisAset = JenisAset::orderBy('nama_jenis')
            ->get();

        $lokasi = LokasiAset::orderBy('nama_lokasi')
            ->get();

        // Pilihan berasal dari nilai yang diizinkan sistem,
        // bukan hanya nilai yang kebetulan ada di database.
        $kondisiOptions = collect([
            'Baik',
            'Rusak Ringan',
            'Rusak Berat',
        ]);

        $statusOptions = collect([
            'Tersedia',
            'Dipinjam',
            'Perlu Perbaikan',
            'Nonaktif',
        ]);

        return view(
            'data-aset.index',
            compact(
                'aset',
                'kategori',
                'subKategori',
                'jenisAset',
                'lokasi',
                'kondisiOptions',
                'statusOptions',
                'search'
            )
        );
    }

    /**
     * Menampilkan detail Unit Aset.
     */
    public function show(string $id)
    {
        // VR-AM-AST-010
        // VR-AM-AST-011 perlu diterapkan apabila relasi
        // transaksi ditampilkan pada halaman detail.
        $aset = Aset::with([
            'jenis.subKategori.kategori',
            'lokasi',
        ])->findOrFail($id);

        return view(
            'data-aset.show',
            compact('aset')
        );
    }

    /**
     * Menampilkan form edit Unit Aset.
     */
    public function edit(string $id)
    {
        // VR-AM-AST-010, VR-AM-AST-012, VR-AM-AST-013
        $aset = Aset::with([
            'jenis.subKategori.kategori',
            'lokasi',
        ])->findOrFail($id);

        $lokasi = LokasiAset::orderBy('nama_lokasi')
            ->get();

        return view(
            'data-aset.update',
            compact('aset', 'lokasi')
        );
    }

    /**
     * Memperbarui informasi Unit Aset yang diperbolehkan.
     *
     * Kode aset, Jenis Aset, Kondisi, dan Status
     * tidak diubah melalui fungsi edit ini.
     */
    public function update(Request $request, string $id)
    {
        // VR-AM-AST-010
        $aset = Aset::findOrFail($id);

        // VR-AM-AST-012 s.d. VR-AM-AST-015
        // Hanya field yang diperbolehkan yang divalidasi.
        $validated = $request->validate([
            'id_lokasi' => [
                'required',
                'string',
                'exists:lokasi_aset,id_lokasi',
            ],

            'tanggal_beli' => [
                'nullable',
                'date',
            ],

            'gambar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'keterangan' => [
                'nullable',
                'string',
            ],
        ]);

        $gambarLama = $aset->gambar;
        $gambarBaru = null;

        // Simpan gambar baru apabila Admin mengunggahnya.
        if ($request->hasFile('gambar')) {
            $gambarBaru = $request
                ->file('gambar')
                ->store('aset', 'public');

            if (!$gambarBaru) {
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Gambar baru gagal disimpan.'
                    );
            }

            $validated['gambar'] = $gambarBaru;
        }

        try {
            // Field yang tidak ada di $validated tidak diubah.
            $aset->update($validated);
        } catch (\Throwable $exception) {
            // Bersihkan gambar baru jika update database gagal.
            if (
                $gambarBaru &&
                Storage::disk('public')->exists($gambarBaru)
            ) {
                Storage::disk('public')->delete($gambarBaru);
            }

            throw $exception;
        }

        // Hapus gambar lama hanya setelah update berhasil.
        if (
            $gambarBaru &&
            $gambarLama &&
            Storage::disk('public')->exists($gambarLama)
        ) {
            Storage::disk('public')->delete($gambarLama);
        }

        return redirect()
            ->route('aset.index')
            ->with(
                'success',
                'Detail aset berhasil diperbarui.'
            );
    }

    /**
     * Mengaktifkan atau menonaktifkan Unit Aset.
     *
     * Fungsi ini tidak digunakan untuk mengubah status
     * akibat transaksi peminjaman atau pengembalian.
     */
    public function ubahStatus(string $id)
    {
        // VR-AM-AST-010, VR-AM-AST-014, VR-AM-AST-015
        $aset = Aset::with('jenis')->findOrFail($id);

        // Aset yang sedang dipinjam tidak dapat dinonaktifkan.
        if ($aset->status_aset === 'Dipinjam') {
            return redirect()
                ->route('aset.index')
                ->with(
                    'error',
                    'Aset yang sedang dipinjam tidak dapat dinonaktifkan.'
                );
        }

        // Hanya aset tersedia yang dapat dinonaktifkan
        // melalui fungsi perubahan status ini.
        if ($aset->status_aset === 'Tersedia') {
            $aset->update([
                'status_aset' => 'Nonaktif',
            ]);

            return redirect()
                ->route('aset.index')
                ->with(
                    'success',
                    'Aset berhasil dinonaktifkan.'
                );
        }

        // Aset Nonaktif hanya dapat diaktifkan kembali
        // jika Jenis Aset induknya sudah Aktif.
        if ($aset->status_aset === 'Nonaktif') {
            if (
                !$aset->jenis ||
                $aset->jenis->status_jenis !== 'Aktif'
            ) {
                return redirect()
                    ->route('aset.index')
                    ->with(
                        'error',
                        'Aktifkan Jenis Aset terlebih dahulu.'
                    );
            }

            // Aset yang tidak dalam kondisi Baik tidak
            // boleh langsung berstatus Tersedia.
            $statusBaru = $aset->kondisi_aset === 'Baik'
                ? 'Tersedia'
                : 'Perlu Perbaikan';

            $aset->update([
                'status_aset' => $statusBaru,
            ]);

            return redirect()
                ->route('aset.index')
                ->with(
                    'success',
                    'Status aset berhasil diperbarui.'
                );
        }

        // Status Perlu Perbaikan harus ditangani melalui
        // proses perbaikan yang sesuai, bukan diubah
        // langsung melalui fungsi ini.
        return redirect()
            ->route('aset.index')
            ->with(
                'error',
                'Status aset tidak dapat diubah melalui fungsi ini.'
            );
    }
}
