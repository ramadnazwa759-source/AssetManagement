<?php

namespace App\Http\Controllers;

use App\Models\JenisAset;
use App\Models\Aset;
use App\Models\SubKategoriAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class JenisAsetController extends Controller
{
    /**
     * Menampilkan daftar Jenis Aset.
     *
     * FR-AM-JNS-001
     * FR-AM-JNS-004
     */
    public function index(Request $request)
    {
        $request->validate([
            'search' => [
                'nullable',
                'string',
                'max:100',
            ],
        ]);

        $search = trim($request->input('search', ''));

        $jenis = JenisAset::with('subKategori')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('id_jenis', 'like', "%{$search}%")
                        ->orWhere('nama_jenis', 'like', "%{$search}%")
                        ->orWhereHas('subKategori', function ($query) use ($search) {
                            $query->where(
                                'nama_sub_kategori',
                                'like',
                                "%{$search}%"
                            );
                        });
                });
            })
            ->orderBy('id_jenis', 'desc')
            ->get();

        return view(
            'jenis-aset.index',
            compact('jenis')
        );
    }


    /**
     * Menampilkan form tambah Jenis Aset.
     *
     * FR-AM-JNS-002
     */
    public function create()
    {
        $subKategori = SubKategoriAset::orderBy(
            'nama_sub_kategori'
        )->get();

        return view(
            'jenis-aset.create',
            compact('subKategori')
        );
    }


    /**
     * Menyimpan Jenis Aset baru.
     *
     * FR-AM-JNS-002
     *
     * BR:
     * - Subkategori wajib valid
     * - Kode dibuat otomatis
     * - Kode tidak boleh berasal dari input Admin
     *
     * VR:
     * - VR-AM-JNS-001
     * - VR-AM-JNS-002
     * - VR-AM-JNS-003
     * - VR-AM-JNS-004
     * - VR-AM-JNS-005
     */
    public function store(Request $request)
    {
        /*
         * Jangan menerima id_jenis dari Admin.
         * Kode Jenis Aset dibuat sepenuhnya oleh server.
         */
        $validated = $request->validate([
            'id_sub_kategori_aset' => [
                'required',
                'exists:sub_kategori_aset,id_sub_kategori_aset',
            ],

            'nama_jenis' => [
                'required',
                'string',
                'min:3',
                'max:100',
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

        /*
         * Trim nama sebelum disimpan.
         */
        $validated['nama_jenis'] = trim(
            $validated['nama_jenis']
        );

        /*
         * Setelah trim tidak boleh kosong.
         */
        if ($validated['nama_jenis'] === '') {
            return back()
                ->withErrors([
                    'nama_jenis' => 'Nama jenis aset wajib diisi.'
                ])
                ->withInput();
        }

        /*
         * Memastikan nama tidak sama dengan Jenis Aset lain.
         */
        $namaSudahAda = JenisAset::where(
            'nama_jenis',
            $validated['nama_jenis']
        )->exists();

        if ($namaSudahAda) {
            return back()
                ->withErrors([
                    'nama_jenis' => 'Nama jenis aset sudah digunakan.'
                ])
                ->withInput();
        }

        DB::transaction(function () use ($validated) {

            /*
             * Generate kode Jenis Aset di server.
             */
            $idJenisBaru = $this->generateKodeJenis();

            /*
             * Simpan Jenis Aset.
             */
            $jenis = JenisAset::create([
                'id_jenis' => $idJenisBaru,
                'id_sub_kategori_aset' =>
                    $validated['id_sub_kategori_aset'],
                'nama_jenis' =>
                    $validated['nama_jenis'],
                'stok' =>
                    $validated['stok'],
                'status_jenis' =>
                    'Aktif',
                'deskripsi' =>
                    $validated['deskripsi'] ?? null,
            ]);

            /*
             * Membuat Unit/Data Aset sesuai stok awal.
             */
            for (
                $i = 1;
                $i <= $validated['stok'];
                $i++
            ) {
                Aset::create([
                    'kode_aset' => $this->generateKodeAset(
                        $jenis->nama_jenis,
                        $i
                    ),

                    'id_jenis' =>
                        $jenis->id_jenis,

                    'id_lokasi' =>
                        null,

                    'nama_aset' =>
                        $jenis->nama_jenis,

                    'tanggal_beli' =>
                        null,

                    'kondisi_aset' =>
                        'Baik',

                    'status_aset' =>
                        'Tersedia',

                    'gambar' =>
                        null,

                    'keterangan' =>
                        null,
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
     * Menampilkan form edit Jenis Aset.
     *
     * FR-AM-JNS-003
     */
    public function edit(string $id)
    {
        $jenis = JenisAset::findOrFail($id);

        $subKategori = SubKategoriAset::orderBy(
            'nama_sub_kategori'
        )->get();

        return view(
            'jenis-aset.edit',
            compact('jenis', 'subKategori')
        );
    }


    /**
     * Mengubah Jenis Aset.
     *
     * FR-AM-JNS-003
     *
     * Kode Jenis Aset tidak dapat diubah.
     */
    public function update(Request $request, string $id)
    {
        /*
         * Pastikan ID Jenis Aset benar-benar ada.
         */
        $jenis = JenisAset::findOrFail($id);

        /*
         * Jangan menerima id_jenis sebagai data yang
         * dapat diperbarui.
         */
        $validated = $request->validate([
            'id_sub_kategori_aset' => [
                'required',
                'exists:sub_kategori_aset,id_sub_kategori_aset',
            ],

            'nama_jenis' => [
                'required',
                'string',
                'min:3',
                'max:100',
            ],

            'deskripsi' => [
                'nullable',
                'string',
            ],
        ]);

        /*
         * Trim nama.
         */
        $validated['nama_jenis'] = trim(
            $validated['nama_jenis']
        );

        /*
         * Nama setelah trim tidak boleh kosong.
         */
        if ($validated['nama_jenis'] === '') {
            return back()
                ->withErrors([
                    'nama_jenis' => 'Nama jenis aset wajib diisi.'
                ])
                ->withInput();
        }

        /*
         * Cek nama Jenis Aset agar tidak duplikat.
         * Data milik ID yang sedang diedit dikecualikan.
         */
        $namaSudahAda = JenisAset::where(
            'nama_jenis',
            $validated['nama_jenis']
        )
            ->where(
                'id_jenis',
                '!=',
                $jenis->id_jenis
            )
            ->exists();

        if ($namaSudahAda) {
            return back()
                ->withErrors([
                    'nama_jenis' => 'Nama jenis aset sudah digunakan.'
                ])
                ->withInput();
        }

        /*
         * Kode tidak ikut di-update.
         */
        $jenis->update([
            'id_sub_kategori_aset' =>
                $validated['id_sub_kategori_aset'],

            'nama_jenis' =>
                $validated['nama_jenis'],

            'deskripsi' =>
                $validated['deskripsi'] ?? null,
        ]);

        return redirect()
            ->route('jenis-aset.index')
            ->with(
                'success',
                'Jenis aset berhasil diperbarui.'
            );
    }


    /**
     * Menambahkan stok Jenis Aset.
     *
     * FR-AM-JNS-005
     *
     * BR:
     * - Hanya Jenis Aset Aktif yang dapat ditambah stok
     * - Setiap stok menghasilkan satu Unit/Data Aset
     * - Unit terhubung dengan Jenis Aset
     *
     * VR:
     * - VR-AM-JNS-009
     * - VR-AM-JNS-010
     * - VR-AM-JNS-011
     * - VR-AM-JNS-012
     */
    public function addStock(Request $request, string $id)
    {
        /*
         * Pastikan Jenis Aset benar-benar ada.
         */
        $jenis = JenisAset::findOrFail($id);

        /*
         * Validasi jumlah stok.
         */
        $validated = $request->validate([
            'tambah_stok' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        /*
         * Jenis Aset Nonaktif tidak boleh
         * digunakan untuk penambahan stok.
         */
        if ($jenis->status_jenis !== 'Aktif') {
            return back()
                ->with(
                    'error',
                    'Stok tidak dapat ditambahkan karena jenis aset sedang nonaktif.'
                );
        }

        DB::transaction(function () use (
            $jenis,
            $validated
        ) {

            /*
             * Ambil stok terbaru dari database.
             */
            $jenis = JenisAset::where(
                'id_jenis',
                $jenis->id_jenis
            )
                ->lockForUpdate()
                ->firstOrFail();

            /*
             * Cek kembali status setelah row dikunci.
             */
            if ($jenis->status_jenis !== 'Aktif') {
                throw new \RuntimeException(
                    'Jenis aset sedang nonaktif.'
                );
            }

            $stokLama = (int) $jenis->stok;

            $tambahStok = (int) $validated['tambah_stok'];

            $stokBaru = $stokLama + $tambahStok;

            /*
             * Update stok Jenis Aset.
             */
            $jenis->update([
                'stok' => $stokBaru,
            ]);

            /*
             * Membuat Unit/Data Aset baru.
             */
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

                    'id_jenis' =>
                        $jenis->id_jenis,

                    'id_lokasi' =>
                        null,

                    'nama_aset' =>
                        $jenis->nama_jenis,

                    'tanggal_beli' =>
                        null,

                    'kondisi_aset' =>
                        'Baik',

                    'status_aset' =>
                        'Tersedia',

                    'gambar' =>
                        null,

                    'keterangan' =>
                        null,
                ]);
            }
        });

        return redirect()
            ->route('jenis-aset.index')
            ->with(
                'success',
                'Stok aset berhasil ditambahkan.'
            );
    }


    /**
     * Mengubah status Jenis Aset.
     *
     * FR-AM-JNS-006
     *
     * VR:
     * - VR-AM-JNS-013
     * - VR-AM-JNS-014
     */
    public function ubahStatus(string $id)
    {
        /*
         * ID harus diverifikasi melalui database.
         */
        $jenis = JenisAset::findOrFail($id);

        DB::transaction(function () use ($jenis) {

            /*
             * Ambil data terbaru dan kunci record.
             */
            $jenis = JenisAset::where(
                'id_jenis',
                $jenis->id_jenis
            )
                ->lockForUpdate()
                ->firstOrFail();

            /*
             * Status hanya boleh Aktif atau Nonaktif.
             */
            if (
                !in_array(
                    $jenis->status_jenis,
                    ['Aktif', 'Nonaktif'],
                    true
                )
            ) {
                throw new \RuntimeException(
                    'Status jenis aset tidak valid.'
                );
            }

            /*
             * Jika Aktif -> Nonaktif.
             */
            if ($jenis->status_jenis === 'Aktif') {

                $jenis->update([
                    'status_jenis' => 'Nonaktif',
                ]);

                /*
                 * Semua unit yang terhubung
                 * menjadi Nonaktif.
                 */
                $jenis->aset()->update([
                    'status_aset' => 'Nonaktif',
                ]);

            } else {

                /*
                 * Jika Nonaktif -> Aktif.
                 */
                $jenis->update([
                    'status_jenis' => 'Aktif',
                ]);

                /*
                 * Unit yang sebelumnya Nonaktif
                 * karena jenis aset nonaktif
                 * dikembalikan menjadi Tersedia.
                 */
                $jenis->aset()
                    ->where('status_aset', 'Nonaktif')
                    ->update([
                        'status_aset' => 'Tersedia',
                    ]);
            }
        });

        $pesan = $jenis->status_jenis === 'Aktif'
            ? 'Jenis aset berhasil diaktifkan.'
            : 'Jenis aset dan unit aset berhasil dinonaktifkan.';

        return redirect()
            ->route('jenis-aset.index')
            ->with(
                'success',
                $pesan
            );
    }


    /**
     * Generate kode Jenis Aset.
     *
     * Format:
     * JA-001
     * JA-002
     * JA-003
     * dst.
     *
     * Kode tidak berasal dari input Admin.
     */
    private function generateKodeJenis(): string
    {
        $lastJenis = JenisAset::orderBy(
            'id_jenis',
            'desc'
        )->first();

        if (!$lastJenis) {
            $nomorBaru = 1;
        } else {
            $nomorTerakhir = (int) str_replace(
                'JA-',
                '',
                $lastJenis->id_jenis
            );

            $nomorBaru = $nomorTerakhir + 1;
        }

        /*
         * Pastikan kode benar-benar unik.
         */
        do {
            $idJenisBaru = 'JA-' . str_pad(
                $nomorBaru,
                3,
                '0',
                STR_PAD_LEFT
            );

            $sudahAda = JenisAset::where(
                'id_jenis',
                $idJenisBaru
            )->exists();

            if ($sudahAda) {
                $nomorBaru++;
            }

        } while ($sudahAda);

        return $idJenisBaru;
    }


    /**
     * Membuat kode Unit/Data Aset.
     *
     * Prefix diambil dari nama Jenis Aset.
     */
    private function generateKodeAset(
        string $namaJenis,
        int $nomor
    ): string {
        $prefix = $this->getPrefix($namaJenis);

        /*
         * Cek agar kode unit tidak bentrok.
         */
        $kode = $prefix . str_pad(
            $nomor,
            2,
            '0',
            STR_PAD_LEFT
        );

        /*
         * Jika kode sudah ada, gunakan nomor berikutnya.
         */
        while (
            Aset::where(
                'kode_aset',
                $kode
            )->exists()
        ) {
            $nomor++;

            $kode = $prefix . str_pad(
                $nomor,
                2,
                '0',
                STR_PAD_LEFT
            );
        }

        return $kode;
    }


    /**
     * Mengambil prefix kode Unit/Data Aset
     * dari nama Jenis Aset.
     */
    private function getPrefix(string $namaJenis): string
    {
        $nama = preg_replace(
            '/[^a-zA-Z]/',
            '',
            trim($namaJenis)
        );

        /*
         * Jika nama tidak menghasilkan karakter
         * alfabet, gunakan prefix AST.
         */
        if ($nama === '') {
            return 'AST';
        }

        return strtoupper(
            substr($nama, 0, 3)
        );
    }
}