<?php

namespace App\Http\Controllers;

use App\Models\RelKriteria;
use App\Models\RelAlternatif;
use App\Models\Alternatif;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class HasilController extends Controller
{
    public function index()
    {
        return view('hasil.index');
    }

    public function hitung(Request $request)
    {
        $tahun = $request->tahun;

        // ambil data
        $kriterias = Kriteria::where('tahun', $tahun)->get();
        $relKriteria = RelKriteria::where('tahun', $tahun)->get();
        $alternatifs = Alternatif::where('tahun', $tahun)->get();
        $relAlternatif = RelAlternatif::where('tahun', $tahun)->get();

        // TODO: Tambahkan perhitungan AHP + TOPSIS di sini nanti
        return view('hasil.index', compact('kriterias', 'alternatifs'));
    }
}
