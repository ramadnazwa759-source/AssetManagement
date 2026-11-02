<?php

namespace Tests\Feature;

use App\Models\LokasiAset;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LokasiAsetTest extends TestCase
{
    // Menggunakan DatabaseTransactions agar setiap operasi database selama testing
    // otomatis di-rollback sehingga tidak merusak / mengotori data asli di MySQL.
    use DatabaseTransactions;

    /**
     * SKENARIO 1 (POSITIF):
     * Admin berhasil menambahkan lokasi aset dengan data valid.
     * Sistem harus menyimpan data ke tabel lokasi_aset dan redirect ke daftar lokasi.
     */
    public function test_admin_berhasil_menambahkan_lokasi_aset_dengan_data_valid(): void
    {
        // 1. Arrange: Siapkan session login dan data lokasi yang valid
        $sessionData = [
            'asset_management_authenticated' => true,
        ];

        $payload = [
            'nama_lokasi' => 'Ruang Laboratorium Komputer Baru',
            'deskripsi'   => 'Ruang laboratorium untuk praktikum mahasiswa',
        ];

        // 2. Act: Kirim request POST ke route lokasi.store
        $response = $this->withSession($sessionData)
            ->post(route('lokasi.store'), $payload);

        // 3. Assert:
        // - Redirect ke halaman daftar lokasi (lokasi.index)
        $response->assertRedirect(route('lokasi.index'));
        $response->assertSessionHas('success', 'Lokasi berhasil ditambahkan.');

        // - Pastikan data tersimpan di tabel lokasi_aset
        $this->assertDatabaseHas('lokasi_aset', [
            'nama_lokasi' => 'Ruang Laboratorium Komputer Baru',
            'deskripsi'   => 'Ruang laboratorium untuk praktikum mahasiswa',
        ]);
    }

    /**
     * SKENARIO 2 (NEGATIF - NAMA LOKASI KOSONG):
     * Setiap Lokasi Aset harus memiliki nama lokasi (wajib diisi).
     * Jika nama lokasi dikosongkan, sistem harus menolak dan menampilkan pesan validasi.
     */
    public function test_gagal_menambahkan_lokasi_aset_jika_nama_lokasi_kosong(): void
    {
        // 1. Arrange: Siapkan session login dan payload dengan nama_lokasi kosong
        $sessionData = [
            'asset_management_authenticated' => true,
        ];

        $payload = [
            'nama_lokasi' => '',
            'deskripsi'   => 'Deskripsi lokasi tanpa nama',
        ];

        // 2. Act: Kirim request POST ke route lokasi.store
        $response = $this->withSession($sessionData)
            ->post(route('lokasi.store'), $payload);

        // 3. Assert:
        // - Sistem mengembalikan redirect (302) kembali ke form
        $response->assertStatus(302);

        // - Sistem mengembalikan error validasi required pada kolom nama_lokasi
        $response->assertSessionHasErrors([
            'nama_lokasi' => 'Nama lokasi wajib diisi.',
        ]);

        // - Pastikan data dengan nama kosong TIDAK tersimpan ke database
        $this->assertDatabaseMissing('lokasi_aset', [
            'nama_lokasi' => '',
        ]);
    }

    /**
     * SKENARIO 3 (NEGATIF - NAMA LOKASI DUPLIKAT):
     * Nama Lokasi Aset tidak boleh sama dengan nama lokasi lain yang telah tersimpan.
     * Jika nama lokasi sudah ada, sistem harus menolak dan menampilkan pesan error validasi.
     */
    public function test_gagal_menambahkan_lokasi_aset_jika_nama_lokasi_sudah_digunakan(): void
    {
        // 1. Arrange: Buat satu data lokasi awal yang sudah tersimpan di Master Lokasi
        $sessionData = [
            'asset_management_authenticated' => true,
        ];

        $lokasiAwal = LokasiAset::create([
            'id_lokasi'   => 'LOK-999',
            'nama_lokasi' => 'Gudang Utama Lantai 1',
            'deskripsi'   => 'Gudang penyimpanan aset utama',
        ]);

        // Data yang akan diinput menggunakan nama_lokasi yang SAMA PERSIS
        $payloadDuplikat = [
            'nama_lokasi' => 'Gudang Utama Lantai 1',
            'deskripsi'   => 'Mencoba menambahkan gudang dengan nama yang sama',
        ];

        // 2. Act: Kirim request POST ke route lokasi.store
        $response = $this->withSession($sessionData)
            ->post(route('lokasi.store'), $payloadDuplikat);

        // 3. Assert:
        // - Sistem menolak input (redirect 302 kembali ke form)
        $response->assertStatus(302);

        // - Sistem mengembalikan error validasi unique pada kolom nama_lokasi
        $response->assertSessionHasErrors([
            'nama_lokasi' => 'Nama lokasi tersebut sudah digunakan.',
        ]);

        // - Pastikan jumlah data dengan nama tersebut di database tetap hanya 1 (tidak bertambah duplikat)
        $jumlahData = LokasiAset::where('nama_lokasi', 'Gudang Utama Lantai 1')->count();
        $this->assertSame(1, $jumlahData);
    }
}
