<?php
// database/seeders/RelKriteriaSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RelKriteriaSeeder extends Seeder
{
    public function run()
    {
        // Data default pembobotan AHP
        $data = [
            // C1 vs lainnya
            ['kode_kriteria1' => 'C1', 'kode_kriteria2' => 'C1', 'nilai' => 1],
            ['kode_kriteria1' => 'C1', 'kode_kriteria2' => 'C2', 'nilai' => 5],
            ['kode_kriteria1' => 'C1', 'kode_kriteria2' => 'C3', 'nilai' => 1],
            ['kode_kriteria1' => 'C1', 'kode_kriteria2' => 'C4', 'nilai' => 1],
            ['kode_kriteria1' => 'C1', 'kode_kriteria2' => 'C5', 'nilai' => 3],

            // C2 vs lainnya
            ['kode_kriteria1' => 'C2', 'kode_kriteria2' => 'C1', 'nilai' => 0.2],
            ['kode_kriteria1' => 'C2', 'kode_kriteria2' => 'C2', 'nilai' => 1],
            ['kode_kriteria1' => 'C2', 'kode_kriteria2' => 'C3', 'nilai' => 0.2],
            ['kode_kriteria1' => 'C2', 'kode_kriteria2' => 'C4', 'nilai' => 0.2],
            ['kode_kriteria1' => 'C2', 'kode_kriteria2' => 'C5', 'nilai' => 0.33333333333333],

            // C3 vs lainnya
            ['kode_kriteria1' => 'C3', 'kode_kriteria2' => 'C1', 'nilai' => 1],
            ['kode_kriteria1' => 'C3', 'kode_kriteria2' => 'C2', 'nilai' => 5],
            ['kode_kriteria1' => 'C3', 'kode_kriteria2' => 'C3', 'nilai' => 1],
            ['kode_kriteria1' => 'C3', 'kode_kriteria2' => 'C4', 'nilai' => 5],
            ['kode_kriteria1' => 'C3', 'kode_kriteria2' => 'C5', 'nilai' => 5],

            // C4 vs lainnya
            ['kode_kriteria1' => 'C4', 'kode_kriteria2' => 'C1', 'nilai' => 1],
            ['kode_kriteria1' => 'C4', 'kode_kriteria2' => 'C2', 'nilai' => 5],
            ['kode_kriteria1' => 'C4', 'kode_kriteria2' => 'C3', 'nilai' => 0.2],
            ['kode_kriteria1' => 'C4', 'kode_kriteria2' => 'C4', 'nilai' => 1],
            ['kode_kriteria1' => 'C4', 'kode_kriteria2' => 'C5', 'nilai' => 3],

            // C5 vs lainnya
            ['kode_kriteria1' => 'C5', 'kode_kriteria2' => 'C1', 'nilai' => 0.33333333333333],
            ['kode_kriteria1' => 'C5', 'kode_kriteria2' => 'C2', 'nilai' => 3],
            ['kode_kriteria1' => 'C5', 'kode_kriteria2' => 'C3', 'nilai' => 0.2],
            ['kode_kriteria1' => 'C5', 'kode_kriteria2' => 'C4', 'nilai' => 0.33333333333333],
            ['kode_kriteria1' => 'C5', 'kode_kriteria2' => 'C5', 'nilai' => 1],
        ];

        foreach ($data as $item) {
            DB::table('tb_rel_kriteria')->updateOrInsert(
                [
                    'kode_kriteria1' => $item['kode_kriteria1'],
                    'kode_kriteria2' => $item['kode_kriteria2']
                ],
                [
                    'nilai' => $item['nilai'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }
}
