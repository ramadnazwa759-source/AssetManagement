<?php

namespace Tests\Feature\SubKategoriAset;

use Tests\TestCase;
use App\Models\KategoriAset;
use App\Models\SubKategoriAset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class TC_AM_SUB_012Test extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();

        $this->withSession([
            'asset_management_authenticated' => true,
        ]);
    }

    public function test_gambar_pengganti_tidak_sesuai()
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
}
