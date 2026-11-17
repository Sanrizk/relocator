@extends('layouts.app')

@section('title', 'Manajemen Alternatif Lokasi')

@section('content')
<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Alternatif Lokasi</h1>
            <p class="text-xs text-slate-500 mt-1">
                Daftar kandidat lokasi pembangunan cabang ritel baru yang akan dinilai.
            </p>
        </div>
        <div>
            <button onclick="document.getElementById('modalTambahAlternatif').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow transition">
                <i class="fa-solid fa-plus mr-1.5"></i> Tambah Lokasi Baru
            </button>
        </div>
    </div>

    <!-- Table Alternatif -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 text-xs uppercase font-semibold border-b border-slate-200">
                        <th class="py-3.5 px-4 text-center w-16">Kode</th>
                        <th class="py-3.5 px-4">Nama Lokasi Cabang</th>
                        <th class="py-3.5 px-4">Alamat Lengkap</th>
                        <th class="py-3.5 px-4 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($alternatifs as $index => $alt)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-3 px-4 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-700 font-bold text-xs">
                                A{{ $index + 1 }}
                            </span>
                        </td>
                        <td class="py-3 px-4 font-semibold text-slate-800 flex items-center space-x-2">
                            <i class="fa-solid fa-location-dot text-slate-400"></i>
                            <span>{{ $alt->nama_lokasi }}</span>
                        </td>
                        <td class="py-3 px-4 text-slate-600 text-xs">{{ $alt->alamat }}</td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <button onclick="openEditModal({{ $alt }})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form action="{{ route('alternatif.destroy', $alt) }}" method="POST" onsubmit="return confirm('Hapus alternatif lokasi ini? Semua nilai terkait juga akan terhapus.')" class="inline">
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
                        <td colspan="4" class="text-center py-8 text-slate-400">
                            Belum ada alternatif lokasi cabang ritel yang ditambahkan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Alternatif -->
<div id="modalTambahAlternatif" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm hidden">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 border border-slate-100">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-slate-800">Tambah Alternatif Lokasi</h3>
            <button onclick="document.getElementById('modalTambahAlternatif').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form action="{{ route('alternatif.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Nama Lokasi Cabang</label>
                <input type="text" name="nama_lokasi" placeholder="Contoh: Ritel Cabang BSD" required
                    class="w-full text-sm px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Alamat Lengkap</label>
                <textarea name="alamat" rows="3" placeholder="Masukkan alamat lengkap lokasi ritel..." required
                    class="w-full text-sm px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <div class="flex justify-end space-x-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('modalTambahAlternatif').classList.add('hidden')" class="px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-100 rounded-xl transition">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow transition">
                    Simpan Lokasi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Alternatif -->
<div id="modalEditAlternatif" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm hidden">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 border border-slate-100">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-slate-800">Edit Alternatif Lokasi</h3>
            <button onclick="document.getElementById('modalEditAlternatif').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form id="formEditAlternatif" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Nama Lokasi Cabang</label>
                <input type="text" id="edit_nama_lokasi" name="nama_lokasi" required
                    class="w-full text-sm px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Alamat Lengkap</label>
                <textarea id="edit_alamat" name="alamat" rows="3" required
                    class="w-full text-sm px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <div class="flex justify-end space-x-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('modalEditAlternatif').classList.add('hidden')" class="px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-100 rounded-xl transition">
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
    function openEditModal(alt) {
        document.getElementById('edit_nama_lokasi').value = alt.nama_lokasi;
        document.getElementById('edit_alamat').value = alt.alamat;
        document.getElementById('formEditAlternatif').action = `/alternatif/${alt.id}`;
        document.getElementById('modalEditAlternatif').classList.remove('hidden');
    }
</script>
@endpush
@endsection
