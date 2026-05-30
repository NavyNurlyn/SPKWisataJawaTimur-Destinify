<?php

namespace App\Http\Controllers;

use App\Models\RelKriteria;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class RelKriteriaController extends Controller
{
    public function index()
    {
        $kriterias = Kriteria::all();
        return view('rel_kriteria.index', compact('kriterias'));
    }

    public function store(Request $request)
    {
        $tahun = $request->tahun;

        foreach ($request->input('nilai') as $id1 => $row) {
            foreach ($row as $id2 => $nilai) {
                RelKriteria::updateOrCreate(
                    ['tahun' => $tahun, 'ID1' => $id1, 'ID2' => $id2],
                    ['nilai' => $nilai]
                );
            }
        }

        return back()->with('success', 'Data perbandingan kriteria berhasil disimpan.');
    }
}
