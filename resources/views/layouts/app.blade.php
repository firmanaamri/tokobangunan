<!DOCTYPE html>
<html lang="id" class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard Inventori' }} </title>
    
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}?v={{ time() }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon.png') }}?v={{ time() }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
    
    <style>
        [x-cloak] { display: none !important; }
        .font-heading { font-family: 'Poppins', sans-serif; }
        .font-body { font-family: 'Inter', sans-serif; }
        
        @media (max-width: 767px) {
            aside:not(.w-64) {
                transform: translateX(-100%);
            }
        }

        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
    </style>
</head>
<body class="h-screen font-body overflow-hidden bg-white">

<div x-data="{ 
    sidebarOpen: (function() {
        const saved = localStorage.getItem('sidebarOpen');
        if (saved !== null) return saved === 'true';
        return window.innerWidth >= 768;
    })(),
    toggleSidebar() {
        this.sidebarOpen = !this.sidebarOpen;
        localStorage.setItem('sidebarOpen', this.sidebarOpen);
    }
}" class="relative flex h-screen overflow-hidden">

    {{-- SIDEBAR --}}
    @include('layouts.partials.sidebar')

    {{-- OVERLAY MOBILE --}}
    <div x-show="sidebarOpen" x-cloak 
         class="fixed inset-0 z-40 bg-black/40 backdrop-blur-sm md:hidden" 
         @click="toggleSidebar()">
    </div>

    {{-- AREA KONTEN KANAN --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden relative">

        <header class="flex-shrink-0 absolute top-0 left-0 right-0 z-30 flex items-center justify-between px-8 py-6 
                       bg-white/60 backdrop-blur-xl backdrop-saturate-150 min-h-[88px]
                       border-b border-white/40 shadow-sm transition-all duration-300">
            
            <div class="flex items-center space-x-6"> {{-- space-x diperlebar --}}
               {{-- Tombol Toggle dengan Tooltip --}}
                <button @click="toggleSidebar()" 
                        class="group relative text-slate-600 hover:text-slate-900 hover:bg-white/50 p-2 rounded-xl transition-all focus:outline-none backdrop-blur-md">
                    
                    {{-- Ikon Hamburger (Muncul saat sidebar tertutup) --}}
                    <svg x-show="!sidebarOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    
                    {{-- Ikon Close (Muncul saat sidebar terbuka) --}}
                    <svg x-show="sidebarOpen" x-cloak class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>

                    <div class="absolute top-full left-1/2 -translate-x-1/2 mt-2 px-3 py-1 bg-slate-800 text-white text-xs rounded-md opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none whitespace-nowrap z-50 shadow-lg">
                        
                        {{-- Teks berubah tergantung kondisi sidebar --}}
                        <span x-show="sidebarOpen">Ciutkan Menu</span>
                        <span x-show="!sidebarOpen">Luaskan Menu</span>
                        
                        {{-- Panah kecil di atas tooltip (Hiasan) --}}
                        <div class="absolute -top-1 left-1/2 -translate-x-1/2 w-2 h-2 bg-slate-800 rotate-45"></div>
                    </div>
                </button>
                
                <div>
                    {{-- Judul lebih besar --}}
                    <h1 class="text-2xl font-bold text-slate-800 font-heading tracking-tight">
                        {{ $header ?? 'Dashboard' }}
                    </h1>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                 <div class="hidden md:flex flex-col items-end">
                    <span class="text-base font-bold text-slate-800">{{ Auth::user()->name ?? 'Admin' }}</span>
                    
                 </div>
                 
                 {{-- Avatar diperbesar --}}
                 <div class="h-12 w-12 rounded-full bg-gradient-to-br from-sky-400 to-emerald-400 p-0.5 shadow-lg shadow-sky-500/30 cursor-pointer hover:scale-105 transition-transform">
                    <div class="h-full w-full rounded-full bg-white flex items-center justify-center text-sky-600 font-bold text-lg">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                 </div>
            </div>
        </header>

        {{-- MAIN CONTENT --}}

        <main class="flex-1 overflow-y-auto bg-gray-50/50 pt-32 pb-10 px-6 md:px-10 scroll-smooth relative">

            <div class="fixed top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
                <div class="absolute top-[-10%] left-[-10%] w-88 h-88 bg-sky-200/30 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
                <div class="absolute top-[-10%] right-[-10%] w-88 h-88 bg-emerald-200/30 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>
            </div>

            @include('layouts.partials.notification')
            
            @hasSection('content')
                @yield('content')
            @else
                {{ $slot ?? '' }}
            @endif

            @includeWhen(View::exists('layouts.partials.footer'), 'layouts.partials.footer')
        </main>
        
    </div>
</div>

    @livewireScripts
    @stack('scripts')
    
    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
    </style>
</body>
</html>