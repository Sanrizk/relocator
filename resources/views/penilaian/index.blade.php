@extends('layouts.app')

@section('title', 'Matriks Penilaian Alternatif')

@section('content')
<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Matriks Penilaian Alternatif</h1>
            <p class="text-xs text-slate-500 mt-1">
                Input atau edit skor mentah masing-masing alternatif lokasi terhadap seluruh kriteria yang telah ditentukan.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('spk.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow transition">
                <i class="fa-solid fa-calculator mr-1.5"></i> Proses Perhitungan SAW
            </a>
        </div>
    </div>

    @if($kriterias->isEmpty() || $alternatifs->isEmpty())
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 text-center text-amber-800">
            <i class="fa-solid fa-triangle-exclamation text-3xl mb-2 text-amber-600"></i>
            <h3 class="font-bold text-base">Data Belum Lengkap</h3>
            <p class="text-xs mt-1">
                Pastikan Anda telah menambahkan data <b>Kriteria</b> dan <b>Alternatif Lokasi</b> sebelum mengisi nilai pada matriks penilaian.
            </p>
            <div class="mt-4 flex justify-center gap-3">
                <a href="{{ route('kriteria.index') }}" class="px-3 py-1.5 bg-amber-600 text-white rounded-lg text-xs font-semibold">Ke Kriteria</a>
                <a href="{{ route('alternatif.index') }}" class="px-3 py-1.5 bg-amber-600 text-white rounded-lg text-xs font-semibold">Ke Alternatif</a>
            </div>
        </div>
    @else
        <form action="{{ route('penilaian.store') }}" method="POST">
            @csrf
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                    <div>
                        <h2 class="text-sm font-bold text-slate-800">Tabel Matriks Keputusan ($X$)</h2>
                        <span class="text-xs text-slate-500">Skor aktual untuk setiap kriteria</span>
                    </div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold shadow transition">
                        <i class="fa-solid fa-floppy-disk mr-1.5"></i> Simpan Matriks Penilaian
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="bg-slate-100/70 text-slate-600 text-xs uppercase font-semibold border-b border-slate-200">
                                <th class="py-3.5 px-4 w-16 text-center">Kode</th>
                                <th class="py-3.5 px-4 min-w-[200px]">Alternatif Lokasi</th>
                                @foreach($kriterias as $krit)
                                    <th class="py-3.5 px-4 text-center min-w-[140px]">
                                        <div>{{ $krit->nama_kriteria }}</div>
                                        <span class="text-[10px] px-1.5 py-0.5 rounded font-normal {{ $krit->jenis === 'benefit' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                            {{ strtoupper($krit->jenis) }}
                                        </span>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($alternatifs as $index => $alt)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-blue-50 text-blue-700 font-bold text-xs">
                                        A{{ $index + 1 }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-semibold text-slate-800">{{ $alt->nama_lokasi }}</div>
                                    <div class="text-[11px] text-slate-400 truncate max-w-xs">{{ $alt->alamat }}</div>
                                </td>
                                @foreach($kriterias as $krit)
                                    <td class="py-3.5 px-4 text-center">
                                        <input type="number" step="any" min="0" required
                                            name="nilai[{{ $alt->id }}][{{ $krit->id }}]"
                                            value="{{ old('nilai.'.$alt->id.'.'.$krit->id, $matrixNilai[$alt->id][$krit->id] ?? '') }}"
                                            placeholder="0"
                                            class="w-28 text-center text-sm px-2.5 py-1.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono font-medium">
                                    </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 bg-slate-50 border-t border-slate-200 flex justify-end">
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold shadow transition">
                        <i class="fa-solid fa-floppy-disk mr-1.5"></i> Simpan Matriks Penilaian
                    </button>
                </div>
            </div>
        </form>
    @endif
</div>
@endsection
