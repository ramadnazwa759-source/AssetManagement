<?php

namespace Tests\Feature\SubKategoriAset;

use Tests\TestCase;
use App\Models\KategoriAset;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TC_AM_SUB_006Test extends TestCase
{
    use RefreshDatabase;
        protected function setUp(): void
    {
        parent::setUp();

        $this->withSession([
            'asset_management_authenticated' => true,
        ]);
    }

    public function test_nama_subkategori_kosong_ditolak()
    {
        KategoriAset::create([
            'id_kategori' => 'KAT-01',
            'nama_kategori' => 'Peralatan Outdoor',
        ]);

        $response = $this->post(
            route('kategori.sub-kategori.store', 'KAT-01'),
            [
                'nama_sub_kategori' => '',
            ]
        );

        $response->assertSessionHasErrors([
            'nama_sub_kategori',
        ]);

        $this->assertDatabaseMissing('sub_kategori_aset', [
            'nama_sub_kategori' => '',
        ]);
    }
}
