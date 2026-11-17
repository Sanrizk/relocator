<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SPK Pemilihan Lokasi Cabang Ritel') - ROC & SAW</title>
    <!-- Tailwind CSS CDN & FontAwesome Icons -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">

    <!-- Top Navigation -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white shadow-md">
                        <i class="fa-solid fa-store text-lg"></i>
                    </div>
                    <div>
                        <span class="text-lg font-bold bg-gradient-to-r from-blue-700 to-indigo-700 bg-clip-text text-transparent">SPK Ritel Lokasi</span>
                        <span class="hidden sm:inline-block text-xs px-2 py-0.5 ml-2 font-medium bg-blue-50 text-blue-700 rounded-full border border-blue-200">Metode ROC & SAW</span>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-slate-800">{{ Auth::user()->name ?? 'User' }}</p>
                        <p class="text-xs uppercase tracking-wider font-bold text-slate-500">
                            <span class="inline-block w-2 h-2 rounded-full {{ Auth::user()->role === 'admin' ? 'bg-emerald-500' : 'bg-purple-500' }} mr-1"></span>
                            {{ Auth::user()->role ?? 'Guest' }}
                        </p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-3 py-2 border border-slate-200 text-xs font-medium rounded-lg text-slate-600 bg-white hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition">
                            <i class="fa-solid fa-arrow-right-from-bracket mr-1.5"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Navigation Bar -->
    <nav class="bg-slate-900 text-white shadow-inner">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex space-x-1 sm:space-x-4 py-2 overflow-x-auto text-sm">
                <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-lg font-medium transition flex items-center space-x-2 {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-chart-line text-sm"></i>
                    <span>Dashboard</span>
                </a>

                @if(Auth::user()->role === 'admin')
                <a href="{{ route('kriteria.index') }}" class="px-3 py-2 rounded-lg font-medium transition flex items-center space-x-2 {{ request()->routeIs('kriteria.*') ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-sliders text-sm"></i>
                    <span>Kriteria (ROC)</span>
                </a>

                <a href="{{ route('alternatif.index') }}" class="px-3 py-2 rounded-lg font-medium transition flex items-center space-x-2 {{ request()->routeIs('alternatif.*') ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-location-dot text-sm"></i>
                    <span>Alternatif Lokasi</span>
                </a>

                <a href="{{ route('penilaian.index') }}" class="px-3 py-2 rounded-lg font-medium transition flex items-center space-x-2 {{ request()->routeIs('penilaian.*') ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-table-cells text-sm"></i>
                    <span>Matriks Penilaian</span>
                </a>
                @endif

                <a href="{{ route('spk.index') }}" class="px-3 py-2 rounded-lg font-medium transition flex items-center space-x-2 {{ request()->routeIs('spk.index') ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-calculator text-sm"></i>
                    <span>Hasil & Rekomendasi (SAW)</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="flex items-center p-4 mb-4 text-sm text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-200 shadow-sm" role="alert">
                <i class="fa-solid fa-circle-check text-emerald-600 text-lg mr-3"></i>
                <div class="font-medium">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="flex items-center p-4 mb-4 text-sm text-rose-800 rounded-xl bg-rose-50 border border-rose-200 shadow-sm" role="alert">
                <i class="fa-solid fa-triangle-exclamation text-rose-600 text-lg mr-3"></i>
                <div class="font-medium">{{ session('error') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 mb-4 text-sm text-rose-800 rounded-xl bg-rose-50 border border-rose-200 shadow-sm" role="alert">
                <div class="font-semibold flex items-center mb-1">
                    <i class="fa-solid fa-triangle-exclamation mr-2"></i> Periksa kembali data formulir:
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-700">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Content Area -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-4 text-center text-xs text-slate-500 mt-auto">
        <p>&copy; {{ date('Y') }} Sistem Pendukung Keputusan Pemilihan Lokasi Cabang Ritel Baru - Integrasi ROC & SAW.</p>
    </footer>

    @stack('scripts')
</body>
</html>
