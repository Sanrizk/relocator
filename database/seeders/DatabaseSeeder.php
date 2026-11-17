<?php

namespace Database\Seeders;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\User;
use App\Services\SpkService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Pengguna (RBAC: Admin & Pimpinan)
        User::updateOrCreate(
            ['email' => 'admin@ritel.com'],
            [
                'name' => 'Administrator SPK',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'pimpinan@ritel.com'],
            [
                'name' => 'Bapak Pimpinan Direksi',
                'password' => Hash::make('password'),
                'role' => 'pimpinan',
            ]
        );

        // 2. Kriteria Pemilihan Lokasi Cabang Ritel
        // Contoh nyata:
        // C1: Kepadatan Penduduk (Benefit) - Peringkat 1
        // C2: Aksesibilitas & Lalu Lintas Kendaraan (Benefit) - Peringkat 2
        // C3: Potensi Daya Beli Konsumen (Benefit) - Peringkat 3
        // C4: Jarak dari Kompetitor Ritel Terdekat (Benefit - makin jauh makin baik, atau Cost tergantung studi) -> Benefit
        // C5: Biaya Jasa Sewa Lahan per Tahun (Cost) - Peringkat 5
        $kriteriasData = [
            ['nama_kriteria' => 'Kepadatan Penduduk Sekitar (jiwa/km²)', 'jenis' => 'benefit', 'peringkat' => 1],
            ['nama_kriteria' => 'Aksesibilitas & Arus Lalu Lintas (skor 1-100)', 'jenis' => 'benefit', 'peringkat' => 2],
            ['nama_kriteria' => 'Potensi Daya Beli Konsumen (skor 1-100)', 'jenis' => 'benefit', 'peringkat' => 3],
            ['nama_kriteria' => 'Jarak dari Toko Kompetitor (meter)', 'jenis' => 'benefit', 'peringkat' => 4],
            ['nama_kriteria' => 'Biaya Sewa Lahan per Tahun (Juta Rp)', 'jenis' => 'cost', 'peringkat' => 5],
        ];

        $kriteriaModels = [];
        foreach ($kriteriasData as $item) {
            $kriteriaModels[] = Kriteria::updateOrCreate(
                ['nama_kriteria' => $item['nama_kriteria']],
                [
                    'jenis' => $item['jenis'],
                    'peringkat' => $item['peringkat'],
                    'bobot_roc' => 0,
                ]
            );
        }

        // Hitung bobot ROC untuk semua kriteria
        $spkService = new SpkService();
        $spkService->calculateRocWeights();

        // 3. Alternatif Calon Lokasi Cabang Ritel
        $alternatifData = [
            [
                'nama_lokasi' => 'Ritel Cabang BSD Boulevard',
                'alamat' => 'Jl. BSD Grand Boulevard, Serpong, Tangerang Selatan',
            ],
            [
                'nama_lokasi' => 'Ritel Cabang Pamulang Square',
                'alamat' => 'Jl. Siliwangi No. 12, Pamulang, Tangerang Selatan',
            ],
            [
                'nama_lokasi' => 'Ritel Cabang Bintaro Jaya Sektor 7',
                'alamat' => 'Jl. Boulevard Bintaro Jaya Blok B7, Pondok Aren',
            ],
            [
                'nama_lokasi' => 'Ritel Cabang Ciputat Raya',
                'alamat' => 'Jl. Ir H. Juanda No. 45, Ciputat Timur',
            ],
        ];

        $alternatifModels = [];
        foreach ($alternatifData as $altItem) {
            $alternatifModels[] = Alternatif::updateOrCreate(
                ['nama_lokasi' => $altItem['nama_lokasi']],
                ['alamat' => $altItem['alamat']]
            );
        }

        // 4. Matriks Penilaian Sampel
        // Format nilai:
        // C1 (Kepadatan Penduduk): misal 8500, 12000, 9500, 14000
        // C2 (Aksesibilitas): 85, 75, 90, 70
        // C3 (Daya Beli): 90, 70, 88, 65
        // C4 (Jarak Kompetitor): 1500, 800, 2000, 600
        // C5 (Biaya Sewa Juta Rp): 180, 120, 220, 95
        $sampleScores = [
            0 => [8500, 85, 90, 1500, 180], // BSD
            1 => [12000, 75, 70, 800, 120],  // Pamulang
            2 => [9500, 90, 88, 2000, 220],  // Bintaro
            3 => [14000, 70, 65, 600, 95],   // Ciputat
        ];

        foreach ($alternatifModels as $aIdx => $alt) {
            foreach ($kriteriaModels as $kIdx => $krit) {
                Penilaian::updateOrCreate(
                    [
                        'alternatif_id' => $alt->id,
                        'kriteria_id' => $krit->id,
                    ],
                    [
                        'nilai' => $sampleScores[$aIdx][$kIdx],
                    ]
                );
            }
        }

        // Jalankan perhitungan awal SAW & simpan ke tabel hasil_spk
        $spkService->calculateSaw(true);
    }
}
