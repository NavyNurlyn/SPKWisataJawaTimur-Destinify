<?php

namespace App\Http\Controllers;

use App\Models\RelAlternatif;
use App\Models\Alternatif;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class RelAlternatifController extends Controller
{
    public function index()
    {
        $alternatifs = Alternatif::all();
        $kriterias = Kriteria::all();
        return view('rel_alternatif.index', compact('alternatifs', 'kriterias'));
    }

    public function store(Request $request)
    {
        $tahun = $request->tahun;

        foreach ($request->nilai as $alt => $rows) {
            foreach ($rows as $krit => $nilai) {
                RelAlternatif::updateOrCreate(
                    ['tahun' => $tahun, 'kode_alternatif' => $alt, 'kode_kriteria' => $krit],
                    ['nilai' => $nilai]
                );
            }
        }

        return back()->with('success', 'Nilai alternatif berhasil disimpan.');
    }
}
