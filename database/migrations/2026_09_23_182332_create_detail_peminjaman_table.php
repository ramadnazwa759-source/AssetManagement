<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Membuat detail jenis aset pada transaksi
    public function up(): void
    {
        Schema::create('detail_peminjaman', function (Blueprint $table) {
            $table->id('id_detail_peminjaman');

            $table->unsignedBigInteger('id_peminjaman');

            $table->string('id_jenis', 25);

            $table->integer('jumlah_pinjam');

            $table->integer('jumlah_dikembalikan')
                ->default(0);

            $table->timestamps();

            $table->foreign('id_peminjaman')
                ->references('id_peminjaman')
                ->on('peminjaman_aset')
                ->onDelete('cascade');

            $table->foreign('id_jenis')
                ->references('id_jenis')
                ->on('jenis_aset')
                ->onDelete('restrict');

            $table->unique([
                'id_peminjaman',
                'id_jenis'
            ]);
        });
    }

    // Menghapus tabel detail jenis aset
    public function down(): void
    {
        Schema::dropIfExists('detail_peminjaman');
    }
};
