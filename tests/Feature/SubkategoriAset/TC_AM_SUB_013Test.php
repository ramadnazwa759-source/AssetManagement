<?php

namespace Tests\Feature\SubKategoriAset;

use Tests\TestCase;
use App\Models\KategoriAset;
use App\Models\SubKategoriAset;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TC_AM_SUB_013Test extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();

        $this->withSession([
            'asset_management_authenticated' => true,
        ]);
    }

    public function test_mencari_subkategori_dengan_kata_kunci_sesuai()
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
}
