<?php

namespace Tests\Feature;

use App\Models\Aset;
use App\Models\JenisAset;
use App\Models\KategoriAset;
use App\Models\SubKategoriAset;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class JenisAsetAddStockTest extends TestCase
{
    use RefreshDatabase;

    /** Satu-satunya database yang boleh dipakai test ini. */
    private const DB_TESTING = 'asset_management_testing';

    private User $admin;

    
    protected function beforeRefreshingDatabase()
    {
        $namaDb = DB::connection()->getDatabaseName();

        if (app()->environment() !== 'testing' || $namaDb !== self::DB_TESTING) {
            throw new \RuntimeException(
                "TEST DIHENTIKAN: database yang terbaca adalah '{$namaDb}', "
                . "bukan '" . self::DB_TESTING . "'. "
                . 'Periksa .env.testing dan phpunit.xml.'
            );
        }
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Precondition: "Admin sudah login ke sistem Asset Management."
        $this->admin = $this->buatAdmin();
    }

    
    // TC-AM-JNS-023 : Tambah stok dengan jumlah valid
    public function test_tc_am_jns_023_tambah_stok_valid_membuat_unit_baru(): void
    {
        // Arrange: Helm Safety, Aktif, stok awal 3 (3 unit).
        $jenis = $this->buatJenis('Helm Safety', 'Aktif', 3, 'HEL');
        $kodeLama = $this->kodeUnit($jenis);

        // Act: admin menambah stok 5.
        $response = $this->kirimTambahStok($jenis->id_jenis, 5);

        // Assert: berhasil, stok 3 + 5 = 8, dan 5 unit baru terbentuk.
        $response->assertRedirect(route('jenis-aset.index'));
        $response->assertSessionHas('success', 'Stok aset berhasil ditambahkan.');

        $this->assertStokDanUnit($jenis, 8, 8);

        $kodeBaru = array_values(array_diff($this->kodeUnit($jenis), $kodeLama));
        $this->assertCount(5, $kodeBaru);                       // tepat 5 unit baru
        $this->assertCount(5, array_unique($kodeBaru));         // kode unik
        $this->assertSame(
            5,
            Aset::whereIn('kode_aset', $kodeBaru)
                ->where('id_jenis', $jenis->id_jenis)           // terhubung ke Helm Safety
                ->count()
        );
    }

    
   
    // TC-AM-JNS-025 : Jumlah salah (0), diperbaiki menjadi 5
    public function test_tc_am_jns_025_jumlah_diperbaiki_tidak_menambah_ganda(): void
    {
        // Arrange
        $jenis = $this->buatJenis('Helm Safety', 'Aktif', 3, 'HEL');

        // Act 1: percobaan pertama dengan jumlah 0.
        $percobaan1 = $this->kirimTambahStok($jenis->id_jenis, 0);

        // Assert 1: ditolak dan belum ada perubahan.
        $percobaan1->assertSessionHasErrors('tambah_stok');
        $this->assertStokDanUnit($jenis, 3, 3);

        // Act 2: percobaan kedua dengan jumlah 5.
        $percobaan2 = $this->kirimTambahStok($jenis->id_jenis, 5);

        // Assert 2: stok 8 dan 5 unit baru (bukan 10).
        $percobaan2->assertRedirect(route('jenis-aset.index'));
        $percobaan2->assertSessionHas('success');
        $this->assertStokDanUnit($jenis, 8, 8);
    }

    
    
    // TC-AM-JNS-027 : Jenis aset Nonaktif
    public function test_tc_am_jns_027_jenis_nonaktif_ditolak(): void
    {
        // Arrange: Tali Webbing, Nonaktif, stok 4 (4 unit).
        $jenis = $this->buatJenis('Tali Webbing', 'Nonaktif', 4, 'TAL');

        // Act
        $response = $this->kirimTambahStok($jenis->id_jenis, 5);

        // Assert: ditolak dengan pesan, stok tetap 4, tidak ada unit baru.
        $response->assertRedirect(route('jenis-aset.index'));
        $response->assertSessionHasErrors([
            'tambah_stok' => 'Jenis Aset Nonaktif tidak dapat digunakan untuk menambah stok.',
        ]);
        $response->assertSessionMissing('success');
        $this->assertStokDanUnit($jenis, 4, 4);
    }


    // TC-AM-JNS-030 : Jumlah minimum valid (1)
    public function test_tc_am_jns_030_jumlah_minimum_satu_membuat_satu_unit(): void
    {
        // Arrange
        $jenis = $this->buatJenis('Helm Safety', 'Aktif', 3, 'HEL');
        $kodeLama = $this->kodeUnit($jenis);

        // Act
        $response = $this->kirimTambahStok($jenis->id_jenis, 1);

        // Assert: stok 4 dan tepat 1 unit baru dengan kode unik.
        $response->assertRedirect(route('jenis-aset.index'));
        $response->assertSessionHas('success');
        $this->assertStokDanUnit($jenis, 4, 4);

        $kodeBaru = array_values(array_diff($this->kodeUnit($jenis), $kodeLama));
        $this->assertCount(1, $kodeBaru);
    }

    /*
    |--------------------------------------------------------------------------
    | TC-AM-JNS-031 : Dua penambahan stok pada jenis aset yang sama
    |--------------------------------------------------------------------------
    | Ini SIMULASI BERURUTAN (dua request, satu demi satu), bukan konkurensi
    | sungguhan. Yang dibuktikan: tidak ada pembaruan stok yang hilang dan
    | tidak ada kode unit yang kembar.
    */
    public function test_tc_am_jns_031_dua_penambahan_berurutan_tanpa_kode_kembar(): void
    {
        // Arrange
        $jenis = $this->buatJenis('Helm Safety', 'Aktif', 3, 'HEL');

        // Act: dua permintaan, masing-masing menambah 1 unit.
        $this->kirimTambahStok($jenis->id_jenis, 1)->assertSessionHas('success');
        $this->kirimTambahStok($jenis->id_jenis, 1)->assertSessionHas('success');

        // Assert: stok 3 + 1 + 1 = 5, dan seluruh kode unit berbeda.
        $this->assertStokDanUnit($jenis, 5, 5);

        $semuaKode = $this->kodeUnit($jenis);
        $this->assertCount(5, array_unique($semuaKode));
    }

    
     // TEST DATA: membuat user admin untuk login ke sistem Asset Management.
    private function buatAdmin(): User
    {
        return User::query()->create([
            'name' => 'Admin Uji',
            'email' => 'admin-uji@example.test',
            'password' => 'Testing123!',
        ]);
    }

    // TEST DATA: membuat SubKategoriAset untuk keperluan membuat JenisAset.
    private function buatSubKategori(): SubKategoriAset
    {
        $kategori = KategoriAset::query()->create([
            'id_kategori' => 'KAT-01',
            'nama_kategori' => 'Alat Keselamatan',
            'deskripsi' => 'Kategori untuk pengujian',
        ]);

        return SubKategoriAset::query()->create([
            'id_sub_kategori' => 'SUB-01',
            'id_kategori' => $kategori->id_kategori,
            'nama_sub_kategori' => 'Perlengkapan Keselamatan',
            'gambar' => null,
            'deskripsi' => null,
        ]);
    }

    // TEST DATA: membuat JenisAset beserta unit-unitnya (tabel aset) sesuai stok awal.
    private function buatJenis(
        string $nama,
        string $status,
        int $stokAwal,
        string $prefixKode
    ): JenisAset {
        $subKategori = $this->buatSubKategori();

        $jenis = JenisAset::query()->create([
            'id_jenis' => 'JA-001',
            'id_sub_kategori_aset' => $subKategori->id_sub_kategori,
            'nama_jenis' => $nama,
            'stok' => $stokAwal,
            'status_jenis' => $status,
            'deskripsi' => null,
        ]);

        $statusUnit = $status === 'Aktif' ? 'Tersedia' : 'Nonaktif';

        for ($i = 1; $i <= $stokAwal; $i++) {
            Aset::query()->create([
                'kode_aset' => $prefixKode . str_pad((string) $i, 2, '0', STR_PAD_LEFT),
                'id_jenis' => $jenis->id_jenis,
                'id_lokasi' => null,
                'nama_aset' => $nama,
                'tanggal_beli' => null,
                'kondisi_aset' => 'Baik',
                'status_aset' => $statusUnit,
                'gambar' => null,
                'keterangan' => null,
            ]);
        }

        return $jenis;
    }

    // Mengirim request POST untuk menambah stok pada jenis aset tertentu.
    private function kirimTambahStok(string $idJenis, mixed $jumlah): TestResponse
    {
        return $this->withSession([
            'asset_management_authenticated' => true,
        ])
            ->from(route('jenis-aset.index'))
            ->post(route('jenis-aset.stok', $idJenis), [
                'tambah_stok' => $jumlah,
            ]);
    }

    /** Daftar kode seluruh unit milik sebuah Jenis Aset. */
    private function kodeUnit(JenisAset $jenis): array
    {
        return Aset::where('id_jenis', $jenis->id_jenis)
            ->pluck('kode_aset')
            ->all();
    }

    /** Memeriksa stok pada jenis_aset dan jumlah baris pada tabel aset. */
    private function assertStokDanUnit(
        JenisAset $jenis,
        int $stokHarapan,
        int $unitHarapan
    ): void {
        $this->assertSame($stokHarapan, $jenis->fresh()->stok);
        $this->assertSame(
            $unitHarapan,
            Aset::where('id_jenis', $jenis->id_jenis)->count()
        );
    }
}