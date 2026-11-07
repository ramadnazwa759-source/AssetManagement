<?php

namespace Tests\Feature\SubKategoriAset;

use Tests\TestCase;
use App\Models\KategoriAset;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TC_AM_SUB_004Test extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class
        );

        $this->withSession([
            'asset_management_authenticated' => true,
        ]);
    }

    public function test_menambahkan_subkategori_dengan_data_valid()
    {
        KategoriAset::create([
            'id_kategori' => 'KAT-01',
            'nama_kategori' => 'Peralatan Outdoor',
        ]);

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
}
