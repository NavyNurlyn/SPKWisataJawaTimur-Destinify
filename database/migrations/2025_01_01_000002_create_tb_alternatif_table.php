<?php

// database/migrations/2025_01_01_000002_create_tb_alternatif_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_alternatif', function (Blueprint $table) {
            $table->id();
            $table->string('kode_alternatif')->unique();
            $table->string('nama_wisata');
            $table->enum('jenis_wisata', ['alam', 'buatan', 'budaya']);
            $table->text('deskripsi')->nullable();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->decimal('jarak_default', 10, 2)->default(0)->comment('Jarak default dalam KM dari titik referensi');
            $table->integer('biaya_masuk')->default(0);
            $table->decimal('rating', 3, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_alternatif');
    }
};
