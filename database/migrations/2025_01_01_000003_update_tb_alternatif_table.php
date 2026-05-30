<?php
// database/migrations/2025_01_01_000002_update_tb_alternatif_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_alternatif', function (Blueprint $table) {
            // Tambah kolom baru yang diperlukan
            $table->string('alamat')->nullable()->after('nama_wisata');
            $table->string('kota')->nullable()->after('alamat');
            $table->string('link_gmaps')->nullable()->after('longitude');

            // Ubah kolom rating menjadi 0-5 dengan desimal
            $table->decimal('rating', 3, 2)->default(0)->change();

            // Tambah kolom untuk nilai kriteria yang sudah dinormalisasi
            $table->decimal('biaya_normalisasi', 10, 2)->default(0)->after('biaya_masuk');
            $table->decimal('aksesibilitas', 3, 2)->default(0)->after('biaya_normalisasi');
            $table->decimal('fasilitas', 3, 2)->default(0)->after('aksesibilitas');
        });
    }

    public function down(): void
    {
        Schema::table('tb_alternatif', function (Blueprint $table) {
            $table->dropColumn(['alamat', 'kota', 'link_gmaps', 'biaya_normalisasi', 'aksesibilitas', 'fasilitas']);
        });
    }
};
