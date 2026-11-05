<?php

namespace App\Services;

class StockRules
{
    public function calculateNewStock(int $stokLama, int $tambahStok): int
    {
        return $stokLama + $tambahStok;
    }

    public function canAddStock(string $statusJenis): bool
    {
        return $statusJenis === 'Aktif';
    }
}