<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    public function index()
    {
        $alternatifs = Alternatif::orderBy('id', 'asc')->get();
        $kriterias = Kriteria::orderBy('peringkat', 'asc')->get();
        $penilaians = Penilaian::all();

        // Buat map [alternatif_id][kriteria_id] => nilai
        $matrixNilai = [];
        foreach ($penilaians as $p) {
            $matrixNilai[$p->alternatif_id][$p->kriteria_id] = $p->nilai;
        }

        return view('penilaian.index', compact('alternatifs', 'kriterias', 'matrixNilai'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nilai' => 'required|array',
            'nilai.*.*' => 'required|numeric|min:0',
        ]);

        foreach ($request->input('nilai') as $alternatifId => $kriteriaValues) {
            foreach ($kriteriaValues as $kriteriaId => $val) {
                Penilaian::updateOrCreate(
                    [
                        'alternatif_id' => $alternatifId,
                        'kriteria_id' => $kriteriaId,
                    ],
                    [
                        'nilai' => $val,
                    ]
                );
            }
        }

        return redirect()->route('penilaian.index')->with('success', 'Matriks penilaian berhasil disimpan!');
    }
}
