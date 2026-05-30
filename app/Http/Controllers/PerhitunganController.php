<?php
// app/Http/Controllers/PerhitunganController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kriteria;

class PerhitunganController extends Controller
{
    public function index()
    {
        // Data dari AHP
        $kriterias = Kriteria::all();
        $matriks = session('matriks', []);
        $bobot = session('bobot', []);
        $totalKolom = session('totalKolom', []);
        $lamdaMax = session('lamdaMax');
        $ci = session('ci');
        $ri = session('ri');
        $cr = session('cr');
        $ahpKonsisten = session('ahp_konsisten', false);

        // Jika belum ada hasil AHP
        if (!$lamdaMax || empty($bobot)) {
            return view('perhitungan.index', [
                'kriterias' => $kriterias,
                'matriks' => [],
                'bobot' => [],
                'totalKolom' => [],
                'lamdaMax' => null,
                'ci' => null,
                'ri' => null,
                'cr' => null,
                'ahpKonsisten' => false,
            ])->with('warning', 'Silakan lakukan perhitungan pembobotan AHP terlebih dahulu.');
        }

        return view('perhitungan.index', [
            'kriterias' => $kriterias,
            'matriks' => $matriks,
            'bobot' => $bobot,
            'totalKolom' => $totalKolom,
            'lamdaMax' => $lamdaMax,
            'ci' => $ci,
            'ri' => $ri,
            'cr' => $cr,
            'ahpKonsisten' => $ahpKonsisten,
        ]);
    }
}
