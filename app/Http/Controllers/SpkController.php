<?php

namespace App\Http\Controllers;

use App\Models\HasilSpk;
use App\Services\SpkService;
use Illuminate\Http\Request;

class SpkController extends Controller
{
    protected SpkService $spkService;

    public function __construct(SpkService $spkService)
    {
        $this->spkService = $spkService;
    }

    public function index(Request $request)
    {
        // Hitung ulang bobot ROC untuk memastikan konsistensi
        $this->spkService->calculateRocWeights();

        // Jalankan perhitungan SAW dan simpan riwayat ke tabel hasil_spk
        $calculation = $this->spkService->calculateSaw(true);

        return view('spk.index', compact('calculation'));
    }

    public function cetakPdf()
    {
        $this->spkService->calculateRocWeights();
        $calculation = $this->spkService->calculateSaw(false);

        return view('spk.cetak', compact('calculation'));
    }
}
