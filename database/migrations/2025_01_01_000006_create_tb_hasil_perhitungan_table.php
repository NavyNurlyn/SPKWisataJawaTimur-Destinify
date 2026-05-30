<?php

// database/migrations/2025_01_01_000006_create_tb_hasil_perhitungan_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_hasil_perhitungan', function (Blueprint $table) {
            $table->id();
            $table->string('session_id');
            $table->foreignId('alternatif_id')->constrained('tb_alternatif')->onDelete('cascade');
            $table->decimal('jarak_km', 10, 2)->nullable();
            $table->decimal('nilai_preferensi', 10, 6)->nullable();
            $table->integer('ranking')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_hasil_perhitungan');
    }
};
