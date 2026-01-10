<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Jaya Prana</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex items-center justify-center min-h-screen bg-gradient-to-br from-emerald-900 via-slate-900 to-slate-800">

    <!-- Decorative elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-72 h-72 bg-emerald-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-cyan-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="w-full max-w-md p-8 space-y-8 bg-white/95 backdrop-blur-sm shadow-2xl rounded-2xl relative z-10 border border-white/20">
        
        <!-- Header -->
        <div class="text-center space-y-3">
            <div class="flex justify-center mb-4">
                <div class="p-3 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-xl shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>
            
            <h1 class="text-4xl font-bold bg-gradient-to-r from-emerald-600 to-cyan-600 bg-clip-text text-transparent">
                Jaya Prana
            </h1>
            <h2 class="text-sm font-semibold text-slate-600">
                Sistem Manajemen Inventori
            </h2>
            <p class="text-xs text-slate-500">
                Masuk untuk mengakses dashboard
            </p>
        </div>

        <!-- Error Alert -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <div class="flex items-start gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4v2m0-6a4 4 0 110 8 4 4 0 010-8z" />
                    </svg>
                    <div>
                        <p class="font-bold text-sm">Gagal Login</p>
                        <ul class="mt-2 list-disc list-inside text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form -->
        <form class="space-y-5" action="{{ route('login') }}" method="POST">
            @csrf
            <input type="hidden" name="remember" value="true">
            
            <!-- Username Input -->
            <div>
                <label for="username" class="block text-sm font-semibold text-slate-900 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="inline h-5 w-5 mr-1 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>Username
                </label>
                <input 
                    id="username" 
                    name="username" 
                    type="text" 
                    autocomplete="username" 
                    required
                    value="{{ old('username') }}"
                    class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg text-slate-900 placeholder-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 focus:outline-none transition-all duration-200"
                    placeholder="Masukkan username">
                @error('username')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Input with Toggle -->
            <div>
                <label for="password" class="block text-sm font-semibold text-slate-900 mb-2">
                    <i class="fas fa-lock mr-1 text-emerald-600"></i>Password (Min. 8 karakter)
                </label>
                <div class="relative">
                    <input 
                        id="password" 
                        name="password" 
                        type="password" 
                        autocomplete="current-password" 
                        required
                        minlength="8"
                        class="w-full px-4 py-3 pr-12 border-2 border-slate-200 rounded-lg text-slate-900 placeholder-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 focus:outline-none transition-all duration-200"
                        placeholder="Minimal 8 karakter">
                    <button 
                        type="button"
                        onclick="togglePassword()"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-500 hover:text-slate-700 transition-colors"
                        aria-label="Toggle password visibility">
                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            

            <!-- Submit Button -->
            <button 
                type="submit"
                class="w-full py-3 px-4 bg-gradient-to-r from-emerald-600 to-cyan-600 hover:from-emerald-700 hover:to-cyan-700 text-white font-bold rounded-lg shadow-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="inline h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3v-1" />
                </svg>
                Masuk
            </button>
        </form>

        <div class="flex items-center justify-center">
                <div class="flex items-center gap-2">
                </div>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition-colors">
                        Lupa password?
                    </a>
                @endif
            </div>

        <!-- Divider -->
        <div class="flex items-center gap-3">
            <div class="flex-1 h-px bg-slate-200"></div>
            <span class="text-xs text-slate-500">atau</span>
            <div class="flex-1 h-px bg-slate-200"></div>
        </div>

        <!-- Register Link -->
        @if (Route::has('register'))
            <div class="text-center">
                <p class="text-slate-600">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-bold text-emerald-600 hover:text-emerald-700 transition-colors">
                        Daftar sekarang
                    </a>
                </p>
            </div>
        @endif

        <!-- Footer -->
        <p class="text-center text-xs text-slate-500 border-t border-slate-200 pt-6">
            &copy; {{ date('Y') }} Sistem Inventori Jaya Prana. Semua hak dilindungi.
        </p>
    </div>

    <!-- Password Toggle Script -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-4.803M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><line x1="4" y1="4" x2="20" y2="20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            }
        }
    </script>

</body>
</html>