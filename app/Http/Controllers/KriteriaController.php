<?php

// app/Http/Controllers/KriteriaController.php
namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    public function index()
    {
        $kriterias = Kriteria::all();
        return view('kriteria.index', compact('kriterias'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kriteria' => 'required',
            'atribut' => 'required',
        ]);

        $kriteria = Kriteria::findOrFail($id);
        $kriteria->update([
            'nama_kriteria' => $request->nama_kriteria,
            'atribut' => $request->atribut,
        ]);

        return back()->with('success', 'Kriteria berhasil diperbarui.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_kriteria' => 'required|unique:tb_kriteria',
            'nama_kriteria' => 'required',
            'atribut' => 'required',
        ]);

        Kriteria::create($request->all());
        return back()->with('success', 'Kriteria berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        Kriteria::destroy($id);
        return back()->with('success', 'Kriteria berhasil dihapus.');
    }
}
