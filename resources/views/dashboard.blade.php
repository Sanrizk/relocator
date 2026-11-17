@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header Welcome -->
    <div class="bg-gradient-to-r from-blue-700 via-indigo-700 to-blue-900 rounded-2xl p-6 sm:p-8 text-white shadow-lg">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-semibold uppercase tracking-wider text-blue-100 mb-2">
                    Panel {{ ucfirst(Auth::user()->role) }}
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Selamat Datang, {{ Auth::user()->name }}!</h1>
                <p class="text-blue-100 text-sm mt-1 max-w-2xl">
                    Sistem Pendukung Keputusan Pemilihan Lokasi Pembangunan Cabang Ritel Baru berbasis kombinasi metode ROC (Rank Order Centroid) dan SAW (Simple Additive Weighting).
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('spk.index') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-white text-blue-800 font-semibold text-sm shadow hover:bg-blue-50 transition">
                    <i class="fa-solid fa-calculator mr-2"></i> Lihat Hasil SPK
                </a>
            </div>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-sliders"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Kriteria (ROC)</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $totalKriteria }}</h3>
                <span class="text-xs text-blue-600 font-medium">Prioritas Terukur</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-location-dot"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Alternatif Lokasi</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $totalAlternatif }}</h3>
                <span class="text-xs text-indigo-600 font-medium">Calon Cabang Ritel</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-table-cells"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Matriks Nilai</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $totalPenilaian }}</h3>
                <span class="text-xs text-emerald-600 font-medium">Nilai Terinput</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-trophy"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Metode SPK</p>
                <h3 class="text-lg font-bold text-slate-800">ROC + SAW</h3>
                <span class="text-xs text-amber-600 font-medium">Akurasi & Transparansi</span>
            </div>
        </div>
    </div>

    <!-- Ringkasan Top Rekomendasi Lokasi -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <div>
                <h2 class="text-base font-bold text-slate-800">Top Rekomendasi Lokasi Terbaru</h2>
                <p class="text-xs text-slate-500">Berdasarkan hasil kalkulasi nilai preferensi ($V_i$) metode SAW</p>
            </div>
            <a href="{{ route('spk.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 flex items-center">
                Detail Kalkulasi <i class="fa-solid fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="p-6">
            @if($latestHasil->isEmpty())
                <div class="text-center py-8 text-slate-400">
                    <i class="fa-solid fa-chart-simple text-4xl mb-3 text-slate-300"></i>
                    <p class="text-sm">Belum ada riwayat hasil perhitungan SPK.</p>
                    <a href="{{ route('spk.index') }}" class="mt-3 inline-block text-xs text-blue-600 font-semibold hover:underline">
                        Jalankan Kalkulasi Sekarang
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="text-xs uppercase font-semibold text-slate-400 border-b border-slate-200">
                                <th class="pb-3 text-center w-16">Peringkat</th>
                                <th class="pb-3">Nama Lokasi Cabang</th>
                                <th class="pb-3">Alamat</th>
                                <th class="pb-3 text-right">Nilai Preferensi ($V_i$)</th>
                                <th class="pb-3 text-center">Status Rekomendasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($latestHasil as $hasil)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3 text-center font-bold">
                                    @if($hasil->ranking === 1)
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-100 text-amber-700 shadow-sm font-extrabold">
                                            <i class="fa-solid fa-crown text-xs"></i>
                                        </span>
                                    @elseif($hasil->ranking === 2)
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-slate-200 text-slate-700 font-bold">2</span>
                                    @elseif($hasil->ranking === 3)
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-800/10 text-amber-800 font-bold">3</span>
                                    @else
                                        <span class="text-slate-500 font-medium">{{ $hasil->ranking }}</span>
                                    @endif
                                </td>
                                <td class="py-3 font-semibold text-slate-800">{{ $hasil->alternatif->nama_lokasi ?? '-' }}</td>
                                <td class="py-3 text-slate-500 text-xs">{{ $hasil->alternatif->alamat ?? '-' }}</td>
                                <td class="py-3 text-right font-mono font-bold text-blue-600">{{ number_format($hasil->nilai_preferensi, 4) }}</td>
                                <td class="py-3 text-center">
                                    @if($hasil->ranking === 1)
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Paling Direkomendasikan</span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-slate-100 text-slate-600">Alternatif #{{ $hasil->ranking }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
