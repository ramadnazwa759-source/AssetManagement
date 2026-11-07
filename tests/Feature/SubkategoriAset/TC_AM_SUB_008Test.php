<?php

namespace Tests\Feature\SubKategoriAset;

use Tests\TestCase;
use App\Models\KategoriAset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class TC_AM_SUB_008Test extends TestCase
{
    use RefreshDatabase;
        protected function setUp(): void
    {
        parent::setUp();

        $this->withSession([
            'asset_management_authenticated' => true,
        ]);
    }

    public function test_ukuran_gambar_melebihi_batas()
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
}
