<!DOCTYPE html>
<html lang="id" class="h-full bg-[#FAF7F2]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Jaya Prana Inventory</title>
    
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}?v={{ time() }}">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}?v={{ time() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="h-full flex items-center justify-center p-4 sm:p-6 lg:p-8">

    <div class="w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row min-h-[600px] border border-stone-100">
        
        <div class="w-full md:w-1/2 p-8 sm:p-12 flex flex-col justify-center relative">
            
            <div class="mb-10">
                <div class="flex items-center gap-3 mb-2">
                    <div class="h-10 w-10 bg-gradient-to-br from-emerald-600 to-amber-600 rounded-lg flex items-center justify-center p-1 shadow-lg">
                        <img src="{{ asset('favicon.png') }}" alt="JP" class="h-6 w-6 brightness-200 grayscale-0">
                    </div>
                    <h1 class="text-2xl font-bold text-stone-800 tracking-tight">Jaya Prana</h1>
                </div>
                <h2 class="text-stone-500 text-sm font-medium">Sistem Manajemen Inventori Terpadu</h2>
            </div>

            <div class="mb-8">
                <h3 class="text-3xl font-bold text-stone-900 mb-2">Selamat Datang! 👋</h3>
                <p class="text-stone-500">Silakan masukkan detail akun Anda untuk mulai mengelola stok.</p>
            </div>

            @if ($errors->any())
                <!-- Alerts handled by SweetAlert -->
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="space-y-2">
                    <label for="username" class="text-sm font-semibold text-stone-700">Username</label>
                    <input 
                        type="text" 
                        name="username" 
                        id="username" 
                        class="w-full px-4 py-3 rounded-lg border border-stone-300 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all duration-200 outline-none text-stone-800 placeholder-stone-400 bg-stone-50"
                        placeholder="Contoh: admin_gudang"
                        value="{{ old('username') }}"
                        required
                        autofocus
                    >
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label for="password" class="text-sm font-semibold text-stone-700">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs font-semibold text-emerald-700 hover:text-emerald-800 hover:underline">
                                Lupa Password?
                            </a>
                        @endif
                    </div>
                    <div class="relative group">
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            class="w-full px-4 py-3 rounded-lg border border-stone-300 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all duration-200 outline-none text-stone-800 placeholder-stone-400 bg-stone-50"
                            placeholder="••••••••"
                            required
                        >
                        <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-stone-400 hover:text-emerald-700 transition-colors p-1">
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

                <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-emerald-700 to-emerald-600 hover:from-emerald-800 hover:to-emerald-700 text-white font-bold rounded-lg shadow-lg shadow-emerald-500/20 transition-all duration-200 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 text-sm tracking-wide">
                    MASUK KE SISTEM
                </button>

                <div class="pt-4 text-center">
                    <p class="text-sm text-stone-500">
                        Belum punya akun? 
                        <a href="{{ route('register') }}" class="font-bold text-emerald-700 hover:text-emerald-800 transition-colors">Hubungi Admin</a>
                    </p>
                </div>
            </form>
            
            <div class="mt-8 pt-6 border-t border-stone-100 text-center">
                <p class="text-xs text-stone-400">&copy; {{ date('Y') }} Jaya Prana Building Store. v1.0.0</p>
            </div>
        </div>

        <div class="hidden md:flex w-1/2 relative overflow-hidden items-center justify-center bg-stone-900">
            
            <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80&w=2070&auto=format&fit=crop" 
                 alt="Warehouse Background" 
                 class="absolute inset-0 w-full h-full object-cover opacity-40 mix-blend-overlay">
            
            <div class="absolute inset-0 bg-gradient-to-t from-stone-900 via-stone-900/40 to-amber-900/20"></div>

            <div class="relative z-20 w-full max-w-md text-center p-8">
                
                <div class="mb-6 inline-flex p-4 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 shadow-2xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>

                <div class="space-y-4">
                    <h3 class="text-3xl font-bold text-white tracking-wide">
                        Inventori Terkontrol,<br>
                        Bisnis Lancar.
                    </h3>
                    <p class="text-stone-300 text-sm leading-relaxed px-4">
                        Pantau stok bahan bangunan, kelola pesanan masuk, dan optimalkan gudang Jaya Prana dalam satu dashboard.
                    </p>
                </div>

                <div class="flex flex-wrap justify-center gap-3 pt-8">
                    <span class="flex items-center gap-1 px-3 py-1.5 bg-emerald-500/20 rounded-full text-xs font-medium text-emerald-300 border border-emerald-500/30">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Real-time
                    </span>
                    <span class="flex items-center gap-1 px-3 py-1.5 bg-amber-500/20 rounded-full text-xs font-medium text-amber-300 border border-amber-500/30">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Aman
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