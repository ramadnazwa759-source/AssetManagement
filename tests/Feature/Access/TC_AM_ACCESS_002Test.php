<?php

namespace Tests\Feature\Access;

use Tests\TestCase;

class TC_AM_ACCESS_002Test extends TestCase
{
    public function test_membuka_asset_management_dengan_sesi_sudah_aktif(): void
    {
        $response = $this->withSession([
            'asset_management_authenticated' => true,
        ])->get(route('peminjaman.index'));

        // nanti rubah saat sudah narik main
        // Memastikan pengguna tidak ditolak karena belum login.
        $this->assertNotEquals(401, $response->getStatusCode());
        $this->assertNotEquals(403, $response->getStatusCode());
        $this->assertNotEquals(302, $response->getStatusCode());
    }
}
