<?php

namespace Tests\Feature\SubKategoriAset;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TC_AM_SUB_005Test extends TestCase
{
    use RefreshDatabase;
        protected function setUp(): void
    {
        parent::setUp();

        $this->withSession([
            'asset_management_authenticated' => true,
        ]);
    }

    public function test_kategori_induk_tidak_ditemukan()
    {
        $response = $this->post(
            route('kategori.sub-kategori.store', 'KAT-TIDAK-ADA'),
            [
                'nama_sub_kategori' => 'Tenda Dome',
            ]
        );

        $response->assertNotFound();

        $this->assertDatabaseMissing('sub_kategori_aset', [
            'nama_sub_kategori' => 'Tenda Dome',
        ]);
    }
}
