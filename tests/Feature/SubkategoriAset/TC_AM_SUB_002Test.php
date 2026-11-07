<?php

namespace Tests\Feature\SubKategoriAset;

use Tests\TestCase;
use App\Models\KategoriAset;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TC_AM_SUB_002Test extends TestCase
{
    use RefreshDatabase;
        protected function setUp(): void
    {
        parent::setUp();

        $this->withSession([
            'asset_management_authenticated' => true,
        ]);
    }

    public function test_menampilkan_tombol_aksi_subkategori()
    {
        KategoriAset::create([
            'id_kategori' => 'KAT-01',
            'nama_kategori' => 'Peralatan Outdoor',
        ]);

        $response = $this->get(
            route('kategori.sub-kategori.index', 'KAT-01')
        );

        $response->assertOk();

        // Sesuaikan dengan teks tombol pada file Blade.
        $response->assertSee('Tambah');
        $response->assertSee('search');
    }
}

