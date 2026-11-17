<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Services\SpkService;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    protected SpkService $spkService;

    public function __construct(SpkService $spkService)
    {
        $this->spkService = $spkService;
    }

    public function index()
    {
        $kriterias = Kriteria::orderBy('peringkat', 'asc')->get();
        return view('kriteria.index', compact('kriterias'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kriteria' => 'required|string|max:255',
            'jenis' => 'required|in:benefit,cost',
            'peringkat' => 'required|integer|min:1',
        ]);

        Kriteria::create([
            'nama_kriteria' => $validated['nama_kriteria'],
            'jenis' => $validated['jenis'],
            'peringkat' => $validated['peringkat'],
            'bobot_roc' => 0,
        ]);

        // Otomatis update perhitungan bobot ROC
        $this->spkService->calculateRocWeights();

        return redirect()->route('kriteria.index')->with('success', 'Kriteria berhasil ditambahkan dan bobot ROC telah diperbarui.');
    }

    public function update(Request $request, Kriteria $kriteria)
    {
        $validated = $request->validate([
            'nama_kriteria' => 'required|string|max:255',
            'jenis' => 'required|in:benefit,cost',
            'peringkat' => 'required|integer|min:1',
        ]);

        $kriteria->update($validated);

        // Update bobot ROC
        $this->spkService->calculateRocWeights();

        return redirect()->route('kriteria.index')->with('success', 'Kriteria berhasil diperbarui dan bobot ROC telah dikalkulasi ulang.');
    }

    public function destroy(Kriteria $kriteria)
    {
        $kriteria->delete();

        // Hitung ulang bobot ROC untuk kriteria yang tersisa
        $this->spkService->calculateRocWeights();

        return redirect()->route('kriteria.index')->with('success', 'Kriteria berhasil dihapus dan bobot ROC telah diperbarui.');
    }

    public function hitungRoc()
    {
        $this->spkService->calculateRocWeights();

        return redirect()->route('kriteria.index')->with('success', 'Kalkulasi Bobot ROC (Rank Order Centroid) berhasil diperbarui.');
    }
}
