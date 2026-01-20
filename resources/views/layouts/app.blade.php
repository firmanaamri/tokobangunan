

<!DOCTYPE html>
<html lang="id" class="h-full bg-[#FAF7F2]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard Inventori' }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}?v={{ time() }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon.png') }}?v={{ time() }}">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}?v={{ time() }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}?v={{ time() }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        [x-cloak] { display: none !important; }
        .font-heading { font-family: 'Poppins', sans-serif; }
        .font-body { font-family: 'Inter', sans-serif; }
        
        /* Mobile sidebar */
        @media (max-width: 767px) {
            aside:not(.w-64) {
                transform: translateX(-100%);
            }
        }
    </style>
</head>
<body class="h-screen font-body overflow-x-hidden">

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
}" class="relative min-h-screen md:flex">

    @include('layouts.partials.sidebar')


    <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-40 bg-black opacity-50 md:hidden" @click="toggleSidebar()"></div>

    <div class="flex-1 flex flex-col">
        
        <header class="sticky top-0 z-30 flex items-center justify-between px-6 py-4 bg-white/80 backdrop-blur-md backdrop-saturate-150 border-b border-slate-200/50 shadow-sm">
            
            <div class="flex items-center space-x-4">
                {{-- Toggle button untuk mobile dan desktop --}}
                <button @click="toggleSidebar()" class="text-gray-500 hover:text-gray-700 hover:bg-gray-100 p-2 rounded-md transition-all duration-300 focus:outline-none">
                    <svg x-show="!sidebarOpen" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="sidebarOpen" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                
                <h1 class="text-xl font-semibold text-gray-800 font-heading tracking-tight">
                    {{ $header ?? 'Selamat Datang' }}
                </h1>
            </div>
            
            <div x-data="{ dropdownOpen: false }" class="relative">
                <button  class="">
                    <span class="font-medium">Halo, {{ Auth::user()->name ?? 'Admin' }}</span>
                </button>
                
               
            </div>
        </header>

        <main class="flex-1 p-6 md:p-10 bg-white">
            @include('layouts.partials.notification')
            
            @hasSection('content')
                @yield('content')
            @else
                {{ $slot ?? '' }}
            @endif
        </main>
        
        @includeWhen(View::exists('layouts.partials.footer'), 'layouts.partials.footer')
    </div>
    
</div>

    @stack('scripts')
</body>
</html>