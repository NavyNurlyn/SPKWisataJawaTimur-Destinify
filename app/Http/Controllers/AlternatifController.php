<?php
// app/Http/Controllers/AlternatifController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alternatif;

class AlternatifController extends Controller
{
    public function index()
    {
        $alternatifs = Alternatif::orderBy('nama_wisata')->get();
        return view('alternatif.index', compact('alternatifs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_alternatif' => 'required|unique:tb_alternatif',
            'nama_wisata' => 'required',
            'jenis_wisata' => 'required|in:alam,buatan,budaya',
            'deskripsi' => 'nullable',
            'alamat' => 'nullable',
            'kota' => 'nullable',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'link_gmaps' => 'nullable|url',
            'jarak_default' => 'required|numeric|min:0',
            'biaya_masuk' => 'required|integer|min:0',
            'aksesibilitas' => 'required|numeric|min:1|max:5',
            'fasilitas' => 'required|numeric|min:1|max:5',
            'rating' => 'required|numeric|min:0|max:5',
        ]);

        $data = $request->all();
        $data['biaya_normalisasi'] = $request->biaya_masuk / 1000;

        Alternatif::create($data);

        return back()->with('success', 'Tempat wisata berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_wisata' => 'required',
            'jenis_wisata' => 'required|in:alam,buatan,budaya',
            'alamat' => 'nullable',
            'kota' => 'nullable',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'link_gmaps' => 'nullable|url',
            'jarak_default' => 'required|numeric|min:0',
            'biaya_masuk' => 'required|integer|min:0',
            'aksesibilitas' => 'required|numeric|min:1|max:5',
            'fasilitas' => 'required|numeric|min:1|max:5',
            'rating' => 'required|numeric|min:0|max:5',
        ]);

        $alternatif = Alternatif::findOrFail($id);

        $data = $request->all();
        $data['biaya_normalisasi'] = $request->biaya_masuk / 1000;

        $alternatif->update($data);

        return back()->with('success', 'Tempat wisata berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Alternatif::destroy($id);
        return back()->with('success', 'Tempat wisata berhasil dihapus.');
    }
}
