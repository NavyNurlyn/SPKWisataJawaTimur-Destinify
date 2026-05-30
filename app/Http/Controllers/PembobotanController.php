<?php
// app/Http/Controllers/PembobotanController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Kriteria;

class PembobotanController extends Controller
{
    public function index()
    {
        $kriterias = Kriteria::all();
        $relasi = DB::table('tb_rel_kriteria')->get();

        // Buat matriks dari database (BUKAN dari session)
        $matriks = [];
        foreach ($kriterias as $k1) {
            foreach ($kriterias as $k2) {
                $data = $relasi
                    ->where('kode_kriteria1', $k1->kode_kriteria)
                    ->where('kode_kriteria2', $k2->kode_kriteria)
                    ->first();

                $matriks[$k1->kode_kriteria][$k2->kode_kriteria] =
                    $data->nilai ?? ($k1->kode_kriteria == $k2->kode_kriteria ? 1 : null);
            }
        }

        // Ambil hasil perhitungan dari session (jika ada)
        $hasil = [
            'matriks' => $matriks, // GUNAKAN matriks dari DB, bukan session
            'normalisasi' => session('normalisasi'),
            'bobot' => session('bobot'),
            'totalKolom' => session('totalKolom'),
            'lamdaMax' => session('lamdaMax'),
            'ci' => session('ci'),
            'ri' => session('ri'),
            'cr' => session('cr'),
            'ahpKonsisten' => session('ahp_konsisten'),
        ];

        return view('pembobotan.index', array_merge(
            compact('kriterias', 'matriks'),
            $hasil
        ));
    }

    public function updateNilai(Request $request)
    {
        $k1 = $request->input('kriteria1');
        $k2 = $request->input('kriteria2');
        $nilai = floatval($request->input('nilai'));

        if ($k1 == $k2) {
            return back()->with('error', 'Kriteria tidak boleh sama.');
        }

        // Simpan nilai utama
        DB::table('tb_rel_kriteria')->updateOrInsert(
            ['kode_kriteria1' => $k1, 'kode_kriteria2' => $k2],
            ['nilai' => $nilai, 'updated_at' => now()]
        );

        // Simpan nilai kebalikannya (1/nilai)
        DB::table('tb_rel_kriteria')->updateOrInsert(
            ['kode_kriteria1' => $k2, 'kode_kriteria2' => $k1],
            ['nilai' => 1 / $nilai, 'updated_at' => now()]
        );

        // HAPUS session hasil perhitungan lama karena data sudah berubah
        session()->forget(['normalisasi', 'bobot', 'totalKolom', 'lamdaMax', 'ci', 'ri', 'cr', 'ahp_konsisten']);

        return redirect()->route('pembobotan.index')
            ->with('success', "Nilai perbandingan $k1 terhadap $k2 berhasil diperbarui!");
    }

    public function hitung()
    {
        $kriterias = Kriteria::all();
        $relasi = DB::table('tb_rel_kriteria')->get();
        $n = $kriterias->count();

        if ($n < 2 || $relasi->isEmpty()) {
            return back()->with('error', 'Data kriteria atau pembobotan belum lengkap.');
        }

        // Buat matriks perbandingan
        $matriks = [];
        foreach ($kriterias as $k1) {
            foreach ($kriterias as $k2) {
                $data = $relasi
                    ->where('kode_kriteria1', $k1->kode_kriteria)
                    ->where('kode_kriteria2', $k2->kode_kriteria)
                    ->first();

                $matriks[$k1->kode_kriteria][$k2->kode_kriteria] = $data->nilai ?? 1;
            }
        }

        // Hitung total kolom
        $totalKolom = [];
        foreach ($kriterias as $k2) {
            $totalKolom[$k2->kode_kriteria] = 0;
            foreach ($kriterias as $k1) {
                $totalKolom[$k2->kode_kriteria] += $matriks[$k1->kode_kriteria][$k2->kode_kriteria];
            }
        }

        // Normalisasi matriks
        $normalisasi = [];
        foreach ($kriterias as $k1) {
            foreach ($kriterias as $k2) {
                $normalisasi[$k1->kode_kriteria][$k2->kode_kriteria] =
                    $matriks[$k1->kode_kriteria][$k2->kode_kriteria] / $totalKolom[$k2->kode_kriteria];
            }
        }

        // Hitung bobot rata-rata
        $bobot = [];
        foreach ($kriterias as $k1) {
            $jumlahBaris = 0;
            foreach ($kriterias as $k2) {
                $jumlahBaris += $matriks[$k1->kode_kriteria][$k2->kode_kriteria] / $totalKolom[$k2->kode_kriteria];
            }
            $bobot[$k1->kode_kriteria] = $jumlahBaris / $n;

            // Simpan bobot ke tabel kriteria
            Kriteria::where('kode_kriteria', $k1->kode_kriteria)
                ->update(['bobot' => $bobot[$k1->kode_kriteria]]);
        }

        // Hitung λ max
        $lamdaMax = 0;
        foreach ($kriterias as $k1) {
            $jumlah = 0;
            foreach ($kriterias as $k2) {
                $jumlah += $matriks[$k1->kode_kriteria][$k2->kode_kriteria] * $bobot[$k2->kode_kriteria];
            }
            $lamdaMax += $jumlah / $bobot[$k1->kode_kriteria];
        }
        $lamdaMax /= $n;

        // Hitung Consistency Index (CI) dan Ratio (CR)
        $ci = ($lamdaMax - $n) / ($n - 1);

        // Nilai Random Index (RI)
        $riTable = [
            1 => 0.00,
            2 => 0.00,
            3 => 0.58,
            4 => 0.90,
            5 => 1.12,
            6 => 1.24,
            7 => 1.32,
            8 => 1.41,
            9 => 1.45,
            10 => 1.49
        ];
        $ri = $riTable[$n] ?? 1.49;
        $cr = $ri > 0 ? $ci / $ri : 0;

        // Simpan hasil perhitungan ke session
        session([
            'normalisasi'   => $normalisasi,
            'bobot' => $bobot,
            'totalKolom' => $totalKolom,
            'lamdaMax' => $lamdaMax,
            'ci' => $ci,
            'ri' => $ri,
            'cr' => $cr,
            'ahp_konsisten' => $cr < 0.1
        ]);

        session()->flash('scroll', true);
        return redirect()->route('pembobotan.index');
    }
}
