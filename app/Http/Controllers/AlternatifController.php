<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use Illuminate\Http\Request;

class AlternatifController extends Controller
{
    public function index()
    {
        $alternatifs = Alternatif::orderBy('id', 'asc')->get();
        return view('alternatif.index', compact('alternatifs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'alamat' => 'required|string',
        ]);

        Alternatif::create($validated);

        return redirect()->route('alternatif.index')->with('success', 'Alternatif lokasi cabang berhasil ditambahkan.');
    }

    public function update(Request $request, Alternatif $alternatif)
    {
        $validated = $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'alamat' => 'required|string',
        ]);

        $alternatif->update($validated);

        return redirect()->route('alternatif.index')->with('success', 'Data lokasi cabang berhasil diperbarui.');
    }

    public function destroy(Alternatif $alternatif)
    {
        $alternatif->delete();

        return redirect()->route('alternatif.index')->with('success', 'Alternatif lokasi cabang berhasil dihapus.');
    }
}
