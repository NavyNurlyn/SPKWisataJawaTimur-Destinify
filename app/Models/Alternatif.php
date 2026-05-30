<?php
// app/Models/Alternatif.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alternatif extends Model
{
    use HasFactory;

    protected $table = 'tb_alternatif';

    protected $fillable = [
        'kode_alternatif',
        'nama_wisata',
        'jenis_wisata',
        'deskripsi',
        'alamat',
        'kota',
        'latitude',
        'longitude',
        'link_gmaps',
        'jarak_default',
        'biaya_masuk',
        'biaya_normalisasi',
        'aksesibilitas',
        'fasilitas',
        'rating'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'jarak_default' => 'decimal:2',
        'biaya_masuk' => 'integer',
        'biaya_normalisasi' => 'decimal:2',
        'aksesibilitas' => 'decimal:2',
        'fasilitas' => 'decimal:2',
        'rating' => 'decimal:2',
    ];

    public function hasilPerhitungan()
    {
        return $this->hasMany(HasilPerhitungan::class, 'alternatif_id');
    }

    // Helper method untuk menghitung biaya normalisasi otomatis
    public function setBiayaMasukAttribute($value)
    {
        $this->attributes['biaya_masuk'] = $value;
        $this->attributes['biaya_normalisasi'] = $value / 1000;
    }
}
