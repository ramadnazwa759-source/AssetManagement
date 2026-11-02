<?php

namespace Tests\Feature;

use App\Models\KategoriAset;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class KategoriAsetTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    // TEST CASE 1: Menampilkan kondisi data kosong
    public function test_sistem_menampilkan_data_kosong(): void
    {
        // Arrange
        KategoriAset::query()->delete();

        $sessionData = [
            'asset_management_authenticated' => true,
        ];

        // Act
        $response = $this->withSession($sessionData)
            ->get(route('kategori.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Belum ada data kategori aset.');
    }

    // TEST CASE 2: Admin dapat mengakses halaman tambah kategori
    public function test_admin_dapat_mengakses_halaman_tambah_kategori(): void
    {
        // Arrange
        $sessionData = [
            'asset_management_authenticated' => true,
        ];

        // Act
        $indexResponse = $this->withSession($sessionData)
            ->get(route('kategori.index'));

        $createResponse = $this->withSession($sessionData)
            ->get(route('kategori.create'));

        // Assert
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee(route('kategori.create'));
        $indexResponse->assertSee('Tambah Kategori Aset');

        $createResponse->assertStatus(200);
        $createResponse->assertSee('Tambah Kategori Aset');
        $createResponse->assertSee(route('kategori.store'));
    }

    // TEST CASE 3: Berhasil menambahkan kategori dengan data valid
    public function test_kategori_berhasil_disimpan_dengan_data_valid(): void
    {
        // Arrange
        $sessionData = [
            'asset_management_authenticated' => true,
        ];

        $payload = [
            'nama_kategori' => 'Peralatan Multimedia',
            'deskripsi' => 'Kategori untuk proyektor, kamera, dan audio',
        ];

        // Act
        $response = $this->withSession($sessionData)
            ->post(route('kategori.store'), $payload);

        // Assert
        $response->assertRedirect(route('kategori.index'));
        $response->assertSessionHas('success', 'Kategori berhasil ditambahkan.');

        $this->assertDatabaseHas('kategori_aset', $payload);

        $indexResponse = $this->withSession($sessionData)
            ->get(route('kategori.index'));

        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Peralatan Multimedia');
    }

    // TEST CASE 4: Menolak nama kategori kosong
    public function test_gagal_menambahkan_kategori_jika_nama_kosong(): void
    {
        // Arrange
        $sessionData = [
            'asset_management_authenticated' => true,
        ];

        $payload = [
            'nama_kategori' => '',
            'deskripsi' => 'Deskripsi tanpa nama kategori',
        ];

        // Act
        $response = $this->withSession($sessionData)
            ->post(route('kategori.store'), $payload);

        // Assert
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['nama_kategori']);

        $this->assertDatabaseMissing('kategori_aset', [
            'nama_kategori' => '',
        ]);
    }

    // TEST CASE 5: Menolak nama kategori duplikat
    public function test_gagal_menambahkan_kategori_jika_nama_duplikat(): void
    {
        // Arrange
        $sessionData = [
            'asset_management_authenticated' => true,
        ];

        KategoriAset::create([
            'id_kategori' => 'KAT-99',
            'nama_kategori' => 'Peralatan Kantor Utama',
            'deskripsi' => 'Kategori awal',
        ]);

        $payload = [
            'nama_kategori' => 'Peralatan Kantor Utama',
            'deskripsi' => 'Mencoba menambahkan kategori duplikat',
        ];

        // Act
        $response = $this->withSession($sessionData)
            ->post(route('kategori.store'), $payload);

        // Assert
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['nama_kategori']);

        $jumlahData = KategoriAset::where(
            'nama_kategori',
            'Peralatan Kantor Utama'
        )->count();

        $this->assertSame(1, $jumlahData);
    }
}