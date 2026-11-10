<?php

namespace Tests\Feature\PeminjamanAset;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use App\Models\PeminjamanAset;
use App\Models\DetailPeminjaman;
use App\Models\DetailUnitPeminjaman;
use App\Models\JenisAset;
use App\Models\Aset;

class PeminjamanAsetTest extends TestCase
{
    // Jangan aktifkan RefreshDatabase sebelum konfigurasi
    // database testing dipastikan tidak mengarah ke database produksi.
    protected function setUp(): void
    {
        parent::setUp();

        // Menonaktifkan CSRF hanya selama pengujian.
        $this->withoutMiddleware(
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class
        );

        // Mengaktifkan sesi autentikasi.
        $this->withSession([
            'asset_management_authenticated' => true,
        ]);
    }

    private function dataPeminjaman(array $ubah = []): array
    {
        return array_merge([
            'nama_peminjam' => 'Nazwa',
            'tanggal_pinjam' => '2026-11-07',
            'tanggal_pengembalian' => '2026-11-10',
            'tujuan' => 'Kegiatan organisasi',
            'catatan' => 'Keperluan kegiatan',
            'detail' => [
                [
                    'id_jenis' => 'JNS-001',
                    'jumlah_pinjam' => 1,
                ],
            ],
        ], $ubah);
    }

    // 001 - Menampilkan halaman daftar peminjaman
    public function test_TC_AM_PEM_001_menampilkan_daftar_peminjaman(): void
    {
        $response = $this->get(route('peminjaman.index'));

        $response->assertStatus(200);
    }

    // 002 - Menampilkan halaman tambah peminjaman
    public function test_TC_AM_PEM_002_menampilkan_form_peminjaman(): void
    {
        $response = $this->get(route('peminjaman.create'));

        $response->assertStatus(200);
    }

    // 003 - Menampilkan daftar saat data kosong
    public function test_TC_AM_PEM_003_daftar_peminjaman_kosong(): void
    {
        $response = $this->get(route('peminjaman.index'));

        $response->assertStatus(200);
    }

    // 004 - Validasi nama peminjam wajib diisi
    public function test_TC_AM_PEM_004_nama_peminjam_kosong(): void
    {
        $data = $this->dataPeminjaman([
            'nama_peminjam' => '',
        ]);

                dump([
            'environment' => app()->environment(),
            'running_in_console' => app()->runningInConsole(),
            'running_unit_tests' => app()->runningUnitTests(),
        ]);

        $response = $this->post(route('peminjaman.store'), $data);

        $response->assertSessionHasErrors('nama_peminjam');
    }

    // 005 - Validasi tanggal peminjaman wajib diisi
    public function test_TC_AM_PEM_005_tanggal_pinjam_kosong(): void
    {
        $data = $this->dataPeminjaman([
            'tanggal_pinjam' => '',
        ]);

        $response = $this->post(route('peminjaman.store'), $data);

        $response->assertSessionHasErrors('tanggal_pinjam');
    }

    // 006 - Validasi tanggal pengembalian sebelum tanggal pinjam
    public function test_TC_AM_PEM_006_tanggal_pengembalian_tidak_valid(): void
    {
        $data = $this->dataPeminjaman([
            'tanggal_pengembalian' => '2026-11-06',
        ]);

        $response = $this->post(route('peminjaman.store'), $data);

        $response->assertSessionHasErrors('tanggal_pengembalian');
    }

    // 007 - Validasi tujuan wajib diisi
    public function test_TC_AM_PEM_007_tujuan_kosong(): void
    {
        $data = $this->dataPeminjaman([
            'tujuan' => '',
        ]);

        $response = $this->post(route('peminjaman.store'), $data);

        $response->assertSessionHasErrors('tujuan');
    }

    // 008 - Validasi detail peminjaman wajib diisi
    public function test_TC_AM_PEM_008_detail_peminjaman_kosong(): void
    {
        $data = $this->dataPeminjaman([
            'detail' => [],
        ]);

        $response = $this->post(route('peminjaman.store'), $data);

        $response->assertSessionHasErrors('detail');
    }

    // 009 - Validasi jumlah pinjam minimal satu
    public function test_TC_AM_PEM_009_jumlah_pinjam_nol(): void
    {
        $data = $this->dataPeminjaman([
            'detail' => [
                [
                    'id_jenis' => 'JNS-001',
                    'jumlah_pinjam' => 0,
                ],
            ],
        ]);

        $response = $this->post(route('peminjaman.store'), $data);

        $response->assertSessionHasErrors('detail.0.jumlah_pinjam');
    }

    // 010 - Validasi jenis aset tidak ditemukan
    public function test_TC_AM_PEM_010_jenis_aset_tidak_ditemukan(): void
    {
        $data = $this->dataPeminjaman([
            'detail' => [
                [
                    'id_jenis' => 'JNS-TIDAK-ADA',
                    'jumlah_pinjam' => 1,
                ],
            ],
        ]);

        $response = $this->post(route('peminjaman.store'), $data);

        $response->assertSessionHasErrors('detail.0.id_jenis');
    }

    // 011 - Pencarian berdasarkan kode peminjaman
    public function test_TC_AM_PEM_011_pencarian_kode_peminjaman(): void
    {
        $response = $this->get(route('peminjaman.index', [
            'search' => 'PMJ-',
        ]));

        $response->assertStatus(200);
    }

    // 012 - Pencarian berdasarkan nama peminjam
    public function test_TC_AM_PEM_012_pencarian_nama_peminjam(): void
    {
        $response = $this->get(route('peminjaman.index', [
            'search' => 'Nazwa',
        ]));

        $response->assertStatus(200);
    }

    // 013 - Pencarian berdasarkan nama jenis aset
    public function test_TC_AM_PEM_013_pencarian_jenis_aset(): void
    {
        $response = $this->get(route('peminjaman.index', [
            'search' => 'Tenda',
        ]));

        $response->assertStatus(200);
    }

    // 014 - Filter tanggal mulai
    public function test_TC_AM_PEM_014_filter_tanggal_mulai(): void
    {
        $response = $this->get(route('peminjaman.index', [
            'tanggal_mulai' => '2026-11-01',
        ]));

        $response->assertStatus(200);
    }

    // 015 - Filter tanggal akhir
    public function test_TC_AM_PEM_015_filter_tanggal_akhir(): void
    {
        $response = $this->get(route('peminjaman.index', [
            'tanggal_akhir' => '2026-11-30',
        ]));

        $response->assertStatus(200);
    }

    // 016 - Filter status peminjaman
    public function test_TC_AM_PEM_016_filter_status_peminjaman(): void
    {
        $response = $this->get(route('peminjaman.index', [
            'status_peminjaman' => 'Dipinjam',
        ]));

        $response->assertStatus(200);
    }

    // 017 - Menampilkan detail transaksi
    public function test_TC_AM_PEM_017_menampilkan_detail_peminjaman(): void
    {
        $response = $this->get(route('peminjaman.show', [
            'peminjaman' => 'PMJ-TIDAK-ADA',
        ]));

        $response->assertStatus(404);
    }

    // 018 - Membatalkan transaksi tanpa alasan
    public function test_TC_AM_PEM_018_alasan_pembatalan_kosong(): void
    {
        $response = $this->post(
            route('peminjaman.batal', ['id' => 'PMJ-TIDAK-ADA']),
            ['catatan' => '']
        );

        $response->assertSessionHasErrors('catatan');
    }

    // 019 - Membatalkan transaksi yang tidak ditemukan
    public function test_TC_AM_PEM_019_peminjaman_tidak_ditemukan(): void
    {
        $response = $this->post(
            route('peminjaman.batal', ['id' => 'PMJ-TIDAK-ADA']),
            ['catatan' => 'Pembatalan kegiatan']
        );

        $response->assertNotFound();
    }

    // 020 - Validasi status filter tidak valid
    public function test_TC_AM_PEM_020_filter_status_tidak_valid(): void
    {
        $response = $this->get(route('peminjaman.index', [
            'status_peminjaman' => 'StatusSalah',
        ]));

        $response->assertSessionHasErrors('status_peminjaman');
    }

    // 021 - Validasi tanggal akhir sebelum tanggal mulai
    public function test_TC_AM_PEM_021_filter_rentang_tanggal_tidak_valid(): void
    {
        $response = $this->get(route('peminjaman.index', [
            'tanggal_mulai' => '2026-11-10',
            'tanggal_akhir' => '2026-11-01',
        ]));

        $response->assertSessionHasErrors('tanggal_akhir');
    }
}
