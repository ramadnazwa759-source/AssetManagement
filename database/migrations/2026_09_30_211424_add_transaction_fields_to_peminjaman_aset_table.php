<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Menambahkan kode dan status transaksi peminjaman
    public function up(): void
    {
        Schema::table('peminjaman_aset', function (Blueprint $table) {
            $table->string('kode_peminjaman', 25)
                ->unique()
                ->after('id_peminjaman');

            $table->enum('status_peminjaman', [
                'Dipinjam',
                'Sebagian Dikembalikan',
                'Dikembalikan',
                'Dibatalkan'
            ])
                ->default('Dipinjam')
                ->after('jumlah_dikembalikan');

            $table->string('id_jenis', 25)
                ->nullable()
                ->change();

            $table->integer('jumlah_pinjam')
                ->nullable()
                ->change();
        });
    }

    // Menghapus kode dan status transaksi
    public function down(): void
    {
        Schema::table('peminjaman_aset', function (Blueprint $table) {
            $table->dropUnique([
                'kode_peminjaman'
            ]);

            $table->dropColumn([
                'kode_peminjaman',
                'status_peminjaman'
            ]);

            $table->string('id_jenis', 25)
                ->nullable(false)
                ->change();

            $table->integer('jumlah_pinjam')
                ->nullable(false)
                ->change();
        });
    }
};
