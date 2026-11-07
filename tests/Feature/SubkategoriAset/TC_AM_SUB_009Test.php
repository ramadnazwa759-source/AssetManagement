<?php

namespace Tests\Feature\SubKategoriAset;

use Tests\TestCase;
use App\Models\KategoriAset;
use App\Models\SubKategoriAset;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TC_AM_SUB_009Test extends TestCase
{
    use RefreshDatabase;
        protected function setUp(): void
    {
        parent::setUp();

        $this->withSession([
            'asset_management_authenticated' => true,
        ]);
    }

    public function test_mengubah_subkategori_dengan_data_valid()
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
}
