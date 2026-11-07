<?php

namespace Tests\Feature\Access;

use Tests\TestCase;

class TC_AM_ACCESS_003Test extends TestCase
{
    public function test_admin_harus_login_sebelum_mengakses_fungsi_pengelolaan(): void
    {
        $response = $this->get(route('peminjaman.index'));

        $response->assertRedirect();

        $this->assertGuest();
    }
}
