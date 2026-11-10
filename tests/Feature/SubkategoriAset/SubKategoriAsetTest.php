<?php

namespace Tests\Feature\SubKategoriAset;

use Tests\TestCase;
use App\Models\KategoriAset;
use App\Models\SubKategoriAset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SubKategoriAsetTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();

        // Mengaktifkan sesi autentikasi Asset Management
        $this->withSession([
            'asset_management_authenticated' => true,
        ]);

        // Menonaktifkan CSRF Laravel 13 selama pengujian
        $this->withoutMiddleware(
            \Illuminate\Foundation\Http\Middleware\PreventRequestForgery::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TC-AM-SUB-001
    |--------------------------------------------------------------------------
    | Judul:
    | Menampilkan Daftar Subkategori Aset
    |
    | Skenario:
    | Menampilkan daftar subkategori berdasarkan kategori induk.
    |
    | Hasil yang diharapkan:
    | Daftar subkategori berhasil ditampilkan.
    */

    public function test_tc_am_sub_001_menampilkan_daftar_subkategori(): void
    {
        KategoriAset::create([
            'id_kategori' => 'KAT-01',
            'nama_kategori' => 'Peralatan Outdoor',
        ]);

        SubKategoriAset::create([
            'id_sub_kategori' => 'SUB-01',
            'id_kategori' => 'KAT-01',
            'nama_sub_kategori' => 'Tenda',
        ]);

        $response = $this->get(
            route('kategori.sub-kategori.index', 'KAT-01')
        );

        $response->assertOk();
        $response->assertSee('Tenda');
    }


    /*
    |--------------------------------------------------------------------------
    | TC-AM-SUB-002
    |--------------------------------------------------------------------------
    | Judul:
    | Menampilkan Daftar Subkategori Aset
    |
    | Skenario:
    | Memeriksa tampilan tombol aksi dan pencarian subkategori.
    |
    | Hasil yang diharapkan:
    | Tombol Tambah dan fitur pencarian tersedia.
    */

    public function test_tc_am_sub_002_menampilkan_tombol_aksi_subkategori(): void
    {
        KategoriAset::create([
            'id_kategori' => 'KAT-01',
            'nama_kategori' => 'Peralatan Outdoor',
        ]);

        $response = $this->get(
            route('kategori.sub-kategori.index', 'KAT-01')
        );

        $response->assertOk();

        // Memastikan tombol Tambah tersedia.
        $response->assertSee('Tambah');

        // Memastikan elemen pencarian tersedia.
        $response->assertSee('search');
    }


    /*
    |--------------------------------------------------------------------------
    | TC-AM-SUB-003
    |--------------------------------------------------------------------------
    | Judul:
    | Menampilkan Daftar Subkategori Aset
    |
    | Skenario:
    | Menampilkan halaman subkategori ketika belum ada data.
    |
    | Hasil yang diharapkan:
    | Halaman tetap tampil dan daftar subkategori kosong.
    */

    public function test_tc_am_sub_003_halaman_tampil_ketika_data_kosong(): void
    {
        KategoriAset::create([
            'id_kategori' => 'KAT-01',
            'nama_kategori' => 'Peralatan Outdoor',
        ]);

        $response = $this->get(
            route('kategori.sub-kategori.index', 'KAT-01')
        );

        $response->assertOk();

        $response->assertViewHas('subKategori', function ($data) {
            return $data->isEmpty();
        });
    }


    /*
    |--------------------------------------------------------------------------
    | TC-AM-SUB-004
    |--------------------------------------------------------------------------
    | Judul:
    | Menambahkan Subkategori Aset
    |
    | Skenario:
    | Menambahkan subkategori menggunakan data yang valid.
    |
    | Hasil yang diharapkan:
    | Data subkategori berhasil disimpan.
    */

    public function test_tc_am_sub_004_menambahkan_subkategori_data_valid(): void
    {
        KategoriAset::create([
            'id_kategori' => 'KAT-01',
            'nama_kategori' => 'Peralatan Outdoor',
        ]);

        // Menonaktifkan CSRF hanya untuk pengujian ini.
        $this->withoutMiddleware(
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class
        );

        $response = $this->post(
            route('kategori.sub-kategori.store', 'KAT-01'),
            [
                'nama_sub_kategori' => 'Tenda Dome',
                'deskripsi' => 'Tenda untuk kegiatan outdoor',
            ]
        );

        $response->assertRedirect(
            route('kategori.sub-kategori.index', 'KAT-01')
        );

        $this->assertDatabaseHas('sub_kategori_aset', [
            'id_kategori' => 'KAT-01',
            'nama_sub_kategori' => 'Tenda Dome',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | TC-AM-SUB-005
    |--------------------------------------------------------------------------
    | Judul:
    | Menambahkan Subkategori Aset
    |
    | Skenario:
    | Menambahkan subkategori dengan kategori induk yang tidak ditemukan.
    |
    | Hasil yang diharapkan:
    | Sistem menolak penambahan karena kategori induk tidak tersedia.
    */

    public function test_tc_am_sub_005_kategori_induk_tidak_ditemukan(): void
    {
        $response = $this->post(
            route('kategori.sub-kategori.store', 'KAT-TIDAK-ADA'),
            [
                'nama_sub_kategori' => 'Tenda Dome',
            ]
        );

        $response->assertNotFound();

        $this->assertDatabaseMissing('sub_kategori_aset', [
            'nama_sub_kategori' => 'Tenda Dome',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | TC-AM-SUB-006
    |--------------------------------------------------------------------------
    | Judul:
    | Menambahkan Subkategori Aset
    |
    | Skenario:
    | Menambahkan subkategori dengan nama kosong.
    |
    | Hasil yang diharapkan:
    | Sistem menolak data dan menampilkan pesan validasi.
    */

    public function test_tc_am_sub_006_nama_subkategori_kosong_ditolak(): void
    {
        KategoriAset::create([
            'id_kategori' => 'KAT-01',
            'nama_kategori' => 'Peralatan Outdoor',
        ]);

        $response = $this->post(
            route('kategori.sub-kategori.store', 'KAT-01'),
            [
                'nama_sub_kategori' => '',
            ]
        );

        $response->assertSessionHasErrors([
            'nama_sub_kategori',
        ]);

        $this->assertDatabaseMissing('sub_kategori_aset', [
            'nama_sub_kategori' => '',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | TC-AM-SUB-007
    |--------------------------------------------------------------------------
    | Judul:
    | Menambahkan Subkategori Aset
    |
    | Skenario:
    | Menambahkan subkategori dengan format gambar yang tidak diizinkan.
    |
    | Hasil yang diharapkan:
    | Sistem menolak file dengan format PDF.
    */

    public function test_tc_am_sub_007_format_gambar_tidak_diizinkan(): void
    {
        KategoriAset::create([
            'id_kategori' => 'KAT-01',
            'nama_kategori' => 'Peralatan Outdoor',
        ]);

        Storage::fake('public');

        $response = $this->post(
            route('kategori.sub-kategori.store', 'KAT-01'),
            [
                'nama_sub_kategori' => 'Tenda Dome',
                'gambar' => UploadedFile::fake()->create(
                    'dokumen.pdf',
                    100,
                    'application/pdf'
                ),
            ]
        );

        $response->assertSessionHasErrors(['gambar']);

        $this->assertDatabaseMissing('sub_kategori_aset', [
            'nama_sub_kategori' => 'Tenda Dome',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | TC-AM-SUB-008
    |--------------------------------------------------------------------------
    | Judul:
    | Menambahkan Subkategori Aset
    |
    | Skenario:
    | Menambahkan subkategori dengan ukuran gambar melebihi batas.
    |
    | Hasil yang diharapkan:
    | Sistem menolak gambar yang melebihi batas ukuran.
    */

    public function test_tc_am_sub_008_ukuran_gambar_melebihi_batas(): void
    {
        KategoriAset::create([
            'id_kategori' => 'KAT-01',
            'nama_kategori' => 'Peralatan Outdoor',
        ]);

        Storage::fake('public');

        $response = $this->post(
            route('kategori.sub-kategori.store', 'KAT-01'),
            [
                'nama_sub_kategori' => 'Tenda Dome',
                'gambar' => UploadedFile::fake()
                    ->image('besar.jpg')
                    ->size(2500),
            ]
        );

        $response->assertSessionHasErrors(['gambar']);

        $this->assertDatabaseMissing('sub_kategori_aset', [
            'nama_sub_kategori' => 'Tenda Dome',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | TC-AM-SUB-009
    |--------------------------------------------------------------------------
    | Judul:
    | Mengubah Subkategori Aset
    |
    | Skenario:
    | Mengubah subkategori menggunakan data yang valid.
    |
    | Hasil yang diharapkan:
    | Data subkategori berhasil diperbarui.
    */

    public function test_tc_am_sub_009_mengubah_subkategori_data_valid(): void
    {
        KategoriAset::create([
            'id_kategori' => 'KAT-01',
            'nama_kategori' => 'Peralatan Outdoor',
        ]);

        SubKategoriAset::create([
            'id_sub_kategori' => 'SUB-01',
            'id_kategori' => 'KAT-01',
            'nama_sub_kategori' => 'Tenda Lama',
        ]);

        $response = $this->put(
            route('kategori.sub-kategori.update', [
                'id_kategori' => 'KAT-01',
                'id' => 'SUB-01',
            ]),
            [
                'nama_sub_kategori' => 'Tenda Baru',
                'deskripsi' => 'Tenda untuk camping',
            ]
        );

        $response->assertRedirect(
            route('kategori.sub-kategori.index', 'KAT-01')
        );

        $this->assertDatabaseHas('sub_kategori_aset', [
            'id_sub_kategori' => 'SUB-01',
            'nama_sub_kategori' => 'Tenda Baru',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | TC-AM-SUB-010
    |--------------------------------------------------------------------------
    | Judul:
    | Mengubah Subkategori Aset
    |
    | Skenario:
    | Mengubah nama subkategori tanpa mengganti gambar.
    |
    | Hasil yang diharapkan:
    | Nama berhasil diperbarui dan gambar lama tetap tersimpan.
    */

    public function test_tc_am_sub_010_mengubah_subkategori_tanpa_mengganti_gambar(): void
    {
        KategoriAset::create([
            'id_kategori' => 'KAT-01',
            'nama_kategori' => 'Peralatan Outdoor',
        ]);

        Storage::fake('public');

        Storage::disk('public')->put(
            'sub-kategori/tenda.jpg',
            'gambar-lama'
        );

        SubKategoriAset::create([
            'id_sub_kategori' => 'SUB-01',
            'id_kategori' => 'KAT-01',
            'nama_sub_kategori' => 'Tenda Lama',
            'gambar' => 'sub-kategori/tenda.jpg',
        ]);

        $response = $this->put(
            route('kategori.sub-kategori.update', [
                'id_kategori' => 'KAT-01',
                'id' => 'SUB-01',
            ]),
            [
                'nama_sub_kategori' => 'Tenda Baru',
            ]
        );

        $response->assertRedirect(
            route('kategori.sub-kategori.index', 'KAT-01')
        );

        $this->assertDatabaseHas('sub_kategori_aset', [
            'id_sub_kategori' => 'SUB-01',
            'nama_sub_kategori' => 'Tenda Baru',
            'gambar' => 'sub-kategori/tenda.jpg',
        ]);

        Storage::disk('public')->assertExists(
            'sub-kategori/tenda.jpg'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | TC-AM-SUB-011
    |--------------------------------------------------------------------------
    | Judul:
    | Mengubah Subkategori Aset
    |
    | Skenario:
    | Mengubah subkategori dengan nama kosong.
    |
    | Hasil yang diharapkan:
    | Sistem menolak perubahan dan mempertahankan nama sebelumnya.
    */

    public function test_tc_am_sub_011_nama_kosong_saat_edit_ditolak(): void
    {
        KategoriAset::create([
            'id_kategori' => 'KAT-01',
            'nama_kategori' => 'Peralatan Outdoor',
        ]);

        SubKategoriAset::create([
            'id_sub_kategori' => 'SUB-01',
            'id_kategori' => 'KAT-01',
            'nama_sub_kategori' => 'Tenda Lama',
        ]);

        $response = $this->put(
            route('kategori.sub-kategori.update', [
                'id_kategori' => 'KAT-01',
                'id' => 'SUB-01',
            ]),
            [
                'nama_sub_kategori' => '',
            ]
        );

        $response->assertSessionHasErrors([
            'nama_sub_kategori',
        ]);

        $this->assertDatabaseHas('sub_kategori_aset', [
            'id_sub_kategori' => 'SUB-01',
            'nama_sub_kategori' => 'Tenda Lama',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | TC-AM-SUB-012
    |--------------------------------------------------------------------------
    | Judul:
    | Mengubah Subkategori Aset
    |
    | Skenario:
    | Mengubah gambar subkategori menggunakan format yang tidak sesuai.
    |
    | Hasil yang diharapkan:
    | Sistem menolak gambar dan mempertahankan data sebelumnya.
    */

    public function test_tc_am_sub_012_gambar_pengganti_tidak_sesuai(): void
    {
        KategoriAset::create([
            'id_kategori' => 'KAT-01',
            'nama_kategori' => 'Peralatan Outdoor',
        ]);

        Storage::fake('public');

        SubKategoriAset::create([
            'id_sub_kategori' => 'SUB-01',
            'id_kategori' => 'KAT-01',
            'nama_sub_kategori' => 'Tenda Lama',
            'gambar' => 'sub-kategori/lama.jpg',
        ]);

        $response = $this->put(
            route('kategori.sub-kategori.update', [
                'id_kategori' => 'KAT-01',
                'id' => 'SUB-01',
            ]),
            [
                'nama_sub_kategori' => 'Tenda Baru',
                'gambar' => UploadedFile::fake()->create(
                    'dokumen.pdf',
                    100,
                    'application/pdf'
                ),
            ]
        );

        $response->assertSessionHasErrors(['gambar']);

        $this->assertDatabaseHas('sub_kategori_aset', [
            'id_sub_kategori' => 'SUB-01',
            'nama_sub_kategori' => 'Tenda Lama',
            'gambar' => 'sub-kategori/lama.jpg',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | TC-AM-SUB-013
    |--------------------------------------------------------------------------
    | Judul:
    | Mencari Subkategori Aset
    |
    | Skenario:
    | Mencari subkategori menggunakan kata kunci yang sesuai.
    |
    | Hasil yang diharapkan:
    | Sistem menampilkan data yang sesuai dengan kata kunci.
    */

    public function test_tc_am_sub_013_mencari_subkategori_kata_kunci_sesuai(): void
    {
        KategoriAset::create([
            'id_kategori' => 'KAT-01',
            'nama_kategori' => 'Peralatan Outdoor',
        ]);

        SubKategoriAset::create([
            'id_sub_kategori' => 'SUB-01',
            'id_kategori' => 'KAT-01',
            'nama_sub_kategori' => 'Tenda Dome',
        ]);

        SubKategoriAset::create([
            'id_sub_kategori' => 'SUB-02',
            'id_kategori' => 'KAT-01',
            'nama_sub_kategori' => 'Kursi Lipat',
        ]);

        $response = $this->get(
            route('kategori.sub-kategori.index', 'KAT-01')
            . '?search=Tenda'
        );

        $response->assertOk();
        $response->assertSee('Tenda Dome');
        $response->assertDontSee('Kursi Lipat');
    }


    /*
    |--------------------------------------------------------------------------
    | TC-AM-SUB-014
    |--------------------------------------------------------------------------
    | Judul:
    | Mencari Subkategori Aset
    |
    | Skenario:
    | Mencari subkategori menggunakan kata kunci yang tidak ditemukan.
    |
    | Hasil yang diharapkan:
    | Sistem menampilkan daftar kosong tanpa menampilkan data yang tidak sesuai.
    */

    public function test_tc_am_sub_014_pencarian_subkategori_tidak_ditemukan(): void
    {
        KategoriAset::create([
            'id_kategori' => 'KAT-01',
            'nama_kategori' => 'Peralatan Outdoor',
        ]);

        SubKategoriAset::create([
            'id_sub_kategori' => 'SUB-01',
            'id_kategori' => 'KAT-01',
            'nama_sub_kategori' => 'Tenda Dome',
        ]);

        $response = $this->get(
            route('kategori.sub-kategori.index', 'KAT-01')
            . '?search=Helikopter'
        );

        $response->assertOk();

        $response->assertViewHas('subKategori', function ($data) {
            return $data->isEmpty();
        });

        $response->assertDontSee('Tenda Dome');
    }
}
