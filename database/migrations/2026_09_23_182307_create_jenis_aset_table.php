<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_aset', function (Blueprint $table) {
            $table->string('id_jenis', 25)->primary();
            $table->string('id_sub_kategori_aset', 25);

            $table->string('nama_jenis', 100);
            $table->integer('stok')->default(0);
            $table->text('deskripsi')->nullable();

            $table->timestamps();

            $table->foreign('id_sub_kategori_aset')
                ->references('id_sub_kategori')
                ->on('sub_kategori_aset')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_aset');
    }
};
