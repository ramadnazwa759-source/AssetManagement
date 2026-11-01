<?php

namespace App\Http\Controllers;

use App\Models\JenisAset;
use App\Models\Aset;
use App\Models\SubKategoriAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JenisAsetController extends Controller
{
    /**
     * FR-AM-JNS-001
     * Menampilkan daftar Jenis Aset.
     *
     * FR-AM-JNS-004
     * Mencari Jenis Aset.
     */
    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:100',
        ]);

        $search = trim($request->input('search', ''));

        $jenis = JenisAset::with('subKategori')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('id_jenis', 'like', '%' . $search . '%')
                        ->orWhere('nama_jenis', 'like', '%' . $search . '%')
                        ->orWhere('status_jenis', 'like', '%' . $search . '%')
                        ->orWhereHas('subKategori', function ($subQuery) use ($search) {
                            $subQuery->where(
                                'nama_sub_kategori',
                                'like',
                                '%' . $search . '%'
                            );
                        });
                });
            })
            ->latest()
            ->get();

        return view(
            'jenis-aset.index',
            compact('jenis')
        );
    }


    /**
     * FR-AM-JNS-002
     * Menampilkan form tambah Jenis Aset.
     */
    public function create()
    {
        $subKategori = SubKategoriAset::all();

        // Kode Jenis Aset dibuat otomatis oleh sistem.
        $idJenisBaru = $this->generateKodeJenis();

        return view(
            'jenis-aset.create',
            compact('subKategori', 'idJenisBaru')
        );
    }


    /**
     * FR-AM-JNS-002
     * Menambahkan Jenis Aset.
     */
    public function store(Request $request)
    {
        // VR-AM-JNS-004
        // Admin tidak boleh menentukan kode jenis secara manual.
        if ($request->has('id_jenis')) {
            return back()
                ->withInput()
                ->withErrors([
                    'id_jenis' => 'Kode Jenis Aset dibuat otomatis oleh sistem.',
                ]);
        }

        // VR-AM-JNS-001 dan VR-AM-JNS-002
        $request->merge([
            'nama_jenis' => trim($request->input('nama_jenis', '')),
        ]);

        $validated = $request->validate([
            'id_sub_kategori_aset' => [
                'required',
                'exists:sub_kategori_aset,id_sub_kategori',
            ],

            'nama_jenis' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'unique:jenis_aset,nama_jenis',
            ],

            'stok' => [
                'required',
                'integer',
                'min:0',
            ],

            'deskripsi' => [
                'nullable',
                'string',
            ],
        ]);

        DB::transaction(function () use ($validated) {

            // Kode Jenis Aset dibuat otomatis oleh sistem.
            $idJenis = $this->generateKodeJenis();

            $jenis = JenisAset::create([
                'id_jenis' => $idJenis,
                'id_sub_kategori_aset' => $validated['id_sub_kategori_aset'],
                'nama_jenis' => $validated['nama_jenis'],
                'stok' => $validated['stok'],
                'status_jenis' => 'Aktif',
                'deskripsi' => $validated['deskripsi'] ?? null,
            ]);

            // Membuat unit aset sesuai stok awal.
            // created_at otomatis mencatat waktu setiap unit dibuat.
            for ($i = 0; $i < $validated['stok']; $i++) {

                Aset::create([
                    'kode_aset' => $this->generateKodeAset(
                        $jenis->nama_jenis,
                        $jenis->id_jenis
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
                'Jenis aset berhasil ditambahkan.'
            );
    }


    /**
     * Menampilkan detail Jenis Aset.
     */
    public function show(string $id)
    {
        $jenis = JenisAset::with([
            'subKategori',
            'aset',
        ])->findOrFail($id);

        return view(
            'jenis-aset.show',
            compact('jenis')
        );
    }


    /**
     * FR-AM-JNS-003
     * Menampilkan form edit.
     */
    public function edit(string $id)
    {
        $jenis = JenisAset::findOrFail($id);

        $subKategori = SubKategoriAset::all();

        return view(
            'jenis-aset.update',
            compact('jenis', 'subKategori')
        );
    }


    /**
     * FR-AM-JNS-003
     * Mengubah Jenis Aset.
     */
    public function update(Request $request, string $id)
    {
        // VR-AM-JNS-007
        $jenis = JenisAset::find($id);

        if (!$jenis) {
            return back()
                ->withInput()
                ->withErrors([
                    'jenis' => 'Jenis Aset yang akan diubah tidak ditemukan.',
                ]);
        }

        // VR-AM-JNS-006
        // Kode Jenis Aset tidak boleh diubah.
        if ($request->has('id_jenis')) {
            return back()
                ->withInput()
                ->withErrors([
                    'id_jenis' => 'Kode Jenis Aset tidak dapat diubah.',
                ]);
        }

        // VR-AM-JNS-001 dan VR-AM-JNS-002
        $request->merge([
            'nama_jenis' => trim($request->input('nama_jenis', '')),
        ]);

        $validated = $request->validate([
            'id_sub_kategori_aset' => [
                'required',
                'exists:sub_kategori_aset,id_sub_kategori',
            ],

            'nama_jenis' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'unique:jenis_aset,nama_jenis,' .
                    $jenis->id_jenis .
                    ',id_jenis',
            ],

            'deskripsi' => [
                'nullable',
                'string',
            ],
        ]);

        $jenis->update([
            'id_sub_kategori_aset' => $validated['id_sub_kategori_aset'],
            'nama_jenis' => $validated['nama_jenis'],
            'deskripsi' => $validated['deskripsi'] ?? null,
        ]);

        return redirect()
            ->route('jenis-aset.index')
            ->with(
                'success',
                'Jenis aset berhasil diperbarui.'
            );
    }


    /**
     * FR-AM-JNS-005
     * Menambahkan stok Jenis Aset.
     */
    public function addStock(Request $request, string $id)
    {
        // VR-AM-JNS-009
        $validated = $request->validate([
            'tambah_stok' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        // Proses penambahan stok dilakukan dalam transaksi.
        $berhasil = DB::transaction(function () use ($id, $validated) {

            // Mengunci data Jenis Aset selama proses penambahan stok.
            $jenis = JenisAset::where('id_jenis', $id)
                ->lockForUpdate()
                ->firstOrFail();

            // VR-AM-JNS-010
            // Jenis Aset Nonaktif tidak boleh ditambahkan stok.
            if ($jenis->status_jenis === 'Nonaktif') {
                return false;
            }

            $stokLama = (int) $jenis->stok;
            $tambahStok = (int) $validated['tambah_stok'];

            $stokBaru = $stokLama + $tambahStok;

            // Memperbarui jumlah stok pada Jenis Aset.
            $jenis->update([
                'stok' => $stokBaru,
            ]);

            // VR-AM-JNS-012
            // Membuat unit baru sebanyak jumlah stok tambahan.
            // Setiap unit mendapatkan created_at otomatis.
            for ($i = 0; $i < $tambahStok; $i++) {

                Aset::create([
                    'kode_aset' => $this->generateKodeAset(
                        $jenis->nama_jenis,
                        $jenis->id_jenis
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

            return true;
        });

        if (!$berhasil) {
            return back()
                ->withErrors([
                    'tambah_stok' =>
                        'Jenis Aset Nonaktif tidak dapat digunakan untuk menambah stok.',
                ]);
        }

        return redirect()
            ->route('jenis-aset.index')
            ->with(
                'success',
                'Stok aset berhasil ditambahkan.'
            );
    }


    /**
     * FR-AM-JNS-006
     * Mengubah status Jenis Aset.
     */
    public function ubahStatus(string $id)
    {
        DB::transaction(function () use ($id) {

            $jenis = JenisAset::where('id_jenis', $id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($jenis->status_jenis === 'Aktif') {

                // Menonaktifkan Jenis Aset.
                $jenis->update([
                    'status_jenis' => 'Nonaktif',
                ]);

                /*
                 * Hanya unit yang berstatus Tersedia
                 * yang diubah menjadi Nonaktif.
                 *
                 * Unit Dipinjam dan Perlu Perbaikan
                 * tetap mempertahankan statusnya.
                 */
                $jenis->aset()
                    ->where('status_aset', 'Tersedia')
                    ->update([
                        'status_aset' => 'Nonaktif',
                    ]);

            } else {

                // Mengaktifkan kembali Jenis Aset.
                $jenis->update([
                    'status_jenis' => 'Aktif',
                ]);

                /*
                 * Unit yang sebelumnya Nonaktif
                 * dikembalikan sesuai kondisi masing-masing.
                 */
                $unitNonaktif = $jenis->aset()
                    ->where('status_aset', 'Nonaktif')
                    ->get();

                foreach ($unitNonaktif as $aset) {

                    $statusBaru = $aset->kondisi_aset === 'Baik'
                        ? 'Tersedia'
                        : 'Perlu Perbaikan';

                    $aset->update([
                        'status_aset' => $statusBaru,
                    ]);
                }
            }
        });

        $jenis = JenisAset::findOrFail($id);

        $pesan = $jenis->status_jenis === 'Aktif'
            ? 'Jenis aset dan unit aset berhasil diaktifkan.'
            : 'Jenis aset dan unit aset berhasil dinonaktifkan.';

        return redirect()
            ->route('jenis-aset.index')
            ->with('success', $pesan);
    }


    /**
     * Generate kode Jenis Aset otomatis.
     *
     * Format:
     * JA-001
     * JA-002
     * JA-003
     */
    private function generateKodeJenis(): string
    {
        $jenisTerakhir = JenisAset::query()
            ->orderByRaw(
                "CAST(SUBSTRING(id_jenis, 4) AS UNSIGNED) DESC"
            )
            ->first();

        if (!$jenisTerakhir) {
            $nomorBaru = 1;
        } else {
            $nomorTerakhir = (int) str_replace(
                'JA-',
                '',
                $jenisTerakhir->id_jenis
            );

            $nomorBaru = $nomorTerakhir + 1;
        }

        do {
            $kode = 'JA-' . str_pad(
                $nomorBaru,
                3,
                '0',
                STR_PAD_LEFT
            );

            $nomorBaru++;

        } while (
            JenisAset::where('id_jenis', $kode)->exists()
        );

        return $kode;
    }


    /**
     * Generate kode Unit/Data Aset.
     *
     * Contoh:
     * LAP01
     * LAP02
     * KUR01
     */
    private function generateKodeAset(
        string $namaJenis,
        string $idJenis
    ): string {
        $prefix = $this->getPrefix($namaJenis);

        $nomor = Aset::where('id_jenis', $idJenis)->count() + 1;

        do {
            $kode = $prefix . str_pad(
                $nomor,
                2,
                '0',
                STR_PAD_LEFT
            );

            $nomor++;

        } while (
            Aset::where('kode_aset', $kode)->exists()
        );

        return $kode;
    }


    /**
     * Mengambil 3 huruf pertama dari nama Jenis Aset.
     */
    private function getPrefix(string $namaJenis): string
    {
        $nama = preg_replace(
            '/[^a-zA-Z]/',
            '',
            trim($namaJenis)
        );

        $prefix = strtoupper(
            substr($nama, 0, 3)
        );

        return str_pad(
            $prefix,
            3,
            'X'
        );
    }
}
