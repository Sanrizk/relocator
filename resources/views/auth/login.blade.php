<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SPK Pemilihan Lokasi Ritel Baru</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl p-8 border border-slate-100">
        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 inline-flex items-center justify-center text-white text-2xl shadow-lg mb-3">
                <i class="fa-solid fa-store"></i>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">SPK Ritel Lokasi</h1>
            <p class="text-xs text-slate-500 mt-1">Sistem Pendukung Keputusan Cabang Baru (ROC - SAW)</p>
        </div>

        @if($errors->any())
            <div class="mb-5 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm">
                <i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Email Akun</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <i class="fa-solid fa-envelope"></i>
                    </span>
                    <input type="email" id="email" name="email" value="{{ old('email', 'admin@ritel.com') }}" required autofocus
                        class="w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input type="password" id="password" name="password" required value="password"
                        class="w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded text-blue-600 focus:ring-blue-500">
                    <span class="text-slate-600 text-xs">Ingat saya</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3 px-4 rounded-xl text-white font-medium bg-blue-600 hover:bg-blue-700 shadow-md hover:shadow-lg transition">
                Masuk ke Sistem
            </button>
        </form>

        <div class="mt-6 pt-5 border-t border-slate-100 text-xs text-slate-500">
            <p class="font-semibold text-slate-700 mb-1">Kredensial Demo:</p>
            <div class="flex justify-between bg-slate-50 p-2.5 rounded-lg border border-slate-200">
                <div>
                    <span class="font-bold text-slate-800">Admin:</span> admin@ritel.com / password
                </div>
                <div>
                    <span class="font-bold text-slate-800">Pimpinan:</span> pimpinan@ritel.com / password
                </div>
            </div>
        </div>
    </div>
</body>
</html>
