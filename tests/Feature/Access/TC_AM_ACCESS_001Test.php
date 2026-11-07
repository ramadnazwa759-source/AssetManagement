<?php

namespace Tests\Feature\Access;

use Tests\TestCase;

class TC_AM_ACCESS_001Test extends TestCase
{
    public function test_membuka_asset_management_dengan_sesi_belum_aktif(): void
    {
        $response = $this->get(route('peminjaman.index'));

        $response->assertRedirect();

        $this->assertGuest();
    }
}
