<?php
// database/seeders/KriteriaSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kriteria;

class KriteriaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'kode_kriteria' => 'C1',
                'nama_kriteria' => 'Biaya',
                'atribut' => 'Cost',
                'bobot' => 0.26136
            ],
            [
                'kode_kriteria' => 'C2',
                'nama_kriteria' => 'Aksesibilitas',
                'atribut' => 'Benefit',
                'bobot' => 0.04795
            ],
            [
                'kode_kriteria' => 'C3',
                'nama_kriteria' => 'Fasilitas',
                'atribut' => 'Benefit',
                'bobot' => 0.39998
            ],
            [
                'kode_kriteria' => 'C4',
                'nama_kriteria' => 'Jarak',
                'atribut' => 'Cost',
                'bobot' => 0.19982
            ],
            [
                'kode_kriteria' => 'C5',
                'nama_kriteria' => 'Rating',
                'atribut' => 'Benefit',
                'bobot' => 0.09090
            ],
        ];

        foreach ($data as $item) {
            Kriteria::updateOrCreate(
                ['kode_kriteria' => $item['kode_kriteria']],
                $item
            );
        }
    }
}
