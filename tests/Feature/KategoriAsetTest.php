<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class KategoriAsetTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Pengujian penambahan kategori aset dengan nama_kategori kosong.
     * Sistem harus menolak input, mengembalikan error validasi,
     * dan memastikan data tidak tersimpan ke database.
     */
    public function test_menambahkan_kategori_aset_dengan_nama_kosong_gagal_validasi(): void
    {
        // 1. Arrange: Siapkan session autentikasi dan data uji dengan nama kosong
        $session = [
            'asset_management_authenticated' => true,
        ];

        $payload = [
            'nama_kategori' => '',
            'deskripsi' => 'Deskripsi untuk pengujian kategori dengan nama kosong',
        ];

        // 2. Act: Kirim request POST ke route kategori.store
        $response = $this->withSession($session)
            ->post(route('kategori.store'), $payload);

        // 3. Assert: Pastikan terjadi redirect kembali karena validasi gagal
        $response->assertStatus(302);

        // Pastikan session memiliki error validasi pada kolom nama_kategori
        $response->assertSessionHasErrors(['nama_kategori']);

        // Pastikan data dengan nama_kategori kosong tidak tersimpan di database
        $this->assertDatabaseMissing('kategori_aset', [
            'nama_kategori' => '',
        ]);
    }
}
