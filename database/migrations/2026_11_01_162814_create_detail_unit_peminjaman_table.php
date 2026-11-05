<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_unit_peminjaman', function (Blueprint $table) {
            $table->id('id_detail_unit_peminjaman');

            // Foreign key ke detail peminjaman
            $table->unsignedBigInteger('id_detail_peminjaman');

            // Foreign key ke aset
            $table->string('kode_aset', 25);

            $table->enum('status_unit', [
                'Dipinjam',
                'Dikembalikan',
                'Dibatalkan',
            ])->default('Dipinjam');

            $table->date('tanggal_dikembalikan')->nullable();

            $table->timestamps();

            // Relasi ke detail peminjaman
            $table->foreign('id_detail_peminjaman')
                ->references('id_detail_peminjaman')
                ->on('detail_peminjaman')
                ->onDelete('cascade');

            // Relasi ke aset
            $table->foreign('kode_aset')
                ->references('kode_aset')
                ->on('aset')
                ->onDelete('restrict');

            // Satu unit aset tidak boleh tercatat
            // dua kali pada detail transaksi yang sama.
            $table->unique([
                'id_detail_peminjaman',
                'kode_aset',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_unit_peminjaman');
    }
};