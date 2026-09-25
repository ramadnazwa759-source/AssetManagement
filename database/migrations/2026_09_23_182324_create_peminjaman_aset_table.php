<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman_aset', function (Blueprint $table) {
            $table->id('id_peminjaman');

            $table->string('id_jenis', 25);
            $table->string('nama_peminjam', 100);
            $table->date('tanggal_pinjam');
            $table->integer('jumlah_pinjam');
            $table->text('tujuan');
            $table->date('tanggal_pengembalian')->nullable();
            $table->integer('jumlah_dikembalikan')->default(0);
            $table->text('catatan')->nullable();

            $table->timestamps();

            $table->foreign('id_jenis')
                ->references('id_jenis')
                ->on('jenis_aset')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman_aset');
    }
};
