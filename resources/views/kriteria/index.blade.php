@extends('layouts.app')

@section('title', 'Manajemen Kriteria & Bobot ROC')

@section('content')
<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Kriteria & Metode ROC</h1>
            <p class="text-xs text-slate-500 mt-1">
                Atur bobot kriteria dengan metode <b>Rank Order Centroid (ROC)</b>. Urutkan prioritas dari ranking 1 (paling penting), 2, dst.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <form action="{{ route('kriteria.hitung-roc') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow transition">
                    <i class="fa-solid fa-arrows-rotate mr-1.5"></i> Hitung Ulang Bobot ROC
                </button>
            </form>
            <button onclick="document.getElementById('modalTambahKriteria').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow transition">
                <i class="fa-solid fa-plus mr-1.5"></i> Tambah Kriteria
            </button>
        </div>
    </div>

    <!-- Rumus ROC Information Card -->
    <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200/60 rounded-2xl flex items-start space-x-3 text-sm text-blue-900">
        <i class="fa-solid fa-circle-info text-blue-600 text-xl mt-0.5"></i>
        <div>
            <span class="font-bold">Formula Pembobotan ROC:</span>
            <div class="font-mono text-xs bg-white/80 p-2 rounded-lg border border-blue-200 mt-1 inline-block">
                W_j = (1 / N) * &sum;_{k=j}^{N} (1 / k)
            </div>
            <p class="text-xs text-blue-800 mt-1">
                Di mana <b>N</b> adalah total jumlah kriteria, dan <b>j</b> adalah nomor peringkat kriteria. Kriteria peringkat 1 selalu memiliki bobot tertinggi.
            </p>
        </div>
    </div>

    <!-- Table Kriteria -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 text-xs uppercase font-semibold border-b border-slate-200">
                        <th class="py-3.5 px-4 text-center w-24">Peringkat (j)</th>
                        <th class="py-3.5 px-4">Nama Kriteria</th>
                        <th class="py-3.5 px-4 text-center">Atribut / Jenis</th>
                        <th class="py-3.5 px-4 text-right">Bobot ROC ($W_j$)</th>
                        <th class="py-3.5 px-4 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($kriterias as $kriteria)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-3 px-4 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg font-bold text-xs {{ $kriteria->peringkat == 1 ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700' }}">
                                #{{ $kriteria->peringkat }}
                            </span>
                        </td>
                        <td class="py-3 px-4 font-semibold text-slate-800">{{ $kriteria->nama_kriteria }}</td>
                        <td class="py-3 px-4 text-center">
                            @if($kriteria->jenis === 'benefit')
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <i class="fa-solid fa-arrow-trend-up mr-1 text-[10px]"></i> Benefit
                                </span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-rose-50 text-rose-700 border border-rose-200">
                                    <i class="fa-solid fa-arrow-trend-down mr-1 text-[10px]"></i> Cost
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-right font-mono font-bold text-blue-600">
                            {{ number_format($kriteria->bobot_roc, 4) }}
                        </td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <button onclick="openEditModal({{ $kriteria }})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form action="{{ route('kriteria.destroy', $kriteria) }}" method="POST" onsubmit="return confirm('Hapus kriteria ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-slate-400">
                            Belum ada data kriteria. Silakan tambahkan kriteria baru.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($kriterias->isNotEmpty())
                <tfoot>
                    <tr class="bg-slate-50 font-bold text-xs text-slate-700 border-t border-slate-200">
                        <td colspan="3" class="py-3 px-4 text-right uppercase">Total Bobot:</td>
                        <td class="py-3 px-4 text-right font-mono text-emerald-700">
                            {{ number_format($kriterias->sum('bobot_roc'), 4) }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Kriteria -->
<div id="modalTambahKriteria" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm hidden">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 border border-slate-100">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-slate-800">Tambah Kriteria Baru</h3>
            <button onclick="document.getElementById('modalTambahKriteria').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form action="{{ route('kriteria.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Nama Kriteria</label>
                <input type="text" name="nama_kriteria" placeholder="Contoh: Kepadatan Penduduk" required
                    class="w-full text-sm px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Atribut / Jenis</label>
                <select name="jenis" required class="w-full text-sm px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="benefit">Benefit (Makin tinggi makin baik)</option>
                    <option value="cost">Cost (Makin rendah makin baik)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Peringkat Prioritas (ROC)</label>
                <input type="number" name="peringkat" min="1" value="{{ $kriterias->count() + 1 }}" required
                    class="w-full text-sm px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-[11px] text-slate-400 mt-1">Urutan prioritas: 1 = paling penting, 2 = kedua terpenting, dst.</p>
            </div>

            <div class="flex justify-end space-x-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('modalTambahKriteria').classList.add('hidden')" class="px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-100 rounded-xl transition">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow transition">
                    Simpan Kriteria
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Kriteria -->
<div id="modalEditKriteria" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm hidden">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 border border-slate-100">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-slate-800">Edit Kriteria</h3>
            <button onclick="document.getElementById('modalEditKriteria').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form id="formEditKriteria" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Nama Kriteria</label>
                <input type="text" id="edit_nama_kriteria" name="nama_kriteria" required
                    class="w-full text-sm px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Atribut / Jenis</label>
                <select id="edit_jenis" name="jenis" required class="w-full text-sm px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="benefit">Benefit (Makin tinggi makin baik)</option>
                    <option value="cost">Cost (Makin rendah makin baik)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Peringkat Prioritas (ROC)</label>
                <input type="number" id="edit_peringkat" name="peringkat" min="1" required
                    class="w-full text-sm px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex justify-end space-x-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('modalEditKriteria').classList.add('hidden')" class="px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-100 rounded-xl transition">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openEditModal(kriteria) {
        document.getElementById('edit_nama_kriteria').value = kriteria.nama_kriteria;
        document.getElementById('edit_jenis').value = kriteria.jenis;
        document.getElementById('edit_peringkat').value = kriteria.peringkat;
        document.getElementById('formEditKriteria').action = `/kriteria/${kriteria.id}`;
        document.getElementById('modalEditKriteria').classList.remove('hidden');
    }
</script>
@endpush
@endsection
