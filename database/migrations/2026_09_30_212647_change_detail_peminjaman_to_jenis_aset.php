<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Mengubah detail peminjaman menjadi detail berdasarkan jenis aset
    public function up(): void
    {
        Schema::table('detail_peminjaman', function (Blueprint $table) {

            // Hapus foreign key id_aset
            $table->dropForeign(['id_aset']);

            // Hapus kolom id_aset
            $table->dropColumn('id_aset');
        });

        Schema::table('detail_peminjaman', function (Blueprint $table) {

            // Menambahkan id jenis aset
            $table->string('id_jenis', 25)
                ->after('id_peminjaman');

            // Menambahkan jumlah unit yang dipinjam
            $table->unsignedInteger('jumlah_pinjam')
                ->after('id_jenis');

            // Relasi ke jenis aset
            $table->foreign('id_jenis')
                ->references('id_jenis')
                ->on('jenis_aset')
                ->onDelete('restrict');

            // Satu jenis aset hanya satu baris dalam satu transaksi
            $table->unique([
                'id_peminjaman',
                'id_jenis'
            ]);
        });
    }

    // Mengembalikan struktur sebelumnya
    public function down(): void
    {
        Schema::table('detail_peminjaman', function (Blueprint $table) {

            // Hapus unique
            $table->dropUnique([
                'id_peminjaman',
                'id_jenis'
            ]);

            // Hapus foreign key id_jenis
            $table->dropForeign(['id_jenis']);

            // Hapus kolom baru
            $table->dropColumn([
                'id_jenis',
                'jumlah_pinjam'
            ]);
        });

        Schema::table('detail_peminjaman', function (Blueprint $table) {

            // Mengembalikan id_aset
            $table->string('id_aset', 25)
                ->after('id_peminjaman');

            $table->foreign('id_aset')
                ->references('kode_aset')
                ->on('aset')
                ->onDelete('restrict');
        });
    }
};
