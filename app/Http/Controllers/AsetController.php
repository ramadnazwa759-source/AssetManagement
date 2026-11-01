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
     * filter, dan waktu penambahan stok.
     */
    public function index(Request $request)
    {
        // VR-AM-AST-007
        $search = trim((string) $request->input('search', ''));

        $request->merge([
            'search' => $search,
        ]);

        // Validasi pencarian dan filter.
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

        // Mengambil seluruh kolom aset, termasuk created_at.
        $query = Aset::select('aset.*')->with([
            'jenis.subKategori.kategori',
            'lokasi',
        ]);

        // Pencarian data aset.
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

        // Filter berdasarkan jenis aset.
        if ($request->filled('id_jenis')) {
            $query->where('id_jenis', $request->input('id_jenis'));
        }

        // Filter berdasarkan kategori.
        if ($request->filled('id_kategori')) {
            $idKategori = $request->input('id_kategori');

            $query->whereHas(
                'jenis.subKategori.kategori',
                function ($kategori) use ($idKategori) {
                    $kategori->where('id_kategori', $idKategori);
                }
            );
        }

        // Filter berdasarkan subkategori.
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

        // Filter berdasarkan lokasi.
        if ($request->filled('id_lokasi')) {
            $query->where('id_lokasi', $request->input('id_lokasi'));
        }

        // Filter berdasarkan kondisi.
        if ($request->filled('kondisi_aset')) {
            $query->where(
                'kondisi_aset',
                $request->input('kondisi_aset')
            );
        }

        // Filter berdasarkan status.
        if ($request->filled('status_aset')) {
            $query->where(
                'status_aset',
                $request->input('status_aset')
            );
        }

        $aset = $query
            ->orderBy('kode_aset')
            ->get();

        // Data referensi untuk pilihan filter.
        $kategori = KategoriAset::orderBy('nama_kategori')->get();

        $subKategori = SubKategoriAset::orderBy(
            'nama_sub_kategori'
        )->get();

        $jenisAset = JenisAset::orderBy('nama_jenis')->get();

        $lokasi = LokasiAset::orderBy('nama_lokasi')->get();

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
        $aset = Aset::with([
            'jenis.subKategori.kategori',
            'lokasi',
        ])->findOrFail($id);

        $lokasi = LokasiAset::orderBy('nama_lokasi')->get();

        $kondisiOptions = collect([
            'Baik',
            'Rusak Ringan',
            'Rusak Berat',
        ]);

        // Dipinjam tidak disediakan sebagai pilihan manual.
        // Status tersebut hanya ditetapkan oleh transaksi peminjaman.
        $statusOptions = collect([
            'Tersedia',
            'Perlu Perbaikan',
            'Nonaktif',
        ]);

        return view(
            'data-aset.update',
            compact(
                'aset',
                'lokasi',
                'kondisiOptions',
                'statusOptions'
            )
        );
    }

    /**
     * Memperbarui informasi Unit Aset.
     *
     * Admin dapat mengubah:
     * - Lokasi
     * - Tanggal beli
     * - Kondisi aset
     * - Status aset
     * - Gambar
     * - Keterangan
     *
     * Status Dipinjam hanya diatur melalui transaksi peminjaman.
     */
    public function update(Request $request, string $id)
    {
        $aset = Aset::findOrFail($id);

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

            'kondisi_aset' => [
                'required',
                Rule::in([
                    'Baik',
                    'Rusak Ringan',
                    'Rusak Berat',
                ]),
            ],

            'status_aset' => [
                'required',
                Rule::in([
                    'Tersedia',
                    'Perlu Perbaikan',
                    'Nonaktif',
                ]),
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

        /*
         * Aset yang sedang dipinjam tidak boleh diubah
         * kondisi dan statusnya secara manual.
         *
         * Kondisi dan status akan ditentukan ketika
         * proses pengembalian dilakukan.
         */
        if ($aset->status_aset === 'Dipinjam') {
            if (
                $validated['kondisi_aset'] !== $aset->kondisi_aset ||
                $validated['status_aset'] !== $aset->status_aset
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'status_aset' => 'Kondisi dan status aset yang sedang dipinjam tidak dapat diubah secara manual. Perbarui melalui proses pengembalian.',
                    ]);
            }
        }

        /*
         * Aset dengan kondisi rusak tidak boleh
         * memiliki status Tersedia.
         */
        if (
            $validated['status_aset'] === 'Tersedia' &&
            $validated['kondisi_aset'] !== 'Baik'
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'status_aset' => 'Aset hanya dapat berstatus Tersedia jika kondisinya Baik. Pilih Perlu Perbaikan atau Nonaktif.',
                ]);
        }

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
                    ->with('error', 'Gambar baru gagal disimpan.');
            }

            $validated['gambar'] = $gambarBaru;
        }

        try {
            // Perbarui data aset.
            // created_at tetap dipertahankan.
            $aset->update($validated);
        } catch (\Throwable $exception) {
            // Hapus gambar baru jika update database gagal.
            if (
                $gambarBaru &&
                Storage::disk('public')->exists($gambarBaru)
            ) {
                Storage::disk('public')->delete($gambarBaru);
            }

            throw $exception;
        }

        // Hapus gambar lama setelah update berhasil.
        if (
            $gambarBaru &&
            $gambarLama &&
            Storage::disk('public')->exists($gambarLama)
        ) {
            Storage::disk('public')->delete($gambarLama);
        }

        return redirect()
            ->route('aset.index')
            ->with('success', 'Data aset berhasil diperbarui.');
    }

    /**
     * Mengaktifkan atau menonaktifkan Unit Aset.
     *
     * Fungsi ini khusus untuk perubahan status aktif/nonaktif.
     * Perubahan akibat peminjaman dan pengembalian tetap
     * dilakukan melalui controller transaksi.
     */
    public function ubahStatus(string $id)
    {
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

        // Aset tersedia dapat dinonaktifkan.
        if ($aset->status_aset === 'Tersedia') {
            $aset->update([
                'status_aset' => 'Nonaktif',
            ]);

            return redirect()
                ->route('aset.index')
                ->with('success', 'Aset berhasil dinonaktifkan.');
        }

        // Aset Nonaktif dapat diaktifkan kembali
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

            // Status mengikuti kondisi aset.
            $statusBaru = $aset->kondisi_aset === 'Baik'
                ? 'Tersedia'
                : 'Perlu Perbaikan';

            $aset->update([
                'status_aset' => $statusBaru,
            ]);

            return redirect()
                ->route('aset.index')
                ->with('success', 'Status aset berhasil diperbarui.');
        }

        // Aset Perlu Perbaikan tidak diubah melalui toggle ini.
        return redirect()
            ->route('aset.index')
            ->with(
                'error',
                'Status aset tidak dapat diubah melalui fungsi ini.'
            );
    }
}
