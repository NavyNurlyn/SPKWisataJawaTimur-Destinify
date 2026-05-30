<?php
// database/migrations/2025_01_01_000007_update_tb_hasil_perhitungan_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_hasil_perhitungan', function (Blueprint $table) {
            // Tambah kolom untuk menyimpan jarak dari user (jika ada)
            $table->decimal('jarak_user', 10, 2)->nullable()->after('jarak_km');
            // Tambah kolom untuk filter jenis wisata yang dipilih user
            $table->string('filter_jenis_wisata')->nullable()->after('jarak_user');
        });
    }

    public function down(): void
    {
        Schema::table('tb_hasil_perhitungan', function (Blueprint $table) {
            $table->dropColumn(['jarak_user', 'filter_jenis_wisata']);
        });
    }
};
