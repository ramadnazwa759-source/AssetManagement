<?php

namespace Tests\Feature\SubKategoriAset;

use Tests\TestCase;
use App\Models\KategoriAset;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TC_AM_SUB_003Test extends TestCase
{
    use RefreshDatabase;
        protected function setUp(): void
    {
        parent::setUp();

        $this->withSession([
            'asset_management_authenticated' => true,
        ]);
    }

    public function test_halaman_subkategori_tetap_tampil_ketika_data_kosong()
    {
        KategoriAset::create([
            'id_kategori' => 'KAT-01',
            'nama_kategori' => 'Peralatan Outdoor',
        ]);

        $response = $this->get(
            route('kategori.sub-kategori.index', 'KAT-01')
        );

        $response->assertOk();

        $response->assertViewHas('subKategori', function ($data) {
            return $data->isEmpty();
        });
    }
}
