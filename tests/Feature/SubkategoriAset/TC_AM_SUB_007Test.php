<?php

namespace Tests\Feature\SubKategoriAset;

use Tests\TestCase;
use App\Models\KategoriAset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class TC_AM_SUB_007Test extends TestCase
{
    use RefreshDatabase;
        protected function setUp(): void
    {
        parent::setUp();

        $this->withSession([
            'asset_management_authenticated' => true,
        ]);
    }

    public function test_format_gambar_tidak_diizinkan()
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
}
