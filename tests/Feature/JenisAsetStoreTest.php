<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

// class pengujian
class JenisAsetStoreTest extends TestCase
{
    use RefreshDatabase;

    // TC-AM-JNS-006 Admin berhasil menambahkan jenis aset dengan data valid.

    public function test_admin_berhasil_menambahkan_jenis_aset(): void
    {
        // Menyiapkan data subkategori yang sudah tersedia.
        $subkategori = DB::table('sub_kategori_aset')
            ->where('nama_sub_kategori', 'Perlengkapan Keselamatan')
            ->first();

        $this->assertNotNull(
            $subkategori,
            'Data subkategori Perlengkapan Keselamatan belum tersedia.'
        );

        // Menyiapkan data yang akan dikirim.
        $data = [
            'nama_jenis' => 'Pelampung',
            'id_sub_kategori_aset' => $subkategori->id_sub_kategori,
            'stok' => 1,
            'deskripsi' => 'Pelampung untuk perlengkapan keselamatan.',
        ];

        // Menyimpan jumlah aset sebelum pengujian.
        $jumlahAsetSebelum = DB::table('aset')->count();

        // ACT
        // Mengirim data ke fungsi store().
        $response = $this->post(
            route('jenis-aset.store'),
            $data
        );

        // ASSERT
        // Memastikan sistem mengarahkan kembali ke halaman daftar.
        $response->assertRedirect(route('jenis-aset.index'));

        // Memastikan pesan berhasil ditampilkan.
        $response->assertSessionHas(
            'success',
            'Jenis aset berhasil ditambahkan.'
        );

        // Memastikan Jenis Aset berhasil tersimpan.
        $this->assertDatabaseHas('jenis_aset', [
            'nama_jenis' => 'Pelampung',
            'id_sub_kategori_aset' => $subkategori->id_sub_kategori,
            'stok' => 1,
            'status_jenis' => 'Aktif',
        ]);

        // Mengambil data Jenis Aset yang baru disimpan.
        $jenis = DB::table('jenis_aset')
            ->where('nama_jenis', 'Pelampung')
            ->first();

        // Memastikan kode Jenis Aset dibuat otomatis.
        $this->assertNotEmpty($jenis->id_jenis);
        $this->assertMatchesRegularExpression(
            '/^JA-\d+$/',
            $jenis->id_jenis
        );

        
    }
}
