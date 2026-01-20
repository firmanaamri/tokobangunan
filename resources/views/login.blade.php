<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Jaya Prana Inventory</title>
    
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="h-full flex items-center justify-center p-4 sm:p-6 lg:p-8 bg-[#E8F9FF] selection:bg-sky-100 selection:text-sky-900">

    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row min-h-[650px] border border-sky-100">
        
        {{-- BAGIAN KIRI: FORM LOGIN --}}
        <div class="w-full md:w-1/2 p-8 sm:p-12 lg:p-16 flex flex-col justify-center relative">
            
            
            <a href="{{ url('/') }}" class="absolute top-4 left-4 sm:top-6 sm:left-6 inline-flex items-center gap-2 text-slate-400 hover:text-sky-600 transition-colors group z-20">
                <div class="p-2 rounded-full bg-slate-50 border border-slate-100 group-hover:bg-sky-50 group-hover:border-sky-100 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform group-hover:-translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </div>
                <span class="text-xs font-bold uppercase tracking-wide hidden sm:inline-block">Kembali</span>
            </a>

            <div class="mb-10 mt-12 sm:mt-0"> 
                <a href="{{ url('/') }}" class="flex items-center gap-3 mb-2 group w-fit">
                    <div class="h-10 w-10 bg-white rounded-xl flex items-center justify-center p-1.5 shadow-lg group-hover:scale-105 transition-transform">
                        <img src="{{ asset('favicon.png') }}" alt="JP" class="h-full w-full object-contain brightness-200 grayscale-0">
                    </div>
                    <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight group-hover:text-sky-700 transition-colors">TB. Jaya Prana</h1>
                </a>
                <h2 class="text-slate-400 text-sm font-medium ml-1">Jaya Prana Building Materials </h2>
            </div>

            <div class="mb-8">
                <h3 class="text-3xl font-bold text-slate-900 mb-2">Selamat Datang! 👋</h3>
                <p class="text-slate-500 leading-relaxed">Masuk untuk mengelola stok, penjualan, dan laporan harian toko.</p>
            </div>

            {{-- Form --}}
            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf
                
                <div class="space-y-2">
                    <label for="username" class="text-sm font-semibold text-slate-700">Username</label>
                    <div class="relative">
                        <input 
                            type="text" 
                            name="username" 
                            id="username" 
                            class="w-full pl-4 pr-4 py-3.5 rounded-xl border border-slate-200 focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all duration-200 outline-none text-slate-800 placeholder-slate-400 bg-slate-50/50 focus:bg-white"
                            placeholder="Masukan username anda"
                            value="{{ old('username') }}"
                            required
                            autofocus
                        >
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label for="password" class="text-sm font-semibold text-slate-700">Password</label>
                    </div>
                    <div class="relative group">
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            class="w-full pl-4 pr-12 py-3.5 rounded-xl border border-slate-200 focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all duration-200 outline-none text-slate-800 placeholder-slate-400 bg-slate-50/50 focus:bg-white"
                            placeholder="••••••••"
                            required
                        >
                        <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-sky-600 transition-colors p-2 rounded-lg hover:bg-sky-50">
                            <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-4.803M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-4 px-6 bg-gradient-to-r from-sky-600 to-sky-500 hover:from-sky-700 hover:to-sky-600 text-white font-bold rounded-xl shadow-lg shadow-sky-500/30 transition-all duration-200 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 text-sm tracking-wide uppercase">
                        Masuk
                    </button>
                </div>
            </form>
            
            <div class="mt-auto pt-8 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-400 font-medium">
                    &copy; {{ date('Y') }} Jaya Prana Building Store. <br>
                    <span class="text-slate-300">All rights reserved.</span>
                </p>
            </div>
        </div>

        {{-- BAGIAN KANAN: GAMBAR & MOTIVASI (Tetap Sama) --}}
        <div class="hidden md:flex w-1/2 relative overflow-hidden items-center justify-center bg-slate-900">
            <img src="https://images.unsplash.com/photo-1531834685032-c34bf0d84c7c?q=80&w=1997&auto=format&fit=crop" 
                 alt="Building Construction" 
                 class="absolute inset-0 w-full h-full object-cover opacity-60 mix-blend-overlay">
            
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-sky-900/60 to-emerald-900/40"></div>

            <div class="relative z-20 w-full max-w-md text-center p-10">
                <div class="mb-8 inline-flex p-5 bg-white/10 backdrop-blur-md rounded-2xl border border-white/10 shadow-2xl ring-1 ring-white/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-sky-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 00-2 2h-2a2 2 0 00-2 2" />
                    </svg>
                </div>

                <div class="space-y-5">
                    <h3 class="text-3xl font-bold text-white tracking-wide leading-tight">
                        Kelola Inventori<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-300 to-emerald-300">Lebih Efisien</span>
                    </h3>
                    <p class="text-slate-300 text-sm leading-relaxed px-6 font-light">
                        "Pantau stok material, tracking penjualan harian, dan kelola supplier dalam satu platform terintegrasi."
                    </p>
                </div>

                <div class="flex flex-wrap justify-center gap-3 pt-10">
                    <span class="flex items-center gap-1.5 px-4 py-2 bg-sky-500/20 rounded-full text-xs font-semibold text-sky-200 border border-sky-500/30 backdrop-blur-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Cepat
                    </span>
                    <span class="flex items-center gap-1.5 px-4 py-2 bg-emerald-500/20 rounded-full text-xs font-semibold text-emerald-200 border border-emerald-500/30 backdrop-blur-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Akurat
                    </span>
                </div>
            </div>
        </div>

    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eyeOpen');
            const eyeClosed = document.getElementById('eyeClosed');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }
    </script>
</body>
</html>