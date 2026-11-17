<?php

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Services\SpkService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('ROC weights calculate properly based on rank order centroid', function () {
    // Buat 3 kriteria
    Kriteria::create(['nama_kriteria' => 'K1', 'jenis' => 'benefit', 'peringkat' => 1, 'bobot_roc' => 0]);
    Kriteria::create(['nama_kriteria' => 'K2', 'jenis' => 'cost', 'peringkat' => 2, 'bobot_roc' => 0]);
    Kriteria::create(['nama_kriteria' => 'K3', 'jenis' => 'benefit', 'peringkat' => 3, 'bobot_roc' => 0]);

    $service = new SpkService();
    $weights = $service->calculateRocWeights();

    // N = 3
    // W1 = (1/3) * (1/1 + 1/2 + 1/3) = (1/3) * (1.8333) = 0.6111
    // W2 = (1/3) * (1/2 + 1/3) = (1/3) * (0.8333) = 0.2778
    // W3 = (1/3) * (1/3) = 0.1111
    $k1 = Kriteria::where('peringkat', 1)->first();
    $k2 = Kriteria::where('peringkat', 2)->first();
    $k3 = Kriteria::where('peringkat', 3)->first();

    expect($k1->bobot_roc)->toEqual(0.6111)
        ->and($k2->bobot_roc)->toEqual(0.2778)
        ->and($k3->bobot_roc)->toEqual(0.1111);
});

test('SAW calculates preference value and ranks alternatives correctly', function () {
    $k1 = Kriteria::create(['nama_kriteria' => 'Kepadatan Penduduk', 'jenis' => 'benefit', 'peringkat' => 1, 'bobot_roc' => 0.6111]);
    $k2 = Kriteria::create(['nama_kriteria' => 'Biaya Sewa', 'jenis' => 'cost', 'peringkat' => 2, 'bobot_roc' => 0.3889]);

    $altA = Alternatif::create(['nama_lokasi' => 'Lokasi A', 'alamat' => 'Alamat A']);
    $altB = Alternatif::create(['nama_lokasi' => 'Lokasi B', 'alamat' => 'Alamat B']);

    // Alt A: K1=100 (benefit), K2=50 (cost)
    Penilaian::create(['alternatif_id' => $altA->id, 'kriteria_id' => $k1->id, 'nilai' => 100]);
    Penilaian::create(['alternatif_id' => $altA->id, 'kriteria_id' => $k2->id, 'nilai' => 50]);

    // Alt B: K1=80 (benefit), K2=25 (cost)
    Penilaian::create(['alternatif_id' => $altB->id, 'kriteria_id' => $k1->id, 'nilai' => 80]);
    Penilaian::create(['alternatif_id' => $altB->id, 'kriteria_id' => $k2->id, 'nilai' => 25]);

    $service = new SpkService();
    $result = $service->calculateSaw(true);

    expect($result['has_data'])->toBeTrue()
        ->and($result['rankings'])->toHaveCount(2);

    // K1 Max = 100 -> R_A1 = 100/100 = 1.0, R_B1 = 80/100 = 0.8
    // K2 Min = 25  -> R_A2 = 25/50 = 0.5,   R_B2 = 25/25 = 1.0
    // V_A = (0.6111 * 1.0) + (0.3889 * 0.5) = 0.6111 + 0.1945 = 0.8056
    // V_B = (0.6111 * 0.8) + (0.3889 * 1.0) = 0.4889 + 0.3889 = 0.8778
    // Sehingga Ranking 1 adalah Lokasi B (0.8778), Ranking 2 Lokasi A (0.8056)
    expect($result['rankings'][0]['alternatif_id'])->toBe($altB->id)
        ->and($result['rankings'][0]['ranking'])->toBe(1)
        ->and($result['rankings'][1]['alternatif_id'])->toBe($altA->id)
        ->and($result['rankings'][1]['ranking'])->toBe(2);
});
