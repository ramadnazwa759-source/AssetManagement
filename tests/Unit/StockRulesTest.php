<?php

namespace Tests\Unit;

use App\Services\StockRules;
use PHPUnit\Framework\TestCase;

class StockRulesTest extends TestCase
{
    // TC-AM-JNS-023
    public function test_menghitung_penambahan_stok_normal(): void
    {
        // Arrange
        $rules = new StockRules();
        $stokLama = 3;
        $tambahStok = 5;

        // Act
        $hasil = $rules->calculateNewStock($stokLama, $tambahStok);

        // Assert
        $this->assertSame(8, $hasil);
    }

    // TC-AM-JNS-030
    public function test_menghitung_penambahan_stok_minimum(): void
    {
        // Arrange
        $rules = new StockRules();
        $stokLama = 3;
        $tambahStok = 1;

        // Act
        $hasil = $rules->calculateNewStock($stokLama, $tambahStok);

        // Assert
        $this->assertSame(4, $hasil);
    }

    // TC-AM-JNS-023
    public function test_jenis_aset_aktif_boleh_ditambah_stok(): void
    {
        // Arrange
        $rules = new StockRules();

        // Act
        $hasil = $rules->canAddStock('Aktif');

        // Assert
        $this->assertTrue($hasil);
    }

    // TC-AM-JNS-027
    public function test_jenis_aset_nonaktif_tidak_boleh_ditambah_stok(): void
    {
        // Arrange
        $rules = new StockRules();

        // Act
        $hasil = $rules->canAddStock('Nonaktif');

        // Assert
        $this->assertFalse($hasil);
    }
}
