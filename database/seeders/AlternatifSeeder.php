<?php
// database/seeders/AlternatifSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AlternatifSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Path ke file SQL
        $sqlFile = database_path('seeders/sql/tb_alternatif.sql');

        // Cek apakah file ada
        if (File::exists($sqlFile)) {
            // Baca file SQL
            $sql = File::get($sqlFile);

            // Jalankan SQL
            DB::unprepared($sql);

            $this->command->info('✓ Alternatif data seeded from SQL file successfully!');
        } else {
            $this->command->warn('⚠ SQL file not found. Creating sample data...');
            $this->seedSampleData();
        }
    }

    /**
     * Seed sample data jika file SQL tidak ada
     */
    private function seedSampleData(): void
    {
        $sampleData = [
            [
                'kode_alternatif' => 'A1',
                'nama_wisata' => 'Air Terjun Coban Rondo',
                'alamat' => 'Jl. Coban Rondo No.30, Krajan, Pandesari, Kec. Pujon, Kabupaten Malang, Jawa Timur 65391',
                'kota' => 'Kabupaten Malang',
                'jenis_wisata' => 'alam',
                'deskripsi' => 'Air Terjun Coban Rondo merupakan air terjun yang terletak di Kecamatan Pujon, Kabupaten Malang, Jawa Timur.',
                'latitude' => -7.8954,
                'longitude' => 112.4832,
                'link_gmaps' => 'https://maps.app.goo.gl/CRzNv8JS9R66gXHJ8',
                'jarak_default' => 114,
                'biaya_masuk' => 35000,
                'biaya_normalisasi' => 35,
                'aksesibilitas' => 5,
                'fasilitas' => 5,
                'rating' => 4.5,
            ],
            [
                'kode_alternatif' => 'A2',
                'nama_wisata' => 'Air Terjun Coban Talun',
                'alamat' => 'Dusun Wonorejo, Desa Tulungrejo, Kecamatan Bumiaji, Kota Batu, Jawa Timur.',
                'kota' => 'Batu',
                'jenis_wisata' => 'alam',
                'deskripsi' => 'Air Terjun Coban Talun adalah destinasi wisata alam populer yang terletak di Dusun Wonorejo.',
                'latitude' => -7.8020389,
                'longitude' => 112.5170485,
                'link_gmaps' => 'https://maps.app.goo.gl/paLMvCEHqGNPg6vY9',
                'jarak_default' => 110,
                'biaya_masuk' => 10000,
                'biaya_normalisasi' => 10,
                'aksesibilitas' => 4,
                'fasilitas' => 5,
                'rating' => 4.6,
            ],
        ];

        foreach ($sampleData as $data) {
            DB::table('tb_alternatif')->updateOrInsert(
                ['kode_alternatif' => $data['kode_alternatif']],
                array_merge($data, [
                    'created_at' => now(),
                    'updated_at' => now()
                ])
            );
        }

        $this->command->info('✓ Sample alternatif data seeded successfully!');
    }
}
