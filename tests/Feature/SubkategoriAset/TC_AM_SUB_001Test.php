<?php

namespace Tests\Feature\SubKategoriAset;

use Tests\TestCase;
use App\Models\KategoriAset;
use App\Models\SubKategoriAset;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TC_AM_SUB_001Test extends TestCase
{
    use RefreshDatabase;
        protected function setUp(): void
    {
        parent::setUp();

        $this->withSession([
            'asset_management_authenticated' => true,
        ]);
    }

    public function test_menampilkan_daftar_subkategori()
    {
        $kategori = KategoriAset::create([
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
}
