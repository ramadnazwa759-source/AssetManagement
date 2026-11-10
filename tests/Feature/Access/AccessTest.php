<?php

namespace Tests\Feature\Access;

use Tests\TestCase;

class AccessTest extends TestCase
{
    // TC-AM-ACCESS-001 Membuka Asset Management dengan sesi modul belum aktif

    public function test_TC_AM_ACCESS_001_sesi_modul_belum_aktif(): void
    {
        $response = $this->get(route('peminjaman.index'));

        $response->assertRedirect();

        $this->assertGuest();
    }


    // TC-AM-ACCESS-002 Membuka Asset Management dengan sesi modul sudah aktif

    public function test_TC_AM_ACCESS_002_sesi_modul_sudah_aktif(): void
    {
        $response = $this->withSession([
            'asset_management_authenticated' => true,
        ])->get(route('peminjaman.index'));

        // Memastikan akses tidak ditolak karena belum login.
        $this->assertNotEquals(401, $response->getStatusCode());
        $this->assertNotEquals(403, $response->getStatusCode());
        $this->assertNotEquals(302, $response->getStatusCode());
    }


    // TC-AM-ACCESS-003 Mengakses modul tanpa login Asset Management.

    public function test_TC_AM_ACCESS_003_admin_harus_login(): void
    {
        $response = $this->get(route('peminjaman.index'));

        $response->assertRedirect();

        $this->assertGuest();
    }
}
