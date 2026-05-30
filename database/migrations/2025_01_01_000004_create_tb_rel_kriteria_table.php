<?php

// database/migrations/2025_01_01_000004_create_tb_rel_kriteria_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_rel_kriteria', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kriteria1');
            $table->string('kode_kriteria2');
            $table->double('nilai');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_rel_kriteria');
    }
};
