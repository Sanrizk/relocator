<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\HasilSpk;
use App\Models\Kriteria;
use App\Models\Penilaian;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalKriteria = Kriteria::count();
        $totalAlternatif = Alternatif::count();
        $totalPenilaian = Penilaian::count();
        $latestHasil = HasilSpk::with('alternatif')->orderBy('ranking', 'asc')->take(5)->get();

        return view('dashboard', compact('totalKriteria', 'totalAlternatif', 'totalPenilaian', 'latestHasil'));
    }
}
