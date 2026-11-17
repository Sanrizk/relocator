@extends('layouts.app')

@section('title', 'Proses & Hasil SPK (ROC + SAW)')

@section('content')
<div class="space-y-8">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Proses & Hasil Rekomendasi SPK</h1>
            <p class="text-xs text-slate-500 mt-1">
                Kalkulasi transparan integrasi metode <b>Rank Order Centroid (ROC)</b> untuk pembobotan & <b>Simple Additive Weighting (SAW)</b> untuk perankingan lokasi.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('spk.cetak') }}" target="_blank" class="inline-flex items-center px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-semibold shadow transition">
                <i class="fa-solid fa-file-pdf mr-1.5"></i> Cetak / Export PDF
            </a>
        </div>
    </div>

    @if(!$calculation['has_data'])
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 text-center text-amber-800">
            <i class="fa-solid fa-triangle-exclamation text-3xl mb-2 text-amber-600"></i>
            <h3 class="font-bold text-base">Data Belum Mencukupi</h3>
            <p class="text-xs mt-1">{{ $calculation['message'] }}</p>
            <div class="mt-4 flex justify-center gap-3">
                <a href="{{ route('kriteria.index') }}" class="px-3 py-1.5 bg-amber-600 text-white rounded-lg text-xs font-semibold">Ke Kriteria</a>
                <a href="{{ route('alternatif.index') }}" class="px-3 py-1.5 bg-amber-600 text-white rounded-lg text-xs font-semibold">Ke Alternatif</a>
            </div>
        </div>
    @else

        <!-- 1. HASIL AKHIR & PERANGKINGAN (Rekomendasi Utama) -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-700 to-indigo-800 text-white flex justify-between items-center">
                <div>
                    <h2 class="text-base font-bold flex items-center">
                        <i class="fa-solid fa-trophy mr-2 text-amber-300"></i>
                        Hasil Akhir Nilai Preferensi ($V_i$) & Perankingan Lokasi
                    </h2>
                    <p class="text-xs text-blue-100">Formula SAW: $V_i = \sum_{j=1}^{n} W_j R_{ij}$ (Lokasi dengan nilai tertinggi adalah prioritas utama)</p>
                </div>
                <span class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-semibold">
                    Total {{ count($calculation['rankings']) }} Alternatif
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 text-xs uppercase font-semibold border-b border-slate-200">
                            <th class="py-3.5 px-4 text-center w-24">Peringkat</th>
                            <th class="py-3.5 px-4">Nama Lokasi Cabang</th>
                            <th class="py-3.5 px-4">Alamat</th>
                            <th class="py-3.5 px-4 text-right">Nilai Preferensi ($V_i$)</th>
                            <th class="py-3.5 px-4 text-center">Status Keputusan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($calculation['rankings'] as $res)
                        <tr class="hover:bg-blue-50/40 transition {{ $res['ranking'] === 1 ? 'bg-amber-50/50' : '' }}">
                            <td class="py-3.5 px-4 text-center">
                                @if($res['ranking'] === 1)
                                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-gradient-to-tr from-amber-400 to-yellow-500 text-white shadow-md font-extrabold text-sm">
                                        <i class="fa-solid fa-crown mr-0.5"></i> 1
                                    </span>
                                @elseif($res['ranking'] === 2)
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-slate-200 text-slate-700 font-bold text-xs">
                                        2
                                    </span>
                                @elseif($res['ranking'] === 3)
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-amber-800/10 text-amber-800 font-bold text-xs">
                                        3
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-slate-100 text-slate-500 font-medium text-xs">
                                        {{ $res['ranking'] }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-800 text-base">
                                {{ $res['nama_lokasi'] }}
                                @if($res['ranking'] === 1)
                                    <span class="ml-2 text-xs font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 border border-emerald-200">
                                        Terpilih
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 text-xs">{{ $res['alamat'] }}</td>
                            <td class="py-3.5 px-4 text-right font-mono font-extrabold text-lg {{ $res['ranking'] === 1 ? 'text-amber-600' : 'text-blue-700' }}">
                                {{ number_format($res['nilai_preferensi'], 4) }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($res['ranking'] === 1)
                                    <span class="px-3 py-1.5 text-xs font-bold rounded-xl bg-emerald-600 text-white shadow-sm">
                                        Rekomendasi Utama
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-lg bg-slate-100 text-slate-600">
                                        Alternatif #{{ $res['ranking'] }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 2. TABEL BOBOT ROC KRITERIA -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">1. Bobot Kriteria ROC ($W_j$)</h3>
                    <p class="text-xs text-slate-500">Bobot dihitung dari rumus Rank Order Centroid berdasarkan peringkat prioritas</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-slate-100/70 text-slate-600 text-xs uppercase font-semibold border-b border-slate-200">
                            <th class="py-3 px-4 text-center w-20">Peringkat</th>
                            <th class="py-3 px-4">Nama Kriteria</th>
                            <th class="py-3 px-4 text-center">Jenis</th>
                            <th class="py-3 px-4 text-center">Nilai Extremum</th>
                            <th class="py-3 px-4 text-right">Bobot ROC ($W_j$)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($calculation['kriterias'] as $krit)
                        <tr class="hover:bg-slate-50/70">
                            <td class="py-2.5 px-4 text-center font-bold text-xs text-slate-600">#{{ $krit->peringkat }}</td>
                            <td class="py-2.5 px-4 font-semibold text-slate-800">{{ $krit->nama_kriteria }}</td>
                            <td class="py-2.5 px-4 text-center">
                                <span class="px-2 py-0.5 text-[11px] font-semibold rounded-full {{ $krit->jenis === 'benefit' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                    {{ strtoupper($krit->jenis) }}
                                </span>
                            </td>
                            <td class="py-2.5 px-4 text-center text-xs text-slate-500">
                                @if($krit->jenis === 'benefit')
                                    Max: <b class="text-slate-700">{{ $calculation['extremes'][$krit->id]['max'] }}</b>
                                @else
                                    Min: <b class="text-slate-700">{{ $calculation['extremes'][$krit->id]['min'] }}</b>
                                @endif
                            </td>
                            <td class="py-2.5 px-4 text-right font-mono font-bold text-blue-600">
                                {{ number_format($krit->bobot_roc, 4) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 3. TABEL MATRIKS NORMALISASI (R) -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">2. Matriks Normalisasi SAW ($R_{ij}$)</h3>
                    <p class="text-xs text-slate-500">Normalisasi: Benefit = $X_{ij} / \max(X_j)$, Cost = $\min(X_j) / X_{ij}$</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-slate-100/70 text-slate-600 text-xs uppercase font-semibold border-b border-slate-200">
                            <th class="py-3 px-4 w-16 text-center">Kode</th>
                            <th class="py-3 px-4">Alternatif Lokasi</th>
                            @foreach($calculation['kriterias'] as $krit)
                                <th class="py-3 px-4 text-center">
                                    <div>{{ $krit->nama_kriteria }}</div>
                                    <span class="text-[10px] text-slate-400 font-normal">({{ strtoupper($krit->jenis) }})</span>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($calculation['alternatifs'] as $index => $alt)
                        <tr class="hover:bg-slate-50/70">
                            <td class="py-2.5 px-4 text-center font-bold text-xs text-blue-600">A{{ $index + 1 }}</td>
                            <td class="py-2.5 px-4 font-semibold text-slate-800">{{ $alt->nama_lokasi }}</td>
                            @foreach($calculation['kriterias'] as $krit)
                                <td class="py-2.5 px-4 text-center font-mono text-slate-700">
                                    {{ number_format($calculation['matrix_r'][$alt->id][$krit->id], 4) }}
                                </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 4. TABEL PERKALIAN BOBOT DENGAN NORMALISASI (W * R) -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">3. Matriks Terbobot ($W_j \times R_{ij}$)</h3>
                    <p class="text-xs text-slate-500">Perkalian antara bobot ROC ($W_j$) dengan nilai matriks normalisasi ($R_{ij}$)</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-slate-100/70 text-slate-600 text-xs uppercase font-semibold border-b border-slate-200">
                            <th class="py-3 px-4 w-16 text-center">Kode</th>
                            <th class="py-3 px-4">Alternatif Lokasi</th>
                            @foreach($calculation['kriterias'] as $krit)
                                <th class="py-3 px-4 text-center">
                                    <div>{{ $krit->nama_kriteria }}</div>
                                    <span class="text-[10px] text-blue-600 font-mono font-normal">W={{ number_format($krit->bobot_roc, 4) }}</span>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($calculation['alternatifs'] as $index => $alt)
                        <tr class="hover:bg-slate-50/70">
                            <td class="py-2.5 px-4 text-center font-bold text-xs text-blue-600">A{{ $index + 1 }}</td>
                            <td class="py-2.5 px-4 font-semibold text-slate-800">{{ $alt->nama_lokasi }}</td>
                            @foreach($calculation['kriterias'] as $krit)
                                <td class="py-2.5 px-4 text-center font-mono text-slate-700">
                                    {{ number_format($calculation['matrix_v'][$alt->id][$krit->id], 4) }}
                                </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    @endif
</div>
@endsection
