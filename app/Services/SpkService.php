<?php

namespace App\Services;

use App\Models\Alternatif;
use App\Models\HasilSpk;
use App\Models\Kriteria;
use App\Models\Penilaian;
use Illuminate\Support\Facades\DB;

class SpkService
{
    /**
     * Hitung bobot ROC untuk seluruh kriteria berdasarkan peringkatnya.
     * Rumus ROC: W_j = (1/N) * sum_{k=j}^{N} (1/k)
     * di mana N adalah jumlah kriteria, dan j adalah urutan peringkat kriteria (1, 2, ..., N).
     */
    public function calculateRocWeights(): array
    {
        $kriterias = Kriteria::orderBy('peringkat', 'asc')->get();
        $n = $kriterias->count();

        if ($n === 0) {
            return [];
        }

        $results = [];

        foreach ($kriterias as $kriteria) {
            $j = (int) $kriteria->peringkat;
            // Jika peringkat di luar jangkauan (misal user input > n atau < 1), amankan j
            if ($j < 1) $j = 1;
            if ($j > $n) $j = $n;

            $sum = 0.0;
            for ($k = $j; $k <= $n; $k++) {
                $sum += (1.0 / $k);
            }

            $bobot = (1.0 / $n) * $sum;
            $kriteria->bobot_roc = round($bobot, 4);
            $kriteria->save();

            $results[$kriteria->id] = $kriteria->bobot_roc;
        }

        return $results;
    }

    /**
     * Menjalankan perhitungan SAW lengkap:
     * 1. Matriks Keputusan (X)
     * 2. Menentukan Nilai Max/Min per kriteria
     * 3. Normalisasi Matriks (R)
     * 4. Perkalian Bobot ROC dengan R (V_ij)
     * 5. Nilai Preferensi Akhir (V_i) & Perankingan
     * 6. Simpan hasil ke database jika diminta ($saveToDb = true)
     */
    public function calculateSaw(bool $saveToDb = false): array
    {
        $kriterias = Kriteria::orderBy('peringkat', 'asc')->get();
        $alternatifs = Alternatif::all();
        $penilaians = Penilaian::all();

        if ($kriterias->isEmpty() || $alternatifs->isEmpty()) {
            return [
                'has_data' => false,
                'message' => 'Data Kriteria atau Alternatif masih kosong.',
                'kriterias' => $kriterias,
                'alternatifs' => $alternatifs,
                'matrix_x' => [],
                'extremes' => [],
                'matrix_r' => [],
                'matrix_v' => [],
                'rankings' => [],
            ];
        }

        // Susun Matriks Keputusan X[alternatif_id][kriteria_id]
        $matrixX = [];
        foreach ($alternatifs as $alt) {
            foreach ($kriterias as $krit) {
                $matrixX[$alt->id][$krit->id] = 0.0;
            }
        }

        foreach ($penilaians as $p) {
            if (isset($matrixX[$p->alternatif_id][$p->kriteria_id])) {
                $matrixX[$p->alternatif_id][$p->kriteria_id] = (float) $p->nilai;
            }
        }

        // Cari Min dan Max per kriteria
        $extremes = [];
        foreach ($kriterias as $krit) {
            $colValues = [];
            foreach ($alternatifs as $alt) {
                $colValues[] = $matrixX[$alt->id][$krit->id];
            }

            $maxVal = !empty($colValues) ? max($colValues) : 0.0;
            $minVal = !empty($colValues) ? min($colValues) : 0.0;

            $extremes[$krit->id] = [
                'max' => $maxVal,
                'min' => $minVal,
                'jenis' => $krit->jenis,
            ];
        }

        // Normalisasi Matriks R[alternatif_id][kriteria_id]
        // Benefit: X_ij / Max(X_j)
        // Cost: Min(X_j) / X_ij
        $matrixR = [];
        foreach ($alternatifs as $alt) {
            foreach ($kriterias as $krit) {
                $xVal = $matrixX[$alt->id][$krit->id];
                $maxVal = $extremes[$krit->id]['max'];
                $minVal = $extremes[$krit->id]['min'];

                if ($krit->jenis === 'benefit') {
                    $rVal = ($maxVal > 0) ? ($xVal / $maxVal) : 0.0;
                } else {
                    // Cost
                    $rVal = ($xVal > 0) ? ($minVal / $xVal) : 0.0;
                }

                $matrixR[$alt->id][$krit->id] = round($rVal, 4);
            }
        }

        // Hitung Perkalian Bobot ROC & Nilai Preferensi V_i
        // V_i = sum(W_j * R_ij)
        $matrixV = []; // Nilai per sel: W_j * R_ij
        $rankings = [];

        foreach ($alternatifs as $alt) {
            $totalVi = 0.0;
            foreach ($kriterias as $krit) {
                $w = (float) $krit->bobot_roc;
                $r = $matrixR[$alt->id][$krit->id];
                $weightedVal = round($w * $r, 4);

                $matrixV[$alt->id][$krit->id] = $weightedVal;
                $totalVi += $weightedVal;
            }

            $rankings[] = [
                'alternatif_id' => $alt->id,
                'nama_lokasi' => $alt->nama_lokasi,
                'alamat' => $alt->alamat,
                'nilai_preferensi' => round($totalVi, 4),
            ];
        }

        // Urutkan nilai preferensi dari yang terbesar ke terkecil
        usort($rankings, function ($a, $b) {
            return $b['nilai_preferensi'] <=> $a['nilai_preferensi'];
        });

        // Tetapkan ranking 1, 2, 3, dst.
        foreach ($rankings as $idx => &$item) {
            $item['ranking'] = $idx + 1;
        }
        unset($item);

        // Jika saveToDb true, simpan riwayat ke tabel hasil_spk
        if ($saveToDb) {
            HasilSpk::query()->delete();
            foreach ($rankings as $row) {
                HasilSpk::create([
                    'alternatif_id' => $row['alternatif_id'],
                    'nilai_preferensi' => $row['nilai_preferensi'],
                    'ranking' => $row['ranking'],
                ]);
            }
        }

        return [
            'has_data' => true,
            'kriterias' => $kriterias,
            'alternatifs' => $alternatifs,
            'matrix_x' => $matrixX,
            'extremes' => $extremes,
            'matrix_r' => $matrixR,
            'matrix_v' => $matrixV,
            'rankings' => $rankings,
        ];
    }
}
